# 📚 Module 3 : L'encapsulation

> **Objectif** : Maîtriser l'encapsulation et les modificateurs d'accès en PHP

## 🎯 Ce que vous allez apprendre

- Qu'est-ce que l'encapsulation
- Les modificateurs d'accès : public, private, protected
- Créer des getters et setters
- Bonnes pratiques d'encapsulation
- Contrôler l'accès aux données

---

## Qu'est-ce que l'encapsulation ?

L'**encapsulation** est le principe qui consiste à cacher les détails internes d'une classe et à contrôler l'accès aux données. C'est comme une boîte noire : on sait ce qu'elle fait, mais pas comment elle le fait.

### 🏠 Analogie avec une maison

- **Public** = Salon (accessible à tous)
- **Private** = Chambre (accessible seulement au propriétaire)
- **Protected** = Bureau (accessible au propriétaire et à sa famille)

---

## Les modificateurs d'accès

### 🔓 Public

Les propriétés et méthodes `public` sont accessibles partout.

```php
<?php
class CompteBancaire {
    public $numeroCompte;  // Accessible partout

    public function consulterSolde() {
        return "Solde consulté";
    }
}

$compte = new CompteBancaire();
$compte->numeroCompte = "123456";  // OK : public
echo $compte->consulterSolde();    // OK : public
?>
```

### 🔒 Private

Les propriétés et méthodes `private` sont accessibles uniquement dans la classe.

```php
<?php
class CompteBancaire {
    public $numeroCompte;
    private $solde;  // Accessible seulement dans cette classe

    public function consulterSolde() {
        return $this->solde;  // OK : même classe
    }

    public function deposer($montant) {
        if ($montant > 0) {
            $this->solde += $montant;  // OK : même classe
            return true;
        }
        return false;
    }
}

$compte = new CompteBancaire();
$compte->numeroCompte = "123456";  // OK : public
// $compte->solde = 1000;  // ERREUR : private
echo $compte->consulterSolde();    // OK : méthode publique
?>
```

### 🛡️ Protected

Les propriétés et méthodes `protected` sont accessibles dans la classe et ses enfants.

```php
<?php
class Vehicule {
    protected $marque;  // Accessible dans cette classe et ses enfants
    private $vitesse;

    public function __construct($marque) {
        $this->marque = $marque;
        $this->vitesse = 0;
    }
}

class Voiture extends Vehicule {
    public function afficherMarque() {
        return $this->marque;  // OK : classe enfant
    }

    public function accelerer($kmh) {
        $this->vitesse += $kmh;  // ERREUR : $vitesse est private
    }
}

$voiture = new Voiture("BMW");
echo $voiture->afficherMarque();  // OK
// echo $voiture->marque;  // ERREUR : protected
?>
```

---

## Getters et setters

Les **getters** et **setters** permettent de contrôler l'accès aux propriétés privées.

### 🔍 Getters (Accesseurs)

Les getters permettent de lire les propriétés privées.

```php
<?php
class Utilisateur {
    private $nom;
    private $email;
    private $age;

    public function __construct($nom, $email, $age) {
        $this->nom = $nom;
        $this->email = $email;
        $this->age = $age;
    }

    // Getters
    public function getNom() {
        return $this->nom;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getAge() {
        return $this->age;
    }
}

$user = new Utilisateur("Alice", "alice@example.com", 25);
echo $user->getNom();   // Alice
echo $user->getEmail(); // alice@example.com
echo $user->getAge();   // 25
?>
```

### ✏️ Setters (Mutateurs)

Les setters permettent de modifier les propriétés privées avec validation.

```php
<?php
class Utilisateur {
    private $nom;
    private $email;
    private $age;

    // Setters avec validation
    public function setNom($nom) {
        if (strlen($nom) >= 2) {
            $this->nom = $nom;
            return true;
        }
        return false;
    }

    public function setEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
            return true;
        }
        return false;
    }

    public function setAge($age) {
        if ($age >= 0 && $age <= 120) {
            $this->age = $age;
            return true;
        }
        return false;
    }
}

$user = new Utilisateur("Alice", "alice@example.com", 25);

// Tests des setters
if ($user->setNom("Bob")) {
    echo "Nom modifié avec succès";
} else {
    echo "Nom invalide";
}

if ($user->setEmail("bob@example.com")) {
    echo "Email modifié avec succès";
} else {
    echo "Email invalide";
}

if ($user->setAge(30)) {
    echo "Âge modifié avec succès";
} else {
    echo "Âge invalide";
}
?>
```

---

## Exemple complet : Classe CompteBancaire

