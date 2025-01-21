-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 20 Ara 2024, 19:41:56
-- Sunucu sürümü: 10.6.12-MariaDB
-- PHP Sürümü: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sprintbatuhusey_yk`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_turkish_ci;

--
-- Table dump data `categories`--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(5, 'Test Category 1734166774', '2024-12-14 08:59:34', '2024-12-14 08:59:34'),
(20, 'TestCategory_17347060556767', '2024-12-20 14:47:35', '2024-12-20 14:47:35'),
(21, 'News', '2024-12-20 14:47:35', '2024-12-20 14:47:35'),
(22, ' Lifestyle', '2024-12-20 14:47:35', '2024-12-20 14:47:35');

-- --------------------------------------------------------

--
-- Table structure for table `posts`--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `img` varchar(535) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_turkish_ci;

--
-- Table dump data `posts`--

INSERT INTO `posts` (`id`, `user_id`, `img`, `title`, `content`, `category`, `created_at`, `updated_at`) VALUES
(11, 47, 'img/posts/675d5577b3347_1734169975.png', 'test222', '<p><strong>Lorem Ipsum</strong>, dizgi ve baskı endüstrisinde kullanılan mıgır metinlerdir. Lorem Ipsum, adı bilinmeyen bir matbaacının bir hurufat numune kitabı oluşturmak üzere bir yazı galerisini alarak karıştırdığı 1500\'lerden beri endüstri standardı sahte metinler olarak kullanılmıştır. Beşyüz yıl boyunca varlığını sürdürmekle kalmamış, aynı zamanda pek değişmeden elektronik dizgiye de sıçramıştır. 1960\'larda Lorem Ipsum pasajları da içeren Letraset yapraklarının yayınlanması ile ve yakın zamanda Aldus PageMaker gibi Lorem Ipsum sürümleri içeren masaüstü yayıncılık yazılımları ile popüler olmuştur.</p>', 5, '2024-12-14 09:52:55', '2024-12-20 16:41:31'),
(17, 47, 'img/posts/675d5577b3347_1734169975.png', 'test222', '<p><strong>Lorem Ipsum</strong>, dizgi ve baskı endüstrisinde kullanılan mıgır metinlerdir. Lorem Ipsum, adı bilinmeyen bir matbaacının bir hurufat numune kitabı oluşturmak üzere bir yazı galerisini alarak karıştırdığı 1500\'lerden beri endüstri standardı sahte metinler olarak kullanılmıştır. Beşyüz yıl boyunca varlığını sürdürmekle kalmamış, aynı zamanda pek değişmeden elektronik dizgiye de sıçramıştır. 1960\'larda Lorem Ipsum pasajları da içeren Letraset yapraklarının yayınlanması ile ve yakın zamanda Aldus PageMaker gibi Lorem Ipsum sürümleri içeren masaüstü yayıncılık yazılımları ile popüler olmuştur.</p>', 5, '2024-12-14 09:52:55', '2024-12-20 16:41:34'),
(40, 92, 'img/posts/676599daefb90_1734711770.jpg', 'Lorem Ipsum', '<p><strong>Lorem Ipsum</strong>, dizgi ve baskı endüstrisinde kullanılan mıgır metinlerdir. Lorem Ipsum, adı bilinmeyen bir matbaacının bir hurufat numune kitabı oluşturmak üzere bir yazı galerisini alarak karıştırdığı 1500\'lerden beri endüstri standardı sahte metinler olarak kullanılmıştır. Beşyüz yıl boyunca varlığını sürdürmekle kalmamış, aynı zamanda pek değişmeden elektronik dizgiye de sıçramıştır. 1960\'larda Lorem Ipsum pasajları da içeren Letraset yapraklarının yayınlanması ile ve yakın zamanda Aldus PageMaker gibi Lorem Ipsum sürümleri içeren masaüstü yayıncılık yazılımları ile popüler olmuştur.</p>', 22, '2024-12-20 16:22:50', '2024-12-20 16:22:50'),
(41, 92, 'img/posts/67659da6970fc_1734712742.jpg', 'lorem ipsum 2', '<p><strong>Lorem Ipsum</strong>, dizgi ve baskı endüstrisinde kullanılan mıgır metinlerdir. Lorem Ipsum, adı bilinmeyen bir matbaacının bir hurufat numune kitabı oluşturmak üzere bir yazı galerisini alarak karıştırdığı 1500\'lerden beri endüstri standardı sahte metinler olarak kullanılmıştır. Beşyüz yıl boyunca varlığını sürdürmekle kalmamış, aynı zamanda pek değişmeden elektronik dizgiye de sıçramıştır. 1960\'larda Lorem Ipsum pasajları da içeren Letraset yapraklarının yayınlanması ile ve yakın zamanda Aldus PageMaker gibi Lorem Ipsum sürümleri içeren masaüstü yayıncılık yazılımları ile popüler olmuştur.</p>', 22, '2024-12-20 16:39:02', '2024-12-20 16:39:02'),
(42, 92, 'img/posts/67659dd0599c7_1734712784.jpg', 'lorem ipsum 2', '<p><strong>Lorem Ipsum</strong>, dizgi ve baskı endüstrisinde kullanılan mıgır metinlerdir. Lorem Ipsum, adı bilinmeyen bir matbaacının bir hurufat numune kitabı oluşturmak üzere bir yazı galerisini alarak karıştırdığı 1500\'lerden beri endüstri standardı sahte metinler olarak kullanılmıştır. Beşyüz yıl boyunca varlığını sürdürmekle kalmamış, aynı zamanda pek değişmeden elektronik dizgiye de sıçramıştır. 1960\'larda Lorem Ipsum pasajları da içeren Letraset yapraklarının yayınlanması ile ve yakın zamanda Aldus PageMaker gibi Lorem Ipsum sürümleri içeren masaüstü yayıncılık yazılımları ile popüler olmuştur.</p>', 22, '2024-12-20 16:39:44', '2024-12-20 16:39:44'),
(43, 92, 'img/posts/67659de5d6a52_1734712805.png', 'loremm', '<p><strong>Lorem Ipsum</strong>, dizgi ve baskı endüstrisinde kullanılan mıgır metinlerdir. Lorem Ipsum, adı bilinmeyen bir matbaacının bir hurufat numune kitabı oluşturmak üzere bir yazı galerisini alarak karıştırdığı 1500\'lerden beri endüstri standardı sahte metinler olarak kullanılmıştır. Beşyüz yıl boyunca varlığını sürdürmekle kalmamış, aynı zamanda pek değişmeden elektronik dizgiye de sıçramıştır. 1960\'larda Lorem Ipsum pasajları da içeren Letraset yapraklarının yayınlanması ile ve yakın zamanda Aldus PageMaker gibi Lorem Ipsum sürümleri içeren masaüstü yayıncılık yazılımları ile popüler olmuştur.</p>', 21, '2024-12-20 16:40:05', '2024-12-20 16:40:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_turkish_ci;

--
-- Table dump data `users`--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `updated_at`) VALUES
(47, 'admin', 'karakayayusuf147@gmail.com', '$2y$10$Unj4gf/Vu41XZ3Xny6lDM.SMFbItgdChsxBx20jkP/Mcd.UaG/PAS', '2024-12-14 08:42:11', '2024-12-20 14:35:14'),
(52, 'testuser_1734166774', 'test_1734166774@test.com', '$2y$10$vwABh74PyHgwbyzYKQvP0OUjTo2303BgBnGJ9A6//hwFt4nnC2QDa', '2024-12-14 08:59:34', '2024-12-14 08:59:34'),
(63, 'testuser_17341709095467', 'test_17341709093242@test.com', '$2y$10$ZQHZr0iFs0Walfj0EhXvyuOFLvSTlQuRgCS91PO83VYPEV/lSk6u6', '2024-12-14 10:08:29', '2024-12-14 10:08:29'),
(72, 'testuser_17347050349411', 'test_17347050342407@test.com', '$2y$10$WaY4nHHxxcbtNqIaMwLUJu.83ebjHhx2G.w.QOrGMhCy/WlsuZVI6', '2024-12-20 14:30:34', '2024-12-20 14:30:34'),
(77, 'testuser_17347051061262', 'test_17347051066246@test.com', '$2y$10$c1sNYbkfWR4A9jms/6ZXj.QOUr8Y3SJqLs6sbQbqGBZ9myCofFGd6', '2024-12-20 14:31:46', '2024-12-20 14:31:46'),
(82, 'testuser_17347051573038', 'test_17347051578361@test.com', '$2y$10$CQxufvs9U5Iu/iJBNoZcSuX1gGfh6WA.ezXPLoYp.CimuTgJudW6G', '2024-12-20 14:32:37', '2024-12-20 14:32:37'),
(91, 'testuser_17347060552152', 'test_17347060555262@test.com', '$2y$10$yZXdENA7.Vv0mr/6S6krc.nu0Akn57PaXmeEHdimfTJrW2Jk62Axi', '2024-12-20 14:47:35', '2024-12-20 14:47:35'),
(92, 'test1', 'test1@gmail.com', '$2y$10$9cfgWx5AZiZSj4fAmvtJaON0Edn0cum3z1HPCYWywQcWScScQThcq', '2024-12-20 16:22:01', '2024-12-20 16:22:01');

--
-- Indexes for dumped tables--

--
-- Indexes for table `categories`--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `posts`--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `users`--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT value for dumped tables--

--
-- AUTO_INCREMENT value for table `categories`--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Tablo için AUTO_INCREMENT değeri `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT value for table `users`--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- Restrictions for dumped tables--

--
-- Table constraints `posts`--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`category`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
