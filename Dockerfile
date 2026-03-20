FROM php:8.2-fpm-alpine

# Dépendances système
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    zip \
    unzip

# Configuration GD
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg

# Extensions PHP nécessaires
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    gd \
    exif \
    intl \
    zip \
    mbstring \
    opcache

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
