-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 14 avr. 2022 à 01:27
-- Version du serveur : 10.4.22-MariaDB
-- Version de PHP : 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `getaway`
--

-- --------------------------------------------------------

--
-- Structure de la table `activite`
--

CREATE TABLE `activite` (
  `RefAct` int(11) NOT NULL,
  `Nom` varchar(50) NOT NULL,
  `Descrip` varchar(50) NOT NULL,
  `Duree` varchar(50) NOT NULL,
  `NbrPlace` int(11) NOT NULL,
  `Date` varchar(100) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `Location` varchar(50) NOT NULL,
  `Prix` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `activite`
--

INSERT INTO `activite` (`RefAct`, `Nom`, `Descrip`, `Duree`, `NbrPlace`, `Date`, `Type`, `Location`, `Prix`) VALUES
(20, 'yoga', 'match ', '2H3', 20, '12-03-2022', 'bla', 'ariana', 255),
(21, 'foot', 'match ', '2H3', 16, '12-03-2022', 'bla', 'ariana', 255),
(22, 'handball', 'wow', '2H3', 16, '12-03-2022', 'sport', 'tunis', 255),
(25, 'sky', 'wow', '2H4', 10, '13-03-2022', 'diver', 'arianaa', 255);

-- --------------------------------------------------------

--
-- Structure de la table `avion`
--

CREATE TABLE `avion` (
  `id_avion` int(11) NOT NULL,
  `nbr_place` int(11) NOT NULL,
  `id_agence` int(11) NOT NULL,
  `nom_avion` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `RefAvis` int(11) NOT NULL,
  `Message` varchar(250) NOT NULL,
  `Date` date NOT NULL,
  `Id` int(11) NOT NULL,
  `RefActivite` int(11) NOT NULL,
  `Rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `categorievoy`
--

CREATE TABLE `categorievoy` (
  `idcat` int(11) NOT NULL,
  `nomcat` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `categorievoy`
--

INSERT INTO `categorievoy` (`idcat`, `nomcat`) VALUES
(1, 'omra'),
(5, 'honney moon1'),
(7, 'fin dannee');

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id_categ` int(11) NOT NULL,
  `nom_categ` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id_categ`, `nom_categ`) VALUES
(1, 'villa'),
(2, 'hotel'),
(3, 'caravane'),
(5, '1'),
(6, '1');

-- --------------------------------------------------------

--
-- Structure de la table `hebergement`
--

CREATE TABLE `hebergement` (
  `referance` int(11) NOT NULL,
  `offreur_id` int(11) DEFAULT NULL,
  `paye` varchar(15) DEFAULT NULL,
  `adress` varchar(50) DEFAULT NULL,
  `prix` float DEFAULT NULL,
  `description` varchar(300) DEFAULT NULL,
  `photo` varchar(999) DEFAULT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `contact` int(11) DEFAULT NULL,
  `nbr_detoile` int(11) DEFAULT NULL,
  `nbr_suite` int(11) DEFAULT NULL,
  `nbr_parking` int(11) DEFAULT NULL,
  `model_caravane` varchar(15) DEFAULT NULL,
  `id_categ` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `hebergement`
--

INSERT INTO `hebergement` (`referance`, `offreur_id`, `paye`, `adress`, `prix`, `description`, `photo`, `date_start`, `date_end`, `contact`, `nbr_detoile`, `nbr_suite`, `nbr_parking`, `model_caravane`, `id_categ`) VALUES
(7, 3, 'tunis', 'tunis', 25000, 'ppppppppp', '55', '2022-04-01', '2022-04-30', 4, NULL, NULL, NULL, NULL, NULL),
(8, 1, 'tunis', 'tunis', 25000, 'ddddd', 'test', '2022-04-11', '2022-05-15', 4, 4, 22, 22, 'm1', 2),
(13, 1, 'tunis', 'soussa', 850, 'sss', 'aaaaa', '2022-04-01', '2022-04-30', 4, 4, 22, 1, 'm1', 3);

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE `paiement` (
  `id` int(11) NOT NULL,
  `modalite_paiement` varchar(30) NOT NULL,
  `montant` float NOT NULL,
  `date` date NOT NULL,
  `id_reservation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `paiement`
--

INSERT INTO `paiement` (`id`, `modalite_paiement`, `montant`, `date`, `id_reservation`) VALUES
(146, 'CARTE BLEUE', 1500, '2022-04-13', 237),
(147, 'Paypal', 50000, '2022-04-13', 238),
(151, 'CARTE BLEUE', 255, '2022-04-13', 242),
(152, 'CAISSE', 510, '2022-04-13', 244),
(153, 'CAISSE', 1000, '2022-04-13', 245),
(154, 'VIREMENT', 50000, '2022-04-13', 246);

-- --------------------------------------------------------

--
-- Structure de la table `reclamation`
--

CREATE TABLE `reclamation` (
  `idR` int(11) NOT NULL,
  `idClient` int(11) NOT NULL,
  `objet` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL,
  `etat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `date_reservation` date NOT NULL,
  `nbr_place` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `id_client` int(11) NOT NULL,
  `id_voyage` int(11) DEFAULT NULL,
  `id_activite` int(11) DEFAULT NULL,
  `id_vol` int(11) DEFAULT NULL,
  `id_hebergement` int(11) DEFAULT NULL,
  `etat` varchar(30) NOT NULL,
  `type` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`id`, `date_reservation`, `nbr_place`, `date_debut`, `date_fin`, `id_client`, `id_voyage`, `id_activite`, `id_vol`, `id_hebergement`, `etat`, `type`) VALUES
(237, '2022-04-13', 3, '2020-05-22', '2020-05-22', 1, NULL, NULL, 6, NULL, 'Approuve', 'Vol'),
(238, '2022-04-13', 0, '2022-04-14', '2022-04-16', 1, NULL, NULL, NULL, 8, 'Annulee', 'Hebergement'),
(242, '2022-04-13', 1, '2022-03-12', '2022-03-12', 2, NULL, 21, NULL, NULL, 'Approuve', 'Activite'),
(244, '2022-04-13', 2, '2022-03-12', '2022-03-12', 1, NULL, 22, NULL, NULL, 'Approuve', 'Activite'),
(245, '2022-04-13', 2, '2020-05-22', '2020-05-22', 2, NULL, NULL, 6, NULL, 'Approuve', 'Vol'),
(246, '2022-04-13', 0, '2022-04-22', '2022-04-24', 2, NULL, NULL, NULL, 8, 'Approuve', 'Hebergement');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `securityQ` varchar(255) DEFAULT NULL,
  `answer` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `numtel` int(11) DEFAULT NULL,
  `nomAgence` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL,
  `etat` varchar(50) NOT NULL DEFAULT '1',
  `solde` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `nom`, `prenom`, `password`, `securityQ`, `answer`, `email`, `adresse`, `numtel`, `nomAgence`, `role`, `etat`, `solde`) VALUES
(1, 'salma', 'sal', 'salouma', '', '', 'salma.dj@gm.com', 'canada', 21559039, 'tunisair', 'Agent-Aerien', '1', 0),
(2, 'zeineb', 'mbarki', 'QVZkBomDLSbitS4C9lGaUA==', '', '', '', '', 0, '', 'Client', '1', 0),
(3, 'admin', 'hahah', 'QVZkBomDLSbitS4C9lGaUA==', '', '', 'adm.ad@gm.com', 'hahah', 2168726, '', 'Admin', '1', 0);

-- --------------------------------------------------------

--
-- Structure de la table `vol`
--

CREATE TABLE `vol` (
  `id_vol` int(11) NOT NULL,
  `date_depart` datetime NOT NULL,
  `date_arrivee` datetime NOT NULL,
  `ville_depart` varchar(60) NOT NULL,
  `ville_arrivee` varchar(50) NOT NULL,
  `nbr_placedispo` int(11) NOT NULL,
  `id_avion` int(11) NOT NULL,
  `prix` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `vol`
--

INSERT INTO `vol` (`id_vol`, `date_depart`, `date_arrivee`, `ville_depart`, `ville_arrivee`, `nbr_placedispo`, `id_avion`, `prix`) VALUES
(6, '2020-05-22 12:11:12', '2020-05-22 17:11:15', 'tunis', 'Paris', 58, 2, 500),
(9, '2022-04-13 23:35:54', '2022-04-13 23:35:54', 'Tunis', 'qatar', 4, 1, 2500),
(11, '2022-04-13 23:46:09', '2022-04-13 23:46:09', 'Tunis', 'paris', 12, 1, 350);

-- --------------------------------------------------------

--
-- Structure de la table `voyageorganise`
--

CREATE TABLE `voyageorganise` (
  `idVoy` int(11) NOT NULL,
  `villeDepart` varchar(30) NOT NULL,
  `villeDest` varchar(30) NOT NULL,
  `dateDepart` varchar(20) NOT NULL,
  `dateArrive` varchar(20) NOT NULL,
  `nbrPlace` int(11) NOT NULL,
  `idCat` int(11) NOT NULL,
  `prix` float NOT NULL,
  `description` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `voyageorganise`
--

INSERT INTO `voyageorganise` (`idVoy`, `villeDepart`, `villeDest`, `dateDepart`, `dateArrive`, `nbrPlace`, `idCat`, `prix`, `description`) VALUES
(65, 'tunis', 'usa', '2022-03-05', '2022-03-10', 3, 1, 35000, ''),
(67, 'tunis', 'hinde', '05-03-2022', '10-03-2022', 19, 7, 35000, 'adadadfad'),
(68, 'tunis', 'usa', '2022-03-05', '2022-03-10', 24, 5, 35000, 'adadadfad'),
(69, 'tunis', 'turky', '2022-03-05', '2022-03-10', 28, 5, 35000, 'adadadfad'),
(70, 'tunis', 'qatar', '2022-03-05', '2022-03-10', 20, 5, 35000, 'adadadfad');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `activite`
--
ALTER TABLE `activite`
  ADD PRIMARY KEY (`RefAct`);

--
-- Index pour la table `avion`
--
ALTER TABLE `avion`
  ADD PRIMARY KEY (`id_avion`),
  ADD KEY `id_agence` (`id_agence`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`RefAvis`),
  ADD KEY `fk_idavis` (`Id`),
  ADD KEY `frk_act` (`RefActivite`);

--
-- Index pour la table `categorievoy`
--
ALTER TABLE `categorievoy`
  ADD PRIMARY KEY (`idcat`);

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id_categ`);

--
-- Index pour la table `hebergement`
--
ALTER TABLE `hebergement`
  ADD PRIMARY KEY (`referance`),
  ADD KEY `fk_off` (`offreur_id`),
  ADD KEY `fk_categ` (`id_categ`);

--
-- Index pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reservation` (`id_reservation`);

--
-- Index pour la table `reclamation`
--
ALTER TABLE `reclamation`
  ADD PRIMARY KEY (`idR`),
  ADD KEY `idClient` (`idClient`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_client` (`id_client`),
  ADD KEY `fk_voyage` (`id_voyage`),
  ADD KEY `fk_act` (`id_activite`),
  ADD KEY `id_vol` (`id_vol`),
  ADD KEY `fk_heb` (`id_hebergement`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `vol`
--
ALTER TABLE `vol`
  ADD PRIMARY KEY (`id_vol`),
  ADD KEY `id_avion` (`id_avion`);

--
-- Index pour la table `voyageorganise`
--
ALTER TABLE `voyageorganise`
  ADD PRIMARY KEY (`idVoy`),
  ADD KEY `idCat` (`idCat`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `activite`
--
ALTER TABLE `activite`
  MODIFY `RefAct` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `avion`
--
ALTER TABLE `avion`
  MODIFY `id_avion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `RefAvis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `categorievoy`
--
ALTER TABLE `categorievoy`
  MODIFY `idcat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id_categ` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `hebergement`
--
ALTER TABLE `hebergement`
  MODIFY `referance` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT pour la table `reclamation`
--
ALTER TABLE `reclamation`
  MODIFY `idR` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `vol`
--
ALTER TABLE `vol`
  MODIFY `id_vol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `voyageorganise`
--
ALTER TABLE `voyageorganise`
  MODIFY `idVoy` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avion`
--
ALTER TABLE `avion`
  ADD CONSTRAINT `fk_idag` FOREIGN KEY (`id_agence`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `fk_idavis` FOREIGN KEY (`Id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `frk_act` FOREIGN KEY (`RefActivite`) REFERENCES `activite` (`RefAct`);

--
-- Contraintes pour la table `hebergement`
--
ALTER TABLE `hebergement`
  ADD CONSTRAINT `fk_categ` FOREIGN KEY (`id_categ`) REFERENCES `category` (`id_categ`),
  ADD CONSTRAINT `fk_off` FOREIGN KEY (`offreur_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `fk_reservation` FOREIGN KEY (`id_reservation`) REFERENCES `reservation` (`id`);

--
-- Contraintes pour la table `reclamation`
--
ALTER TABLE `reclamation`
  ADD CONSTRAINT `id_clientfk` FOREIGN KEY (`idClient`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `fk_act` FOREIGN KEY (`id_activite`) REFERENCES `activite` (`RefAct`),
  ADD CONSTRAINT `fk_client` FOREIGN KEY (`id_client`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_heb` FOREIGN KEY (`id_hebergement`) REFERENCES `hebergement` (`referance`),
  ADD CONSTRAINT `fk_voyage` FOREIGN KEY (`id_voyage`) REFERENCES `voyageorganise` (`idVoy`),
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`id_vol`) REFERENCES `vol` (`id_vol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
