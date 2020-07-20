-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 15, 2020 lúc 05:55 PM
-- Phiên bản máy phục vụ: 10.4.11-MariaDB
-- Phiên bản PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `raspberry`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `devices`
--

CREATE TABLE `devices` (
  `device_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `devices`
--

INSERT INTO `devices` (`device_id`, `created_at`, `updated_at`) VALUES
(4, '2020-07-15 15:17:11', '2020-07-15 15:17:11'),
(5, '2020-07-15 15:17:11', '2020-07-15 15:17:11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nutrients`
--

CREATE TABLE `nutrients` (
  `id` int(10) UNSIGNED NOT NULL,
  `plant_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `ppm_min` int(11) NOT NULL,
  `ppm_max` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `nutrients`
--

INSERT INTO `nutrients` (`id`, `plant_name`, `ppm_min`, `ppm_max`, `created_at`, `updated_at`) VALUES
(1, 'Húng quế', 500, 800, '2020-07-15 15:20:59', '2020-07-15 15:20:59'),
(2, 'Bắp cải', 700, 1200, '2020-07-15 15:20:59', '2020-07-15 15:20:59'),
(3, 'Cần tây', 750, 1200, '2020-07-15 15:22:53', '2020-07-15 15:22:53'),
(4, 'Cải xoong', 600, 1200, '2020-07-15 15:22:53', '2020-07-15 15:22:53'),
(5, 'Cải xanh', 600, 1200, '2020-07-15 15:24:07', '2020-07-15 15:24:07'),
(6, 'Tía tô', 800, 1000, '2020-07-15 15:24:07', '2020-07-15 15:24:07'),
(7, 'Bạc hà', 500, 700, '2020-07-15 15:24:46', '2020-07-15 15:24:46'),
(8, 'Cải bó xôi', 900, 1750, '2020-07-15 15:24:46', '2020-07-15 15:24:46'),
(9, 'Húng lủi', 650, 850, '2020-07-15 15:26:30', '2020-07-15 15:26:30'),
(10, 'Rau muống', 400, 850, '2020-07-15 15:26:30', '2020-07-15 15:26:30'),
(11, 'Xà lách', 400, 750, '2020-07-15 15:27:10', '2020-07-15 15:27:10');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ppm_automatic`
--

CREATE TABLE `ppm_automatic` (
  `id` int(10) UNSIGNED NOT NULL,
  `device_id` int(10) UNSIGNED NOT NULL,
  `nutrient_id` int(10) UNSIGNED NOT NULL,
  `auto_mode` tinyint(1) NOT NULL DEFAULT 0,
  `auto_status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `ppm_automatic`
--

INSERT INTO `ppm_automatic` (`id`, `device_id`, `nutrient_id`, `auto_mode`, `auto_status`, `created_at`, `updated_at`) VALUES
(1, 4, 5, 0, 0, '2020-07-15 15:42:16', '2020-07-15 15:42:16'),
(2, 6, 3, 0, 0, '2020-07-15 15:42:16', '2020-07-15 15:42:16');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pump_automatic`
--

CREATE TABLE `pump_automatic` (
  `id` int(10) UNSIGNED NOT NULL,
  `device_id` int(10) UNSIGNED NOT NULL,
  `time_on` int(10) NOT NULL DEFAULT 5,
  `time_off` int(10) NOT NULL DEFAULT 10,
  `auto` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `pump_automatic`
--

INSERT INTO `pump_automatic` (`id`, `device_id`, `time_on`, `time_off`, `auto`, `created_at`, `updated_at`) VALUES
(1, 4, 5, 10, 0, '2020-07-15 15:45:27', '2020-07-15 15:45:27'),
(2, 5, 10, 20, 0, '2020-07-15 15:45:27', '2020-07-15 15:45:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sensors`
--

CREATE TABLE `sensors` (
  `id` int(10) UNSIGNED NOT NULL,
  `device_id` int(10) UNSIGNED NOT NULL,
  `temperature` int(10) NOT NULL DEFAULT 0,
  `humidity` int(10) NOT NULL DEFAULT 0,
  `light` int(10) NOT NULL DEFAULT 0,
  `EC` double(8,2) NOT NULL DEFAULT 0.00,
  `PPM` int(10) NOT NULL DEFAULT 0,
  `water` int(10) NOT NULL DEFAULT 0,
  `pump` tinyint(1) NOT NULL DEFAULT 0,
  `water_in` tinyint(1) NOT NULL DEFAULT 0,
  `water_out` tinyint(1) NOT NULL DEFAULT 0,
  `mix` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `sensors`
--

INSERT INTO `sensors` (`id`, `device_id`, `temperature`, `humidity`, `light`, `EC`, `PPM`, `water`, `pump`, `water_in`, `water_out`, `mix`, `created_at`, `updated_at`) VALUES
(1, 4, 32, 60, 80, 0.08, 59, 62, 0, 0, 0, 0, '2020-07-15 15:53:11', '2020-07-15 15:53:11'),
(2, 5, 35, 51, 92, 0.09, 72, 53, 0, 0, 0, 0, '2020-07-15 15:53:11', '2020-07-15 15:53:11');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`device_id`);

--
-- Chỉ mục cho bảng `nutrients`
--
ALTER TABLE `nutrients`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `ppm_automatic`
--
ALTER TABLE `ppm_automatic`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `pump_automatic`
--
ALTER TABLE `pump_automatic`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `sensors`
--
ALTER TABLE `sensors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `nutrients`
--
ALTER TABLE `nutrients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `ppm_automatic`
--
ALTER TABLE `ppm_automatic`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `pump_automatic`
--
ALTER TABLE `pump_automatic`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `sensors`
--
ALTER TABLE `sensors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
