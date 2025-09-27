# 📚 Module 6 : Le polymorphisme

> **Objectif** : Maîtriser le polymorphisme, les interfaces et les classes abstraites en PHP

## 🎯 Ce que vous allez apprendre

- Qu'est-ce que le polymorphisme
- Créer et utiliser des interfaces
- Comprendre les classes abstraites
- Implémenter le polymorphisme dans vos applications
- Différencier interfaces et classes abstraites

---

## Qu'est-ce que le polymorphisme ?

Le **polymorphisme** permet d'utiliser une interface commune pour différents types d'objets. C'est comme un interrupteur universel qui peut contrôler différents appareils.

### 🔌 Analogie avec les interrupteurs

- **Interface commune** = Interrupteur universel
- **Implémentations différentes** = Lampe, ventilateur, télévision
- **Même action** = Allumer/éteindre
- **Comportements différents** = Chaque appareil réagit différemment

---

## Les interfaces

Une **interface** définit un contrat que les classes doivent respecter. C'est comme un plan de construction que toutes les maisons doivent suivre.

### Syntaxe de base

```php
<?php
interface NomInterface {
    public function methode1();
    public function methode2($parametre);
}
?>
```

### Exemple : Interface Volant

```php
<?php
interface Volant {
    public function decoller();
    public function atterrir();
    public function voler();
}

class Oiseau implements Volant {
    public function decoller() {
        echo "L'oiseau décolle du sol !";
    }

    public function atterrir() {
        echo "L'oiseau atterrit sur une branche !";
    }

    public function voler() {
        echo "L'oiseau vole dans le ciel !";
    }
}

class Avion implements Volant {
    public function decoller() {
        echo "L'avion décolle de la piste !";
    }

    public function atterrir() {
        echo "L'avion atterrit sur la piste !";
    }

    public function voler() {
        echo "L'avion vole à haute altitude !";
    }
}

// Utilisation polymorphique
$objetsVolants = [
    new Oiseau(),
    new Avion()
];

foreach ($objetsVolants as $objet) {
    $objet->decoller();
    $objet->voler();
    $objet->atterrir();
}
?>
```

---

## Interfaces multiples

### Exemple : Canard qui vole et nage

```php
<?php
interface Volant {
    public function decoller();
    public function atterrir();
}

interface Nageant {
    public function nager();
    public function plonger();
}

class Canard implements Volant, Nageant {
    public function decoller() {
        echo "Le canard décolle de l'eau !";
    }

    public function atterrir() {
        echo "Le canard atterrit sur l'eau !";
    }

    public function nager() {
        echo "Le canard nage dans l'eau !";
    }

    public function plonger() {
        echo "Le canard plonge sous l'eau !";
    }

    public function sePresenter() {
        echo "Je suis un canard, je peux voler et nager !";
    }
}

class Poisson implements Nageant {
    public function nager() {
        echo "Le poisson nage dans l'eau !";
    }

    public function plonger() {
        echo "Le poisson plonge profondément !";
    }
}

// Test
$canard = new Canard();
$poisson = new Poisson();

$canard->sePresenter();
$canard->decoller();
$canard->nager();

$poisson->nager();
$poisson->plonger();
?>
```

---

## Les classes abstraites

Une **classe abstraite** ne peut pas être instanciée directement. Elle sert de modèle pour les classes enfants.

### Syntaxe de base

```php
<?php
abstract class ClasseAbstraite {
    // Propriétés
    protected $propriete;

    // Méthodes concrètes
    public function methodeConcrete() {
        // Code de la méthode
    }

    // Méthodes abstraites (doivent être implémentées)
    abstract public function methodeAbstraite();
}
?>
```

### Exemple : Système de paiement

