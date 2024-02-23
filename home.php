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
    <style>
        .product-cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            align-items: flex-start;
            margin-top: 20px;
            margin-bottom: 50px;
        }


        .product-card {
            margin: 10px;
            padding: 10px;
            border: 1px solid #141313;
            border-radius: 5px;
            width: 250px;
        }

        .product-card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .product-info {
            text-align: center;
        }

        .product-info h3 {
            margin-top: 100px;
            margin-bottom: 5px;
        }

        .product-info p {
            margin-bottom: 10px;
        }

        .product-info .price {
            font-weight: bold;
            color: #007bff;
        }

        .product-info button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .product-info button:hover {
            background-color: #0056b3;
        }
    </style>

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
            <li><a href="">Home</a></li>
            <li><a href="">Cart</a></li>
            <li><a href="">Profil</a></li>
            <li><a href="">Products</a></li>
            <li><a href="">Contact Us</a></li>

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
        // Parcourir les produits et générer les cartes de produits
        foreach ($products as $product) {
            echo '<div class="product-card">';
            echo '<img src="' . $product['image'] . '" alt="' . $product['name'] . '">';
            echo '<div class="product-info">';
            echo '<h3>' . $product['name'] . '</h3>';
            echo '<p>' . $product['description'] . '</p>';
            echo '<span class="price">$' . $product['price'] . '</span>';
            echo '<button>Ajouter au panier</button>';
            echo '</div>';
            echo '</div>';
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