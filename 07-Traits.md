# 📚 Module 7 : Les traits

> **Objectif** : Maîtriser les traits pour partager du code entre plusieurs classes en PHP

## 🎯 Ce que vous allez apprendre

- Qu'est-ce qu'un trait
- Créer et utiliser des traits
- Résoudre les conflits de méthodes
- Cas d'usage typiques des traits
- Bonnes pratiques avec les traits

---

## Qu'est-ce qu'un trait ?

Un **trait** est un mécanisme de réutilisation de code en PHP. Il permet de partager des méthodes entre plusieurs classes sans héritage multiple.

### 🧬 Analogie avec l'ADN

- **Trait** = Gène (caractéristique héréditaire)
- **Classe** = Organisme (peut avoir plusieurs gènes)
- **Héritage multiple** = Plusieurs parents (impossible en PHP)
- **Traits** = Gènes que l'on peut "greffer" sur n'importe quelle classe

---

## Syntaxe de base des traits

### Déclaration d'un trait

```php
<?php
trait NomDuTrait {
    // Propriétés
    private $propriete;

    // Méthodes
    public function methode() {
        // Code de la méthode
    }
}
?>
```

### Utilisation d'un trait

```php
<?php
class NomDeLaClasse {
    use NomDuTrait;

    // Autres propriétés et méthodes
}
?>
```

---

## Exemple simple : Trait Loggable

```php
<?php
trait Loggable {
    public function log($message) {
        echo "[" . date('Y-m-d H:i:s') . "] " . $message . "\n";
    }

    public function logError($message) {
        echo "ERREUR [" . date('Y-m-d H:i:s') . "] " . $message . "\n";
    }
}

class Utilisateur {
    use Loggable;

    private $nom;

    public function __construct($nom) {
        $this->nom = $nom;
        $this->log("Utilisateur {$nom} créé");
    }

    public function seConnecter() {
        $this->log("Utilisateur {$this->nom} s'est connecté");
    }
}

class Produit {
    use Loggable;

    private $nom;
    private $prix;

    public function __construct($nom, $prix) {
        $this->nom = $nom;
        $this->prix = $prix;
        $this->log("Produit {$nom} créé au prix de {$prix}€");
    }

    public function vendre() {
        $this->log("Produit {$this->nom} vendu");
    }
}

// Test
$user = new Utilisateur("Alice");
$user->seConnecter();

$produit = new Produit("Laptop", 999);
$produit->vendre();
?>
```

---

## Traits multiples

### Exemple : Traits Loggable et Cacheable

```php
<?php
trait Loggable {
    public function log($message) {
        echo "[" . date('Y-m-d H:i:s') . "] " . $message . "\n";
    }
}

trait Cacheable {
    private $cache = [];

    public function getFromCache($key) {
        return isset($this->cache[$key]) ? $this->cache[$key] : null;
    }

    public function setCache($key, $value) {
        $this->cache[$key] = $value;
    }

    public function clearCache() {
        $this->cache = [];
    }
}

trait Validable {
    public function validerEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function validerAge($age) {
        return is_numeric($age) && $age >= 0 && $age <= 120;
    }
}

class Utilisateur {
    use Loggable, Cacheable, Validable;

    private $nom;
    private $email;
    private $age;

    public function __construct($nom, $email, $age) {
        if (!$this->validerEmail($email)) {
            $this->logError("Email invalide : {$email}");
            throw new InvalidArgumentException("Email invalide");
        }

        if (!$this->validerAge($age)) {
            $this->logError("Âge invalide : {$age}");
            throw new InvalidArgumentException("Âge invalide");
        }

        $this->nom = $nom;
        $this->email = $email;
        $this->age = $age;

        $this->log("Utilisateur {$nom} créé avec succès");
    }

    public function getNom() {
        $cached = $this->getFromCache('nom');
        if ($cached) {
            $this->log("Nom récupéré du cache");
            return $cached;
        }

        $this->setCache('nom', $this->nom);
        $this->log("Nom mis en cache");
        return $this->nom;
    }

    public function getEmail() {
        $cached = $this->getFromCache('email');
        if ($cached) {
            return $cached;
        }

        $this->setCache('email', $this->email);
        return $this->email;
    }
}

// Test
$user = new Utilisateur("Alice", "alice@example.com", 25);
echo $user->getNom();
echo $user->getEmail();
?>
```

---

## Résolution des conflits

### Conflit de méthodes

