# 📚 Module 11 : Exercices pratiques

> **Objectif** : Mettre en pratique tous les concepts appris avec des exercices concrets

## 🎯 Ce que vous allez apprendre

- Créer une classe User complète
- Développer un système de login orienté objet
- Construire une mini API REST en POO
- Appliquer les principes SOLID
- Utiliser l'architecture MVC

---

## Exercice 1 : Classe User complète

### 🎯 Objectif

Créer une classe `User` complète avec toutes les fonctionnalités de base d'un utilisateur.

### 📋 Spécifications

- Propriétés : id, nom, email, mot de passe, date de création, statut
- Méthodes : getters, setters avec validation, activation/désactivation
- Validation : email, mot de passe, nom
- Sécurité : hachage du mot de passe

### 💻 Solution

```php
<?php
class User {
    private $id;
    private $nom;
    private $email;
    private $passwordHash;
    private $dateCreation;
    private $actif;

    public function __construct($nom, $email, $password) {
        $this->id = uniqid();
        $this->nom = $nom;
        $this->email = $email;
        $this->passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $this->dateCreation = new DateTime();
        $this->actif = true;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getEmail() { return $this->email; }
    public function getDateCreation() { return $this->dateCreation; }
    public function isActif() { return $this->actif; }

    // Setters avec validation
    public function setNom($nom) {
        if (strlen($nom) >= 2) {
            $this->nom = trim($nom);
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

    public function setPassword($password) {
        if (strlen($password) >= 8) {
            $this->passwordHash = password_hash($password, PASSWORD_DEFAULT);
            return true;
        }
        return false;
    }

    // Méthodes de gestion
    public function activer() {
        $this->actif = true;
    }

    public function desactiver() {
        $this->actif = false;
    }

    public function verifierPassword($password) {
        return password_verify($password, $this->passwordHash);
    }

    public function getInfo() {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'email' => $this->email,
            'date_creation' => $this->dateCreation->format('Y-m-d H:i:s'),
            'actif' => $this->actif
        ];
    }

    public function toJson() {
        return json_encode($this->getInfo());
    }
}

// Test de la classe
$user = new User("Alice Dupont", "alice@example.com", "motdepasse123");
echo "Utilisateur créé : " . $user->getNom() . "\n";

// Test des setters
if ($user->setNom("Alice Martin")) {
    echo "Nom modifié avec succès\n";
}

if ($user->setEmail("alice.martin@example.com")) {
    echo "Email modifié avec succès\n";
}

// Test de vérification du mot de passe
if ($user->verifierPassword("motdepasse123")) {
    echo "Mot de passe correct\n";
} else {
    echo "Mot de passe incorrect\n";
}

// Affichage des informations
echo "Informations utilisateur :\n";
echo $user->toJson() . "\n";
?>
```

---

## Exercice 2 : Système de login orienté objet

### 🎯 Objectif

Créer un système d'authentification complet avec gestion des erreurs.

### 📋 Spécifications

- Classe `AuthService` pour gérer l'authentification
- Méthodes : register, login, logout
- Validation des données
- Gestion des erreurs avec exceptions
- Sécurité : hachage des mots de passe

### 💻 Solution

