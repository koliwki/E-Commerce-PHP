<?php
session_start();
include_once 'config.php';

if (isset($_SESSION['email'])) {
    // Récupérer l'e-mail associé à l'utilisateur connecté
    $email = $_SESSION['email'];

    // Préparer la requête SQL pour récupérer le nom d'utilisateur associé à l'adresse e-mail
    $sql = "SELECT username FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($username);
        $stmt->fetch();
        $stmt->close();
    }
}

// Récupérer la catégorie sélectionnée (par défaut, 'sport' si aucune catégorie n'est sélectionnée)
$category = isset($_GET['category']) ? $_GET['category'] : 'sport';

// Récupérer les produits en fonction de la catégorie sélectionnée
$sql = "SELECT * FROM product WHERE category = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="Front/css/home.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <nav>
        <div class="logo" style="display: flex;align-items: center;">
            <span style="color:#01939c; font-size:26px; font-weight:bold; letter-spacing: 1px;margin-left: 20px;">Shop</span>
        </div>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="cart.php">Cart</a></li>
            <!-- <li><a href="">Profil</a></li> -->
            <li><a href="product.php">Products</a></li>
            <!-- <li><a href="contact.php">Contact Us</a></li> -->

            <?php
            if (isset($username)) {
                echo '<li><a class="username" href="profil.php?username=' . $username . '">' . $username . '</a></li>';
            } else {
                echo '<li><a class="login-button" href="login.php">Login</a></li>';
            }
            ?>

        </ul>
    </nav>
    <div class="product-cards">
        <!-- Ajoutez ce formulaire pour permettre aux utilisateurs de choisir la catégorie -->
        <form method="get" action="home.php">
            <label for="category">Choose a category:</label>
            <select id="category" name="category">
                <option value="sport">Sport</option>
                <option value="nourriture">Nourriture</option>
                <option value="autres">Autres</option>
            </select>
            <input type="submit" value="Filter">
        </form>

        <?php
        // Afficher les produits correspondants à la catégorie sélectionnée
        if ($result->num_rows > 0) {
            echo '<div class="product-cards">';
            while ($row = $result->fetch_assoc()) {
                // Afficher chaque produit
                echo '<div class="product-card">';
                echo '<img src="' . $row['product_image'] . '" alt="' . $row['product_name'] . '">';
                echo '<div class="product-info">';
                echo '<h3>' . $row['product_name'] . '</h3>';
                echo '<p>' . $row['description'] . '</p>';
                echo '<span class="price">$' . $row['price'] . '</span>';
                echo '<button class="add-to-cart" data-product-id="' . $row['product_id'] . '">Ajouter au panier</button>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo "Aucun produit n'a été trouvé dans cette catégorie.";
        }

        ?>
    </div>

    <script>
    $(document).ready(function() {
        // Lorsque le bouton "Ajouter au panier" est cliqué
        $('.add-to-cart').click(function() {
            // Récupérer l'identifiant du produit à partir de l'attribut de données
            var productId = $(this).data('product-id');
            // Envoyer une requête AJAX pour ajouter le produit au panier
            $.ajax({
                url: 'cart.php',
                type: 'post',
                data: {
                    product_id: productId
                },
                success: function(response) {
                    // Afficher une notification pour confirmer que le produit a été ajouté au panier
                    alert('produit ajouté au panier !');
                }
            });
        });
    });
</script>

</body>

</html>
