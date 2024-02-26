<?php
session_start();

include_once 'config.php'; 

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

$sql = "SELECT username, phone_number, firstname, lastname, country FROM user WHERE email = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($username, $phone_number, $firstname, $lastname, $country); 
    $stmt->fetch();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['phone_number']) && !empty(trim($_POST['phone_number']))) {
        $new_phone_number = str_replace(' ', '', $_POST['phone_number']);

        $sql = "UPDATE user SET phone_number = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $new_phone_number, $email);
            $stmt->execute();
            $stmt->close();
        }
    }
    if (isset($_POST['firstname'])) {
        $new_firstname = $_POST['firstname'];

        $sql = "UPDATE user SET firstname = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $new_firstname, $email);
            $stmt->execute();
            $stmt->close();
        }
    }
    if (isset($_POST['lastname'])) {
        $new_lastname = $_POST['lastname'];

        $sql = "UPDATE user SET lastname = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $new_lastname, $email);
            $stmt->execute();
            $stmt->close();
        }
    }
    if (isset($_POST['country'])) {
        $new_country = $_POST['country'];

        $sql = "UPDATE user SET country = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $new_country, $email);
            $stmt->execute();
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="Front/css/profil.css">
</head>
<body>
    <div class="page-content page-container" id="page-content">
        <div class="padding">
            <div class="row container d-flex justify-content-center">
                <div class="col-xl-6 col-md-12">
                    <div class="card user-card-full">
                        <div class="row m-l-0 m-r-0">
                            <div class="col-sm-4 bg-c-lite-green user-profile">
                                <div class="card-block text-center text-white">
                                    <div class="m-b-25">
                                        <img src="https://img.icons8.com/bubbles/100/000000/user.png" class="img-radius" alt="User-Profile-Image">
                                    </div>
                                    <h6 class="f-w-600"><?php echo $username; ?></h6>
                                    <i class=" mdi mdi-square-edit-outline feather icon-edit m-t-10 f-16"></i>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="card-block">
                                    <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Information</h6>
                                    <form method="post">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="m-b-10 f-w-600">Votre email :</p>
                                                <h6 class="text-muted f-w-400"><?php echo $email; ?></h6>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="m-b-10 f-w-600">Phone number</p>
                                                <input type="text" name="phone_number" value="<?php echo $phone_number; ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="m-b-10 f-w-600">Firstname</p>
                                                <input type="text" name="firstname" value="<?php echo $firstname; ?>">
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="m-b-10 f-w-600">Lastname</p>
                                                <input type="text" name="lastname" value="<?php echo $lastname; ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="m-b-10 f-w-600">Country</p>
                                                <input type="text" name="country" value="<?php echo $country; ?>">
                                            </div>
                                        </div>
                                        <div class="button-container">
                                            <button type="submit" class="submit-button">Valider</button>
                                        </div>                                 
                                      </form>
                                    <div class="button-container">
                                        <a href="login.php" class="logout-button">Déconnexion</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
