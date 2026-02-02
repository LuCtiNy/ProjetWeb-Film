# Projet Web - Base de données de films

Ce projet est une application web complète (Back-end Symfony + Front-end Twig) permettant de gérer et de louer des films.

## 🚀 Fonctionnalités

- **Gestion des films (CRUD)** : Ajouter, modifier et supprimer des films.
- **Catalogue interactif** : Consultation de la liste des films avec recherche et filtres par genre ou année.
- **Détails du film** : Fiche détaillée pour chaque film avec synopsis, durée, etc.
- **Espace Utilisateur** : Inscription, connexion et profil personnel.
- **Système de Location** : Panier de location et historique des transactions (système simplifié).
- **Tarification Dynamique** : Les prix de location varient selon le jour de la semaine (ex: tarifs réduits certains jours).
- **Favoris** : Possibilité de marquer des films comme favoris.

## 🛠️ Prérequis

Avant de commencer, assurez-vous d'avoir installé les outils suivants :

- **PHP** >= 8.2
- **Composer**
- **PostgreSQL** (ou tout autre SGBD compatible avec Doctrine)
- **Symfony CLI** (recommandé pour le serveur local)

## 📥 Installation

1. **Cloner le projet** :
   ```bash
   git clone <url-du-depot>
   cd ProjetWeb-Film
   ```

2. **Installer les dépendances PHP** :
   ```bash
   composer install
   ```

3. **Configurer l'environnement** :
   - Copiez le fichier `.env` en `.env.local` :
     ```bash
     cp .env .env.local
     ```
   - Modifiez la ligne `DATABASE_URL` dans `.env.local` avec vos identifiants de base de données. Exemple pour PostgreSQL :
     ```text
     DATABASE_URL="pgsql://utilisateur:motdepasse@127.0.0.1:5432/nom_db?serverVersion=16&charset=utf8"
     ```

## 🗄️ Configuration de la Base de Données

Une fois la configuration terminée, lancez les commandes suivantes pour initialiser la base :

1. **Créer la base de données** :
   ```bash
   php bin/console doctrine:database:create
   ```

2. **Exécuter les migrations** pour créer les tables :
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

## 🌐 Lancer le Serveur

Pour démarrer l'application localement, vous pouvez utiliser le serveur Symfony :

```bash
symfony serve
```

Ou utiliser le serveur intégré de PHP :

```bash
php -S localhost:8000 -t public
```

L'application sera alors accessible sur [http://localhost:8000](http://localhost:8000).

---
