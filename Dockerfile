# Use an official PHP image with FPM and required extensions
FROM php:8.1-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libxml2-dev \
    zip \
    git \
    libonig-dev \
    libssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql xml bcmath mbstring opcache \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory inside container
WORKDIR /var/www

# Copy the local code into the container
COPY .. .

# Install PHP dependencies (if using Laravel)
RUN composer install

# Expose port 9000 to connect to PHP-FPM
EXPOSE 9000
