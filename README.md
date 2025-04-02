# 🚗 EasyRent - Backend Symfony

Ce projet constitue l’API backend de la plateforme **EasyRent**, développée avec **Symfony**, **MySQL**, et **Docker**.

---

## 📦 Installation du projet

### ✅ Prérequis

Avant de commencer, assure-toi d’avoir les outils suivants installés sur ta machine :

- [Git](https://git-scm.com/)
- [Composer](https://getcomposer.org/)
- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

---

### ⚙️ Étapes d’installation

1. **Cloner le dépôt Git :**

```bash
git clone https://github.com/MathisPFR/EasyRent-Back
cd EasyRent-Back
```

2. **Installer les dépendances PHP avec Composer :**

```bash
composer install
```

3. **Lancer les conteneurs Docker :**

```bash
docker-compose up -d --build
```

> Le flag `--build` permet de reconstruire l'image avec les dépendances nécessaires comme OpenSSL et ACL.

4. *(Optionnel - si non automatisé)* **Installer manuellement PDO dans le conteneur App :**

```bash
docker exec -it symfony_app docker-php-ext-install pdo pdo_mysql
```

---

## 🔐 Clés JWT

Les clés JWT sont nécessaires à l’authentification. Elles peuvent être générées automatiquement via le script d’entrée Docker.

Si ce n’est pas encore fait, tu peux les générer manuellement :

```bash
docker exec -it symfony_app php bin/console lexik:jwt:generate-keypair
```

---

## 🔁 Redémarrer le conteneur App (si nécessaire)

```bash
docker-compose restart app
```

---

### 🌐 Accès aux services

- **Symfony API** : [http://localhost:8080](http://localhost:8080/)
- **Documentation de l'API** : [http://localhost:8080/api/docs](http://localhost:8080/api/docs)
- **phpMyAdmin** : [http://localhost:8081](http://localhost:8081/)

---

### 🛠️ Informations base de données

- **Hôte (host)** : `db`
- **Port** : `3306`
- **Nom de la base** : `symfony`
- **Utilisateur** : `root`
- **Mot de passe** : `root`

---

### 📁 Arborescence utile

```
.
├── docker-compose.yml
├── Dockerfile
├── docker-entrypoint.sh
├── config/
│   └── jwt/           # Dossier des clés JWT
├── public/
├── src/
├── .env
└── ...
```

---

### 🧪 Tests

*À venir*

---

### 📬 Contact

Développé par **Mathis Petit**  
📧 [mathispetitfrpro@gmail.com](mailto:mathispetitfrpro@gmail.com)