```php
<?php
abstract class Paiement {
    protected $montant;
    protected $date;

    public function __construct($montant) {
        $this->montant = $montant;
        $this->date = new DateTime();
    }

    // Méthode concrète
    public function getMontant() {
        return $this->montant;
    }

    public function getDate() {
        return $this->date;
    }

    // Méthodes abstraites
    abstract public function traiterPaiement();
    abstract public function verifierValidite();
    abstract public function obtenirConfirmation();
}

class PaiementCarte extends Paiement {
    private $numeroCarte;
    private $codeCVV;

    public function __construct($montant, $numeroCarte, $codeCVV) {
        parent::__construct($montant);
        $this->numeroCarte = $numeroCarte;
        $this->codeCVV = $codeCVV;
    }

    public function traiterPaiement() {
        echo "Traitement du paiement par carte de {$this->montant}€";
    }

    public function verifierValidite() {
        // Simulation de vérification
        return strlen($this->numeroCarte) === 16 && strlen($this->codeCVV) === 3;
    }

    public function obtenirConfirmation() {
        if ($this->verifierValidite()) {
            echo "Paiement par carte confirmé";
            return true;
        }
        echo "Paiement par carte refusé";
        return false;
    }
}

class PaiementPayPal extends Paiement {
    private $email;
    private $motDePasse;

    public function __construct($montant, $email, $motDePasse) {
        parent::__construct($montant);
        $this->email = $email;
        $this->motDePasse = $motDePasse;
    }

    public function traiterPaiement() {
        echo "Traitement du paiement PayPal de {$this->montant}€";
    }

    public function verifierValidite() {
        // Simulation de vérification
        return filter_var($this->email, FILTER_VALIDATE_EMAIL) && strlen($this->motDePasse) >= 6;
    }

    public function obtenirConfirmation() {
        if ($this->verifierValidite()) {
            echo "Paiement PayPal confirmé";
            return true;
        }
        echo "Paiement PayPal refusé";
        return false;
    }
}

// Test
$paiements = [
    new PaiementCarte(100, "1234567890123456", "123"),
    new PaiementPayPal(50, "user@example.com", "password123")
];

foreach ($paiements as $paiement) {
    $paiement->traiterPaiement();
    if ($paiement->verifierValidite()) {
        $paiement->obtenirConfirmation();
    }
}
?>
```

---

## Polymorphisme en action

### Exemple : Système de formes géométriques

```php
<?php
interface Forme {
    public function calculerAire();
    public function calculerPerimetre();
    public function afficher();
}

class Rectangle implements Forme {
    private $largeur;
    private $hauteur;

    public function __construct($largeur, $hauteur) {
        $this->largeur = $largeur;
        $this->hauteur = $hauteur;
    }

    public function calculerAire() {
        return $this->largeur * $this->hauteur;
    }

    public function calculerPerimetre() {
        return 2 * ($this->largeur + $this->hauteur);
    }

    public function afficher() {
        echo "Rectangle {$this->largeur}x{$this->hauteur}";
    }
}

class Cercle implements Forme {
    private $rayon;

    public function __construct($rayon) {
        $this->rayon = $rayon;
    }

    public function calculerAire() {
        return pi() * $this->rayon * $this->rayon;
    }

    public function calculerPerimetre() {
        return 2 * pi() * $this->rayon;
    }

    public function afficher() {
        echo "Cercle de rayon {$this->rayon}";
    }
}

class Triangle implements Forme {
    private $base;
    private $hauteur;
    private $cote1;
    private $cote2;

    public function __construct($base, $hauteur, $cote1, $cote2) {
        $this->base = $base;
        $this->hauteur = $hauteur;
        $this->cote1 = $cote1;
        $this->cote2 = $cote2;
    }

    public function calculerAire() {
        return ($this->base * $this->hauteur) / 2;
    }

    public function calculerPerimetre() {
        return $this->base + $this->cote1 + $this->cote2;
    }

    public function afficher() {
        echo "Triangle de base {$this->base} et hauteur {$this->hauteur}";
    }
}

// Utilisation polymorphique
$formes = [
    new Rectangle(5, 3),
    new Cercle(4),
    new Triangle(6, 4, 5, 5)
];

foreach ($formes as $forme) {
    $forme->afficher();
    echo " - Aire : " . $forme->calculerAire();
    echo " - Périmètre : " . $forme->calculerPerimetre();
    echo "\n";
}
?>
```

---

## Différences entre interfaces et classes abstraites

### 📊 Comparaison

| **Interface**                                    | **Classe abstraite**                                  |
| ------------------------------------------------ | ----------------------------------------------------- |
| Ne peut pas avoir de propriétés                  | Peut avoir des propriétés                             |
| Toutes les méthodes sont abstraites              | Peut avoir des méthodes concrètes                     |
| Une classe peut implémenter plusieurs interfaces | Une classe ne peut hériter que d'une classe abstraite |
| Utilise `implements`                             | Utilise `extends`                                     |
| Pas de constructeur                              | Peut avoir un constructeur                            |

### 🎯 Quand utiliser quoi ?

**Utilisez une interface quand :**

- Vous voulez définir un contrat
- Plusieurs classes non liées doivent avoir le même comportement
- Vous voulez permettre l'héritage multiple

**Utilisez une classe abstraite quand :**

