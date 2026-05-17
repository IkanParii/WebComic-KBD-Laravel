<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Login Publisher</title>
</head>
<body style="margin:0;padding:0;background:#f5f5f7;font-family:Poppins,Segoe UI,Arial,sans-serif;color:#1f1f1f;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:620px;background:#ffffff;border:1px solid #dfd8ff;border-radius:20px;overflow:hidden;">
                    <tr>
                        <td style="background:linear-gradient(135deg,#7B4DFF,#6C63FF);padding:24px 28px;color:#ffffff;">
                            <div style="font-size:13px;opacity:.9;">AuVerse Security</div>
                            <h1 style="margin:8px 0 0;font-size:28px;line-height:1.2;">Verifikasi Login Publisher</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 14px;font-size:15px;line-height:1.7;">Halo <strong>{{ $name }}</strong>,</p>
                            <p style="margin:0 0 18px;font-size:15px;line-height:1.7;">Gunakan kode OTP di bawah ini untuk melanjutkan login Publisher.</p>

                            <div style="margin:0 0 18px;padding:16px;border:1px dashed #b9a6ff;border-radius:14px;background:#f7f3ff;text-align:center;">
                                <div style="font-size:30px;letter-spacing:8px;font-weight:700;color:#5f44e6;">{{ $otp }}</div>
                                <div style="margin-top:6px;font-size:12px;color:#6b7280;">Berlaku sampai {{ $expiresAt }} ({{ $timezone }})</div>
                            </div>

                            <p style="margin:0 0 10px;font-size:13px;line-height:1.7;color:#4b5563;">Jika Anda tidak merasa meminta OTP ini, abaikan email ini dan segera ubah password akun Anda.</p>
                            <p style="margin:0;font-size:12px;color:#9ca3af;">Email ini dikirim otomatis, mohon tidak membalas.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