```php
<?php
class AuthException extends Exception {
    public function __construct($message) {
        parent::__construct($message);
    }
}

class AuthService {
    private $users = [];
    private $currentUser = null;

    public function register($nom, $email, $password) {
        // Vérifier si l'email existe déjà
        if ($this->emailExists($email)) {
            throw new AuthException("Email déjà utilisé");
        }

        // Valider les données
        if (empty($nom) || strlen($nom) < 2) {
            throw new AuthException("Nom invalide");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new AuthException("Email invalide");
        }

        if (strlen($password) < 8) {
            throw new AuthException("Mot de passe trop court (minimum 8 caractères)");
        }

        // Créer l'utilisateur
        $user = new User($nom, $email, $password);
        $this->users[] = $user;

        return $user;
    }

    public function login($email, $password) {
        $user = $this->findUserByEmail($email);

        if (!$user) {
            throw new AuthException("Utilisateur non trouvé");
        }

        if (!$user->isActif()) {
            throw new AuthException("Compte désactivé");
        }

        if (!$user->verifierPassword($password)) {
            throw new AuthException("Mot de passe incorrect");
        }

        $this->currentUser = $user;
        return $user;
    }

    public function logout() {
        $this->currentUser = null;
        return true;
    }

    public function getCurrentUser() {
        return $this->currentUser;
    }

    public function isLoggedIn() {
        return $this->currentUser !== null;
    }

    private function emailExists($email) {
        return $this->findUserByEmail($email) !== null;
    }

    private function findUserByEmail($email) {
        foreach ($this->users as $user) {
            if ($user->getEmail() === $email) {
                return $user;
            }
        }
        return null;
    }
}

// Test du système
$auth = new AuthService();

try {
    // Inscription
    $user = $auth->register("Bob", "bob@example.com", "motdepasse123");
    echo "Utilisateur créé avec succès\n";

    // Connexion
    $loggedUser = $auth->login("bob@example.com", "motdepasse123");
    echo "Connexion réussie pour : " . $loggedUser->getNom() . "\n";

    // Vérification de l'état de connexion
    if ($auth->isLoggedIn()) {
        echo "Utilisateur connecté : " . $auth->getCurrentUser()->getNom() . "\n";
    }

    // Déconnexion
    $auth->logout();
    echo "Déconnexion réussie\n";

} catch (AuthException $e) {
    echo "Erreur d'authentification : " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Erreur générale : " . $e->getMessage() . "\n";
}
?>
```

---

## Exercice 3 : Mini API REST en POO

### 🎯 Objectif

Créer une API REST simple avec architecture MVC et gestion des erreurs.

### 📋 Spécifications

- Routeur simple pour gérer les routes
- Contrôleurs pour gérer les requêtes
- Services pour la logique métier
- Gestion des erreurs HTTP
- Réponses JSON

### 💻 Solution

```php
<?php
// Routeur simple
class Router {
    private $routes = [];

    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                return call_user_func($route['handler']);
            }
        }

        http_response_code(404);
        return json_encode(['error' => 'Route non trouvée']);
    }
}

// Contrôleur API
class UserController {
    private $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    public function getAllUsers() {
        try {
            $users = $this->userService->getAllUsers();
            return $this->jsonResponse($users);
        } catch (Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function createUser() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (!$data || !isset($data['nom']) || !isset($data['email'])) {
                return $this->jsonResponse(['error' => 'Données manquantes'], 400);
            }

            $user = $this->userService->createUser($data['nom'], $data['email']);
            return $this->jsonResponse($user->getInfo(), 201);

        } catch (Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function getUser($id) {
        try {
            $user = $this->userService->getUser($id);
            return $this->jsonResponse($user->getInfo());
        } catch (Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 404);
        }
    }

    public function updateUser($id) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (!$data) {
                return $this->jsonResponse(['error' => 'Données manquantes'], 400);
            }

            $user = $this->userService->updateUser($id, $data);
            return $this->jsonResponse($user->getInfo());

        } catch (Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function deleteUser($id) {
        try {
            $this->userService->deleteUser($id);
            return $this->jsonResponse(['message' => 'Utilisateur supprimé']);
        } catch (Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 404);
        }
    }

    private function jsonResponse($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        return json_encode($data);
    }
}

// Service utilisateur
class UserService {
    private $users = [];

    public function createUser($nom, $email) {
        if (empty($nom) || empty($email)) {
            throw new Exception('Nom et email requis');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Email invalide');
        }

        // Vérifier si l'email existe déjà
        foreach ($this->users as $user) {
            if ($user->getEmail() === $email) {
                throw new Exception('Email déjà utilisé');
            }
        }

        $user = new User($nom, $email, 'password123'); // Mot de passe par défaut
        $this->users[] = $user;

        return $user;
    }

    public function getUser($id) {
        foreach ($this->users as $user) {
            if ($user->getId() === $id) {
                return $user;
            }
        }
        throw new Exception('Utilisateur non trouvé');
    }

    public function getAllUsers() {
        return array_map(function($user) {
            return $user->getInfo();
        }, $this->users);
    }

    public function updateUser($id, $data) {
        $user = $this->getUser($id);

        if (isset($data['nom'])) {
            if (!$user->setNom($data['nom'])) {
                throw new Exception('Nom invalide');
            }
        }

        if (isset($data['email'])) {
            if (!$user->setEmail($data['email'])) {
                throw new Exception('Email invalide');
            }
        }

        return $user;
    }

    public function deleteUser($id) {
        foreach ($this->users as $key => $user) {
            if ($user->getId() === $id) {
                unset($this->users[$key]);
                return true;
            }
        }
        throw new Exception('Utilisateur non trouvé');
    }
}

// Configuration du routeur
$router = new Router();
$controller = new UserController();

// Routes
$router->addRoute('GET', '/api/users', [$controller, 'getAllUsers']);
$router->addRoute('POST', '/api/users', [$controller, 'createUser']);
$router->addRoute('GET', '/api/users/1', [$controller, 'getUser']);
$router->addRoute('PUT', '/api/users/1', [$controller, 'updateUser']);
$router->addRoute('DELETE', '/api/users/1', [$controller, 'deleteUser']);

// Exécution
echo $router->handleRequest();
?>
```

