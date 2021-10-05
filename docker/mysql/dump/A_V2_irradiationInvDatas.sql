-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mar. 05 oct. 2021 à 11:53
-- Version du serveur :  5.7.27-30-log
-- Version de PHP :  7.3.13-1+0~20191218.50+debian9~1.gbp23c2da

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `dev_amanda`
--

-- --------------------------------------------------------

--
-- Structure de la table `A_V2_irradiationInvDatas`
--

CREATE TABLE `A_V2_irradiationInvDatas` (
  `id_projet` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `G572` int(10) UNSIGNED DEFAULT NULL,
  `ghi` float NOT NULL,
  `gti` float NOT NULL,
  `temp` float NOT NULL,
  `pvout` float NOT NULL,
  PRIMARY KEY(id_projet, datetime)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
