<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Test-Contact-Form</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/confirm.css') }}" />
</head>

<body>
    <header class="header">
        <a href="/" class="header__logo">FashionablyLate</a> <a href="{{ route('register') }}" class="header__register-button">register</a>
    </header>
    
    <div class="main-content">
        <div class="login-card">
            <h2 class="login-card__title">Login</h2>
            <div class="form__error">
                @error('message')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="例: test@example.com" >
                    @error('email')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
            </div>

                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input id="password" type="password" name="password" placeholder="例: coachtech123" >
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                
                <button type="submit" class="login-button">ログイン</button>

                
        </div>
    </div>

   
</body>
</html>
