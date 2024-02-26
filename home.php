<?php
session_start();
include_once 'config.php';

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

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

$category = isset($_GET['category']) ? $_GET['category'] : 'sport';

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
        <form method="get" action="home.php">
            <label for="category">Choose a category:</label>
            <select id="category" name="category">
                <option value="sport">Sport</option>
                <option value="nourriture">Nourriture</option>
                <option value="autres">Autres</option>
            </select>
            <input type="submit" class="filter" value="Filter">
        </form>
    </div>
    <div class="product-cards">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="product-card">';
                echo '<img src="' . $row['product_image'] . '" alt="' . $row['product_name'] . '">';
                echo '<div class="product-info">';
                echo '<a href="product_details.php?id=' . $row['product_id'] . '" class="product-link">';
                echo '<h3>' . $row['product_name'] . '</h3>';
                echo '</a>';
                echo '<span class="price">$' . $row['price'] . '</span>';
                echo '<form method="post" action="home.php">';
                echo '<input type="hidden" name="product_id" value="' . $row['product_id'] . '">';
                echo '<input type="submit" class="add-to-cart" value="Ajouter au panier" onclick="addToCart(this);">';

                echo '</form>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "Aucun produit n'a été trouvé dans cette catégorie.";
        }

        ?>
    </div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
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

            $product_id = $_POST['product_id'];

            $sql_check_cart = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
            $stmt_check_cart = $conn->prepare($sql_check_cart);
            $stmt_check_cart->bind_param("ii", $user_id, $product_id);
            $stmt_check_cart->execute();
            $result_check_cart = $stmt_check_cart->get_result();

            if ($result_check_cart->num_rows > 0) {
                $sql_update_cart = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
                $stmt_update_cart = $conn->prepare($sql_update_cart);
                $stmt_update_cart->bind_param("ii", $user_id, $product_id);
                $stmt_update_cart->execute();
                $stmt_update_cart->close();
            } else {
                $sql_insert_cart = "INSERT INTO cart (user_id, product_id, quantity, added_date) VALUES (?, ?, 1, NOW())";
                $stmt_insert_cart = $conn->prepare($sql_insert_cart);

                if ($stmt_insert_cart) {
                    $stmt_insert_cart->bind_param("ii", $user_id, $product_id);
                    $stmt_insert_cart->execute();
                    $stmt_insert_cart->close();
                    echo '<script>alert("Produit ajouté au panier !");</script>';
                } else {
                    echo "Erreur lors de l'ajout du produit au panier : " . $conn->error;
                }
            }
        } else {
            echo "Erreur lors de la récupération de l'identifiant de l'utilisateur : " . $conn->error;
        }
    } else {
        echo "Veuillez vous connecter pour ajouter des produits au panier.";
    }
}
?>


</body>

</html>
