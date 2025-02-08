# KoulMaghreb

**[KoulMaghreb](https://restaurant-704m.onrender.com/)** est une application web de gestion de restaurant maghrébin développée en Symfony. Ce projet vise à offrir une expérience utilisateur unique en regroupant les saveurs authentiques de l'Algérie, de la Tunisie et du Maroc.

## Table des matières

- [À propos du projet](#À-propos-du-projet)
- [Objectifs](#Objectifs)
- [Installation](#Installation)
- [Utilisation](#Utilisation)
- [Structure du projet](#Structure-du-projet)
- [Fonctionnalités](#Fonctionnalités)
  - [Fonctionnalités utilisateur](#Fonctionnalités-utilisateur)
  - [Fonctionnalités administrateur](#Fonctionnalités-administrateur)
- [Fonctionnalités futures](#Fonctionnalités-futures)
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

## Utilisation

### Démarrer en mode développement

```bash
symfony server:start
npm run watch
```

### Générer les fichiers pour la production

```bash
npm run build
```

## Structure du projet

```
KoulMaghreb/
├── public/
│   ├── index.html
│   ├── assets/
│   │   ├── css/
│   │   │   ├── styles.css
│   │   │   ├── tailwind.min.css
│   │   └── images/
├── src/
├── config/
├── migrations/
├── templates/
├── tailwind.config.js
├── package.json
├── composer.json
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
