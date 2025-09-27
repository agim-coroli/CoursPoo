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

### Exercice 1 : Système de gestion d'employés

**🎯 OBJECTIF :** Créer un système simple pour gérer les employés d'une entreprise et calculer leur salaire.

**📋 SCÉNARIO :** Vous travaillez pour une startup qui veut automatiser la gestion de ses employés. Vous devez créer un système qui permet de :

- Enregistrer un nouvel employé
- Afficher ses informations
- Calculer son salaire mensuel
- Gérer son anniversaire

**📝 SPÉCIFICATIONS :**

- Classe `Employe` avec propriétés : `nom`, `age`, `poste`, `salaireHoraire`, `heuresTravaillees`
- Méthodes : `sePresenter()`, `calculerSalaireMensuel()`, `feterAnniversaire()`, `changerPoste()`

**✅ RÉSULTAT ATTENDU :**

```
=== GESTION DES EMPLOYÉS ===
Nouvel employé créé : Alice Dupont, 28 ans, Développeuse
Salaire mensuel d'Alice : 3200€
Alice fête son anniversaire ! Elle a maintenant 29 ans
Alice a été promue : Développeuse Senior
Nouveau salaire mensuel : 4000€
```

**💻 SOLUTION :**

```php
<?php
class Employe {
    public $nom;
    public $age;
    public $poste;
    public $salaireHoraire;
    public $heuresTravaillees;

    public function __construct($nom, $age, $poste, $salaireHoraire, $heuresTravaillees = 160) {
        $this->nom = $nom;
        $this->age = $age;
        $this->poste = $poste;
        $this->salaireHoraire = $salaireHoraire;
        $this->heuresTravaillees = $heuresTravaillees;
    }

    public function sePresenter() {
        echo "Nouvel employé créé : {$this->nom}, {$this->age} ans, {$this->poste}\n";
    }

    public function calculerSalaireMensuel() {
        $salaire = $this->salaireHoraire * $this->heuresTravaillees;
        echo "Salaire mensuel de {$this->nom} : {$salaire}€\n";
        return $salaire;
    }

    public function feterAnniversaire() {
        $this->age++;
        echo "{$this->nom} fête son anniversaire ! Elle a maintenant {$this->age} ans\n";
    }

    public function changerPoste($nouveauPoste, $nouveauSalaire) {
        $ancienPoste = $this->poste;
        $this->poste = $nouveauPoste;
        $this->salaireHoraire = $nouveauSalaire;
        echo "{$this->nom} a été promue : {$nouveauPoste}\n";
        echo "Nouveau salaire mensuel : " . $this->calculerSalaireMensuel() . "€\n";
    }
}

// Test du système
echo "=== GESTION DES EMPLOYÉS ===\n";
$employe = new Employe("Alice Dupont", 28, "Développeuse", 20, 160);
$employe->sePresenter();
$employe->calculerSalaireMensuel();
$employe->feterAnniversaire();
$employe->changerPoste("Développeuse Senior", 25);
?>
```

### Exercice 2 : Système de gestion de stock

**🎯 OBJECTIF :** Comparer deux approches pour gérer l'inventaire d'un magasin et comprendre pourquoi la POO est meilleure.

**📋 SCÉNARIO :** Vous gérez un magasin de technologie. Vous devez :

- Suivre les produits en stock
- Gérer les ventes
- Afficher les statistiques
- Gérer plusieurs produits simultanément

**❌ PROBLÈME avec l'approche procédurale :**

- Variables globales dangereuses
- Code dupliqué pour chaque produit
- Difficile à maintenir

**✅ SOLUTION avec la POO :**

- Chaque produit est un objet indépendant
- Code réutilisable
- Facile à étendre

**📝 SPÉCIFICATIONS :**

- Classe `Produit` avec propriétés : `nom`, `prix`, `stock`
- Méthodes : `afficher()`, `vendre()`, `ajouterStock()`, `estDisponible()`

**✅ RÉSULTAT ATTENDU :**

```
=== GESTION DE STOCK ===
Produit: Laptop, Prix: 999€, Stock: 5
Vente effectuée ! Stock restant: 4
Stock ajouté ! Nouveau stock: 9
Produit: Souris, Prix: 25€, Stock: 10
Vente effectuée ! Stock restant: 9
=== STATISTIQUES ===
Laptop: 9 en stock (valeur: 8991€)
Souris: 9 en stock (valeur: 225€)
```

**💻 SOLUTION :**

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
        echo "Produit: {$this->nom}, Prix: {$this->prix}€, Stock: {$this->stock}\n";
    }

    public function vendre() {
        if ($this->stock > 0) {
            $this->stock--;
            echo "Vente effectuée ! Stock restant: {$this->stock}\n";
            return true;
        } else {
            echo "Produit épuisé !\n";
            return false;
        }
    }

    public function ajouterStock($quantite) {
        $this->stock += $quantite;
        echo "Stock ajouté ! Nouveau stock: {$this->stock}\n";
    }

    public function estDisponible() {
        return $this->stock > 0;
    }

    public function getValeurStock() {
        return $this->stock * $this->prix;
    }

    public function getInfo() {
        return [
            'nom' => $this->nom,
            'prix' => $this->prix,
            'stock' => $this->stock,
            'valeur' => $this->getValeurStock()
        ];
    }
}

// Test du système
echo "=== GESTION DE STOCK ===\n";
$laptop = new Produit("Laptop", 999, 5);
$souris = new Produit("Souris", 25, 10);

$laptop->afficher();
$laptop->vendre();
$laptop->ajouterStock(5);

$souris->afficher();
$souris->vendre();

echo "=== STATISTIQUES ===\n";
$produits = [$laptop, $souris];
foreach ($produits as $produit) {
    $info = $produit->getInfo();
    echo "{$info['nom']}: {$info['stock']} en stock (valeur: {$info['valeur']}€)\n";
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
