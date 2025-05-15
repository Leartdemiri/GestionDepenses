SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données : `UB$`
--

CREATE DATABASE `UB$` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Use the database
USE `UB$`;
-- --------------------------------------------------------

--
-- Structure de la table `economy`
--

CREATE TABLE `economy` (
  `idEconomy` int(11) NOT NULL,
  `idUser` int(11) DEFAULT NULL,
  `monthlyLimit` double DEFAULT NULL,
  `spendAim` double DEFAULT NULL,
  `BaseMoney` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `spending`
--

CREATE TABLE `spending` (
  `idSpending` int(11) NOT NULL,
  `idEconomy` int(11) DEFAULT NULL,
  `idSpendType` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `dateCreated` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `spendTypes`
--

CREATE TABLE `spendTypes` (
  `idSpendingType` int(11) NOT NULL,
  `Type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `spendTypes`
--

INSERT INTO `spendTypes` (`idSpendingType`, `Type`) VALUES
(1, 'Nourriture'),
(2, 'Logement'),
(3, 'Transports'),
(4, 'Santé'),
(5, 'Loisir'),
(6, 'Assurances'),
(7, 'Vêtements'),
(10, 'Autres');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `idUser` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `Firstname` varchar(255) DEFAULT NULL,
  `Lastname` varchar(255) DEFAULT NULL,
  `Token` varchar(500) DEFAULT NULL,
  `Password` varchar(500) NOT NULL,
  `currency` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour la table `economy`
--
ALTER TABLE `economy`
  ADD PRIMARY KEY (`idEconomy`),
  ADD KEY `idUser` (`idUser`);

--
-- Index pour la table `spending`
--
ALTER TABLE `spending`
  ADD PRIMARY KEY (`idSpending`),
  ADD KEY `idEconomy` (`idEconomy`),
  ADD KEY `idSpendType` (`idSpendType`);

--
-- Index pour la table `spendTypes`
--
ALTER TABLE `spendTypes`
  ADD PRIMARY KEY (`idSpendingType`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT pour la table `economy`
--
ALTER TABLE `economy`
  MODIFY `idEconomy` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `spending`
--
ALTER TABLE `spending`
  MODIFY `idSpending` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `spendTypes`
--
ALTER TABLE `spendTypes`
  MODIFY `idSpendingType` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour la table `economy`
--
ALTER TABLE `economy`
  ADD CONSTRAINT `economy_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE;

--
-- Contraintes pour la table `spending`
--
ALTER TABLE `spending`
  ADD CONSTRAINT `spending_ibfk_1` FOREIGN KEY (`idEconomy`) REFERENCES `economy` (`idEconomy`) ON DELETE CASCADE,
  ADD CONSTRAINT `spending_ibfk_2` FOREIGN KEY (`idSpendType`) REFERENCES `spendTypes` (`idSpendingType`);
COMMIT;

--
-- Création d'un utilisateur pour la connection entre PDO et BDD
--
    
CREATE USER 'UBS_ADMIN'@'%' IDENTIFIED VIA mysql_native_password USING '***';
GRANT ALL PRIVILEGES ON *.* TO 'UBS_ADMIN'@'%' REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT ALL PRIVILEGES ON `UB$`.* TO 'UBS_ADMIN'@'%';