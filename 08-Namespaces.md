# 📚 Module 8 : Les namespaces

> **Objectif** : Maîtriser les namespaces pour organiser et structurer votre code PHP

## 🎯 Ce que vous allez apprendre

- Qu'est-ce qu'un namespace
- Déclarer et utiliser des namespaces
- Comprendre `use` et `as`
- Organiser votre code avec les namespaces
- Utiliser l'autoloading avec Composer

---

## Qu'est-ce qu'un namespace ?

Un **namespace** est un espace de noms qui permet d'organiser le code et d'éviter les conflits de noms. C'est comme un système de dossiers pour vos classes.

### 📁 Analogie avec les dossiers

- **Namespace** = Dossier (organise les fichiers)
- **Classe** = Fichier (dans un dossier spécifique)
- **Conflit de noms** = Deux fichiers avec le même nom dans le même dossier
- **Solution** = Mettre les fichiers dans des dossiers différents

---

## Syntaxe de base des namespaces

### Déclaration d'un namespace

```php
<?php
namespace NomDuNamespace;

class MaClasse {
    // Code de la classe
}
?>
```

### Exemple simple

```php
<?php
// Fichier: src/Models/User.php
namespace App\Models;

class User {
    private $nom;
    private $email;

    public function __construct($nom, $email) {
        $this->nom = $nom;
        $this->email = $email;
    }

    public function getNom() {
        return $this->nom;
    }
}
?>
```

---

## Organisation du code avec les namespaces

### Structure de dossiers recommandée

```
src/
├── Models/
│   ├── User.php
│   ├── Product.php
│   └── Order.php
├── Controllers/
│   ├── UserController.php
│   ├── ProductController.php
│   └── OrderController.php
├── Services/
│   ├── UserService.php
│   ├── EmailService.php
│   └── PaymentService.php
└── Utils/
    ├── Validator.php
    └── Logger.php
```

### Exemple complet

```php
<?php
// Fichier: src/Models/User.php
namespace App\Models;

class User {
    private $id;
    private $nom;
    private $email;

    public function __construct($nom, $email) {
        $this->id = uniqid();
        $this->nom = $nom;
        $this->email = $email;
    }

    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getEmail() { return $this->email; }
}
?>
```

```php
<?php
// Fichier: src/Controllers/UserController.php
namespace App\Controllers;

use App\Models\User;
use App\Services\UserService;

class UserController {
    private $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    public function creerUtilisateur($nom, $email) {
        $user = new User($nom, $email);
        return $this->userService->sauvegarder($user);
    }

    public function listerUtilisateurs() {
        return $this->userService->getAll();
    }
}
?>
```

```php
<?php
// Fichier: src/Services/UserService.php
namespace App\Services;

use App\Models\User;

class UserService {
    private $users = [];

    public function sauvegarder(User $user) {
        $this->users[] = $user;
        return $user;
    }

    public function getAll() {
        return $this->users;
    }
}
?>
```

---

## Utilisation de `use` et `as`

### Import simple avec `use`

```php
<?php
namespace App\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Utils\Validator;

class UserController {
    private $userService;
    private $validator;

    public function __construct() {
        $this->userService = new UserService();
        $this->validator = new Validator();
    }

    public function creerUtilisateur($nom, $email) {
        if ($this->validator->validerEmail($email)) {
            $user = new User($nom, $email);
            return $this->userService->sauvegarder($user);
        }
        return false;
    }
}
?>
```

### Alias avec `as`

```php
<?php
namespace App\Controllers;

use App\Models\User as Utilisateur;
use App\Services\UserService as ServiceUtilisateur;
use App\Utils\Validator as Validateur;

class UserController {
    private $serviceUtilisateur;
    private $validateur;

    public function __construct() {
        $this->serviceUtilisateur = new ServiceUtilisateur();
        $this->validateur = new Validateur();
    }

    public function creerUtilisateur($nom, $email) {
        if ($this->validateur->validerEmail($email)) {
            $user = new Utilisateur($nom, $email);
            return $this->serviceUtilisateur->sauvegarder($user);
        }
        return false;
    }
}
?>
```

---

## Exemple complet : Système e-commerce

### Structure des namespaces

```php
<?php
// Fichier: src/Models/Product.php
namespace App\Models;

class Product {
    private $id;
    private $nom;
    private $prix;
    private $stock;

    public function __construct($nom, $prix, $stock = 0) {
        $this->id = uniqid();
        $this->nom = $nom;
        $this->prix = $prix;
        $this->stock = $stock;
    }

    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getPrix() { return $this->prix; }
    public function getStock() { return $this->stock; }

    public function reduireStock($quantite) {
        if ($quantite <= $this->stock) {
            $this->stock -= $quantite;
            return true;
        }
        return false;
    }
}
?>
```

