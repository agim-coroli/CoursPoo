# 📚 Module 1 : Introduction à la POO en PHP

> **Objectif** : Découvrir les concepts de base de la programmation orientée objet

## 🎯 Ce que vous allez apprendre

- Qu'est-ce qu'une classe
- Qu'est-ce qu'un objet
- Les propriétés et méthodes
- Comment instancier un objet
- Votre premier exercice pratique

---

## Qu'est-ce qu'une classe ?

Une **classe** est un modèle (plan) qui définit la structure d'un objet. C'est comme un plan d'architecte pour construire une maison.

### 🏠 Analogie simple

- **Classe** = Plan d'architecte
- **Objet** = Maison construite à partir du plan
- **Propriétés** = Caractéristiques (couleur, nombre de pièces)
- **Méthodes** = Actions (ouvrir porte, allumer lumière)

---

## Qu'est-ce qu'un objet ?

Un **objet** est une instance concrète d'une classe. C'est comme construire une vraie maison à partir du plan.

### Exemple simple

```php
<?php
// La classe (le plan)
class Voiture {
    // Propriétés (caractéristiques)
    public $marque;
    public $couleur;

    // Méthodes (actions)
    public function demarrer() {
        echo "La voiture démarre !";
    }

    public function afficher() {
        echo "Voiture : {$this->marque}, Couleur : {$this->couleur}";
    }
}

// L'objet (la maison construite)
$maVoiture = new Voiture();
$maVoiture->marque = "Toyota";
$maVoiture->couleur = "Rouge";
$maVoiture->demarrer();
$maVoiture->afficher();
?>
```

---

## Les propriétés

Les **propriétés** sont les caractéristiques de l'objet. Ce sont des variables qui stockent des données.

### Syntaxe

```php
<?php
class Personne {
    public $nom;        // Propriété publique
    public $age;         // Propriété publique
    public $email;       // Propriété publique
}
?>
```

### Utilisation

```php
<?php
$personne = new Personne();
$personne->nom = "Alice";
$personne->age = 25;
$personne->email = "alice@example.com";
?>
```

---

## Les méthodes

Les **méthodes** sont les actions que peut effectuer l'objet. Ce sont des fonctions à l'intérieur de la classe.

### Syntaxe

```php
<?php
class Personne {
    public $nom;
    public $age;

    // Méthode
    public function sePresenter() {
        echo "Bonjour, je m'appelle {$this->nom} et j'ai {$this->age} ans";
    }

    // Autre méthode
    public function feterAnniversaire() {
        $this->age++;
        echo "Joyeux anniversaire ! J'ai maintenant {$this->age} ans";
    }
}
?>
```

### Utilisation

```php
<?php
$personne = new Personne();
$personne->nom = "Alice";
$personne->age = 25;

$personne->sePresenter();        // Appel de la méthode
$personne->feterAnniversaire(); // Appel de la méthode
?>
```

---

## Comment instancier un objet ?

L'**instanciation** est le processus de création d'un objet à partir d'une classe.

### Syntaxe

```php
<?php
$nomDeLobjet = new NomDeLaClasse();
?>
```

### Exemple complet

```php
<?php
class Animal {
    public $nom;
    public $espece;

    public function crier() {
        echo "{$this->nom} fait du bruit !";
    }

    public function sePresenter() {
        echo "Je suis {$this->nom}, un {$this->espece}";
    }
}

// Création de 2 objets différents
$chien = new Animal();
$chien->nom = "Rex";
$chien->espece = "Chien";

$chat = new Animal();
$chat->nom = "Minou";
$chat->espece = "Chat";

// Utilisation des objets
$chien->sePresenter();
$chien->crier();

$chat->sePresenter();
$chat->crier();
?>
```

---

## 🎯 Exercice pratique

### 🎯 Objectif

Créer votre première classe et l'utiliser.

### 📋 Instructions

1. Créez une classe `Livre` avec 3 propriétés : `titre`, `auteur`, `pages`
2. Ajoutez 2 méthodes : `afficher()` et `lire()`
3. Instanciez 2 objets différents
4. Utilisez les méthodes sur chaque objet

### ✅ Résultat attendu

```
Livre : Le Petit Prince, Auteur : Antoine de Saint-Exupéry, Pages : 96
Je lis Le Petit Prince...
Livre : Harry Potter, Auteur : J.K. Rowling, Pages : 320
Je lis Harry Potter...
```

### 💻 Solution

```php
<?php
class Livre {
    public $titre;
    public $auteur;
    public $pages;

    public function afficher() {
        echo "Livre : {$this->titre}, Auteur : {$this->auteur}, Pages : {$this->pages}\n";
    }

    public function lire() {
        echo "Je lis {$this->titre}...\n";
    }
}

// Création des objets
$livre1 = new Livre();
$livre1->titre = "Le Petit Prince";
$livre1->auteur = "Antoine de Saint-Exupéry";
$livre1->pages = 96;

$livre2 = new Livre();
$livre2->titre = "Harry Potter";
$livre2->auteur = "J.K. Rowling";
$livre2->pages = 320;

// Utilisation des objets
$livre1->afficher();
$livre1->lire();

$livre2->afficher();
$livre2->lire();
?>
```

---

## 🎯 Points clés à retenir

✅ **Une classe** est un modèle (plan)  
✅ **Un objet** est une instance concrète de la classe  
✅ **Les propriétés** stockent les données de l'objet  
✅ **Les méthodes** définissent les actions de l'objet  
✅ **`new`** permet de créer un objet  
✅ **`$this`** fait référence à l'objet courant

---

## 🚀 Prochaines étapes

Maintenant que vous savez créer des classes et des objets, passez au **[Module 2 : Les classes et objets](02-Classes-Objets.md)** pour apprendre le typage strict et la sécurité !

---

## 📚 Ressources complémentaires

- 📖 [Documentation PHP - Introduction à la POO](https://www.php.net/manual/fr/language.oop5.php)
- 🎥 [Vidéo : Introduction à la POO](https://www.youtube.com/results?search_query=introduction+poo+php&sp=EgIQAQ%253D%253D)
- 📝 [Article : Pourquoi utiliser la POO ?](https://example.com)

---

**Bonne programmation ! 🎉**
