# 📚 Module 9 : La gestion des erreurs

> **Objectif** : Maîtriser la gestion des erreurs orientée objet avec les exceptions en PHP

## 🎯 Ce que vous allez apprendre

- Qu'est-ce qu'une exception
- Créer des exceptions personnalisées
- Utiliser try, catch, finally
- Gérer les erreurs de manière professionnelle
- Bonnes pratiques de gestion d'erreurs

---

## Qu'est-ce qu'une exception ?

Une **exception** est un événement qui se produit pendant l'exécution d'un programme et qui interrompt le flux normal d'exécution. C'est comme une alerte qui dit "quelque chose ne va pas !"

### 🚨 Analogie avec les alertes

- **Exception** = Alerte d'incendie
- **Try** = Vérifier si tout va bien
- **Catch** = Réagir à l'alerte
- **Finally** = Nettoyer après l'alerte

---

## Syntaxe de base des exceptions

### Structure try-catch

```php
<?php
try {
    // Code qui peut générer une exception
    $resultat = 10 / 0; // Division par zéro
} catch (Exception $e) {
    // Code exécuté en cas d'exception
    echo "Erreur : " . $e->getMessage();
}
?>
```

### Exemple simple

```php
<?php
function diviser($a, $b) {
    if ($b == 0) {
        throw new Exception("Division par zéro impossible");
    }
    return $a / $b;
}

try {
    $resultat = diviser(10, 0);
    echo "Résultat : " . $resultat;
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
```

---

## Exceptions personnalisées

### Création d'exceptions personnalisées

```php
<?php
class ValidationException extends Exception {
    private $errors = [];

    public function __construct($message, $errors = []) {
        parent::__construct($message);
        $this->errors = $errors;
    }

    public function getErrors() {
        return $this->errors;
    }
}

class DatabaseException extends Exception {
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

class FileNotFoundException extends Exception {
    private $filename;

    public function __construct($filename) {
        $this->filename = $filename;
        parent::__construct("Fichier non trouvé : {$filename}");
    }

    public function getFilename() {
        return $this->filename;
    }
}
?>
```

---

## Exemple complet : Système de validation

```php
<?php
class ValidationException extends Exception {
    private $errors = [];

    public function __construct($message, $errors = []) {
        parent::__construct($message);
        $this->errors = $errors;
    }

    public function getErrors() {
        return $this->errors;
    }
}

class UserService {
    public function creerUtilisateur($nom, $email, $age) {
        $errors = [];

        // Validation du nom
        if (empty($nom)) {
            $errors[] = "Le nom est requis";
        } elseif (strlen($nom) < 2) {
            $errors[] = "Le nom doit contenir au moins 2 caractères";
        }

        // Validation de l'email
        if (empty($email)) {
            $errors[] = "L'email est requis";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'email est invalide";
        }

        // Validation de l'âge
        if (!is_numeric($age)) {
            $errors[] = "L'âge doit être un nombre";
        } elseif ($age < 0 || $age > 120) {
            $errors[] = "L'âge doit être entre 0 et 120 ans";
        }

        // Si des erreurs existent, lancer une exception
        if (!empty($errors)) {
            throw new ValidationException("Erreurs de validation", $errors);
        }

        // Créer l'utilisateur si tout est valide
        return new User($nom, $email, $age);
    }
}

class User {
    private $nom;
    private $email;
    private $age;

    public function __construct($nom, $email, $age) {
        $this->nom = $nom;
        $this->email = $email;
        $this->age = $age;
    }

    public function getNom() { return $this->nom; }
    public function getEmail() { return $this->email; }
    public function getAge() { return $this->age; }
}

// Test
$userService = new UserService();

try {
    $user = $userService->creerUtilisateur("", "email-invalide", -5);
    echo "Utilisateur créé avec succès";
} catch (ValidationException $e) {
    echo "Erreurs de validation :\n";
    foreach ($e->getErrors() as $error) {
        echo "- " . $error . "\n";
    }
} catch (Exception $e) {
    echo "Erreur générale : " . $e->getMessage();
}
?>
```

---

## Gestion des erreurs avec try-catch-finally

### Structure complète

```php
<?php
try {
    // Code qui peut générer une exception
    $fichier = fopen("fichier.txt", "r");
    $contenu = fread($fichier, filesize("fichier.txt"));
    fclose($fichier);

    echo "Contenu lu avec succès";
} catch (FileNotFoundException $e) {
    echo "Fichier non trouvé : " . $e->getFilename();
} catch (Exception $e) {
    echo "Erreur générale : " . $e->getMessage();
} finally {
    // Code exécuté dans tous les cas
    echo "Nettoyage terminé";
}
?>
```

### Exemple pratique : Gestion de base de données

