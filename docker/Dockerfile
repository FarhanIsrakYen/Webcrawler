FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    zip

RUN docker-php-ext-install intl

RUN docker-php-ext-install zip

# install-php-extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync
RUN install-php-extensions \
    pdo \
    mysqli \
    pcntl \
    pdo_mysql \
    opcache 

WORKDIR /var/www

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN usermod -u 1000 www-data

CMD ["php-fpm"]

EXPOSE 9000
