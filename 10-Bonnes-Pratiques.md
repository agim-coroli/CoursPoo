# 📚 Module 10 : Les bonnes pratiques

> **Objectif** : Maîtriser les principes SOLID et les bonnes pratiques de développement en PHP

## 🎯 Ce que vous allez apprendre

- Les principes SOLID
- Architecture MVC en PHP
- Séparation des responsabilités
- Bonnes pratiques de code
- Design patterns courants

---

## Les principes SOLID

Les **principes SOLID** sont cinq principes fondamentaux de la programmation orientée objet qui permettent de créer du code maintenable et évolutif.

### 🏗️ S - Single Responsibility Principle (SRP)

**Une classe ne doit avoir qu'une seule raison de changer.**

#### ❌ Mauvaise pratique

```php
<?php
class User {
    public function save() {
        // Sauvegarde en base de données
    }

    public function sendEmail() {
        // Envoi d'email
    }

    public function generateReport() {
        // Génération de rapport
    }
}
?>
```

#### ✅ Bonne pratique

```php
<?php
class User {
    public function save() {
        // Sauvegarde en base de données
    }
}

class EmailService {
    public function sendEmail($user) {
        // Envoi d'email
    }
}

class ReportGenerator {
    public function generateReport($user) {
        // Génération de rapport
    }
}
?>
```

### 🔄 O - Open/Closed Principle (OCP)

**Les classes doivent être ouvertes à l'extension mais fermées à la modification.**

#### ✅ Exemple avec les processeurs de paiement

```php
<?php
abstract class PaymentProcessor {
    abstract public function process($amount);
}

class CreditCardProcessor extends PaymentProcessor {
    public function process($amount) {
        return "Paiement par carte de {$amount}€";
    }
}

class PayPalProcessor extends PaymentProcessor {
    public function process($amount) {
        return "Paiement PayPal de {$amount}€";
    }
}

class BitcoinProcessor extends PaymentProcessor {
    public function process($amount) {
        return "Paiement Bitcoin de {$amount}€";
    }
}

// Utilisation
$processors = [
    new CreditCardProcessor(),
    new PayPalProcessor(),
    new BitcoinProcessor()
];

foreach ($processors as $processor) {
    echo $processor->process(100) . "\n";
}
?>
```

### 🔄 L - Liskov Substitution Principle (LSP)

**Les objets d'une classe dérivée doivent pouvoir remplacer les objets de la classe de base.**

#### ✅ Exemple avec les véhicules

```php
<?php
class Vehicle {
    public function start() {
        return "Véhicule démarré";
    }

    public function stop() {
        return "Véhicule arrêté";
    }
}

class Car extends Vehicle {
    public function start() {
        return "Voiture démarrée avec la clé";
    }

    public function stop() {
        return "Voiture arrêtée";
    }
}

class ElectricCar extends Vehicle {
    public function start() {
        return "Voiture électrique démarrée";
    }

    public function stop() {
        return "Voiture électrique arrêtée";
    }
}

// Test de substitution
function testVehicle(Vehicle $vehicle) {
    echo $vehicle->start() . "\n";
    echo $vehicle->stop() . "\n";
}

testVehicle(new Car());
testVehicle(new ElectricCar());
?>
```

### 🔧 I - Interface Segregation Principle (ISP)

**Les clients ne doivent pas dépendre d'interfaces qu'ils n'utilisent pas.**

#### ❌ Mauvaise pratique

```php
<?php
interface Worker {
    public function work();
    public function eat();
    public function sleep();
}

class Human implements Worker {
    public function work() { return "Humain travaille"; }
    public function eat() { return "Humain mange"; }
    public function sleep() { return "Humain dort"; }
}

class Robot implements Worker {
    public function work() { return "Robot travaille"; }
    public function eat() { return "Robot ne mange pas"; } // Inutile
    public function sleep() { return "Robot ne dort pas"; } // Inutile
}
?>
```

#### ✅ Bonne pratique

```php
<?php
interface Workable {
    public function work();
}

interface Eatable {
    public function eat();
}

interface Sleepable {
    public function sleep();
}

class Human implements Workable, Eatable, Sleepable {
    public function work() { return "Humain travaille"; }
    public function eat() { return "Humain mange"; }
    public function sleep() { return "Humain dort"; }
}

class Robot implements Workable {
    public function work() { return "Robot travaille"; }
}
?>
```

### 🔄 D - Dependency Inversion Principle (DIP)

**Les modules de haut niveau ne doivent pas dépendre des modules de bas niveau. Les deux doivent dépendre d'abstractions.**

#### ❌ Mauvaise pratique