```php
<?php
class DatabaseException extends Exception {
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class DatabaseConnection {
    private $connection;
    private $host;
    private $username;
    private $password;
    private $database;

    public function __construct($host, $username, $password, $database) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    public function connect() {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->database}",
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        } catch (PDOException $e) {
            throw new DatabaseException("Impossible de se connecter à la base de données : " . $e->getMessage());
        }
    }

    public function query($sql, $params = []) {
        if (!$this->connection) {
            throw new DatabaseException("Aucune connexion à la base de données");
        }

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new DatabaseException("Erreur lors de l'exécution de la requête : " . $e->getMessage());
        }
    }

    public function close() {
        $this->connection = null;
    }
}

// Test
$db = new DatabaseConnection("localhost", "user", "password", "ma_base");

try {
    $db->connect();
    $result = $db->query("SELECT * FROM users WHERE id = ?", [1]);
    echo "Requête exécutée avec succès";
} catch (DatabaseException $e) {
    echo "Erreur de base de données : " . $e->getMessage();
} finally {
    $db->close();
}
?>
```

---

## Exemple complet : Système de fichiers

```php
<?php
class FileNotFoundException extends Exception {
    private $filename;

    public function __construct($filename) {
        $this->filename = $filename;
        parent::__construct("Fichier non trouvé : {$filename}");
    }

    public function getFilename() {
        return $this->filename;
    }
}

class FileManager {
    private $basePath;

    public function __construct($basePath) {
        $this->basePath = $basePath;
    }

    public function readFile($filename) {
        $fullPath = $this->basePath . '/' . $filename;

        if (!file_exists($fullPath)) {
            throw new FileNotFoundException($filename);
        }

        if (!is_readable($fullPath)) {
            throw new Exception("Fichier non lisible : {$filename}");
        }

        return file_get_contents($fullPath);
    }

    public function writeFile($filename, $content) {
        $fullPath = $this->basePath . '/' . $filename;

        if (!is_writable($this->basePath)) {
            throw new Exception("Répertoire non accessible en écriture");
        }

        if (file_put_contents($fullPath, $content) === false) {
            throw new Exception("Impossible d'écrire dans le fichier : {$filename}");
        }

        return true;
    }

    public function deleteFile($filename) {
        $fullPath = $this->basePath . '/' . $filename;

        if (!file_exists($fullPath)) {
            throw new FileNotFoundException($filename);
        }

        if (!unlink($fullPath)) {
            throw new Exception("Impossible de supprimer le fichier : {$filename}");
        }

        return true;
    }
}

// Test
$fileManager = new FileManager("uploads");

try {
    $content = $fileManager->readFile("document.txt");
    echo "Contenu lu : " . $content;
} catch (FileNotFoundException $e) {
    echo "Fichier non trouvé : " . $e->getFilename();
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
```

---

## 🎯 Exercices pratiques

### Exercice 1 : Système de validation d'utilisateur

Créez un système de validation avec :

- Exception `ValidationException` avec liste d'erreurs
- Classe `UserValidator` avec méthodes de validation
- Gestion des erreurs avec try-catch

```php
<?php
class ValidationException extends Exception {
    private $errors = [];

    public function __construct($message, $errors = []) {
        parent::__construct($message);
        $this->errors = $errors;
    }

    public function getErrors() {
        return $this->errors;
    }
}

class UserValidator {
    public function validerUtilisateur($nom, $email, $age, $password) {
        $errors = [];

        // Validation du nom
        if (empty($nom)) {
            $errors[] = "Le nom est requis";
        } elseif (strlen($nom) < 2) {
            $errors[] = "Le nom doit contenir au moins 2 caractères";
        }

        // Validation de l'email
        if (empty($email)) {
            $errors[] = "L'email est requis";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'email est invalide";
        }

        // Validation de l'âge
        if (!is_numeric($age)) {
            $errors[] = "L'âge doit être un nombre";
        } elseif ($age < 0 || $age > 120) {
            $errors[] = "L'âge doit être entre 0 et 120 ans";
        }

        // Validation du mot de passe
        if (empty($password)) {
            $errors[] = "Le mot de passe est requis";
        } elseif (strlen($password) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
        }

        if (!empty($errors)) {
            throw new ValidationException("Erreurs de validation", $errors);
        }

        return true;
    }
}

// Test
$validator = new UserValidator();

try {
    $validator->validerUtilisateur("", "email-invalide", -5, "123");
    echo "Utilisateur validé avec succès";
} catch (ValidationException $e) {
    echo "Erreurs de validation :\n";
    foreach ($e->getErrors() as $error) {
        echo "- " . $error . "\n";
    }
}
?>
```

### Exercice 2 : Système de gestion de fichiers

Créez un système de gestion de fichiers avec :

- Exception `FileNotFoundException`
- Exception `FilePermissionException`
- Classe `FileManager` avec gestion d'erreurs

