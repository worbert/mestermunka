-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Ápr 07. 10:23
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
(3, '2025-04-18', 'https://th.bing.com/th/id/R.e902cdb63741cce79fc34d3056c16fa3?rik=HG6%2bW%2bcsDW1xXQ&riu=http%3a%2f%2fwww.owl.hu%2fowl%2f3gallery%2fgownpic2011%2f2011_19_18q2.jpg&ehk=d1LzXXlpuZ%2fkAlKWaELoigM%2fczze2Z99V%2biytgVJO%2fg%3d&risl=&pid=ImgRaw&r=0', 'Kispest Hengersor'),
(4, '2025-04-11', 'https://th.bing.com/th/id/OIP.fYSoeo4R7tYuGo31YF13MwHaEK?rs=1&pid=ImgDetMain', 'Budapest');

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
(5, 1, '2025-04-02', 'https://th.bing.com/th/id/OIP.oivuIedwoVk4ixPFn7HACwHaEK?rs=1&pid=ImgDetMain', 1),
(7, 1, '2025-04-07', 'https://th.bing.com/th/id/OIP.7ekt1OgS0vfWkRWpWa4uKAHaFr?w=225&h=180&c=7&r=0&o=5&dpr=1.1&pid=1.7', 1),
(8, 70, '2025-04-07', 'https://th.bing.com/th?q=Motor+Clip+Art&w=120&h=120&c=1&rs=1&qlt=90&cb=1&dpr=1.1&pid=InlineBlock&mkt=en-WW&cc=HU&setlang=hu&adlt=strict&t=1&mw=247', 1);

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
(18, 'Példa', 'Ember', 'peldaember', 'peldaember@gmail.com', '06704568779', '$2y$10$BL7CcQsRX0T6gJzLcUPp9.9jpmInF3Fof.w9pD9ji26gvud7/PLQG', 1, NULL),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT a táblához `kepek`
--
ALTER TABLE `kepek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