```php
<?php
class CompteBancaire {
    private $numeroCompte;
    private $solde;
    private $codeSecret;

    public function __construct($numeroCompte, $soldeInitial = 0) {
        $this->numeroCompte = $numeroCompte;
        $this->solde = $soldeInitial;
        $this->codeSecret = rand(1000, 9999);
    }

    // Getters
    public function getNumeroCompte() {
        return $this->numeroCompte;
    }

    public function getSolde() {
        return $this->solde;
    }

    // Méthodes publiques
    public function deposer($montant) {
        if ($montant > 0) {
            $this->solde += $montant;
            echo "Dépôt de {$montant}€ effectué. Nouveau solde : {$this->solde}€";
            return true;
        }
        echo "Montant invalide";
        return false;
    }

    public function retirer($montant, $codeSecret) {
        if ($codeSecret !== $this->codeSecret) {
            echo "Code secret incorrect";
            return false;
        }

        if ($montant > 0 && $montant <= $this->solde) {
            $this->solde -= $montant;
            echo "Retrait de {$montant}€ effectué. Nouveau solde : {$this->solde}€";
            return true;
        }
        echo "Montant invalide ou solde insuffisant";
        return false;
    }

    public function consulterSolde($codeSecret) {
        if ($codeSecret !== $this->codeSecret) {
            echo "Code secret incorrect";
            return false;
        }
        echo "Solde actuel : {$this->solde}€";
        return $this->solde;
    }
}

// Utilisation
$compte = new CompteBancaire("123456", 1000);
$compte->deposer(500);
$compte->retirer(200, 1234);  // Code incorrect
$compte->consulterSolde(1234); // Code incorrect
?>
```

---

## Bonnes pratiques d'encapsulation

### ✅ Règles à suivre

1. **Utiliser `private` par défaut** pour les propriétés
2. **Créer des getters/setters** pour les propriétés importantes
3. **Valider les données** dans les setters
4. **Documenter** les méthodes avec des commentaires
5. **Ne pas exposer** les détails d'implémentation

### ❌ À éviter

```php
<?php
// ❌ Mauvaise pratique
class Utilisateur {
    public $nom;        // Trop permissif
    public $email;      // Pas de validation
    public $password;   // DANGEREUX !

    public function afficher() {
        echo $this->nom;  // Pas de contrôle
    }
}
?>
```

### ✅ Bonne pratique

```php
<?php
// ✅ Bonne pratique
class Utilisateur {
    private $nom;
    private $email;
    private $passwordHash;  // Hashé, pas en clair

    public function getNom() {
        return $this->nom;
    }

    public function setNom($nom) {
        if (strlen($nom) >= 2) {
            $this->nom = trim($nom);
            return true;
        }
        return false;
    }

    public function setPassword($password) {
        if (strlen($password) >= 8) {
            $this->passwordHash = password_hash($password, PASSWORD_DEFAULT);
            return true;
        }
        return false;
    }

    public function afficher() {
        echo "Nom : " . $this->getNom();
    }
}
?>
```

---

## 🎯 Exercices pratiques

### Exercice 1 : Classe Produit avec encapsulation

Créez une classe `Produit` avec :

- Propriétés privées : `nom`, `prix`, `stock`
- Getters et setters avec validation
- Méthodes : `vendre()`, `reapprovisionner()`, `afficherInfo()`

```php
<?php
class Produit {
    private $nom;
    private $prix;
    private $stock;

    public function __construct($nom, $prix, $stock = 0) {
        $this->setNom($nom);
        $this->setPrix($prix);
        $this->setStock($stock);
    }

    // Getters
    public function getNom() { return $this->nom; }
    public function getPrix() { return $this->prix; }
    public function getStock() { return $this->stock; }

    // Setters avec validation
    public function setNom($nom) {
        if (strlen($nom) >= 2) {
            $this->nom = trim($nom);
            return true;
        }
        return false;
    }

    public function setPrix($prix) {
        if ($prix > 0) {
            $this->prix = $prix;
            return true;
        }
        return false;
    }

    public function setStock($stock) {
        if ($stock >= 0) {
            $this->stock = $stock;
            return true;
        }
        return false;
    }

    // Méthodes métier
    public function vendre($quantite) {
        if ($quantite > 0 && $quantite <= $this->stock) {
            $this->stock -= $quantite;
            return true;
        }
        return false;
    }

    public function reapprovisionner($quantite) {
        if ($quantite > 0) {
            $this->stock += $quantite;
            return true;
        }
        return false;
    }

    public function afficherInfo() {
        echo "Produit : {$this->nom} - Prix : {$this->prix}€ - Stock : {$this->stock}";
    }
}

// Test
$produit = new Produit("Laptop", 999.99, 10);
$produit->afficherInfo();
$produit->vendre(2);
$produit->reapprovisionner(5);
$produit->afficherInfo();
?>
```

