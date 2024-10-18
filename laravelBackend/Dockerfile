# Use an official PHP runtime as a parent image
FROM php:8.1-fpm

# Set working directory inside the container
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    zip \
    git \
    curl \
    unzip

# Clear the apt cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# Copy the existing application directory contents to the working directory
COPY . /var/www

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage

# Expose port 9000 and start PHP-FPM server
EXPOSE 9000
CMD ["php-fpm"]
