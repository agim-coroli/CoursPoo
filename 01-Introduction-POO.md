# 📚 Module 1 : Introduction à la POO en PHP

> **Objectif** : Comprendre les concepts fondamentaux de la programmation orientée objet

## 🎯 Ce que vous allez apprendre

- Qu'est-ce que la programmation orientée objet
- Pourquoi utiliser la POO en PHP
- Différences entre programmation procédurale et orientée objet
- Avantages de la POO

---

## Qu'est-ce que la programmation orientée objet ?

La **Programmation Orientée Objet (POO)** est un paradigme de programmation qui organise le code autour de concepts du monde réel appelés **objets**. Chaque objet possède :

- **Des propriétés** (caractéristiques) - comme la couleur d'une voiture
- **Des méthodes** (actions) - comme démarrer ou freiner

### 🏠 Analogie avec la vie réelle

Imaginez une **maison** :

- **Propriétés** : nombre de pièces, couleur, taille, adresse
- **Méthodes** : ouvrir la porte, allumer la lumière, chauffer

En programmation, on crée des **classes** (modèles) pour représenter ces concepts.

---

## Pourquoi utiliser la POO en PHP ?

### ✅ Avantages de la POO

| **Avantage**        | **Description**                               | **Exemple**                                  |
| ------------------- | --------------------------------------------- | -------------------------------------------- |
| **Réutilisabilité** | Éviter la duplication de code                 | Une classe `User` utilisable partout         |
| **Maintenabilité**  | Code plus facile à modifier et déboguer       | Modifier une méthode affecte tous les objets |
| **Organisation**    | Structure claire et logique                   | Séparation des responsabilités               |
| **Évolutivité**     | Facilite l'ajout de nouvelles fonctionnalités | Héritage et polymorphisme                    |

### 🔄 Comparaison avec la programmation procédurale

| **Procédural**        | **Orienté Objet**         |
| --------------------- | ------------------------- |
| Fonctions isolées     | Méthodes dans des classes |
| Variables globales    | Propriétés encapsulées    |
| Code dupliqué         | Code réutilisable         |
| Difficile à maintenir | Facile à organiser        |

---

## Exemple concret : Gestion d'utilisateurs

### ❌ Approche procédurale (ancienne)

```php
<?php
// Variables globales
$user_name = "";
$user_email = "";
$user_age = 0;

// Fonctions isolées
function create_user($name, $email, $age) {
    global $user_name, $user_email, $user_age;
    $user_name = $name;
    $user_email = $email;
    $user_age = $age;
}

function get_user_info() {
    global $user_name, $user_email, $user_age;
    return "Nom: $user_name, Email: $user_email, Âge: $user_age";
}

// Problèmes :
// - Variables globales dangereuses
// - Code dupliqué pour chaque utilisateur
// - Difficile à maintenir
?>
```

### ✅ Approche orientée objet (moderne)

```php
<?php
class User {
    private $nom;
    private $email;
    private $age;

    public function __construct($nom, $email, $age) {
        $this->nom = $nom;
        $this->email = $email;
        $this->age = $age;
    }

    public function getInfo() {
        return "Nom: {$this->nom}, Email: {$this->email}, Âge: {$this->age}";
    }
}

// Utilisation
$user1 = new User("Alice", "alice@example.com", 25);
$user2 = new User("Bob", "bob@example.com", 30);

echo $user1->getInfo(); // Alice
echo $user2->getInfo(); // Bob
?>
```

---

## Les 4 piliers de la POO

### 1. 🏗️ **Encapsulation**

Cacher les détails internes et contrôler l'accès aux données.

```php
<?php
class CompteBancaire {
    private $solde; // Propriété privée

    public function consulterSolde() {
        return $this->solde; // Accès contrôlé
    }
}
?>
```

### 2. 🔄 **Héritage**

Une classe peut hériter des propriétés et méthodes d'une autre.

```php
<?php
class Animal {
    protected $nom;

    public function manger() {
        echo "{$this->nom} mange";
    }
}

class Chien extends Animal {
    public function aboyer() {
        echo "{$this->nom} aboie !";
    }
}
?>
```

### 3. 🎭 **Polymorphisme**

Un même nom de méthode peut avoir différents comportements.

```php
<?php
class Forme {
    public function calculerAire() {
        return 0;
    }
}

class Rectangle extends Forme {
    public function calculerAire() {
        return $this->largeur * $this->hauteur;
    }
}
?>
```

### 4. 🔧 **Abstraction**

Simplifier la complexité en se concentrant sur l'essentiel.

```php
<?php
abstract class Vehicule {
    abstract public function demarrer();
}

class Voiture extends Vehicule {
    public function demarrer() {
        echo "La voiture démarre !";
    }
}
?>
```

---

## 🎯 Exercices pratiques

### Exercice 1 : Créer votre première classe

Créez une classe `Personne` avec :

- Propriétés : `nom`, `age`
- Méthodes : `sePresenter()`, `feterAnniversaire()`

```php
<?php
class Personne {
    public $nom;
    public $age;

    public function __construct($nom, $age) {
        $this->nom = $nom;
        $this->age = $age;
    }

    public function sePresenter() {
        echo "Bonjour, je suis {$this->nom} et j'ai {$this->age} ans.";
    }

    public function feterAnniversaire() {
        $this->age++;
        echo "Joyeux anniversaire ! J'ai maintenant {$this->age} ans.";
    }
}

// Test
$personne = new Personne("Marie", 25);
$personne->sePresenter();
$personne->feterAnniversaire();
?>
```

### Exercice 2 : Comparaison procédural vs POO

**Version procédurale :**

```php
<?php
$produit_nom = "Laptop";
$produit_prix = 999;
$produit_stock = 5;

function afficher_produit($nom, $prix, $stock) {
    echo "Produit: $nom, Prix: $prix€, Stock: $stock";
}

function reduire_stock($stock) {
    return $stock - 1;
}
?>
```

**Version orientée objet :**

```php
<?php
class Produit {
    private $nom;
    private $prix;
    private $stock;

    public function __construct($nom, $prix, $stock) {
        $this->nom = $nom;
        $this->prix = $prix;
        $this->stock = $stock;
    }

    public function afficher() {
        echo "Produit: {$this->nom}, Prix: {$this->prix}€, Stock: {$this->stock}";
    }

    public function vendre() {
        if ($this->stock > 0) {
            $this->stock--;
            return true;
        }
        return false;
    }
}
?>
```

---

## 🎯 Points clés à retenir

✅ **La POO organise le code** autour d'objets du monde réel  
✅ **Chaque objet a des propriétés** (données) et des méthodes (actions)  
✅ **La POO améliore la réutilisabilité** et la maintenabilité du code  
✅ **Les 4 piliers** : Encapsulation, Héritage, Polymorphisme, Abstraction  
✅ **La POO est plus évolutive** que la programmation procédurale

---

## 🚀 Prochaines étapes

Maintenant que vous comprenez les concepts de base, passez au **[Module 2 : Les classes et objets](02-Classes-Objets.md)** pour apprendre à créer vos premières classes !

---

## 📚 Ressources complémentaires

- 📖 [Documentation PHP - Introduction à la POO](https://www.php.net/manual/fr/language.oop5.php)
- 🎥 [Vidéo : Introduction à la POO](https://www.youtube.com/watch?v=example)
- 📝 [Article : Pourquoi utiliser la POO ?](https://example.com)

---

**Bonne programmation ! 🎉**
