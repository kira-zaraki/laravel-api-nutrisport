# NutriSport – Test Technique (API Laravel)

## Présentation

Ce projet est le backend développé pour le **test technique NutriSport**.  
Il s’agit d’une **API REST en Laravel 12** destinée à plusieurs sites e-commerce :

- nutri-sport.fr  
- nutri-sport.it  
- nutri-sport.be  

L’API expose des endpoints pour :

- Gestion du catalogue produits  
- Authentification client (JWT)  
- Gestion du panier  
- Gestion des commandes  
- BackOffice pour les agents  
- Flux public produits (JSON / XML)  
- Rapport quotidien des ventes (cron job)

Le frontend est prévu en React / Vue / Angular et consomme cette API.

---

## Stack Technique

- PHP 8.2+  
- Laravel 12  
- MySQL  
- Cache Laravel pour le panier  
- Authentification JWT  
- Docker via Laravel Sail  
- Développement sous **WSL2** (Ubuntu) sur Windows

---

## Installation (Laravel Sail sous WSL)

1. Cloner le dépôt :

```bash
git clone <repository-url>
cd laravel-api-nutrisport
```

2. Installer les dépendances :

```bash
./vendor/bin/sail composer install
```

3. Démarrer Sail (Docker) :

```bash
./vendor/bin/sail up -d
```

4. Exécuter les migrations :

```bash
./vendor/bin/sail artisan migrate
```

5. Générer la clé de l’application :

```bash
./vendor/bin/sail artisan key:generate
```

## Authentification API

L’authentification est gérée via JWT.

- Durée du token client : 6 heures
- Durée du token agent : 8 heures

Routes disponibles :

- POST /api/register → Créer un compte utilisateur
- POST /api/login → Connexion utilisateur
- GET /api/refresh → Rafraîchir le token JWT

## Liste des Routes API

### Authentification

| Méthode | Route         | Description                 |
| ------- | ------------- | --------------------------- |
| POST    | /api/register | Créer un utilisateur        |
| POST    | /api/login    | Authentifier un utilisateur |
| GET     | /api/refresh  | Rafraîchir le token JWT     |

### Commandes

| Méthode | Route       | Description                                        |
| ------- | ----------- | -------------------------------------------------- |
| POST    | /api/order  | Créer une nouvelle commande                        |
| GET     | /api/orders | Historique des commandes de l’utilisateur connecté |

### Produits

| Méthode | Route                                | Description                                  |
| ------- | ------------------------------------ | -------------------------------------------- |
| GET     | /api/products                        | Liste de tous les produits                   |
| GET     | /api/products/{product}              | Détails d’un produit                         |
| GET     | /api/products/site/{site}            | Produits disponibles pour un site spécifique |
| GET     | /api/sites/{site}/products/{product} | Détail d’un produit pour un site donné       |

### Flux Catalogue

| Méthode | Route                    | Description                |
| ------- | ------------------------ | -------------------------- |
| GET     | /api/feeds/products.json | Flux catalogue JSON public |
| GET     | /api/feeds/products.xml  | Flux catalogue XML public  |


Les flux incluent :

- ID produit
- Nom
- Disponibilité en stock

L’architecture est extensible pour ajouter facilement de nouveaux formats.

## Panier (Cache Laravel)

Caractéristiques :

- Panier stocké dans le cache Laravel
- Durée de vie : 3 jours
- L’utilisateur n’a pas besoin d’être authentifié
- Identification via cart_id

Routes :

- POST /api/cart/add → Ajouter un produit au panier
- POST /api/cart/remove → Supprimer un produit du panier
- POST /api/cart/show → Afficher le contenu du panier

## Profil Utilisateur

- PUT /api/user/profile → Mettre à jour les informations personnelles

## BackOffice (Agents)

Routes protégées par le middleware CheckAgent :

- GET /api/agent/orders → Liste des commandes des 5 derniers jours
- POST /api/agent/product → Créer un produit

L’agent avec ID = 1 a un accès complet à toutes les ressources.

## Gestion des Commandes

Lors de la création d’une commande :

- Récupération du panier depuis le cache
- Vérification du stock disponible
- Création de la commande et des lignes de commande
- Décrémentation du stock produit dans une transaction sécurisée
- Suppression du panier du cache
- Déclenchement de l’événement OrderCreated

## Gestion du Stock

- Stock partagé entre tous les sites
- Validation avant création de commande
- Transaction sécurisée pour éviter les incohérences
- Échec de la commande si le stock est insuffisant

## Rapport Quotidien des Ventes (Cron Job)

Une commande artisan génère un rapport des ventes du jour précédent (J-1).

Informations incluses :

- Produit le plus vendu
- Produit le moins vendu
- Produit CA max
- Produit CA min
- CA par site

Exécution manuelle :


```bash
./vendor/bin/sail artisan report:sales
```
Planification automatique : tous les jours à minuit.

## Base de Données

- users
- sites
- products
- product_prices
- orders
- order_items
- Le stock est partagé entre les sites
- Les prix peuvent varier selon le site

## Git Workflow

Branches principales :

- feature/auth
- feature/products
- feature/cart
- feature/orders
- feature/backoffice
- feature/feeds
- feature/reports

Commits fréquents et explicatifs pour chaque fonctionnalité.

## Commandes utiles

Lister toutes les routes :

```bash
./vendor/bin/sail artisan route:list
```

Exécuter le rapport quotidien :

```bash
./vendor/bin/sail artisan report:sales
```

## Tester l'API avec Postman

Une collection Postman est fournie pour tester facilement tous les endpoints.

- Télécharger le fichier JSON : `postman/NutriSport_API.postman_collection.json`  
- Importer dans Postman via **File → Import → Upload Files**

## Conclusion

Ce projet démontre :

- Développement d’une API REST avec Laravel 12
- Architecture claire et maintenable (Services, Events, Enums)
- Gestion du panier via cache Laravel
- Gestion du stock et des commandes avec transactions
- Flux catalogue public (JSON / XML)
- Rapport quotidien automatisé via cron job
- Développement sous WSL2 + Laravel Sail pour environnement stable sous Windows