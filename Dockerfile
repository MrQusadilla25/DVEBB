FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libonig-dev \
    unzip \
    && docker-php-ext-install mysqli mbstring zip \
    && rm -rf /var/lib/apt/lists/*

RUN rm -f /etc/apache2/mods-enabled/mpm_event.load \
    /etc/apache2/mods-enabled/mpm_event.conf \
    /etc/apache2/mods-enabled/mpm_worker.load \
    /etc/apache2/mods-enabled/mpm_worker.conf \
    && a2enmod mpm_prefork rewrite

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80