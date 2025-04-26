-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 23 Nis 2025, 13:35:49
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
-- Veritabanı: `filo_takip`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `certificate_types`
--

CREATE TABLE `certificate_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `certificate_types`
--

INSERT INTO `certificate_types` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Forklift Operatörü', 'Forklift kullanım sertifikasıN', '2025-04-02 13:13:45', '2025-04-06 08:22:56'),
(2, 'Vinç Operatörü', 'Vinç kullanım sertifikası', '2025-04-02 13:13:45', '2025-04-02 13:13:45'),
(3, 'İş Makinesi Operatörü', 'İş makinesi kullanım sertifikası', '2025-04-02 13:13:45', '2025-04-02 13:13:45'),
(4, 'Yüksek İş Platformu Operatörü', 'Yüksek iş platformu kullanım sertifikası', '2025-04-02 13:13:45', '2025-04-02 13:13:45'),
(5, 'Kaldırma ve Taşıma Operatörü', 'Kaldırma ve taşıma ekipmanları kullanım sertifikası', '2025-04-02 13:13:45', '2025-04-02 13:13:45');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `company_name` varchar(200) NOT NULL,
  `tax_office` varchar(100) DEFAULT NULL,
  `tax_number` varchar(20) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `status` enum('Aktif','Pasif') NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `companies`
--

INSERT INTO `companies` (`id`, `company_name`, `tax_office`, `tax_number`, `address`, `phone`, `email`, `logo_url`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ABC Lojistik A.Ş.', 'İstanbul VD', '1234567890', 'Ataşehir, İstanbul', '(212) 555-1234', 'info@abclojistik.com', NULL, 'Aktif', '2025-04-03 07:50:21', '2025-04-03 07:50:21'),
(3, 'Delta Nakliyat', 'İzmir VD', '5678901234', 'Konak, İzmir', '(232) 333-9876', 'delta@deltanakliyat.com', NULL, 'Aktif', '2025-04-03 07:50:21', '2025-04-03 08:54:44'),
(4, 'Duygu İnşaat', 'Ankara', '1225221111', 'Ankara', '(312) 222-1212', 'duygu@duygugrup.com', 'uploads/company_logos/67ee4d78e2c0c_duygu.jpg', 'Aktif', '2025-04-03 08:57:28', '2025-04-03 08:57:28');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `drivers`
--

CREATE TABLE `drivers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `identity_number` varchar(20) NOT NULL,
  `license_number` varchar(20) NOT NULL,
  `primary_license_type` varchar(5) DEFAULT NULL,
  `license_issue_date` date DEFAULT NULL,
  `license_expiry_date` date DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('Aktif','Pasif','İzinli') NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `drivers`
--

INSERT INTO `drivers` (`id`, `name`, `surname`, `identity_number`, `license_number`, `primary_license_type`, `license_issue_date`, `license_expiry_date`, `company_id`, `phone`, `email`, `address`, `birth_date`, `notes`, `status`, `created_at`) VALUES
(1, 'Ahmet', 'Yılmaz', '12345678910', 'B123456', 'B', '2015-05-15', '2026-02-19', 4, '5551234567', 'ahmet@example.com', 'Beşiktaş, İstanbul', '2002-12-09', '', 'Aktif', '2023-03-22 08:00:00'),
(2, 'Mehmet', 'Demir', '12345678911', 'E234567', 'B', '2017-08-22', '2025-09-12', 1, '5552345678', 'mehmet@example.com', 'Kadık&ouml;y, İstanbul', '1975-02-19', '', 'Aktif', '2023-03-22 08:30:00'),
(3, 'Ayşe', 'Kaya', '12345678912', 'B345678', 'B', '2017-01-05', '2027-01-05', NULL, '5553456789', 'ayse@example.com', 'Şişli, İstanbul', NULL, NULL, 'İzinli', '2023-03-22 09:00:00'),
(4, 'Fatma', 'Çelik', '12345678913', 'B456789', 'B', NULL, '2029-11-12', NULL, '5554567890', 'fatma@example.com', 'Üsküdar, İstanbul', NULL, NULL, 'İzinli', '2023-03-22 09:30:00'),
(5, 'Ali', 'Yıldız', '12345678914', 'E567890', 'D', '2015-09-18', '2025-09-18', NULL, '5555678901', 'ali@example.com', 'Beyoğlu, İstanbul', NULL, NULL, 'Aktif', '2023-03-22 10:00:00'),
(6, 'Mustafa', 'Şahin', '12345678915', 'C678901', 'C', '2018-04-30', '2028-04-30', NULL, '5556789012', 'mustafa@example.com', 'Bakırköy, İstanbul', NULL, NULL, 'Aktif', '2023-03-22 10:30:00'),
(7, 'Zeynep', 'Öztürk', '12345678916', 'B789012', 'B', '2016-12-01', '2026-12-01', NULL, '5557890123', 'zeynep@example.com', 'Ataşehir, İstanbul', NULL, NULL, 'Aktif', '2023-03-22 11:00:00'),
(8, 'Hüseyin', 'Aydın', '12345678917', 'E890123', 'CE', '2016-02-20', '2026-02-20', NULL, '5558901234', 'huseyin@example.com', 'Maltepe, İstanbul', NULL, NULL, 'Aktif', '2023-03-22 11:30:00'),
(9, 'ismail', 'çelikbaş', '40574073114', 'DE258556', 'A', '0000-00-00', '0000-00-00', NULL, '5552552525', 'ismailcelikbas66@gmail.com', 'Yozgat Merkez', NULL, NULL, 'Aktif', '2025-04-02 11:09:00'),
(10, 'SEHER', 'EROL', '58584445666', 'DE885554', 'B', '0000-00-00', '2029-02-09', NULL, '255551223', 'seher@bb.com', 'yeni şöför', NULL, NULL, 'Aktif', '2025-04-02 11:21:47'),
(12, 'abc', 'abc', '45555555555', 'EDA455454', 'M', '0000-00-00', '0000-00-00', NULL, '4455552211', 'aaaa@bb.com', 'aaaa', NULL, NULL, 'Aktif', '2025-04-02 11:52:11'),
(13, 'deneme2', 'deneme3', '11111111111', 'DE55444545', 'B', '0000-00-00', '0000-00-00', NULL, '2553691212', 'aba@bb.com', '', NULL, NULL, 'Aktif', '2025-04-02 12:05:05'),
(14, 'sevval', 'deneme', '55555555555', 'DE112444', 'A', NULL, '2030-03-03', 4, '3232225512', 'abx@bb.com', 'Yozgat Merkez', NULL, NULL, 'Aktif', '2025-04-02 12:12:14'),
(15, 'mehmet', 'zorbilmez', '22554111111', 'abc44455', 'A', NULL, '2026-12-12', 1, '5552221255', 'abc@dd.com', 'Yozgat', NULL, NULL, 'Aktif', '2025-04-04 06:53:47'),
(16, 'SERDAR', 'DENEME', '44555544444', '4185555', 'A', NULL, '2025-05-19', 4, '5552552525', 'serdardeneme@deneme.com', 'Yozgat / Merkez', NULL, NULL, 'Aktif', '2025-04-05 06:59:38'),
(17, 'AHMET', 'DENEME3', '58555665555', '4856125', 'B', NULL, '2025-05-15', 4, '2542556666', 'ABX@BB.COM', 'YOZGAT / AZİZLİ', NULL, NULL, 'Aktif', '2025-04-05 07:47:57'),
(18, 'HACI', 'AYTANBANTA', '22212555555', '5488455', 'C1E', '2020-05-19', '2030-05-19', 1, '2552231232', '', 'YOZGAT / KAYADİBİ', NULL, NULL, 'Aktif', '2025-04-05 07:53:37'),
(19, 'deneme', 'hacibaba', '25521454545', '54214465565', 'B', '2002-12-02', '2028-12-02', 4, '212155555', 'afafanba@dd.com', 'ankara', NULL, NULL, 'Aktif', '2025-04-05 09:33:28'),
(20, 'DELTA2', 'S&Uuml;R&Uuml;C&Uuml;S&Uuml;', '45588255555', '54545454', 'B', '2002-12-02', '2031-12-02', 3, '212222122', 'DELTA@DB.COM', 'ANKARA MERKEZ', NULL, NULL, 'Aktif', '2025-04-06 07:54:37'),
(21, 'DELTADEN', 'DENEMESOF', '22225555441', '56645525', 'B', '2002-12-05', '2028-12-05', 3, '212112122', 'ABCX@BB.CIM', 'YOZGAT MERKEZ', '2008-12-05', 'YENİ SÖFOR', 'Aktif', '2025-04-06 08:14:57'),
(22, 'şevval', 'beçet', '55555555521', '556555', 'B', '2002-12-02', '2025-04-02', 4, '4585551266', 'serv@bvb.com', 'yozgat merkez', '2002-02-09', 'deneme sürücü', 'Aktif', '2025-04-12 20:09:29'),
(23, 'ibrahim', 'torulmaz', '55520222122', '44555', 'B', '2010-12-02', '2026-12-02', 1, '5552221233', 'ibrahim@duygu.com', 'yozgat/merkez', '2002-02-12', 'damperli kamyon söförü', 'Aktif', '2025-04-17 07:35:55'),
(24, 'FATİH', 'YILMAZ', '55522211122', 'EA2222', 'B', '2010-03-12', '2010-03-12', 4, '5552221212', 'ft@bb.com', 'Yozgat/Merkez', '2006-01-01', 'Damperli Kamyon Sürer', 'Aktif', '2025-04-18 08:39:16');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `driver_certificates`
--

