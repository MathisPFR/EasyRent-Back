FROM php:8.2-fpm

# Installation des dépendances nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql zip \
    && docker-php-ext-enable pdo pdo_mysql

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définition du répertoire de travail
WORKDIR /var/www

# Copie des fichiers de l'application
COPY . /var/www

# Changement des permissions
RUN chown -R www-data:www-data /var/www

# Exposition des ports
EXPOSE 9000

# Commande de démarrage du conteneur
CMD ["php-fpm"]
