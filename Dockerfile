FROM php:8.3-apache

WORKDIR /var/www/laravel

RUN curl -o /usr/local/bin/composer https://getcomposer.org/download/latest-stable/composer.phar \
    && chmod +x /usr/local/bin/composer

RUN apt-get update \
    && apt-get install --no-install-recommends -y \
    nodejs \
    npm \
    cron \
    icu-devtools \
    jq \
    libfreetype6-dev libicu-dev libjpeg62-turbo-dev libpng-dev libpq-dev \
    libsasl2-dev libssl-dev libwebp-dev libxpm-dev libzip-dev libzstd-dev \
    unzip \
    zlib1g-dev \
    supervisor \
    && apt-get clean \
    && apt-get autoclean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini \
    && pecl install --configureoptions='enable-redis-igbinary="yes" enable-redis-lzf="yes" enable-redis-zstd="yes"' igbinary zstd redis \
    && pecl clear-cache \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm \
    && docker-php-ext-install gd intl pdo_mysql pdo_pgsql zip \
    && docker-php-ext-enable igbinary opcache redis zstd

COPY composer.json composer.lock ./
RUN composer install --no-autoloader --no-scripts --no-dev

COPY docker/ /
RUN a2enmod rewrite headers \
    && a2ensite laravel \
    && a2dissite 000-default \
    && chmod +x /usr/local/bin/docker-laravel-entrypoint

COPY . /var/www/laravel
RUN composer install --optimize-autoloader --no-dev

RUN npm ci
# gets built in entrypoint to have prod .env
#RUN npm run build

# scheduler crontab
RUN chmod 0644 /var/spool/cron/crontabs/root \
    && crontab /var/spool/cron/crontabs/root

CMD ["docker-laravel-entrypoint"]