### Exercice 2 : Classe Etudiant avec notes

Créez une classe `Etudiant` avec :

- Propriétés privées : `nom`, `notes` (tableau)
- Méthodes : `ajouterNote()`, `calculerMoyenne()`, `getMeilleureNote()`, `getPireNote()`

```php
<?php
class Etudiant {
    private $nom;
    private $notes = [];

    public function __construct($nom) {
        $this->nom = $nom;
    }

    public function getNom() {
        return $this->nom;
    }

    public function ajouterNote($matiere, $note) {
        if ($note >= 0 && $note <= 20) {
            $this->notes[$matiere] = $note;
            return true;
        }
        return false;
    }

    public function calculerMoyenne() {
        if (empty($this->notes)) {
            return 0;
        }
        return array_sum($this->notes) / count($this->notes);
    }

    public function getMeilleureNote() {
        if (empty($this->notes)) {
            return null;
        }
        return max($this->notes);
    }

    public function getPireNote() {
        if (empty($this->notes)) {
            return null;
        }
        return min($this->notes);
    }

    public function afficherBulletin() {
        echo "Bulletin de {$this->nom} :\n";
        foreach ($this->notes as $matiere => $note) {
            echo "- {$matiere} : {$note}/20\n";
        }
        echo "Moyenne : " . $this->calculerMoyenne() . "/20\n";
        echo "Meilleure note : " . $this->getMeilleureNote() . "/20\n";
        echo "Pire note : " . $this->getPireNote() . "/20\n";
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

### Exercice 3 : Classe Compte avec sécurité

Créez une classe `Compte` avec :

- Propriétés privées : `numero`, `solde`, `codeSecret`
- Méthodes sécurisées : `deposer()`, `retirer()`, `consulterSolde()`
- Validation des codes secrets

```php
<?php
class Compte {
    private $numero;
    private $solde;
    private $codeSecret;

    public function __construct($numero, $codeSecret, $soldeInitial = 0) {
        $this->numero = $numero;
        $this->codeSecret = $codeSecret;
        $this->solde = $soldeInitial;
    }

    public function getNumero() {
        return $this->numero;
    }

    private function verifierCode($code) {
        return $code === $this->codeSecret;
    }

    public function deposer($montant, $code) {
        if (!$this->verifierCode($code)) {
            echo "Code secret incorrect";
            return false;
        }

        if ($montant > 0) {
            $this->solde += $montant;
            echo "Dépôt de {$montant}€ effectué. Nouveau solde : {$this->solde}€";
            return true;
        }
        echo "Montant invalide";
        return false;
    }

    public function retirer($montant, $code) {
        if (!$this->verifierCode($code)) {
            echo "Code secret incorrect";
            return false;
        }

        if ($montant > 0 && $montant <= $this->solde) {
            $this->solde -= $montant;
            echo "Retrait de {$montant}€ effectué. Nouveau solde : {$this->solde}€";
            return true;
        }
        echo "Montant invalide ou solde insuffisant";
        return false;
    }

    public function consulterSolde($code) {
        if (!$this->verifierCode($code)) {
            echo "Code secret incorrect";
            return false;
        }
        echo "Solde actuel : {$this->solde}€";
        return $this->solde;
    }
}

// Test
$compte = new Compte("123456", 1234, 1000);
$compte->deposer(500, 1234);  // Code correct
$compte->retirer(200, 5678); // Code incorrect
$compte->consulterSolde(1234); // Code correct
?>
```

---

## 🎯 Points clés à retenir

✅ **L'encapsulation protège les données** en contrôlant l'accès  
✅ **`private`** : accessible seulement dans la classe  
✅ **`protected`** : accessible dans la classe et ses enfants  
✅ **`public`** : accessible partout  
✅ **Les getters/setters** permettent de contrôler l'accès aux propriétés privées  
✅ **Toujours valider les données** dans les setters  
✅ **Utiliser `private` par défaut** pour les propriétés

---

## 🚀 Prochaines étapes

Maintenant que vous maîtrisez l'encapsulation, passez au **[Module 4 : Constructeurs et destructeurs](04-Constructeurs-Destructeurs.md)** pour apprendre à initialiser et nettoyer vos objets !

---

## 📚 Ressources complémentaires

- 📖 [Documentation PHP - Visibilité](https://www.php.net/manual/fr/language.oop5.visibility.php)
- 🎥 [Vidéo : L'encapsulation en PHP](https://www.youtube.com/watch?v=example)
- 📝 [Article : Bonnes pratiques d'encapsulation](https://example.com)

---

**Bonne programmation ! 🎉**
