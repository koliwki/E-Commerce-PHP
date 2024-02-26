<?php
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

include_once 'config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid product ID.";
    exit();
}

$product_id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = htmlspecialchars($_POST['product_name']);
    $description = htmlspecialchars($_POST['description']);
    $price = htmlspecialchars($_POST['price']);

    if (empty($product_name) || empty($description) || empty($price)) {
        echo "<script>alert('Please fill in all fields.');</script>";
    } else {
        $sql = "UPDATE product SET product_name = ?, description = ?, price = ? WHERE product_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssdi", $product_name, $description, $price, $product_id);
            if ($stmt->execute()) {
                echo "<script>alert('Product updated successfully');</script>";
            } else {
                echo "<script>alert('Error updating product: " . $conn->error . "');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Error preparing SQL statement: " . $conn->error . "');</script>";
        }
    }
}

$sql = "SELECT * FROM product WHERE product_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $product_name = $row['product_name'];
        $description = $row['description'];
        $price = $row['price'];
    } else {
        echo "Product not found.";
        exit();
    }

    $stmt->close();
} else {
    echo "Error preparing SQL statement: " . $conn->error;
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Product</title>
</head>
<body>
    <h1>Edit Product</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="product_name">Product Name:</label><br>
        <input type="text" id="product_name" name="product_name" value="<?php echo $product_name; ?>"><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description"><?php echo $description; ?></textarea><br><br>

        <label for="price">Price:</label><br>
        <input type="text" id="price" name="price" value="<?php echo $price; ?>"><br><br>

        <input type="submit" value="Update Product">
    </form>
</body>
</html>
