# 📚 Module 4 : Constructeurs et destructeurs

> **Objectif** : Maîtriser l'initialisation et le nettoyage des objets avec les constructeurs et destructeurs

## 🎯 Ce que vous allez apprendre

- Qu'est-ce qu'un constructeur
- Utiliser `__construct()` pour initialiser les objets
- Qu'est-ce qu'un destructeur
- Utiliser `__destruct()` pour nettoyer les ressources
- Bonnes pratiques d'initialisation

---

## Qu'est-ce qu'un constructeur ?

Un **constructeur** est une méthode spéciale appelée automatiquement lors de la création d'un objet. C'est comme un plan de construction qui s'exécute à chaque fois qu'on crée une nouvelle instance.

### 🏗️ Analogie avec la construction

- **Constructeur** = Plan de construction d'une maison
- **Appel automatique** = Le plan s'exécute dès qu'on commence à construire
- **Initialisation** = Mise en place des fondations, des murs, etc.

---

## Le constructeur `__construct()`

### Syntaxe de base

```php
<?php
class NomDeLaClasse {
    public function __construct() {
        // Code d'initialisation
    }
}
?>
```

### Exemple simple

```php
<?php
class Personne {
    private $nom;
    private $age;

    public function __construct($nom, $age) {
        $this->nom = $nom;
        $this->age = $age;
        echo "Personne créée : {$nom}, {$age} ans";
    }

    public function sePresenter() {
        echo "Bonjour, je suis {$this->nom} et j'ai {$this->age} ans";
    }
}

// Création d'objets - le constructeur est appelé automatiquement
$personne1 = new Personne("Alice", 25);
$personne2 = new Personne("Bob", 30);
?>
```

---

## Initialisation des objets

### Exemple : Classe Produit

```php
<?php
class Produit {
    private $nom;
    private $prix;
    private $dateCreation;
    private $actif;

    public function __construct($nom, $prix) {
        $this->nom = $nom;
        $this->prix = $prix;
        $this->dateCreation = new DateTime();
        $this->actif = true;

        echo "Produit '{$nom}' créé avec succès !";
    }

    public function getInfo() {
        return "{$this->nom} - {$this->prix}€ (créé le {$this->dateCreation->format('d/m/Y')})";
    }

    public function estActif() {
        return $this->actif;
    }
}

// Création d'un produit
$produit = new Produit("Laptop", 999.99);
echo $produit->getInfo();
?>
```

### Exemple : Classe CompteBancaire

```php
<?php
class CompteBancaire {
    private $numeroCompte;
    private $solde;
    private $dateCreation;
    private $codeSecret;

    public function __construct($numeroCompte, $soldeInitial = 0) {
        $this->numeroCompte = $numeroCompte;
        $this->solde = $soldeInitial;
        $this->dateCreation = new DateTime();
        $this->codeSecret = rand(1000, 9999);

        echo "Compte {$numeroCompte} créé avec un solde de {$soldeInitial}€";
    }

    public function getInfo() {
        return "Compte : {$this->numeroCompte} - Solde : {$this->solde}€ - Créé le {$this->dateCreation->format('d/m/Y')}";
    }
}

// Création de comptes
$compte1 = new CompteBancaire("123456", 1000);
$compte2 = new CompteBancaire("789012", 500);
?>
```

---

## Constructeurs avec validation

### Exemple : Classe Etudiant

```php
<?php
class Etudiant {
    private $nom;
    private $age;
    private $notes;
    private $dateInscription;

    public function __construct($nom, $age) {
        // Validation des données
        if (strlen($nom) < 2) {
            throw new InvalidArgumentException("Le nom doit contenir au moins 2 caractères");
        }

        if ($age < 0 || $age > 120) {
            throw new InvalidArgumentException("L'âge doit être entre 0 et 120 ans");
        }

        $this->nom = $nom;
        $this->age = $age;
        $this->notes = [];
        $this->dateInscription = new DateTime();

        echo "Étudiant {$nom} inscrit avec succès";
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
}

// Test avec validation
try {
    $etudiant = new Etudiant("Alice", 20);
    $etudiant->ajouterNote("Maths", 15);
    $etudiant->ajouterNote("Français", 18);
    echo "Moyenne : " . $etudiant->calculerMoyenne();
} catch (InvalidArgumentException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
```

---

## Qu'est-ce qu'un destructeur ?

Un **destructeur** est une méthode spéciale appelée automatiquement quand un objet est détruit. C'est comme un plan de démolition qui s'exécute avant la destruction.

### 🗑️ Analogie avec la démolition

- **Destructeur** = Plan de démolition d'une maison
- **Appel automatique** = Le plan s'exécute avant la destruction
- **Nettoyage** = Libération des ressources, sauvegarde des données, etc.

---

## Le destructeur `__destruct()`

### Syntaxe de base

```php
<?php
class NomDeLaClasse {
    public function __destruct() {
        // Code de nettoyage
    }
}
?>
```

