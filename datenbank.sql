-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Gép: localhost
-- Létrehozás ideje: 2016. Máj 08. 00:54
-- Kiszolgáló verziója: 10.1.9-MariaDB
-- PHP verzió: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `info2`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `category`
--

CREATE TABLE `category` (
  `no` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `user` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `category`
--

INSERT INTO `category` (`no`, `name`, `user`) VALUES
(0, '-', 0),
(2, 'Lebensmittel', 0),
(3, 'Sport', 0),
(4, 'Wohnen', 0),
(5, 'Stipendium', 0),
(12, 'Bezahlung', 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `category_detect`
--

CREATE TABLE `category_detect` (
  `no` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `pattern` varchar(100) NOT NULL,
  `user` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `category_detect`
--

INSERT INTO `category_detect` (`no`, `category`, `pattern`, `user`) VALUES
(1, 2, '/spar/', 0),
(2, 5, '/muszaki/', 0),
(3, 2, '/stoczek/', 0),
(9, 4, '/schönherz/', 1),
(10, 3, '/(.*)uszoda(.*)/i', 1),
(11, 12, '/munka/', 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `expense`
--

CREATE TABLE `expense` (
  `no` int(11) NOT NULL,
  `description` varchar(100) NOT NULL,
  `category` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `date` date NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `expense`
--

INSERT INTO `expense` (`no`, `description`, `category`, `amount`, `date`, `user`) VALUES
(93, '', 0, -9700, '2016-02-20', 1),
(89, '', 0, -9600, '2016-04-08', 1),
(58, '', 0, -9500, '2016-01-21', 1),
(54, '', 0, -8700, '2015-12-03', 1),
(102, '', 0, -8400, '2015-12-13', 1),
(103, '', 0, -8300, '2015-12-24', 1),
(113, '', 0, -7900, '2016-04-15', 1),
(64, '', 0, -7800, '2016-01-18', 1),
(82, '', 0, -7700, '2016-04-30', 1),
(81, '', 0, -7300, '2015-12-07', 1),
(95, '', 0, -7300, '2016-05-01', 1),
(91, '', 0, -7200, '2016-02-09', 1),
(78, '', 0, -7100, '2016-02-11', 1),
(66, '', 0, -7100, '2016-03-10', 1),
(63, '', 0, -7000, '2016-04-27', 1),
(106, '', 0, -6900, '2016-05-01', 1),
(100, '', 0, -6400, '2015-12-28', 1),
(84, '', 0, -6400, '2016-02-20', 1),
(110, '', 0, -6300, '2016-01-27', 1),
(98, '', 0, -6000, '2016-02-29', 1),
(107, '', 0, -5900, '2016-02-20', 1),
(80, '', 0, -5600, '2016-02-07', 1),
(112, '', 0, -5600, '2016-04-08', 1),
(108, '', 0, -5500, '2016-03-24', 1),
(70, '', 0, -5400, '2016-02-12', 1),
(59, '', 0, -5100, '2015-12-18', 1),
(67, '', 0, -5100, '2016-02-10', 1),
(97, '', 0, -4800, '2016-01-04', 1),
(88, '', 0, -4800, '2016-01-11', 1),
(73, '', 0, -4600, '2016-02-01', 1),
(86, '', 0, -4600, '2016-03-06', 1),
(90, '', 0, -4600, '2016-04-26', 1),
(62, '', 0, -4500, '2015-12-18', 1),
(77, '', 0, -4100, '2015-12-30', 1),
(105, '', 0, -3900, '2016-01-17', 1),
(109, '', 0, -3700, '2016-02-12', 1),
(74, '', 0, -3300, '2016-04-08', 1),
(83, '', 0, -3100, '2016-02-10', 1),
(68, '', 0, -3000, '2016-01-06', 1),
(94, '', 0, -2900, '2016-03-31', 1),
(69, '', 0, -2800, '2016-02-23', 1),
(52, '', 0, -2700, '2015-12-01', 1),
(76, '', 0, -2700, '2016-01-23', 1),
(104, '', 0, -2100, '2016-03-15', 1),
(53, '', 0, -2000, '2015-12-02', 1),
(61, '', 0, -2000, '2016-04-28', 1),
(87, '', 0, -1900, '2015-12-22', 1),
(60, '', 0, -1500, '2016-01-07', 1),
(57, '', 0, -1200, '2016-01-28', 1),
(72, '', 0, -900, '2016-04-04', 1),
(99, '', 0, -700, '2016-04-29', 1),
(56, '', 0, -500, '2016-04-20', 1),
(111, '', 0, -400, '2016-04-02', 1),
(101, '', 0, -200, '2016-01-24', 1),
(55, '', 0, 0, '2016-01-25', 1),
(164, '', 0, 0, '2016-05-08', 1),
(121, 'BME Schönherz Zoltán Kollégium', 4, -9320, '2015-12-05', 1),
(122, 'BME Schönherz Zoltán Kollégium', 4, -9320, '2016-01-05', 1),
(123, 'BME Schönherz Zoltán Kollégium', 4, -9320, '2016-02-05', 1),
(124, 'BME Schönherz Zoltán Kollégium', 4, -9320, '2016-03-05', 1),
(125, 'BME Schönherz Zoltán Kollégium', 4, -9320, '2016-04-05', 1),
(126, 'BME Schönherz Zoltán Kollégium', 4, -9320, '2016-05-05', 1),
(115, 'Budapesti Műszaki és Gazdaságtudományi Egyetem', 5, 12000, '2015-12-05', 1),
(116, 'Budapesti Műszaki és Gazdaságtudományi Egyetem', 5, 12000, '2016-01-05', 1),
(117, 'Budapesti Műszaki és Gazdaságtudományi Egyetem', 5, 12000, '2016-02-05', 1),
(118, 'Budapesti Műszaki és Gazdaságtudományi Egyetem', 5, 12000, '2016-03-05', 1),
(119, 'Budapesti Műszaki és Gazdaságtudományi Egyetem', 5, 12000, '2016-04-05', 1),
(120, 'Budapesti Műszaki és Gazdaságtudományi Egyetem', 5, 12000, '2016-05-05', 1),
(127, 'gyakornoki munka', 12, 100000, '2015-12-05', 1),
(128, 'gyakornoki munka', 12, 100000, '2016-01-05', 1),
(129, 'gyakornoki munka', 12, 100000, '2016-02-05', 1),
(130, 'gyakornoki munka', 12, 100000, '2016-03-05', 1),
(131, 'gyakornoki munka', 12, 100000, '2016-04-05', 1),
(132, 'gyakornoki munka', 12, 100000, '2016-05-05', 1),
(136, 'neu', 12, 0, '2016-05-07', 1),
(1, 'spar', 2, -9200, '2016-01-31', 1),
(13, 'spar', 2, -8600, '2015-12-28', 1),
(28, 'spar', 2, -8300, '2016-02-24', 1),
(16, 'spar', 2, -8100, '2016-01-11', 1),
(14, 'spar', 2, -8100, '2016-03-01', 1),
(6, 'spar', 2, -7900, '2015-12-27', 1),
(5, 'spar', 2, -7600, '2015-12-13', 1),
(31, 'spar', 2, -6600, '2015-12-01', 1),
(10, 'spar', 2, -6200, '2016-01-25', 1),
(8, 'spar', 2, -6200, '2016-02-25', 1),
(17, 'spar', 2, -5600, '2016-04-02', 1),
(29, 'spar', 2, -5300, '2016-04-19', 1),
(15, 'spar', 2, -5100, '2016-02-19', 1),
(35, 'spar', 2, -5100, '2016-04-30', 1),
(24, 'spar', 2, -4900, '2015-12-31', 1),
(9, 'spar', 2, -4600, '2016-01-12', 1),
(23, 'spar', 2, -4200, '2016-03-17', 1),
(7, 'spar', 2, -3300, '2016-04-04', 1),
(30, 'spar', 2, -3300, '2016-04-12', 1),
(26, 'spar', 2, -3200, '2016-01-15', 1),
(34, 'spar', 2, -3100, '2016-01-03', 1),
(11, 'spar', 2, -3000, '2016-03-06', 1),
(22, 'spar', 2, -2800, '2016-01-01', 1),
(32, 'spar', 2, -2800, '2016-03-15', 1),
(27, 'spar', 2, -2500, '2015-12-25', 1),
(18, 'spar', 2, -2100, '2016-03-27', 1),
(20, 'spar', 2, -1700, '2016-01-16', 1),
(25, 'spar', 2, -1500, '2016-02-27', 1),
(3, 'spar', 2, -1300, '2016-04-15', 1),
(2, 'spar', 2, -900, '2015-12-08', 1),
(21, 'spar', 2, -400, '2016-02-08', 1),
(19, 'spar', 2, -400, '2016-03-01', 1),
(4, 'spar', 2, -200, '2016-01-30', 1),
(43, 'uszoda', 3, -1600, '2016-04-28', 1),
(51, 'uszoda', 3, -1500, '2015-12-04', 1),
(50, 'uszoda', 3, -1500, '2015-12-18', 1),
(38, 'uszoda', 3, -1500, '2015-12-20', 1),
(47, 'uszoda', 3, -1500, '2015-12-21', 1),
(39, 'uszoda', 3, -1500, '2015-12-27', 1),
(41, 'uszoda', 3, -1500, '2016-01-03', 1),
(46, 'uszoda', 3, -1500, '2016-01-06', 1),
(36, 'uszoda', 3, -1500, '2016-01-24', 1),
(44, 'uszoda', 3, -1500, '2016-01-25', 1),
(40, 'uszoda', 3, -1500, '2016-03-06', 1),
(48, 'uszoda', 3, -1500, '2016-03-18', 1),
(37, 'uszoda', 3, -1500, '2016-04-09', 1),
(49, 'uszoda', 3, -1500, '2016-04-25', 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `user`
--

CREATE TABLE `user` (
  `no` int(11) NOT NULL,
  `user` varchar(50) NOT NULL,
  `password` char(40) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `user`
--

INSERT INTO `user` (`no`, `user`, `password`, `name`, `email`) VALUES
(0, 'nulluser', '2be88ca4242c76e8253ac62474851065032d6833', 'Null User', 'null@app.com'),
(1, 'gipszjakab', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Gipsz Jakab', 'gipsz@jakab.hu');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`no`);

--
-- A tábla indexei `category_detect`
--
ALTER TABLE `category_detect`
  ADD PRIMARY KEY (`no`);

--
-- A tábla indexei `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`no`),
  ADD UNIQUE KEY `duplicate_prevent` (`description`,`category`,`amount`,`date`,`user`);

--
-- A tábla indexei `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`no`),
  ADD UNIQUE KEY `user` (`user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `category`
--
ALTER TABLE `category`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT a táblához `category_detect`
--
ALTER TABLE `category_detect`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT a táblához `expense`
--
ALTER TABLE `expense`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;
--
-- AUTO_INCREMENT a táblához `user`
--
ALTER TABLE `user`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
