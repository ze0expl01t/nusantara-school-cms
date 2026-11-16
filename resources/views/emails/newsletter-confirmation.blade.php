<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Newsletter</title>
</head>
<body style="margin: 0; padding: 0; background: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; color: #334155;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background: #f8fafc; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">

                    <tr>
                        <td style="background: linear-gradient(135deg, #10b981 0%, #0d9488 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">
                                {{ config('app.name') }}
                            </h1>
                            <p style="margin: 8px 0 0 0; color: rgba(255,255,255,0.9); font-size: 14px;">
                                Konfirmasi Langganan Newsletter
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="margin: 0 0 16px 0; color: #0f172a; font-size: 20px; font-weight: 700;">
                                Halo{{ $subscriber->name ? ' ' . $subscriber->name : '' }}!
                            </h2>

                            <p style="margin: 0 0 16px 0; color: #475569; font-size: 15px; line-height: 1.6;">
                                Terima kasih telah mendaftar di newsletter <strong>{{ config('app.name') }}</strong>.
                                Untuk mengaktifkan langganan Anda, silakan klik tombol di bawah ini:
                            </p>

                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin: 32px 0;">
                                <tr>
                                    <td align="center" style="background: #10b981; border-radius: 999px;">
                                        <a href="{{ $verifyUrl }}"
                                           style="display: inline-block; padding: 14px 40px; color: #ffffff; text-decoration: none; font-weight: 700; font-size: 15px;">
                                            Konfirmasi Langganan
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 8px 0; color: #64748b; font-size: 13px; line-height: 1.6;">
                                Atau copy-paste link berikut ke browser Anda:
                            </p>
                            <p style="margin: 0 0 24px 0; padding: 12px; background: #f1f5f9; border-radius: 8px; word-break: break-all; font-size: 12px; color: #475569; font-family: monospace;">
                                {{ $verifyUrl }}
                            </p>

                            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 24px 0;">

                            <p style="margin: 0; color: #94a3b8; font-size: 12px; line-height: 1.6;">
                                Jika Anda tidak merasa mendaftar newsletter, abaikan email ini.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="background: #f8fafc; padding: 24px 30px; text-align: center;">
                            <p style="margin: 0 0 8px 0; color: #94a3b8; font-size: 12px;">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                            <p style="margin: 0; color: #94a3b8; font-size: 11px;">
                                Tidak ingin menerima email lagi?
                                <a href="{{ $unsubscribeUrl }}" style="color: #10b981; text-decoration: underline;">
                                    Berhenti berlangganan
                                </a>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
