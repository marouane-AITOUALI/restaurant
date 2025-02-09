# KoulMaghreb

< https://restaurant-704m.onrender.com/ >

**[KoulMaghreb](https://restaurant-704m.onrender.com/)** est une application web de gestion de restaurant maghrébin développée en Symfony. Ce projet vise à offrir une expérience utilisateur unique en regroupant les saveurs authentiques de l'Algérie, de la Tunisie et du Maroc.

## Table des matières

- [À propos du projet](#À-propos-du-projet)
- [Objectifs](#Objectifs)
- [Schéma de la base de données](#Schéma-de-la-base-de-données)
- [Pré-requis](#Pré-requis)
- [Installation](#Installation)
  - [Clonez le dépôt](#1-clonez-le-dépôt)
  - [Installez les dépendances](#2-installez-les-dépendances)
  - [.env File](#3-rajouter-votre-propre-fichier-env)
  - [Base de données](#4-créer-la-base-de-données)
  - [Fixtures](#5-ajouter-les-fixtures-)
- [Utilisation](#Utilisation)
- [Structure du projet](#Structure-du-projet)
- [Fonctionnalités](#Fonctionnalités)
  - [Fonctionnalités utilisateur](#Fonctionnalités-utilisateur)
  - [Fonctionnalités administrateur](#Fonctionnalités-administrateur)
  - [Fonctionnalités futures](#Fonctionnalités-futures)
- [Tests](#Tests)
- [Requêtes personnalisées avec Query Builder](#Requêtes-personnalisées-avec-Query-Builder)
- [Gestion des rôles et permissions](#Gestion-des-rôles-et-permissions)
- [APIs et Voter](#APIs-et-Voter)
  - [APIs](#APIs)
  - [Voter](#Voter)
- [Fonctionnalités bonus](#Fonctionnalités-bonus)
- [Contribuer](#Contribuer)
- [Déploiement](#Déploiement)

## À propos du projet

Le projet **KoulMaghreb** a été conçu pour offrir une solution complète de gestion de restaurant, intégrant la réservation de tables, la gestion des menus et des événements, ainsi que des promotions pour attirer et fidéliser les clients.

### Pourquoi ce projet ?

Fondé en 1994 par trois amis passionnés de cuisine, **KoulMaghreb** est devenu un lieu emblématique où se rencontrent les traditions culinaires du Maghreb. Chaque plat raconte une histoire, et chaque client devient partie de cette aventure culinaire.

## Objectifs

- **Gestion des réservations** : Permettre aux utilisateurs de réserver des tables en ligne.
- **Organisation des événements** : Faciliter la création et la gestion des événements du restaurant.
- **Gestion des menus et des plats** : Offrir une interface intuitive pour créer et modifier les menus.
- **Promotions** : Attirer et fidéliser les clients avec des offres spéciales.
- **Commentaires et évaluations** : Permettre aux clients de laisser des avis.
- **Gestion des utilisateurs et des rôles** : Système d'administration sécurisé.

## Schéma de la base de données:
![bdd](https://github.com/user-attachments/assets/322293e5-1c1c-4266-b237-8882a8c24075)

## Pré-requis:
Voici les outils et versions nécessaires pour exécuter ce projet :

- PHP 8.1 ou supérieur
- Composer (gestionnaire de dépendances PHP)
- Symfony CLI (outil en ligne de commande pour Symfony)
- Base de données : MySQL ou PostgreSQL
- Node.js et npm (pour lancer les commandes TailwindCSS)

## Installation

### 1. Clonez le dépôt

```bash
git clone https://github.com/marouane-AITOUALI/restaurant.git
cd restaurant
```

### 2. Installez les dépendances

Assurez-vous d'avoir **Node.js**, **npm**, **Composer** et **Symfony CLI** installés, puis exécutez :

```bash
npm install
composer install
```

### 3. Rajouter votre propre fichier .env

Assurez-vous d'avoir dans votre .env :

```bash
'OPENWEATHER_API_KEY' => Permet d\'afficher la météo sur notre site Web
'DATABASE_URL' => Afin de créer votre bdd et passer la migration
'MAILER_DSN' => Pour l'envoi de mail/ Validation de l'inscription par mail 
```

### 4. Créer la base de données

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 5. Ajouter les fixtures :

```bash
php bin/console doctrine:fixtures:load
```

## Utilisation

### Démarrer en mode développement

```bash
npm run watch
symfony server:start
```

### Générer les fichiers pour la production

```bash
npm run build:css
```

## Structure du projet

```
KoulMaghreb/
├── assets
├── bin
├── config/
├── migrations/
├── node_modules/
├── public/
    ├── build/
├── src/
    ├── Controller/
    ├── DataFixtures/
    ├── Entity/
    ├── Form/
    ├── Repository/
    ├── Security/
├── templates/
├── tests/
├── composer.json
├── package.json
├── Dockerfile
├── tailwind.config.js
```

## Fonctionnalités

### Fonctionnalités utilisateur

- **Inscription & connexion** avec vérification par email
- **Gestion des réservations** (modification, suppression)
- **Accès aux plats et menus**
- **Participation aux événements**
- **Laisser des avis et notes sur les plats**

### Fonctionnalités administrateur

- **Tableau de bord** pour la gestion des utilisateurs
- **Gestion des événements et promotions**
- **Ajout, modification et suppression de plats**
- **Contrôle des réservations**

## Fonctionnalités futures

- **Chatbot IA** pour répondre aux questions des utilisateurs
- **Recommandations personnalisées** basées sur les préférences
- **Notifications d'événements et offres spéciales**


## Tests

- Vous avez dans le répertoire tests, deux dossiers (Controller: pour les tests fonctionnels / Unit: pour les test unitaires)

## Requêtes personnalisées avec Query Builder
Des requêtes personnalisées ont été implémentées dans les repositories en utilisant Query Builder. Ex :
- public function getDailySpecial(): array (PlatRepository)

## Gestion des rôles et permissions
Le projet implémente 4 rôles différents avec des permissions spécifiques :

- Rôle Utilisateur : Accès limité à la consultation des menu et plats, à la réservation et à la réservation des évènements.
- Rôle Administrateur : Accès complet à la gestion des utilisateurs, des réservations, des plats, commentaire... 
- Rôle Banni : Ce rôle est attribué aux utilisateurs bannis. Les utilisateurs avec ce rôle ne peuvent pas effectuer d'actions spécifiques sur le site, grâce au AccessDeniedHandler.
- ROLE_ORGANISATEUR_EVENT: Permet d'organiser des évènements et de gérer la réservation grâce au VoterPersonnalisé!.


## APIs et Voter:
### APIS
- Envoi de mail : L'application utilise Symfony mailer pour la vérification des users mails à l'inscription
- Accès à une API Externe: OpenWeather afin d'avoir la météo de la ville de Paris
### Voter
Voters personnalisés qui définit le rôle de ROLE_ORGANISATEUR_EVENT: Ce rôle permet de créer des évènements dans notre site !

## Fonctionnalités bonus
- Upload et Stockage des images en local
- DockerFile pour la conteneurisation du projet
- AccessDeniedHandler

## Contribuer

1. **Forkez** le dépôt.
2. **Créez une branche** pour votre fonctionnalité :
   ```bash
   git checkout -b feature/ma-fonctionnalité
   ```
3. **Commitez** vos modifications :
   ```bash
   git commit -m 'Ajout de ma fonctionnalité'
   ```
4. **Poussez votre branche** :
   ```bash
   git push origin feature/ma-fonctionnalité
   ```
5. **Ouvrez une Pull Request.**

## Déploiement

Le projet est actuellement déployé sur [Render](https://restaurant-704m.onrender.com/), mais requiert un plan payant pour assurer le bon fonctionnement de la base de données.

---
