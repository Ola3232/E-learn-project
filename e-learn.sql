-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 10 juin 2025 à 17:23
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
-- Base de données : `e-learn`
--

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

CREATE TABLE `commentaires` (
  `id_commentaire` int(11) NOT NULL,
  `id_doc` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `commentaire` text NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `statut` int(50) NOT NULL DEFAULT 0,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commentaires`
--

INSERT INTO `commentaires` (`id_commentaire`, `id_doc`, `nom`, `commentaire`, `date_creation`, `statut`, `id_user`) VALUES
(17, 62, 'OLOSSOUMARE', 'yo les gars ', '2025-06-07 08:24:57', 1, NULL),
(31, 62, 'OLOSSOUMARE Sadath', 'Document id 63', '2025-06-07 22:25:35', 0, 11),
(32, 62, 'OLOSSOUMARE Sadath', 'yo', '2025-06-07 22:45:13', 0, 11),
(33, 62, 'OLOSSOUMARE', 'yo', '2025-06-07 22:59:05', 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `docs`
--

CREATE TABLE `docs` (
  `id_doc` int(11) NOT NULL,
  `label` varchar(100) DEFAULT NULL,
  `annee` varchar(50) DEFAULT NULL,
  `code_f` varchar(20) DEFAULT NULL,
  `fichier` varchar(100) DEFAULT NULL,
  `auteur` varchar(150) DEFAULT NULL,
  `date_ajout` date DEFAULT NULL,
  `ser` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `docs`
--

INSERT INTO `docs` (`id_doc`, `label`, `annee`, `code_f`, `fichier`, `auteur`, `date_ajout`, `ser`) VALUES
(62, 'C++', 'Licence1', 'SIL', '../uploads/COURS DE BASE DE DONNEES  - 2024.pdf', 'Docteur SANDA', '2025-06-05', NULL),
(63, 'C#', 'Licence1', 'SIL', '../uploads/Maintenance Sur Site.pdf', 'OLOSSOUMARE', '2025-06-05', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `e_user`
--

CREATE TABLE `e_user` (
  `id_user` int(11) NOT NULL,
  `nom` varchar(20) DEFAULT NULL,
  `prenom` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `annee` varchar(50) DEFAULT NULL,
  `mdp` varchar(20) DEFAULT NULL,
  `code_f` varchar(20) DEFAULT NULL,
  `role` enum('admin','etudiant') DEFAULT 'etudiant',
  `statut` varchar(50) NOT NULL DEFAULT 'actif',
  `date_inscription` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `e_user`
--

INSERT INTO `e_user` (`id_user`, `nom`, `prenom`, `email`, `annee`, `mdp`, `code_f`, `role`, `statut`, `date_inscription`) VALUES
(11, 'OLOSSOUMARE', 'Sadath', 'olossoumaresadath@gmail.com', 'Licence1', '123456', 'SIL', 'etudiant', 'actif', '2025-05-06 15:04:27'),
(13, 'Admin', 'Générale', 'admin@gmail.com', NULL, 'admin123', NULL, 'admin', 'actif', '2025-05-06 15:04:27');

-- --------------------------------------------------------

--
-- Structure de la table `filiere`
--

CREATE TABLE `filiere` (
  `code_f` varchar(20) NOT NULL,
  `libelle` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `filiere`
--

INSERT INTO `filiere` (`code_f`, `libelle`) VALUES
('BFA', 'Banque Finance et Assurance '),
('EGP', 'Entrepreneuria et Gestion de Projet'),
('RIT', 'Réseau Informatique et Télécommunications'),
('SIL', 'Système Informatique et Logiciel');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD PRIMARY KEY (`id_commentaire`),
  ADD KEY `fk_commentaires_user` (`id_user`);

--
-- Index pour la table `docs`
--
ALTER TABLE `docs`
  ADD PRIMARY KEY (`id_doc`),
  ADD UNIQUE KEY `ser` (`ser`),
  ADD KEY `fk_docs` (`code_f`);

--
-- Index pour la table `e_user`
--
ALTER TABLE `e_user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `fk_e_user` (`code_f`);

--
-- Index pour la table `filiere`
--
ALTER TABLE `filiere`
  ADD PRIMARY KEY (`code_f`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commentaires`
--
ALTER TABLE `commentaires`
  MODIFY `id_commentaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT pour la table `docs`
--
ALTER TABLE `docs`
  MODIFY `id_doc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT pour la table `e_user`
--
ALTER TABLE `e_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD CONSTRAINT `fk_commentaires_user` FOREIGN KEY (`id_user`) REFERENCES `e_user` (`id_user`);

--
-- Contraintes pour la table `docs`
--
ALTER TABLE `docs`
  ADD CONSTRAINT `fk_docs` FOREIGN KEY (`code_f`) REFERENCES `filiere` (`code_f`),
  ADD CONSTRAINT `fk_docs_user` FOREIGN KEY (`ser`) REFERENCES `e_user` (`id_user`);

--
-- Contraintes pour la table `e_user`
--
ALTER TABLE `e_user`
  ADD CONSTRAINT `fk_e_user` FOREIGN KEY (`code_f`) REFERENCES `filiere` (`code_f`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
