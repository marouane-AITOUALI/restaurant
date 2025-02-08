# Utilise une image PHP avec Apache
FROM php:8.2-apache

# Installe les dépendances nécessaires, y compris libzip-dev
RUN apt-get update && apt-get install -y unzip curl git libzip-dev

# Installe les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql zip gd

# Active le module Apache rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copie les fichiers du projet dans le conteneur
COPY . /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installe les dépendances Symfony
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-scripts --no-autoloader

# Installe Node.js, npm et build Tailwind CSS
RUN apt-get install -y nodejs npm && npm install && npm run build:css

# Expose le port 80
EXPOSE 80

# Démarre Apache
CMD ["apache2-foreground"]
