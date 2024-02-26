<?php
session_start();
include_once 'config.php';

// Vérifiez si l'utilisateur est connecté en vérifiant la présence de son e-mail dans la session
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: login.php");
    exit(); // Assurez-vous de terminer le script après la redirection
}

// Vérifiez si l'identifiant du produit est spécifié dans l'URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Récupérer les détails du produit depuis la base de données
    $stmt_product = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
    $stmt_product->bind_param("i", $product_id);
    $stmt_product->execute();
    $result_product = $stmt_product->get_result();

    // Vérifiez si le produit existe
    if ($result_product->num_rows > 0) {
        $row_product = $result_product->fetch_assoc();
        $product_name = $row_product['product_name'];
        $description = $row_product['description'];
        $price = $row_product['price'];
        $product_image = $row_product['product_image'];

        // Enregistrer un commentaire si le formulaire est soumis
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_comment'])) {
            $email = $_SESSION['email']; // Obtenez l'e-mail de l'utilisateur à partir de la session
            $comment_content = htmlspecialchars($_POST['comment_content']); // Prévenir les attaques XSS

            // Récupérer l'ID de l'utilisateur à partir de son e-mail
            $stmt_user_id = $conn->prepare("SELECT user_id FROM user WHERE email = ?");
            $stmt_user_id->bind_param("s", $email);
            $stmt_user_id->execute();
            $stmt_user_id->bind_result($user_id);
            $stmt_user_id->fetch();
            $stmt_user_id->close();

            // Insérez le commentaire dans la base de données
            $stmt_insert_comment = $conn->prepare("INSERT INTO comments (product_id, user_id, content) VALUES (?, ?, ?)");
            $stmt_insert_comment->bind_param("iis", $product_id, $user_id, $comment_content);
            
            if ($stmt_insert_comment->execute()) {
                // Rafraîchir la page pour afficher le nouveau commentaire
                header("Location: product_details.php?id=$product_id");
                exit();
            } else {
                echo "Erreur lors de l'ajout du commentaire : " . $stmt_insert_comment->error;
            }
        }

        // Récupérer les commentaires associés au produit depuis la base de données
        $stmt_comments = $conn->prepare("SELECT c.content, u.username FROM comments c JOIN user u ON c.user_id = u.user_id WHERE c.product_id = ?");
        $stmt_comments->bind_param("i", $product_id);
        $stmt_comments->execute();
        $result_comments = $stmt_comments->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du produit</title>
    <link rel="stylesheet" href="Front/css/product_details.css">
</head>
<body>
    <div class="container">
        <div class="product-details">
            <?php
            // Récupérer les dimensions de l'image pour redimensionner si nécessaire
            list($width, $height) = getimagesize($product_image);
            $max_width = 500; 
            $new_height = ($max_width / $width) * $height;
            ?>
            <img src="<?php echo $product_image; ?>" alt="<?php echo $product_name; ?>" class="product-image" style="max-width: <?php echo $max_width; ?>px; height: <?php echo $new_height; ?>px;">
            <div class="product-info">
                <h1><?php echo $product_name; ?></h1>
                <p><?php echo $description; ?></p>
                <p class="price">$<?php echo $price; ?></p>
                <h2>Commentaires :</h2>
                <?php
                // Afficher les commentaires existants
                while ($row_comment = $result_comments->fetch_assoc()) {
                    echo '<div class="comment">';
                    echo '<p><strong>' . $row_comment['username'] . '</strong></p>';
                    echo '<p>' . $row_comment['content'] . '</p>';
                    echo '</div>';
                }
                ?>
                <!-- Formulaire pour ajouter un commentaire -->
                <form method="post" action="">
                    <textarea name="comment_content" placeholder="Ajouter un commentaire" required></textarea>
                    <input type="submit" name="submit_comment" value="Commenter">
                </form>
                <!-- Lien pour retourner à la page d'accueil -->
                <a href="home.php">Retourner à la page d'accueil</a>
            </div>
        </div>
    </div>
</body>
</html>

<?php
    } else {
        echo "Produit non trouvé.";
    }
} else {
    echo "Identifiant du produit non spécifié.";
}
?>
