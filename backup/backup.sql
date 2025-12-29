-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 28 Ara 2025, 17:19:35
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `ytumint.org`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `about_us`
--

CREATE TABLE `about_us` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `about_us`
--

INSERT INTO `about_us` (`id`, `content`, `image`, `updated_at`) VALUES
(1, 'MİNT (Multidisipliner İnovasyon Teknolojileri Kulübü); Yıldız Teknik Üniversitesi çatısı altında, YTÜ Blockchain, Yıldız Siber ve HSD YTÜ (Huawei Student Developers) topluluklarının bilgi birikimi, üretim kültürü ve merak enerjisinin kesişiminde konumlanan; bünyesinde teknik proje ve yarışma takımı DEPTRON’u barındıran çok disiplinli bir öğrenci oluşumudur.\r\n\r\n\r\nMİNT, teknolojiyi yalnızca tüketilen bir araç olarak değil; tartışılan, üretilen ve dönüştürülen bir alan olarak ele alır. Eğitimler, atölyeler, projeler ve etkinliklerle öğrencilerin teknik yetkinliklerini geliştirirken; analitik düşünme, problem çözme ve disiplinler arası bakış açılarını da güçlendirmeyi hedefler.\r\n\r\nMİNT\'te mesele yalnızca ne öğrendiğin değil, nasıl düşündüğündür. Çünkü yenilikçi fikirler çoğu zaman tek bir disiplinin içinde değil, disiplinlerin kesiştiği alanlarda filizlenir. MİNT ve DEPTRON, bu alanları bilinçli olarak açar ve üretime dönüştürür.', 'uploads/about_1766931230.jpg', '2025-12-28 14:13:50');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'admin', '$2y$10$KXt6VPaQEhiXJam.QhBr5O1vrtVXhp5iPP16.YT2iKjXdv6q6M9W2', 'admin@ytumint.org', '2025-12-28 12:35:21');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `communities`
--

CREATE TABLE `communities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `communities`
--

INSERT INTO `communities` (`id`, `name`, `description`, `image`, `website_url`, `display_order`, `is_active`, `created_at`, `updated_at`) VALUES
(5, 'Blockchain', '', 'uploads/community_1766930213_6102.png', '', 0, 1, '2025-12-28 12:54:19', '2025-12-28 13:57:14'),
(6, 'Depthron', '', 'uploads/community_1766930229_8843.png', '', 1, 1, '2025-12-28 13:57:09', '2025-12-28 13:57:09'),
(7, 'HSD', '', 'uploads/community_1766930249_9623.png', '', 2, 1, '2025-12-28 13:57:29', '2025-12-28 13:57:29'),
(8, 'YTU Siber', '', 'uploads/community_1766930262_1601.png', '', 3, 1, '2025-12-28 13:57:42', '2025-12-28 13:57:42');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `updated_at`) VALUES
(1, 'site_name', 'YTU MINT', '2025-12-28 11:52:18'),
(2, 'youtube_video_id', 'PCw2UwwNPR4', '2025-12-28 15:22:11'),
(4, 'twitter_url', 'https://x.com/ytumint', '2025-12-28 14:01:34'),
(5, 'instagram_url', 'https://www.instagram.com/ytumint/', '2025-12-28 14:01:34'),
(6, 'linkedin_url', 'https://www.linkedin.com/in/ytu-mint/?originalSubdomain=tr', '2025-12-28 14:01:34'),
(7, 'tiktok_url', 'https://www.tiktok.com/@ytumint', '2025-12-28 14:14:41'),
(8, 'hero_title', 'YTU MINT\'e Hoş Geldiniz', '2025-12-28 11:52:18'),
(9, 'hero_subtitle', 'Teknoloji ve İnovasyonda Öncü Topluluk', '2025-12-28 11:39:07'),
(10, 'hero_button_text', 'Keşfet', '2025-12-28 11:39:07'),
(14, 'site_title', 'YTU MINT', '2025-12-28 14:01:34'),
(15, 'site_slogan', 'Multidisipliner İnovasyon Teknolojileri Kulübü', '2025-12-28 14:01:34'),
(16, 'site_description', 'ytu, mint, ytumint, deepmint, blockchain, siber, depthron, kulüp, öğrenci,', '2025-12-28 14:01:34'),
(21, 'youtube_url', 'https://www.youtube.com/@mintkulubu', '2025-12-28 14:01:34'),
(22, 'site_logo', 'uploads/logo_1766930494_mint_logo_transparent.png', '2025-12-28 14:01:34'),
(23, 'site_favicon', 'uploads/favicon_1766930494_mint_logo_transparent.png', '2025-12-28 14:01:34');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `team_members`
--

CREATE TABLE `team_members` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `member_type` enum('board','community','active') DEFAULT 'active',
  `community_id` int(11) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `team_members`
--

INSERT INTO `team_members` (`id`, `name`, `position`, `image`, `email`, `linkedin`, `member_type`, `community_id`, `display_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Sude Naz Akkaya', 'Başkan', 'uploads/team/1766933580_sude-naz-akkaya.jpg', '', '', 'board', NULL, 0, 1, '2025-12-28 14:53:00', '2025-12-28 14:53:00');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `about_us`
--
ALTER TABLE `about_us`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Tablo için indeksler `communities`
--
ALTER TABLE `communities`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Tablo için indeksler `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `community_id` (`community_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `about_us`
--
ALTER TABLE `about_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `communities`
--
ALTER TABLE `communities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Tablo için AUTO_INCREMENT değeri `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `team_members`
--
ALTER TABLE `team_members`
  ADD CONSTRAINT `team_members_ibfk_1` FOREIGN KEY (`community_id`) REFERENCES `communities` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
