# Use the official PHP image with Apache
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    lsb-release \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs

# Create non-root user and set permissions
RUN useradd -u 1000 -d /home/appuser -m appuser && \
    mkdir -p /var/www/html && \
    chown -R appuser:appuser /var/www/html

# Switch to non-root user
USER appuser

# Set working directory
WORKDIR /var/www/html

# Copy files with correct ownership
COPY --chown=appuser:appuser . .

# Set environment to production (optional)
ENV APP_ENV=prod

# Install PHP dependencies
RUN composer install 

# Install npm dependencies and build Tailwind
RUN npm install && npm run build:css

# Switch back to root for Apache setup
USER root

# Set Apache permissions
RUN chown -R www-data:www-data /var/www/html/public

# Start Apache
CMD ["apache2-foreground"]
