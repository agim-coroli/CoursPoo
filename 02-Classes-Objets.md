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

### Exercice 1 : Système de gestion d'un refuge pour animaux

**🎯 OBJECTIF :** Créer un système pour gérer les animaux d'un refuge et suivre leur état de santé.

**📋 SCÉNARIO :** Vous gérez un refuge pour animaux. Vous devez :

- Enregistrer les nouveaux animaux
- Suivre leur état de santé (faim, fatigue, bonheur)
- Gérer leurs activités quotidiennes
- Afficher un rapport de santé

**📝 SPÉCIFICATIONS :**

- Classe `Animal` avec propriétés : `nom`, `espece`, `age`, `faim`, `fatigue`, `bonheur`
- Méthodes : `manger()`, `dormir()`, `jouer()`, `sePresenter()`, `etatSante()`

**✅ RÉSULTAT ATTENDU :**

```
=== REFUGE POUR ANIMAUX ===
Nouvel animal : Rex, Chien, 3 ans
Rex mange et se sent mieux !
Rex joue et est plus heureux !
Rex dort et récupère de l'énergie !
État de santé de Rex :
- Faim: 20/100 (rassasié)
- Fatigue: 30/100 (reposé)
- Bonheur: 80/100 (très heureux)
```

**💻 SOLUTION :**

```php
<?php
class Animal {
    public $nom;
    public $espece;
    public $age;
    public $faim;
    public $fatigue;
    public $bonheur;

    public function __construct($nom, $espece, $age) {
        $this->nom = $nom;
        $this->espece = $espece;
        $this->age = $age;
        $this->faim = 80;      // Commence affamé
        $this->fatigue = 70;    // Commence fatigué
        $this->bonheur = 50;   // Commence neutre
    }

    public function manger() {
        $this->faim = max(0, $this->faim - 60);
        $this->bonheur = min(100, $this->bonheur + 10);
        echo "{$this->nom} mange et se sent mieux !\n";
    }

    public function dormir() {
        $this->fatigue = max(0, $this->fatigue - 50);
        $this->faim = min(100, $this->faim + 20);
        echo "{$this->nom} dort et récupère de l'énergie !\n";
    }

    public function jouer() {
        $this->fatigue = min(100, $this->fatigue + 30);
        $this->faim = min(100, $this->faim + 20);
        $this->bonheur = min(100, $this->bonheur + 30);
        echo "{$this->nom} joue et est plus heureux !\n";
    }

    public function sePresenter() {
        echo "Nouvel animal : {$this->nom}, {$this->espece}, {$this->age} ans\n";
    }

    public function etatSante() {
        echo "État de santé de {$this->nom} :\n";
        echo "- Faim: {$this->faim}/100 " . ($this->faim < 30 ? "(rassasié)" : ($this->faim > 70 ? "(affamé)" : "(normal)")) . "\n";
        echo "- Fatigue: {$this->fatigue}/100 " . ($this->fatigue < 30 ? "(reposé)" : ($this->fatigue > 70 ? "(fatigué)" : "(normal)")) . "\n";
        echo "- Bonheur: {$this->bonheur}/100 " . ($this->bonheur < 30 ? "(triste)" : ($this->bonheur > 70 ? "(très heureux)" : "(content)")) . "\n";
    }
}

// Test du système
echo "=== REFUGE POUR ANIMAUX ===\n";
$chien = new Animal("Rex", "Chien", 3);
$chien->sePresenter();
$chien->manger();
$chien->jouer();
$chien->dormir();
$chien->etatSante();
?>
```

### Exercice 2 : Système de calcul de factures pour un restaurant

**🎯 OBJECTIF :** Créer un système de calcul de factures pour un restaurant avec gestion des taxes et pourboires.

**📋 SCÉNARIO :** Vous travaillez dans un restaurant et devez :

- Calculer le total des commandes
- Ajouter les taxes (TVA)
- Gérer les pourboires
- Afficher le détail de la facture
- Gérer plusieurs tables simultanément

**📝 SPÉCIFICATIONS :**

- Classe `Facture` avec propriétés : `numeroTable`, `items`, `total`, `taxe`, `pourboire`
- Méthodes : `ajouterItem()`, `calculerTotal()`, `ajouterTaxe()`, `ajouterPourboire()`, `afficherFacture()`

