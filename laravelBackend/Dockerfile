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
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

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
