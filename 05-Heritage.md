# 📚 Module 5 : L'héritage

> **Objectif** : Maîtriser l'héritage et la surcharge de méthodes en PHP

## 🎯 Ce que vous allez apprendre

- Qu'est-ce que l'héritage
- Utiliser `extends` pour créer des classes enfants
- Comprendre `parent::` et `self::`
- Surcharger les méthodes
- Gérer l'héritage multiple avec les traits

---

## Qu'est-ce que l'héritage ?

L'**héritage** permet à une classe d'hériter des propriétés et méthodes d'une autre classe. C'est comme une famille : les enfants héritent des caractéristiques de leurs parents.

### 👨‍👩‍👧‍👦 Analogie familiale

- **Classe parente** = Parents (caractéristiques communes)
- **Classe enfant** = Enfants (héritent + ont leurs propres caractéristiques)
- **Héritage** = Transmission des traits familiaux

---

## Syntaxe de l'héritage avec `extends`

### Syntaxe de base

```php
<?php
class ClasseParente {
    // Propriétés et méthodes
}

class ClasseEnfant extends ClasseParente {
    // Nouvelles propriétés et méthodes
    // + hérite de celles de la classe parente
}
?>
```

### Exemple simple : Animal et Chien

```php
<?php
// Classe parente
class Animal {
    protected $nom;
    protected $age;

    public function __construct($nom, $age) {
        $this->nom = $nom;
        $this->age = $age;
    }

    public function manger() {
        echo "{$this->nom} mange";
    }

    public function dormir() {
        echo "{$this->nom} dort";
    }

    public function sePresenter() {
        echo "Je suis {$this->nom} et j'ai {$this->age} ans";
    }
}

// Classe enfant
class Chien extends Animal {
    private $race;

    public function __construct($nom, $age, $race) {
        parent::__construct($nom, $age); // Appel du constructeur parent
        $this->race = $race;
    }

    // Surcharge de la méthode manger
    public function manger() {
        echo "{$this->nom} mange ses croquettes";
    }

    // Nouvelle méthode spécifique au chien
    public function aboyer() {
        echo "{$this->nom} aboie : Wouf ! Wouf !";
    }

    public function getRace() {
        return $this->race;
    }
}

// Utilisation
$monChien = new Chien("Rex", 3, "Labrador");
$monChien->sePresenter(); // Hérité de Animal
$monChien->manger();      // Surchargé dans Chien
$monChien->aboyer();      // Spécifique à Chien
?>
```

---

## `parent::` et `self::`

### 🏠 `parent::` - Appel de la classe parente

```php
<?php
class Vehicule {
    protected $marque;
    protected $vitesse;

    public function __construct($marque) {
        $this->marque = $marque;
        $this->vitesse = 0;
    }

    public function accelerer($kmh) {
        $this->vitesse += $kmh;
    }

    public function getInfo() {
        return "{$this->marque} - Vitesse : {$this->vitesse} km/h";
    }
}

class Voiture extends Vehicule {
    private $nombrePortes;

    public function __construct($marque, $nombrePortes) {
        parent::__construct($marque); // Appel du constructeur parent
        $this->nombrePortes = $nombrePortes;
    }

    public function getInfo() {
        // self:: fait référence à la classe courante
        $infoParent = parent::getInfo(); // Appel de la méthode parent
        return $infoParent . " - Portes : {$this->nombrePortes}";
    }
}

$voiture = new Voiture("BMW", 5);
$voiture->accelerer(60);
echo $voiture->getInfo();
?>
```

### 🔄 `self::` - Référence à la classe courante

```php
<?php
class CompteBancaire {
    private static $nombreComptes = 0;
    private $numeroCompte;
    private $solde;

    public function __construct($soldeInitial = 0) {
        self::$nombreComptes++; // self:: pour les propriétés statiques
        $this->numeroCompte = "COMPTE" . self::$nombreComptes;
        $this->solde = $soldeInitial;
    }

    public static function getNombreComptes() {
        return self::$nombreComptes;
    }

    public function getInfo() {
        return "Compte : {$this->numeroCompte} - Solde : {$this->solde}€";
    }
}

$compte1 = new CompteBancaire(1000);
$compte2 = new CompteBancaire(500);
echo "Nombre de comptes : " . CompteBancaire::getNombreComptes();
?>
```