**✅ RÉSULTAT ATTENDU :**

```
=== RESTAURANT CHEZ MARIE ===
Table 5 - Nouvelle facture créée
Ajout: Pizza Margherita (15.50€)
Ajout: Boisson (3.00€)
Ajout: Dessert (6.50€)
Sous-total: 25.00€
Taxe (20%): 5.00€
Pourboire (15%): 3.75€
TOTAL: 33.75€
=== FACTURE DÉTAILLÉE ===
Table 5:
- Pizza Margherita: 15.50€
- Boisson: 3.00€
- Dessert: 6.50€
Sous-total: 25.00€
Taxe: 5.00€
Pourboire: 3.75€
TOTAL: 33.75€
```

**💻 SOLUTION :**

```php
<?php
class Facture {
    private $numeroTable;
    private $items = [];
    private $total = 0;
    private $taxe = 0;
    private $pourboire = 0;

    public function __construct($numeroTable) {
        $this->numeroTable = $numeroTable;
        echo "Table {$numeroTable} - Nouvelle facture créée\n";
    }

    public function ajouterItem($nom, $prix) {
        $this->items[] = ['nom' => $nom, 'prix' => $prix];
        $this->total += $prix;
        echo "Ajout: {$nom} ({$prix}€)\n";
    }

    public function calculerTotal() {
        $sousTotal = $this->total;
        $totalAvecTaxe = $sousTotal + $this->taxe;
        $totalFinal = $totalAvecTaxe + $this->pourboire;

        echo "Sous-total: {$sousTotal}€\n";
        if ($this->taxe > 0) {
            echo "Taxe (20%): {$this->taxe}€\n";
        }
        if ($this->pourboire > 0) {
            echo "Pourboire (15%): {$this->pourboire}€\n";
        }
        echo "TOTAL: {$totalFinal}€\n";

        return $totalFinal;
    }

    public function ajouterTaxe($pourcentage = 20) {
        $this->taxe = $this->total * ($pourcentage / 100);
        return $this;
    }

    public function ajouterPourboire($pourcentage = 15) {
        $totalAvecTaxe = $this->total + $this->taxe;
        $this->pourboire = $totalAvecTaxe * ($pourcentage / 100);
        return $this;
    }

    public function afficherFacture() {
        echo "=== FACTURE DÉTAILLÉE ===\n";
        echo "Table {$this->numeroTable}:\n";

        foreach ($this->items as $item) {
            echo "- {$item['nom']}: {$item['prix']}€\n";
        }

        echo "Sous-total: {$this->total}€\n";
        if ($this->taxe > 0) {
            echo "Taxe: {$this->taxe}€\n";
        }
        if ($this->pourboire > 0) {
            echo "Pourboire: {$this->pourboire}€\n";
        }

        $totalFinal = $this->total + $this->taxe + $this->pourboire;
        echo "TOTAL: {$totalFinal}€\n";
    }
}

// Test du système
echo "=== RESTAURANT CHEZ MARIE ===\n";
$facture = new Facture(5);
$facture->ajouterItem("Pizza Margherita", 15.50);
$facture->ajouterItem("Boisson", 3.00);
$facture->ajouterItem("Dessert", 6.50);
$facture->ajouterTaxe(20);
$facture->ajouterPourboire(15);
$facture->calculerTotal();
$facture->afficherFacture();
?>
```

### Exercice 3 : Système de gestion scolaire

**🎯 OBJECTIF :** Créer un système de gestion des notes et bulletins pour une école.

**📋 SCÉNARIO :** Vous êtes responsable du système informatique d'une école. Vous devez :

- Gérer les notes des étudiants
- Calculer les moyennes par matière et générale
- Détecter les étudiants en difficulté
- Générer des bulletins détaillés
- Suivre l'évolution des performances

**📝 SPÉCIFICATIONS :**

- Classe `Etudiant` avec propriétés : `nom`, `classe`, `notes`, `moyenneGenerale`
- Méthodes : `ajouterNote()`, `calculerMoyenne()`, `afficherBulletin()`, `estEnDifficulte()`, `getStatistiques()`

