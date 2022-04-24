FROM php:8.1-fpm

RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli

WORKDIR /var/www

HEALTHCHECK --interval=5s --timeout=10s --retries=1 CMD curl http://localhost/health

ENTRYPOINT configs/php/entrypoint.sh