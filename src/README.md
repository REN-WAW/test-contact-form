# お問い合わせフォーム


## 環境構築
### Dockerビルド
1. git clone git@github.com:REN-WAW/test-contact-form.git
(リポジトリの設定)
2. docker-compose up -d-build
(Dockerの設定)

### Laravel環境構築
1. docker-compose exec php bash
（PHPコンテナへログイン）
2. composer install
(パッケージのインストール)
3.  cp .env.example .env
(env.exampleファイルから.envを作成)
4. VSCodeから、envファイルの11行目以降を修正\
// 前略\
DB_CONNECTION=mysql\
-DB_HOST=~~127.0.0.1~~\
+DB_HOST=mysql\
DB_PORT=3306\
-DB_DATABASE=~~laravel~~\
-DB_USERNAME=~~root~~\
-DB_PASSWORD=\
+DB_DATABASE=laravel_db
+DB_USERNAME=laravel_user
+DB_PASSWORD=laravel_pass\
// 後略

5. php artisan key:generate
(keyを生成し設定)
6. php artisan migrate
(マイグレーション)
7. phpartisan db:seed


## 使用技術
*PHP8.1
*laravel 10LTS
*MySQL

#URL
*開発環境:http//localhost
*phpMyAdmin:http//localhost
