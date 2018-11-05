FROM php:5.6-apache

RUN apt-get update \
    && apt-get install -y mysql-client libpng-dev \
    && docker-php-ext-install gd \
    && docker-php-ext-install mysqli \
    && a2enmod rewrite

COPY rootfs/ /

ENTRYPOINT ["/db/entrypoint.sh"]
