<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Form</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>

<body>
  <header class="header">
    <div class="header__inner">
      <a class="header__logo">
        FashionablyLate
      </a>
    </div>
  </header>

  <main>
    <div class="contact-form__content">
      <div class="contact-form__heading">
        <h2>Contact</h2>
      </div>
    </div>
    <form class="form" action="/contacts/confirm" method="post">
      @csrf
      <div class="form__group">
        <div class="form__group-title">
          <span class="form__label--item">お名前</span>
          <span class="form__label--required">※</span>
        </div>
        <div class="form__group-content">
          <td class="form__input--name">
            <input type="text" name="first_name" placeholder="例:山田" value="{{ old('first_name') }}" />
            <input type="text" name="last_name" placeholder="例:太郎" value="{{ old('last_name') }}" />
          </td>
          <div class="form__error">
            @error('first_name')
            <span class="error-message">{{ $message }}</span>
            @enderror
            @error('last_name')
            <span class="error-message">{{ $message }}</span>
            @enderror
          </div>
        </div>
      </div>
      
      <div class="form__group">
        <div class="form__group-title">
          <span class="form__label--item">性別</span>
          <span class="form__label--required">※</span>
        </div>
        <div class="form__group-content">
          <div class="form__input--radio">
            <input type="radio" id="gender_male" name="gender" value="1" {{ old('gender', '1') == '1' ? 'checked' : '' }}>
            <label for="gender_male">男性</label>
            <input type="radio" id="gender_female" name="gender" value="2" {{ old('gender') == '2' ? 'checked' : '' }}>
            <label for="gender_female">女性</label>
            <input type="radio" id="gender_other" name="gender" value="3" {{ old('gender') == '3' ? 'checked' : '' }}>
            <label for="gender_other">その他</label>
          </div>
        </div>
        <div class="form__error">
          @error('gender')
          <span class="error-message">{{ $message }}</span>
          @enderror
        </div>
      </div>
      
      <div class="form__group">
        <div class="form__group-title">
          <span class="form__label--item">メールアドレス</span>
          <span class="form__label--required">※</span>
        </div>
        <div class="form__group-content">
          <div class="form__input--text">
            <input type="email" name="email" placeholder="例: test@example.com" value="{{ old('email') }}" />
          </div>
          <div class="form__error">
            @error('email')
            <span class="error-message">{{ $message }}</span>
            @enderror
          </div>
        </div>
      </div>

      <div class="form__group">
        <div class="form__group-title">
          <span class="form__label--item">電話番号</span>
          <span class="form__label--required">※</span>
        </div>
        <div class="form__group-content">
          <td class="form__input--tel">
            <input type="tel" name="tel1" placeholder="080" value="{{ old('tel1') }}" /> -
            <input type="tel" name="tel2" placeholder="1234" value="{{ old('tel1') }}" /> -
            <input type="tel" name="tel3" placeholder="5678" value="{{ old('tel1') }}" />
          </td>
          <div class="form__error">
            @error('tel1')
            <span class="error-message">{{ $message }}</span>
            @enderror
            @error('tel2')
            <span class="error-message">{{ $message }}</span>
            @enderror
            @error('tel3')
            <span class="error-message">{{ $message }}</span>
            @enderror
          </div>
        </div>
      </div>
      
      <div class="form__group">
        <div class="form__group-title">
          <span class="form__label--item">住所</span>
          <span class="form__label--required">※</span>
        </div>
        <div class="form__group-content">
          <div class="form__input--text">
            <input type="address" name="address" placeholder="例: 東京都渋谷区千駄ヶ谷1-2-3" value="{{ old('address') }}" />
          </div>
          <div class="form__error">
            @error('address')
            <span class="error-message">{{ $message }}</span>
            @enderror
          </div>
        </div>
      </div>
      
      <div class="form__group">
        <div class="form__group-title">
          <span class="form__label--item">建物名</span>
        </div>
        <div class="form__group-content">
          <div class="form__input--text">
            <input type="text" name="building" placeholder="例: 千駄ヶ谷マンション101" value="{{ old('building') }}"/>
          </div>
          <div class="form__error">
            @error('building')
            <span class="error-message">{{ $message }}</span>
            @enderror
          </div>
        </div>
      </div>
        
      <div class="form__group">
        <div class="form__group-title">
          <span class="form__label--item">お問い合わせの種類</span>
          <span class="form__label--required">※</span>
        </div>
        <div class="form__group-content">
          <div class="form__input--select">
            <select id="category" name="category_id">
              <option value="">選択してください</option>
              @foreach($categories as $category)
              <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->content }}
              </option>
              @endforeach
            </select>
          </div>
          <div class="form__error">
            @error('category_id')
            <span class="error-message">{{ $message }}</span>
            @enderror
          </div>
        </div>
      </div>
      
      <div class="form__group">
        <div class="form__group-title">
          <span class="form__label--item">お問い合わせ内容</span>
          <span class="form__label--required">※</span>
        </div>
        <div class="form__group-content">
          <div class="form__input--textarea">
            <textarea name="detail" placeholder="お問い合わせ内容をご記載ください">{{ old('detail') }}</textarea>
          </div>
          <div class="form__error">
            @error('detail')
            <span class="error-message">{{ $message }}</span>
            @enderror
          </div>
        </div>
      </div>
      
      <div class="form__button">
        <button class="form__button-submit" type="submit">確認画面</button>
      </div>
    
    </form>
  </main>
</body>
</html>
