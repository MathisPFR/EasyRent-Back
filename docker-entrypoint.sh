#!/bin/sh
set -e

# Installer les dépendances si vendor n'existe pas
if [ ! -d vendor ]; then
    echo "Installation des dépendances Composer..."
    composer install --no-interaction
fi

# Attendre que la base de données soit prête
echo "Attente de la base de données..."
while ! mysqladmin ping -h"db" -u"root" -p"root" --silent; do
    sleep 1
done

# Génération des clés JWT si elles n'existent pas déjà
if [ ! -f config/jwt/private.pem ]; then
    echo "Génération des clés JWT..."
    mkdir -p config/jwt

    # S'assurer que le dossier a les bonnes permissions
    chmod -R 777 config/jwt

    # Génération des clés
    php bin/console lexik:jwt:generate-keypair --skip-if-exists

    # Ajuster les permissions après génération
    setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
    setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
else
    echo "Les clés JWT existent déjà, aucune action nécessaire."
fi

# Nettoyer les anciennes migrations
echo "Nettoyage des anciennes migrations..."
rm -rf migrations/*.php

# Réinitialiser la base de données si nécessaire
echo "Suppression du schéma de base de données existant..."
php bin/console doctrine:schema:drop --force --full-database --no-interaction || true

# Créer la migration
echo "Création des migrations de base de données..."
php bin/console make:migration --no-interaction

# Exécuter les migrations
echo "Exécution des migrations..."
php bin/console doctrine:migrations:migrate --no-interaction

# Lancer PHP-FPM normalement
exec php-fpm