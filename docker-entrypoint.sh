#!/bin/sh
set -e

# Lancer php-fpm en arrière-plan
php-fpm -D

# Génération des clés JWT si elles n'existent pas déjà
if [ ! -f config/jwt/private.pem ]; then
    echo "Génération des clés JWT..."
    php bin/console lexik:jwt:generate-keypair
    setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
    setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
else
    echo "Les clés JWT existent déjà, aucune action nécessaire."
fi

# Redémarrer PHP-FPM normalement
exec php-fpm
