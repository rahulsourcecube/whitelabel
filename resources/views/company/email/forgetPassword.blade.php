<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #F4F4F4;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 600px;
      margin: 20px auto;
      background-color: #FFFFFF;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
      color: #333333;
    }
    p {
      color: #555555;
    }
    .cta-button {
      display: inline-block;
      padding: 10px 20px;
      text-decoration: none;
      background-color: #4CAF50;
      color: #FFFFFF;
      border-radius: 4px;
    }
    .footer {
      margin-top: 20px;
      color: #777777;
      font-size: 12px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Forgot Your Password?</h1>
    <p>Hi {{ $email}},</p>
    <p>We received a request to reset the password associated with this email address. If you made this request, please click the button below to reset your password:</p>
    <a href="{{ route('company.confirmPassword', $token) }}" class="cta-button">Reset Password</a>
    <br><br><br>
    <p>If you did not request to reset your password, please ignore this email. Your password will remain unchanged.</p>
    <p>If you have any questions or need assistance, feel free to contact our support team.</p>
  </div>
</body>
</html>
