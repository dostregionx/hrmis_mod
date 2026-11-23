FROM php:8.3-apache-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       libcurl4-openssl-dev libonig-dev libzip-dev \
       libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j1 \
       mysqli pdo_mysql gd zip calendar mbstring curl \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY apache-hrmis.conf /etc/apache2/sites-enabled/000-default.conf

RUN printf '%s\n' \
      'upload_max_filesize=100M' \
      'post_max_size=100M' \
      'max_file_uploads=20' \
      'expose_php=Off' \
      'session.upload_progress.enabled=Off' \
      > /usr/local/etc/php/conf.d/hrmis-runtime.ini

WORKDIR /var/www/html

# Application code is supplied by the production bind mount.
# Keep executable-upload protection in rebuilt runtime images.
COPY apache-upload-guard.conf /etc/apache2/conf-enabled/zz-hrmis-upload-guard.conf
