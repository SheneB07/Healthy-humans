-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 06 mrt 2026 om 12:10
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `happy_herbivore`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`) VALUES
(1, 'Breakfast', 'Breakfast'),
(2, 'Lunch&dinner', 'Lunch & Dinner'),
(3, 'Sides', 'Sides'),
(4, 'Handhelds', 'Handhelds'),
(5, 'Dips', 'Dips'),
(6, 'Drinks', 'Drinks');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `images`
--

CREATE TABLE `images` (
  `image_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `images`
--

INSERT INTO `images` (`image_id`, `filename`, `description`) VALUES
(1, 'acai-bowl.webp', 'Breakfast item 1'),
(2, 'garden-breakfast.webp', 'Breakfast item 2'),
(3, 'toast.webp', 'Breakfast item 3'),
(4, 'tofu-bowl.webp', 'Lunch & Dinner item 1'),
(5, 'supergreen-harvest.webp', 'Lunch & Dinner item 2'),
(6, 'falafel-bowl.webp', 'Lunch & Dinner item 3'),
(7, 'potato-wedges.webp', 'Sides item 1'),
(8, 'zucchini-fries.webp', 'Sides item 2'),
(9, 'falafel-bites.webp', 'Sides item 3'),
(10, 'veggie-platter.webp', 'Sides item 4'),
(11, 'hummus-wrap.webp', 'Handhelds item 1'),
(12, 'avocado-toastie.webp', 'Handhelds item 2'),
(13, 'jackfruit-slider.webp', 'Handhelds item 3'),
(18, 'hummus.webp', 'Dips item 1'),
(19, 'lime-crema.webp', 'Dips item 2'),
(20, 'yoghurt-ranch.webp', 'Dips item 3'),
(21, 'sriracha-mayo.webp', 'Dips item 4'),
(22, 'satay-sauce.webp', 'Dips item 5'),
(25, 'green-smoothie.webp', 'Drink item 1'),
(26, 'matcha-latte.webp', 'Drink item 2'),
(27, 'fruit-water.webp', 'Drink item 3'),
(28, 'berry-smoothie.webp', 'Drink item 4'),
(29, 'citrus-cooler.webp', 'Drink item 5'),
(30, 'oats.webp', 'Breakfast item 4'),
(31, 'tempeh-bowl.webp', 'Lunch & Dinner item 4\r\n');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_status_id` int(11) NOT NULL,
  `pickup_number` tinyint(4) NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `datetime` datetime NOT NULL,
  `ordered_product` int(11) NOT NULL,
  `dineChoice` varchar(2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `order_status`
--

CREATE TABLE `order_status` (
  `order_status_id` int(11) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `order_status`
--

INSERT INTO `order_status` (`order_status_id`, `description`) VALUES
(1, 'Start order'),
(2, 'Placed and paid'),
(3, 'Preparing'),
(4, 'Ready for pickup'),
(5, 'Picked up');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `kcal` varchar(25) NOT NULL,
  `available` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tabelstructuur voor tabel `translations`
--

CREATE TABLE `translations` (
  `id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL,
  `key` varchar(191) NOT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `image_id`, `name`, `description`, `price`, `kcal`, `available`) VALUES
