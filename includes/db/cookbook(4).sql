-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 11. Jan 2026 um 20:52
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `cookbook`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(260) DEFAULT NULL,
  `time_min` int(11) DEFAULT NULL,
  `servings` int(11) DEFAULT NULL,
  `steps` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `picture_path` varchar(255) DEFAULT '/img/placeholder_food.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Daten für Tabelle `recipes`
--

INSERT INTO `recipes` (`id`, `title`, `description`, `time_min`, `servings`, `steps`, `user_id`, `picture_path`) VALUES
(1, 'Spaghetti Carbonara', 'Klassische italienische Pasta mit Speck, Ei und Parmesan – schnell und cremig.', 20, 2, '1. Spaghetti nach Packungsanleitung kochen. \r\n2. Speck in einer Pfanne anbraten. \r\n3. Eier mit Parmesan verrühren. \r\n4. Spaghetti mit Speck mischen, Pfanne vom Herd nehmen und Eiermischung unterrühren. \r\n5. Mit Pfeffer abschmecken.', 2, '/uploads/recipes/recipe_1.jpg'),
(2, 'Kürbiscremesuppe', 'Cremige Suppe aus Hokkaido-Kürbis – perfekt für kalte Tage.', 35, 4, '1. Kürbis, Zwiebel und Knoblauch würfeln. \r\n2. Zwiebel und Knoblauch anschwitzen. \r\n3. Kürbis dazugeben und kurz dünsten. \r\n4. Mit Gemüsebrühe aufgießen und weich kochen. \r\n5. Pürieren und Sahne einrühren.', 2, '/uploads/recipes/recipe_2.jpg'),
(3, 'Rindergulasch mit Kartoffeln', 'Deftiges Schmorgericht mit zartem Rindfleisch und sämiger Sauce.', 120, 4, '1. Fleisch anbraten. \r\n2. Zwiebeln zugeben. \r\n3. Tomatenmark einrühren. \r\n4. Brühe angießen und schmoren lassen. \r\n5. Mit Kartoffeln servieren.', 3, '/uploads/recipes/recipe_3.jpg'),
(4, 'Caesar-Salat mit Hähnchen', 'Knackiger Salat mit Hähnchenstreifen, Croutons und cremigem Dressing.', 25, 2, '1. Hähnchen braten und in Streifen schneiden. \r\n2. Salat waschen. \r\n3. Croutons rösten. \r\n4. Dressing aus Joghurt, Zitrone, Senf, Knoblauch und Parmesan rühren. \r\n5. Alles mischen und servieren.', 3, '/uploads/recipes/recipe_4.jpg'),
(5, 'Overnight Oats mit Beeren', 'Schnelles und gesundes Frühstück mit Haferflocken, Joghurt und frischen Beeren.', 10, 1, 'Haferflocken mit Joghurt und Milch mischen\r\nHonig unterrühren\r\nBeeren und Nüsse darübergeben\r\nÜber Nacht im Kühlschrank ziehen lassen', 4, '/uploads/recipes/recipe_5.jpg'),
(8, 'Spaghetti Aglio e Olio', 'Schnelle Pasta mit Knoblauch, Chili und Olivenöl.', 15, 2, '1) Wasser salzen und Pasta kochen.\r\n2) Knoblauch in Öl anschwitzen.\r\n3) Chili dazu.\r\n4) Pasta schwenken und servieren.', 4, '/uploads/recipes/recipe_8.jpg'),
(9, 'Shakshuka', 'Würzige Tomaten-Paprika-Pfanne mit Eiern.', 25, 2, '1) Zwiebel und Paprika anbraten.\r\n2) Tomaten und Gewürze dazu.\r\n3) Eier in die Sauce geben.\r\n4) Stocken lassen.', 4, '/uploads/recipes/recipe_9.jpg'),
(10, 'Ofengemüse mit Feta', 'Buntes Ofengemüse, dazu Feta – super einfach.', 35, 3, '1) Gemüse schneiden.\r\n2) Mit Öl und Gewürzen mischen.\r\n3) 25–30 min backen.\r\n4) Feta darüberbröseln.', 4, '/uploads/recipes/recipe_10.jpg'),
(11, 'Linsensuppe', 'Sämige Linsensuppe mit Gemüse – perfekt zum Aufwärmen.', 40, 4, '1) Zwiebel anbraten.\r\n2) Linsen und Gemüse zugeben.\r\n3) Mit Brühe köcheln.\r\n4) Abschmecken und servieren.', 4, '/uploads/recipes/recipe_11.jpg'),
(12, 'Pancakes', 'Fluffige Pancakes fürs Frühstück.', 20, 2, '1) Zutaten verrühren.\r\n2) In Pfanne ausbacken.\r\n3) Wenden.\r\n4) Mit Topping servieren.', 4, '/uploads/recipes/recipe_12.jpg'),
(13, 'Tomatensalat', 'Klassisch mit Basilikum und Balsamico.', 10, 2, '1) Tomaten schneiden.\r\n2) Mit Öl und Balsamico mischen.\r\n3) Basilikum dazu.\r\n4) Abschmecken.', 2, '/uploads/recipes/recipe_13.jpg'),
(14, 'Chicken Wrap', 'Wraps mit Huhn, Salat und Joghurtsauce.', 25, 2, '1) Huhn anbraten.\r\n2) Sauce anrühren.\r\n3) Wrap belegen.\r\n4) Rollen und servieren.', 2, '/uploads/recipes/recipe_14.jpg'),
(15, 'Curryreis', 'Reis mit mildem Curry und Gemüse.', 30, 3, '1) Reis kochen.\r\n2) Gemüse anbraten.\r\n3) Curry dazu und kurz rösten.\r\n4) Mit Reis mischen.', 2, '/uploads/recipes/recipe_15.jpg'),
(16, 'Griechischer Joghurt Bowl', 'Schnelle Bowl mit Obst und Nüssen.', 8, 1, '1) Joghurt in Schüssel.\r\n2) Obst schneiden.\r\n3) Nüsse drüber.\r\n4) Optional Honig.', 2, '/uploads/recipes/recipe_16.jpg'),
(17, 'Käsespätzle', 'Spätzle mit Käse und Röstzwiebeln.', 25, 2, '1) Spätzle kochen.\r\n2) Zwiebeln rösten.\r\n3) Spätzle mit Käse schichten.\r\n4) Kurz ziehen lassen.', 2, '/uploads/recipes/recipe_17.jpg'),
(18, 'Chili sin Carne', 'Vegetarisches Chili mit Bohnen und Mais.', 45, 4, '1) Zwiebel anbraten.\r\n2) Bohnen, Mais, Tomaten zugeben.\r\n3) Würzen.\r\n4) Köcheln lassen.', 3, '/uploads/recipes/recipe_18.jpg'),
(19, 'Fried Rice', 'Gebratener Reis mit Ei und Gemüse.', 25, 2, '1) Reis vorkochen.\r\n2) Gemüse anbraten.\r\n3) Ei stocken lassen.\r\n4) Reis dazu und würzen.', 3, '/uploads/recipes/recipe_19.jpg'),
(20, 'Thunfisch Pasta', 'Schnelle Pasta mit Thunfisch-Tomatensauce.', 20, 2, '1) Pasta kochen.\r\n2) Zwiebel anbraten.\r\n3) Thunfisch + Tomaten dazu.\r\n4) Mit Pasta mischen.', 3, '/uploads/recipes/recipe_20.jpg'),
(21, 'Kartoffelgratin', 'Cremiges Gratin aus Kartoffeln.', 60, 4, '1) Kartoffeln schneiden.\r\n2) In Form schichten.\r\n3) Sahne würzen und drüber.\r\n4) Backen bis goldbraun.', 3, '/uploads/recipes/recipe_21.jpg'),
(22, 'Eierspeise', 'Rührei klassisch – schnell und gut.', 10, 1, '1) Eier verquirlen.\n2) In Pfanne stocken lassen.\n3) Salzen.\n4) Servieren.', 3, '/img/placeholder_food.jpg'),
(23, 'Gemüsepfanne', 'Bunte Pfanne mit Saison-Gemüse.', 20, 2, '1) Gemüse schneiden.\n2) In Pfanne anbraten.\n3) Würzen.\n4) Servieren.', 4, '/img/placeholder_food.jpg'),
(24, 'Bolognese', 'Klassische Bolognese mit Rinderhack.', 50, 4, '1) Zwiebel anbraten.\n2) Hack dazu.\n3) Tomaten und Gewürze.\n4) Köcheln lassen.', 4, '/img/placeholder_food.jpg'),
(25, 'Caprese Sandwich', 'Sandwich mit Mozzarella, Tomate und Basilikum.', 10, 1, '1) Brot schneiden.\n2) Belegen.\n3) Würzen.\n4) Servieren.', 4, '/img/placeholder_food.jpg'),
(26, 'Haferflocken Porridge', 'Warm, sättigend, perfekt morgens.', 10, 1, '1) Milch erhitzen.\n2) Haferflocken einrühren.\n3) Kurz köcheln.\n4) Topping drauf.', 4, '/img/placeholder_food.jpg'),
(27, 'Schnelles Couscous', 'Couscous mit Gemüse und Zitrone.', 15, 2, '1) Couscous mit heißem Wasser quellen.\n2) Gemüse schneiden.\n3) Alles mischen.\n4) Mit Zitrone abschmecken.', 4, '/img/placeholder_food.jpg');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `recipe_ingredients`
--