- Vous avez du code commun à partager
- Vous voulez forcer certaines méthodes
- Les classes sont liées par l'héritage

---

## 🎯 Exercices pratiques

### Exercice 1 : Système de véhicules

Créez un système de véhicules avec :

- Interface `Vehicule` : demarrer(), arreter(), accelerer()
- Classe `Voiture` : implémente Vehicule
- Classe `Moto` : implémente Vehicule
- Classe `Velo` : implémente Vehicule

```php
<?php
interface Vehicule {
    public function demarrer();
    public function arreter();
    public function accelerer($vitesse);
}

class Voiture implements Vehicule {
    private $marque;
    private $vitesse;

    public function __construct($marque) {
        $this->marque = $marque;
        $this->vitesse = 0;
    }

    public function demarrer() {
        echo "La voiture {$this->marque} démarre avec la clé";
    }

    public function arreter() {
        echo "La voiture {$this->marque} s'arrête";
        $this->vitesse = 0;
    }

    public function accelerer($vitesse) {
        $this->vitesse += $vitesse;
        echo "La voiture {$this->marque} accélère à {$this->vitesse} km/h";
    }
}

class Moto implements Vehicule {
    private $marque;
    private $vitesse;

    public function __construct($marque) {
        $this->marque = $marque;
        $this->vitesse = 0;
    }

    public function demarrer() {
        echo "La moto {$this->marque} démarre avec le kick";
    }

    public function arreter() {
        echo "La moto {$this->marque} s'arrête";
        $this->vitesse = 0;
    }

    public function accelerer($vitesse) {
        $this->vitesse += $vitesse;
        echo "La moto {$this->marque} accélère à {$this->vitesse} km/h";
    }
}

class Velo implements Vehicule {
    private $marque;
    private $vitesse;

    public function __construct($marque) {
        $this->marque = $marque;
        $this->vitesse = 0;
    }

    public function demarrer() {
        echo "Le vélo {$this->marque} démarre en pédalant";
    }

    public function arreter() {
        echo "Le vélo {$this->marque} s'arrête en freinant";
        $this->vitesse = 0;
    }

    public function accelerer($vitesse) {
        $this->vitesse += $vitesse;
        echo "Le vélo {$this->marque} accélère à {$this->vitesse} km/h";
    }
}

// Test
$vehicules = [
    new Voiture("BMW"),
    new Moto("Honda"),
    new Velo("Trek")
];

foreach ($vehicules as $vehicule) {
    $vehicule->demarrer();
    $vehicule->accelerer(50);
    $vehicule->arreter();
    echo "\n";
}
?>
```

### Exercice 2 : Système de lecteurs

Créez un système de lecteurs avec :

- Interface `Lecteur` : lire(), pause(), arreter()
- Classe `LecteurCD` : implémente Lecteur
- Classe `LecteurMP3` : implémente Lecteur
- Classe `LecteurVinyle` : implémente Lecteur

```php
<?php
interface Lecteur {
    public function lire();
    public function pause();
    public function arreter();
}

class LecteurCD implements Lecteur {
    private $marque;
    private $etat;

    public function __construct($marque) {
        $this->marque = $marque;
        $this->etat = "arrêté";
    }

    public function lire() {
        $this->etat = "lecture";
        echo "Le lecteur CD {$this->marque} lit un CD";
    }

    public function pause() {
        $this->etat = "pause";
        echo "Le lecteur CD {$this->marque} est en pause";
    }

    public function arreter() {
        $this->etat = "arrêté";
        echo "Le lecteur CD {$this->marque} s'arrête";
    }
}

class LecteurMP3 implements Lecteur {
    private $marque;
    private $etat;

    public function __construct($marque) {
        $this->marque = $marque;
        $this->etat = "arrêté";
    }

    public function lire() {
        $this->etat = "lecture";
        echo "Le lecteur MP3 {$this->marque} lit un fichier MP3";
    }

    public function pause() {
        $this->etat = "pause";
        echo "Le lecteur MP3 {$this->marque} est en pause";
    }

    public function arreter() {
        $this->etat = "arrêté";
        echo "Le lecteur MP3 {$this->marque} s'arrête";
    }
}

class LecteurVinyle implements Lecteur {
    private $marque;
    private $etat;

    public function __construct($marque) {
        $this->marque = $marque;
        $this->etat = "arrêté";
    }

    public function lire() {
        $this->etat = "lecture";
        echo "Le lecteur vinyle {$this->marque} lit un disque vinyle";
    }

    public function pause() {
        $this->etat = "pause";
        echo "Le lecteur vinyle {$this->marque} est en pause";
    }

    public function arreter() {
        $this->etat = "arrêté";
        echo "Le lecteur vinyle {$this->marque} s'arrête";
    }
}

// Test
$lecteurs = [
    new LecteurCD("Sony"),
    new LecteurMP3("Apple"),
    new LecteurVinyle("Technics")
];

foreach ($lecteurs as $lecteur) {
    $lecteur->lire();
    $lecteur->pause();
    $lecteur->arreter();
    echo "\n";
}
?>
```