CREATE TABLE `driver_certificates` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `certificate_type_id` int(11) NOT NULL,
  `certificate_number` varchar(50) NOT NULL,
  `issue_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `issuing_authority` varchar(100) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `driver_certificates`
--

INSERT INTO `driver_certificates` (`id`, `driver_id`, `certificate_type_id`, `certificate_number`, `issue_date`, `expiry_date`, `issuing_authority`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '145255', '2002-05-09', '2025-04-09', 'MİLLİ EĞİTİM', '', '2025-04-02 13:16:32', '2025-04-03 07:00:06'),
(2, 12, 1, '1145224', '2020-05-09', '2025-05-09', 'MİLLİ EĞİTİM', '', '2025-04-02 13:18:27', '2025-04-02 13:18:27'),
(3, 1, 5, '154555', '2021-01-02', '2025-06-02', 'MİLLİ EĞİTİM', '', '2025-04-02 13:33:44', '2025-04-02 13:33:44'),
(4, 14, 5, '145555', '2020-01-02', '2025-10-01', 'ÜNİVERSİTE', '', '2025-04-02 14:11:00', '2025-04-02 14:11:00'),
(5, 4, 2, '5252411', '2020-12-01', '2025-02-11', 'milli eğitim', '', '2025-04-03 07:01:14', '2025-04-03 07:01:14'),
(6, 10, 3, '452255', '2021-01-03', '2025-04-30', 'üniversite', '', '2025-04-03 07:19:22', '2025-04-03 07:19:22'),
(7, 16, 2, '14558525', '2019-05-12', '2025-05-12', 'milli eğitim', 'vinç operatörü', '2025-04-05 07:00:56', '2025-04-05 07:00:56'),
(8, 15, 4, '144555', '2002-02-12', '2026-11-10', 'erciyes üniversitesi', '', '2025-04-05 12:10:09', '2025-04-05 12:10:09'),
(9, 21, 2, '145554', '2002-12-09', '2026-12-01', 'ERCİYES ÜNİVERSİTESİ', 'VERİLMİŞ ALMIŞ', '2025-04-06 08:19:52', '2025-04-06 08:19:52'),
(10, 2, 1, '442255', '2005-12-15', '2030-02-12', 'anadolu üniversitesi', 'kontrol et', '2025-04-15 12:09:41', '2025-04-15 12:09:41'),
(11, 24, 3, '555455', '2020-02-12', '2026-02-12', 'ted kololi', 'abc noto', '2025-04-18 08:39:53', '2025-04-18 08:39:53');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `driver_licenses`
--

CREATE TABLE `driver_licenses` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `license_type_id` int(11) NOT NULL,
  `issue_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `driver_licenses`
--

INSERT INTO `driver_licenses` (`id`, `driver_id`, `license_type_id`, `issue_date`, `expiry_date`, `notes`, `created_at`) VALUES
(2, 1, 10, '2002-07-20', '2028-07-20', 'Ağır vasıta ehliyeti', '2025-04-02 10:51:59'),
(3, 2, 15, '2016-03-10', '2026-03-10', NULL, '2025-04-02 10:51:59'),
(5, 2, 11, '2017-08-22', '2027-08-22', 'Tır kullanım izni', '2025-04-02 10:51:59'),
(7, 4, 6, '2019-11-12', '2029-11-12', NULL, '2025-04-02 10:51:59'),
(8, 5, 14, '2015-09-18', '2025-09-18', 'Otobüs kullanım belgesi', '2025-04-02 10:51:59'),
(9, 5, 15, '2015-09-18', '2025-09-18', NULL, '2025-04-02 10:51:59'),
(11, 7, 6, '2016-12-01', '2026-12-01', NULL, '2025-04-02 10:51:59'),
(12, 8, 15, '2014-08-15', '2024-08-15', NULL, '2025-04-02 10:51:59'),
(13, 8, 10, '2014-08-15', '2024-08-15', NULL, '2025-04-02 10:51:59'),
(14, 8, 11, '2016-02-20', '2026-02-20', 'Ek yetki', '2025-04-02 10:51:59'),
(16, 9, 6, '2002-12-20', '2025-12-20', 'B OTOMOBİL', '2025-04-02 11:10:19'),
(17, 10, 6, '2002-02-09', '2029-02-09', 'Birincil ehliyet', '2025-04-02 11:21:47'),
(22, 12, 6, '2002-12-09', '2025-12-09', '', '2025-04-02 12:02:09'),
(23, 13, 6, '2022-02-12', '2025-06-27', 'Birincil ehliyet', '2025-04-02 12:05:05'),
(24, 14, 4, '2002-05-06', '2030-03-03', 'Birincil ehliyet', '2025-04-02 12:12:14'),
(25, 14, 3, '2002-10-15', '2029-12-02', '', '2025-04-02 12:13:34'),
(26, 1, 6, '2015-02-12', '2026-02-19', '', '2025-04-02 12:24:16'),
(27, 3, 8, '2002-02-19', '2025-05-17', '', '2025-04-02 14:15:49'),
(28, 10, 12, NULL, NULL, '', '2025-04-03 07:18:41'),
(29, 14, 16, '2002-12-01', '2025-12-12', '', '2025-04-04 06:44:33'),
(30, 15, 4, '2002-12-01', '2026-12-12', 'Birincil ehliyet', '2025-04-04 06:53:47'),
(31, 16, 4, '2020-09-02', '2025-05-19', 'Birincil ehliyet', '2025-04-05 06:59:38'),
(32, 17, 6, '2002-12-02', '2025-05-15', 'Birincil ehliyet', '2025-04-05 07:47:57'),
(33, 18, 9, '2020-05-19', '2030-05-19', 'Birincil ehliyet', '2025-04-05 07:53:37'),
(34, 19, 6, '2002-12-02', '2028-12-02', 'Birincil ehliyet', '2025-04-05 09:33:28'),
(35, 20, 6, '2002-12-02', '2031-12-02', 'Birincil ehliyet', '2025-04-06 07:54:37'),
(36, 21, 6, '2002-12-05', '2028-12-05', 'Birincil ehliyet', '2025-04-06 08:14:57'),
(37, 22, 6, '2002-12-02', '2025-04-02', 'Birincil ehliyet', '2025-04-12 20:09:29'),
(38, 2, 6, '2002-12-09', '2025-09-12', '', '2025-04-15 12:08:42'),
(39, 23, 6, '2010-12-02', '2026-12-02', 'Birincil ehliyet', '2025-04-17 07:35:55'),
(40, 24, 6, '2010-03-12', '2010-03-12', 'Birincil ehliyet', '2025-04-18 08:39:16');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `fine_payments`
--

