# 🛠 PHP + MySQL 開発環境構築手順（Docker Compose）

---

## ✅ 前提条件（ローカル開発環境）

* OS: Mac / Windows / Linux
* Docker Desktop インストール済み
* Git インストール済み（推奨）

---

## 📁 1. プロジェクトのディレクトリを作成

```bash
mkdir my-php-app
cd my-php-app

mkdir -p docker/php docker/nginx docker/mysql
mkdir src
touch docker-compose.yml .env
```

---

## 📁 プロジェクト構成

```
php-01/
├── docker-compose.yml
├── src/
│   └── index.php
├── mysql_data/         ← MySQLの永続化用ボリューム
```

---

## 1️⃣ `docker-compose.yml` の作成

```yaml
version: '3.8'

services:
  app:
    build:
      context: ./docker/php
    container_name: php-app
    volumes:
      - ./src:/var/www/html
    environment:
      - TZ=Asia/Tokyo
    depends_on:
      - db
    networks:
      - backend

  web:
    image: nginx:1.25-alpine
    container_name: nginx-web
    ports:
      - "8080:80"
    volumes:
      - ./src:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
    networks:
      - backend

  db:
    image: mysql:8.0
    container_name: mysql-db
    restart: always
    volumes:
      - db-data:/var/lib/mysql
      - ./docker/mysql/my.cnf:/etc/mysql/conf.d/my.cnf
    environment:
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
      MYSQL_DATABASE: ${DB_NAME}
      MYSQL_USER: ${DB_USER}
      MYSQL_PASSWORD: ${DB_PASSWORD}
      TZ: Asia/Tokyo
    networks:
      - backend
    command: --default-time-zone='+09:00'

volumes:
  db-data: 

networks:
  backend:
    driver: bridge

```

---

## 2️⃣ `src/index.php` の作成

```php
<?php
echo "Hello from PHP in Docker!";
?>
```

---

## 3️⃣ Docker コンテナの起動・ビルド

```bash
docker-compose up -d --build
```

---

## 4️⃣ MySQLコンテナに接続

```bash
docker exec -it mysql-db mysql -u root -p
# パスワード: rootpassword
```

---

## 5️⃣ データベースとテーブル作成

```sql
USE myapp;

CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  message TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

---

## 6️⃣ タイムゾーンを日本時間に設定

`docker-compose.yml` の `db` サービスに以下を追加：

```yaml
    environment:
      ...
      TZ: Asia/Tokyo
    command: --default-time-zone='+09:00'
```

確認コマンド：

```sql
SHOW VARIABLES LIKE 'time_zone';
-- 結果: +09:00
```

---

## 7️⃣ DBeaver から接続する方法

| 設定項目    | 値                   |
|-------------|----------------------|
| ホスト      | `localhost`          |
| ポート      | `3306`               |
| ユーザー名  | `root`               |
| パスワード  | `rootpassword`       |
| データベース | `myapp`（任意）       |

必要に応じて `docker-compose.yml` に以下を追加：

```yaml
    ports:
      - "3306:3306"
```

---

## ✅ 動作確認

- ブラウザで `http://localhost:8080` を開く
- 「Hello from PHP in Docker!」が表示されればOK

---

## 🧹 コンテナ停止・削除コマンド（必要に応じて）

```bash
docker-compose down         # コンテナ停止＋削除
docker volume prune         # 永続ボリュームを削除（確認あり）
```

---

# MySQLで `posts` テーブルが**正しく作成できているか確認する方法**は、次のSQLコマンドを使うのが一般的です。

---

## ✅ 手順：作成済みテーブルの確認

1. MySQLにログイン

```bash
docker exec -it mysql-db mysql -u root -p
# パスワードを入力 → docker-compose.yml の MYSQL_ROOT_PASSWORD
```

2. データベースを選択

```sql
USE myapp;
```

3. テーブル一覧を確認

```sql
SHOW TABLES;
```

期待される出力：

```
+------------------+
| Tables_in_myapp  |
+------------------+
| posts            |
+------------------+
```

4. `posts` テーブルの構造確認

```sql
DESCRIBE posts;
```

期待される出力（例）：

```
+------------+--------------+------+-----+-------------------+----------------+
| Field      | Type         | Null | Key | Default           | Extra          |
+------------+--------------+------+-----+-------------------+----------------+
| id         | int          | NO   | PRI | NULL              | auto_increment |
| name       | varchar(100) | NO   |     | NULL              |                |
| message    | text         | NO   |     | NULL              |                |
| created_at | datetime     | YES  |     | CURRENT_TIMESTAMP |                |
+------------+--------------+------+-----+-------------------+----------------+
```

---

## ✅ さらに：テーブルにデータがあるか確認（任意）

```sql
SELECT * FROM posts;
```

（まだ何も投稿していなければ空欄になります）

---

## 📝 補足

* `USE myapp;` を忘れると `posts` テーブルが見つからないことがあります。
* `DESCRIBE` はテーブル定義の確認に便利です。
* `SHOW CREATE TABLE posts;` を使えば作成時のSQLも見られます。

---

うまく確認できたかどうか教えてもらえれば、次のステップに進むお手伝いもできます！