```php
<?php
class FileNotFoundException extends Exception {
    private $filename;

    public function __construct($filename) {
        $this->filename = $filename;
        parent::__construct("Fichier non trouvé : {$filename}");
    }

    public function getFilename() {
        return $this->filename;
    }
}

class FilePermissionException extends Exception {
    private $filename;
    private $operation;

    public function __construct($filename, $operation) {
        $this->filename = $filename;
        $this->operation = $operation;
        parent::__construct("Permission refusée pour {$operation} sur {$filename}");
    }

    public function getFilename() {
        return $this->filename;
    }

    public function getOperation() {
        return $this->operation;
    }
}

class FileManager {
    private $basePath;

    public function __construct($basePath) {
        $this->basePath = $basePath;
    }

    public function readFile($filename) {
        $fullPath = $this->basePath . '/' . $filename;

        if (!file_exists($fullPath)) {
            throw new FileNotFoundException($filename);
        }

        if (!is_readable($fullPath)) {
            throw new FilePermissionException($filename, "lecture");
        }

        return file_get_contents($fullPath);
    }

    public function writeFile($filename, $content) {
        $fullPath = $this->basePath . '/' . $filename;

        if (!is_writable($this->basePath)) {
            throw new FilePermissionException($filename, "écriture");
        }

        if (file_put_contents($fullPath, $content) === false) {
            throw new Exception("Impossible d'écrire dans le fichier : {$filename}");
        }

        return true;
    }

    public function deleteFile($filename) {
        $fullPath = $this->basePath . '/' . $filename;

        if (!file_exists($fullPath)) {
            throw new FileNotFoundException($filename);
        }

        if (!is_writable($fullPath)) {
            throw new FilePermissionException($filename, "suppression");
        }

        if (!unlink($fullPath)) {
            throw new Exception("Impossible de supprimer le fichier : {$filename}");
        }

        return true;
    }
}

// Test
$fileManager = new FileManager("uploads");

try {
    $content = $fileManager->readFile("document.txt");
    echo "Contenu lu : " . $content;
} catch (FileNotFoundException $e) {
    echo "Fichier non trouvé : " . $e->getFilename();
} catch (FilePermissionException $e) {
    echo "Permission refusée pour " . $e->getOperation() . " sur " . $e->getFilename();
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
```

### Exercice 3 : Système de base de données

Créez un système de base de données avec :

- Exception `DatabaseException`
- Exception `QueryException`
- Classe `DatabaseManager` avec gestion d'erreurs

```php
<?php
class DatabaseException extends Exception {
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class QueryException extends Exception {
    private $query;

    public function __construct($message, $query) {
        $this->query = $query;
        parent::__construct($message);
    }

    public function getQuery() {
        return $this->query;
    }
}

class DatabaseManager {
    private $connection;
    private $host;
    private $username;
    private $password;
    private $database;

    public function __construct($host, $username, $password, $database) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    public function connect() {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->database}",
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        } catch (PDOException $e) {
            throw new DatabaseException("Impossible de se connecter à la base de données : " . $e->getMessage());
        }
    }

    public function query($sql, $params = []) {
        if (!$this->connection) {
            throw new DatabaseException("Aucune connexion à la base de données");
        }

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new QueryException("Erreur lors de l'exécution de la requête : " . $e->getMessage(), $sql);
        }
    }

    public function insert($table, $data) {
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";

        try {
            $stmt = $this->query($sql, $data);
            return $this->connection->lastInsertId();
        } catch (QueryException $e) {
            throw new QueryException("Erreur lors de l'insertion : " . $e->getMessage(), $sql);
        }
    }

    public function close() {
        $this->connection = null;
    }
}

// Test
$db = new DatabaseManager("localhost", "user", "password", "ma_base");

try {
    $db->connect();
    $result = $db->query("SELECT * FROM users WHERE id = ?", [1]);
    echo "Requête exécutée avec succès";
} catch (DatabaseException $e) {
    echo "Erreur de base de données : " . $e->getMessage();
} catch (QueryException $e) {
    echo "Erreur de requête : " . $e->getMessage();
    echo "Requête : " . $e->getQuery();
} finally {
    $db->close();
}
?>
```

---

## 🎯 Points clés à retenir

✅ **Les exceptions** permettent de gérer les erreurs de manière élégante  
✅ **`try-catch-finally`** structure la gestion des erreurs  
✅ **Les exceptions personnalisées** permettent de créer des erreurs spécifiques  
✅ **`throw`** lance une exception  
✅ **`catch`** capture et traite les exceptions  
✅ **`finally`** exécute du code dans tous les cas

---

## 🚀 Prochaines étapes

Maintenant que vous maîtrisez la gestion des erreurs, passez au **[Module 10 : Les bonnes pratiques](10-Bonnes-Pratiques.md)** pour apprendre les principes SOLID et les bonnes pratiques de développement !

---

## 📚 Ressources complémentaires

- 📖 [Documentation PHP - Exceptions](https://www.php.net/manual/fr/language.exceptions.php)
- 🎥 [Vidéo : La gestion des erreurs en PHP](https://www.youtube.com/watch?v=example)
- 📝 [Article : Bonnes pratiques de gestion d'erreurs](https://example.com)

---

**Bonne programmation ! 🎉**

