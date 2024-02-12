<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "e-commerce";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $email = $_POST['signup-email'];
    $password = $_POST['signup-password'];
    // Vous pouvez également récupérer d'autres champs si nécessaire

    // Insérer les données dans la base de données
    $sql = "INSERT INTO user (email, password) VALUES ('$email', '$password')"; // Adapté à votre schéma de base de données
    if ($conn->query($sql) === TRUE) {
        echo "Inscription réussie!";
    } else {
        echo "Erreur lors de l'inscription: " . $conn->error;
    }
}

$conn->close();
?>
<!-- Formulaire d'inscription -->
<form class="form form-signup" method="post" action="register.php">
  <fieldset>
    <legend>Please, enter your email, password and password confirmation for sign up.</legend>
    <div class="input-block">
      <label>Email</label>
      <input name="signup-email" id="signup-email" type="email" required>
    </div>
    <div class="input-block">
      <label>Password</label>
      <input name="signup-password" id="signup-password" type="password" required>
    </div>
    <div class="input-block">
      <label for="signup-password-confirm">Confirm password</label>
      <input name="signup-password-confirm" id="signup-password-confirm" type="password" required>
    </div>
  </fieldset>
  <button type="submit" class="btn">
    <span>Continue</span>
  </button>
</form>
