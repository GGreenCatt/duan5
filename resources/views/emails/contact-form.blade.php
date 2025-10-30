<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ mới</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        h1 {
            font-size: 24px;
            color: #333;
        }
        p {
            margin-bottom: 10px;
        }
        strong {
            font-weight: bold;
        }
        .message-box {
            background-color: #f9f9f9;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thông tin khách liên hệ</h1>
        <p><strong>From:</strong> {{ $formData['full-name'] }}</p>
        <p><strong>Email:</strong> {{ $formData['email'] }}</p>
        <p><strong>Subject:</strong> {{ $formData['subject'] }}</p>
        <hr>
        <h2>Message:</h2>
        <div class="message-box">
            <p>{{ $formData['message'] }}</p>
        </div>
    </div>
</body>
</html>
