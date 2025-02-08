# Use the official PHP image with Apache
FROM php:8.2-apache

# Install system dependencies for Composer, Symfony, and Node.js
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    lsb-release \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Verify Composer installation
RUN composer --version

# Install Symfony CLI globally
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Install Node.js (via NodeSource) and npm
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs

# Set the working directory for the container
WORKDIR /var/www/html

# Copy the project files into the container
COPY . .

# Set file permissions to make sure Apache can serve the files
RUN chown -R www-data:www-data /var/www/html

# Install PHP dependencies with Composer (no dev dependencies, optimized autoloader)
RUN composer install --no-dev --optimize-autoloader

# Install npm dependencies and build Tailwind CSS
RUN npm install && npm run build:css

# Expose port 80 for the Apache web server
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
