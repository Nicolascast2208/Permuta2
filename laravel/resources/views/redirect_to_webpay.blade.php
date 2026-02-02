<!DOCTYPE html>
<html>
<head>
    <title>Redirigiendo a Webpay...</title>
</head>
<body>
    <form method="POST" action="{{ $url }}" id="webpay-form">
        <input type="hidden" name="token_ws" value="{{ $token_ws }}">
    </form>
    <script>document.getElementById('webpay-form').submit();</script>
</body>
</html>