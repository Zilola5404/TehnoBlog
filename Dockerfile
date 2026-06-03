## Стадия сборки фронтенд-ассетов
## Собирается CSS из SCSS с помощью node/sass, результаты копируются в финальный образ.
FROM node:18 AS assets
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm ci --silent || npm install --silent
COPY assets assets
# Собираем CSS; команда не прерывает сборку, чтобы не ломать image если локально нет node_modules
RUN npm run build:css || true

## Финальный PHP-образ с Apache
FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libzip-dev \
    && docker-php-ext-install pdo_mysql zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

## Копируем composer из официального образа для установки зависимостей
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# Устанавливаем PHP-зависимости проекта (Composer)
COPY composer.json composer.lock* ./
RUN composer install --no-interaction --prefer-dist --no-progress --optimize-autoloader

# Копируем весь проект в образ
COPY . .

# Копируем сгенерированные на стадии `assets` CSS в публичную папку
COPY --from=assets /app/public/assets/css /var/www/html/public/assets/css

# Создаём каталоги для Smarty и назначаем владельца веб-сервера
RUN mkdir -p var/cache var/templates_c \
    && chown -R www-data:www-data var