```php
<?php
trait TraitA {
    public function methode() {
        echo "Méthode du TraitA";
    }
}

trait TraitB {
    public function methode() {
        echo "Méthode du TraitB";
    }
}

class MaClasse {
    use TraitA, TraitB {
        TraitA::methode insteadof TraitB; // Utilise la méthode de TraitA
        TraitB::methode as methodeB; // Renomme la méthode de TraitB
    }
}

$objet = new MaClasse();
$objet->methode();   // Affiche : Méthode du TraitA
$objet->methodeB();  // Affiche : Méthode du TraitB
?>
```

### Exemple pratique : Conflit de validation

```php
<?php
trait ValidationEmail {
    public function valider($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

trait ValidationTelephone {
    public function valider($telephone) {
        return preg_match('/^[0-9]{10}$/', $telephone);
    }
}

class Contact {
    use ValidationEmail, ValidationTelephone {
        ValidationEmail::valider as validerEmail;
        ValidationTelephone::valider as validerTelephone;
    }

    private $email;
    private $telephone;

    public function __construct($email, $telephone) {
        if (!$this->validerEmail($email)) {
            throw new InvalidArgumentException("Email invalide");
        }

        if (!$this->validerTelephone($telephone)) {
            throw new InvalidArgumentException("Téléphone invalide");
        }

        $this->email = $email;
        $this->telephone = $telephone;
    }
}

// Test
$contact = new Contact("alice@example.com", "0123456789");
?>
```

---

## Cas d'usage typiques

### 1. Trait pour la sérialisation

```php
<?php
trait Serializable {
    public function toArray() {
        return get_object_vars($this);
    }

    public function toJson() {
        return json_encode($this->toArray());
    }

    public function toXml() {
        $xml = new SimpleXMLElement('<object/>');
        $this->arrayToXml($this->toArray(), $xml);
        return $xml->asXML();
    }

    private function arrayToXml($array, &$xml) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $subnode = $xml->addChild($key);
                $this->arrayToXml($value, $subnode);
            } else {
                $xml->addChild($key, htmlspecialchars($value));
            }
        }
    }
}

class Produit {
    use Serializable;

    private $nom;
    private $prix;
    private $description;

    public function __construct($nom, $prix, $description) {
        $this->nom = $nom;
        $this->prix = $prix;
        $this->description = $description;
    }
}

// Test
$produit = new Produit("Laptop", 999, "Ordinateur portable");
echo $produit->toJson();
?>
```

### 2. Trait pour la validation

```php
<?php
trait Validable {
    private $errors = [];

    public function validerEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Email invalide";
            return false;
        }
        return true;
    }

    public function validerAge($age) {
        if (!is_numeric($age) || $age < 0 || $age > 120) {
            $this->errors[] = "Âge invalide";
            return false;
        }
        return true;
    }

    public function validerNom($nom) {
        if (strlen($nom) < 2) {
            $this->errors[] = "Nom trop court";
            return false;
        }
        return true;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function hasErrors() {
        return !empty($this->errors);
    }

    public function clearErrors() {
        $this->errors = [];
    }
}

class Personne {
    use Validable;

    private $nom;
    private $email;
    private $age;

    public function __construct($nom, $email, $age) {
        $this->nom = $nom;
        $this->email = $email;
        $this->age = $age;
    }

    public function valider() {
        $this->clearErrors();

        $this->validerNom($this->nom);
        $this->validerEmail($this->email);
        $this->validerAge($this->age);

        return !$this->hasErrors();
    }
}

// Test
$personne = new Personne("A", "email-invalide", -5);
if (!$personne->valider()) {
    echo "Erreurs : " . implode(", ", $personne->getErrors());
}
?>
```

### 3. Trait pour le cache

