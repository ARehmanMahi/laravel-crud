FROM php:8.1-fpm-alpine

RUN mkdir -p /var/www/html/public

RUN docker-php-ext-install pdo pdo_mysql

# CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.d/www.conf", "-R"]