```php
<?php
class MySQLDatabase {
    public function save($data) {
        return "Sauvegardé en MySQL";
    }
}

class UserRepository {
    private $database;

    public function __construct() {
        $this->database = new MySQLDatabase(); // Dépendance directe
    }

    public function saveUser($user) {
        return $this->database->save($user);
    }
}
?>
```

#### ✅ Bonne pratique

```php
<?php
interface DatabaseInterface {
    public function save($data);
}

class MySQLDatabase implements DatabaseInterface {
    public function save($data) {
        return "Sauvegardé en MySQL";
    }
}

class PostgreSQLDatabase implements DatabaseInterface {
    public function save($data) {
        return "Sauvegardé en PostgreSQL";
    }
}

class UserRepository {
    private $database;

    public function __construct(DatabaseInterface $database) {
        $this->database = $database; // Dépendance par injection
    }

    public function saveUser($user) {
        return $this->database->save($user);
    }
}

// Utilisation
$mysqlDb = new MySQLDatabase();
$userRepo = new UserRepository($mysqlDb);

$postgresDb = new PostgreSQLDatabase();
$userRepo2 = new UserRepository($postgresDb);
?>
```

---

## Architecture MVC en PHP

### 🏗️ Modèle (Model)

```php
<?php
namespace App\Models;

class User {
    private $id;
    private $nom;
    private $email;

    public function __construct($nom, $email) {
        $this->id = uniqid();
        $this->nom = $nom;
        $this->email = $email;
    }

    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getEmail() { return $this->email; }

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
}
?>
```

### 🎮 Contrôleur (Controller)

```php
<?php
namespace App\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Views\UserView;

class UserController {
    private $userService;
    private $userView;

    public function __construct() {
        $this->userService = new UserService();
        $this->userView = new UserView();
    }

    public function createUser($nom, $email) {
        try {
            $user = $this->userService->createUser($nom, $email);
            return $this->userView->renderSuccess($user);
        } catch (Exception $e) {
            return $this->userView->renderError($e->getMessage());
        }
    }

    public function getUser($id) {
        try {
            $user = $this->userService->getUser($id);
            return $this->userView->renderUser($user);
        } catch (Exception $e) {
            return $this->userView->renderError($e->getMessage());
        }
    }
}
?>
```

### 👁️ Vue (View)

```php
<?php
namespace App\Views;

use App\Models\User;

class UserView {
    public function renderUser(User $user) {
        return "<h1>Utilisateur : {$user->getNom()}</h1>" .
               "<p>Email : {$user->getEmail()}</p>";
    }

    public function renderSuccess(User $user) {
        return "<div class='success'>Utilisateur {$user->getNom()} créé avec succès</div>";
    }

    public function renderError($message) {
        return "<div class='error'>Erreur : {$message}</div>";
    }
}
?>
```

### 🔧 Service (Service)

```php
<?php
namespace App\Services;

use App\Models\User;

class UserService {
    private $users = [];

    public function createUser($nom, $email) {
        if (empty($nom) || empty($email)) {
            throw new Exception("Nom et email requis");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email invalide");
        }

        $user = new User($nom, $email);
        $this->users[] = $user;

        return $user;
    }

    public function getUser($id) {
        foreach ($this->users as $user) {
            if ($user->getId() === $id) {
                return $user;
            }
        }
        throw new Exception("Utilisateur non trouvé");
    }

    public function getAllUsers() {
        return $this->users;
    }
}
?>
```

---

## Séparation des responsabilités

### 🏗️ Architecture en couches

```php
<?php
// Couche de données (Data Layer)
namespace App\Repositories;

interface UserRepositoryInterface {
    public function save(User $user);
    public function findById($id);
    public function findAll();
}

class UserRepository implements UserRepositoryInterface {
    private $database;

    public function __construct(DatabaseInterface $database) {
        $this->database = $database;
    }

    public function save(User $user) {
        return $this->database->save($user);
    }

    public function findById($id) {
        return $this->database->findById($id);
    }

    public function findAll() {
        return $this->database->findAll();
    }
}

// Couche de service (Service Layer)
namespace App\Services;

class UserService {
    private $userRepository;
    private $emailService;

    public function __construct(UserRepositoryInterface $userRepository, EmailService $emailService) {
        $this->userRepository = $userRepository;
        $this->emailService = $emailService;
    }

    public function createUser($nom, $email) {
        $user = new User($nom, $email);
        $this->userRepository->save($user);
        $this->emailService->sendWelcomeEmail($user);
        return $user;
    }
}

// Couche de contrôle (Controller Layer)
namespace App\Controllers;

class UserController {
    private $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function createUser($data) {
        try {
            $user = $this->userService->createUser($data['nom'], $data['email']);
            return $this->jsonResponse(['success' => true, 'user' => $user]);
        } catch (Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    private function jsonResponse($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        return json_encode($data);
    }
}
?>
```

