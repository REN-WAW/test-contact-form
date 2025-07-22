<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Form</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/thanks.css') }}" />


</head>

<body>
  <div class="thanks-container">
    <h2 class="thanks-message">お問い合わせありがとうございました</h2>
    <a href="{{ route('contact.index') }}" class="home-button">HOME</a>
  </div>
</body>
</html>