```php
<?php
// Fichier: src/Models/Order.php
namespace App\Models;

use App\Models\Product;

class Order {
    private $id;
    private $products;
    private $total;
    private $date;

    public function __construct() {
        $this->id = uniqid();
        $this->products = [];
        $this->total = 0;
        $this->date = new \DateTime();
    }

    public function ajouterProduit(Product $product, $quantite = 1) {
        if ($product->reduireStock($quantite)) {
            $this->products[] = [
                'product' => $product,
                'quantite' => $quantite,
                'prix' => $product->getPrix() * $quantite
            ];
            $this->calculerTotal();
            return true;
        }
        return false;
    }

    private function calculerTotal() {
        $this->total = 0;
        foreach ($this->products as $item) {
            $this->total += $item['prix'];
        }
    }

    public function getTotal() { return $this->total; }
    public function getProducts() { return $this->products; }
}
?>
```

```php
<?php
// Fichier: src/Services/OrderService.php
namespace App\Services;

use App\Models\Order;
use App\Models\Product;

class OrderService {
    private $orders = [];

    public function creerCommande() {
        $order = new Order();
        $this->orders[] = $order;
        return $order;
    }

    public function ajouterProduit(Order $order, Product $product, $quantite = 1) {
        return $order->ajouterProduit($product, $quantite);
    }

    public function finaliserCommande(Order $order) {
        // Logique de finalisation
        echo "Commande {$order->getId()} finalisée pour {$order->getTotal()}€";
        return true;
    }
}
?>
```

```php
<?php
// Fichier: src/Controllers/OrderController.php
namespace App\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Services\OrderService;

class OrderController {
    private $orderService;

    public function __construct() {
        $this->orderService = new OrderService();
    }

    public function creerCommande() {
        $order = $this->orderService->creerCommande();
        return $order;
    }

    public function ajouterProduit(Order $order, Product $product, $quantite = 1) {
        return $this->orderService->ajouterProduit($order, $product, $quantite);
    }

    public function finaliserCommande(Order $order) {
        return $this->orderService->finaliserCommande($order);
    }
}
?>
```

---

## Autoloading avec Composer

### Configuration de Composer

```json
{
  "autoload": {
    "psr-4": {
      "App\\": "src/"
    }
  }
}
```

### Utilisation de l'autoloading

```php
<?php
// Fichier: index.php
require_once 'vendor/autoload.php';

use App\Models\Product;
use App\Models\Order;
use App\Controllers\OrderController;

// Maintenant on peut utiliser les classes directement
$product = new Product("Laptop", 999, 10);
$order = new Order();
$controller = new OrderController();

$controller->ajouterProduit($order, $product, 2);
$controller->finaliserCommande($order);
?>
```

---

## Gestion des conflits de noms

### Exemple de conflit

```php
<?php
// Fichier: src/Models/User.php
namespace App\Models;

class User {
    // Code de la classe
}
?>
```

```php
<?php
// Fichier: src/External/User.php
namespace App\External;

class User {
    // Code de la classe
}
?>
```

### Résolution du conflit

```php
<?php
namespace App\Controllers;

use App\Models\User as ModelUser;
use App\External\User as ExternalUser;

class UserController {
    public function creerUtilisateur() {
        $modelUser = new ModelUser();
        $externalUser = new ExternalUser();

        // Utilisation des deux classes sans conflit
    }
}
?>
```

---

## 🎯 Exercices pratiques

### Exercice 1 : Système de blog

Créez un système de blog avec les namespaces suivants :

- `App\Models` : Article, Comment, User
- `App\Controllers` : ArticleController, CommentController
- `App\Services` : ArticleService, CommentService

```php
<?php
// Fichier: src/Models/Article.php
namespace App\Models;

class Article {
    private $id;
    private $titre;
    private $contenu;
    private $auteur;
    private $dateCreation;

    public function __construct($titre, $contenu, $auteur) {
        $this->id = uniqid();
        $this->titre = $titre;
        $this->contenu = $contenu;
        $this->auteur = $auteur;
        $this->dateCreation = new \DateTime();
    }

    public function getId() { return $this->id; }
    public function getTitre() { return $this->titre; }
    public function getContenu() { return $this->contenu; }
    public function getAuteur() { return $this->auteur; }
    public function getDateCreation() { return $this->dateCreation; }
}
?>
```

```php
<?php
// Fichier: src/Models/Comment.php
namespace App\Models;

use App\Models\Article;

class Comment {
    private $id;
    private $contenu;
    private $auteur;
    private $article;
    private $dateCreation;

    public function __construct($contenu, $auteur, Article $article) {
        $this->id = uniqid();
        $this->contenu = $contenu;
        $this->auteur = $auteur;
        $this->article = $article;
        $this->dateCreation = new \DateTime();
    }

    public function getId() { return $this->id; }
    public function getContenu() { return $this->contenu; }
    public function getAuteur() { return $this->auteur; }
    public function getArticle() { return $this->article; }
    public function getDateCreation() { return $this->dateCreation; }
}
?>
```

