<?php

namespace App\Http\Controllers\Api\Fnb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Fnb\ProcessTransactionRequest;
use App\Http\Resources\Fnb\SaleResource;
use App\Mail\InvoiceMail;
use App\Models\FnbCostumer;
use App\Models\FnbCoupon;
use App\Models\FnbOutlet;
use App\Models\FnbProductVariant;
use App\Models\FnbSale;
use App\Models\FnbTax;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TransactionController extends Controller
{
    public function process(ProcessTransactionRequest $request): JsonResponse
    {
        try {

            $outletId = $request->attributes->get('outlet_id');

            $outlet = FnbOutlet::find($outletId);
            if (! $outlet) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Outlet not found',
                ], Response::HTTP_NOT_FOUND);
            }

            // Batch-load all requested product variants to avoid N+1
            $variantIds = collect($request->fnb_sale_items)->pluck('fnb_product_variant_id');
            $variants = FnbProductVariant::whereIn('id', $variantIds)->get()->keyBy('id');

            $saleItems = collect($request->fnb_sale_items)
                ->filter(fn (array $item): bool => $variants->has($item['fnb_product_variant_id']))
                ->map(function (array $item) use ($variants): array {
                    $variant = $variants->get($item['fnb_product_variant_id']);

                    return [
                        'fnb_product_variant_id' => $item['fnb_product_variant_id'],
                        'quantity' => $item['quantity'],
                        'note' => $item['note'] ?? null,
                        'unit_price' => round((float) $variant->price, 2),
                        'total_price' => round((float) $variant->price * $item['quantity'], 2),
                    ];
                });

            if ($saleItems->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada item valid dalam transaksi',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Resolve coupon discount
            $discount = 0.00;
            if ($request->filled('fnb_coupon_id')) {
                $coupon = FnbCoupon::find($request->fnb_coupon_id);
                if ($coupon) {
                    $discount = round((float) $coupon->value, 2);
                }
            }

            $subtotal = round((float) $saleItems->sum('total_price'), 2);
            $subtotalWithDiscount = round(max($subtotal - $discount, 0), 2);

            // Batch-load taxes and calculate each tax amount from percent
            $taxes = collect();
            if ($request->filled('taxes')) {
                $taxes = FnbTax::whereIn('id', $request->taxes)->get()->map(fn (FnbTax $tax): array => [
                    'name' => $tax->name,
                    'percent' => $tax->percent,
                    'amount' => round($subtotalWithDiscount * $tax->percent / 100, 2),
                ]);
            }

            $taxAmount = round((float) $taxes->sum('amount'), 2);
            $total = round($subtotalWithDiscount + $taxAmount, 2);

            $fnbCostumerId = null;
            if ($request->customer_name) {
                $user = User::where('phone', $request->customer_phone)->first();
                $userId = null;
                $userEmail = null;
                if ($user && $request->customer_phone != null && $request->customer_phone != '') {
                    $userId = $user->id;
                    $userEmail = $user->email;
                }
                $customer = FnbCostumer::create([
                    'fnb_outlet_id' => $outletId,
                    'name' => $request->customer_name,
                    'phone' => $request->customer_phone,
                    'user_id' => $userId,
                    'email' => $userEmail,
                ]);
                $fnbCostumerId = $customer->id;

            }

            $paidAmount = null;
            $changeAmount = null;
            $status = 'pending';
            if($request->payment_method == 'cash'){
              $paidAmount = $request->paid_amount;
              $changeAmount = $request->paid_amount - $total;
              $status = 'paid';
            }elseif($request->payment_method == 'qris'){
              $paidAmount = $total;
              $changeAmount = 0;
            }else{
              $paidAmount = $total;
              $changeAmount = 0;
            }


            $sale = DB::transaction(function () use ($request, $outlet, $outletId, $saleItems, $taxes, $discount, $subtotal, $total, $fnbCostumerId, $paidAmount, $changeAmount, $status): FnbSale {
                // Generate invoice number inside transaction to prevent race conditions
                $prefix = collect(preg_split('/[^a-zA-Z]+/', $outlet->name))
                    ->filter()
                    ->map(fn (string $word): string => strtoupper($word[0]))
                    ->implode('');

                $sequence = FnbSale::where('fnb_outlet_id', $outletId)
                    ->lockForUpdate()
                    ->count() + 1;

                $invoiceNumber = $prefix.Carbon::now()->format('Ymd').str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);

                $sale = FnbSale::query()->create([
                    'fnb_outlet_id' => $outletId,
                    'fnb_outlet_staff_id' => $request->fnb_outlet_staff_id,
                    'fnb_costumer_id' => $fnbCostumerId,
                    'fnb_table_id' => $request->fnb_table_id,
                    'fnb_coupon_id' => $request->fnb_coupon_id,
                    'invoice_number' => $invoiceNumber,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'taxes' => $taxes->toArray(),
                    'total' => $total,
                    'payment_method' => $request->payment_method,
                    'fnb_sale_mode_outlet_id' => $request->fnb_sale_mode_outlet_id,
                    'paid_amount'=> $paidAmount,
                    'change_amount'=> $changeAmount,
                    'status'=> $status,
                ]);

                // Persist sale items
                $sale->items()->createMany($saleItems->toArray());

                return $sale;
            });

            $sale->load([
                'outlet.business',
                'staff.user',
                'costumer',
                'table',
                'coupon',
                'saleModeOutlet.saleMode',
                'items.productVariant.product',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil disimpan',
                'data' => SaleResource::make($sale),
            ], Response::HTTP_OK);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function sendInvoice(Request $request, string $id): JsonResponse
    {
        try {
            $sale = FnbSale::with(['outlet.business', 'costumer', 'items.productVariant.product'])
                ->where('fnb_outlet_id', $request->attributes->get('outlet_id'))
                ->find($id);

            if (! $sale) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaksi tidak ditemukan',
                ], Response::HTTP_NOT_FOUND);
            }

            $email = $request->input('email', $sale->costumer?->email);

            if (! $email) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email penerima tidak tersedia',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            Mail::to($email)->queue(new InvoiceMail($sale));

            return response()->json([
                'status' => 'success',
                'message' => 'Invoice berhasil dikirim ke '.$email,
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
