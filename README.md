# Projet Web - Base de données de films

Ce projet est une application web complète (Back-end Symfony + Front-end Twig) permettant de gérer et de louer des films.

## Installation et Lancement

Pour faire fonctionner le projet, suivez ces étapes :

### 1. Prérequis
- **PHP 8.2+** avec les extensions suivantes : `intl`, `pdo_pgsql`, `ctype`, `iconv`.
- **Composer** (ou utiliser le `composer.phar` inclus).
- **Symfony CLI** (recommandé pour le serveur local).
- **PostgreSQL** installé et configuré.

### 2. Installation des dépendances
Le dossier `vendor/` n'est pas inclus dans le dépôt. Vous devez l'installer :
```bash
php composer.phar install
```

### 3. Configuration de la base de données
Vérifiez ou créez un fichier `.env.local` pour configurer vos accès à PostgreSQL :
```text
DATABASE_URL="pgsql://VOTRE_USER:VOTRE_PASSWORD@127.0.0.1:5432/VOTRE_DB_NAME?serverVersion=16&charset=utf8"
```

### 4. Importation de la base de données
Importez le fichier `database.sql` fourni.
```bash
psql -h 127.0.0.1 -U VOTRE_USER -d VOTRE_DB_NAME -f database.sql
```

### 5. Lancement du serveur
```bash
symfony serve
```
Le site sera alors accessible sur `http://127.0.0.1:8000`.

## Fonctionnalités

- **Gestion des films (CRUD)** : Ajouter, modifier et supprimer des films.
- **Catalogue interactif** : Consultation de la liste des films avec recherche et filtres par genre ou année.
- **Détails du film** : Fiche détaillée pour chaque film avec synopsis, durée, etc.
- **Espace Utilisateur** : Inscription, connexion et profil personnel.
- **Système de Location** : Panier de location et historique des transactions (système simplifié).
- **Tarification Dynamique** : Les prix de location varient selon le jour de la semaine (ex: tarifs réduits certains jours).
- **Favoris** : Possibilité de marquer des films comme favoris.

## Accès administrateur

Identifiant : admin@admin.test
Mot de passe : adminadmin

ou alors vous pouvez créer un nouveau compte via le formulaire d'inscription et de changer le rôle de l'utilisateur dans la base de données (le mettre à 2 pour avoir l'accès administrateur).

---
Projet réalisé dans le cadre d'un projet web.
