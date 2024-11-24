<?php

define('DB_HOST', 'localhost'); // ou l'adresse IP de votre serveur
define('DB_NAME', 'e_commerce'); // nom de votre base de données
define('DB_USER', 'votre_utilisateur'); // utilisateur de la base de données
define('DB_PASS', 'votre_mot_de_passe'); // mot de passe de la base de données

require 'vendor/autoload.php';
require 'config.php';

$faker = Faker\Factory::create();
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);

for ($i = 0; $i < 100; $i++) {
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom_utilisateur, mot_de_passe, email) VALUES (?, ?, ?)");
    $stmt->execute([$faker->userName, password_hash($faker->password, PASSWORD_BCRYPT), $faker->email]);
}

for ($i = 0; $i < 50; $i++) {
    $stmt = $pdo->prepare("INSERT INTO produits (nom, description, prix, stock) VALUES (?, ?, ?, ?)");
    $stmt->execute([$faker->word, $faker->text(200), $faker->randomFloat(2, 5, 100), $faker->numberBetween(1, 100)]);
}


$users = $pdo->query("SELECT utilisateur_id FROM utilisateurs")->fetchAll(PDO::FETCH_COLUMN);
foreach ($users as $user_id) {
    for ($i = 0; $i < rand(1, 3); $i++) { // Chaque utilisateur peut avoir 1 à 3 adresses
        $stmt = $pdo->prepare("INSERT INTO adresses (utilisateur_id, rue, ville, état, code_postal, pays) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $faker->streetAddress, $faker->city, $faker->state, $faker->postcode, $faker->country]);
    }
}

foreach ($users as $user_id) {
    $stmt = $pdo->prepare("INSERT INTO paniers (utilisateur_id) VALUES (?)");
    $stmt->execute([$user_id]);
}

$produits = $pdo->query("SELECT produit_id FROM produits")->fetchAll(PDO::FETCH_COLUMN);
foreach ($users as $user_id) {
    $panier_id = $pdo->query("SELECT panier_id FROM paniers WHERE utilisateur_id = $user_id")->fetchColumn();
    for ($i = 0; $i < rand(1, 5); $i++) { // Chaque panier peut avoir 1 à 5 articles
        $produit_id = $produits[array_rand($produits)];
        $quantite = rand(1, 3); // Quantité aléatoire
        $stmt = $pdo->prepare("INSERT INTO articles_panier (panier_id, produit_id, quantite) VALUES (?, ?, ?)");
        $stmt->execute([$panier_id, $produit_id, $quantite]);
    }
}

foreach ($users as $user_id) {
    for ($i = 0; $i < rand(1, 5); $i++) { // Chaque utilisateur peut passer 1 à 5 commandes
        $stmt = $pdo->prepare("INSERT INTO commandes (utilisateur_id) VALUES (?)");
        $stmt->execute([$user_id]);
        $commande_id = $pdo->lastInsertId();

        foreach (array_rand(array_flip($produits), rand(1, 3)) as $produit_id) {
            $quantite = rand(1, 3);
            $prix = $faker->randomFloat(2, 5, 100); // Prix au moment de l'achat
            $stmt = $pdo->prepare("INSERT INTO articles_commande (commande_id, produit_id, quantite, prix) VALUES (?, ?, ?, ?)");
            $stmt->execute([$commande_id, $produit_id, $quantite, $prix]);
        }
    }
}

foreach ($users as $user_id) {
    foreach ($produits as $produit_id) {
        if (rand(0, 1)) { // 50% de chance d'évaluer un produit
            $stmt = $pdo->prepare("INSERT INTO évaluations (utilisateur_id, produit_id, note, commentaire) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $produit_id, $faker->numberBetween(1, 5), $faker->sentence(10)]);
        }
    }
}

foreach ($users as $user_id) {
    // Choisir une méthode de paiement aléatoire
    $methode_paiement = $faker->randomElement(['carte_de_crédit', 'carte_de-débite', 'paypal', 'virement_bancaire', 'portefeuille_numérique', 'chèque', 'espèce', 'ticket_resto']);

    // Pour les cartes, générer des données spécifiques
    if ($methode_paiement === 'carte_de_crédit' || $methode_paiement === 'carte_de_débit') {
        $numéro_carte = $faker->creditCardNumber;
        $date_expiration = $faker->dateTimeBetween('now', '+3 years')->format('Y-m-d'); // Date d'expiration
        $cvv = $faker->numberBetween(100, 999); // Code CVV
        $stmt = $pdo->prepare("INSERT INTO paiements (utilisateur_id, méthode_paiement, numéro_carte, date_expiration, cvv) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $methode_paiement, $numéro_carte, $date_expiration, $cvv]);
    } elseif ($methode_paiement === 'espèce' || $methode_paiement === 'ticket_resto') {
        $stmt = $pdo->prepare("INSERT INTO paiements (utilisateur_id, méthode_paiement) VALUES (?, ?)");
        $stmt->execute([$user_id, $methode_paiement]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO paiements (utilisateur_id, méthode_paiement, iban) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $methode_paiement, $faker->iban]);
    }
}

echo "Base de données peuplée avec des données fictives.";

