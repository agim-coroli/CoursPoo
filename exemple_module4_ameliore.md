# 📚 Exemple d'amélioration - Module 4 : Constructeurs et Destructeurs

## ❌ AVANT : Exercice sans objectif clair

### Exercice 2 : Classe Connexion avec destructeur

Créez une classe `Connexion` qui simule une connexion à une base de données :

- Constructeur : établit la connexion
- Destructeur : ferme la connexion et sauvegarde les logs
- Méthodes : `executerRequete()`, `obtenirLogs()`

## ✅ APRÈS : Exercice avec objectif concret

### Exercice 2 : Système de gestion de base de données pour une application web

**🎯 OBJECTIF :** Créer un système de gestion de base de données sécurisé pour une application e-commerce avec gestion automatique des connexions.

**📋 SCÉNARIO :** Vous développez une boutique en ligne. Vous devez :

- Gérer les connexions à la base de données de manière sécurisée
- Exécuter des requêtes SQL de manière contrôlée
- Sauvegarder automatiquement les logs d'activité
- Fermer proprement les connexions pour éviter les fuites mémoire
- Tracker les performances de la base de données

**📝 SPÉCIFICATIONS :**

- Classe `GestionnaireBDD` avec propriétés privées : `host`, `database`, `utilisateur`, `logs`, `connexionActive`
- Constructeur : établit la connexion et initialise les logs
- Destructeur : ferme la connexion et sauvegarde les logs
- Méthodes : `executerRequete()`, `obtenirLogs()`, `verifierConnexion()`

**✅ RÉSULTAT ATTENDU :**

```
=== SYSTÈME DE BASE DE DONNÉES ===
Connexion établie à localhost/boutique_online
Connexion active : Oui
Requête 'SELECT * FROM produits WHERE prix < 100' exécutée avec succès
Requête 'INSERT INTO commandes VALUES (...)' exécutée avec succès
Requête 'UPDATE stock SET quantite = quantite - 1' exécutée avec succès
=== FERMETURE DE LA CONNEXION ===
Connexion fermée. Nombre d'opérations : 3
Logs sauvegardés : 3 entrées
=== LOGS D'ACTIVITÉ ===
- 2024-01-15 10:30:15 : Connexion établie à localhost/boutique_online
- 2024-01-15 10:30:16 : Requête exécutée : SELECT * FROM produits WHERE prix < 100
- 2024-01-15 10:30:17 : Requête exécutée : INSERT INTO commandes VALUES (...)
- 2024-01-15 10:30:18 : Requête exécutée : UPDATE stock SET quantite = quantite - 1
- 2024-01-15 10:30:19 : Connexion fermée
```

**💻 SOLUTION :**

```php
<?php
class GestionnaireBDD {
    private $host;
    private $database;
    private $utilisateur;
    private $logs;
    private $connexionActive;
    private $nbRequetes;

    public function __construct($host, $database, $utilisateur) {
        $this->host = $host;
        $this->database = $database;
        $this->utilisateur = $utilisateur;
        $this->logs = [];
        $this->connexionActive = true;
        $this->nbRequetes = 0;

        $this->ajouterLog("Connexion établie à {$host}/{$database}");
        echo "Connexion établie à {$host}/{$database}\n";
    }

    public function executerRequete($requete) {
        if (!$this->connexionActive) {
            echo "Erreur : Connexion fermée\n";
            return false;
        }

        // Simulation d'une requête SQL
        $this->nbRequetes++;
        $this->ajouterLog("Requête exécutée : {$requete}");
        echo "Requête '{$requete}' exécutée avec succès\n";
        return true;
    }

    public function verifierConnexion() {
        $statut = $this->connexionActive ? "Oui" : "Non";
        echo "Connexion active : {$statut}\n";
        return $this->connexionActive;
    }

    public function obtenirLogs() {
        return $this->logs;
    }

    public function afficherLogs() {
        echo "=== LOGS D'ACTIVITÉ ===\n";
        foreach ($this->logs as $log) {
            echo "- {$log['timestamp']} : {$log['message']}\n";
        }
    }

    private function ajouterLog($message) {
        $this->logs[] = [
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    public function __destruct() {
        if ($this->connexionActive) {
            echo "\n=== FERMETURE DE LA CONNEXION ===\n";
            $this->connexionActive = false;
            $this->ajouterLog("Connexion fermée");

            echo "Connexion fermée. Nombre d'opérations : {$this->nbRequetes}\n";

            // Sauvegarde des logs
            $this->sauvegarderLogs();
        }
    }

    private function sauvegarderLogs() {
        echo "Logs sauvegardés : " . count($this->logs) . " entrées\n";
        // Ici, on pourrait sauvegarder dans un fichier ou une base de données
    }
}

// Test du système
echo "=== SYSTÈME DE BASE DE DONNÉES ===\n";
$bdd = new GestionnaireBDD("localhost", "boutique_online", "admin");
$bdd->verifierConnexion();
$bdd->executerRequete("SELECT * FROM produits WHERE prix < 100");
$bdd->executerRequete("INSERT INTO commandes VALUES (...)");
$bdd->executerRequete("UPDATE stock SET quantite = quantite - 1");
$bdd->afficherLogs();

// Le destructeur sera appelé automatiquement à la fin du script
?>
```

## 🎯 Améliorations apportées

### Objectif clair

- ❌ Avant : "Créez une classe Connexion"
- ✅ Après : "Système de gestion de base de données pour une application e-commerce"

### Scénario réaliste

- ❌ Avant : Exercice isolé
- ✅ Après : Contexte professionnel (boutique en ligne)

### Résultat attendu

- ❌ Avant : Pas de sortie attendue
- ✅ Après : Sortie exacte avec logs détaillés

### Fonctionnalités ajoutées

- ✅ Vérification de l'état de la connexion
- ✅ Affichage des logs d'activité
- ✅ Comptage des requêtes
- ✅ Gestion d'erreurs
- ✅ Messages informatifs

### Cas d'usage professionnel

- ✅ Gestion des connexions BDD
- ✅ Logging des activités
- ✅ Prévention des fuites mémoire
- ✅ Monitoring des performances
