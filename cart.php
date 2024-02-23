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
<body>
    <div class="container">
        <h1 class="title">Panier d'achat</h1>
        <div class="cart-items">
            <!-- Exemple de contenu du panier -->
            <div class="cart-item">
                <div class="item-details">
                    <h3>Produit 1</h3>
                    <p>Description du produit 1</p>
                </div>
                <div class="item-actions">
                    <p>Prix : $10</p>
                    <button class="remove-btn">Supprimer</button>
                </div>
            </div>
            <div class="cart-item">
                <div class="item-details">
                    <h3>Produit 2</h3>
                    <p>Description du produit 2</p>
                </div>
                <div class="item-actions">
                    <p>Prix : $15</p>
                    <button class="remove-btn">Supprimer</button>
                </div>
            </div>
        </div>
        <div class="cart-total">
            <h2>Total : $25</h2>
        </div>
        <div class="buttons">
            <a href="home.php" class="continue-shopping-btn">Continuer vos achats</a>
            <a href="paiment.php" class="proceed-to-payment-btn">Procéder au paiement</a>
        </div>
    </div>
</body>
