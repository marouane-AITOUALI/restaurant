# Use PHP with Apache
FROM php:8.2-apache

# Install necessary dependencies
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libzip-dev \
    default-mysql-client nodejs npm

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip gd

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set the correct working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Ensure Composer is available
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Modify Apache configuration to set the document root to "public/"
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Ensure proper file permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# ✅ Install all Composer dependencies, including symfony/runtime
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader

# ✅ Ensure symfony/runtime is installed
RUN composer require symfony/runtime --no-scripts --no-interaction

# ✅ Clear Symfony cache after dependencies are installed
RUN php bin/console cache:clear

# Build Tailwind CSS if needed
RUN npm install && npm run build:css

# Start Apache
CMD ["apache2-foreground"]
