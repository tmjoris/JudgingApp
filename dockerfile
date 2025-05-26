FROM php:8.3-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY apache/virtualhost.conf /etc/apache2/sites-enabled/000-default.conf

EXPOSE 80