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
//changer ici pour l'instant a enlever pour le prix via la bdd
$products = array(
    array("name" => "Product 1", "description" => "Description du produit 1", "price" => 10.99, "image" => "path/to/product1/image.jpg"),
    array("name" => "Product 2", "description" => "Description du produit 2", "price" => 19.99, "image" => "path/to/product2/image.jpg"),
    // Ajoutez plus de produits ici si nécessaire
);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="Front/css/home.css" />

</head>

<body>
    <nav>
        <div class="logo" style="display: flex;align-items: center;">
            <span
                style="color:#01939c; font-size:26px; font-weight:bold; letter-spacing: 1px;margin-left: 20px;">Shop</span>
        </div>
        <div class="hamburger">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
        </div>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="">Profil</a></li>
            <li><a href="product.php">Products</a></li>
            <li><a href="contact.php">Contact Us</a></li>

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
        <?php
        // // Parcourir les produits et générer les cartes de produits
        // foreach ($products as $product) {
        //     echo '<div class="product-card">';
        //     echo '<img src="' . $product['image'] . '" alt="' . $product['name'] . '">';
        //     echo '<div class="product-info">';
        //     echo '<h3>' . $product['name'] . '</h3>';
        //     echo '<p>' . $product['description'] . '</p>';
        //     echo '<span class="price">$' . $product['price'] . '</span>';
        //     echo '<button>Ajouter au panier</button>';
        //     echo '</div>';
        //     echo '</div>';
        // }

        $sql = "SELECT * FROM product";
        $result = $conn->query($sql);

        // Vérifier si des produits ont été trouvés
        if ($result->num_rows > 0) {
            // Afficher le début de la section HTML pour les produits
            echo '<div class="product-cards">';
    
            // Parcourir les résultats et afficher chaque produit
            while($row = $result->fetch_assoc()) {
                echo '<div class="product-card">';
                echo '<img src="' . $row['product_image'] . '" alt="' . $row['product_name'] . '">';
                echo '<div class="product-info">';
                echo '<h3>' . $row['product_name'] . '</h3>';
                echo '<p>' . $row['description'] . '</p>';
                echo '<span class="price">$' . $row['price'] . '</span>';
                echo '<button>Ajouter au panier</button>';
                echo '</div>';
                echo '</div>';
            }
            // Afficher la fin de la section HTML pour les produits
            echo '</div>';
        } else {
            // Afficher un message si aucun produit n'est trouvé
            echo "Aucun produit n'a été trouvé.";
        }

        ?>
    </div>
    

    <script>
        const hamburger = document.querySelector(".hamburger");
        const navLinks = document.querySelector(".nav-links");
        const links = document.querySelectorAll(".nav-links li");

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle("open");
            links.forEach(link => {
                link.classList.toggle("fade");
            });
            hamburger.classList.toggle("toggle");
        });
    </script>
</body>

</html>