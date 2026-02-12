-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2026 at 10:10 AM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `system`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `autobusy`
--

CREATE TABLE `autobusy` (
  `id_autobusu` int(11) NOT NULL,
  `rejestracja` varchar(10) NOT NULL,
  `marka` varchar(255) NOT NULL,
  `model` varchar(255) DEFAULT NULL,
  `liczba_miejsc` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `konta`
--

CREATE TABLE `konta` (
  `id_konta` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `haslo` varchar(255) NOT NULL,
  `rola` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pracownicy`
--

CREATE TABLE `pracownicy` (
  `id_pracownika` int(11) NOT NULL,
  `id_konta` int(11) NOT NULL,
  `imie` varchar(50) NOT NULL,
  `nazwisko` varchar(50) NOT NULL,
  `pesel` char(11) NOT NULL,
  `telefon` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `adres` varchar(255) NOT NULL,
  `stanowisko` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `rezerwacje`
--

CREATE TABLE `rezerwacje` (
  `id_rezerwacji` int(11) NOT NULL,
  `id_konta` int(11) NOT NULL,
  `imie_nazwisko` varchar(255) NOT NULL,
  `telefon` varchar(20) NOT NULL,
  `instytucja` varchar(255) DEFAULT NULL,
  `miasto_z` varchar(255) NOT NULL,
  `miasto_do` varchar(255) NOT NULL,
  `data_przejazdu` datetime NOT NULL,
  `godzina_powrotu` time DEFAULT NULL,
  `liczba_osob` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `data_utworzenia` date NOT NULL,
  `id_autobusu` int(11) NOT NULL,
  `id_pracownika` int(11) DEFAULT NULL,
  `cena` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id_uzytkownika` int(11) NOT NULL,
  `id_konta` int(11) NOT NULL,
  `imie` varchar(50) NOT NULL,
  `nazwisko` varchar(50) NOT NULL,
  `instytucja` varchar(100) NOT NULL,
  `telefon` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `adres` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `autobusy`
--
ALTER TABLE `autobusy`
  ADD PRIMARY KEY (`id_autobusu`);

--
-- Indeksy dla tabeli `konta`
--
ALTER TABLE `konta`
  ADD PRIMARY KEY (`id_konta`);

--
-- Indeksy dla tabeli `pracownicy`
--
ALTER TABLE `pracownicy`
  ADD PRIMARY KEY (`id_pracownika`),
  ADD KEY `FK_pracownicy_konta` (`id_konta`);

--
-- Indeksy dla tabeli `rezerwacje`
--
ALTER TABLE `rezerwacje`
  ADD PRIMARY KEY (`id_rezerwacji`),
  ADD KEY `FK_rezerwacje_autobusy` (`id_autobusu`),
  ADD KEY `FK_rezerwacje_pracownicy` (`id_pracownika`),
  ADD KEY `FK_rezerwacje_konta` (`id_konta`);

--
-- Indeksy dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id_uzytkownika`),
  ADD KEY `FK_uzytkownicy_konta` (`id_konta`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `autobusy`
--
ALTER TABLE `autobusy`
  MODIFY `id_autobusu` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `konta`
--
ALTER TABLE `konta`
  MODIFY `id_konta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pracownicy`
--
ALTER TABLE `pracownicy`
  MODIFY `id_pracownika` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rezerwacje`
--
ALTER TABLE `rezerwacje`
  MODIFY `id_rezerwacji` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id_uzytkownika` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pracownicy`
--
ALTER TABLE `pracownicy`
  ADD CONSTRAINT `FK_pracownicy_konta` FOREIGN KEY (`id_konta`) REFERENCES `konta` (`id_konta`);

--
-- Constraints for table `rezerwacje`
--
ALTER TABLE `rezerwacje`
  ADD CONSTRAINT `FK_rezerwacje_autobusy` FOREIGN KEY (`id_autobusu`) REFERENCES `autobusy` (`id_autobusu`),
  ADD CONSTRAINT `FK_rezerwacje_konta` FOREIGN KEY (`id_konta`) REFERENCES `konta` (`id_konta`),
  ADD CONSTRAINT `FK_rezerwacje_pracownicy` FOREIGN KEY (`id_pracownika`) REFERENCES `pracownicy` (`id_pracownika`);

--
-- Constraints for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD CONSTRAINT `FK_uzytkownicy_konta` FOREIGN KEY (`id_konta`) REFERENCES `konta` (`id_konta`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
