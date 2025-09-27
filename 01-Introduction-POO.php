<?php

// exe1
class Personne
{
    public ?string $nom;
    public ?int $age;

    public function __construct($nom, $age)
    {
        $this->nom = $nom;
        $this->age = $age;
    }

    public function sePresenter()
    {
        echo "Bonjour, je suis {$this->nom} et j'ai {$this->age} ans.";
    }
    public function feterAnniversaire()
    {
        $this->age++;
        echo "Joyeux anniversaire ! J'ai maintenant {$this->age} ans.";
    }
}

$personne = new Personne("Marie", 25);
$personne->sePresenter();
$personne->feterAnniversaire();


// exe2


class Ordinateur {
    private $produit_nom;
    private $produit_prix;
    private $produit_stock;

    public function __construct($produit_nom, $produit_prix, $produit_stock){
        $this->produit_nom = $produit_nom;
        $this->produit_prix = $produit_prix;
        $this->produit_stock = $produit_stock;
    }

    public function afficher_produit(){
        echo "produit : $this->produit_nom. Prix : $this->produit_prix. Stock restant : $this->produit_stock";
    }

    public function reduire_stock(){
        return $this->produit_stock - 1;
    }
}