---

## Design Patterns courants

### 🏭 Factory Pattern

```php
<?php
interface PaymentProcessor {
    public function process($amount);
}

class CreditCardProcessor implements PaymentProcessor {
    public function process($amount) {
        return "Paiement par carte de {$amount}€";
    }
}

class PayPalProcessor implements PaymentProcessor {
    public function process($amount) {
        return "Paiement PayPal de {$amount}€";
    }
}

class PaymentProcessorFactory {
    public static function create($type) {
        switch ($type) {
            case 'creditcard':
                return new CreditCardProcessor();
            case 'paypal':
                return new PayPalProcessor();
            default:
                throw new Exception("Type de processeur non supporté");
        }
    }
}

// Utilisation
$processor = PaymentProcessorFactory::create('creditcard');
echo $processor->process(100);
?>
```

### 🔧 Singleton Pattern

```php
<?php
class DatabaseConnection {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $this->connection = new PDO("mysql:host=localhost;dbname=ma_base", "user", "password");
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}

// Utilisation
$db = DatabaseConnection::getInstance();
$connection = $db->getConnection();
?>
```

### 👁️ Observer Pattern

```php
<?php
interface Observer {
    public function update($data);
}

interface Subject {
    public function attach(Observer $observer);
    public function detach(Observer $observer);
    public function notify();
}

class User implements Subject {
    private $observers = [];
    private $nom;
    private $email;

    public function attach(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer) {
        $key = array_search($observer, $this->observers);
        if ($key !== false) {
            unset($this->observers[$key]);
        }
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function setNom($nom) {
        $this->nom = $nom;
        $this->notify();
    }

    public function setEmail($email) {
        $this->email = $email;
        $this->notify();
    }
}

class EmailNotifier implements Observer {
    public function update($data) {
        echo "Email de notification envoyé pour {$data->getNom()}\n";
    }
}

class LogNotifier implements Observer {
    public function update($data) {
        echo "Log enregistré pour {$data->getNom()}\n";
    }
}

// Utilisation
$user = new User();
$user->attach(new EmailNotifier());
$user->attach(new LogNotifier());

$user->setNom("Alice");
$user->setEmail("alice@example.com");
?>
```

---

## 🎯 Exercices pratiques

### Exercice 1 : Système de gestion de commandes

Créez un système de gestion de commandes en respectant les principes SOLID :

```php
<?php
// Interface pour les processeurs de paiement
interface PaymentProcessor {
    public function process($amount);
}

// Implémentations concrètes
class CreditCardProcessor implements PaymentProcessor {
    public function process($amount) {
        return "Paiement par carte de {$amount}€";
    }
}

class PayPalProcessor implements PaymentProcessor {
    public function process($amount) {
        return "Paiement PayPal de {$amount}€";
    }
}

// Interface pour les services de notification
interface NotificationService {
    public function send($message);
}

class EmailNotificationService implements NotificationService {
    public function send($message) {
        return "Email envoyé : {$message}";
    }
}

class SMSNotificationService implements NotificationService {
    public function send($message) {
        return "SMS envoyé : {$message}";
    }
}

// Classe Order respectant SRP
class Order {
    private $id;
    private $items;
    private $total;

    public function __construct() {
        $this->id = uniqid();
        $this->items = [];
        $this->total = 0;
    }

    public function addItem($item, $price) {
        $this->items[] = ['item' => $item, 'price' => $price];
        $this->total += $price;
    }

    public function getTotal() {
        return $this->total;
    }

    public function getId() {
        return $this->id;
    }
}

// Service de commande respectant DIP
class OrderService {
    private $paymentProcessor;
    private $notificationService;

    public function __construct(PaymentProcessor $paymentProcessor, NotificationService $notificationService) {
        $this->paymentProcessor = $paymentProcessor;
        $this->notificationService = $notificationService;
    }

    public function processOrder(Order $order) {
        $paymentResult = $this->paymentProcessor->process($order->getTotal());
        $notificationResult = $this->notificationService->send("Commande {$order->getId()} traitée");

        return [
            'payment' => $paymentResult,
            'notification' => $notificationResult
        ];
    }
}

// Test
$order = new Order();
$order->addItem("Laptop", 999);
$order->addItem("Souris", 25);

$orderService = new OrderService(
    new CreditCardProcessor(),
    new EmailNotificationService()
);

$result = $orderService->processOrder($order);
print_r($result);
?>
```

### Exercice 2 : Système de gestion d'utilisateurs

Créez un système de gestion d'utilisateurs avec architecture MVC :

