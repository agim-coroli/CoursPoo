# 📚 Module 2 : Les classes et objets

> **Objectif** : Maîtriser la création et l'utilisation des classes et objets en PHP

## 🎯 Ce que vous allez apprendre

- Déclarer une classe
- Créer et utiliser des objets
- Comprendre les propriétés et méthodes
- Utiliser le mot-clé `$this`
- Différencier classe et objet

---

## Qu'est-ce qu'une classe ?

Une **classe** est un modèle (template) qui définit la structure d'un objet. C'est comme un plan d'architecte pour construire une maison.

### 🏠 Analogie avec la construction

- **Classe** = Plan d'architecte (modèle)
- **Objet** = Maison construite (instance concrète)
- **Propriétés** = Caractéristiques (couleur, taille, nombre de pièces)
- **Méthodes** = Actions (ouvrir porte, allumer lumière)

---

## Déclaration d'une classe

### Syntaxe de base

```php
<?php
class NomDeLaClasse {
    // Propriétés (variables)
    public $propriete1;
    public $propriete2;

    // Méthodes (fonctions)
    public function nomDeLaMethode() {
        // Code de la méthode
    }
}
?>
```

### Exemple concret : Classe Voiture

```php
<?php
class Voiture {
    // Propriétés (caractéristiques)
    public $marque;
    public $couleur;
    public $vitesse = 0;

    // Méthodes (actions)
    public function demarrer() {
        echo "La voiture démarre !";
    }

    public function accelerer($kmh) {
        $this->vitesse += $kmh;
        echo "Vitesse actuelle : {$this->vitesse} km/h";
    }

    public function freiner() {
        $this->vitesse = max(0, $this->vitesse - 10);
        echo "Vitesse après freinage : {$this->vitesse} km/h";
    }
}
?>
```

---

## Création d'objets (instanciation)

Un **objet** est une instance concrète d'une classe. C'est comme construire une maison à partir du plan.

### Syntaxe d'instanciation

```php
<?php
// Création d'un objet
$monObjet = new NomDeLaClasse();
?>
```

### Exemple avec la classe Voiture

```php
<?php
// Création d'objets (instances)
$maVoiture = new Voiture();
$voitureDeMonAmi = new Voiture();

// Accès aux propriétés
$maVoiture->marque = "Toyota";
$maVoiture->couleur = "Rouge";

$voitureDeMonAmi->marque = "BMW";
$voitureDeMonAmi->couleur = "Noire";

// Appel des méthodes
$maVoiture->demarrer();
$maVoiture->accelerer(50);

$voitureDeMonAmi->demarrer();
$voitureDeMonAmi->accelerer(80);
?>
```

---

## Propriétés et méthodes

### 🏷️ Propriétés (Variables d'instance)

Les propriétés stockent les données de l'objet :

```php
<?php
class Personne {
    public $nom;        // Propriété publique
    public $age;        // Propriété publique
    public $email;      // Propriété publique
}

$personne = new Personne();
$personne->nom = "Alice";
$personne->age = 25;
$personne->email = "alice@example.com";
?>
```

### ⚙️ Méthodes (Fonctions d'instance)

Les méthodes définissent le comportement de l'objet :

```php
<?php
class Personne {
    public $nom;
    public $age;

    public function sePresenter() {
        echo "Bonjour, je suis {$this->nom} et j'ai {$this->age} ans.";
    }

    public function feterAnniversaire() {
        $this->age++;
        echo "Joyeux anniversaire ! J'ai maintenant {$this->age} ans.";
    }

    public function estMajeur() {
        return $this->age >= 18;
    }
}

$personne = new Personne();
$personne->nom = "Marie";
$personne->age = 17;

$personne->sePresenter();
$personne->feterAnniversaire();

if ($personne->estMajeur()) {
    echo "Vous êtes majeur !";
} else {
    echo "Vous êtes mineur.";
}
?>
```

---

## Le mot-clé `$this`

Le mot-clé `$this` fait référence à l'objet courant dans lequel on se trouve.

### 🎯 Utilisation de `$this`

```php
<?php
class CompteBancaire {
    private $solde;
    private $numeroCompte;

    public function __construct($numeroCompte, $soldeInitial = 0) {
        $this->numeroCompte = $numeroCompte;
        $this->solde = $soldeInitial;
    }

    public function deposer($montant) {
        $this->solde += $montant;
        echo "Dépôt de {$montant}€. Nouveau solde : {$this->solde}€";
    }

    public function retirer($montant) {
        if ($this->solde >= $montant) {
            $this->solde -= $montant;
            echo "Retrait de {$montant}€. Nouveau solde : {$this->solde}€";
        } else {
            echo "Solde insuffisant !";
        }
    }

    public function consulterSolde() {
        return $this->solde;
    }

    public function getInfo() {
        return "Compte {$this->numeroCompte} - Solde : {$this->solde}€";
    }
}

// Utilisation
$compte1 = new CompteBancaire("123456", 1000);
$compte2 = new CompteBancaire("789012", 500);

$compte1->deposer(200);  // $this fait référence à $compte1
$compte2->retirer(100);  // $this fait référence à $compte2

echo $compte1->getInfo();
echo $compte2->getInfo();
?>
```

---

## Différence entre classe et objet