```php
<?php
trait Cacheable {
    private $cache = [];
    private $cacheExpiration = [];

    public function getFromCache($key) {
        if (isset($this->cache[$key])) {
            if (isset($this->cacheExpiration[$key]) && time() > $this->cacheExpiration[$key]) {
                unset($this->cache[$key]);
                unset($this->cacheExpiration[$key]);
                return null;
            }
            return $this->cache[$key];
        }
        return null;
    }

    public function setCache($key, $value, $expiration = 3600) {
        $this->cache[$key] = $value;
        $this->cacheExpiration[$key] = time() + $expiration;
    }

    public function clearCache($key = null) {
        if ($key === null) {
            $this->cache = [];
            $this->cacheExpiration = [];
        } else {
            unset($this->cache[$key]);
            unset($this->cacheExpiration[$key]);
        }
    }

    public function getCacheInfo() {
        return [
            'keys' => array_keys($this->cache),
            'count' => count($this->cache)
        ];
    }
}

class ServiceUtilisateur {
    use Cacheable;

    private $utilisateurs = [];

    public function __construct() {
        $this->utilisateurs = [
            1 => ['nom' => 'Alice', 'email' => 'alice@example.com'],
            2 => ['nom' => 'Bob', 'email' => 'bob@example.com']
        ];
    }

    public function getUtilisateur($id) {
        $cached = $this->getFromCache("user_{$id}");
        if ($cached) {
            echo "Utilisateur récupéré du cache\n";
            return $cached;
        }

        if (isset($this->utilisateurs[$id])) {
            $user = $this->utilisateurs[$id];
            $this->setCache("user_{$id}", $user, 300); // Cache pendant 5 minutes
            echo "Utilisateur récupéré de la base de données\n";
            return $user;
        }

        return null;
    }
}

// Test
$service = new ServiceUtilisateur();
$user1 = $service->getUtilisateur(1); // Récupéré de la DB
$user2 = $service->getUtilisateur(1); // Récupéré du cache
?>
```

---

## 🎯 Exercices pratiques

### Exercice 1 : Système de logging

Créez un système de logging avec :

- Trait `Loggable` : log(), logError(), logWarning()
- Classe `Utilisateur` : utilise Loggable
- Classe `Commande` : utilise Loggable

```php
<?php
trait Loggable {
    private $logs = [];

    public function log($message, $level = 'INFO') {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => $level,
            'message' => $message
        ];
        $this->logs[] = $logEntry;
        echo "[{$level}] {$message}\n";
    }

    public function logError($message) {
        $this->log($message, 'ERROR');
    }

    public function logWarning($message) {
        $this->log($message, 'WARNING');
    }

    public function getLogs() {
        return $this->logs;
    }

    public function clearLogs() {
        $this->logs = [];
    }
}

class Utilisateur {
    use Loggable;

    private $nom;
    private $email;

    public function __construct($nom, $email) {
        $this->nom = $nom;
        $this->email = $email;
        $this->log("Utilisateur {$nom} créé");
    }

    public function seConnecter() {
        $this->log("Utilisateur {$this->nom} s'est connecté");
    }

    public function seDeconnecter() {
        $this->log("Utilisateur {$this->nom} s'est déconnecté");
    }
}

class Commande {
    use Loggable;

    private $id;
    private $montant;
    private $statut;

    public function __construct($id, $montant) {
        $this->id = $id;
        $this->montant = $montant;
        $this->statut = 'en_attente';
        $this->log("Commande {$id} créée pour {$montant}€");
    }

    public function confirmer() {
        $this->statut = 'confirmee';
        $this->log("Commande {$this->id} confirmée");
    }

    public function annuler() {
        $this->statut = 'annulee';
        $this->logWarning("Commande {$this->id} annulée");
    }
}

// Test
$user = new Utilisateur("Alice", "alice@example.com");
$user->seConnecter();
$user->seDeconnecter();

$commande = new Commande("CMD001", 150);
$commande->confirmer();
?>
```

### Exercice 2 : Système de validation

Créez un système de validation avec :

- Trait `Validable` : validerEmail(), validerTelephone(), validerAge()
- Classe `Contact` : utilise Validable
- Classe `Employe` : utilise Validable

```php
<?php
trait Validable {
    private $errors = [];

    public function validerEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Email invalide : {$email}";
            return false;
        }
        return true;
    }

    public function validerTelephone($telephone) {
        if (!preg_match('/^[0-9]{10}$/', $telephone)) {
            $this->errors[] = "Téléphone invalide : {$telephone}";
            return false;
        }
        return true;
    }

    public function validerAge($age) {
        if (!is_numeric($age) || $age < 0 || $age > 120) {
            $this->errors[] = "Âge invalide : {$age}";
            return false;
        }
        return true;
    }

    public function validerNom($nom) {
        if (strlen($nom) < 2) {
            $this->errors[] = "Nom trop court : {$nom}";
            return false;
        }
        return true;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function hasErrors() {
        return !empty($this->errors);
    }

    public function clearErrors() {
        $this->errors = [];
    }
}

class Contact {
    use Validable;

    private $nom;
    private $email;
    private $telephone;

    public function __construct($nom, $email, $telephone) {
        $this->nom = $nom;
        $this->email = $email;
        $this->telephone = $telephone;
    }

    public function valider() {
        $this->clearErrors();

        $this->validerNom($this->nom);
        $this->validerEmail($this->email);
        $this->validerTelephone($this->telephone);

        return !$this->hasErrors();
    }
}

class Employe {
    use Validable;

    private $nom;
    private $email;
    private $age;
    private $poste;

    public function __construct($nom, $email, $age, $poste) {
        $this->nom = $nom;
        $this->email = $email;
        $this->age = $age;
        $this->poste = $poste;
    }

    public function valider() {
        $this->clearErrors();

        $this->validerNom($this->nom);
        $this->validerEmail($this->email);
        $this->validerAge($this->age);

        return !$this->hasErrors();
    }
}

// Test
$contact = new Contact("A", "email-invalide", "123");
if (!$contact->valider()) {
    echo "Erreurs contact : " . implode(", ", $contact->getErrors()) . "\n";
}

$employe = new Employe("Bob", "bob@example.com", 25, "Développeur");
if ($employe->valider()) {
    echo "Employé validé avec succès\n";
}
?>
```