```php
<?php
// Modèle
namespace App\Models;

class User {
    private $id;
    private $nom;
    private $email;
    private $role;

    public function __construct($nom, $email, $role = 'user') {
        $this->id = uniqid();
        $this->nom = $nom;
        $this->email = $email;
        $this->role = $role;
    }

    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getEmail() { return $this->email; }
    public function getRole() { return $this->role; }

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
}

// Service
namespace App\Services;

use App\Models\User;

class UserService {
    private $users = [];

    public function createUser($nom, $email, $role = 'user') {
        if (empty($nom) || empty($email)) {
            throw new Exception("Nom et email requis");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email invalide");
        }

        $user = new User($nom, $email, $role);
        $this->users[] = $user;

        return $user;
    }

    public function getUser($id) {
        foreach ($this->users as $user) {
            if ($user->getId() === $id) {
                return $user;
            }
        }
        throw new Exception("Utilisateur non trouvé");
    }

    public function getAllUsers() {
        return $this->users;
    }

    public function updateUser($id, $nom, $email) {
        $user = $this->getUser($id);

        if (!$user->setNom($nom)) {
            throw new Exception("Nom invalide");
        }

        if (!$user->setEmail($email)) {
            throw new Exception("Email invalide");
        }

        return $user;
    }
}

// Contrôleur
namespace App\Controllers;

use App\Services\UserService;
use App\Views\UserView;

class UserController {
    private $userService;
    private $userView;

    public function __construct() {
        $this->userService = new UserService();
        $this->userView = new UserView();
    }

    public function createUser($data) {
        try {
            $user = $this->userService->createUser($data['nom'], $data['email'], $data['role'] ?? 'user');
            return $this->userView->renderSuccess("Utilisateur créé avec succès", $user);
        } catch (Exception $e) {
            return $this->userView->renderError($e->getMessage());
        }
    }

    public function getUser($id) {
        try {
            $user = $this->userService->getUser($id);
            return $this->userView->renderUser($user);
        } catch (Exception $e) {
            return $this->userView->renderError($e->getMessage());
        }
    }

    public function getAllUsers() {
        try {
            $users = $this->userService->getAllUsers();
            return $this->userView->renderUsers($users);
        } catch (Exception $e) {
            return $this->userView->renderError($e->getMessage());
        }
    }
}

// Vue
namespace App\Views;

use App\Models\User;

class UserView {
    public function renderUser(User $user) {
        return "<div class='user'>" .
               "<h3>{$user->getNom()}</h3>" .
               "<p>Email : {$user->getEmail()}</p>" .
               "<p>Rôle : {$user->getRole()}</p>" .
               "</div>";
    }

    public function renderUsers($users) {
        $html = "<div class='users'>";
        foreach ($users as $user) {
            $html .= $this->renderUser($user);
        }
        $html .= "</div>";
        return $html;
    }

    public function renderSuccess($message, User $user = null) {
        $html = "<div class='success'>{$message}</div>";
        if ($user) {
            $html .= $this->renderUser($user);
        }
        return $html;
    }

    public function renderError($message) {
        return "<div class='error'>{$message}</div>";
    }
}

// Test
$controller = new UserController();

// Créer un utilisateur
$result = $controller->createUser([
    'nom' => 'Alice',
    'email' => 'alice@example.com',
    'role' => 'admin'
]);
echo $result;

// Lister tous les utilisateurs
$result = $controller->getAllUsers();
echo $result;
?>
```

---

## 🎯 Points clés à retenir

✅ **Les principes SOLID** améliorent la maintenabilité du code  
✅ **SRP** : Une classe, une responsabilité  
✅ **OCP** : Ouvert à l'extension, fermé à la modification  
✅ **LSP** : Les classes enfants doivent pouvoir remplacer les parents  
✅ **ISP** : Interfaces spécifiques plutôt que générales  
✅ **DIP** : Dépendre d'abstractions, pas de concrétions  
✅ **L'architecture MVC** sépare les responsabilités  
✅ **Les design patterns** résolvent des problèmes courants

---

## 🚀 Prochaines étapes

Maintenant que vous maîtrisez les bonnes pratiques, passez au **[Module 11 : Exercices pratiques](11-Exercices-Pratiques.md)** pour mettre en pratique tous les concepts appris !

---

## 📚 Ressources complémentaires

- 📖 [Documentation PHP - Bonnes pratiques](https://www.php.net/manual/fr/language.oop5.php)
- 🎥 [Vidéo : Les principes SOLID](https://www.youtube.com/watch?v=example)
- 📝 [Article : Design patterns en PHP](https://example.com)

---

**Bonne programmation ! 🎉**

