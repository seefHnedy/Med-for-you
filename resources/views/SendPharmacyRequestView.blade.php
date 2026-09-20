<!DOCTYPE html>
<html>
<head>
    <title>Medicine Donation</title>
</head>
<body>
<div style="direction:rtl;margin:0;padding:24px;background:#f6f8fb;font-family:Arial,sans-serif;color:#111827">
    <div style="max-width:520px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:12px;padding:24px">
        <h2 style="text-align:center;margin:0 0 16px;font-size:22px;color: #4374e0;">Medicine Donation</h2>
        <p style="margin:0 0 16px;font-size:16px;line-height:1.7">مرحباً،</p>
        <p style="margin:0 0 16px;font-size:16px;line-height:1.7">
            رمز التحقق الخاص بك للاستفادة من الأدوية التالية هو:
        </p>
        <div style="margin:0 0 20px;padding:16px 20px;background: #ededed;border-radius:10px;font-size:32px;font-weight:700;letter-spacing:4px;text-align:center;color: #4374e0;">
            {{$otp}}
        </div>

        <!-- جدول الأدوية -->
        <table style="width:100%;border-collapse:collapse;margin:20px 0;font-size:14px;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
            <thead>
            <tr style="background:#4374e0;color:#ffffff;">
                <th style="padding:10px 12px;text-align:center;border:1px solid #e5e7eb;">اسم الدواء</th>
                <th style="padding:10px 12px;text-align:center;border:1px solid #e5e7eb;">الكمية</th>
                <th style="padding:10px 12px;text-align:center;border:1px solid #e5e7eb;">السعر الفردي</th>
                <th style="padding:10px 12px;text-align:center;border:1px solid #e5e7eb;">السعر الإجمالي</th>
            </tr>
            </thead>
            <tbody>
            @foreach($medicines as $medicine)
                <tr style="background: {{ $loop->even ? '#f9fafb' : '#ffffff' }};">
                    <td style="padding:10px 12px;text-align:center;border:1px solid #e5e7eb;">{{ $medicine['name'] ?? $medicine->name ?? '' }}</td>
                    <td style="padding:10px 12px;text-align:center;border:1px solid #e5e7eb;">{{ $medicine['quantity'] ?? $medicine->quantity ?? $medicine['qty'] ?? '' }}</td>
                    <td style="padding:10px 12px;text-align:center;border:1px solid #e5e7eb;">{{ number_format($medicine['price'] ?? $medicine->price ?? 0, 2) }}</td>
                    <td style="padding:10px 12px;text-align:center;border:1px solid #e5e7eb;font-weight:bold;color:#4374e0;">
                        {{ number_format(($medicine['quantity'] ?? $medicine->quantity ?? $medicine['qty'] ?? 0) * ($medicine['price'] ?? $medicine->price ?? 0), 2) }}
                    </td>
                </tr>
            @endforeach
            </tbody>
            @if(isset($medicines) && count($medicines) > 0)
                <tfoot>
                <tr style="background:#f3f4f6;font-weight:bold;">
                    <td colspan="3" style="padding:10px 12px;text-align:left;border:1px solid #e5e7eb;">المجموع الكلي</td>
                    <td style="padding:10px 12px;text-align:center;border:1px solid #e5e7eb;color:#4374e0;font-size:16px;">
                        {{ number_format(collect($medicines)->sum(function($item) {
                            $qty = $item['quantity'] ?? $item->quantity ?? $item['qty'] ?? 0;
                            $price = $item['price'] ?? $item->price ?? 0;
                            return $qty * $price;
                        }), 2) }}
                    </td>
                </tr>
                </tfoot>
            @endif
        </table>

        <p style="margin:0 0 12px;font-size:15px;line-height:1.7;">
            تنتهي صلاحية هذا الرمز خلال 10 دقائق.
        </p>
        <p style="margin:0;font-size:14px;line-height:1.7;color:#4b5563;">
            إذا لم تطلب هذا الرمز، يمكنك تجاهل هذه الرسالة.
        </p>
    </div>
</div>
</body>
</html>
