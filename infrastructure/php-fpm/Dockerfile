FROM php:8.2-fpm

# Fix debconf warnings upon .build
ARG DEBIAN_FRONTEND=noninteractive
ARG env

RUN apt-get update && apt-get install -y \
        locales \
        libzip-dev \
        zip \
        unzip \
        p7zip-full \
    && docker-php-ext-install zip opcache mysqli pdo pdo_mysql

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Set the locale
RUN sed -i '/en_US.UTF-8/s/^# //g' /etc/locale.gen && \
    locale-gen
ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

COPY www.conf /usr/local/etc/php-fpm.d
COPY ${env}/opcache.ini "$PHP_INI_DIR"/conf.d
COPY ${env}/php.ini "$PHP_INI_DIR"/conf.d

WORKDIR "/application"