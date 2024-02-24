<?php
session_start();
include_once 'config.php';

// Traitement du formulaire d'ajout de produit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    // Récupérer les données du formulaire
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    // Upload de l'image
    $target_dir = "uploads/"; // Dossier de destination des images
    $target_file = $target_dir . basename($_FILES["product_image"]["name"]); // Chemin complet de l'image
    $uploadOk = 1; // Variable pour vérifier si le fichier a été correctement téléchargé
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); // Extension du fichier image

    // Vérifier si $uploadOk est défini à 0 par une erreur
    if ($uploadOk == 0) {
        echo "Désolé, votre fichier n'a pas été téléchargé.";
    // Si tout est ok, télécharger le fichier et stocker le chemin d'accès dans la base de données
    } else {
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            // Insérer le nouveau produit dans la base de données avec le chemin d'accès de l'image
            $sql = "INSERT INTO product (product_name, description, price, category, product_image) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ssdss", $product_name, $description, $price, $category, $target_file);
                $stmt->execute();
                $stmt->close();
                // Rediriger vers la page d'accueil après l'ajout du produit
                header("Location: product.php");
                exit();
            } else {
                echo "Erreur lors de l'ajout du produit : " . $conn->error;
            }
        } else {
            echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
        }
    }
}

// Récupérer les produits existants depuis la base de données
$sql_products = "SELECT * FROM product";
$result_products = $conn->query($sql_products);
$products = [];
if ($result_products->num_rows > 0) {
    while ($row = $result_products->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
</head>

<body>
    <h1>Add New Product</h1>
    <form method="post" enctype="multipart/form-data">
        <label for="product_name">Product Name:</label><br>
        <input type="text" id="product_name" name="product_name" required><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required></textarea><br>
        <label for="price">Price:</label><br>
        <input type="number" id="price" name="price" step="0.01" min="0" required><br>
        <label for="category">Category:</label><br>
        <input type="text" id="category" name="category" required><br>
        <label for="product_image">Product Image:</label><br>
        <input type="file" id="product_image" name="product_image" accept="image/*" required><br>
        <input type="submit" name="add_product" value="Add Product">
    </form>

    <h1>Existing Products</h1>
    <table>
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Category</th>
            <th>Image</th>
        </tr>
        <?php foreach ($products as $product) : ?>
            <tr>
                <td><?php echo $product['product_id']; ?></td>
                <td><?php echo $product['product_name']; ?></td>
                <td><?php echo $product['description']; ?></td>
                <td><?php echo $product['price']; ?></td>
                <td><?php echo $product['category']; ?></td>
                <td><img src="<?php echo $product['product_image']; ?>" alt="<?php echo $product['product_name']; ?>" style="max-width: 100px; max-height: 100px;"></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>
