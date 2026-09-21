<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alert Notification</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6;">
    
    <!-- Wrapper -->
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f3f4f6; padding: 30px 0;">
        <tr>
            <td align="center">
                
                <!-- Main Container -->
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); padding: 30px; text-align: center;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <div style="width: 60px; height: 60px; background-color: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 15px; line-height: 60px; font-size: 32px;">
                                            {{ $alertData['level'] === 'critical' ? '🚨' : '⚠️' }}
                                        </div>
                                        <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: bold; letter-spacing: 0.5px;">
                                            ALERT TRIGGERED
                                        </h1>
                                        <p style="margin: 8px 0 0; color: rgba(255,255,255,0.9); font-size: 14px;">
                                            Immediate attention required
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                     <!-- TAMBAHKAN BANNER INI -->
                            @if($isReminder ?? false)
                            <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                                <p style="margin: 0; color: #92400e; font-size: 14px; font-weight: bold;">
                                    🔔 This is an automated reminder. This alert has not been resolved yet. Please take immediate action.
                                </p>
                            </div>
                            @endif
                            <!-- AKHIR TAMBAHAN -->
                            <p style="margin: 0 0 20px; color: #374151; font-size: 15px; line-height: 1.6;">
                                An abnormal condition has been detected in your assigned laboratory. Please review the details below and take immediate action.
                            </p>

                            <!-- Alert Details Table -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb; margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        
                                        <!-- Location -->
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 15px;">
                                            <tr>
                                                <td width="40" style="color: #6b7280; font-size: 13px; font-weight: bold; vertical-align: top;">📍</td>
                                                <td style="color: #6b7280; font-size: 13px; font-weight: bold; vertical-align: top; padding-right: 10px;">Location</td>
                                                <td style="color: #111827; font-size: 14px; font-weight: 600; vertical-align: top;">{{ $alertData['location'] }}</td>
                                            </tr>
                                        </table>

                                        <!-- Device ID -->
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 15px;">
                                            <tr>
                                                <td width="40" style="color: #6b7280; font-size: 13px; font-weight: bold; vertical-align: top;">🔌</td>
                                                <td style="color: #6b7280; font-size: 13px; font-weight: bold; vertical-align: top; padding-right: 10px;">Device</td>
                                                <td style="color: #111827; font-size: 14px; font-weight: 600; vertical-align: top;">{{ $alertData['deviceId'] }}</td>
                                            </tr>
                                        </table>

                                        <!-- Parameter -->
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 15px;">
                                            <tr>
                                                <td width="40" style="color: #6b7280; font-size: 13px; font-weight: bold; vertical-align: top;">📊</td>
                                                <td style="color: #6b7280; font-size: 13px; font-weight: bold; vertical-align: top; padding-right: 10px;">Parameter</td>
                                                <td style="color: #111827; font-size: 14px; font-weight: 600; vertical-align: top; text-transform: uppercase;">{{ $alertData['parameter'] }}</td>
                                            </tr>
                                        </table>

                                        <!-- Triggered Value (HIGHLIGHTED) -->
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 15px; background-color: {{ $alertData['level'] === 'critical' ? '#fef2f2' : '#fffbeb' }}; border-radius: 6px; padding: 12px;">
                                            <tr>
                                                <td width="40" style="color: #6b7280; font-size: 13px; font-weight: bold; vertical-align: top; padding: 12px;">🔥</td>
                                                <td style="color: #6b7280; font-size: 13px; font-weight: bold; vertical-align: top; padding: 12px 10px 12px 0;">Value</td>
                                                <td style="color: {{ $alertData['level'] === 'critical' ? '#dc2626' : '#d97706' }}; font-size: 20px; font-weight: 800; vertical-align: top; padding: 12px;">
                                                    {{ $alertData['value'] }}{{ $alertData['parameter'] === 'humidity' ? '%' : '°C' }}
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Level Badge -->
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 15px;">
                                            <tr>
                                                <td width="40" style="color: #6b7280; font-size: 13px; font-weight: bold; vertical-align: top;">⚡</td>
                                                <td style="color: #6b7280; font-size: 13px; font-weight: bold; vertical-align: top; padding-right: 10px;">Severity</td>
                                                <td style="vertical-align: top;">
                                                    <span style="display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #ffffff; background-color: {{ $alertData['level'] === 'critical' ? '#dc2626' : '#f59e0b' }};">
                                                        {{ $alertData['level'] }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Time -->
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td width="40" style="color: #6b7280; font-size: 13px; font-weight: bold; vertical-align: top;">🕐</td>
                                                <td style="color: #6b7280; font-size: 13px; font-weight: bold; vertical-align: top; padding-right: 10px;">Time</td>
                                                <td style="color: #111827; font-size: 14px; font-weight: 600; vertical-align: top;">{{ $alertData['time'] }}</td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                            </table>

                            <!-- Call to Action -->
                            <p style="margin: 0 0 20px; color: #374151; font-size: 14px; line-height: 1.6;">
                                Please log in to the Lab Monitoring System to acknowledge and resolve this issue immediately.
                            </p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/login') }}" style="display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px; letter-spacing: 0.3px; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);">
                                            🔐 Go to Dashboard
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 20px 40px; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0; color: #6b7280; font-size: 11px; text-align: center; line-height: 1.5;">
                                This is an automated message from the <strong>Lab Monitoring System</strong>.<br>
                                Please do not reply to this email. For support, contact the system administrator.
                            </p>
                            <p style="margin: 10px 0 0; color: #9ca3af; font-size: 10px; text-align: center;">
                                © {{ date('Y') }} Philips Lab Monitoring. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
                <!-- End Main Container -->

            </td>
        </tr>
    </table>
    <!-- End Wrapper -->    

</body>
</html>