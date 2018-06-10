-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Giu 10, 2018 alle 19:03
-- Versione del server: 10.2.14-MariaDB
-- Versione PHP: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sd_webserver`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `laps`
--

CREATE TABLE `laps` (
  `id` int(11) NOT NULL,
  `race_id` int(11) NOT NULL,
  `laptime` double NOT NULL,
  `fuel` double NOT NULL,
  `position` int(5) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `wettness` int(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `races`
--

CREATE TABLE `races` (
  `id` int(11) NOT NULL,
  `user_id` int(100) NOT NULL,
  `user_skill` int(4) NOT NULL,
  `track_id` varchar(100) NOT NULL,
  `car_id` varchar(100) NOT NULL,
  `type` int(2) NOT NULL,
  `setup` text NOT NULL,
  `startposition` int(5) NOT NULL,
  `endposition` int(5) DEFAULT NULL,
  `sdversion` varchar(100) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `img` varchar(100) NOT NULL,
  `nation` varchar(30) NOT NULL,
  `registrationdate` timestamp NOT NULL DEFAULT current_timestamp(),
  `sessionid` varchar(50) DEFAULT NULL,
  `sessionip` varchar(15) DEFAULT NULL,
  `sessiontimestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `laps`
--
ALTER TABLE `laps`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `races`
--
ALTER TABLE `races`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `laps`
--
ALTER TABLE `laps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `races`
--
ALTER TABLE `races`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