### 📋 Classe vs Objet

| **Classe**            | **Objet**                   |
| --------------------- | --------------------------- |
| Modèle/Plan           | Instance concrète           |
| Définit la structure  | Utilise la structure        |
| Existe une seule fois | Peut exister plusieurs fois |
| Contient le code      | Contient les données        |

### 🏭 Analogie avec l'usine

```php
<?php
// La classe est comme un plan d'usine
class Produit {
    public $nom;
    public $prix;

    public function afficher() {
        echo "Produit : {$this->nom} - Prix : {$this->prix}€";
    }
}

// Les objets sont les produits fabriqués
$produit1 = new Produit();  // Premier produit
$produit1->nom = "Laptop";
$produit1->prix = 999;

$produit2 = new Produit();  // Deuxième produit
$produit2->nom = "Souris";
$produit2->prix = 25;

$produit1->afficher();  // Affiche : Produit : Laptop - Prix : 999€
$produit2->afficher();  // Affiche : Produit : Souris - Prix : 25€
?>
```

---

## 🎯 Exercices pratiques

### Exercice 1 : Classe Animal

Créez une classe `Animal` avec :

- Propriétés : `nom`, `espece`, `age`
- Méthodes : `manger()`, `dormir()`, `sePresenter()`

```php
<?php
class Animal {
    public $nom;
    public $espece;
    public $age;

    public function manger() {
        echo "{$this->nom} mange";
    }

    public function dormir() {
        echo "{$this->nom} dort";
    }

    public function sePresenter() {
        echo "Je suis {$this->nom}, un {$this->espece} de {$this->age} ans";
    }
}

// Test
$chien = new Animal();
$chien->nom = "Rex";
$chien->espece = "Chien";
$chien->age = 3;

$chat = new Animal();
$chat->nom = "Mimi";
$chat->espece = "Chat";
$chat->age = 2;

$chien->sePresenter();
$chat->manger();
?>
```

### Exercice 2 : Classe Calculatrice

Créez une classe `Calculatrice` avec :

- Propriété : `resultat`
- Méthodes : `additionner()`, `soustraire()`, `multiplier()`, `diviser()`, `afficherResultat()`

```php
<?php
class Calculatrice {
    private $resultat = 0;

    public function additionner($nombre) {
        $this->resultat += $nombre;
        return $this;
    }

    public function soustraire($nombre) {
        $this->resultat -= $nombre;
        return $this;
    }

    public function multiplier($nombre) {
        $this->resultat *= $nombre;
        return $this;
    }

    public function diviser($nombre) {
        if ($nombre != 0) {
            $this->resultat /= $nombre;
        } else {
            echo "Erreur : Division par zéro !";
        }
        return $this;
    }

    public function afficherResultat() {
        echo "Résultat : {$this->resultat}";
    }
}

// Test
$calc = new Calculatrice();
$calc->additionner(10)
     ->multiplier(2)
     ->soustraire(5)
     ->afficherResultat(); // Affiche : Résultat : 15
?>
```

### Exercice 3 : Classe Etudiant

Créez une classe `Etudiant` avec :

- Propriétés : `nom`, `notes` (tableau)
- Méthodes : `ajouterNote()`, `calculerMoyenne()`, `afficherBulletin()`

```php
<?php
class Etudiant {
    private $nom;
    private $notes = [];

    public function __construct($nom) {
        $this->nom = $nom;
    }

    public function ajouterNote($matiere, $note) {
        $this->notes[$matiere] = $note;
        echo "Note ajoutée : {$matiere} = {$note}/20";
    }

    public function calculerMoyenne() {
        if (empty($this->notes)) {
            return 0;
        }
        return array_sum($this->notes) / count($this->notes);
    }

    public function afficherBulletin() {
        echo "Bulletin de {$this->nom} :\n";
        foreach ($this->notes as $matiere => $note) {
            echo "- {$matiere} : {$note}/20\n";
        }
        echo "Moyenne générale : " . $this->calculerMoyenne() . "/20";
    }
}

// Test
$etudiant = new Etudiant("Alice");
$etudiant->ajouterNote("Maths", 15);
$etudiant->ajouterNote("Français", 18);
$etudiant->ajouterNote("Histoire", 12);
$etudiant->afficherBulletin();
?>
```

---

## 🎯 Points clés à retenir

✅ **Une classe est un modèle** qui définit la structure d'un objet  
✅ **Un objet est une instance** concrète d'une classe  
✅ **Les propriétés stockent les données** de l'objet  
✅ **Les méthodes définissent le comportement** de l'objet  
✅ **`$this` fait référence** à l'objet courant  
✅ **On peut créer plusieurs objets** à partir d'une même classe

---

## 🚀 Prochaines étapes

Maintenant que vous savez créer des classes et des objets, passez au **[Module 3 : L'encapsulation](03-Encapsulation.md)** pour apprendre à contrôler l'accès aux données !

---

## 📚 Ressources complémentaires

- 📖 [Documentation PHP - Classes et objets](https://www.php.net/manual/fr/language.oop5.basic.php)
- 🎥 [Vidéo : Créer sa première classe](https://www.youtube.com/watch?v=example)
- 📝 [Article : Bonnes pratiques pour les classes](https://example.com)

---

**Bonne programmation ! 🎉**
