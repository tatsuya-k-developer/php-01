以下に、**Dockerを用いたセキュリティ対策・メンテナンス性を考慮したPHP環境構築手順**を、**初心者でも再現できるレベルで詳細に**説明します。
Laravelを使う場合も想定している構成です（純粋なPHPのみでも使えます）。

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

## 🐳 2. `docker-compose.yml` を作成

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
    networks:
      - backend

volumes:
  db-data:

networks:
  backend:
    driver: bridge
```

---

## ⚙️ 3. PHPのDockerfileを作成（`docker/php/Dockerfile`）

```Dockerfile
FROM php:8.2-fpm-alpine

RUN addgroup -g 1000 app && adduser -G app -g "App User" -s /bin/sh -D app

RUN apk --no-cache add \
    bash \
    tzdata \
    libzip-dev \
    oniguruma-dev \
  && docker-php-ext-install pdo_mysql zip mbstring

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

USER app
```

---

## 🌐 4. nginx設定（`docker/nginx/default.conf`）

```nginx
server {
    listen 80;
    server_name localhost;

    root /var/www/html/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location ~ /\.ht {
        deny all;
    }

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";
}
```

---

## 🛡 5. MySQL設定（`docker/mysql/my.cnf`）

```ini
[mysqld]
default-authentication-plugin=mysql_native_password
character-set-server=utf8mb4
collation-server=utf8mb4_unicode_ci
```

---

## 🔐 6. `.env`ファイルの作成

※ `.gitignore` でこのファイルは除外すべきです

```env
DB_ROOT_PASSWORD=rootpassword
DB_NAME=myapp
DB_USER=myuser
DB_PASSWORD=mypassword
```

---

## 📝 7. PHPコードを配置（例：`src/public/index.php`）

```php
<?php
phpinfo();
```

```bash
mkdir -p src/public
```

---

## 🛠 8. ビルドと起動

```bash
docker-compose up -d --build
```

起動後、ブラウザで以下を開いて確認：

```
http://localhost:8080
```

`phpinfo()` が表示されればOK。


---

## 🔐 セキュリティ観点での強化点

| 対策             | 内容                     |
| -------------- | ---------------------- |
| ユーザー権限         | rootではなく `app` ユーザーで実行 |
| Docker最小構成     | Alpineベースで不要なものは入れない   |
| nginxセキュリティヘッダ | XSS対策・Clickjacking対策   |
| データ永続化         | DBデータを `volume` で保持    |
| `.env`管理       | 認証情報はハードコードしない。git除外   |

---


