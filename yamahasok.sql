-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Ápr 15. 08:41
-- Kiszolgáló verziója: 10.4.28-MariaDB
-- PHP verzió: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `yamahasok`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `esemenyek`
--

CREATE TABLE `esemenyek` (
  `id` int(11) NOT NULL,
  `Idopont` date NOT NULL,
  `KepURL` varchar(255) DEFAULT NULL,
  `Helyszin` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `esemenyek`
--

INSERT INTO `esemenyek` (`id`, `Idopont`, `KepURL`, `Helyszin`) VALUES
(5, '2023-09-30', 'https://eu-prod.asyncgw.teams.microsoft.com/v1/objects/0-weu-d4-89dc0b67c0608b0aed23eca95bf3a669/views/imgo', '1. Yamaha Piknik'),
(6, '2024-04-20', 'https://eu-prod.asyncgw.teams.microsoft.com/v1/objects/0-weu-d2-dca005e5a608b81ab90fd7702d774bcb/views/imgo', '2. Yamaha Piknik'),
(7, '2024-07-13', 'https://eu-prod.asyncgw.teams.microsoft.com/v1/objects/0-frca-d6-03d7686afcd309ea553bf630affb49ef/views/imgo', '10. Yamaha Találkozó'),
(8, '2025-05-10', 'https://eu-prod.asyncgw.teams.microsoft.com/v1/objects/0-weu-d16-977a3454566e3a498ce145c6e5a761e7/views/imgo', '3. Yamaha Piknik');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `esemeny_resztvevok`
--

CREATE TABLE `esemeny_resztvevok` (
  `esemeny_id` int(11) NOT NULL,
  `felhasznalo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `kepek`
--

CREATE TABLE `kepek` (
  `id` int(11) NOT NULL,
  `feltolto_id` int(11) NOT NULL,
  `Datum` date NOT NULL,
  `KepURL` varchar(255) DEFAULT NULL,
  `approved` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `kepek`
--

INSERT INTO `kepek` (`id`, `feltolto_id`, `Datum`, `KepURL`, `approved`) VALUES
(7, 1, '2025-04-15', 'https://eu-prod.asyncgw.teams.microsoft.com/v1/objects/0-weu-d1-6ce135c52dc30f8095410ff0ea1d3cb1/views/imgo', 1),
(8, 1, '2025-04-15', 'https://eu-prod.asyncgw.teams.microsoft.com/v1/objects/0-weu-d19-72758641e89211ddd55755fd7914b636/views/imgo', 1),
(10, 1, '2025-04-15', 'https://eu-prod.asyncgw.teams.microsoft.com/v1/objects/0-weu-d3-6e0b47af2d86ab9717f935a9bc184bd8/views/imgo', 1),
(11, 70, '2025-04-15', 'https://eu-prod.asyncgw.teams.microsoft.com/v1/objects/0-weu-d13-d003259d1f50f862ccd98fc63926108e/views/imgo', 1),
(12, 70, '2025-04-15', 'https://eu-prod.asyncgw.teams.microsoft.com/v1/objects/0-weu-d1-84a551391b6a7dc645732e1f49ac4bbc/views/imgo', 1),
(13, 1, '2025-04-15', 'https://eu-prod.asyncgw.teams.microsoft.com/v1/objects/0-weu-d4-54758100a7b1293ab2efea70cf898228/views/imgo', 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `Vnev` varchar(255) NOT NULL,
  `Knev` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Telefon` varchar(20) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` tinyint(1) NOT NULL,
  `profile_pic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `Vnev`, `Knev`, `Username`, `Email`, `Telefon`, `Password`, `Role`, `profile_pic`) VALUES
(1, 'Admin', 'Admin', 'Admin123', 'admin123@gmail.com', '+36 70 2345678', '$2y$10$yAOzYbQEQlOyvKGMgriuX.FqWbIX3.IrV8u65GwWEupF5izz/BrOu', 1, 'profile_1.jpg'),
(70, 'asd', 'asd', 'asd', 'asd@gmail.com', '06704568779', '$2y$10$JWmoizq1sNLwgl7Q7KYdsuW7dEmXUuRziQ5dT03jS5fUe2REVtSVe', 0, NULL);

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `esemenyek`
--
ALTER TABLE `esemenyek`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `esemeny_resztvevok`
--
ALTER TABLE `esemeny_resztvevok`
  ADD PRIMARY KEY (`esemeny_id`,`felhasznalo_id`),
  ADD KEY `felhasznalo_id` (`felhasznalo_id`);

--
-- A tábla indexei `kepek`
--
ALTER TABLE `kepek`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feltolto_id` (`feltolto_id`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`Username`),
  ADD UNIQUE KEY `email` (`Email`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `esemenyek`
--
ALTER TABLE `esemenyek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT a táblához `kepek`
--
ALTER TABLE `kepek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `esemeny_resztvevok`
--
ALTER TABLE `esemeny_resztvevok`
  ADD CONSTRAINT `fk_esemeny_resztvevok_esemenyek` FOREIGN KEY (`esemeny_id`) REFERENCES `esemenyek` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_esemeny_resztvevok_users` FOREIGN KEY (`felhasznalo_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `kepek`
--
ALTER TABLE `kepek`
  ADD CONSTRAINT `fk_kepek_users` FOREIGN KEY (`feltolto_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