**✅ RÉSULTAT ATTENDU :**

```
=== SYSTÈME SCOLAIRE ===
Nouvel étudiant : Alice Dupont (3ème A)
Note ajoutée : Mathématiques = 15/20
Note ajoutée : Français = 18/20
Note ajoutée : Histoire = 12/20
Note ajoutée : Sciences = 14/20

=== BULLETIN DE ALICE DUPONT ===
Classe : 3ème A
Matières :
- Mathématiques : 15/20
- Français : 18/20
- Histoire : 12/20
- Sciences : 14/20
Moyenne générale : 14.75/20
Statut : Bon niveau
Matière la plus forte : Français (18/20)
Matière à améliorer : Histoire (12/20)
```

**💻 SOLUTION :**

```php
<?php
class Etudiant {
    private $nom;
    private $classe;
    private $notes = [];
    private $moyenneGenerale = 0;

    public function __construct($nom, $classe) {
        $this->nom = $nom;
        $this->classe = $classe;
        echo "Nouvel étudiant : {$nom} ({$classe})\n";
    }

    public function ajouterNote($matiere, $note) {
        if ($note < 0 || $note > 20) {
            echo "Erreur : Note invalide (doit être entre 0 et 20)\n";
            return false;
        }

        $this->notes[$matiere] = $note;
        echo "Note ajoutée : {$matiere} = {$note}/20\n";
        $this->calculerMoyenne();
        return true;
    }

    public function calculerMoyenne() {
        if (empty($this->notes)) {
            $this->moyenneGenerale = 0;
            return 0;
        }

        $this->moyenneGenerale = array_sum($this->notes) / count($this->notes);
        return $this->moyenneGenerale;
    }

    public function afficherBulletin() {
        echo "\n=== BULLETIN DE {$this->nom} ===\n";
        echo "Classe : {$this->classe}\n";
        echo "Matières :\n";

        foreach ($this->notes as $matiere => $note) {
            echo "- {$matiere} : {$note}/20\n";
        }

        echo "Moyenne générale : " . round($this->moyenneGenerale, 2) . "/20\n";

        if ($this->moyenneGenerale >= 16) {
            echo "Statut : Excellent niveau\n";
        } elseif ($this->moyenneGenerale >= 14) {
            echo "Statut : Bon niveau\n";
        } elseif ($this->moyenneGenerale >= 12) {
            echo "Statut : Niveau correct\n";
        } else {
            echo "Statut : En difficulté\n";
        }

        // Trouver la meilleure et la pire matière
        if (!empty($this->notes)) {
            $meilleureMatiere = array_keys($this->notes, max($this->notes))[0];
            $pireMatiere = array_keys($this->notes, min($this->notes))[0];
            echo "Matière la plus forte : {$meilleureMatiere} (" . max($this->notes) . "/20)\n";
            echo "Matière à améliorer : {$pireMatiere} (" . min($this->notes) . "/20)\n";
        }
    }

    public function estEnDifficulte() {
        return $this->moyenneGenerale < 12;
    }

    public function getStatistiques() {
        if (empty($this->notes)) {
            return "Aucune note enregistrée";
        }

        $nbNotes = count($this->notes);
        $noteMax = max($this->notes);
        $noteMin = min($this->notes);

        return [
            'nb_notes' => $nbNotes,
            'moyenne' => round($this->moyenneGenerale, 2),
            'note_max' => $noteMax,
            'note_min' => $noteMin,
            'en_difficulte' => $this->estEnDifficulte()
        ];
    }
}

// Test du système
echo "=== SYSTÈME SCOLAIRE ===\n";
$etudiant = new Etudiant("Alice Dupont", "3ème A");
$etudiant->ajouterNote("Mathématiques", 15);
$etudiant->ajouterNote("Français", 18);
$etudiant->ajouterNote("Histoire", 12);
$etudiant->ajouterNote("Sciences", 14);
$etudiant->afficherBulletin();

$stats = $etudiant->getStatistiques();
echo "\nStatistiques : {$stats['nb_notes']} notes, moyenne {$stats['moyenne']}, max {$stats['note_max']}, min {$stats['note_min']}\n";
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
