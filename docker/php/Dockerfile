FROM php:8.2-fpm-alpine

# セキュリティ強化: パッケージの最小化 & 非rootユーザー
RUN addgroup -g 1000 app && adduser -G app -g "App User" -s /bin/sh -D app

# タイムゾーン & 必要な拡張のインストール
RUN apk --no-cache add \
    bash \
    tzdata \
    libzip-dev \
    oniguruma-dev \
    && docker-php-ext-install pdo_mysql zip mbstring

# Composerインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 非rootユーザーに切り替え
USER app
