<?php
session_start(); // Démarre la session PHP

$servername = "localhost";
$username = "root";
$password = "";
$database = "e-commerce";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email']; 
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");

    // Vérifier si la préparation de la requête a réussi
    if ($stmt) {
        $stmt->bind_param("s", $email); 
        $stmt->execute(); // Exécution de la requête
        $result = $stmt->get_result(); // Obtention du résultat
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if ($user['password'] == $password) {
                header("Location: home.php");
                exit();
            } else {
                echo "Mot de passe incorrect!";
            }
        } else {
            echo "Cette adresse mail n'existe pas, veuillez vous enregistrer dans SIGN UP";
        }
        
        $stmt->close(); // Fermeture de la requête préparée
    } else {
        // Gestion de l'erreur si la préparation de la requête a échoué
        echo "Erreur lors de la préparation de la requête : " . $conn->error;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login/Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <link rel="stylesheet" href="Front/css/style-Login_Register.css">
</head>
<body>
<section class="forms-section">
  <div class="forms">
    <div class="form-wrapper is-active">
      <button type="button" class="switcher switcher-login">
        Login
        <span class="underline"></span>
      </button>
      <form class="form form-login" method="POST" action="">
        <fieldset>
          <legend>Please, enter your email and password for login.</legend>
          <div class="input-block">
            <label>Email</label> 
            <input name="email" id="login-email" type="email" required> <!-- Changement ici pour id="login-email" -->
          </div>
          <div class="input-block">
            <label>Password</label>
            <input name="password" id="login-password" type="password" required>
          </div>
        </fieldset>
        <button type="submit" class="btn">
          <span>Login</span>
        </button>
      </form>
    </div>
    <div class="form-wrapper">
      <button type="button" class="switcher switcher-signup" onclick="redirectToRegister()">
        Sign Up
        <span class="underline"></span>
      </button>
      <form class="form form-signup">
        <fieldset>
          <legend>Please, enter your email, password and password confirmation for sign up.</legend>
          <div class="input-block">
            <label>E-mail</label>
            <input id="signup-email" type="email" required>
          </div>
          <div class="input-block">
            <label>Password</label>
            <input id="signup-password" type="password" required>
          </div>
          <div class="input-block">
            <label for="signup-password-confirm">Confirm password</label>
            <input id="signup-password-confirm" type="password" required>
          </div>
        </fieldset>
        <button type="submit" class="btn">
          <span>Continue</span>
        </button>
      </form>
    </div>
  </div>
</section>

<script>
  function redirectToRegister() {
    window.location.href = "register.php";
  }
</script>

</body>
</html>
