<?php
session_start();

// Vérifier si le panier existe dans la session, sinon le créer
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Ajouter un article au panier s'il est passé en paramètre d'URL
if (isset($_GET['add_to_cart'])) {
    $item_id = $_GET['add_to_cart'];
    $_SESSION['cart'][] = $item_id;
}

// Supprimer un article du panier s'il est passé en paramètre d'URL
if (isset($_GET['remove_from_cart'])) {
    $item_id = $_GET['remove_from_cart'];
    $index = array_search($item_id, $_SESSION['cart']);
    if ($index !== false) {
        unset($_SESSION['cart'][$index]);
    }
}
?>
<link rel="stylesheet" href="Front/css/cart.css"/>
<section class="vh-100" style="background-color: #fdccbc;">
  <div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col">
        <p><span class="h2">Panier </span><span class="h4"></span></p>

        <!-- Conteneur du panier avec les informations des commandes -->
        <div class="cart-container mb-4">
          <!-- Commande 1 -->
          <div class="cart-item card mb-3">
            <div class="card-body p-4">
              <div class="row align-items-center">
                <div class="col-md-2">
                </div>
                <div class="col-md-2 d-flex justify-content-center">
                  <div>
                </div>
              </div>
            </div>
          </div>
          <!-- Fin de la commande 1 -->
          
          <!-- Vous pouvez ajouter ici d'autres commandes si nécessaire -->
        </div>
        <!-- Fin du conteneur du panier -->

        <!-- Boutons -->
        <div class="d-flex justify-content-end mb-4">
          <!-- Rediriger vers home.php lors du clic -->
          <a href="home.php" class="btn btn-light btn-lg me-2">Continue shopping</a>
          <!-- Bouton pour le moyen de paiement -->
          <a href="paiment.php" class="btn btn-primary btn-lg me-2">Proceed to Payment</a>
          <!-- Vérification de la session utilisateur pour afficher ou masquer le bouton de connexion -->
          <?php if (!isset($_SESSION['email'])) { ?>
            <a href="login.php" class="btn btn-primary btn-lg">Login to Checkout</a>
          <?php } ?>
        </div>
        <!-- Fin des boutons -->

      </div>
    </div>
  </div>
</section>
