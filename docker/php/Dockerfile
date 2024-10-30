
FROM php:8.2-fpm-alpine3.20 AS base

RUN apk add --no-cache \
    curl \
    supervisor \
    git \
    build-base \
    zlib-dev \
    freetype \
    libpng \
    libjpeg-turbo \
    freetype-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    oniguruma-dev \
    libwebp-dev \
    autoconf \
    bash \
    zip \
    libzip-dev \
    imagemagick-dev \
    icu-dev \
    --update linux-headers && \
    pecl install imagick && \
    docker-php-ext-enable imagick && \
    docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp && \
    docker-php-ext-configure intl && \
    docker-php-ext-install -j$(nproc) \
    gd \
    exif \
    intl \
    mysqli \
    pdo_mysql \
    mbstring \
    zip

COPY php.ini /usr/local/etc/php/php.ini

ARG PUID=1000
ARG PGID=1000

RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

FROM base AS dev

COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN pecl install xdebug && docker-php-ext-enable xdebug

FROM base AS prod

COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY supervisor/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf
COPY run.sh /run.sh

RUN docker-php-ext-install opcache

ENTRYPOINT /bin/bash /run.sh

# # Bazaviy PHP imidjidan foydalanamiz
# FROM php:8.2-fpm-bullseye AS base

# # Kerakli paketlar va PHP kengaytmalarini o'rnatamiz
# RUN apt-get update && apt-get install -y \
#     supervisor \
#     curl \
#     git \
#     build-essential \
#     zlib1g-dev \
#     libfreetype6-dev \
#     libpng-dev \
#     libjpeg62-turbo-dev \
#     libonig-dev \
#     libwebp-dev \
#     libzip-dev \
#     libicu-dev \
#     imagemagick \
#     libmagickwand-dev \
#     libfontconfig1 \
#     libxrender1 \
#     libxext6 \
#     wget \
#     xz-utils \
#     gnupg2 \
#     software-properties-common \
#     && pecl install imagick && \
#     docker-php-ext-enable imagick && \
#     docker-php-ext-configure gd \
#         --with-freetype \
#         --with-jpeg \
#         --with-webp && \
#     docker-php-ext-configure intl && \
#     docker-php-ext-install -j$(nproc) \
#     gd \
#     exif \
#     intl \
#     mysqli \
#     pdo_mysql \
#     mbstring \
#     zip

# # PHP konfiguratsiya faylini nusxalaymiz
# COPY php.ini /usr/local/etc/php/php.ini

# # Foydalanuvchi va guruh ID parametrlarini belgilaymiz
# ARG PUID=1000
# ARG PGID=1000

# RUN groupmod -o -g ${PGID} www-data && \
#     usermod -o -u ${PUID} -g www-data www-data

# # Composer'ni bazaviy imidjdan nusxalaymiz
# COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# # Wkhtmltopdf ni o'rnatamiz
# RUN apt-get install -y --no-install-recommends wkhtmltopdf

# # Dev uchun imidj
# FROM base AS dev

# # Xdebug sozlamalarini nusxalaymiz
# COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# # Xdebug'ni o'rnatamiz
# RUN pecl install xdebug && docker-php-ext-enable xdebug

# # Prod uchun imidj
# FROM base AS prod

# # Opcache sozlamalarini nusxalaymiz
# COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini
# COPY run.sh /run.sh
# COPY supervisor/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

# # Opcache kengaytmasini o'rnatamiz
# RUN docker-php-ext-install opcache

# ENTRYPOINT /bin/bash /run.sh