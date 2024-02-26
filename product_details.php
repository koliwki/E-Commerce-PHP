<?php
session_start();
include_once 'config.php';

// Récup l'identifiant du produit a partir de l'URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Requête SQL pour recup les détails du produit spécifique
    $sql = "SELECT * FROM product WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $product_name = $row['product_name'];
        $description = $row['description'];
        $price = $row['price'];
        $product_image = $row['product_image'];
        
        //methodes daffichages du produit 
        echo '<!DOCTYPE html>';
        echo '<html lang="en">';
        echo '<head>';
        echo '<meta charset="UTF-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta http-equiv="X-UA-Compatible" content="ie=edge">';
        echo '<title>Détails du produit</title>';
        echo '<link rel="stylesheet" href="Front/css/product_details.css">';
        echo '</head>';
        echo '<body>';
        echo '<div class="container">';
        echo '<div class="product-details">';
        echo '<style>';
        echo 'img { max-width: 100%; height: auto; }';
        echo '</style>';
        echo '<img src="' . $product_image . '" alt="' . $product_name . '">';
        echo '<div class="product-info">';
        echo '<h1>' . $product_name . '</h1>';
        echo '<p>' . $description . '</p>';
        echo '<p class="price">$' . $price . '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</body>';
        echo '</html>';
    } else {
        echo "Produit non trouvé.";
    }
} else {
    echo "Identifiant du produit non spécifié.";
}
?>
