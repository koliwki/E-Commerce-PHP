<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

include_once 'config.php';

// Fonction pour afficher les produits
function displayProducts($conn) {
    $sql = "SELECT * FROM product";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Products</h2>";
        echo "<table border='1'>";
        echo "<tr><th>user_ID</th><th>Name</th><th>Description</th><th>Price</th><th>Actions</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['product_id']."</td>";
            echo "<td>".$row['product_name']."</td>";
            echo "<td>".$row['description']."</td>";
            echo "<td>".$row['price']."</td>";
            echo "<td><a href='edit_product.php?id=".$row['product_id']."'>Edit</a> | <a href='delete_product.php?id=".$row['product_id']."'>Delete</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No products found.";
    }
}

// Fonction pour afficher les utilisateurs
function displayUsers($conn) {
    $sql = "SELECT * FROM user";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Users</h2>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Action</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['user_id']."</td>";
            echo "<td>".$row['username']."</td>";
            echo "<td>".$row['email']."</td>";
            echo "<td>
                    <form method='post' action='".$_SERVER["PHP_SELF"]."'>
                        <input type='hidden' name='delete_user_id' value='".$row['user_id']."'>
                        <button type='submit' name='delete_user'>Delete</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No users found.";
    }
}

// Traitement de la suppression d'utilisateur
if(isset($_POST['delete_user'])) {
    $user_id = $_POST['delete_user_id'];
    $delete_sql = "DELETE FROM user WHERE user_id = $user_id";
    if($conn->query($delete_sql) === TRUE) {
        echo "<script>alert('User deleted successfully');</script>";
    } else {
        echo "<script>alert('Error deleting user: ".$conn->error."');</script>";
    }
}

?>

<!DOCTYPE html>
<link rel="stylesheet" href="Front/css/dash_board.css"/>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p><a href="login.php">Deconnexion</a></p>

    <?php
    // Afficher les produits
    displayProducts($conn);

    // Afficher les utilisateurs
    displayUsers($conn);
    ?>

    <p><a href="add_product.php">Add New Product</a></p>
    <p><a href="register.php">Add New User</a></p>
</body>
</html>