---

## Exercice 4 : Système de gestion de bibliothèque

### 🎯 Objectif

Créer un système de gestion de bibliothèque avec architecture MVC et principes SOLID.

### 📋 Spécifications

- Modèles : Book, User, Loan
- Services : BookService, UserService, LoanService
- Contrôleurs : BookController, UserController, LoanController
- Gestion des emprunts et retours
- Validation des données

### 💻 Solution

```php
<?php
// Modèle Book
class Book {
    private $id;
    private $titre;
    private $auteur;
    private $isbn;
    private $disponible;

    public function __construct($titre, $auteur, $isbn) {
        $this->id = uniqid();
        $this->titre = $titre;
        $this->auteur = $auteur;
        $this->isbn = $isbn;
        $this->disponible = true;
    }

    public function getId() { return $this->id; }
    public function getTitre() { return $this->titre; }
    public function getAuteur() { return $this->auteur; }
    public function getIsbn() { return $this->isbn; }
    public function isDisponible() { return $this->disponible; }

    public function emprunter() {
        if ($this->disponible) {
            $this->disponible = false;
            return true;
        }
        return false;
    }

    public function retourner() {
        $this->disponible = true;
        return true;
    }

    public function getInfo() {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'auteur' => $this->auteur,
            'isbn' => $this->isbn,
            'disponible' => $this->disponible
        ];
    }
}

// Modèle Loan
class Loan {
    private $id;
    private $user;
    private $book;
    private $dateEmprunt;
    private $dateRetour;
    private $actif;

    public function __construct(User $user, Book $book) {
        $this->id = uniqid();
        $this->user = $user;
        $this->book = $book;
        $this->dateEmprunt = new DateTime();
        $this->dateRetour = null;
        $this->actif = true;
    }

    public function getId() { return $this->id; }
    public function getUser() { return $this->user; }
    public function getBook() { return $this->book; }
    public function getDateEmprunt() { return $this->dateEmprunt; }
    public function getDateRetour() { return $this->dateRetour; }
    public function isActif() { return $this->actif; }

    public function retourner() {
        $this->dateRetour = new DateTime();
        $this->actif = false;
        $this->book->retourner();
        return true;
    }

    public function getInfo() {
        return [
            'id' => $this->id,
            'user' => $this->user->getNom(),
            'book' => $this->book->getTitre(),
            'date_emprunt' => $this->dateEmprunt->format('Y-m-d H:i:s'),
            'date_retour' => $this->dateRetour ? $this->dateRetour->format('Y-m-d H:i:s') : null,
            'actif' => $this->actif
        ];
    }
}

// Service BookService
class BookService {
    private $books = [];

    public function addBook($titre, $auteur, $isbn) {
        $book = new Book($titre, $auteur, $isbn);
        $this->books[] = $book;
        return $book;
    }

    public function getBook($id) {
        foreach ($this->books as $book) {
            if ($book->getId() === $id) {
                return $book;
            }
        }
        throw new Exception('Livre non trouvé');
    }

    public function getAllBooks() {
        return $this->books;
    }

    public function getAvailableBooks() {
        return array_filter($this->books, function($book) {
            return $book->isDisponible();
        });
    }
}

// Service LoanService
class LoanService {
    private $loans = [];
    private $bookService;
    private $userService;

    public function __construct(BookService $bookService, UserService $userService) {
        $this->bookService = $bookService;
        $this->userService = $userService;
    }

    public function emprunterLivre($userId, $bookId) {
        $user = $this->userService->getUser($userId);
        $book = $this->bookService->getBook($bookId);

        if (!$book->isDisponible()) {
            throw new Exception('Livre non disponible');
        }

        $loan = new Loan($user, $book);
        $book->emprunter();
        $this->loans[] = $loan;

        return $loan;
    }

    public function retournerLivre($loanId) {
        foreach ($this->loans as $loan) {
            if ($loan->getId() === $loanId && $loan->isActif()) {
                $loan->retourner();
                return $loan;
            }
        }
        throw new Exception('Emprunt non trouvé ou déjà retourné');
    }

    public function getActiveLoans() {
        return array_filter($this->loans, function($loan) {
            return $loan->isActif();
        });
    }

    public function getLoansByUser($userId) {
        return array_filter($this->loans, function($loan) use ($userId) {
            return $loan->getUser()->getId() === $userId;
        });
    }
}

// Contrôleur LibraryController
class LibraryController {
    private $bookService;
    private $userService;
    private $loanService;

    public function __construct() {
        $this->bookService = new BookService();
        $this->userService = new UserService();
        $this->loanService = new LoanService($this->bookService, $this->userService);
    }

    public function addBook($data) {
        try {
            $book = $this->bookService->addBook($data['titre'], $data['auteur'], $data['isbn']);
            return $this->jsonResponse($book->getInfo(), 201);
        } catch (Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function emprunterLivre($data) {
        try {
            $loan = $this->loanService->emprunterLivre($data['user_id'], $data['book_id']);
            return $this->jsonResponse($loan->getInfo(), 201);
        } catch (Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function retournerLivre($loanId) {
        try {
            $loan = $this->loanService->retournerLivre($loanId);
            return $this->jsonResponse($loan->getInfo());
        } catch (Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function getAvailableBooks() {
        try {
            $books = $this->bookService->getAvailableBooks();
            return $this->jsonResponse(array_map(function($book) {
                return $book->getInfo();
            }, $books));
        } catch (Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    private function jsonResponse($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        return json_encode($data);
    }
}

// Test du système
$library = new LibraryController();

// Ajouter des livres
$library->addBook(['titre' => 'Le Petit Prince', 'auteur' => 'Antoine de Saint-Exupéry', 'isbn' => '978-2-07-036822-8']);
$library->addBook(['titre' => '1984', 'auteur' => 'George Orwell', 'isbn' => '978-2-07-036822-9']);

// Créer un utilisateur
$user = new User("Alice", "alice@example.com", "password123");

// Emprunter un livre
$loan = $library->loanService->emprunterLivre($user->getId(), $library->bookService->getAllBooks()[0]->getId());
echo "Emprunt créé : " . json_encode($loan->getInfo()) . "\n";

// Retourner le livre
$library->loanService->retournerLivre($loan->getId());
echo "Livre retourné\n";
?>
```

