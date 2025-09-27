# 🎯 Exemple d'amélioration des exercices

## ❌ AVANT : Exercice sans objectif clair

### Exercice 3 : Classe Compte avec sécurité

Créez une classe `Compte` avec :

- Propriétés privées : `numero`, `solde`, `codeSecret`
- Méthodes sécurisées : `deposer()`, `retirer()`, `consulterSolde()`
- Validation des codes secrets

## ✅ APRÈS : Exercice avec objectif concret

### Exercice 3 : Système bancaire sécurisé

**🎯 OBJECTIF :** Créer un système bancaire sécurisé pour une banque en ligne avec protection des données sensibles.

**📋 SCÉNARIO :** Vous développez une application bancaire mobile. Vous devez :

- Protéger les données sensibles (solde, code secret)
- Permettre les opérations bancaires sécurisées
- Empêcher l'accès non autorisé aux comptes
- Gérer les erreurs de sécurité
- Afficher un historique des transactions

**📝 SPÉCIFICATIONS :**

- Classe `CompteBancaire` avec propriétés privées : `numero`, `solde`, `codeSecret`, `historique`
- Méthodes sécurisées : `deposer()`, `retirer()`, `consulterSolde()`, `afficherHistorique()`
- Validation des codes secrets et montants

**✅ RÉSULTAT ATTENDU :**

```
=== BANQUE SÉCURISÉE ===
Compte 123456 créé avec succès
Dépôt de 500€ effectué. Nouveau solde : 1500€
Code secret incorrect - Accès refusé
Retrait de 200€ effectué. Nouveau solde : 1300€
Solde actuel : 1300€
=== HISTORIQUE DES TRANSACTIONS ===
- Dépôt: +500€ (Solde: 1500€)
- Retrait: -200€ (Solde: 1300€)
```

**💻 SOLUTION :**

```php
<?php
class CompteBancaire {
    private $numero;
    private $solde;
    private $codeSecret;
    private $historique = [];

    public function __construct($numero, $codeSecret, $soldeInitial = 0) {
        $this->numero = $numero;
        $this->codeSecret = $codeSecret;
        $this->solde = $soldeInitial;
        echo "Compte {$numero} créé avec succès\n";
    }

    public function getNumero() {
        return $this->numero;
    }

    private function verifierCode($code) {
        return $code === $this->codeSecret;
    }

    public function deposer($montant, $code) {
        if (!$this->verifierCode($code)) {
            echo "Code secret incorrect - Accès refusé\n";
            return false;
        }

        if ($montant > 0) {
            $this->solde += $montant;
            $this->ajouterHistorique("Dépôt", $montant);
            echo "Dépôt de {$montant}€ effectué. Nouveau solde : {$this->solde}€\n";
            return true;
        }
        echo "Montant invalide\n";
        return false;
    }

    public function retirer($montant, $code) {
        if (!$this->verifierCode($code)) {
            echo "Code secret incorrect - Accès refusé\n";
            return false;
        }

        if ($montant > 0 && $montant <= $this->solde) {
            $this->solde -= $montant;
            $this->ajouterHistorique("Retrait", -$montant);
            echo "Retrait de {$montant}€ effectué. Nouveau solde : {$this->solde}€\n";
            return true;
        }
        echo "Montant invalide ou solde insuffisant\n";
        return false;
    }

    public function consulterSolde($code) {
        if (!$this->verifierCode($code)) {
            echo "Code secret incorrect - Accès refusé\n";
            return false;
        }
        echo "Solde actuel : {$this->solde}€\n";
        return $this->solde;
    }

    private function ajouterHistorique($operation, $montant) {
        $this->historique[] = [
            'operation' => $operation,
            'montant' => $montant,
            'solde' => $this->solde,
            'date' => date('Y-m-d H:i:s')
        ];
    }

    public function afficherHistorique($code) {
        if (!$this->verifierCode($code)) {
            echo "Code secret incorrect - Accès refusé\n";
            return false;
        }

        echo "=== HISTORIQUE DES TRANSACTIONS ===\n";
        foreach ($this->historique as $transaction) {
            $signe = $transaction['montant'] > 0 ? '+' : '';
            echo "- {$transaction['operation']}: {$signe}{$transaction['montant']}€ (Solde: {$transaction['solde']}€)\n";
        }
    }
}

// Test du système
echo "=== BANQUE SÉCURISÉE ===\n";
$compte = new CompteBancaire("123456", 1234, 1000);
$compte->deposer(500, 1234);  // Code correct
$compte->retirer(200, 5678); // Code incorrect
$compte->retirer(200, 1234); // Code correct
$compte->consulterSolde(1234);
$compte->afficherHistorique(1234);
?>
```

## 🎯 Différences clés

| **Avant**                  | **Après**                              |
| -------------------------- | -------------------------------------- |
| ❌ Pas d'objectif clair    | ✅ Objectif concret : système bancaire |
| ❌ Pas de scénario         | ✅ Scénario réaliste : banque en ligne |
| ❌ Pas de résultat attendu | ✅ Résultat attendu détaillé           |
| ❌ Exercice isolé          | ✅ Intégré dans un système complet     |
| ❌ Pas de contexte         | ✅ Contexte professionnel              |
