<?php


require 'vendor/autoload.php';
require 'config.php';
require 'database.php';

use Faker\Factory;

// Connexion à la base de données
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
$faker = Factory::create();

// Insérer des utilisateurs
for ($i = 0; $i < 10; $i++) {
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom_utilisateur, mot_de_passe, email) VALUES (?, ?, ?)");
    $stmt->execute([$faker->userName, password_hash('password', PASSWORD_DEFAULT), $faker->email]);
}

// Insérer des adresses
for ($i = 1; $i <= 10; $i++) {
    $stmt = $pdo->prepare("INSERT INTO adresses (utilisateur_id, rue, ville, état, code_postal, pays) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$i, $faker->streetAddress, $faker->city, $faker->state, $faker->postcode, $faker->country]);
}

// Insérer des produits
for ($i = 0; $i < 15; $i++) {
    $stmt = $pdo->prepare("INSERT INTO produits (nom, description, prix, stock) VALUES (?, ?, ?, ?)");
    $stmt->execute([$faker->word, $faker->text(200), $faker->randomFloat(2, 5, 100), $faker->numberBetween(1, 50)]);
}

// Insérer des paniers
for ($i = 1; $i <= 10; $i++) {
    $stmt = $pdo->prepare("INSERT INTO paniers (utilisateur_id) VALUES (?)");
    $stmt->execute([$i]);
}

// Insérer des commandes
for ($i = 1; $i <= 10; $i++) {
    $stmt = $pdo->prepare("INSERT INTO commandes (utilisateur_id, statut) VALUES (?, ?)");
    $stmt->execute([$i, $faker->randomElement(['en_attente', 'expédié', 'livré', 'annulé'])]);
}

// Insérer des factures
for ($i = 1; $i <= 10; $i++) {
    $stmt = $pdo->prepare("INSERT INTO factures (commande_id, total) VALUES (?, ?)");
    $stmt->execute([$i, $faker->randomFloat(2, 10, 500)]);
}

// Insérer des photos
for ($i = 1; $i <= 10; $i++) {
    $stmt = $pdo->prepare("INSERT INTO photos (utilisateur_id, produit_id, url_photo) VALUES (?, ?, ?)");
    $stmt->execute([$faker->numberBetween(1, 10), $faker->numberBetween(1, 15), $faker->imageUrl()]);
}

// Insérer des évaluations
for ($i = 1; $i <= 10; $i++) {
    $stmt = $pdo->prepare("INSERT INTO évaluations (utilisateur_id, produit_id, note, commentaire) VALUES (?, ?, ?, ?)");
    $stmt->execute([$faker->numberBetween(1, 10), $faker->numberBetween(1, 15), $faker->numberBetween(1, 5), $faker->sentence()]);
}

// Insérer des paiements
for ($i = 1; $i <= 10; $i++) {
    $stmt = $pdo->prepare("INSERT INTO paiements (utilisateur_id, méthode_paiement, numéro_carte, date_expiration, cvv) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$i, $faker->randomElement(['carte_de_crédit', 'carte_de_débit', 'paypal']), $faker->creditCardNumber, $faker->creditCardExpirationDateString, $faker->randomNumber(3)]);
}

// Insérer des articles dans le panier
for ($i = 1; $i <= 10; $i++) {
    $stmt = $pdo->prepare("INSERT INTO articles_panier (panier_id, produit_id, quantite) VALUES (?, ?, ?)");
    $stmt->execute([$i, $faker->numberBetween(1, 15), $faker->numberBetween(1, 5)]);
}

// Insérer des articles dans les commandes
for ($i = 1; $i <= 10; $i++) {
    $stmt = $pdo->prepare("INSERT INTO articles_commande (commande_id, produit_id, quantite, prix) VALUES (?, ?, ?, ?)");
    $stmt->execute([$i, $faker->numberBetween(1, 15), $faker->numberBetween(1, 5), $faker->randomFloat(2, 5, 100)]);
}

echo "Données fictives insérées dans toutes les tables.\n";
