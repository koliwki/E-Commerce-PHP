-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 26 fév. 2024 à 23:35
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `e-commerce`
--

-- --------------------------------------------------------

--
-- Structure de la table `address`
--

CREATE TABLE `address` (
  `address_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `street_address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `postal_code` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `added_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `product_id`, `quantity`, `added_date`) VALUES
(86, 9, 10, 1, '2024-02-26 21:42:57'),
(87, 9, 8, 3, '2024-02-26 21:43:08'),
(88, 7, 10, 3, '2024-02-26 21:52:10'),
(89, 7, 7, 1, '2024-02-26 21:54:27');

-- --------------------------------------------------------

--
-- Structure de la table `command`
--

CREATE TABLE `command` (
  `command_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `address_delivery` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`comment_id`, `product_id`, `user_id`, `content`, `created_at`) VALUES
(1, 7, NULL, 'jaime biennnn', '2024-02-26 22:02:38'),
(2, 7, NULL, 'jaime biennnn', '2024-02-26 22:04:24'),
(3, 7, NULL, 'jaime biennnn', '2024-02-26 22:04:57'),
(4, 7, NULL, 'lourd \r\n', '2024-02-26 22:05:05'),
(5, 7, NULL, 'jaime biennnn', '2024-02-26 22:06:34'),
(6, 7, 7, 'ok', '2024-02-26 22:29:02'),
(7, 7, 7, 'cest vraiment du lourd wow ', '2024-02-26 22:30:08'),
(8, 2, 7, 'ca glisse comme brice de nice ', '2024-02-26 22:30:29'),
(9, 2, 9, 'jai glisse jusquen laponie ', '2024-02-26 22:30:57'),
(10, 7, 9, 'jai pas reussi a les portes cest lourd ', '2024-02-26 22:31:21'),
(11, 10, 9, 'ca rebondit bien \r\n', '2024-02-26 22:31:51'),
(12, 3, 9, 'hmmm delicious ', '2024-02-26 22:32:01'),
(13, 3, 7, 'jaime trop les kinder ', '2024-02-26 22:32:42'),
(14, 9, 7, 'mon chien et moi on aime beaucoup', '2024-02-26 22:33:05'),
(15, 8, 7, 'ca facile le transit ', '2024-02-26 22:33:21');

-- --------------------------------------------------------

--
-- Structure de la table `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `command_id` int(11) NOT NULL,
  `invoice_date` datetime NOT NULL,
  `total_amount` int(11) NOT NULL,
  `status_payment` varchar(255) NOT NULL,
  `payment_method` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` int(11) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `description`, `price`, `category`, `product_image`) VALUES
(2, 'planche de surf ', 'vend planche de surf neuve ', 499, 'sport', 'https://www.surfmarket.org/images/stories/virtuemart/product/dhd-twin-fin-blue-scarlett.jpg'),
(3, 'boite de chocolat', 'boite de chocolat assortie avec plein de surprise ', 50, 'nourriture', 'https://www.mesechantillonsgratuits.fr/wp-content/uploads/2021/03/assortiment-de-barre-au-chocolat-20-pieces-2048x1676.png'),
(7, 'altere', 'cest du lourd', 55, 'sport', 'https://i.pinimg.com/originals/aa/24/ef/aa24efdb8ca97c8889604dd45a5fefed.jpg'),
(8, 'cable ethernet ', 'cable ethernet haute performances ', 15, 'autres', 'https://th.bing.com/th/id/OIP.2AV9NvFzgaPc636VES9BOwAAAA?rs=1&pid=ImgDetMain'),
(9, 'croquette pour chien', 'croquette pour chien', 20, 'autres', 'https://images-na.ssl-images-amazon.com/images/I/81dCdPMezkL._AC_SL1500_.jpg'),
(10, 'balle de tenis', 'plusie', 40, 'sport', 'https://www.ecotennis.ca/wp-content/uploads/2018/06/00100dPORTRAIT_00100_BURST20180619073630606_COVER-1080x675.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `phone_number` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`username`, `password`, `email`, `firstname`, `lastname`, `country`, `phone_number`, `user_id`) VALUES
('koliwki', '$2y$10$uZQ9UWb1s20UjICjklfCPuhia/s19Ru./tjt7teMaX74hUH/ZSCAW', 'thomas.bataille@solveg.fr', '', '', '', NULL, 7),
('admin', '$2y$10$eOcZi93VeTGBDghDpDjazORljqFj.JjnhIlOtixygNEa2PDbq1kE.', 'admin@admin.com', '', '', '', NULL, 8),
('akaza', '$2y$10$lZYtfYBYcf94fKitLiosIeZHpFsfxQbjSjn5ortLRccXGP55HKxVO', 'az@az', 'Thomas', 'Bataille', 'France', 624574408, 9);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD UNIQUE KEY `unique_cart_product` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Index pour la table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
