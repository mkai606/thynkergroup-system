<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; background-color: #0A0A0A; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #0A0A0A; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%;">
                    {{-- Header --}}
                    <tr>
                        <td style="text-align: center; padding-bottom: 30px;">
                            <span style="font-size: 28px; font-weight: 900; letter-spacing: 4px; color: #CCFF00;">THYNKER</span>
                            <br>
                            <span style="font-size: 11px; letter-spacing: 3px; color: #666666; text-transform: uppercase;">Thynker Groups</span>
                        </td>
                    </tr>

                    {{-- Main Card --}}
                    <tr>
                        <td style="background-color: #1A1A1A; border: 1px solid #2A2A2A; border-radius: 16px; padding: 40px;">
                            {{-- Status Badge --}}
                            <div style="text-align: center; margin-bottom: 30px;">
                                <span style="display: inline-block; background-color: rgba(34, 197, 94, 0.15); color: #22c55e; font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; padding: 6px 16px; border-radius: 20px; border: 1px solid rgba(34, 197, 94, 0.3);">
                                    APPROVED
                                </span>
                            </div>

                            {{-- Greeting --}}
                            <h1 style="color: #FFFFFF; font-size: 24px; font-weight: 700; margin: 0 0 10px 0; text-align: center;">
                                Welcome aboard, {{ $registration->full_name }}!
                            </h1>
                            <p style="color: #999999; font-size: 14px; line-height: 1.6; text-align: center; margin: 0 0 30px 0;">
                                Your application to join the Thynker Ecosystem has been reviewed and approved.
                            </p>

                            {{-- Tier Card --}}
                            <div style="background-color: #0A0A0A; border: 1px solid #2A2A2A; border-radius: 12px; padding: 24px; text-align: center; margin-bottom: 30px;">
                                <span style="color: #666666; font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">Your Assigned Tier</span>
                                <div style="color: #CCFF00; font-size: 48px; font-weight: 900; letter-spacing: 4px; margin: 8px 0;">
                                    TIER {{ $tier }}
                                </div>
                            </div>

                            {{-- Login Details --}}
                            <div style="background-color: #0A0A0A; border: 1px solid #2A2A2A; border-radius: 12px; padding: 24px; margin-bottom: 30px;">
                                <span style="color: #CCFF00; font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">Your Login Credentials</span>
                                <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 16px;">
                                    <tr>
                                        <td style="color: #666666; font-size: 13px; padding: 8px 0; border-bottom: 1px solid #1A1A1A;">Email</td>
                                        <td style="color: #FFFFFF; font-size: 13px; padding: 8px 0; border-bottom: 1px solid #1A1A1A; text-align: right; font-family: monospace;">{{ $registration->email }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #666666; font-size: 13px; padding: 8px 0;">Password</td>
                                        <td style="color: #FFFFFF; font-size: 13px; padding: 8px 0; text-align: right; font-family: monospace;">sidekick123</td>
                                    </tr>
                                </table>
                            </div>

                            {{-- CTA Button --}}
                            <div style="text-align: center; margin-bottom: 24px;">
                                <a href="{{ url('/login') }}" style="display: inline-block; background-color: #CCFF00; color: #0A0A0A; font-size: 13px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; text-decoration: none; padding: 14px 40px; border-radius: 30px;">
                                    Login to Dashboard
                                </a>
                            </div>

                            {{-- Security Note --}}
                            <p style="color: #666666; font-size: 11px; text-align: center; margin: 0; line-height: 1.5;">
                                For security, please change your password after your first login.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="text-align: center; padding-top: 30px;">
                            <p style="color: #444444; font-size: 11px; margin: 0;">
                                &copy; {{ date('Y') }} Thynker Ecosystem. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
