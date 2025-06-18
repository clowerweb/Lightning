# /var/www/php/Dockerfile
FROM php:8.4-fpm

# Install system dependencies needed for PHP extensions
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    libicu-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    curl \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring zip xml curl

# Configure and install intl extension
RUN docker-php-ext-configure intl && docker-php-ext-install intl

# Install and enable the APCu extension
RUN pecl install apcu \
    && docker-php-ext-enable apcu

# Install Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

# Optionally, install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html
