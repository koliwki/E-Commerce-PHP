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
    $product_image_link = $_POST['product_image_link']; // Nouveau champ pour le lien de l'image

    // Insérer le nouveau produit dans la base de données avec le lien de l'image
    $sql = "INSERT INTO product (product_name, description, price, category, product_image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssdss", $product_name, $description, $price, $category, $product_image_link);
        $stmt->execute();
        $stmt->close();
        // Rediriger vers la page d'accueil après l'ajout du produit
        header("Location: product.php");
        exit();
    } else {
        echo "Erreur lors de l'ajout du produit : " . $conn->error;
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
    <link rel="stylesheet" href="Front/css/product.css">

</head>

<body>
    <h1>Ajout d'un nouveau produit</h1>
    <form method="post">
        <label for="product_name">Product Name:</label><br>
        <input type="text" id="product_name" name="product_name" required><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required></textarea><br>
        <label for="price">Price:</label><br>
        <input type="number" id="price" name="price" step="0.01" min="0" required><br>
        <label for="category">Category:</label><br>
        <select id="category" name="category" required>
            <option value="sport">Sport</option>
            <option value="nourriture">Nourriture</option>
            <option value="autres">Autres</option>
        </select><br>
        <label for="product_image_link">Product Image Link:</label><br> <!-- Champ pour le lien de l'image -->
        <input type="url" id="product_image_link" name="product_image_link" required><br> <!-- Champs pour le lien de l'image -->
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