(1, 1, 1, 'Morning Boost Açaí Bowl', 'A chilled blend of açaí and banana topped with crunchy granola, chia seeds, and coconut.', 7.50, '320', 1),
(2, 1, 2, 'The Garden Breakfast Wrap', 'Whole-grain wrap with fluffy scrambled eggs, baby spinach, and a light yogurt-herb sauce.', 6.50, '280', 1),
(3, 1, 3, 'Peanut Butter & Cacao Toast', 'Sourdough toast with 100% natural peanut butter, banana, and a sprinkle of cacao nibs.', 5.00, '240', 1),
(4, 2, 4, 'Tofu Power Tahini Bowl', 'Tri-color quinoa, maple-glazed tofu, roasted sweet potatoes, and kale with tahini dressing.', 10.50, '480', 1),
(5, 2, 5, 'The Supergreen Harvest', 'Massaged kale, edamame, avocado, cucumber, and toasted pumpkin seeds with lemon-olive oil.', 9.50, '310', 1),
(6, 2, 6, 'Mediterranean Falafel Bowl', 'Baked falafel, hummus, pickled red onions, cherry tomatoes, and cucumber on a bed of greens.', 10.00, '440', 1),
(7, 3, 7, 'Oven-Baked Sweet Potato Wedges', 'Seasoned with smoked paprika. (Best with Avocado Lime Dip).', 4.50, '260', 1),
(8, 3, 8, 'Zucchini Fries', 'Crispy breaded zucchini sticks. (Best with Greek Yogurt Ranch).', 4.50, '190', 1),
(9, 3, 9, 'Baked Falafel Bites - 5pc', '', 5.00, '230', 1),
(10, 3, 10, 'Mini Veggie Platter & Hummus', 'Fresh crunch: Celery, carrots, and cucumber.', 4.00, '160', 1),
(11, 4, 11, 'Zesty Chickpea Hummus Wrap', 'Spiced chickpeas, shredded carrots, crisp lettuce, and signature hummus in a whole-wheat wrap.', 8.50, '410', 1),
(12, 4, 12, 'Avocado & Halloumi Toastie', 'Grilled halloumi cheese, smashed avocado, and chili flakes on thick-cut multi-grain bread.', 9.00, '460', 1),
(13, 4, 13, 'Smoky BBQ Jackfruit Slider', 'Pulled jackfruit in BBQ sauce with a crunchy purple slaw on a vegan brioche bun.', 7.50, '350', 1),
(18, 5, 18, 'Classic Hummus', '', 1.00, '120', 1),
(19, 5, 19, 'Avocado Lime Dip', '', 1.00, '110', 1),
(20, 5, 20, 'Greek Yogurt Ranch', '', 1.00, '90', 1),
(21, 5, 21, 'Spicy Sriracha Mayo', '', 1.00, '180', 1),
(22, 5, 22, 'Peanut Satay Sauce', '', 1.00, '200', 1),
(25, 6, 25, 'Green Glow Smoothie', 'Spinach, pineapple, cucumber, and coconut water.', 3.50, '120', 1),
(26, 6, 26, 'Iced Matcha Latte', 'Lightly sweetened matcha green tea with almond milk.', 3.00, '90', 1),
(27, 6, 27, 'Fruit-Infused Water', 'Freshly infused water with a choice of lemon-mint, strawberry-basil, or cucumber-lime.', 1.50, '0', 1),
(28, 6, 28, 'Berry Blast Smoothie', 'A creamy blend of strawberries, blueberries, and raspberries with almond milk.', 3.80, '140 ', 1),
(29, 6, 29, 'Citrus Cooler', 'A refreshing mix of orange juice, sparkling water, and a hint of lime.', 3.00, '90 ', 1),
(31, 1, 3, 'Overnight Oats: Apple Pie Style', 'Oats soaked in almond milk with grated apple, cinnamon, and crushed walnuts.', 5.50, '290', 1),
(32, 2, 1, 'Warm Teriyaki Tempeh Bowl ', 'Steamed brown rice, seared tempeh, broccoli, and shredded carrots with a ginger-soy glaze.', 11.00, '500', 1);

--
-- Gegevens worden geëxporteerd voor tabel `translations`
--

