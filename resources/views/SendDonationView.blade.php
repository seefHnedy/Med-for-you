<!DOCTYPE html>
<html>
<head>
    <title>Your OTP Code</title>
</head>
<body>
<div style="direction:rtl;margin:0;padding:24px;background:#f6f8fb;font-family:Arial,sans-serif;color:#111827">
    <div style="max-width:520px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:12px;padding:24px">
        <h2 style="text-align:center;margin:0 0 16px;font-size:22px;color: #4374e0;">Medicine Donation</h2>
        <p style="margin:0 0 16px;font-size:16px;line-height:1.7">مرحباً،</p>
        <p style="margin:0 0 16px;font-size:16px;line-height:1.7">
            رمز التحقق الخاص بك للتبرع التالي هو:
        </p>
        <div style="margin:0 0 20px;padding:16px 20px;background: #ededed;border-radius:10px;font-size:32px;font-weight:700;letter-spacing:4px;text-align:center;color: #4374e0;">
            {{$otp}}
        </div>

        <table style="width:100%;border-collapse:collapse;margin:20px 0;font-size:14px;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
            <thead>
            <tr style="background:#4374e0;color:#ffffff;">
                <th style="padding:10px 12px;text-align:center;border:1px solid #e5e7eb;">اسم الدواء</th>
                <th style="padding:10px 12px;text-align:center;border:1px solid #e5e7eb;">الكمية</th>
                <th style="padding:10px 12px;text-align:center;border:1px solid #e5e7eb;">السعر الإفرادي</th>
                <th style="padding:10px 12px;text-align:center;border:1px solid #e5e7eb;">السعر الإجمالي</th>
            </tr>
            </thead>
            <tbody>
            <tr style="background:#f9fafb;">
                <td style="padding:10px 12px;text-align:center;border:1px solid #e5e7eb;">{{$medicineName}}</td>
                <td style="padding:10px 12px;text-align:center;border:1px solid #e5e7eb;">{{$medicineQty}}</td>
                <td style="padding:10px 12px;text-align:center;border:1px solid #e5e7eb;">{{number_format($medicinePrice, 2)}}</td>
                <td style="padding:10px 12px;text-align:center;border:1px solid #e5e7eb;font-weight:bold;color:#4374e0;">{{number_format($medicineQty * $medicinePrice, 2)}}</td>
            </tr>
            </tbody>
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
