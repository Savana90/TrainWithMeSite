-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Hôte : db.3wa.io
-- Généré le : mar. 19 oct. 2021 à 14:56
-- Version du serveur :  5.7.33-0ubuntu0.18.04.1-log
-- Version de PHP : 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `savannahcharles_training`
--

-- --------------------------------------------------------

--
-- Structure de la table `chat_box`
--

CREATE TABLE `chat_box` (
  `id` int(10) UNSIGNED NOT NULL,
  `message_create_at` datetime NOT NULL,
  `content` text NOT NULL,
  `sender_foreign_key` int(11) NOT NULL,
  `receiver_foreign_key` int(11) NOT NULL,
  `status` int(2) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `chat_box`
--

INSERT INTO `chat_box` (`id`, `message_create_at`, `content`, `sender_foreign_key`, `receiver_foreign_key`, `status`) VALUES
(338, '2021-10-18 01:32:40', 'Hey Cecile .!', 78, 175, NULL),
(339, '2021-10-18 01:32:55', 'Ã§a va?', 78, 175, NULL),
(340, '2021-10-18 01:33:17', 'Hello Marie', 78, 176, 0),
(341, '2021-10-18 01:33:45', 'Ã§a va ?', 78, 176, 0),
(342, '2021-10-18 22:46:20', 'Coucou,  je m\'appelle Erika', 75, 90, 0),
(343, '2021-10-18 22:47:17', 'Je serais intÃ©ressÃ©e par une sÃ©ance jeudi ou vendredi plutÃ´t en matinÃ©e', 75, 90, 0),
(344, '2021-10-19 01:17:15', 'hey', 91, 90, 0),
(345, '2021-10-19 01:17:21', 'Ã§a va ?', 91, 90, 0),
(346, '2021-10-19 01:17:37', 'hello', 91, 75, 0),
(350, '2021-10-19 07:17:47', 'CC', 175, 78, NULL),
(351, '2021-10-19 07:18:16', 'Oui et toi?', 175, 78, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `contact_message`
--

CREATE TABLE `contact_message` (
  `id_contact_message` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `msg` text NOT NULL,
  `msg_process` int(11) DEFAULT '0',
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `contact_message`
--

INSERT INTO `contact_message` (`id_contact_message`, `name`, `email`, `msg`, `msg_process`, `id_user`) VALUES
(32, 'Nana', 'nana@gmail.com', 'Hey admin', 1, 91),
(34, 'Riri', 'riri@gmail.com', 'Hi admin', 1, NULL),
(40, 'Nadine', 'keke@gmail.fr', 'test', 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `gym`
--

CREATE TABLE `gym` (
  `id` int(10) UNSIGNED NOT NULL,
  `gym_name` varchar(50) NOT NULL,
  `gym_road` varchar(50) NOT NULL,
  `gym_zipcode` int(10) NOT NULL,
  `city` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `gym`
--

INSERT INTO `gym` (`id`, `gym_name`, `gym_road`, `gym_zipcode`, `city`) VALUES
(1, 'NEONESS BOURSE OPÉRA', '21 rue de la banque', 75002, 'Paris'),
(2, 'NEONESS CHÂTELET MONTORGUEIL', '35/39 rue Greneta', 75002, 'Paris'),
(3, 'NEONESS RÉPUBLIQUE', '50 rue de Malte', 75011, 'Paris'),
(4, 'NEONESS AUSTERLITZ', '22 bis boulevard Saint Marcel', 75005, 'Paris'),
(5, 'NEONESS MADELEINE', '7 rue de Caumartin', 75009, 'Paris'),
(6, 'NEONESS SAINT-LAZARE', '44 rue de Clichy', 75009, 'Paris'),
(7, 'NEONESS RÉPUBLIQUE', '50 rue de Malte', 75011, 'Paris'),
(8, 'NEONESS BASTILLE', '4 juin Passage Louis Philippe', 75011, 'Paris'),
(9, 'NEONESS BELLEVILLE MENILMONTANT', '25 boulevard de Belleville', 75011, 'Paris'),
(10, 'NEONESS NATION PORTE DE VINCENNES', '81 Rue de Lagny', 75020, 'Paris'),
(11, 'NEONESS BASTILLE', '44351 Passage Louis Philippe', 75011, 'Paris'),
(12, 'NEONESS PLACE D\'ITALIE LES GOBELINS', '7 rue Abel Hovelacque', 75013, 'Paris'),
(13, 'NEONESS PLACE D\'ITALIE BUTTES AUX CAILLES', '7 rue Vergniaud', 75013, 'Paris'),
(14, 'NEONESS MONTPARNASSE', '6 allée de la 2ème Division Blindée', 75014, 'Paris'),
(15, 'NEONESS DENFERT ALÉSIA', '214 avenue du Maine', 75014, 'Paris'),
(16, 'NEONESS BEAUGRENELLE', '104 rue Saint Charles', 75015, 'Paris'),
(17, 'NEONESS LA MOTTE PICQUET', '18 rue Juge', 75015, 'Paris'),
(18, 'NEONESS BATIGNOLLES PLACE DE CLICHY', '5 rue Bernard Buffet', 75017, 'Paris'),
(19, 'NEONESS BARBES MARCADET', '28 rue Boinod', 75018, 'Paris'),
(20, 'NEONESS JAURÈS', '46 ter Rue de Meaux', 75019, 'Paris'),
(21, 'NEONESS NATION', '81 Rue de Lagny', 75020, 'Paris'),
(22, 'NEONESS BELEVILLE MÉNILMONTANT', '25 boulevard de Belleville', 75011, 'Paris');

-- --------------------------------------------------------

--
-- Structure de la table `session_user`
--

CREATE TABLE `session_user` (
  `session_id` int(10) UNSIGNED NOT NULL,
  `user_foreign_key` int(11) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `php_session_id` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `session_user`
--

INSERT INTO `session_user` (`session_id`, `user_foreign_key`, `timestamp`, `php_session_id`) VALUES
(923, 207, '2021-10-19 08:43:38', '2lq7gnpe2ctphmismnfgod7srt');

-- --------------------------------------------------------

--
-- Structure de la table `slot`
--

CREATE TABLE `slot` (
  `id_slot` int(10) UNSIGNED NOT NULL,
  `day_slot` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `slot`
--

INSERT INTO `slot` (`id_slot`, `day_slot`) VALUES
(1, 'Lundi matin'),
(2, 'Lundi après-midi'),
(3, 'Lundi soir'),
(4, 'Mardi matin'),
(5, 'Mardi après-midi'),
(6, 'Mardi soir'),
(7, 'Mercredi matin'),
(8, 'Mercredi après-midi'),
(9, 'Mercredi soir'),
(10, 'Jeudi matin'),
(11, 'Jeudi après-midi'),
(12, 'Jeudi soir'),
(13, 'Vendredi matin'),
(14, 'Vendredi après-midi'),
(15, 'Vendredi soir'),
(16, 'Samedi matin'),
(17, 'Samedi après-midi'),
(18, 'Samedi soir'),
(19, 'Dimanche matin'),
(20, 'Dimanche après-midi'),
(21, 'Dimanche soir');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `admin` int(11) NOT NULL DEFAULT '0',
  `email` varchar(120) NOT NULL,
  `password` varchar(120) NOT NULL,
  `profil_picture` varchar(120) DEFAULT NULL,
  `sexe` varchar(10) NOT NULL DEFAULT 'F',
  `created_at` datetime NOT NULL,
  `time_slot` int(10) UNSIGNED DEFAULT NULL,
  `gym_foreign_key` int(11) UNSIGNED DEFAULT NULL,
  `last_connection_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `user_name`, `admin`, `email`, `password`, `profil_picture`, `sexe`, `created_at`, `time_slot`, `gym_foreign_key`, `last_connection_time`) VALUES
(58, 'toto', 'TOTI', 'toto100', 1, 'toto@gmail.fr', '$2y$10$sq.W2k0IaYYqLqGnRp5XWeq0NvdgJzmNyBUnMW0aofW28wdUzl/Ky', NULL, 'F', '2021-08-06 00:57:31', NULL, NULL, '2021-10-19 08:33:59'),
(75, 'ERIKA', 'CHARLES', 'ariri22', 0, 'Erika.charles88@gmail.com', '$2y$10$2iUq2i2F2RFgA0UiamOgsuMlVIuyB0TazXpeIIMBKZJBcwgjc2y46', NULL, 'F', '2021-08-06 15:41:53', NULL, 14, '2021-10-18 22:44:51'),
(78, 'SAVANNAH', 'CHARLES', 'sasa973', 0, 'charles.savannah0@gmail.com', '$2y$10$updJ/n8pic3E9DqOi4p6kuaFX8WLGa7Y/ufs6w5Fz4.8fVq3oicgu', '61426af094509-78.jpeg', 'F', '2021-08-07 01:42:24', 1, 18, '2021-10-19 08:30:02'),
(80, 'LIZI', 'WILLIAMS', 'lizi1959', 0, 'elizabeth@gmail.com', '$2y$10$8WXx9OogRKddAtyuo41Vx.vNIAUVuTbg2VXwOUs5t8fbrhxqNWUH2', NULL, 'F', '2021-08-07 02:53:10', NULL, NULL, '2021-08-22 23:28:44'),
(81, 'Sevilay', 'Gumus', 'Sevilay', 0, 'gumus.sevilay@gmail.com', '$2y$10$8Qr93qo7aphiAeLZK9KUZ.n5hyiiBKzKHUeZ1MBQHZaBndbY9UbK2', NULL, 'F', '2021-08-07 19:19:52', NULL, NULL, '2021-08-22 23:28:44'),
(82, 'Diane', 'BOYCE', 'Diane973', 0, 'diane@gmail.fr', '$2y$10$FckGnON8IWalGoxnCXjQfOgMjfE7PQgw/K7rlFH9IiLCpEeJERCsa', NULL, 'F', '2021-08-08 04:02:25', 2, NULL, '2021-08-22 23:28:44'),
(90, 'Janice', 'Charles', 'jaja973', 0, 'jaja1@gmail.com', '$2y$10$qcpZQYPXYWYBN6K6a0Q46u2EhbF9uHVT74NMQmPy4Zr4Mv.6Fb6hC', '61514fa27ccec-90.jpeg', 'F', '2021-08-13 03:20:23', 2, 14, '2021-09-27 07:00:08'),
(91, 'Nadine', 'Charles', 'nana973', 0, 'nana@gmail.com', '$2y$10$mmr852Tai.p4Ysw/EW.w8.DfIazY4w3MFgYWpBGR6e3SPjfanH88O', '614792406e7ff-91.jpeg', 'F', '2021-08-13 03:22:08', 1, 14, '2021-10-19 06:33:55'),
(108, 'KESHIA', 'CHARLES', 'KESHIA22', 0, 'keshia-charles@hotmail.fr', '$2y$10$CVb4DUt8cpFx7srMYz8HVudc/gctzQ3WuZWIqMqlvBrsiCwzDz2wi', NULL, 'F', '2021-08-20 22:25:26', NULL, 14, '2021-08-22 23:28:44'),
(175, 'Cecile', 'DONOVAN', 'Cecile7', 0, 'Dcece@gmail.com', '$2y$10$xfSSSbdLnNmKCt7LJzkVK.wxNTBc02MfBAgYwdmgmz5HSA8QLehtm', '61514a64d7510-175.jpeg', 'F', '2021-09-27 06:35:48', 1, 18, '2021-10-19 08:28:59'),
(176, 'Marie', 'Dupont', 'Marie77', 0, 'marie.dupont@gmail.com', '$2y$10$4jcp0m14sc5ADTKmHVVrM.5I8GXVoP5FARSX2W7QCtPsZDHVnp54S', '61514ae632d7f-176.jpeg', 'F', '2021-09-27 06:38:29', 1, 18, '2021-09-27 06:57:50'),
(177, 'Sylvie', 'Crotta', 'Sylvie7', 0, 'crotta.sylvie@hotmail.com', '$2y$10$l49/w2c9CMY9OL1QScBcuetLfz0UR.du4A00qkemlwbmOj//vs4di', '61514c650cbef-177.jpeg', 'F', '2021-09-27 06:41:58', 16, 6, '2021-09-27 06:55:24'),
(195, 'Nuria', 'MERCAN', 'Nunu971', 0, 'nuriamercan@hotmail.fr', '$2y$10$tscpDtyXeJZ6NVK9IVVCAuUe6AcC6S5kHEp5tQnC5IZ.F0sze0j2K', NULL, 'F', '2021-10-06 19:55:33', NULL, NULL, '2021-10-06 19:55:33'),
(198, 'Estelle', 'Meledje', 'Estelle29', 0, 'Enock.lokossou@gmail.com', '$2y$10$t29TBprODdgV4.fcMCTwZ.6RZmiYNBVA2JxfJRJiaUrrnETOwfma2', NULL, 'F', '2021-10-08 12:19:17', 4, 4, '2021-10-08 12:19:17'),
(207, 'Admin', 'ADMIN', 'Admin100', 1, 'admin100@gmail.com', '$2y$10$bYJa1h2vKIPhTVV.L514Vu63woyxuQ5vZdKM9e6sRmOgNc3jy6h9.', NULL, 'F', '2021-10-14 19:33:01', NULL, NULL, '2021-10-19 10:30:26'),
(211, 'yaya', 'yaya', 'yaya876', 0, 'yaya@gmail.fr', '$2y$10$5QOVoUrsVctKM/ETmig3N.o5Ne.N8vtCw4kC5z1QJIAirfcnjPatW', '616e66f85e929-211.jpeg', 'F', '2021-10-19 08:33:40', 1, 4, '2021-10-19 08:33:40');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `chat_box`
--
ALTER TABLE `chat_box`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `contact_message`
--
ALTER TABLE `contact_message`
  ADD PRIMARY KEY (`id_contact_message`);

--
-- Index pour la table `gym`
--
ALTER TABLE `gym`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `session_user`
--
ALTER TABLE `session_user`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `user_foreign_key` (`user_foreign_key`);

--
-- Index pour la table `slot`
--
ALTER TABLE `slot`
  ADD PRIMARY KEY (`id_slot`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `gym_foreign_key` (`gym_foreign_key`),
  ADD KEY `time_slot` (`time_slot`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `chat_box`
--
ALTER TABLE `chat_box`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=360;

--
-- AUTO_INCREMENT pour la table `contact_message`
--
ALTER TABLE `contact_message`
  MODIFY `id_contact_message` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT pour la table `gym`
--
ALTER TABLE `gym`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `session_user`
--
ALTER TABLE `session_user`
  MODIFY `session_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=924;

--
-- AUTO_INCREMENT pour la table `slot`
--
ALTER TABLE `slot`
  MODIFY `id_slot` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `session_user`
--
ALTER TABLE `session_user`
  ADD CONSTRAINT `session_user_ibfk_1` FOREIGN KEY (`user_foreign_key`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`gym_foreign_key`) REFERENCES `gym` (`id`),
  ADD CONSTRAINT `user_ibfk_2` FOREIGN KEY (`time_slot`) REFERENCES `slot` (`id_slot`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
