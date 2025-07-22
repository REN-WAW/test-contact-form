<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test-contact-form</title>
</head>
<body>
    <body>
        <header class="header">
            <a href="/" class="header__logo">FashionablyLate</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="header__logout-button">ログアウト</button>
            </form>
        </header>
    
        <div class="container">
            <h1>管理システム</h1>
    
            @if (session('success'))
                <div style="color: green; text-align: center; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div style="color: red; text-align: center; margin-bottom: 20px;">
                    {{ session('error') }}
                </div>
            @endif
    
            <form action="{{ route('admin.index') }}" method="GET" class="search-form">
                <div class="search-form__item">
                    <label for="name" class="search-form__label">お名前</label>
                    <input type="text" name="name" id="name" class="search-form__input" value="{{ $searchParams['name'] ?? '' }}" placeholder="例: 山田 太郎">
                </div>
    
                <div class="search-form__item">
                    <label for="email" class="search-form__label">メールアドレス</label>
                    <input type="email" name="email" id="email" class="search-form__input" value="{{ $searchParams['email'] ?? '' }}" placeholder="例: test@example.com">
                </div>
    
                <div class="search-form__item">
                    <label for="gender" class="search-form__label">性別</label>
                    <select name="gender" id="gender" class="search-form__select">
                        <option value="0" {{ ($searchParams['gender'] ?? '') == '0' ? 'selected' : '' }}>全て</option>
                        <option value="1" {{ ($searchParams['gender'] ?? '') == '1' ? 'selected' : '' }}>男性</option>
                        <option value="2" {{ ($searchParams['gender'] ?? '') == '2' ? 'selected' : '' }}>女性</option>
                        <option value="3" {{ ($searchParams['gender'] ?? '') == '3' ? 'selected' : '' }}>その他</option>
                    </select>
                </div>
    
                {{-- <div class="search-form__item">
                    <label for="category_id" class="search-form__label">お問い合わせ種類</label>
                    <select name="category_id" id="category_id" class="search-form__select">
                        <option value="">全て</option>
                        <option value="1">商品について</option>
                        <option value="2">サービスについて</option>
                    </select>
                </div> --}}
    
                <div class="search-form__item">
                    <label for="date_from" class="search-form__label">登録日</label>
                    <input type="date" name="date_from" id="date_from" class="search-form__input search-form__input--date" value="{{ $searchParams['date_from'] ?? '' }}">
                    <span>〜</span>
                    <input type="date" name="date_to" id="date_to" class="search-form__input search-form__input--date" value="{{ $searchParams['date_to'] ?? '' }}">
                </div>
    
                <div class="search-form__button-group">
                    <button type="submit" class="search-form__button search-form__button--search">検索</button>
                    <button type="button" class="search-form__button search-form__button--reset" onclick="location.href='{{ route('admin.index') }}'">リセット</button>
                </div>
            </form>
    
            <a href="{{ route('admin.export', request()->query()) }}" class="export-button">エクスポート</a>
            <div style="clear: both;"></div> <table class="contact-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>お名前</th>
                        <th>性別</th>
                        <th>メールアドレス</th>
                        <th>お問い合わせ内容</th>
                        <th></th> </tr>
                </thead>
                <tbody>
                    @foreach ($contacts as $contact)
                        <tr>
                            <td>{{ $contact->id }}</td>
                            <td>{{ $contact->last_name }} {{ $contact->first_name }}</td>
                            <td>{{ $contact->gender_text }}</td> <td>{{ $contact->email }}</td>
                            <td>
                                @if (mb_strlen($contact->opinion) > 20)
                                    {{ mb_substr($contact->opinion, 0, 20) }}... @else
                                    {{ $contact->opinion }}
                                @endif
                            </td>
                            <td>
                                <button type="button" class="contact-table__detail-button"
                                    data-id="{{ $contact->id }}"
                                    data-last_name="{{ $contact->last_name }}"
                                    data-first_name="{{ $contact->first_name }}"
                                    data-gender="{{ $contact->gender_text }}"
                                    data-email="{{ $contact->email }}"
                                    data-postcode="{{ $contact->postcode }}"
                                    data-address="{{ $contact->address }}"
                                    data-building_name="{{ $contact->building_name ?? 'なし' }}"
                                    data-opinion="{{ $contact->opinion }}"
                                    data-created_at="{{ $contact->created_at->format('Y/m/d H:i') }}"
                                >詳細</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    
            <div class="pagination">
                {{ $contacts->appends(request()->input())->links() }}
            </div>
    
            <div id="detailModal" class="modal">
                <div class="modal-content">
                    <span class="close-button">&times;</span>
                    <h2 style="text-align: center; margin-bottom: 20px;">お問い合わせ詳細</h2>
                    <div class="modal-body">
                        <p><strong>ID:</strong> <span id="modal-id"></span></p>
                        <p><strong>お名前:</strong> <span id="modal-name"></span></p>
                        <p><strong>性別:</strong> <span id="modal-gender"></span></p>
                        <p><strong>メールアドレス:</strong> <span id="modal-email"></span></p>
                        <p><strong>郵便番号:</strong> <span id="modal-postcode"></span></p>
                        <p><strong>住所:</strong> <span id="modal-address"></span></p>
                        <p><strong>建物名:</strong> <span id="modal-building_name"></span></p>
                        <p><strong>お問い合わせ内容:</strong></p>
                        <div id="modal-opinion" class="modal-opinion"></div>
                        <p style="margin-top: 10px;"><strong>登録日時:</strong> <span id="modal-created_at"></span></p>
    
                        <form id="deleteForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="modal-delete-button">削除</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('detailModal');
                const closeButton = document.querySelector('.close-button');
                const detailButtons = document.querySelectorAll('.contact-table__detail-button');
                const deleteForm = document.getElementById('deleteForm');
    
                detailButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const data = this.dataset;
                        document.getElementById('modal-id').textContent = data.id;
                        document.getElementById('modal-name').textContent = data.lastName + ' ' + data.firstName;
                        document.getElementById('modal-gender').textContent = data.gender;
                        document.getElementById('modal-email').textContent = data.email;
                        document.getElementById('modal-postcode').textContent = data.postcode;
                        document.getElementById('modal-address').textContent = data.address;
                        document.getElementById('modal-building_name').textContent = data.buildingName;
                        document.getElementById('modal-opinion').textContent = data.opinion;
                        document.getElementById('modal-created_at').textContent = data.createdAt;
    
                        // 削除フォームのactionを設定
                        deleteForm.action = `/admin/${data.id}`; // LaravelのDELETEルートに合わせる
                        modal.style.display = 'flex'; // flexで表示して中央寄せを適用
                    });
                });
    
                closeButton.addEventListener('click', function() {
                    modal.style.display = 'none';
                });
    
                // モーダルの外側をクリックで閉じる
                window.addEventListener('click', function(event) {
                    if (event.target == modal) {
                        modal.style.display = 'none';
                    }
                });
            });
        </script>
    </body>
    </html>
</body>
</html>