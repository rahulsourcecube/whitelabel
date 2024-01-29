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
        <h1>Welcome White Lable</h1>
        <p>Hi @if(!empty($userName)) {{ $userName }} @endif,</p>
        <p>Congratulations! I would like to extend a warm welcome to you behalf of the Company</p>
    </div>
</body>
</html>