---

## Surcharge de méthodes

### 🔄 Redéfinition des méthodes parentes

```php
<?php
class Forme {
    protected $couleur;

    public function __construct($couleur) {
        $this->couleur = $couleur;
    }

    public function calculerAire() {
        return 0; // Méthode de base
    }

    public function afficher() {
        echo "Forme de couleur {$this->couleur}";
    }
}

class Rectangle extends Forme {
    private $largeur;
    private $hauteur;

    public function __construct($couleur, $largeur, $hauteur) {
        parent::__construct($couleur);
        $this->largeur = $largeur;
        $this->hauteur = $hauteur;
    }

    // Surcharge de la méthode calculerAire
    public function calculerAire() {
        return $this->largeur * $this->hauteur;
    }

    // Surcharge de la méthode afficher
    public function afficher() {
        parent::afficher(); // Appel de la méthode parent
        echo " - Rectangle {$this->largeur}x{$this->hauteur}";
    }
}

class Cercle extends Forme {
    private $rayon;

    public function __construct($couleur, $rayon) {
        parent::__construct($couleur);
        $this->rayon = $rayon;
    }

    // Surcharge de la méthode calculerAire
    public function calculerAire() {
        return pi() * $this->rayon * $this->rayon;
    }

    // Surcharge de la méthode afficher
    public function afficher() {
        parent::afficher(); // Appel de la méthode parent
        echo " - Cercle de rayon {$this->rayon}";
    }
}

// Test
$rectangle = new Rectangle("Rouge", 5, 3);
$cercle = new Cercle("Bleu", 4);

$rectangle->afficher();
echo " - Aire : " . $rectangle->calculerAire();

$cercle->afficher();
echo " - Aire : " . $cercle->calculerAire();
?>
```

---

## Exemple complet : Système de véhicules

```php
<?php
class Vehicule {
    protected $marque;
    protected $modele;
    protected $vitesse;
    protected $carburant;

    public function __construct($marque, $modele) {
        $this->marque = $marque;
        $this->modele = $modele;
        $this->vitesse = 0;
        $this->carburant = 100;
    }

    public function accelerer($kmh) {
        if ($this->carburant > 0) {
            $this->vitesse += $kmh;
            $this->carburant -= 5;
            echo "{$this->marque} {$this->modele} accélère à {$this->vitesse} km/h";
        } else {
            echo "Plus de carburant !";
        }
    }

    public function freiner() {
        $this->vitesse = max(0, $this->vitesse - 10);
        echo "{$this->marque} {$this->modele} freine à {$this->vitesse} km/h";
    }

    public function faireLePlein() {
        $this->carburant = 100;
        echo "Plein fait pour {$this->marque} {$this->modele}";
    }

    public function getInfo() {
        return "{$this->marque} {$this->modele} - Vitesse : {$this->vitesse} km/h - Carburant : {$this->carburant}%";
    }
}

class Voiture extends Vehicule {
    private $nombrePortes;
    private $climatisation;

    public function __construct($marque, $modele, $nombrePortes) {
        parent::__construct($marque, $modele);
        $this->nombrePortes = $nombrePortes;
        $this->climatisation = false;
    }

    public function ouvrirPorte($numeroPorte) {
        if ($numeroPorte >= 1 && $numeroPorte <= $this->nombrePortes) {
            echo "Porte {$numeroPorte} ouverte";
        } else {
            echo "Porte inexistante";
        }
    }

    public function activerClimatisation() {
        $this->climatisation = true;
        echo "Climatisation activée";
    }

    public function getInfo() {
        $infoParent = parent::getInfo();
        return $infoParent . " - Portes : {$this->nombrePortes} - Climatisation : " . ($this->climatisation ? "ON" : "OFF");
    }
}

class Moto extends Vehicule {
    private $type;
    private $casque;

    public function __construct($marque, $modele, $type) {
        parent::__construct($marque, $modele);
        $this->type = $type;
        $this->casque = false;
    }

    public function mettreCasque() {
        $this->casque = true;
        echo "Casque mis";
    }

    public function accelerer($kmh) {
        if (!$this->casque) {
            echo "Attention ! Mettez votre casque avant de démarrer";
            return;
        }
        parent::accelerer($kmh);
    }

    public function getInfo() {
        $infoParent = parent::getInfo();
        return $infoParent . " - Type : {$this->type} - Casque : " . ($this->casque ? "Mis" : "Non mis");
    }
}

// Test
$voiture = new Voiture("BMW", "X5", 5);
$moto = new Moto("Honda", "CBR", "Sport");

$voiture->accelerer(50);
$voiture->ouvrirPorte(1);
$voiture->activerClimatisation();
echo $voiture->getInfo();

$moto->mettreCasque();
$moto->accelerer(80);
echo $moto->getInfo();
?>
```

