FROM php:8.0-fpm-alpine

RUN apk update \
    && apk add  --no-cache \
    git \
    curl \
    icu-dev \
    libxml2-dev \
    g++ \
    make \
    autoconf \
    libzip-dev \
    zip

RUN docker-php-ext-install pdo_mysql intl zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /tmp/*


WORKDIR /usr/src/app
