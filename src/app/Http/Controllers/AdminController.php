<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact; // Contactモデルをインポート
use Illuminate\Support\Facades\Auth; // ログアウトで使用（現在はweb.phpで直接処理しているため、このコントローラでは必須ではない）
use Illuminate\Pagination\LengthAwarePaginator; // ページネーション用（直接は使わないが、念のため記載）
use Illuminate\Support\Facades\DB; // 必要に応じてDBファサード
use Symfony\Component\HttpFoundation\StreamedResponse; // CSVエクスポート用

class AdminController extends Controller
{
    /**
     * 管理画面ダッシュボード（お問い合わせ検索一覧）を表示
     * 初回アクセス時やリセットボタン押下時は全件表示、
     * 検索フォームからの送信時やページネーション移動時は検索条件を適用して表示
     */
    public function index(Request $request)
    {
        // 検索条件をセッションから取得、または初期化
        // ページネーション時に検索条件を維持するためにセッションを使用
        $searchParams = $request->session()->get('admin_search_params', [
            'name' => null,
            'email' => null,
            'gender' => null,
            'category_id' => null, // お問い合わせ種類（もしあれば）
            'date_from' => null,
            'date_to' => null,
        ]);

        // リクエストに検索条件が含まれている場合、セッションの検索条件を更新
        // (検索フォームから送信された場合)
        if ($request->hasAny(['name', 'email', 'gender', 'category_id', 'date_from', 'date_to'])) {
            $searchParams = $request->only(['name', 'email', 'gender', 'category_id', 'date_from', 'date_to']);
            $request->session()->put('admin_search_params', $searchParams);
        } else if ($request->query('page') && $request->session()->has('admin_search_params')) {
            // ページネーションリンククリック時など、ページパラメータのみの場合
            // セッションの検索条件を使用するため、searchParamsは更新しない
            // ($searchParamsは既にセッションから取得済み)
        } else {
            // それ以外（初回アクセス、リセットボタンクリック）の場合はセッションの検索条件をクリア
            // または初期状態に戻す。
            // indexルートにアクセスした際にクエリパラメータが全くなく、かつセッションに
            // 検索条件が残っている場合は、セッションをクリアして初期表示とする
            if (empty($request->query()) && $request->session()->has('admin_search_params')) {
                $request->session()->forget('admin_search_params');
                $searchParams = [
                    'name' => null,
                    'email' => null,
                    'gender' => null,
                    'category_id' => null,
                    'date_from' => null,
                    'date_to' => null,
                ];
            }
        }

        // 検索を実行
        $contacts = $this->performSearch($searchParams);

        return view('admin.index', [
            'contacts' => $contacts,
            'searchParams' => $searchParams, // ビューに現在の検索条件を渡す
        ]);
    }

    /**
     * 検索ロジックをカプセル化するヘルパーメソッド
     * 検索条件配列を受け取り、ページネーションされた結果を返す
     *
     * @param array $searchParams
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function performSearch(array $searchParams)
    {
        $query = Contact::query();

        // 名前での検索（姓、名、フルネーム）
        if (!empty($searchParams['name'])) {
            $name = $searchParams['name'];
            $query->where(function ($q) use ($name) {
                $q->where('last_name', 'LIKE', "%{$name}%")
                  ->orWhere('first_name', 'LIKE', "%{$name}%")
                  ->orWhereRaw("CONCAT(last_name, first_name) LIKE ?", ["%{$name}%"]); // フルネーム部分一致
            });
        }

        // メールアドレスでの検索
        if (!empty($searchParams['email'])) {
            $email = $searchParams['email'];
            $query->where('email', 'LIKE', "%{$email}%"); // 部分一致
        }

        // 性別での検索
        // 'gender'がnullでない、かつ空文字でない、かつ'0' (全て) でない場合に適用
        if (isset($searchParams['gender']) && $searchParams['gender'] !== '' && $searchParams['gender'] !== '0') {
            $query->where('gender', $searchParams['gender']); // 完全一致
        }

        // お問い合わせ種類での検索 (contactsテーブルに category_id がある場合)
        // もし contacts テーブルにお問い合わせ種類の ID を保存するカラムがあればコメント解除
        // if (!empty($searchParams['category_id'])) {
        //     $query->where('category_id', $searchParams['category_id']);
        // }

        // 日付での検索 (created_at を使用)
        if (!empty($searchParams['date_from'])) {
            $query->whereDate('created_at', '>=', $searchParams['date_from']);
        }
        if (!empty($searchParams['date_to'])) {
            $query->whereDate('created_at', '<=', $searchParams['date_to']);
        }

        // ページネーションリンクに検索条件を含める
        return $query->paginate(7)->appends($searchParams);
    }


    /**
     * CSVエクスポート
     * 現在のセッションの検索条件を適用してデータをエクスポート
     */
    public function export(Request $request)
    {
        // セッションから現在の検索条件を取得
        $searchParams = $request->session()->get('admin_search_params', []);

        // 検索ロジックを再利用して、絞り込み後のデータを取得（ページネーションなしで全件取得）
        $query = Contact::query();

        // 名前での検索（performSearchのロジックを再度記述、または別のヘルパーメソッドに分離）
        if (!empty($searchParams['name'])) {
            $name = $searchParams['name'];
            $query->where(function ($q) use ($name) {
                $q->where('last_name', 'LIKE', "%{$name}%")
                  ->orWhere('first_name', 'LIKE', "%{$name}%")
                  ->orWhereRaw("CONCAT(last_name, first_name) LIKE ?", ["%{$name}%"]);
            });
        }

        // メールアドレスでの検索
        if (!empty($searchParams['email'])) {
            $email = $searchParams['email'];
            $query->where('email', 'LIKE', "%{$email}%");
        }

        // 性別での検索
        if (isset($searchParams['gender']) && $searchParams['gender'] !== '' && $searchParams['gender'] !== '0') {
            $query->where('gender', $searchParams['gender']);
        }

        // 日付での検索
        if (!empty($searchParams['date_from'])) {
            $query->whereDate('created_at', '>=', $searchParams['date_from']);
        }
        if (!empty($searchParams['date_to'])) {
            $query->whereDate('created_at', '<=', $searchParams['date_to']);
        }

        $contactsToExport = $query->get(); // ここで全ての該当データを取得（ページネーションしない）

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="contacts_'. date('YmdHis') . '.csv"',
        ];

        // CSVデータを生成してストリーミング
        $callback = function() use ($contactsToExport) {
            $file = fopen('php://output', 'w');

            // ヘッダー行を書き込む
            // 日本語文字化け対策のためSJISに変換
            mb_convert_variables('SJIS', 'UTF-8', $header = [
                'ID', '姓', '名', '性別', 'メールアドレス', '郵便番号', '住所', '建物名', 'お問い合わせ内容', '登録日時'
            ]);
            fputcsv($file, $header);

            // データ行を書き込む
            foreach ($contactsToExport as $contact) {
                // アクセサを使用して性別を文字列で取得
                mb_convert_variables('SJIS', 'UTF-8', $row = [
                    $contact->id,
                    $contact->last_name,
                    $contact->first_name,
                    $contact->gender_text, // Contactモデルで定義したアクセサを使用
                    $contact->email,
                    $contact->postcode,
                    $contact->address,
                    $contact->building_name,
                    $contact->opinion,
                    $contact->created_at->format('Y-m-d H:i:s'),
                ]);
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

}