<?php
session_start();

include_once 'config.php'; 

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Obtenez le nom d'utilisateur de l'utilisateur connecté à partir de la base de données
$sql = "SELECT username FROM user WHERE email = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($username); 
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profil</title>
</head>
<body>
    <h1>Profil de <?php echo $username; ?></h1>
    <p>Nom d'utilisateur : <?php echo $username; ?></p>
    <p>Adresse e-mail : <?php echo $email; ?></p>
</body>
</html>
