<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $sale->invoice_number }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f4f6f9; padding: 40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">

                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); padding: 40px 40px 32px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>
                                        <h1 style="margin: 0 0 4px; font-size: 28px; font-weight: 700; color: #ffffff; letter-spacing: -0.5px;">INVOICE</h1>
                                        <p style="margin: 0; font-size: 14px; color: #94a3b8;">#{{ $sale->invoice_number }}</p>
                                    </td>
                                    <td align="right" valign="top">
                                        <table role="presentation" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="background-color: rgba(255,255,255,0.12); border-radius: 8px; padding: 10px 16px;">
                                                    <span style="font-size: 12px; font-weight: 600; color: #4ade80; text-transform: uppercase; letter-spacing: 1px;">
                                                        {{ strtoupper($sale->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Info Section --}}
                    <tr>
                        <td style="padding: 32px 40px 24px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="50%" valign="top">
                                        <p style="margin: 0 0 4px; font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Outlet</p>
                                        <p style="margin: 0 0 16px; font-size: 15px; font-weight: 600; color: #1e293b;">{{ $sale->outlet->name ?? '-' }}</p>

                                        <p style="margin: 0 0 4px; font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Tanggal</p>
                                        <p style="margin: 0; font-size: 15px; color: #475569;">{{ $sale->created_at->format('d M Y, H:i') }}</p>
                                    </td>
                                    <td width="50%" valign="top" align="right">
                                        @if($sale->costumer)
                                            <p style="margin: 0 0 4px; font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Pelanggan</p>
                                            <p style="margin: 0 0 4px; font-size: 15px; font-weight: 600; color: #1e293b;">{{ $sale->costumer->name }}</p>
                                            <p style="margin: 0; font-size: 13px; color: #64748b;">{{ $sale->costumer->email }}</p>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Divider --}}
                    <tr>
                        <td style="padding: 0 40px;">
                            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 0;">
                        </td>
                    </tr>

                    {{-- Items Header --}}
                    <tr>
                        <td style="padding: 24px 40px 12px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; padding-bottom: 12px; border-bottom: 2px solid #f1f5f9;">Item</td>
                                    <td width="60" align="center" style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; padding-bottom: 12px; border-bottom: 2px solid #f1f5f9;">Qty</td>
                                    <td width="120" align="right" style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; padding-bottom: 12px; border-bottom: 2px solid #f1f5f9;">Harga</td>
                                    <td width="120" align="right" style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; padding-bottom: 12px; border-bottom: 2px solid #f1f5f9;">Total</td>
                                </tr>

                                {{-- Items --}}
                                @foreach($sale->items as $item)
                                <tr>
                                    <td style="padding: 14px 0; border-bottom: 1px solid #f1f5f9;">
                                        <p style="margin: 0; font-size: 14px; font-weight: 600; color: #1e293b;">{{ $item->productVariant->product->name ?? 'Product' }}</p>
                                        <p style="margin: 2px 0 0; font-size: 12px; color: #94a3b8;">{{ $item->productVariant->name ?? '' }}</p>
                                        @if($item->note)
                                            <p style="margin: 4px 0 0; font-size: 12px; color: #f59e0b; font-style: italic;">📝 {{ $item->note }}</p>
                                        @endif
                                    </td>
                                    <td align="center" style="padding: 14px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #475569;">{{ $item->quantity }}</td>
                                    <td align="right" style="padding: 14px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #475569;">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td align="right" style="padding: 14px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; font-weight: 600; color: #1e293b;">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>

                    {{-- Summary --}}
                    <tr>
                        <td style="padding: 24px 40px 8px;">
                            <table role="presentation" width="280" cellspacing="0" cellpadding="0" align="right">
                                {{-- Subtotal --}}
                                <tr>
                                    <td style="padding: 6px 0; font-size: 14px; color: #64748b;">Subtotal</td>
                                    <td align="right" style="padding: 6px 0; font-size: 14px; color: #1e293b;">Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</td>
                                </tr>

                                {{-- Discount --}}
                                @if($sale->discount > 0)
                                <tr>
                                    <td style="padding: 6px 0; font-size: 14px; color: #ef4444;">Diskon</td>
                                    <td align="right" style="padding: 6px 0; font-size: 14px; color: #ef4444;">- Rp {{ number_format($sale->discount, 0, ',', '.') }}</td>
                                </tr>
                                @endif

                                {{-- Taxes --}}
                                @if($sale->taxes)
                                    @foreach($sale->taxes as $tax)
                                    <tr>
                                        <td style="padding: 6px 0; font-size: 14px; color: #64748b;">{{ $tax['name'] }} ({{ $tax['percent'] }}%)</td>
                                        <td align="right" style="padding: 6px 0; font-size: 14px; color: #1e293b;">
                                            Rp {{ number_format(($sale->subtotal - $sale->discount) * $tax['percent'] / 100, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif

                                {{-- Total Divider --}}
                                <tr>
                                    <td colspan="2" style="padding: 12px 0 0;">
                                        <hr style="border: none; border-top: 2px solid #e2e8f0; margin: 0;">
                                    </td>
                                </tr>

                                {{-- Grand Total --}}
                                <tr>
                                    <td style="padding: 16px 0 0; font-size: 16px; font-weight: 700; color: #1e293b;">TOTAL</td>
                                    <td align="right" style="padding: 16px 0 0; font-size: 20px; font-weight: 800; color: #0f3460;">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding: 40px 40px 32px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f8fafc; border-radius: 12px; padding: 24px;">
                                <tr>
                                    <td align="center">
                                        <p style="margin: 0 0 4px; font-size: 15px; font-weight: 600; color: #1e293b;">Terima kasih atas kunjungan Anda! 🙏</p>
                                        <p style="margin: 0; font-size: 13px; color: #94a3b8;">Kami senang bisa melayani Anda.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Copyright --}}
                    <tr>
                        <td style="padding: 0 40px 32px;" align="center">
                            <p style="margin: 0; font-size: 12px; color: #cbd5e1;">
                                &copy; {{ date('Y') }} {{ $sale->outlet->business->name ?? config('app.name') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
