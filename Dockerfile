# Utilise une image PHP avec Apache
FROM php:8.2-apache

# Installe les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Active le module Apache rewrite
RUN a2enmod rewrite

# Installe Composer
RUN apt-get update && apt-get install -y unzip curl git \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installe Symfony CLI (en tant que prérequis pour 'symfony-cmd')
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Copie les fichiers du projet dans le conteneur
COPY . /var/www/html

# Installe les dépendances Symfony
RUN composer install --no-dev --optimize-autoloader

# Installe Node.js, npm et build Tailwind CSS
RUN apt-get install -y nodejs npm && npm install && npm run build:css

# Expose le port 80
EXPOSE 80

# Démarre Apache
CMD ["apache2-foreground"]
