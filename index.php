<?php
require 'config.php'; 

// Fonction pour récupérer les données de la table
function fetchData($pdo, $table) {
    $stmt = $pdo->prepare("SELECT * FROM " . $table);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Récupérer les données de toutes les tables
$utilisateurs = fetchData($pdo, 'utilisateurs');
$adresses = fetchData($pdo, 'adresses');
$produits = fetchData($pdo, 'produits');
$paniers = fetchData($pdo, 'paniers');
$commandes = fetchData($pdo, 'commandes');
$factures = fetchData($pdo, 'factures');
$photos = fetchData($pdo, 'photos');
$evaluations = fetchData($pdo, 'évaluations');
$paiements = fetchData($pdo, 'paiements');
$articles_panier = fetchData($pdo, 'articles_panier');
$articles_commande = fetchData($pdo, 'articles_commande');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affichage des données</title>
    <link rel="stylesheet" href="style.css"> <!-- Inclure le fichier CSS -->
</head>
<body>
    <h1>Données de la base de données</h1>

    <h2>Utilisateurs</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nom d'utilisateur</th>
            <th>Email</th>
            <th>Date de création</th>
        </tr>
        <?php foreach ($utilisateurs as $utilisateur): ?>
        <tr>
            <td><?php echo $utilisateur['utilisateur_id']; ?></td>
            <td><?php echo $utilisateur['nom_utilisateur']; ?></td>
            <td><?php echo $utilisateur['email']; ?></td>
            <td><?php echo $utilisateur['date_creation']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Produits</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Prix</th>
            <th>Stock</th>
            <th>Date de création</th>
        </tr>
        <?php foreach ($produits as $produit): ?>
        <tr>
            <td><?php echo $produit['produit_id']; ?></td>
            <td><?php echo $produit['nom']; ?></td>
            <td><?php echo $produit['description']; ?></td>
            <td><?php echo $produit['prix']; ?></td>
            <td><?php echo $produit['stock']; ?></td>
            <td><?php echo $produit['date_creation']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Ajoutez d'autres sections pour les autres tables de la même manière -->

</body>
</html>
