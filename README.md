# Médiathèque interne (PHP MVC)

Application de gestion de ressources (livres, matériel, supports de cours…)
organisée en architecture MVC, à partir d'une version PHP POO.

## Prérequis

- PHP 8.1 ou supérieur (testé avec PHP 8.3)
- MySQL ou MariaDB (XAMPP, Laragon, WAMP…)
- Extension PHP `pdo_mysql` activée
- Git

## Installation

1. Cloner le dépôt :
   ```bash
   git clone https://github.com/samuel-aw/Brief-PHP-MVC
   cd Brief_PHP_MVC
   ```
2. Démarrer MySQL.
3. Importer la base (crée `mediatheque_poo2`, la table `resources` et 5 exemples) :
   ```bash
   # Linux / macOS / Git Bash
   mysql -u root -p < database/schema.sql
   ```
   ```powershell
   # Windows PowerShell
   mysql -u root -p -e "source database/schema.sql"
   ```
   Ou via phpMyAdmin : onglet **Importer**, fichier `database/schema.sql`.
4. Créer la configuration :
   ```bash
   cp config/database.example.php config/database.php
   ```
   Puis adapter les identifiants dans `config/database.php` (fichier non versionné).

## Lancement

```bash
php -S localhost:8000 -t public
```

Ouvrir http://localhost:8000

## Routes

Toutes les requêtes passent par `public/index.php`.

| Méthode | URL | Contrôleur | Rôle |
|---|---|---|---|
| GET | `/` et `/resources` | `ResourceController::index` | Liste des ressources |
| GET | `/resources/show?id=3` | `ResourceController::show` | Détail d'une ressource |
| GET | `/resources/create` | `ResourceController::create` | Formulaire d'ajout |
| POST | `/resources/store` | `ResourceController::store` | Valide et enregistre |
| GET | `/resources/edit?id=3` | `ResourceController::edit` | Formulaire de modification |
| POST | `/resources/update?id=3` | `ResourceController::update` | Valide et met à jour |
| POST | `/resources/delete?id=3` | `ResourceController::delete` | Supprime |

Une URL inconnue renvoie une erreur 404. Une méthode non autorisée
(ex. GET sur `/resources/delete`) renvoie une erreur 405.

## Architecture MVC

```
Navigateur → public/index.php → Router → ResourceController → ResourceRepository → MySQL
                                              │
                                              ▼
Navigateur ← HTML ← View + layout ← données préparées par le contrôleur
```

- **Modèle** : `Resource` représente la donnée, `ResourceRepository` l'enregistre et la lit.
- **Vue** : les fichiers de `views/` affichent les données reçues.
- **Contrôleur** : `ResourceController` reçoit la requête, valide, appelle le repository, choisit la vue ou redirige.

Après un ajout, une modification ou une suppression, le contrôleur **redirige**
(motif Post/Redirect/Get) : pas de double soumission au rafraîchissement.

## Rôle de chaque dossier

| Dossier | Rôle |
|---|---|
| `public/` | Seul dossier accessible depuis le navigateur : `index.php` (point d'entrée et routes) et `assets/` |
| `app/Controllers/` | Contrôleurs : traitement des requêtes HTTP, sans HTML |
| `app/Models/` | Modèles : objets métier (`Resource`) |
| `app/Repositories/` | Accès aux données : toutes les requêtes SQL |
| `app/Core/` | Briques techniques : `Database`, `Router`, `View`, `FlashMessage`, `Validator` |
| `views/layouts/` | Gabarit HTML commun (`main.php`) |
| `views/resources/` | Vues des ressources (liste, ajout, modification, détail) |
| `views/errors/` | Vue des erreurs 404 et 405 |
| `config/` | Démarrage (`bootstrap.php`) et configuration de la base |
| `database/` | Script SQL |

## Sécurité

- Requêtes préparées PDO (dans le repository uniquement)
- Validation côté serveur avant insertion ou modification
- Échappement de toutes les données affichées (`h()` = `htmlspecialchars`)
- Identifiants hors de `public/` et exclus de Git
- Suppression uniquement en POST

## 5 cas de test manuels

1. **Liste** : ouvrir `/resources` → les ressources s'affichent, la plus récente en premier.
2. **Ajout valide** : remplir le formulaire → redirection vers la liste, message vert « Ressource ajoutée. », puis F5 : aucune ressource en double.
3. **Ajout invalide** : retirer l'attribut `required` (outils de développement) et envoyer un titre vide → le formulaire revient avec le message d'erreur et les valeurs saisies.
4. **Ressource inexistante** : ouvrir `/resources/edit?id=9999` → retour à la liste avec le message « Ressource introuvable. »
5. **Suppression et sécurité** : supprimer une ressource (confirmation puis message vert) ; ouvrir `/resources/delete?id=1` dans la barre d'adresse → erreur 405 ; créer une ressource au titre `<b>test</b>` → affiché tel quel, sans mise en gras.