<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - OpenShelf</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; margin: 0; padding: 24px;">
    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; padding: 32px; border: 1px solid #e2e8f0;">
        <h1 style="text-align: center; color: #2C3E50; font-size: 24px; margin-bottom: 8px;">Password Reset Request</h1>

        @if (!empty($userName))
            <p style="text-align: center; color: #64748b; margin-top: 0;">Hi {{ $userName }},</p>
        @endif

        <p style="text-align: center; color: #64748b; line-height: 1.6;">
            We received a request to reset your OpenShelf account password. Use the following verification code to proceed:
        </p>

        <div style="background: #f1f5f9; border: 2px solid #e2e8f0; padding: 25px; text-align: center; font-size: 42px; font-weight: 800; letter-spacing: 10px; color: #4f46e5; border-radius: 16px; margin: 30px 0;">
            {{ $otp }}
        </div>

        <div style="text-align: center; font-size: 14px; color: #64748b; margin-top: -10px; margin-bottom: 30px;">
            This code is valid for <strong>{{ $expiryMinutes }} minutes</strong>.
        </div>

        <p style="text-align: center; font-size: 14px; color: #64748b; line-height: 1.6;">
            If you did not request a password reset, you can safely ignore this email. For your security, never share this code with anyone.
        </p>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('password.forgot') }}" style="display: inline-block; background: #4C9F8A; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 10px; font-weight: 700;">
                Reset My Password
            </a>
        </div>
    </div>
</body>
</html>
