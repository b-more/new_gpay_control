# Use the official PHP image as the base image
FROM php:8.2-apache

# Set the working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git \
    libonig-dev \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    cron \
    supervisor \
    && apt-get install -y libicu-dev libzip-dev libonig-dev --no-install-recommends \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl zip calendar pdo pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Copy application files
COPY . /var/www/html

# Install Node.js and NPM
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs npm

# Set the Apache document root
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && echo "ServerName control.captain.localhost" >> /etc/apache2/apache2.conf \
    && a2enmod rewrite headers

# Set permissions for Laravel storage and bootstrap/cache folders
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Install Node.js dependencies
COPY package*.json ./
RUN npm install -g create-vite

# Generate Laravel key and create storage link
RUN php artisan key:generate && \
    php artisan storage:link

# Create a Supervisor configuration directory
RUN mkdir -p /etc/supervisor/conf.d

# Copy Supervisor configuration file into the container directory
COPY docker-worker.conf /etc/supervisor/conf.d/docker-worker.conf

# Configure cron
RUN touch /var/log/cron.log

# Script file copied into container.
COPY ./start.sh /start.sh

# convert to UNIX style
RUN dos2unix /start.sh

# Giving executable permission to script file.
RUN chmod +x /start.sh

# Expose port 80 for HTTP traffic
EXPOSE 80

# Start Supervisor and Apache server
CMD ["/bin/bash", "-c", "apache2-foreground && /start.sh && cron && supervisord -n"]
