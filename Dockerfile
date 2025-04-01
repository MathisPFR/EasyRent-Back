FROM php:8.2-fpm

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    default-mysql-client \
    openssl \
    acl \
    && docker-php-ext-install pdo pdo_mysql zip \
    && docker-php-ext-enable pdo pdo_mysql

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définition du répertoire de travail
WORKDIR /var/www

# Copie des fichiers de l'application
COPY . /var/www

# Changement des permissions du projet
RUN chown -R www-data:www-data /var/www

# Copie du script d'entrypoint et autorisation d'exécution
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Définition du point d’entrée
ENTRYPOINT ["docker-entrypoint.sh"]

# Exposition du port PHP-FPM
EXPOSE 9000