### Exemple simple

```php
<?php
class Fichier {
    private $nomFichier;
    private $contenu;

    public function __construct($nomFichier) {
        $this->nomFichier = $nomFichier;
        $this->contenu = "Contenu du fichier";
        echo "Fichier '{$nomFichier}' ouvert";
    }

    public function __destruct() {
        echo "Fichier '{$this->nomFichier}' fermé et sauvegardé";
    }

    public function lire() {
        return $this->contenu;
    }
}

// Test
$fichier = new Fichier("document.txt");
echo $fichier->lire();
// Le destructeur sera appelé automatiquement à la fin du script
?>
```

---

## Exemple complet : Gestion de session

```php
<?php
class Session {
    private $id;
    private $utilisateur;
    private $dateDebut;
    private $activite;

    public function __construct($utilisateur) {
        $this->id = uniqid();
        $this->utilisateur = $utilisateur;
        $this->dateDebut = new DateTime();
        $this->activite = [];

        echo "Session démarrée pour {$utilisateur} (ID: {$this->id})";
    }

    public function ajouterActivite($action) {
        $this->activite[] = [
            'action' => $action,
            'timestamp' => new DateTime()
        ];
        echo "Activité ajoutée : {$action}";
    }

    public function getDuree() {
        $maintenant = new DateTime();
        $duree = $maintenant->diff($this->dateDebut);
        return $duree->format('%H:%I:%S');
    }

    public function __destruct() {
        echo "Session terminée pour {$this->utilisateur}";
        echo "Durée totale : " . $this->getDuree();
        echo "Nombre d'activités : " . count($this->activite);

        // Sauvegarde des données de session
        $this->sauvegarderSession();
    }

    private function sauvegarderSession() {
        // Simulation de sauvegarde
        echo "Données de session sauvegardées";
    }
}

// Test
$session = new Session("Alice");
$session->ajouterActivite("Connexion");
$session->ajouterActivite("Navigation");
// Le destructeur sera appelé automatiquement
?>
```

---

## Constructeurs et héritage

### Appel du constructeur parent

```php
<?php
class Vehicule {
    protected $marque;
    protected $vitesse;

    public function __construct($marque) {
        $this->marque = $marque;
        $this->vitesse = 0;
        echo "Véhicule {$marque} créé";
    }
}

class Voiture extends Vehicule {
    private $nombrePortes;

    public function __construct($marque, $nombrePortes) {
        parent::__construct($marque); // Appel du constructeur parent
        $this->nombrePortes = $nombrePortes;
        echo " avec {$nombrePortes} portes";
    }
}

$voiture = new Voiture("BMW", 5);
?>
```

---

## 🎯 Exercices pratiques

### Exercice 1 : Classe Livre avec constructeur

Créez une classe `Livre` avec :

- Propriétés : `titre`, `auteur`, `annee`, `nombrePages`, `emprunte`
- Constructeur qui initialise toutes les propriétés
- Méthodes : `emprunter()`, `rendre()`, `afficherInfo()`

```php
<?php
class Livre {
    private $titre;
    private $auteur;
    private $annee;
    private $nombrePages;
    private $emprunte;

    public function __construct($titre, $auteur, $annee, $nombrePages) {
        $this->titre = $titre;
        $this->auteur = $auteur;
        $this->annee = $annee;
        $this->nombrePages = $nombrePages;
        $this->emprunte = false;

        echo "Livre '{$titre}' ajouté à la bibliothèque";
    }

    public function emprunter() {
        if (!$this->emprunte) {
            $this->emprunte = true;
            echo "Livre '{$this->titre}' emprunté";
            return true;
        }
        echo "Livre déjà emprunté";
        return false;
    }

    public function rendre() {
        if ($this->emprunte) {
            $this->emprunte = false;
            echo "Livre '{$this->titre}' rendu";
            return true;
        }
        echo "Livre n'était pas emprunté";
        return false;
    }

    public function afficherInfo() {
        $statut = $this->emprunte ? "Emprunté" : "Disponible";
        echo "Titre : {$this->titre} - Auteur : {$this->auteur} - Année : {$this->annee} - Pages : {$this->nombrePages} - Statut : {$statut}";
    }
}

// Test
$livre = new Livre("Le Petit Prince", "Antoine de Saint-Exupéry", 1943, 96);
$livre->afficherInfo();
$livre->emprunter();
$livre->afficherInfo();
?>
```

### Exercice 2 : Classe Connexion avec destructeur

Créez une classe `Connexion` qui simule une connexion à une base de données :

- Constructeur : établit la connexion
- Destructeur : ferme la connexion et sauvegarde les logs
- Méthodes : `executerRequete()`, `obtenirLogs()`