### Exercice 3 : Système de cache et logging

Créez un système combinant cache et logging :

- Trait `Cacheable` : getFromCache(), setCache(), clearCache()
- Trait `Loggable` : log(), logError()
- Classe `ServiceProduit` : utilise les deux traits

```php
<?php
trait Cacheable {
    private $cache = [];

    public function getFromCache($key) {
        return isset($this->cache[$key]) ? $this->cache[$key] : null;
    }

    public function setCache($key, $value) {
        $this->cache[$key] = $value;
    }

    public function clearCache($key = null) {
        if ($key === null) {
            $this->cache = [];
        } else {
            unset($this->cache[$key]);
        }
    }
}

trait Loggable {
    public function log($message) {
        echo "[" . date('Y-m-d H:i:s') . "] " . $message . "\n";
    }

    public function logError($message) {
        echo "ERREUR [" . date('Y-m-d H:i:s') . "] " . $message . "\n";
    }
}

class ServiceProduit {
    use Cacheable, Loggable;

    private $produits = [];

    public function __construct() {
        $this->produits = [
            1 => ['nom' => 'Laptop', 'prix' => 999],
            2 => ['nom' => 'Souris', 'prix' => 25],
            3 => ['nom' => 'Clavier', 'prix' => 50]
        ];
        $this->log("ServiceProduit initialisé");
    }

    public function getProduit($id) {
        $cached = $this->getFromCache("produit_{$id}");
        if ($cached) {
            $this->log("Produit {$id} récupéré du cache");
            return $cached;
        }

        if (isset($this->produits[$id])) {
            $produit = $this->produits[$id];
            $this->setCache("produit_{$id}", $produit);
            $this->log("Produit {$id} récupéré de la base de données");
            return $produit;
        }

        $this->logError("Produit {$id} non trouvé");
        return null;
    }

    public function ajouterProduit($id, $nom, $prix) {
        $this->produits[$id] = ['nom' => $nom, 'prix' => $prix];
        $this->clearCache("produit_{$id}");
        $this->log("Produit {$id} ajouté : {$nom} - {$prix}€");
    }

    public function supprimerProduit($id) {
        if (isset($this->produits[$id])) {
            unset($this->produits[$id]);
            $this->clearCache("produit_{$id}");
            $this->log("Produit {$id} supprimé");
            return true;
        }
        $this->logError("Impossible de supprimer le produit {$id}");
        return false;
    }
}

// Test
$service = new ServiceProduit();
$produit1 = $service->getProduit(1); // Récupéré de la DB
$produit2 = $service->getProduit(1); // Récupéré du cache
$service->ajouterProduit(4, "Tablette", 299);
$service->supprimerProduit(2);
?>
```

---

## 🎯 Points clés à retenir

✅ **Les traits permettent** de partager du code entre plusieurs classes  
✅ **`use`** est le mot-clé pour utiliser un trait  
✅ **Un trait peut avoir** des propriétés et des méthodes  
✅ **Les conflits de méthodes** peuvent être résolus avec `insteadof` et `as`  
✅ **Les traits sont utiles** pour éviter la duplication de code  
✅ **Les traits ne remplacent pas** l'héritage, ils le complètent

---

## 🚀 Prochaines étapes

Maintenant que vous maîtrisez les traits, passez au **[Module 8 : Les namespaces](08-Namespaces.md)** pour apprendre à organiser votre code avec les namespaces !

---

## 📚 Ressources complémentaires

- 📖 [Documentation PHP - Traits](https://www.php.net/manual/fr/language.oop5.traits.php)
- 🎥 [Vidéo : Les traits en PHP](https://www.youtube.com/watch?v=example)
- 📝 [Article : Bonnes pratiques avec les traits](https://example.com)

---

**Bonne programmation ! 🎉**