CREATE TABLE `recipe_ingredients` (
  `recipe_id` int(11) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `quantity` int(20) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Daten für Tabelle `recipe_ingredients`
--

INSERT INTO `recipe_ingredients` (`recipe_id`, `unit`, `quantity`, `name`) VALUES
(22, 'Stk', 3, 'Eier'),
(22, 'EL', 1, 'Butter'),
(22, 'Prise', 1, 'Salz'),
(22, 'Prise', 1, 'Pfeffer'),
(22, 'EL', 1, 'Milch'),
(23, 'Stk', 1, 'Zucchini'),
(23, 'Stk', 1, 'Paprika'),
(23, 'Stk', 1, 'Karotte'),
(23, 'EL', 1, 'Öl'),
(23, 'Prise', 1, 'Salz'),
(24, 'g', 400, 'Rinderhack'),
(24, 'Stk', 1, 'Zwiebel'),
(24, 'g', 500, 'Passierte Tomaten'),
(24, 'TL', 1, 'Oregano'),
(24, 'Prise', 1, 'Salz'),
(25, 'Stk', 2, 'Brotscheiben'),
(25, 'g', 80, 'Mozzarella'),
(25, 'Stk', 1, 'Tomate'),
(25, 'EL', 1, 'Olivenöl'),
(25, 'Prise', 1, 'Salz'),
(26, 'g', 60, 'Haferflocken'),
(26, 'ml', 250, 'Milch'),
(26, 'Prise', 1, 'Salz'),
(26, 'TL', 1, 'Honig'),
(26, 'Stk', 1, 'Banane'),
(27, 'g', 150, 'Couscous'),
(27, 'ml', 200, 'Wasser'),
(27, 'Stk', 1, 'Gurke'),
(27, 'EL', 1, 'Olivenöl'),
(27, 'Stk', 1, 'Zitrone'),
(5, 'g', 50, 'Haferflocken'),
(5, 'g', 100, 'Joghurt'),
(5, 'ml', 50, 'Milch'),
(5, 'EL', 1, 'Honig'),
(5, 'g', 50, 'Beeren'),
(1, 'g', 200, 'Spaghetti'),
(1, 'g', 100, 'Speck'),
(1, 'Stk', 2, 'Eier'),
(1, 'g', 50, 'Parmesan'),
(1, 'Prise', 1, 'Pfeffer'),
(2, 'g', 600, 'Hokkaido-Kürbis'),
(2, 'Stk', 1, 'Zwiebel'),
(2, 'Stk', 1, 'Knoblauchzehe'),
(2, 'ml', 700, 'Gemüsebrühe'),
(2, 'ml', 100, 'Sahne'),
(2, 'g', 20, 'Kürbiskerne'),
(2, 'Prise', 1, 'Salz'),
(13, 'Stk', 3, 'Tomaten'),
(13, 'EL', 2, 'Olivenöl'),
(13, 'EL', 1, 'Balsamico'),
(13, 'Prise', 1, 'Salz'),
(13, 'Prise', 1, 'Pfeffer'),
(14, 'Stk', 2, 'Wraps'),
(14, 'g', 250, 'Hühnerbrust'),
(14, 'g', 80, 'Salat'),
(14, 'g', 150, 'Joghurt'),
(14, 'Prise', 1, 'Salz'),
(15, 'g', 250, 'Reis'),
(15, 'Stk', 1, 'Zwiebel'),
(15, 'Stk', 1, 'Karotte'),
(15, 'TL', 2, 'Currypulver'),
(15, 'EL', 1, 'Öl'),
(16, 'g', 250, 'Griechischer Joghurt'),
(16, 'Stk', 1, 'Banane'),
(16, 'g', 50, 'Beeren'),
(16, 'g', 20, 'Nüsse'),
(16, 'TL', 1, 'Honig'),
(17, 'g', 300, 'Spätzle'),
(17, 'g', 150, 'Käse'),
(17, 'Stk', 1, 'Zwiebel'),
(17, 'EL', 1, 'Butter'),
(17, 'Prise', 1, 'Pfeffer'),
(3, 'g', 600, 'Rindfleisch'),
(3, 'Stk', 2, 'Zwiebel'),
(3, 'EL', 2, 'Tomatenmark'),
(3, 'ml', 500, 'Rinderbrühe'),
(3, 'g', 600, 'Kartoffeln'),
(3, 'Prise', 1, 'Salz'),
(4, 'g', 200, 'Hähnchenbrust'),
(4, 'Stk', 2, 'Salatherzen'),
(4, 'g', 80, 'Brotwürfel'),
(4, 'g', 150, 'Joghurt'),
(4, 'EL', 2, 'Zitronensaft'),
(4, 'Stk', 1, 'Knoblauch'),
(4, 'g', 30, 'Parmesan'),
(18, 'g', 400, 'Kidneybohnen'),
(18, 'g', 200, 'Mais'),
(18, 'g', 400, 'Dosentomaten'),
(18, 'Stk', 1, 'Zwiebel'),
(18, 'TL', 1, 'Chilipulver'),
(19, 'g', 250, 'Reis'),
(19, 'Stk', 2, 'Eier'),
(19, 'Stk', 1, 'Karotte'),
(19, 'EL', 1, 'Sojasauce'),
(19, 'EL', 1, 'Öl'),
(20, 'g', 200, 'Pasta'),
(20, 'g', 150, 'Thunfisch'),
(20, 'g', 300, 'Dosentomaten'),
(20, 'Stk', 1, 'Zwiebel'),
(20, 'Prise', 1, 'Salz'),
(21, 'g', 900, 'Kartoffeln'),
(21, 'ml', 300, 'Sahne'),
(21, 'g', 80, 'Käse'),
(21, 'Stk', 1, 'Knoblauchzehe'),
(21, 'Prise', 1, 'Muskat'),
(8, 'g', 200, 'Spaghetti'),
(8, 'EL', 3, 'Olivenöl'),
(8, 'Stk', 2, 'Knoblauchzehen'),
(8, 'Prise', 1, 'Chiliflocken'),
(8, 'Prise', 1, 'Salz'),
(9, 'Stk', 3, 'Eier'),
(9, 'g', 400, 'Dosentomaten'),
(9, 'Stk', 1, 'Paprika'),
(9, 'Stk', 1, 'Zwiebel'),
(9, 'TL', 1, 'Paprikapulver'),
(12, 'g', 180, 'Mehl'),
(12, 'ml', 250, 'Milch'),
(12, 'Stk', 1, 'Ei'),
(12, 'TL', 2, 'Backpulver'),
(12, 'Prise', 1, 'Salz'),
(10, 'Stk', 2, 'Karotten'),
(10, 'Stk', 1, 'Zucchini'),
(10, 'Stk', 1, 'Paprika'),
(10, 'g', 150, 'Feta'),
(10, 'EL', 2, 'Olivenöl'),
(11, 'g', 250, 'Linsen'),
(11, 'Stk', 1, 'Zwiebel'),
(11, 'Stk', 2, 'Karotten'),
(11, 'ml', 900, 'Gemüsebrühe'),
(11, 'Prise', 1, 'Salz');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `recipe_tags`
--

CREATE TABLE `recipe_tags` (
  `recipe_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Daten für Tabelle `recipe_tags`
--

INSERT INTO `recipe_tags` (`recipe_id`, `tag_id`) VALUES
(23, 4),
(23, 6),
(23, 26),
(23, 24),
(23, 21),
(27, 3),
(27, 6),
(27, 26),
(27, 24),
(27, 20),
(24, 4),
(24, 6),
(24, 12),
(24, 22),
(22, 2),
(22, 10),
(22, 21),
(22, 24),
(25, 10),
(25, 3),
(25, 20),
(25, 26),
(25, 24),
(25, 21),
(26, 2),
(26, 26),
(26, 21),
(26, 24),
(5, 2),
(5, 10),
(5, 21),
(5, 26),
(1, 4),
(1, 6),
(1, 12),
(1, 22),
(1, 24),
(2, 4),
(2, 5),
(2, 8),
(2, 21),
(2, 26),
(13, 5),
(13, 9),
(13, 20),
(13, 21),
(13, 24),
(13, 26),
(14, 3),
(14, 6),
(14, 22),
(14, 24),
(15, 3),
(15, 6),
(15, 14),
(15, 22),
(15, 26),
(16, 2),
(16, 10),
(16, 21),
(16, 24),
(16, 26),
(17, 4),
(17, 6),
(17, 16),
(17, 22),
(17, 26),
(3, 4),
(3, 6),
(3, 16),
(3, 22),
(4, 3),
(4, 6),
(4, 9),
(4, 22),
(4, 24),
(18, 4),
(18, 6),
(18, 15),
(18, 22),
(18, 25),
(19, 3),
(19, 6),
(19, 13),
(19, 22),
(19, 24),
(20, 4),
(20, 6),
(20, 12),
(20, 22),
(20, 24),
(21, 4),
(21, 7),
(21, 17),
(21, 22),
(8, 4),
(8, 6),
(8, 12),
(8, 21),
(8, 24),
(8, 25),
(9, 4),
(9, 6),
(9, 19),
(9, 22),
(9, 26),
(12, 2),
(12, 11),
(12, 21),
(10, 4),
(10, 6),
(10, 20),
(10, 21),
(10, 26),
(11, 4),
(11, 8),
(11, 21),
(11, 25);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `shopping_list`
--

CREATE TABLE `shopping_list` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Daten für Tabelle `tags`
--

INSERT INTO `tags` (`id`, `name`, `category`) VALUES
(2, 'Frühstück', 'Mahlzeit'),
(3, 'Mittagessen', 'Mahlzeit'),
(4, 'Abendessen', 'Mahlzeit'),
(5, 'Vorspeise', 'Gang'),
(6, 'Hauptgericht', 'Gang'),
(7, 'Beilage', 'Gang'),
(8, 'Suppe', 'Gang'),
(9, 'Salat', 'Gang'),
(10, 'Snack', 'Gang'),
(11, 'Dessert', 'Gang'),
(12, 'Italienisch', 'Küche'),
(13, 'Asiatisch', 'Küche'),
(14, 'Indisch', 'Küche'),
(15, 'Mexikanisch', 'Küche'),
(16, 'Österreichisch', 'Küche'),
(17, 'Deutsch', 'Küche'),
(18, 'Französisch', 'Küche'),
(19, 'Orientalisch', 'Küche'),
(20, 'Mediterran', 'Küche'),
(21, 'Einfach', 'Schwierigkeit'),
(22, 'Mittel', 'Schwierigkeit'),
(23, 'Anspruchsvoll', 'Schwierigkeit'),
(24, 'Schnelle Küche', 'Besonderheiten'),
(25, 'Vegan', 'Besonderheiten'),
(26, 'Vegetarisch', 'Besonderheiten'),
(27, 'Glutenfrei', 'Besonderheiten'),
(28, 'Laktosefrei', 'Besonderheiten'),
(29, 'Low-Carb', 'Besonderheiten'),
(30, 'Proteinreich', 'Besonderheiten');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `password` varchar(80) NOT NULL,
  `role` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `name`, `password`, `role`, `email`) VALUES
(1, 'Admin', '$2y$10$EvW2N9qjHf9If662mPblSOUL2VS.2ezu0NrJLIEAAIjz4C5TbmHSi', 'admin', 'admin@cookbook.local'),
(2, 'Julia', '$2y$10$mkCw6xgQuqEilcW/m5YYWenYoYtk0J46D7aUvD2t8AQT6LvC9wLa2', 'user', 'julia@cookbook.local'),
(3, 'Markus', '$2y$10$Ctmg28WMB/5Y/Qgxq3kubO6dFyCig181HHnx8.LO8bISpBRSTtHP2', 'user', 'markus@cookbook.local'),
(4, 'Anna', '$2y$10$sRITzIYy6YKFuBOlXENILe3lskN2WG1wz1Dnut.MG0wuhBMsh7aAS', 'user', 'anna@cookbook.local');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_favorites`
--

CREATE TABLE `user_favorites` (
  `user_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `user_favorites`
--

INSERT INTO `user_favorites` (`user_id`, `recipe_id`, `created_at`) VALUES
(2, 1, '2026-01-11 09:38:02'),
(2, 3, '2026-01-11 09:38:02'),
(3, 2, '2026-01-11 09:38:02'),
(3, 5, '2026-01-11 09:38:02'),
(4, 1, '2026-01-11 09:38:02'),
(4, 2, '2026-01-11 10:00:41'),
(4, 3, '2026-01-11 10:03:33'),
(4, 4, '2026-01-11 09:38:02');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_recipes_user` (`user_id`);

--
-- Indizes für die Tabelle `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD KEY `fk_ingredients_recipe` (`recipe_id`);

--
-- Indizes für die Tabelle `recipe_tags`
--
ALTER TABLE `recipe_tags`
  ADD KEY `fk_recipe_tags_recipe` (`recipe_id`),
  ADD KEY `fk_recipe_tags_tag` (`tag_id`);

--
-- Indizes für die Tabelle `shopping_list`
--
ALTER TABLE `shopping_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_item` (`user_id`,`name`,`unit`);

--
-- Indizes für die Tabelle `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD PRIMARY KEY (`user_id`,`recipe_id`),
  ADD KEY `fk_favorites_recipe` (`recipe_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT für Tabelle `shopping_list`
--
ALTER TABLE `shopping_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT für Tabelle `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `fk_recipes_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD CONSTRAINT `fk_ingredients_recipe` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recipe_ingredients_recipe` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `recipe_tags`
--
ALTER TABLE `recipe_tags`
  ADD CONSTRAINT `fk_recipe_tags_recipe` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_recipe_tags_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recipe_id` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tag_id` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `shopping_list`
--
ALTER TABLE `shopping_list`
  ADD CONSTRAINT `fk_shopping_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD CONSTRAINT `fk_fav_recipe` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_fav_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_favorites_recipe` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_favorites_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
