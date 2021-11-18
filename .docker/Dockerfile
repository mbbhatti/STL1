FROM php:7.3-apache

COPY . /srv/app

COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev zlib1g-dev libicu-dev libzip-dev g++ && \
    docker-php-ext-configure gd --with-jpeg-dir=/usr --with-png-dir=/usr && \
    docker-php-ext-install bcmath exif gd gettext intl mysqli pdo_mysql sockets && \
    chown -R www-data:www-data /srv/app && a2enmod rewrite headers

WORKDIR /srv/app