```php
<?php
class Connexion {
    private $host;
    private $database;
    private $utilisateur;
    private $logs;
    private $connexionActive;

    public function __construct($host, $database, $utilisateur) {
        $this->host = $host;
        $this->database = $database;
        $this->utilisateur = $utilisateur;
        $this->logs = [];
        $this->connexionActive = true;

        $this->ajouterLog("Connexion établie à {$host}/{$database}");
        echo "Connexion à la base de données établie";
    }

    public function executerRequete($requete) {
        if ($this->connexionActive) {
            $this->ajouterLog("Requête exécutée : {$requete}");
            echo "Requête '{$requete}' exécutée avec succès";
            return true;
        }
        echo "Connexion fermée";
        return false;
    }

    public function obtenirLogs() {
        return $this->logs;
    }

    private function ajouterLog($message) {
        $this->logs[] = [
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    public function __destruct() {
        if ($this->connexionActive) {
            $this->connexionActive = false;
            $this->ajouterLog("Connexion fermée");

            echo "Connexion fermée. Nombre d'opérations : " . count($this->logs);

            // Sauvegarde des logs
            $this->sauvegarderLogs();
        }
    }

    private function sauvegarderLogs() {
        echo "Logs sauvegardés : " . count($this->logs) . " entrées";
    }
}

// Test
$connexion = new Connexion("localhost", "ma_base", "utilisateur");
$connexion->executerRequete("SELECT * FROM users");
$connexion->executerRequete("INSERT INTO logs VALUES (...)");
// Le destructeur sera appelé automatiquement
?>
```

### Exercice 3 : Classe Panier avec gestion des produits

Créez une classe `Panier` qui gère un panier d'achat :

- Constructeur : initialise le panier vide
- Destructeur : sauvegarde le panier et affiche un résumé
- Méthodes : `ajouterProduit()`, `supprimerProduit()`, `calculerTotal()`

```php
<?php
class Panier {
    private $produits;
    private $dateCreation;
    private $utilisateur;

    public function __construct($utilisateur) {
        $this->utilisateur = $utilisateur;
        $this->produits = [];
        $this->dateCreation = new DateTime();

        echo "Panier créé pour {$utilisateur}";
    }

    public function ajouterProduit($nom, $prix, $quantite = 1) {
        $this->produits[] = [
            'nom' => $nom,
            'prix' => $prix,
            'quantite' => $quantite
        ];
        echo "Produit '{$nom}' ajouté au panier";
    }

    public function supprimerProduit($nom) {
        foreach ($this->produits as $index => $produit) {
            if ($produit['nom'] === $nom) {
                unset($this->produits[$index]);
                echo "Produit '{$nom}' supprimé du panier";
                return true;
            }
        }
        echo "Produit '{$nom}' non trouvé";
        return false;
    }

    public function calculerTotal() {
        $total = 0;
        foreach ($this->produits as $produit) {
            $total += $produit['prix'] * $produit['quantite'];
        }
        return $total;
    }

    public function afficherPanier() {
        echo "Panier de {$this->utilisateur} :";
        foreach ($this->produits as $produit) {
            echo "- {$produit['nom']} : {$produit['prix']}€ x {$produit['quantite']}";
        }
        echo "Total : " . $this->calculerTotal() . "€";
    }

    public function __destruct() {
        echo "Panier de {$this->utilisateur} fermé";
        echo "Nombre de produits : " . count($this->produits);
        echo "Total : " . $this->calculerTotal() . "€";

        // Sauvegarde du panier
        $this->sauvegarderPanier();
    }

    private function sauvegarderPanier() {
        echo "Panier sauvegardé pour {$this->utilisateur}";
    }
}

// Test
$panier = new Panier("Alice");
$panier->ajouterProduit("Laptop", 999, 1);
$panier->ajouterProduit("Souris", 25, 2);
$panier->afficherPanier();
// Le destructeur sera appelé automatiquement
?>
```

---

## 🎯 Points clés à retenir

✅ **Le constructeur `__construct()`** est appelé automatiquement à la création d'un objet  
✅ **Le destructeur `__destruct()`** est appelé automatiquement à la destruction d'un objet  
✅ **Utilisez le constructeur** pour initialiser les propriétés et valider les données  
✅ **Utilisez le destructeur** pour nettoyer les ressources et sauvegarder les données  
✅ **`parent::__construct()`** permet d'appeler le constructeur parent  
✅ **Les constructeurs et destructeurs** sont essentiels pour la gestion des ressources

---

## 🚀 Prochaines étapes

Maintenant que vous maîtrisez les constructeurs et destructeurs, passez au **[Module 5 : L'héritage](05-Heritage.md)** pour apprendre à créer des classes qui héritent d'autres classes !

---

## 📚 Ressources complémentaires

- 📖 [Documentation PHP - Constructeurs](https://www.php.net/manual/fr/language.oop5.decon.php)
- 🎥 [Vidéo : Constructeurs et destructeurs](https://www.youtube.com/watch?v=example)
- 📝 [Article : Bonnes pratiques d'initialisation](https://example.com)

---

**Bonne programmation ! 🎉**