---

## Exercice 5 : Système de gestion d'école

### 🎯 Objectif

Créer un système de gestion d'école avec architecture MVC et principes SOLID.

### 📋 Spécifications

- Modèles : Student, Teacher, Course, Grade
- Services : StudentService, TeacherService, CourseService, GradeService
- Gestion des notes et moyennes
- Validation des données
- Gestion des erreurs

### 💻 Solution

```php
<?php
// Modèle Student
class Student {
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $niveau;
    private $dateInscription;

    public function __construct($nom, $prenom, $email, $niveau) {
        $this->id = uniqid();
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->niveau = $niveau;
        $this->dateInscription = new DateTime();
    }

    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getPrenom() { return $this->prenom; }
    public function getEmail() { return $this->email; }
    public function getNiveau() { return $this->niveau; }
    public function getDateInscription() { return $this->dateInscription; }

    public function getNomComplet() {
        return $this->prenom . ' ' . $this->nom;
    }
}

// Modèle Course
class Course {
    private $id;
    private $nom;
    private $description;
    private $teacher;
    private $niveau;

    public function __construct($nom, $description, Teacher $teacher, $niveau) {
        $this->id = uniqid();
        $this->nom = $nom;
        $this->description = $description;
        $this->teacher = $teacher;
        $this->niveau = $niveau;
    }

    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getDescription() { return $this->description; }
    public function getTeacher() { return $this->teacher; }
    public function getNiveau() { return $this->niveau; }
}

// Modèle Grade
class Grade {
    private $id;
    private $student;
    private $course;
    private $note;
    private $date;
    private $commentaire;

    public function __construct(Student $student, Course $course, $note, $commentaire = '') {
        $this->id = uniqid();
        $this->student = $student;
        $this->course = $course;
        $this->note = $note;
        $this->date = new DateTime();
        $this->commentaire = $commentaire;
    }

    public function getId() { return $this->id; }
    public function getStudent() { return $this->student; }
    public function getCourse() { return $this->course; }
    public function getNote() { return $this->note; }
    public function getDate() { return $this->date; }
    public function getCommentaire() { return $this->commentaire; }
}

// Service GradeService
class GradeService {
    private $grades = [];

    public function addGrade(Student $student, Course $course, $note, $commentaire = '') {
        if ($note < 0 || $note > 20) {
            throw new Exception('Note invalide (doit être entre 0 et 20)');
        }

        $grade = new Grade($student, $course, $note, $commentaire);
        $this->grades[] = $grade;

        return $grade;
    }

    public function getGradesByStudent(Student $student) {
        return array_filter($this->grades, function($grade) use ($student) {
            return $grade->getStudent()->getId() === $student->getId();
        });
    }

    public function getGradesByCourse(Course $course) {
        return array_filter($this->grades, function($grade) use ($course) {
            return $grade->getCourse()->getId() === $course->getId();
        });
    }

    public function calculateAverage(Student $student) {
        $grades = $this->getGradesByStudent($student);

        if (empty($grades)) {
            return 0;
        }

        $total = 0;
        foreach ($grades as $grade) {
            $total += $grade->getNote();
        }

        return $total / count($grades);
    }

    public function calculateCourseAverage(Course $course) {
        $grades = $this->getGradesByCourse($course);

        if (empty($grades)) {
            return 0;
        }

        $total = 0;
        foreach ($grades as $grade) {
            $total += $grade->getNote();
        }

        return $total / count($grades);
    }
}

// Contrôleur SchoolController
class SchoolController {
    private $students = [];
    private $teachers = [];
    private $courses = [];
    private $gradeService;

    public function __construct() {
        $this->gradeService = new GradeService();
    }

    public function addStudent($nom, $prenom, $email, $niveau) {
        $student = new Student($nom, $prenom, $email, $niveau);
        $this->students[] = $student;
        return $student;
    }

    public function addTeacher($nom, $prenom, $email, $specialite) {
        $teacher = new Teacher($nom, $prenom, $email, $specialite);
        $this->teachers[] = $teacher;
        return $teacher;
    }

    public function addCourse($nom, $description, Teacher $teacher, $niveau) {
        $course = new Course($nom, $description, $teacher, $niveau);
        $this->courses[] = $course;
        return $course;
    }

    public function addGrade(Student $student, Course $course, $note, $commentaire = '') {
        return $this->gradeService->addGrade($student, $course, $note, $commentaire);
    }

    public function getStudentReport(Student $student) {
        $grades = $this->gradeService->getGradesByStudent($student);
        $average = $this->gradeService->calculateAverage($student);

        return [
            'student' => $student->getNomComplet(),
            'grades' => array_map(function($grade) {
                return [
                    'course' => $grade->getCourse()->getNom(),
                    'note' => $grade->getNote(),
                    'date' => $grade->getDate()->format('Y-m-d'),
                    'commentaire' => $grade->getCommentaire()
                ];
            }, $grades),
            'average' => round($average, 2)
        ];
    }

    public function getCourseReport(Course $course) {
        $grades = $this->gradeService->getGradesByCourse($course);
        $average = $this->gradeService->calculateCourseAverage($course);

        return [
            'course' => $course->getNom(),
            'teacher' => $course->getTeacher()->getNomComplet(),
            'grades' => array_map(function($grade) {
                return [
                    'student' => $grade->getStudent()->getNomComplet(),
                    'note' => $grade->getNote(),
                    'date' => $grade->getDate()->format('Y-m-d')
                ];
            }, $grades),
            'average' => round($average, 2)
        ];
    }
}

// Test du système
$school = new SchoolController();

// Créer des étudiants
$student1 = $school->addStudent("Dupont", "Alice", "alice@example.com", "L3");
$student2 = $school->addStudent("Martin", "Bob", "bob@example.com", "L3");

// Créer un professeur
$teacher = $school->addTeacher("Durand", "Jean", "jean@example.com", "Informatique");

// Créer un cours
$course = $school->addCourse("Programmation PHP", "Cours de programmation orientée objet", $teacher, "L3");

// Ajouter des notes
$school->addGrade($student1, $course, 15, "Très bon travail");
$school->addGrade($student1, $course, 18, "Excellent");
$school->addGrade($student2, $course, 12, "Correct");

// Générer les rapports
$report1 = $school->getStudentReport($student1);
echo "Rapport étudiant : " . json_encode($report1, JSON_PRETTY_PRINT) . "\n";

$report2 = $school->getCourseReport($course);
echo "Rapport cours : " . json_encode($report2, JSON_PRETTY_PRINT) . "\n";
?>
```

---

## 🎯 Points clés à retenir

✅ **Pratiquez régulièrement** pour maîtriser les concepts  
✅ **Appliquez les principes SOLID** dans vos projets  
✅ **Utilisez l'architecture MVC** pour organiser votre code  
✅ **Gérez les erreurs** avec des exceptions appropriées  
✅ **Validez toujours les données** avant de les traiter  
✅ **Documentez votre code** pour faciliter la maintenance

---

## 🚀 Prochaines étapes

Maintenant que vous avez pratiqué avec ces exercices, consultez le **[Module 12 : Ressources complémentaires](12-Ressources.md)** pour approfondir vos connaissances !

---

## 📚 Ressources complémentaires

- 📖 [Documentation PHP officielle](https://www.php.net/manual/fr/)
- 🎥 [Tutoriels vidéo PHP](https://www.youtube.com/watch?v=example)
- 📝 [Projets open-source à étudier](https://example.com)

---

**Bonne programmation ! 🎉**


