FROM php:8.2-cli

# Install required system packages and PHP extensions
RUN apt-get update && apt-get install -y \
    zip unzip curl git libzip-dev libpq-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip

# Install required PHP extensions and tools
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# Install Composer from official Composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory inside container
WORKDIR /var/www/html