INSERT INTO `translations` (`id`, `language`, `key`, `text`) VALUES
(1, 'en', 'start.language.english', 'English'),
(2, 'en', 'start.language.dutch', 'Nederlands'),
(3, 'en', 'start.dine_in', 'Dine In'),
(4, 'en', 'start.take_out', 'Take Out'),
(5, 'en', 'menu.header_title', 'Breakfast'),
(6, 'en', 'menu.category.full_menu', 'Full menu'),
(7, 'en', 'menu.category.breakfast', 'Breakfast'),
(8, 'en', 'menu.category.lunch_dinner', 'Lunch & Dinner'),
(9, 'en', 'menu.category.handhelds', 'Handhelds'),
(10, 'en', 'menu.category.sides', 'Sides'),
(11, 'en', 'menu.category.dips', 'Dips'),
(12, 'en', 'menu.category.drinks', 'Drinks'),
(13, 'en', 'product.kcal_suffix', 'kcal'),
(14, 'en', 'product.back_to_menu', 'Back to menu'),
(15, 'en', 'product.add_to_cart', 'Add to Cart'),
(16, 'en', 'cart.title', 'Your Order'),
(17, 'en', 'cart.calories_suffix', 'Cal'),
(18, 'en', 'cart.empty', 'Your cart is empty.'),
(19, 'en', 'cart.total_label', 'Total:'),
(20, 'en', 'cart.change_order', 'Change Order'),
(21, 'en', 'cart.checkout', 'Checkout'),
(22, 'en', 'checkout.page_title', 'Order overlook'),
(23, 'en', 'checkout.order_successful', 'Order succesful!'),
(24, 'en', 'checkout.reminder_receipt', 'Don''t forget your receipt'),
(25, 'en', 'checkout.auto_return', 'Automatically returning to start..'),
(26, 'en', 'cart.total_kcal_label', 'Total kcal:');

INSERT INTO `translations` (`id`, `language`, `key`, `text`) VALUES
(27, 'nl', 'product.description.1', 'Een verkoelende mix van açaí en banaan, afgetopt met knapperige granola, chiazaad en kokos.'),
(28, 'nl', 'product.description.2', 'Volkoren wrap met luchtige roerei, babyspinazie en een lichte yoghurt-kruiden saus.'),
(29, 'nl', 'product.description.3', 'Desemtoast met 100% natuurlijke pindakaas, banaan en een snufje cacaonibs.'),
(30, 'nl', 'product.description.4', 'Driekleurige quinoa, maple-geglazuurde tofu, geroosterde zoete aardappel en boerenkool met tahindressing.'),
(31, 'nl', 'product.description.5', 'Geknede boerenkool, edamame, avocado, komkommer en geroosterde pompoenpitten met citroen-olijfolie.'),
(32, 'nl', 'product.description.6', 'Gebakken falafel, hummus, ingelegde rode ui, cherrytomaatjes en komkommer op een bedje van gemengde sla.'),
(33, 'nl', 'product.description.7', 'In de oven gebakken zoete-aardappelpartjes met gerookte paprikakruiden. Lekker met Avocado Lime Dip.'),
(34, 'nl', 'product.description.8', 'Krokante, gepaneerde courgettefrietjes. Heerlijk met Greek Yogurt Ranch.'),
(35, 'nl', 'product.description.9', 'Gebakken falafelballetjes, portie van 5 stuks.'),
(36, 'nl', 'product.description.10', 'Frisse groentesnack met selderij, wortel en komkommer, geserveerd met hummus.'),
(37, 'nl', 'product.description.11', 'Wrap met gekruide kikkererwten, wortel, knapperige sla en onze signature hummus in een volkoren wrap.'),
(38, 'nl', 'product.description.12', 'Gegrilde halloumi, geprakte avocado en chilivlokken op dikgesneden meergranenbrood.'),
(39, 'nl', 'product.description.13', 'Pulled jackfruit in barbecuesaus met een knapperige rode-koolslaw op een vegan briochebroodje.'),
(40, 'nl', 'product.description.18', 'Romige klassieke hummus van kikkererwten, tahin en citroen.'),
(41, 'nl', 'product.description.19', 'Frisse dip van avocado en limoen, perfect bij wedges en bowls.'),
(42, 'nl', 'product.description.20', 'Frisse yoghurtdip met kruiden in ranch-stijl.'),
(43, 'nl', 'product.description.21', 'Pittige mayosaus met sriracha voor extra vuur.'),
(44, 'nl', 'product.description.22', 'Rijke pindasaus met een zachte, zoete sojatoon.'),
(45, 'nl', 'product.description.25', 'Groene smoothie met spinazie, ananas, komkommer en kokoswater.'),
(46, 'nl', 'product.description.26', 'Verfrissende ijskoude matcha latte met lichte zoetheid en amandelmelk.'),
(47, 'nl', 'product.description.27', 'Vers gearomatiseerd water met keuze uit citroen-munt, aardbei-basilicum of komkommer-limoen.'),
(48, 'nl', 'product.description.28', 'Romige smoothie met aardbeien, blauwe bessen en frambozen op basis van amandelmelk.'),
(49, 'nl', 'product.description.29', 'Verkoelende mix van sinaasappelsap, bruiswater en een vleugje limoen.'),
(50, 'nl', 'product.description.31', 'Overnight oats in appelgebak-stijl met amandelmelk, appel, kaneel en walnoten.'),
(51, 'nl', 'product.description.32', 'Kom met zilvervliesrijst, gebakken tempeh, broccoli en wortel met een teriyaki-gember-sojasaus.'),
(52, 'nl', 'cart.total_kcal_label', 'Totaal kcal:');

