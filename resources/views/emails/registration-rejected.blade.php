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
                                <span style="display: inline-block; background-color: rgba(239, 68, 68, 0.15); color: #ef4444; font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; padding: 6px 16px; border-radius: 20px; border: 1px solid rgba(239, 68, 68, 0.3);">
                                    NOT APPROVED
                                </span>
                            </div>

                            {{-- Greeting --}}
                            <h1 style="color: #FFFFFF; font-size: 24px; font-weight: 700; margin: 0 0 10px 0; text-align: center;">
                                Hi {{ $registration->full_name }},
                            </h1>
                            <p style="color: #999999; font-size: 14px; line-height: 1.8; text-align: center; margin: 0 0 30px 0;">
                                Thank you for your interest in joining the Thynker Ecosystem. After reviewing your application, we're unable to approve it at this time.
                            </p>

                            {{-- Reasons Box --}}
                            <div style="background-color: #0A0A0A; border: 1px solid #2A2A2A; border-radius: 12px; padding: 24px; margin-bottom: 30px;">
                                <span style="color: #666666; font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">Common Reasons</span>
                                <ul style="color: #999999; font-size: 13px; line-height: 2; margin: 12px 0 0 0; padding-left: 20px;">
                                    <li>Follower count below minimum requirement</li>
                                    <li>Incomplete social media profile</li>
                                    <li>Content niche not aligned with current campaigns</li>
                                </ul>
                            </div>

                            {{-- Encouragement --}}
                            <div style="background-color: rgba(204, 255, 0, 0.05); border: 1px solid rgba(204, 255, 0, 0.15); border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 24px;">
                                <p style="color: #CCFF00; font-size: 13px; font-weight: 600; margin: 0 0 6px 0;">
                                    Don't give up!
                                </p>
                                <p style="color: #999999; font-size: 12px; margin: 0; line-height: 1.5;">
                                    Keep growing your audience and feel free to reapply in the future. We'd love to have you on board when the time is right.
                                </p>
                            </div>

                            {{-- CTA Button --}}
                            <div style="text-align: center;">
                                <a href="{{ url('/') }}" style="display: inline-block; background-color: transparent; color: #CCFF00; font-size: 13px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; text-decoration: none; padding: 14px 40px; border-radius: 30px; border: 1px solid rgba(204, 255, 0, 0.4);">
                                    Visit Our Website
                                </a>
                            </div>
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
