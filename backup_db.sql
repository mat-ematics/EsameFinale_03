-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 31.11.39.204:3306
-- Creato il: Feb 19, 2025 alle 17:39
-- Versione del server: 8.0.40-31
-- Versione PHP: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Sql1847068_1`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `admins`
--

CREATE TABLE `admins` (
  `idAdmin` tinyint UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_0900_as_cs NOT NULL,
  `password` varchar(80) COLLATE utf8mb4_0900_as_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_as_cs;

--
-- Dump dei dati per la tabella `admins`
--

INSERT INTO `admins` (`idAdmin`, `username`, `password`) VALUES
(1, 'Cringy', '$2y$10$lMHFFqgDY/HYb4DBYKYKx.PaviDpWMW2R4IXYreeB8UFW.4Qh.sWO'),
(11, 'testthere', '$2y$10$EMXDms6LGR1F1akB6aWWHO.5KiAZB6MbjDTF5iFojyB/l5EQGRxvK');

-- --------------------------------------------------------

--
-- Struttura della tabella `categories`
--

CREATE TABLE `categories` (
  `idCategory` tinyint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_0900_as_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_as_cs;

--
-- Dump dei dati per la tabella `categories`
--

INSERT INTO `categories` (`idCategory`, `name`) VALUES
(10, 'asdfasdfa'),
(8, 'ciao'),
(9, 'testCategory');

-- --------------------------------------------------------

--
-- Struttura della tabella `works`
--

CREATE TABLE `works` (
  `idWork` tinyint UNSIGNED NOT NULL,
  `idCategory` tinyint UNSIGNED DEFAULT NULL,
  `name` varchar(50) COLLATE utf8mb4_0900_as_cs NOT NULL,
  `date` date NOT NULL,
  `image_path` varchar(50) COLLATE utf8mb4_0900_as_cs NOT NULL DEFAULT 'images/',
  `languages` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_as_cs,
  `description` varchar(500) COLLATE utf8mb4_0900_as_cs NOT NULL DEFAULT 'Description'
) ;

--
-- Dump dei dati per la tabella `works`
--

INSERT INTO `works` (`idWork`, `idCategory`, `name`, `date`, `image_path`, `languages`, `description`) VALUES
(24, 8, 'test work one', '2025-01-23', 'uploads/images/img_679d148272fb42.82058974.png', '[\"JavaScript\"]', 'This is a Description'),
(25, 9, 'test work two', '2025-01-31', 'uploads/images/img_679d14a12e5398.66689129.png', '[\"Python\"]', 'this is another work'),
(26, 9, 'test work three', '2025-01-18', 'uploads/images/img_679d14b7595813.16453729.png', '[\"JavaScript\"]', 'this is another work'),
(29, 10, 'test', '2025-02-20', 'uploads/images/img_67a4dc3420f5f8.14134437.png', '[\"JavaScript\"]', 'asfdasfdasfdadsff'),
(30, 10, 'testhere', '2025-02-14', 'uploads/images/img_67a6701caa38a3.82578203.png', '[\"Java\",\"JavaScript\",\"Python\",\"C++\"]', 'fasfasfasfdasfas');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`idAdmin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indici per le tabelle `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`idCategory`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indici per le tabelle `works`
--
ALTER TABLE `works`
  ADD PRIMARY KEY (`idWork`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `idCategory_fk` (`idCategory`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `admins`
--
ALTER TABLE `admins`
  MODIFY `idAdmin` tinyint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT per la tabella `categories`
--
ALTER TABLE `categories`
  MODIFY `idCategory` tinyint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `works`
--
ALTER TABLE `works`
  MODIFY `idWork` tinyint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `works`
--
ALTER TABLE `works`
  ADD CONSTRAINT `idCategory_fk` FOREIGN KEY (`idCategory`) REFERENCES `categories` (`idCategory`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
