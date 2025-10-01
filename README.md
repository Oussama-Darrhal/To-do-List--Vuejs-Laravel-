# To-Do List Application

# 📌 Introduction du Projet

Ce projet est une application full-stack "To-Do List" développée dans le cadre d'un test technique.  
L'objectif est de concevoir une application permettant la gestion sécurisée des tâches avec authentification JWT, intégration de notifications en temps réel via Pusher, et une interface moderne avec Vue.js et Pinia.  
Ce projet vise à démontrer la capacité à structurer un projet professionnel en respectant les bonnes pratiques de développement.

# 📌 Structure du Projet

Le projet est organisé en plusieurs dossiers principaux pour une meilleure organisation et clarté :

- **doc/** : Contient la documentation technique complète (`Documentation_Technique.pdf`) décrivant la conception, les diagrammes, la structure de la base de données, et le plan de développement du projet.
- **client/** : Contient le code frontend réalisé avec Vue.js, Pinia et Shadcn.
- **backend/** : Contient le code backend réalisé avec Laravel, gérant l'API, l'authentification JWT, et la logique métier.

# 📌 Configuration de l'application

Suivez ces étapes pour configurer et exécuter l'application To-Do List localement :

### Étape 1 — Prérequis

Avant de commencer, assurez-vous d’avoir installé :

- PHP >= 8.1
- Composer
- Node.js >= 16
- NPM ou Yarn
- MySQL ou une autre base de données compatible Laravel
- Pusher (un compte actif et une clé API)

---

### Étape 2 — Cloner le dépôt

```bash
git clone <url-du-dépôt>
cd <dossier-du-dépôt>
```

---

### Étape 3 — Configuration du backend (Laravel)

```bash
composer install
```

Copier le fichier .env.example vers .env :

```bash
cp .env.example .env
```

Éditer le fichier .env pour configurer la base de données, l’URL de l’API et les clés Pusher/JWT :

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nom_de_votre_base
DB_USERNAME=utilisateur
DB_PASSWORD=mot_de_passe

VITE_API_BASE_URL=http://localhost:8000/api
VITE_PUSHER_KEY=clé_pusher
VITE_PUSHER_HOST=127.0.0.1
VITE_PUSHER_PORT=6001
VITE_PUSHER_TLS=false
```

#### Configuration de Pusher

Pour activer les notifications en temps réel, vous devez créer un compte sur **Pusher** :

1. Rendez-vous sur [https://pusher.com/](https://pusher.com/) et créez un compte.
2. Créez une nouvelle application.
3. Dans les paramètres de l’application, copiez la **clé Pusher**.
4. Remplacez `clé_pusher` dans le fichier `.env` par votre clé Pusher.

⚡ **Astuce** : Vérifiez que le `VITE_PUSHER_KEY` correspond exactement à celui fourni par Pusher.

Générer la clé de l’application :

```bash
php artisan key:generate
php artisan jwt:secret
```

Lier le stockage pour les images :

```bash
php artisan storage:link
```

Lancer les migrations :

```bash
php artisan migrate
```

Démarrer le backend Laravel :

```bash
php artisan serve
```

---

### Étape 4 — Configuration du frontend (Vue.js)

Installer les dépendances :

```bash
npm install
```

Créer manuellement un fichier .env à la racine du projet frontend,
Ajouter les variables d’environnement suivantes dans .env :

```bash
VITE_API_BASE_URL=http://localhost:8000/api
VITE_PUSHER_KEY=clé_pusher
VITE_PUSHER_HOST=127.0.0.1
VITE_PUSHER_PORT=6001
VITE_PUSHER_TLS=false
```

Pour obtenir votre clé Pusher, rendez-vous sur https://pusher.com/
et créez une application. Copiez la clé fournie dans VITE_PUSHER_KEY.

Lancer le serveur de développement frontend :

```bash
npm run dev
```

---

### Étape 5 — Accéder à l’application

Ouvrez votre navigateur et allez sur :

```bash
http://localhost:5173
```

---

### Étape 5 — Tester l'application

1. Inscrivez-vous via la page **Register**.
2. Connectez-vous via la page **Login**.
3. Créez une tâche.
4. Vérifiez que la tâche apparaît dans la liste.
5. Essayez de modifier, compléter ou supprimer une tâche.
6. Vérifiez les notifications en temps réel.

# Auteur

**Oussama DARRHAL**  
[LinkedIn](https://www.linkedin.com/in/oussama-darrhal-6344ba250/)  
Développeur Full Stack