---

## Héritage multiple et traits

PHP ne supporte pas l'héritage multiple, mais utilise les **traits** pour contourner cette limitation.

### 🧬 Exemple avec les traits

```php
<?php
trait Volant {
    public function decoller() {
        echo "Décollage en cours...";
    }

    public function atterrir() {
        echo "Atterrissage en cours...";
    }
}

trait Nageant {
    public function nager() {
        echo "Nage en cours...";
    }
}

class Canard {
    use Volant, Nageant; // Utilisation de plusieurs traits

    public function sePresenter() {
        echo "Je suis un canard, je peux voler et nager !";
    }
}

$canard = new Canard();
$canard->sePresenter();
$canard->decoller();
$canard->nager();
?>
```

---

## 🎯 Exercices pratiques

### Exercice 1 : Système d'employés

Créez une hiérarchie d'employés :

- Classe `Employe` (parente) : nom, salaire, dateEmbauche
- Classe `EmployeTempsPlein` : hérite d'Employe
- Classe `EmployeTempsPartiel` : hérite d'Employe, ajoute heuresTravaillees

```php
<?php
class Employe {
    protected $nom;
    protected $salaire;
    protected $dateEmbauche;

    public function __construct($nom, $salaire) {
        $this->nom = $nom;
        $this->salaire = $salaire;
        $this->dateEmbauche = new DateTime();
    }

    public function calculerSalaire() {
        return $this->salaire;
    }

    public function getInfo() {
        return "Employé : {$this->nom} - Salaire : {$this->salaire}€";
    }
}

class EmployeTempsPlein extends Employe {
    private $avantages;

    public function __construct($nom, $salaire, $avantages = []) {
        parent::__construct($nom, $salaire);
        $this->avantages = $avantages;
    }

    public function calculerSalaire() {
        $salaireBase = parent::calculerSalaire();
        $totalAvantages = array_sum($this->avantages);
        return $salaireBase + $totalAvantages;
    }

    public function getInfo() {
        $infoParent = parent::getInfo();
        return $infoParent . " - Avantages : " . array_sum($this->avantages) . "€";
    }
}

class EmployeTempsPartiel extends Employe {
    private $heuresTravaillees;
    private $tauxHoraire;

    public function __construct($nom, $tauxHoraire, $heuresTravaillees) {
        parent::__construct($nom, 0); // Salaire calculé différemment
        $this->tauxHoraire = $tauxHoraire;
        $this->heuresTravaillees = $heuresTravaillees;
    }

    public function calculerSalaire() {
        return $this->tauxHoraire * $this->heuresTravaillees;
    }

    public function getInfo() {
        return "Employé temps partiel : {$this->nom} - Heures : {$this->heuresTravaillees} - Taux : {$this->tauxHoraire}€/h";
    }
}

// Test
$employe1 = new EmployeTempsPlein("Alice", 3000, [200, 150]);
$employe2 = new EmployeTempsPartiel("Bob", 20, 30);

echo $employe1->getInfo() . " - Salaire total : " . $employe1->calculerSalaire() . "€";
echo $employe2->getInfo() . " - Salaire total : " . $employe2->calculerSalaire() . "€";
?>
```

### Exercice 2 : Système de comptes bancaires

Créez une hiérarchie de comptes :

- Classe `Compte` (parente) : numero, solde, dateCreation
- Classe `CompteCourant` : hérite de Compte, ajoute decouvert
- Classe `CompteEpargne` : hérite de Compte, ajoute tauxInteret

