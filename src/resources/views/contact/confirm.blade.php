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
    <div class="header__inner">
      <a class="header__logo" href="/">
        FashionablyLate
      </a>
    </div>
  </header>

  <main>
    <div class="confirm__content">
      <div class="confirm__heading">
        <h2>confirm</h2>
      </div>
      <form class="form" action="{{ route('contact.send')}}" method="post">
        @csrf

        <div class="confirm-table">
          <table class="confirm-table__inner">

            <tr class="confirm-table__row">
              <th class="confirm-table__header">お名前</th>
              <td class="confirm-table__text">
                {{ $validatedData['first_name'] }}　{{ $validatedData['last_name'] }}
                <input type="hidden" name="first_name" value="{{ $validatedData['first_name'] }}" />
                <input type="hidden" name="last_name" value="{{ $validatedData['last_name'] }}" />
              </td>
            </tr>

            <tr class="confirm-table__row">
              <th class="confirm-table__header">性別</th>
              <td class="confirm-table__text">
                @if ($validatedData['gender'] == 1)
                男性
                @elseif ($validatedData['gender'] == 2)
                女性
                @elseif ($validatedData['gender'] == 3)
                その他
                @else
                不明
                @endif
                <input type="hidden" name="gender" value="{{ $validatedData['gender'] }}" />
              </td>
            </tr>

            <tr class="confirm-table__row">
              <th class="confirm-table__header">メールアドレス</th>
              <td class="confirm-table__text">
                {{ $validatedData['email'] }}
                <input type="hidden" name="email" value="{{ $validatedData['email']}}" />
              </td>
            </tr>

            <tr class="confirm-table__row">
              <th class="confirm-table__header">電話番号</th>
              <td class="confirm-table__text">
              {{ $validatedData['tel'] }}
                <input type="hidden" name="tel1" value="{{ $validatedData['tel1'] }}" />
                <input type="hidden" name="tel2" value="{{ $validatedData['tel2'] }}" />
                <input type="hidden" name="tel3" value="{{ $validatedData['tel3'] }}" />
              </td>
            </tr>

            <tr class="confirm-table__row">
              <th class="confirm-table__header">住所</th>
              <td class="confirm-table__text">
                {{ $validatedData['address'] }}
                <input type="hidden" name="address" value="{{ $validatedData['address']}}">
              </td>
            </tr>

            <tr class="confirm-table__row">
              <th class="confirm-table__header">建物名</th>
              <td class="confirm-table__text">
                {{ $validatedData['building'] ?? '' }}
                <input type="hidden" name="building" value="{{ $validatedData['building'] ?? '' }}">
              </td>
            </tr>
            
            <tr class="confirm-table__row">
              <th class="confirm-table__header">お問い合わせの種類</th>
              <td class="confirm-table__text">{{ $categoryName }}
                <input="hidden" name="category_id" value="{{ $validatedData['category_id'] }}" />
              </td>
            </tr>
            
            <tr class="confirm-table__row">
              <th class="confirm-table__header">お問い合わせ内容</th>
              <td class="confirm-table__text">
                {{ $validatedData['detail'] }}
                <input type="hidden" name="detail" value="{{ $validatedData['detail'] }}" />
              </td>
            </tr>
          </table>
        </div>
        <div class="form__button">
          <button class="form__button-submit" type="submit">送信</button>
          <button class="form__button-back" type="button" onclick="history.back()">修正</button>
        </div>
      </form>
    </div>
  </main>
</body>

</html>
