<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
</head>
<body style="margin:0; padding:0; background:#f4f4f7; font-family: Arial, sans-serif;">

  <!-- Wrapper Table -->
  <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background:#f4f4f7; padding:40px 0;">
    <tr>
      <td align="center">

        <!-- Inner Card -->
        <table border="0" cellpadding="0" cellspacing="0" width="600" style="background:#ffffff; border-radius:15px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
          <tr>
            <td align="center" style="padding:40px;">

              <!-- Lock Icon (as image) -->
              <img src="https://cdn-icons-png.flaticon.com/512/3064/3064197.png" alt="Lock Icon" width="60" style="margin-bottom:20px; display:block;">

              <!-- Headings -->
              <h1 style="font-size:28px; font-weight:bold; color:#2C3E50; margin:0;">FORGOT</h1>
              <h2 style="font-size:18px; font-weight:600; color:#2C3E50; margin:10px 0 30px 0;">YOUR PASSWORD?</h2>

              <!-- Description -->
              <p style="font-size:16px; color:#5A6C7D; margin:0 0 30px 0; line-height:1.5;">
                Not to worry, we got you! Click the button below to reset your password.
              </p>

              <!-- Reset Button -->
              <table border="0" cellpadding="0" cellspacing="0" style="margin:30px 0;">
                <tr>
                  <td align="center" bgcolor="#FF6B6B" style="border-radius:30px;">
                    <a href="{{ $resetUrl }}" target="_blank" 
                      style="display:inline-block; padding:15px 30px; font-size:16px; font-weight:bold; color:#ffffff; text-decoration:none; border-radius:30px;">
                      RESET PASSWORD
                    </a>
                  </td>
                </tr>
              </table>

              <!-- Extra Text -->
              <p style="font-size:14px; color:#A0AEC0; margin:20px 0 0 0;">
                If you did not request a password reset, you can safely ignore this email.
              </p>

            </td>
          </tr>
        </table>
        <!-- End Inner Card -->

      </td>
    </tr>
  </table>
  <!-- End Wrapper -->

</body>
</html>