CREATE TABLE `fine_payments` (
  `id` int(11) NOT NULL,
  `fine_id` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('Nakit','Kredi Kartı','Maaş Kesintisi','Diğer') NOT NULL,
  `receipt_number` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `fine_payments`
--

INSERT INTO `fine_payments` (`id`, `fine_id`, `payment_date`, `amount`, `payment_method`, `receipt_number`, `notes`, `created_at`, `created_by`) VALUES
(1, 1, '2025-04-23', 2000.00, 'Maaş Kesintisi', 'Ocak Maaş Kesintisi', 'Ocak Maaş Kesintisi', '2025-04-23 08:27:00', 1),
(2, 1, '2025-04-23', 168.00, 'Nakit', NULL, 'NAKİT VERDİ MALİYEYE', '2025-04-23 09:57:51', 1),
(3, 2, '2025-04-23', 1000.00, 'Maaş Kesintisi', 'MAYIS MAAŞ TAAHHUK EDİLECEK', 'MAYIS MAAŞ TAAHHUK EDİLECEK', '2025-04-23 10:04:02', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `fine_types`
--

CREATE TABLE `fine_types` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL COMMENT 'Ceza kodu (örn: TRF-001)',
  `name` varchar(100) NOT NULL COMMENT 'Ceza adı',
  `legal_article` varchar(100) DEFAULT NULL COMMENT 'İlgili kanun maddesi',
  `description` text DEFAULT NULL COMMENT 'Detaylı açıklama',
  `default_amount` decimal(10,2) NOT NULL COMMENT 'Varsayılan ceza tutarı',
  `points` int(11) DEFAULT NULL COMMENT 'Ceza puanı (eğer uygulanıyorsa)',
  `active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Aktif/pasif durumu',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `fine_types`
--

INSERT INTO `fine_types` (`id`, `code`, `name`, `legal_article`, `description`, `default_amount`, `points`, `active`, `created_at`) VALUES
(1, 'KZ01', 'Kırmızı Işık Cezası', NULL, 'Kırmızı Işık Cezası', 2168.00, NULL, 1, '2025-04-23 09:36:32'),
(2, 'YKS1', 'Yüksek Ses Cezası', NULL, 'Yüksek Ses Cezası', 993.00, NULL, 1, '2025-04-23 10:39:35'),
(3, 'HZ01', 'Hız Limitlerini %10-30 Oranında Aşma', NULL, 'Hız Limitlerini %10-30 Oranında Aşma', 2168.00, NULL, 1, '2025-04-23 11:13:54'),
(4, 'HZ02', 'Hız Limitlerini %30-50 Oranında Aşma', NULL, 'Hız Limitlerini %30-50 Oranında Aşma', 4512.00, NULL, 1, '2025-04-23 11:14:18'),
(5, 'HZ03', 'Hız Limitlerini %50&#39;den Fazla Aşma', NULL, 'Hız Limitlerini %50&#39;den Fazla Aşma', 9268.00, NULL, 1, '2025-04-23 11:14:41'),
(6, 'DN01', 'U Dönüşü Cezası', NULL, 'U Dönüşü Cezası', 993.00, NULL, 1, '2025-04-23 11:15:16');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `fuel_purchases`
--

CREATE TABLE `fuel_purchases` (
  `id` int(11) NOT NULL,
  `supplier_name` varchar(200) NOT NULL,
  `fuel_type` enum('Benzin','Dizel') NOT NULL,
  `amount` decimal(10,2) NOT NULL COMMENT 'Litre',
  `cost` decimal(10,2) NOT NULL COMMENT 'TL',
  `unit_price` decimal(10,2) NOT NULL COMMENT 'TL/Litre',
  `tank_id` int(11) NOT NULL,
  `invoice_number` varchar(100) DEFAULT NULL,
  `date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `fuel_purchases`
--

INSERT INTO `fuel_purchases` (`id`, `supplier_name`, `fuel_type`, `amount`, `cost`, `unit_price`, `tank_id`, `invoice_number`, `date`, `notes`, `created_at`, `created_by`) VALUES
(1, 'PETROL OFİSİ', 'Dizel', 3000.00, 100000.00, 33.33, 1, 'FA54255522', '2025-04-02', 'İRSALİYESİ BUY', '2025-04-02 09:02:54', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `fuel_records`
--

CREATE TABLE `fuel_records` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `tank_id` int(11) NOT NULL,
  `dispenser_id` int(11) DEFAULT NULL,
  `fuel_type` enum('Benzin','Dizel') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL COMMENT 'Litre',
  `km_reading` int(11) DEFAULT NULL COMMENT 'Kilometre',
  `hour_reading` decimal(10,2) DEFAULT NULL COMMENT 'Çalışma Saati',
  `date` date NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `fuel_records`
--

INSERT INTO `fuel_records` (`id`, `vehicle_id`, `driver_id`, `tank_id`, `dispenser_id`, `fuel_type`, `amount`, `km_reading`, `hour_reading`, `date`, `notes`, `created_at`, `created_by`) VALUES
(10, 11, 7, 1, NULL, 'Dizel', 200.00, 145555, 13350.00, '2025-04-02', '', '2025-04-02 10:21:05', 1),
(11, 2, 2, 1, NULL, 'Dizel', 200.00, 145550, NULL, '2025-04-02', '', '2025-04-02 10:25:31', 1),
(12, 10, 8, 1, NULL, 'Dizel', 222.00, 145780, 12552.00, '2025-04-01', 'km-118', '2025-04-02 10:26:32', 1),
(13, 4, 16, 1, NULL, 'Dizel', 330.00, 155210, 11350.00, '2025-04-05', '', '2025-04-05 07:06:57', 1),
(14, 11, 7, 1, NULL, 'Dizel', 330.00, NULL, 11350.00, '2025-04-05', '', '2025-04-05 07:08:45', 1),
(15, 2, 2, 1, NULL, 'Dizel', 220.00, 74148, 9350.00, '2025-04-04', '', '2025-04-05 07:31:14', 1),
(16, 4, 16, 2, NULL, 'Dizel', 200.00, 29500, 2250.00, '2025-04-02', '', '2025-04-05 07:31:53', 1),
(17, 11, 7, 1, 3, 'Dizel', 325.00, NULL, 14430.00, '2025-04-06', '113 saha çalışması', '2025-04-06 09:35:39', 1),
(18, 11, 7, 1, 3, 'Dizel', 325.00, NULL, 14430.00, '2025-04-06', '113 saha çalışması', '2025-04-06 09:36:59', 1),
(31, 13, 10, 1, NULL, 'Dizel', 43.00, 45011, NULL, '2025-04-13', '', '2025-04-13 20:39:14', 1),
(32, 13, 10, 1, NULL, 'Dizel', 80.00, 35200, 1350.00, '2025-04-13', '', '2025-04-13 20:58:43', 1),
(37, 11, 7, 1, NULL, 'Dizel', 350.00, NULL, 14400.00, '2025-04-13', '', '2025-04-13 21:21:21', 1),
(38, 13, 10, 1, NULL, 'Dizel', 30.00, 33250, NULL, '2025-04-14', '', '2025-04-14 05:32:30', 1),
(39, 13, 10, 1, 3, 'Dizel', 20.00, 35222, 13144.00, '2025-04-14', '', '2025-04-14 06:06:39', 1),
(41, 13, 10, 3, 1, 'Dizel', 100.00, 35500, 11110.00, '2025-04-14', '', '2025-04-14 07:15:50', 1),
(43, 13, 10, 3, 3, 'Dizel', 50.00, 158500, 101010.00, '2025-04-14', NULL, '2025-04-14 10:03:46', 1),
(44, 7, 21, 2, 3, 'Dizel', 50.00, 11551, 11510.00, '2025-04-14', NULL, '2025-04-14 10:52:45', 1),
(45, 15, 23, 1, 3, 'Dizel', 100.00, 34350, NULL, '2025-04-17', '89 saha', '2025-04-17 14:03:21', 1),
(46, 16, 18, 1, NULL, 'Dizel', 100.00, 118100, 12100.00, '2025-04-21', '96 SAHA ÇALIŞMASI', '2025-04-21 07:33:42', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `fuel_tanks`
--

CREATE TABLE `fuel_tanks` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('Sabit','Mobil') NOT NULL,
  `capacity` decimal(10,2) NOT NULL COMMENT 'Litre',
  `current_amount` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Litre',
  `location` varchar(200) DEFAULT NULL,
  `status` enum('Aktif','Pasif','Bakımda') NOT NULL DEFAULT 'Aktif',
  `fuel_type` enum('Benzin','Dizel') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `fuel_tanks`
--

INSERT INTO `fuel_tanks` (`id`, `name`, `type`, `capacity`, `current_amount`, `location`, `status`, `fuel_type`, `created_at`) VALUES
(1, 'Ana Şantiye Tankı', 'Sabit', 45000.00, 4600.00, 'Ana Şantiye', 'Aktif', 'Dizel', '2025-04-02 08:28:16'),
(2, 'Mobil Tank 1', 'Mobil', 2000.00, 950.00, 'Saha 1', 'Aktif', 'Dizel', '2025-04-02 08:28:16'),
(3, 'Mobil Tank 2', 'Mobil', 10000.00, 550.00, 'Saha 2', 'Aktif', 'Dizel', '2025-04-02 08:28:16');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `fuel_transfers`
--

CREATE TABLE `fuel_transfers` (
  `id` int(11) NOT NULL,
  `source_tank_id` int(11) NOT NULL,
  `destination_tank_id` int(11) NOT NULL,
  `fuel_type` enum('Benzin','Dizel') NOT NULL,
  `amount` decimal(10,2) NOT NULL COMMENT 'Litre',
  `date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `fuel_transfers`
--

INSERT INTO `fuel_transfers` (`id`, `source_tank_id`, `destination_tank_id`, `fuel_type`, `amount`, `date`, `notes`, `created_at`, `created_by`) VALUES
(3, 1, 2, 'Dizel', 500.00, '2025-04-06', 'DAĞITIM İÇİN VERİLEN', '2025-04-06 08:32:45', 1),
(4, 1, 2, 'Dizel', 500.00, '2025-04-06', 'DAĞITIM İÇİN VERİLEN', '2025-04-06 08:33:08', 1),
(5, 1, 3, 'Dizel', 500.00, '2025-04-06', 'DAĞITIM İÇİN VERİLEN', '2025-04-06 08:33:55', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `license_types`
--

CREATE TABLE `license_types` (
  `id` int(11) NOT NULL,
  `code` varchar(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `license_types`
--

INSERT INTO `license_types` (`id`, `code`, `name`, `description`) VALUES
(1, 'M', 'M Sınıfı', 'Motorlu bisiklet (Moped) kullanımı için'),
(2, 'A1', 'A1 Sınıfı', 'Silindir hacmi 125 cc\'ye kadar, gücü 11 kilovatı geçmeyen sepetsiz iki tekerlekli motosikletler'),
(3, 'A2', 'A2 Sınıfı', 'Gücü 35 kilovatı geçmeyen, gücü/ağırlığı 0,2 kilovatı/kiloğramı geçmeyen iki tekerlekli motosikletler'),
(4, 'A', 'A Sınıfı', 'Gücü 35 kilovatı veya gücü/ağırlığı 0,2 kilovatı/kiloğramı geçen iki tekerlekli motosikletler'),
(5, 'B1', 'B1 Sınıfı', 'Net motor gücü 15 kilovatı ve net ağırlığı 400 kilogram geçmeyen dört tekerlekli motosikletler'),
(6, 'B', 'B Sınıfı', 'Otomobil ve kamyonet (3500 kg\'a kadar)'),
(7, 'BE', 'BE Sınıfı', 'B sınıfı sürücü belgesi ile sürülebilen otomobil veya kamyonetin römork takılmış hali'),
(8, 'C1', 'C1 Sınıfı', 'Azami yüklü ağırlığı 3.500 kg\'ın üzerinde olan ve 7.500 kg\'ı geçmeyen kamyon ve çekiciler'),
(9, 'C1E', 'C1E Sınıfı', 'C1 sınıfı sürücü belgesi ile sürülebilen araçlara takılan ve azami yüklü ağırlığı 750 kg\'ı geçen römorklu kamyonlar'),
(10, 'C', 'C Sınıfı', 'Kamyon ve Çekici (Tır)'),
(11, 'CE', 'CE Sınıfı', 'C sınıfı sürücü belgesi ile sürülebilen araçlarla römork takılan hali'),
(12, 'D1', 'D1 Sınıfı', 'Minibüs'),
(13, 'D1E', 'D1E Sınıfı', 'D1 sınıfı sürücü belgesi ile sürülebilen araçlara takılan ve azami yüklü ağırlığı 750 kg\'ı geçen römorklu halı'),
(14, 'D', 'D Sınıfı', 'Otobüs'),
(15, 'DE', 'DE Sınıfı', 'D sınıfı sürücü belgesi ile sürülebilen araçlara römork takılan hali'),
(16, 'F', 'F Sınıfı', 'Traktör kullanımı için'),
(17, 'G', 'G Sınıfı', 'İş makinası türündeki motorlu araçları kullanabilme');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `maintenance_records`
--

CREATE TABLE `maintenance_records` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `maintenance_type` enum('Periyodik Bakım','Arıza','Lastik Değişimi','Yağ Değişimi','Diğer') NOT NULL,
  `description` text NOT NULL,
  `planning_date` date DEFAULT NULL COMMENT 'planlama tarihi',
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `cost` decimal(10,2) NOT NULL,
  `km_reading` int(11) NOT NULL,
  `status` enum('Planlandı','Devam Ediyor','Tamamlandı','İptal') NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `service_provider` varchar(100) DEFAULT NULL,
  `next_maintenance_date` date DEFAULT NULL,
  `next_maintenance_km` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `hour_reading` decimal(10,2) DEFAULT NULL COMMENT 'Bakım sırasındaki çalışma saati',
  `next_maintenance_hours` decimal(10,2) DEFAULT NULL COMMENT 'Bir sonraki bakımın çalışma saati'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `maintenance_records`
--

INSERT INTO `maintenance_records` (`id`, `vehicle_id`, `maintenance_type`, `description`, `planning_date`, `start_date`, `end_date`, `cost`, `km_reading`, `status`, `notes`, `created_at`, `created_by`, `service_provider`, `next_maintenance_date`, `next_maintenance_km`, `updated_at`, `hour_reading`, `next_maintenance_hours`) VALUES
(1, 2, 'Periyodik Bakım', '10.000 km bakımı, filtre ve yağ değişimi', NULL, '2023-03-24', '2023-03-24', 1250.00, 10000, 'Tamamlandı', NULL, '2023-03-24 08:00:00', 1, 'ŞANTİYE SERVİSİ', NULL, 20200, '2025-04-03 10:57:36', NULL, NULL),
(2, 3, 'Lastik Değişimi', '4 adet lastik değişimi', NULL, '2023-03-23', NULL, 3200.00, 45000, 'Devam Ediyor', 'Arka lastikler değiştirildi, ön lastikler bekleniyor', '2023-03-23 09:00:00', 1, NULL, NULL, NULL, '2025-04-03 10:56:58', NULL, NULL),
(4, 6, 'Arıza', 'Fren sistemi arızası', NULL, '2023-03-20', '2023-03-21', 1800.00, 45000, 'Tamamlandı', 'Fren diskleri ve balataları değiştirildi', '2023-03-20 11:00:00', 1, NULL, NULL, NULL, '2025-04-03 10:56:58', NULL, NULL),
(5, 8, 'Periyodik Bakım', '50.000 km bakımı', NULL, '2023-03-15', '2023-03-16', 2200.00, 50000, 'Tamamlandı', 'Tüm filtreler, yağlar ve kayışlar değiştirildi', '2023-03-15 12:00:00', 1, NULL, NULL, NULL, '2025-04-03 10:56:58', NULL, NULL),
(6, 10, 'Periyodik Bakım', 'PERSİYODİK BAKIM', NULL, '2025-04-05', '2025-04-10', 3000.00, 148500, 'Devam Ediyor', 'not ekle', '2025-04-04 14:00:30', 1, 'ŞANTİYE SERVİSİ', '2026-04-10', 200000, '2025-04-12 18:39:58', NULL, NULL),
(7, 13, 'Periyodik Bakım', 'periyodik bakım', '2025-04-20', '2025-04-14', NULL, 0.00, 158500, 'Planlandı', NULL, '2025-04-12 20:47:37', 1, 'Şantiye Periyodik Bakım', NULL, NULL, '2025-04-20 07:44:33', NULL, NULL),
(9, 4, 'Periyodik Bakım', 'Periyodik Bakım', '2025-04-25', '2025-04-20', NULL, 52000.00, 13558, 'Tamamlandı', 'Serviste bütün bakımları yapıldı', '2025-04-15 09:51:30', 1, 'Ankara Renault Servisi', '2026-04-30', 15000, '2025-04-20 06:16:21', NULL, NULL),
(10, 15, 'Periyodik Bakım', 'SERVİS PERİYODİK BAKIMI', '2025-04-25', '2025-04-17', '2025-04-18', 15000.00, 13550, 'Tamamlandı', 'SERVİSE GİRECEK SANIRIM', '2025-04-17 07:46:27', 1, 'RENAULT ANKARA', '2026-04-17', 20000, '2025-04-17 07:48:45', NULL, NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `system_logs`
--

CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `system_logs`
--

INSERT INTO `system_logs` (`id`, `user_id`, `action`, `type`, `ip_address`, `details`, `created_at`) VALUES
(118, 1, 'Oturum açıldı', 'login', '::1', 'Kullanıcı adı: admin@filotak.ip', '2025-04-21 07:26:03'),
(119, 1, 'Oturum kapatıldı', 'logout', '::1', 'Kullanıcı adı: admin@filotak.ip', '2025-04-21 11:09:59'),
(120, 1, 'Oturum açıldı', 'login', '::1', 'Kullanıcı adı: admin@filotak.ip', '2025-04-21 11:10:06'),
(121, 1, 'Oturum açıldı', 'login', '::1', 'Kullanıcı adı: admin@filotak.ip', '2025-04-22 07:38:13'),
(122, 1, 'Log silindi', 'delete', '::1', 'ID: 117', '2025-04-22 09:41:40'),
(123, 1, 'Oturum kapatıldı', 'logout', '::1', 'Kullanıcı adı: admin@filotak.ip', '2025-04-22 13:47:25'),
(124, 1, 'Oturum açıldı', 'login', '::1', 'Kullanıcı adı: admin@filotak.ip', '2025-04-23 06:08:24'),
(125, 1, 'Oturum açıldı', 'login', '::1', 'Kullanıcı adı: admin@filotak.ip', '2025-04-23 07:58:56');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `traffic_fines`
--

CREATE TABLE `traffic_fines` (
  `id` int(11) NOT NULL,
  `fine_number` varchar(50) NOT NULL COMMENT 'Ceza numarası',
  `vehicle_id` int(11) NOT NULL COMMENT 'Ceza alan araç',
  `driver_id` int(11) DEFAULT NULL COMMENT 'Ceza alan sürücü (eğer biliniyorsa)',
  `fine_type_id` int(11) DEFAULT NULL COMMENT 'Ceza türü ID',
  `fine_date` date NOT NULL COMMENT 'Ceza tarihi',
  `fine_time` time DEFAULT NULL COMMENT 'Ceza saati',
  `fine_amount` decimal(10,2) NOT NULL COMMENT 'Ceza tutarı',
  `fine_location` varchar(255) DEFAULT NULL COMMENT 'Ceza lokasyonu',
  `description` text DEFAULT NULL COMMENT 'Ceza açıklaması',
  `payment_status` enum('Ödenmedi','Ödendi','İtiraz Edildi','Taksitli Ödeme') NOT NULL DEFAULT 'Ödenmedi',
  `payment_date` date DEFAULT NULL COMMENT 'Ödeme tarihi',
  `payment_amount` decimal(10,2) DEFAULT NULL COMMENT 'Ödenen tutar',
  `document_url` varchar(255) DEFAULT NULL COMMENT 'Ceza belgesi URL',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL COMMENT 'Kaydı oluşturan kullanıcı',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `traffic_fines`
--

INSERT INTO `traffic_fines` (`id`, `fine_number`, `vehicle_id`, `driver_id`, `fine_type_id`, `fine_date`, `fine_time`, `fine_amount`, `fine_location`, `description`, `payment_status`, `payment_date`, `payment_amount`, `document_url`, `created_at`, `created_by`, `updated_at`) VALUES
(1, 'MA4445554', 13, NULL, 1, '2025-04-23', '12:22:00', 2168.00, 'ANKARA KAVŞAK', 'PERSOENEL BİLGİSİ GELECEK', 'Ödendi', NULL, 2168.00, NULL, '2025-04-23 09:45:00', 1, '2025-04-23 09:57:51'),
(2, 'MA55555', 10, 9, 1, '2025-04-17', '11:59:00', 2168.00, NULL, 'PERSONEL İTİRAZ EDECEK', 'Taksitli Ödeme', NULL, 1000.00, NULL, '2025-04-23 10:02:42', 1, '2025-04-23 10:04:02'),
(3, 'ma55555552', 1, 19, 2, '2025-04-03', '11:55:00', 993.00, 'ankara', 'ankarada yemiş', 'Ödenmedi', NULL, NULL, NULL, '2025-04-23 11:04:33', 1, '2025-04-23 11:04:33'),
(4, 'MA556672', 15, 5, 4, '2025-04-09', '14:20:00', 4512.00, 'KARAMAN-KONYA YOLU', 'KARAMAN-KONYA YOLU PERSOENEL', 'Ödenmedi', NULL, NULL, NULL, '2025-04-23 11:17:04', 1, '2025-04-23 11:17:04');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `surname` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `surname`) VALUES
(1, 'ismail', 'admin@filotak.ip', '$2y$10$ZOixpgt5zgHhvwjuBftcSO/hbhMXuw8NylHiqB0oYBr7pUR2EtrTO', 'admin', '2023-03-28 12:00:00', 'çelikbaş'),
(2, 'Veri Girişi', 'user@filotak.ip', '$2y$10$9mf8yzlzMqCj6fWE9F6T3OC.XycX2PE/3wDQ4ViIFpN.XjfDtpK9y', 'user', '2023-03-28 12:00:00', 'Kullanıcısı'),
(3, 'Mehmet', 'mehmetborekci@duygu.com', '$2y$10$uYCHzuSbC.Ulf4doJSPqsO7HHRqOZAjYtvrwdT1.NNAeDXHTzkP6q', 'user', '2025-04-06 09:32:44', 'BÖREKÇİ');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `plate_number` varchar(20) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `vehicle_type` enum('Otomobil','Kamyonet','Damperli Kamyon','Istıcı Kazan','Beton Santrali','Silindir','Loder','Bekoloder','Ekskavatör','Akaryakıt Tankı','Yağlama ve Bakım','Mikserler','Çekici','Arazöz','Mobil Beton Pompası','Jeneratör') NOT NULL,
  `status` enum('Aktif','Pasif','Bakımda') NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_number` varchar(50) DEFAULT NULL,
  `equipment_number` varchar(50) DEFAULT NULL,
  `fixed_asset_number` varchar(50) DEFAULT NULL,
  `cost_center` varchar(100) DEFAULT NULL,
  `production_site` varchar(100) DEFAULT NULL,
  `inspection_date` date DEFAULT NULL,
  `traffic_insurance_agency` varchar(100) DEFAULT NULL,
  `traffic_insurance_date` date DEFAULT NULL,
  `casco_insurance_agency` varchar(100) DEFAULT NULL,
  `casco_insurance_date` date DEFAULT NULL,
  `work_site` varchar(100) DEFAULT NULL,
  `initial_km` int(11) DEFAULT NULL COMMENT 'Aracın sisteme giriş km bilgisi',
  `initial_hours` decimal(10,2) DEFAULT NULL COMMENT 'Aracın sisteme giriş çalışma saati',
  `current_km` int(11) DEFAULT NULL COMMENT 'Aracın mevcut kilometre bilgisi',
  `current_hours` decimal(10,2) DEFAULT NULL COMMENT 'Aracın mevcut çalışma saati'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `vehicles`
--

INSERT INTO `vehicles` (`id`, `plate_number`, `brand`, `model`, `year`, `company_id`, `vehicle_type`, `status`, `created_at`, `order_number`, `equipment_number`, `fixed_asset_number`, `cost_center`, `production_site`, `inspection_date`, `traffic_insurance_agency`, `traffic_insurance_date`, `casco_insurance_agency`, `casco_insurance_date`, `work_site`, `initial_km`, `initial_hours`, `current_km`, `current_hours`) VALUES
(1, '34 ABC 123', 'Mercedes', 'Sprinter', 2020, 1, 'Otomobil', 'Aktif', '2023-03-20 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, '34 DEF 456', 'Ford', 'Transit', 2019, 4, 'Otomobil', 'Aktif', '2023-03-20 13:30:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, '34 GHI 789', 'Volvo', 'FH16', 2021, 3, 'Silindir', 'Pasif', '2023-03-20 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, '34 JKL 012', 'Renault', 'Clio', 2022, 4, 'Otomobil', 'Aktif', '2023-03-21 09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, '34 MNO 345', 'Toyota', 'Corolla', 2022, 1, 'Otomobil', '', '2023-03-21 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, '34 PQR 678', 'Mercedes', 'Travego', 2018, 1, 'Çekici', 'Bakımda', '2023-03-21 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, '34 STU 901', 'Volvo', '9700', 2019, 3, 'Akaryakıt Tankı', 'Aktif', '2023-03-21 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11551, 11510.00),
(8, '34 VWX 234', 'Scania', 'R450', 2020, 3, 'Loder', 'Aktif', '2023-03-21 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, '34 YZA 567', 'MAN', 'TGX', 2021, 1, 'Damperli Kamyon', 'Pasif', '2023-03-21 14:00:00', 'EA14552214', '154151', 'EA11455225', 'YOZGT', 'YOZGAT', '2025-05-22', 'SENDEO', '2025-05-22', 'SENDEO', '2025-05-22', 'AZİZLİ BAGLARİ', NULL, NULL, NULL, NULL),
(10, '34 BCD 890', 'Ford', 'Courier', 2022, 4, 'Kamyonet', 'Bakımda', '2023-03-21 15:00:00', 'ea1454555', '1455454', '215445454', 'yozgat', 'yozgat', '2026-12-05', 'panpa', '2026-12-05', 'panpa', '2025-04-04', '116 saha', NULL, NULL, NULL, NULL),
(11, 'EX-32', 'CATEPİLLER', '336D', 2020, 4, 'Ekskavatör', 'Aktif', '2025-04-02 07:56:54', 'E0010000051', '10000051', '-', '100327', 'YOZGAT', '2025-05-25', 'HEPİYİ', '2025-04-04', 'HEPİYİ', '2025-05-25', 'BALIŞEYH ŞANTİYESİ', NULL, 13450.00, 175200, 11350.00),
(12, '66 DE 953', 'FORD', 'CONNET', 2025, 4, 'Otomobil', 'Aktif', '2025-04-03 09:13:42', 'E0010000011', '10000011', '255000000217', '100328', 'YOZGAT', '2025-04-29', 'SAMPO', '2025-04-29', 'SAMPO', '2025-04-29', 'AKDAĞMADENİ ŞANTİYESİ', NULL, NULL, NULL, NULL),
(13, '19 BG 321', 'VOLKSWAGEN', 'GOLF 1.0 E-TSİ', 2021, 4, 'Loder', 'Bakımda', '2025-04-05 06:12:25', 'E0030000046', '30000046', '254000000193', '100327', '100037', '2025-04-05', 'HDİ SİGORTA', '2025-04-14', 'HDİ KASKO', '2025-04-14', 'YOZGAT ŞANTİYESİ', 34500, NULL, 158500, 101010.00),
(14, '06 GB 0972', 'MAN', '41400', 2021, 4, 'Damperli Kamyon', 'Aktif', '2025-04-15 10:58:05', 'EA1155441', 'EK1554454', 'SK255555', '100', 'YOZGAT', '2025-12-09', 'HEPSİKO', '2025-12-09', 'HEOSİKO', '2025-12-09', '96 SAHA', 145500, 11350.00, NULL, NULL),
(15, '06 AEP 866', 'RENAULT', 'KADJAR', 2020, 4, 'Otomobil', 'Aktif', '2025-04-17 07:43:24', 'EA1155544', 'EK554454', 'SK4455477', '100', '66', '2025-12-01', 'HDŞİ', '2025-12-01', 'HDŞİS', '2025-12-01', '96 SAHA', 13550, 11025.00, 34350, NULL),
(16, '06 GA 0225', 'MERCEDES', '41400', 2020, 4, 'Mobil Beton Pompası', 'Aktif', '2025-04-21 07:30:18', 'E0030000221', '30000221', '254000000368', '100328', 'YOZGAT', '2025-12-09', 'SAMPO', '2025-12-09', 'SAMPO', '2025-12-09', '96 SAHA', 117500, 11350.00, 118100, 12100.00);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vehicle_assignments`
--

CREATE TABLE `vehicle_assignments` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('Aktif','Tamamlandı','İptal') NOT NULL DEFAULT 'Aktif',
  `location` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `vehicle_assignments`
--

INSERT INTO `vehicle_assignments` (`id`, `vehicle_id`, `driver_id`, `start_date`, `end_date`, `status`, `location`, `notes`, `created_at`) VALUES
(1, 1, 1, '2023-03-22', '2025-04-12', 'Tamamlandı', NULL, 'Düzenli şehir içi teslimat', '2023-03-22 12:00:00'),
(2, 2, 2, '2023-03-22', NULL, 'Aktif', NULL, 'Uzun yol teslimatları', '2023-03-22 12:30:00'),
(3, 4, 3, '2023-03-22', '2023-04-05', 'Tamamlandı', NULL, 'Yönetici aracı', '2023-03-22 13:00:00'),
(4, 5, 4, '2023-03-23', NULL, 'Aktif', NULL, 'Şehir içi kısa mesafe', '2023-03-23 08:00:00'),
(5, 6, 5, '2023-03-23', NULL, 'Aktif', NULL, 'Şehirlerarası personel taşıma', '2023-03-23 09:00:00'),
(6, 8, 6, '2023-03-23', NULL, 'Aktif', '96 SAHA', 'Ağır yük taşıma', '2023-03-23 10:00:00'),
(7, 10, 8, '2023-03-24', NULL, 'Aktif', NULL, 'Hızlı teslimat', '2023-03-24 08:00:00'),
(8, 11, 7, '2025-04-02', NULL, 'Aktif', NULL, 'KULLAN BAKALM', '2025-04-02 08:01:42'),
(9, 12, 14, '2025-04-04', '2025-04-20', 'Tamamlandı', NULL, 'not ekledik', '2025-04-04 06:45:35'),
(10, 4, 16, '2025-04-05', '2025-04-25', 'Aktif', '119 saha', 'saha 13 için görevlendirilen yeni personel', '2025-04-05 07:02:04'),
(11, 7, 21, '2025-04-06', '2025-05-11', 'Aktif', '119 saha', '112 SAHA İÇİN VERİLEN', '2025-04-06 08:20:26'),
(12, 13, 10, '2025-04-12', '2025-12-22', 'Aktif', '96 SAHA', '', '2025-04-12 18:46:40'),
(13, 1, 19, '2025-04-12', NULL, 'Aktif', '113 saha', 'yeni alındı', '2025-04-12 19:37:29'),
(14, 14, 22, '2025-04-15', NULL, 'Aktif', '118 SAHA', '', '2025-04-15 11:49:24'),
(15, 15, 23, '2025-04-17', NULL, 'Aktif', '96 SAHA', 'SERVİS SÖFÖRRÜ', '2025-04-17 07:44:49'),
(16, 16, 18, '2025-04-21', '2025-04-25', 'Aktif', '96 SAHA', 'DEMPERLİ KAMYON MOLOZ İŞİ', '2025-04-21 07:31:25');

-- --------------------------------------------------------

--
-- Görünüm yapısı durumu `vehicle_last_readings`
-- (Asıl görünüm için aşağıya bakın)
--
CREATE TABLE `vehicle_last_readings` (
`id` int(11)
,`plate_number` varchar(20)
,`vehicle_type` enum('Otomobil','Kamyonet','Damperli Kamyon','Istıcı Kazan','Beton Santrali','Silindir','Loder','Bekoloder','Ekskavatör','Akaryakıt Tankı','Yağlama ve Bakım','Mikserler','Çekici','Arazöz','Mobil Beton Pompası','Jeneratör')
,`initial_km` int(11)
,`initial_hours` decimal(10,2)
,`current_km` int(11)
,`current_hours` decimal(10,2)
,`last_km` int(11)
,`last_hours` decimal(10,2)
,`last_reading_date` date
,`total_km` bigint(12)
,`total_hours` decimal(11,2)
);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vehicle_readings`
--

CREATE TABLE `vehicle_readings` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `km_reading` int(11) DEFAULT NULL COMMENT 'Kilometre bilgisi',
  `hour_reading` decimal(10,2) DEFAULT NULL COMMENT 'Çalışma saati',
  `date` date NOT NULL,
  `source` enum('Fuel','Maintenance','Manual') NOT NULL COMMENT 'Kaynak: Yakıt, Bakım veya Manuel',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tetikleyiciler `vehicle_readings`
--
DELIMITER $$
CREATE TRIGGER `validate_vehicle_readings` BEFORE INSERT ON `vehicle_readings` FOR EACH ROW BEGIN
    DECLARE v_track_km TINYINT;
    DECLARE v_track_hours TINYINT;
    DECLARE v_current_km INT;
    DECLARE v_current_hours DECIMAL(10,2);
    
    SELECT track_km, track_hours, current_km, current_hours 
    INTO v_track_km, v_track_hours, v_current_km, v_current_hours
    FROM vehicle_type_trackings vtt
    JOIN vehicles v ON vtt.vehicle_type = v.vehicle_type
    WHERE v.id = NEW.vehicle_id;
    
    IF v_track_km = 0 AND NEW.km_reading IS NOT NULL THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Bu araç tipi için km takibi yapılmamaktadır';
    END IF;
    
    IF v_track_hours = 0 AND NEW.hour_reading IS NOT NULL THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Bu araç tipi için saat takibi yapılmamaktadır';
    END IF;
    
    IF v_track_km = 1 AND NEW.km_reading IS NOT NULL AND NEW.km_reading < v_current_km THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Yeni kilometre mevcut kilometreden küçük olamaz';
    END IF;
    
    IF v_track_hours = 1 AND NEW.hour_reading IS NOT NULL AND NEW.hour_reading < v_current_hours THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Yeni saat mevcut saatten küçük olamaz';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vehicle_type_trackings`
--

CREATE TABLE `vehicle_type_trackings` (
  `id` int(11) NOT NULL,
  `vehicle_type` enum('Otomobil','Kamyonet','Damperli Kamyon','Istıcı Kazan','Beton Santrali','Silindir','Loder','Bekoloder','Ekskavatör','Akaryakıt Tankı','Yağlama ve Bakım','Mikserler','Çekici','Arazöz','Mobil Beton Pompası','Jeneratör') NOT NULL,
  `track_km` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Kilometre takibi yapılıyorsa 1',
  `track_hours` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Saat takibi yapılıyorsa 1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `vehicle_type_trackings`
--

INSERT INTO `vehicle_type_trackings` (`id`, `vehicle_type`, `track_km`, `track_hours`) VALUES
(1, 'Otomobil', 1, 0),
(2, 'Kamyonet', 1, 0),
(3, 'Damperli Kamyon', 1, 1),
(4, 'Çekici', 1, 1),
(5, 'Ekskavatör', 0, 1),
(6, 'Loder', 0, 1),
(7, 'Silindir', 0, 1),
(8, 'Istıcı Kazan', 0, 1),
(9, 'Beton Santrali', 0, 1),
(10, 'Bekoloder', 0, 1),
(11, 'Akaryakıt Tankı', 0, 1),
(12, 'Yağlama ve Bakım', 0, 1),
(13, 'Mikserler', 1, 1),
(14, 'Arazöz', 1, 1),
(15, 'Mobil Beton Pompası', 0, 1),
(16, 'Jeneratör', 0, 1);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `certificate_types`
--
ALTER TABLE `certificate_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Tablo için indeksler `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tax_number` (`tax_number`),
  ADD KEY `idx_company_status` (`status`);

--
-- Tablo için indeksler `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identity_number` (`identity_number`),
  ADD UNIQUE KEY `license_number` (`license_number`),
  ADD KEY `idx_drivers_company` (`company_id`);

--
-- Tablo için indeksler `driver_certificates`
--
ALTER TABLE `driver_certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `certificate_type_id` (`certificate_type_id`);

--
-- Tablo için indeksler `driver_licenses`
--
ALTER TABLE `driver_licenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `license_type_id` (`license_type_id`);

--
-- Tablo için indeksler `fine_payments`
--
ALTER TABLE `fine_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fine_id` (`fine_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Tablo için indeksler `fine_types`
--
ALTER TABLE `fine_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Tablo için indeksler `fuel_purchases`
--
ALTER TABLE `fuel_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tank_id` (`tank_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Tablo için indeksler `fuel_records`
--
ALTER TABLE `fuel_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tank_id` (`tank_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `fk_dispenser` (`dispenser_id`);

--
-- Tablo için indeksler `fuel_tanks`
--
ALTER TABLE `fuel_tanks`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `fuel_transfers`
--
ALTER TABLE `fuel_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `source_tank_id` (`source_tank_id`),
  ADD KEY `destination_tank_id` (`destination_tank_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Tablo için indeksler `license_types`
--
ALTER TABLE `license_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Tablo için indeksler `maintenance_records`
--
ALTER TABLE `maintenance_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Tablo için indeksler `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `type` (`type`);

--
-- Tablo için indeksler `traffic_fines`
--
ALTER TABLE `traffic_fines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fine_number` (`fine_number`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `fine_type_id` (`fine_type_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Tablo için indeksler `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plate_number` (`plate_number`),
  ADD KEY `idx_vehicles_company` (`company_id`),
  ADD KEY `idx_vehicle_type` (`vehicle_type`);

--
-- Tablo için indeksler `vehicle_assignments`
--
ALTER TABLE `vehicle_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Tablo için indeksler `vehicle_readings`
--
ALTER TABLE `vehicle_readings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vehicle_date` (`vehicle_id`,`date`);

--
-- Tablo için indeksler `vehicle_type_trackings`
--
ALTER TABLE `vehicle_type_trackings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicle_type` (`vehicle_type`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `certificate_types`
--
ALTER TABLE `certificate_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Tablo için AUTO_INCREMENT değeri `driver_certificates`
--
ALTER TABLE `driver_certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Tablo için AUTO_INCREMENT değeri `driver_licenses`
--
ALTER TABLE `driver_licenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Tablo için AUTO_INCREMENT değeri `fine_payments`
--
ALTER TABLE `fine_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `fine_types`
--
ALTER TABLE `fine_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `fuel_purchases`
--
ALTER TABLE `fuel_purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `fuel_records`
--
ALTER TABLE `fuel_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Tablo için AUTO_INCREMENT değeri `fuel_tanks`
--
ALTER TABLE `fuel_tanks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `fuel_transfers`
--
ALTER TABLE `fuel_transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `license_types`
--
ALTER TABLE `license_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Tablo için AUTO_INCREMENT değeri `maintenance_records`
--
ALTER TABLE `maintenance_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- Tablo için AUTO_INCREMENT değeri `traffic_fines`
--
ALTER TABLE `traffic_fines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Tablo için AUTO_INCREMENT değeri `vehicle_assignments`
--
ALTER TABLE `vehicle_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Tablo için AUTO_INCREMENT değeri `vehicle_readings`
--
ALTER TABLE `vehicle_readings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `vehicle_type_trackings`
--
ALTER TABLE `vehicle_type_trackings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

-- --------------------------------------------------------

--
-- Görünüm yapısı `vehicle_last_readings`
--
DROP TABLE IF EXISTS `vehicle_last_readings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vehicle_last_readings`  AS SELECT `v`.`id` AS `id`, `v`.`plate_number` AS `plate_number`, `v`.`vehicle_type` AS `vehicle_type`, `v`.`initial_km` AS `initial_km`, `v`.`initial_hours` AS `initial_hours`, `v`.`current_km` AS `current_km`, `v`.`current_hours` AS `current_hours`, `vr`.`km_reading` AS `last_km`, `vr`.`hour_reading` AS `last_hours`, `vr`.`date` AS `last_reading_date`, `vr`.`km_reading`- `v`.`initial_km` AS `total_km`, `vr`.`hour_reading`- `v`.`initial_hours` AS `total_hours` FROM ((`vehicles` `v` left join (select `vehicle_readings`.`vehicle_id` AS `vehicle_id`,max(`vehicle_readings`.`date`) AS `max_date` from `vehicle_readings` group by `vehicle_readings`.`vehicle_id`) `latest` on(`v`.`id` = `latest`.`vehicle_id`)) left join `vehicle_readings` `vr` on(`vr`.`vehicle_id` = `latest`.`vehicle_id` and `vr`.`date` = `latest`.`max_date`)) ;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `fk_drivers_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL;

--
-- Tablo kısıtlamaları `driver_certificates`
--
ALTER TABLE `driver_certificates`
  ADD CONSTRAINT `driver_certificates_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `driver_certificates_ibfk_2` FOREIGN KEY (`certificate_type_id`) REFERENCES `certificate_types` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `driver_licenses`
--
ALTER TABLE `driver_licenses`
  ADD CONSTRAINT `driver_licenses_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `driver_licenses_ibfk_2` FOREIGN KEY (`license_type_id`) REFERENCES `license_types` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `fine_payments`
--
ALTER TABLE `fine_payments`
  ADD CONSTRAINT `fine_payments_ibfk_1` FOREIGN KEY (`fine_id`) REFERENCES `traffic_fines` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fine_payments_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `fuel_purchases`
--
ALTER TABLE `fuel_purchases`
  ADD CONSTRAINT `fuel_purchases_ibfk_1` FOREIGN KEY (`tank_id`) REFERENCES `fuel_tanks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fuel_purchases_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `fuel_records`
--
ALTER TABLE `fuel_records`
  ADD CONSTRAINT `fk_dispenser` FOREIGN KEY (`dispenser_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fuel_records_ibfk_1` FOREIGN KEY (`tank_id`) REFERENCES `fuel_tanks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fuel_records_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fuel_records_ibfk_3` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fuel_records_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `fuel_transfers`
--
ALTER TABLE `fuel_transfers`
  ADD CONSTRAINT `fuel_transfers_ibfk_1` FOREIGN KEY (`source_tank_id`) REFERENCES `fuel_tanks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fuel_transfers_ibfk_2` FOREIGN KEY (`destination_tank_id`) REFERENCES `fuel_tanks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fuel_transfers_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `maintenance_records`
--
ALTER TABLE `maintenance_records`
  ADD CONSTRAINT `maintenance_records_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `maintenance_records_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `system_logs`
--
ALTER TABLE `system_logs`
  ADD CONSTRAINT `system_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Tablo kısıtlamaları `traffic_fines`
--
ALTER TABLE `traffic_fines`
  ADD CONSTRAINT `traffic_fines_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `traffic_fines_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `traffic_fines_ibfk_3` FOREIGN KEY (`fine_type_id`) REFERENCES `fine_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `traffic_fines_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `fk_vehicles_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL;

--
-- Tablo kısıtlamaları `vehicle_assignments`
--
ALTER TABLE `vehicle_assignments`
  ADD CONSTRAINT `vehicle_assignments_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicle_assignments_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `vehicle_readings`
--
ALTER TABLE `vehicle_readings`
  ADD CONSTRAINT `vehicle_readings_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
