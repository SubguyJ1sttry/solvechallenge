FROM php:8.2-apache


RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

COPY public/ /var/www/html/
COPY includes/ /var/www/html/includes/
COPY flag.txt /var/www/html/

WORKDIR /var/www/html/

EXPOSE 80
