# Utilise une image PHP avec Apache
FROM php:8.2-apache

# Installe les dépendances nécessaires, y compris libzip-dev
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev \
    libzip-dev default-mysql-client

# Installe les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql zip gd

# Active le module Apache rewrite
RUN a2enmod rewrite

# Ensure Apache has the right permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Modify Apache configuration to allow access
RUN echo '<Directory /var/www/html>' > /etc/apache2/sites-available/000-default.conf
RUN echo '    Options Indexes FollowSymLinks' >> /etc/apache2/sites-available/000-default.conf
RUN echo '    AllowOverride All' >> /etc/apache2/sites-available/000-default.conf
RUN echo '    Require all granted' >> /etc/apache2/sites-available/000-default.conf
RUN echo '</Directory>' >> /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# Copie les fichiers du projet dans le conteneur
COPY . /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


# Installe les dépendances Symfony
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-scripts --no-autoloader

# Installe Node.js, npm et build Tailwind CSS
RUN apt-get install -y nodejs npm && npm install && npm run build:css

# Démarre Apache
CMD ["apache2-foreground"]
