<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Credentials</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333333; background-color: #f8f9fa;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f9fa; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); overflow: hidden;">
                    <!-- Header with Logo -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 600; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Welcome!</h1>
                            <p style="margin: 10px 0 0 0; color: #e8eaf6; font-size: 16px; opacity: 0.9;">Your account has been created successfully</p>
                        </td>
                    </tr>
                    
                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #333333;">Hello,</p>
                            
                            <p style="margin: 0 0 30px 0; font-size: 16px; color: #555555; line-height: 1.6;">
                                Your user account has been successfully created and is ready to use. Below are your login credentials:
                            </p>
                            
                            <!-- Credentials Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 8px; border: 1px solid #dee2e6; margin: 30px 0;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <h3 style="margin: 0 0 20px 0; color: #2c3e50; font-size: 18px; font-weight: 600; display: flex; align-items: center;">
                                            <span style="background-color: #007bff; color: white; width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px; font-size: 12px;">🔑</span>
                                            Login Credentials
                                        </h3>
                                        
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding: 12px 0; border-bottom: 1px solid #dee2e6;">
                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td style="width: 30%; font-weight: 600; color: #495057; font-size: 14px; vertical-align: top; padding-right: 15px;">
                                                                Email:
                                                            </td>
                                                            <td style="font-family: 'Courier New', monospace; background-color: #ffffff; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da; font-size: 14px; color: #212529; word-break: break-all;">
                                                                {{ $email }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 12px 0;">
                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td style="width: 30%; font-weight: 600; color: #495057; font-size: 14px; vertical-align: top; padding-right: 15px;">
                                                                Password:
                                                            </td>
                                                            <td style="font-family: 'Courier New', monospace; background-color: #ffffff; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da; font-size: 14px; color: #212529; letter-spacing: 1px;">
                                                                {{ $password }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Security Notice -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff3cd; border-radius: 6px; border-left: 4px solid #ffc107; margin: 30px 0;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.5;">
                                            <strong style="display: flex; align-items: center; margin-bottom: 8px;">
                                                <span style="margin-right: 8px; font-size: 16px;">⚠️</span>
                                                Security Notice
                                            </strong>
                                            For your security, please change your password after your first login. Keep your credentials confidential and do not share them with anyone.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 30px 0 20px 0; font-size: 16px; color: #555555; line-height: 1.6;">
                                You can now use these credentials to log into your account. If you have any questions or need assistance, please contact our support team.
                            </p>
                            
                            <!-- CTA Button -->
                            <table cellpadding="0" cellspacing="0" style="margin: 30px 0;">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); border-radius: 6px; text-align: center;">
                                        <a href="{{ url('/login') }}" style="display: inline-block; padding: 12px 30px; color: #ffffff; text-decoration: none; font-weight: 600; font-size: 16px;">
                                            Access Your Account
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 30px; text-align: center; border-top: 1px solid #dee2e6;">
                            <p style="margin: 0 0 15px 0; color: #6c757d; font-size: 16px; font-weight: 500;">
                                Best regards,<br>
                                <strong style="color: #495057;">The Team</strong>
                            </p>
                            
                            <p style="margin: 0; color: #adb5bd; font-size: 12px; line-height: 1.4;">
                                This is an automated message. Please do not reply to this email.<br>
                                © {{ date('Y') }} Company Name. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>