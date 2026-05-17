FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libonig-dev \
    unzip \
    && docker-php-ext-install mysqli pdo_mysql mbstring zip \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t /var/www/html"]