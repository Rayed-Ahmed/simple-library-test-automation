FROM php:8.2-apache

RUN docker-php-ext-install mysqli \
    && a2enmod rewrite

# The application is mounted at /var/www/html/simple-online-library-system.
EXPOSE 80
