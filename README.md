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

---

### Étape 1 — Prérequis

Avant de commencer, assurez-vous d’avoir installé :
- PHP >= 8.1
- Composer
- Node.js >= 16
- NPM ou Yarn
- MySQL ou une autre base de données compatible Laravel
- Pusher (ou une alternative pour les notifications en temps réel)

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
Configuration de Pusher

Pour activer les notifications en temps réel, vous devez créer un compte sur Pusher :

Rendez-vous sur https://pusher.com/
 et créez un compte.

Créez une nouvelle application.

Dans les paramètres de l’application, copiez la clé Pusher.

Remplacez clé_pusher dans le fichier .env par votre clé Pusher.

⚡ Astuce : Vérifiez que le VITE_PUSHER_KEY correspond exactement à celui fourni par Pusher.





