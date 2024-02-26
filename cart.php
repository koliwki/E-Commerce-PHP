<?php
session_start();
include_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];

        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];
            $sql_user_id = "SELECT user_id FROM user WHERE email = ?";
            $stmt_user_id = $conn->prepare($sql_user_id);

            if ($stmt_user_id) {
                $stmt_user_id->bind_param("s", $email);
                $stmt_user_id->execute();
                $stmt_user_id->bind_result($user_id);
                $stmt_user_id->fetch();
                $stmt_user_id->close();

                $sql_remove_product = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
                $stmt_remove_product = $conn->prepare($sql_remove_product);

                if ($stmt_remove_product) {
                    $stmt_remove_product->bind_param("ii", $user_id, $product_id);
                    $stmt_remove_product->execute();
                    $stmt_remove_product->close();
                } else {
                    echo "Erreur de préparation de la requête SQL.";
                }
            } else {
                echo "Erreur de préparation de la requête SQL.";
            }
        } else {
            echo "Veuillez vous connecter pour supprimer des produits du panier.";
        }
    } else {
        echo "Paramètre product_id manquant.";
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="Front/css/cart.css"/>
</head>
<body>
    <div class="container">
        <h1 class="title">Panier d'achat</h1>
        <div class="cart-items">
            <?php 
            if (!empty($_SESSION['email'])) {
                $email = $_SESSION['email'];
                $sql_user_id = "SELECT user_id FROM user WHERE email = ?";
                $stmt_user_id = $conn->prepare($sql_user_id);

                if ($stmt_user_id) {
                    $stmt_user_id->bind_param("s", $email);
                    $stmt_user_id->execute();
                    $stmt_user_id->bind_result($user_id);
                    $stmt_user_id->fetch();
                    $stmt_user_id->close();

                    $sql_cart_products = "SELECT product.product_id, product.product_name, product.product_image, product.description, product.price FROM cart JOIN product ON cart.product_id = product.product_id WHERE cart.user_id = ?";
                    $stmt_cart_products = $conn->prepare($sql_cart_products);
                    if ($stmt_cart_products) {
                        $stmt_cart_products->bind_param("i", $user_id);
                        $stmt_cart_products->execute();
                        $result = $stmt_cart_products->get_result();

                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="cart-item">';
                            echo '<div class="item-details">';
                            echo '<img src="' . $row['product_image'] . '" alt="' . $row['product_name'] . '" style="max-width: 300px; max-height: 300px;">';
                            echo '<h3>' . $row['product_name'] . '</h3>';
                            echo '<p>' . $row['description'] . '</p>';
                            echo '</div>';
                            echo '<div class="item-actions">';
                            echo '<p>Prix : $' . $row['price'] . '</p>';
                            echo '<form method="post">';
                            echo '<input type="hidden" name="action" value="remove_product">';
                            echo '<input type="hidden" name="product_id" value="' . $row['product_id'] . '">';
                            echo '<button class="btn" name="remove_product">Supprimer</button>';
                            echo '</form>';
                            echo '</div>';
                            echo '</div>';
                        }
                        $stmt_cart_products->close();
                    } else {
                        echo "Erreur de préparation de la requête SQL pour récupérer les produits du panier.";
                    }
                } else {
                    echo "Erreur de préparation de la requête SQL pour récupérer l'identifiant de l'utilisateur.";
                }
            } else {
                echo "Veuillez vous connecter pour afficher les produits dans le panier.";
            }
            
            ?>
        </div>
        <div class="cart-total">
            <?php
            $total = 0;

            $sql_cart_total = "SELECT product.price FROM cart JOIN product ON cart.product_id = product.product_id WHERE cart.user_id = ?";
            $stmt_cart_total = $conn->prepare($sql_cart_total);
            if ($stmt_cart_total) {
                $stmt_cart_total->bind_param("i", $user_id);
                $stmt_cart_total->execute();
                $result = $stmt_cart_total->get_result();

                while ($row = $result->fetch_assoc()) {
                    $total += $row['price'];
                }

                echo '<p>Total du panier : $' . number_format($total, 2) . '</p>';
                $stmt_cart_total->close();
            } else {
                echo "Erreur de préparation de la requête SQL pour calculer le total du panier.";
            }
            ?>
        </div>

        <div class="buttons">
            <a href="home.php" class="btn">Continuer vos achats</a>
            <a href="paiment.php" class="btn">Procéder au paiement</a>
        </div>
    </div>
</body>
</html>