### Exercice 3 : Système de calculatrices

Créez un système de calculatrices avec :

- Classe abstraite `Calculatrice` : calculer(), afficherResultat()
- Classe `CalculatriceSimple` : hérite de Calculatrice
- Classe `CalculatriceScientifique` : hérite de Calculatrice

```php
<?php
abstract class Calculatrice {
    protected $resultat;
    protected $historique;

    public function __construct() {
        $this->resultat = 0;
        $this->historique = [];
    }

    abstract public function calculer($operation, $a, $b = null);

    public function afficherResultat() {
        echo "Résultat : {$this->resultat}";
    }

    public function afficherHistorique() {
        echo "Historique des calculs :";
        foreach ($this->historique as $calcul) {
            echo "- {$calcul}";
        }
    }

    protected function ajouterHistorique($calcul) {
        $this->historique[] = $calcul;
    }
}

class CalculatriceSimple extends Calculatrice {
    public function calculer($operation, $a, $b = null) {
        switch ($operation) {
            case '+':
                $this->resultat = $a + $b;
                $this->ajouterHistorique("{$a} + {$b} = {$this->resultat}");
                break;
            case '-':
                $this->resultat = $a - $b;
                $this->ajouterHistorique("{$a} - {$b} = {$this->resultat}");
                break;
            case '*':
                $this->resultat = $a * $b;
                $this->ajouterHistorique("{$a} * {$b} = {$this->resultat}");
                break;
            case '/':
                if ($b != 0) {
                    $this->resultat = $a / $b;
                    $this->ajouterHistorique("{$a} / {$b} = {$this->resultat}");
                } else {
                    echo "Division par zéro impossible";
                }
                break;
        }
    }
}

class CalculatriceScientifique extends Calculatrice {
    public function calculer($operation, $a, $b = null) {
        switch ($operation) {
            case 'sin':
                $this->resultat = sin($a);
                $this->ajouterHistorique("sin({$a}) = {$this->resultat}");
                break;
            case 'cos':
                $this->resultat = cos($a);
                $this->ajouterHistorique("cos({$a}) = {$this->resultat}");
                break;
            case 'sqrt':
                $this->resultat = sqrt($a);
                $this->ajouterHistorique("√{$a} = {$this->resultat}");
                break;
            case 'pow':
                $this->resultat = pow($a, $b);
                $this->ajouterHistorique("{$a}^{$b} = {$this->resultat}");
                break;
        }
    }
}

// Test
$calcSimple = new CalculatriceSimple();
$calcSimple->calculer('+', 10, 5);
$calcSimple->afficherResultat();

$calcScientifique = new CalculatriceScientifique();
$calcScientifique->calculer('sin', pi()/2);
$calcScientifique->afficherResultat();
?>
```

---

## 🎯 Points clés à retenir

✅ **Le polymorphisme** permet d'utiliser une interface commune pour différents types d'objets  
✅ **Les interfaces** définissent un contrat que les classes doivent respecter  
✅ **Les classes abstraites** ne peuvent pas être instanciées directement  
✅ **`implements`** est utilisé pour implémenter une interface  
✅ **`extends`** est utilisé pour hériter d'une classe abstraite  
✅ **Le polymorphisme** améliore la flexibilité et la maintenabilité du code

---

## 🚀 Prochaines étapes

Maintenant que vous maîtrisez le polymorphisme, passez au **[Module 7 : Les traits](07-Traits.md)** pour apprendre à partager du code entre plusieurs classes !

---

## 📚 Ressources complémentaires

- 📖 [Documentation PHP - Interfaces](https://www.php.net/manual/fr/language.oop5.interfaces.php)
- 📖 [Documentation PHP - Classes abstraites](https://www.php.net/manual/fr/language.oop5.abstract.php)
- 🎥 [Vidéo : Le polymorphisme en PHP](https://www.youtube.com/watch?v=example)
- 📝 [Article : Interfaces vs Classes abstraites](https://example.com)

---

**Bonne programmation ! 🎉**


