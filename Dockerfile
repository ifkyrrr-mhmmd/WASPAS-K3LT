FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    mysql-client

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring gd xml

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Install composer dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Copy supervisor and nginx configurations
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf

# Setup directory permissions and log directories
RUN mkdir -p /var/log/supervisor /var/log/nginx /var/run && \
    chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Build assets using Node
RUN apk add --no-cache nodejs npm && \
    npm install && \
    npm run build

# Expose port 80
EXPOSE 80

# Run supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