```php
<?php
// Fichier: src/Services/ArticleService.php
namespace App\Services;

use App\Models\Article;

class ArticleService {
    private $articles = [];

    public function creerArticle($titre, $contenu, $auteur) {
        $article = new Article($titre, $contenu, $auteur);
        $this->articles[] = $article;
        return $article;
    }

    public function getArticle($id) {
        foreach ($this->articles as $article) {
            if ($article->getId() === $id) {
                return $article;
            }
        }
        return null;
    }

    public function getAllArticles() {
        return $this->articles;
    }
}
?>
```

```php
<?php
// Fichier: src/Controllers/ArticleController.php
namespace App\Controllers;

use App\Models\Article;
use App\Services\ArticleService;

class ArticleController {
    private $articleService;

    public function __construct() {
        $this->articleService = new ArticleService();
    }

    public function creerArticle($titre, $contenu, $auteur) {
        return $this->articleService->creerArticle($titre, $contenu, $auteur);
    }

    public function afficherArticle($id) {
        $article = $this->articleService->getArticle($id);
        if ($article) {
            echo "Titre : {$article->getTitre()}\n";
            echo "Auteur : {$article->getAuteur()}\n";
            echo "Date : {$article->getDateCreation()->format('Y-m-d H:i:s')}\n";
            echo "Contenu : {$article->getContenu()}\n";
        } else {
            echo "Article non trouvé";
        }
    }
}
?>
```

### Exercice 2 : Système de gestion d'école

Créez un système de gestion d'école avec :

- `App\Models` : Student, Teacher, Course
- `App\Services` : StudentService, TeacherService, CourseService
- `App\Controllers` : StudentController, TeacherController, CourseController

```php
<?php
// Fichier: src/Models/Student.php
namespace App\Models;

class Student {
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $age;
    private $niveau;

    public function __construct($nom, $prenom, $email, $age, $niveau) {
        $this->id = uniqid();
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->age = $age;
        $this->niveau = $niveau;
    }

    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getPrenom() { return $this->prenom; }
    public function getEmail() { return $this->email; }
    public function getAge() { return $this->age; }
    public function getNiveau() { return $this->niveau; }

    public function getNomComplet() {
        return $this->prenom . ' ' . $this->nom;
    }
}
?>
```

```php
<?php
// Fichier: src/Models/Course.php
namespace App\Models;

use App\Models\Teacher;

class Course {
    private $id;
    private $nom;
    private $description;
    private $teacher;
    private $duree;

    public function __construct($nom, $description, Teacher $teacher, $duree) {
        $this->id = uniqid();
        $this->nom = $nom;
        $this->description = $description;
        $this->teacher = $teacher;
        $this->duree = $duree;
    }

    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getDescription() { return $this->description; }
    public function getTeacher() { return $this->teacher; }
    public function getDuree() { return $this->duree; }
}
?>
```

```php
<?php
// Fichier: src/Services/StudentService.php
namespace App\Services;

use App\Models\Student;

class StudentService {
    private $students = [];

    public function creerEtudiant($nom, $prenom, $email, $age, $niveau) {
        $student = new Student($nom, $prenom, $email, $age, $niveau);
        $this->students[] = $student;
        return $student;
    }

    public function getEtudiant($id) {
        foreach ($this->students as $student) {
            if ($student->getId() === $id) {
                return $student;
            }
        }
        return null;
    }

    public function getAllEtudiants() {
        return $this->students;
    }

    public function getEtudiantsByNiveau($niveau) {
        return array_filter($this->students, function($student) use ($niveau) {
            return $student->getNiveau() === $niveau;
        });
    }
}
?>
```

---

## 🎯 Points clés à retenir

✅ **Les namespaces organisent** le code et évitent les conflits de noms  
✅ **`namespace`** déclare l'espace de noms d'un fichier  
✅ **`use`** importe une classe d'un namespace  
✅ **`as`** permet de créer un alias pour une classe  
✅ **L'autoloading** charge automatiquement les classes  
✅ **Composer** gère l'autoloading avec PSR-4

---

## 🚀 Prochaines étapes

Maintenant que vous maîtrisez les namespaces, passez au **[Module 9 : La gestion des erreurs](09-Gestion-Erreurs.md)** pour apprendre à gérer les erreurs de manière professionnelle !

---

## 📚 Ressources complémentaires

- 📖 [Documentation PHP - Namespaces](https://www.php.net/manual/fr/language.namespaces.php)
- 🎥 [Vidéo : Les namespaces en PHP](https://www.youtube.com/watch?v=example)
- 📝 [Article : Bonnes pratiques d'organisation](https://example.com)

---

**Bonne programmation ! 🎉**