INSERT INTO `translations` (`id`, `language`, `key`, `text`) VALUES
(53, 'nl', 'start.language.english', 'Engels'),
(54, 'nl', 'start.language.dutch', 'Nederlands'),
(55, 'nl', 'start.dine_in', 'Hier eten'),
(56, 'nl', 'start.take_out', 'Meenemen'),
(57, 'nl', 'menu.header_title', 'Ontbijt'),
(58, 'nl', 'menu.category.full_menu', 'Volledig menu'),
(59, 'nl', 'menu.category.breakfast', 'Ontbijt'),
(60, 'nl', 'menu.category.lunch_dinner', 'Lunch & Diner'),
(61, 'nl', 'menu.category.handhelds', 'Broodjes'),
(62, 'nl', 'menu.category.sides', 'Bijgerechten'),
(63, 'nl', 'menu.category.dips', 'Dips'),
(64, 'nl', 'menu.category.drinks', 'Dranken'),
(65, 'nl', 'product.kcal_suffix', 'kcal'),
(66, 'nl', 'product.back_to_menu', 'Terug naar menu'),
(67, 'nl', 'product.add_to_cart', 'Toevoegen aan bestelling'),
(68, 'nl', 'cart.title', 'Je bestelling'),
(69, 'nl', 'cart.calories_suffix', 'kcal'),
(70, 'nl', 'cart.empty', 'Je winkelmandje is leeg.'),
(71, 'nl', 'cart.total_label', 'Totaal:'),
(72, 'nl', 'cart.change_order', 'Bestelling aanpassen'),
(73, 'nl', 'cart.checkout', 'Afrekenen'),
(74, 'nl', 'checkout.page_title', 'Overzicht bestelling'),
(75, 'nl', 'checkout.order_successful', 'Bestelling geslaagd!'),
(76, 'nl', 'checkout.reminder_receipt', 'Vergeet je bonnetje niet.'),
(77, 'nl', 'checkout.auto_return', 'Je wordt automatisch teruggestuurd naar het startscherm...');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexen voor tabel `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `Constraint_FK_order_status` (`order_status_id`),
  ADD KEY `order_item` (`ordered_product`);

--
-- Indexen voor tabel `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`order_status_id`);

--
-- Indexen voor tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `Constraint_FK_image` (`image_id`),
  ADD KEY `Constraint_FK_category` (`category_id`);

--
-- Indexen voor tabel `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_language_key` (`language`,`key`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT voor een tabel `images`
--
ALTER TABLE `images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT voor een tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT voor een tabel `order_status`
--
ALTER TABLE `order_status`
  MODIFY `order_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT voor een tabel `translations`
--
ALTER TABLE `translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `Constraint_FK_order_status` FOREIGN KEY (`order_status_id`) REFERENCES `order_status` (`order_status_id`),
  ADD CONSTRAINT `order_item` FOREIGN KEY (`ordered_product`) REFERENCES `products` (`product_id`);

--
-- Beperkingen voor tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `Constraint_FK_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `Constraint_FK_image` FOREIGN KEY (`image_id`) REFERENCES `images` (`image_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