```php
<?php
class Compte {
    protected $numero;
    protected $solde;
    protected $dateCreation;

    public function __construct($numero, $soldeInitial = 0) {
        $this->numero = $numero;
        $this->solde = $soldeInitial;
        $this->dateCreation = new DateTime();
    }

    public function deposer($montant) {
        if ($montant > 0) {
            $this->solde += $montant;
            echo "Dépôt de {$montant}€ effectué";
            return true;
        }
        return false;
    }

    public function retirer($montant) {
        if ($montant > 0 && $montant <= $this->solde) {
            $this->solde -= $montant;
            echo "Retrait de {$montant}€ effectué";
            return true;
        }
        echo "Montant invalide ou solde insuffisant";
        return false;
    }

    public function getSolde() {
        return $this->solde;
    }

    public function getInfo() {
        return "Compte {$this->numero} - Solde : {$this->solde}€";
    }
}

class CompteCourant extends Compte {
    private $decouvert;

    public function __construct($numero, $soldeInitial = 0, $decouvert = 0) {
        parent::__construct($numero, $soldeInitial);
        $this->decouvert = $decouvert;
    }

    public function retirer($montant) {
        if ($montant > 0 && $montant <= ($this->solde + $this->decouvert)) {
            $this->solde -= $montant;
            echo "Retrait de {$montant}€ effectué";
            if ($this->solde < 0) {
                echo " - Découvert utilisé : " . abs($this->solde) . "€";
            }
            return true;
        }
        echo "Montant invalide ou découvert insuffisant";
        return false;
    }

    public function getInfo() {
        $infoParent = parent::getInfo();
        return $infoParent . " - Découvert autorisé : {$this->decouvert}€";
    }
}

class CompteEpargne extends Compte {
    private $tauxInteret;

    public function __construct($numero, $soldeInitial = 0, $tauxInteret = 0.02) {
        parent::__construct($numero, $soldeInitial);
        $this->tauxInteret = $tauxInteret;
    }

    public function calculerInterets() {
        $interets = $this->solde * $this->tauxInteret;
        $this->solde += $interets;
        echo "Intérêts calculés : {$interets}€";
        return $interets;
    }

    public function retirer($montant) {
        if ($montant > 0 && $montant <= $this->solde) {
            $this->solde -= $montant;
            echo "Retrait de {$montant}€ effectué";
            return true;
        }
        echo "Montant invalide ou solde insuffisant";
        return false;
    }

    public function getInfo() {
        $infoParent = parent::getInfo();
        return $infoParent . " - Taux d'intérêt : " . ($this->tauxInteret * 100) . "%";
    }
}

// Test
$compteCourant = new CompteCourant("CC001", 1000, 500);
$compteEpargne = new CompteEpargne("CE001", 5000, 0.03);

$compteCourant->retirer(1200); // Utilise le découvert
$compteEpargne->calculerInterets();

echo $compteCourant->getInfo();
echo $compteEpargne->getInfo();
?>
```

---

## 🎯 Points clés à retenir

✅ **L'héritage permet** de créer des classes qui héritent d'autres classes  
✅ **`extends`** est le mot-clé pour l'héritage  
✅ **`parent::`** permet d'appeler les méthodes de la classe parente  
✅ **`self::`** fait référence à la classe courante  
✅ **La surcharge** permet de redéfinir les méthodes parentes  
✅ **L'héritage multiple** n'est pas supporté, mais les traits le permettent

---

## 🚀 Prochaines étapes

Maintenant que vous maîtrisez l'héritage, passez au **[Module 6 : Le polymorphisme](06-Polymorphisme.md)** pour apprendre à utiliser des interfaces communes pour différents types d'objets !

---

## 📚 Ressources complémentaires

- 📖 [Documentation PHP - Héritage](https://www.php.net/manual/fr/language.oop5.inheritance.php)
- 🎥 [Vidéo : L'héritage en PHP](https://www.youtube.com/watch?v=example)
- 📝 [Article : Bonnes pratiques d'héritage](https://example.com)

---

**Bonne programmation ! 🎉**

