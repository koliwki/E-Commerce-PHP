<?php
session_start();

include_once 'config.php';

if (isset($_SESSION['email'])) {
    //variable permettant de recuperer le mail en lien avec le user
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="Front/css/home.css"/>
</head>
<body>
    <nav>
        <div class="logo" style="display: flex;align-items: center;">
            <span style="color:#01939c; font-size:26px; font-weight:bold; letter-spacing: 1px;margin-left: 20px;">Shop</span>
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
                echo '<li><a class="username" href="profil.php">'.$username.'</a></li>';
            } else {
                echo '<li><a class="login-button" href="login.php">Login</a></li>';
            }
            ?>

        </ul>
    </nav>
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
