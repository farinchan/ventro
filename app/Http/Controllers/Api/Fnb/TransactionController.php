<?php

namespace App\Http\Controllers\Api\Fnb;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceMail;
use App\Models\FnbBusiness;
use App\Models\FnbCoupon;
use App\Models\FnbOutlet;
use App\Models\FnbProductVariant;
use App\Models\FnbSale;
use App\Models\FnbTax;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function process(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fnb_outlet_staff_id' => 'required|exists:fnb_outlet_staff,id',
            'fnb_costumer_id' => 'nullable|exists:fnb_costumers,id',
            'fnb_payment_method_id' => 'nullable|exists:fnb_payment_methods,id',
            'fnb_table_id' => 'nullable|exists:fnb_tables,id',
            'fnb_coupon_id' => 'nullable|exists:fnb_coupons,id',
            'fnb_sale_items' => 'required|array',
            'fnb_sale_items.*.fnb_product_variant_id' => 'required|exists:fnb_product_variants,id',
            'fnb_sale_items.*.quantity' => 'required|integer|min:1',
            'fnb_sale_items.*.note' => 'nullable|string',
            'taxes' => 'nullable|array',
            'fnb_sale_mode_outlet_id' => 'nullable|exists:fnb_sale_mode_outlets,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'validation' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $outlet = FnbOutlet::find($request->attributes->get('outlet_id'));
            if (! $outlet) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Outlet not found',
                ], Response::HTTP_NOT_FOUND);
            }

            $business = FnbBusiness::find($outlet->fnb_business_id);
            if (! $business) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Business not found',
                ], Response::HTTP_NOT_FOUND);
            }

            $invoiceNumber = collect(preg_split('/[^a-zA-Z]+/', $outlet->name))->filter()->map(fn ($word) => strtoupper($word[0]))->implode('').Carbon::now()->format('Ymd').str_pad(FnbSale::where('fnb_outlet_id', $request->attributes->get('outlet_id'))->count() + 1, 4, '0', STR_PAD_LEFT);

            $sale_items = [];
            foreach ($request->fnb_sale_items as $item) {
                $product = FnbProductVariant::find($item['fnb_product_id']);
                if (! $product) {
                    continue;
                }
                $sale_items[] = [
                    'fnb_product_variant_id' => $item['fnb_product_id'],
                    'quantity' => $item['quantity'],
                    'note' => $item['note'],
                    'unit_price' => $product->price,
                    'total_price' => $product->price * $item['quantity'],
                ];
            }
            $sale_items = collect($sale_items);

            $taxes = [];
            foreach ($request->taxes as $tax) {
                $fnb_tax = FnbTax::find($tax);
                if (! $fnb_tax) {
                    continue;
                }
                $taxes[] = [
                    'name' => $fnb_tax->name,
                    'percent' => $fnb_tax->percent,
                    'amount' => $fnb_tax->amount,
                ];
            }
            $taxes = collect($taxes);

            $coupon = FnbCoupon::find($request->coupon);
            $discount = 0;
            if ($coupon) {
                $discount = $coupon->discount;
            }

            $subtotal = $sale_items->sum('total_price');

            $subtotalWithDiscount = $subtotal - $discount;

            $tax = $subtotalWithDiscount * $taxes->sum('percent') / 100;

            $total = $subtotalWithDiscount + $tax;

            $sale = new FnbSale;
            $sale->fnb_outlet_id = $request->attributes->get('outlet_id');
            $sale->fnb_outlet_staff_id = $request->fnb_outlet_staff_id;
            $sale->fnb_costumer_id = $request->fnb_costumer_id;
            $sale->fnb_payment_method_id = $request->fnb_payment_method_id;
            $sale->fnb_table_id = $request->fnb_table_id;
            $sale->fnb_coupon_id = $request->fnb_coupon_id;
            $sale->invoice_number = $invoiceNumber;
            $sale->subtotal = $subtotal;
            $sale->discount = $discount;
            $sale->taxes = $taxes;
            $sale->total = $total;
            $sale->fnb_sale_mode_outlet_id = $request->fnb_sale_mode_outlet_id;
            $sale->status = 'paid';

            $sale->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil disimpan',
                'data' => $sale,
            ], Response::HTTP_OK);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function sendInvoice(Request $request, $id)
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
