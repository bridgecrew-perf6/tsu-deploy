FROM php:8.1-fpm

ARG COMMIT_HASH
ARG TIMESTAMP

ENV BUILD_COMMIT_HASH=$COMMIT_HASH
ENV BUILD_TIMESTAMP=$TIMESTAMP

RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli

WORKDIR /var/www

ENTRYPOINT configs/php/entrypoint.sh