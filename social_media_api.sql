-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2023 at 05:08 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `social_media_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `apis`
--

CREATE TABLE `apis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `account_type` varchar(255) NOT NULL,
  `account_id` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `account_pic` text DEFAULT NULL,
  `account_link` text NOT NULL,
  `token` varchar(1000) NOT NULL,
  `token_secret` varchar(1000) DEFAULT NULL,
  `update_interval` int(11) NOT NULL DEFAULT 60,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `apis`
--

INSERT INTO `apis` (`id`, `creator_id`, `account_type`, `account_id`, `account_name`, `email`, `account_pic`, `account_link`, `token`, `token_secret`, `update_interval`, `created_at`, `updated_at`) VALUES
(3, 2, 'twitter', '1708817086653386752', 'evolve inc', 'evolve.teck@gmail.com', 'http://192.168.1.15:8000/storage/profile_images/1699446013.png', 'https://twitter.com/EvolveTeck', '1708817086653386752-rrWnFqX4ALKzZMXWgYdhaatcj7Il2f', 'FFgoPOWMuGuI1Zfx5pZBphOEWBPSpyYtS7G4jPPs1K5X0', 3000, '2023-11-07 07:16:11', '2023-11-08 10:20:13'),
(4, 1, 'twitter', '1708817086653386752', 'evolve inc', 'evolve.teck@gmail.com', 'http://192.168.1.15:8000/storage/profile_images/1701508644.png', 'https://twitter.com/EvolveTeck', '1708817086653386752-rrWnFqX4ALKzZMXWgYdhaatcj7Il2f', 'FFgoPOWMuGuI1Zfx5pZBphOEWBPSpyYtS7G4jPPs1K5X0', 3000, '2023-11-14 11:35:51', '2023-12-02 07:17:25'),
(7, 1, 'youtube', 'UCDtui0aBpRnx1eNAzq7eUHQ', 'evovle inc', 'evovle inc', 'https://yt3.ggpht.com/ytc/APkrFKZhta5mRe5QbypayrwKrcYOl2L65mX_uncGM8F4yVLU3GonpgFTrQ6j22XgIdLg=s240-c-k-c0xffffffff-no-rj-mo\r\n', 'https://www.youtube.com/channel/UCDtui0aBpRnx1eNAzq7eUHQ', 'ya29.a0AfB_byB9ZUyzCgAo7b5FlMknnV7SYCrX4pzdzpaoFipC-TEMt6KCD4v-JXsvVoPi_usZwGMVp-agZb8wHLbpiCP8Td-FjN9FBdM-EYMznCVO0FqqpwoxmEwLEttzPlvUHjz-L6KKeMs64dnJuwqwZE6u0apzFxSAyyemaCgYKAW0SARMSFQGOcNnCiGhnaAspFUBNCMqzILsxbQ0171\r\n', '1//03OvEbi37WB3TCgYIARAAGAMSNwF-L9IreW7zjQ2dmbRQsoUx9UwIhY3-n4v--YSy4gw3s6KVuQLvDnRoGJNS0heO13RQr5-rSsQ\r\n', 60, NULL, NULL),
(8, 1, 'youtube', 'UCFfozYKZZoCfh_Rs9gMujhQ', 'technology', 'technology', 'https://yt3.ggpht.com/ytc/APkrFKZXFiTCCxbhxXfRcKFRhOaYzS0vBwSrqko1PsRT38ibgk9arbawHBA_hpYP5Bx_=s88-c-k-c0x00ffffff-no-rj\r\n', 'https://www.youtube.com/channel/UCFfozYKZZoCfh_Rs9gMujhQ', 'ya29.a0AfB_byCJiJxZ6lXFfiuHU9wnDI8togP2ISuNR6bWnM-lzsI-GGZUQmAxKcsu8tK-6flirobRldhv6E2pDGdeAaC46TsSa_EGWNzOTeN9u91O1AW4OOV9D_URcorH1nzSR5SLqUF_yvFkIEdBnChc7f3VNSINCw6pUc69aCgYKAYISARISFQHGX2MipmLzRLUmzImafsmcvh-y0Q0171', '1//03jU0iAd0UcmKCgYIARAAGAMSNwF-L9IrtNFMW05K7WAZ9EcbQeWoELQywWqbXsGbiwKjNhcFh1iizgAKcKxzDKdHdJ0PP5gzqow', 60, NULL, NULL),
(11, 1, 'youtube', 'UCH3ydgnuMGdAer1YlZ4HCJQ', 'evolve inc', 'evolve inc', 'https://yt3.ggpht.com/ytc/APkrFKbHW2r9TKDhz8mXpxZfZ0LGEkfvebgxmNOc_PvvxcwubpoW4jBwF-wsZPhx6T7m=s88-c-k-c0x00ffffff-no-rj', 'https:\\/\\/www.youtube.com\\/channel\\/UCH3ydgnuMGdAer1YlZ4HCJQ', 'ya29.a0AfB_byB8ITNsbG3qzvOLVYt_ehB_tSgq2F8b0zoltz6_53V8MafMWaZHWegiC5j6AHF-Yv-fB_u84pof3ANEo15mx43TVnJd4eMV7P9oi0z8wVkiugA2H_77dZFP0aCkz5vHt3r-ffgNJ5ebzCWS-1kZbAdUGsBAtNEOaCgYKAdgSARESFQHGX2MiR3vC9IaDAzznkkk3Idb1lQ0171', '1\\/\\/06bj1xwtcz7fwCgYIARAAGAYSNwF-L9IryCu9vP2V5blvvBI3Xe4K10tAhCB7mbDVD65QoeAxEAhzarVw7R4kgJ8lmJuOP28xkzk', 60, NULL, NULL),
(12, 1, 'twitter', '1719665953183506432', 'evolve inc', 'evolveinc229@gmail.com', 'http://192.168.1.15:8000/storage/profile_images/1701508668.png', 'https://twitter.com/evolve_inc32', '1719665953183506432-RlFCBFhbNfwBHbR5l30ZzyMyQXhjgk', 'UARlQWfxAUdvi9kRQBNF3w7SgSUSAxqMhXRyj2b3YWalL', 60, '2023-12-02 07:17:48', '2023-12-02 07:17:48');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instagrams`
--

CREATE TABLE `instagrams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `insta_name` varchar(255) NOT NULL,
  `insta_token` varchar(1000) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `collection_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `disk` varchar(255) NOT NULL,
  `conversions_disk` varchar(255) DEFAULT NULL,
  `size` bigint(20) UNSIGNED NOT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`manipulations`)),
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`custom_properties`)),
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`generated_conversions`)),
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`responsive_images`)),
  `order_column` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `model_type`, `model_id`, `uuid`, `collection_name`, `name`, `file_name`, `mime_type`, `disk`, `conversions_disk`, `size`, `manipulations`, `custom_properties`, `generated_conversions`, `responsive_images`, `order_column`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, '60eb1a88-8bcc-4686-8110-c77db041cf7a', 'profile_images', '12', '12.jpg', 'image/jpeg', 'public', 'public', 28075, '[]', '[]', '[]', '[]', 1, '2023-11-15 14:08:02', '2023-11-15 14:08:02');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `msg` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `sender` varchar(255) NOT NULL,
  `receiver` varchar(255) NOT NULL,
  `is_seen` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2023_07_10_193132_create_apis_table', 1),
(7, '2023_07_11_085533_create_instagrams_table', 1),
(8, '2023_07_25_120359_create_social_posts_table', 1),
(9, '2023_08_21_101501_create_time_thinks_table', 1),
(10, '2023_09_06_140331_create_youtube_categories_table', 1),
(11, '2023_09_06_141453_create_settings_apis_table', 1),
(12, '2023_09_07_084231_create_publish_posts_table', 1),
(13, '2023_10_17_133937_create_post_images_table', 1),
(14, '2023_10_21_124254_create_permission_tables', 1),
(15, '2023_10_21_145259_create_user_has_roles_table', 1),
(16, '2023_10_24_131038_create_post_videos_table', 1),
(17, '2023_07_08_112058_create_messages_table', 2),
(18, '2023_07_19_081931_update_users', 2),
(28, '2023_11_07_142535_create_subscribers_table', 3),
(29, '2023_11_09_095934_create_subscriber_requests_table', 3),
(31, '2023_11_09_114219_create_news_letters_table', 4),
(34, '2023_11_11_132322_alter_users_table', 5),
(35, '2023_11_15_154812_create_media_table', 6),
(41, '2023_11_21_152744_add_verification_token_to_users_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_letters`
--

CREATE TABLE `news_letters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `title` text NOT NULL,
  `content` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `color` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_letters`
--

INSERT INTO `news_letters` (`id`, `creator_id`, `title`, `content`, `image`, `color`, `created_at`, `updated_at`) VALUES
(2, 1, 'A well-known quote, contained in a blockquote element.', '- Someone famous in Source Title', NULL, '#e0f7fc', '2023-11-09 12:37:24', '2023-11-09 12:37:24'),
(3, 1, 'Card title that wraps to a new line', 'This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.', 'http://192.168.1.15:8000/storage/newsLetter/1700396814_18.jpg', NULL, '2023-11-09 12:37:24', '2023-11-19 10:26:54');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'dashboard.owner', 'web', NULL, '2023-11-12 09:42:57'),
(2, 'posts.create', 'web', NULL, NULL),
(3, 'posts.edit', 'web', NULL, NULL),
(4, 'posts.delete', 'web', NULL, NULL),
(5, 'newsletter.show', 'web', NULL, NULL),
(6, 'newsletter.create', 'web', NULL, NULL),
(7, 'newsletter.edit', 'web', NULL, NULL),
(8, 'newsletter.delete', 'web', NULL, NULL),
(9, 'dashboard.forEachUser', 'web', '2023-11-12 09:14:52', '2023-11-12 09:42:52'),
(10, 'roles.add', 'web', '2023-11-12 13:51:37', '2023-11-13 09:40:20'),
(11, 'roles.edit', 'web', '2023-11-12 13:51:52', '2023-11-13 09:41:32'),
(12, 'roles.assign_roles_to_user', 'web', '2023-11-12 13:53:12', '2023-11-16 13:32:49'),
(13, 'pages.link', 'web', '2023-11-13 08:08:56', '2023-11-13 08:57:29'),
(14, 'services', 'web', '2023-11-13 08:52:58', '2023-11-13 08:52:58'),
(15, 'users.all', 'web', '2023-11-13 08:53:32', '2023-11-13 08:53:32'),
(16, 'users.profile', 'web', '2023-11-13 08:54:47', '2023-11-13 08:54:58'),
(17, 'Youtube_categories', 'web', '2023-11-13 08:59:00', '2023-11-13 08:59:00'),
(18, 'subscribers.all', 'web', '2023-11-13 08:59:33', '2023-11-13 08:59:33'),
(19, 'subscribers.add', 'web', '2023-11-13 08:59:53', '2023-11-13 08:59:53'),
(22, 'roles.assign_role_to_permissions', 'web', '2023-11-13 09:49:42', '2023-11-13 09:50:26'),
(23, 'roles.permissions.add', 'web', '2023-11-13 09:52:04', '2023-11-13 09:52:04'),
(24, 'roles.permissions.edit', 'web', '2023-11-13 09:53:13', '2023-11-13 09:53:13'),
(25, 'media.view', 'web', '2023-11-16 12:10:19', '2023-11-16 12:10:19'),
(26, 'media.add', 'web', '2023-11-16 12:12:25', '2023-11-16 12:12:25');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(7, 'App\\Models\\User', 2, 'API TOKEN', 'ff0f9ac1367165c4eef1b2a8bca57b14c61374f4bb58c84ee3fbaca31485c7bd', '[\"*\"]', '2023-11-09 07:07:04', NULL, '2023-11-08 07:32:18', '2023-11-09 07:07:04'),
(58, 'App\\Models\\User', 4, 'API TOKEN', 'd50b4f14ac09498ef772b73a9754540f201be2d55c947b910f2bf260247d5ed6', '[\"*\"]', '2023-11-20 08:11:52', NULL, '2023-11-20 07:48:36', '2023-11-20 08:11:52'),
(59, 'App\\Models\\User', 6, 'API TOKEN', '493c7e525f74c5d18dbedd06d9d4b1a0803a3da0816cba0a75916e25646e2318', '[\"*\"]', '2023-11-20 09:08:41', NULL, '2023-11-20 08:14:04', '2023-11-20 09:08:41'),
(60, 'App\\Models\\User', 6, 'API TOKEN', 'fdc4bf57ebba15706f2fc2e04f7b54ed1e7deb14029f084da5eddbc966474a2e', '[\"*\"]', '2023-11-20 08:49:47', NULL, '2023-11-20 08:37:13', '2023-11-20 08:49:47'),
(61, 'App\\Models\\User', 6, 'API TOKEN', '08b843b0373e38384e93a5cbd75df1efd5bc5e124c479e89d5d0a0896c1625a8', '[\"*\"]', '2023-11-20 08:51:36', NULL, '2023-11-20 08:42:31', '2023-11-20 08:51:36'),
(62, 'App\\Models\\User', 7, 'API TOKEN', '6474eab9ca3ba139c9c9b3b321e9985019e562a79d3e1f5a17d475dcef2729df', '[\"*\"]', '2023-11-20 09:11:31', NULL, '2023-11-20 09:10:14', '2023-11-20 09:11:31'),
(73, 'App\\Models\\User', 16, 'API TOKEN', '847b6b0af6e5f4c67e3b3d21c7013386cbaffee4ec7adcf41b0c4a623ef6e04d', '[\"*\"]', NULL, NULL, '2023-11-21 12:31:00', '2023-11-21 12:31:00'),
(74, 'App\\Models\\User', 17, 'API TOKEN', '086e2e4376878c1b91f32f651d3f622528d520a127bd6bb7121290f0d4ec7340', '[\"*\"]', NULL, NULL, '2023-11-21 12:34:14', '2023-11-21 12:34:14'),
(75, 'App\\Models\\User', 18, 'API TOKEN', '9e4fb48e70843780e0de0a356eda171536e1d772d2e8a103b4c43cca7adb9275', '[\"*\"]', NULL, NULL, '2023-11-21 12:51:50', '2023-11-21 12:51:50'),
(76, 'App\\Models\\User', 19, 'API TOKEN', '4b3197dabfdafe8b3bca03017f7d6c91f24feb439c825f926dd32af231a0e42e', '[\"*\"]', NULL, NULL, '2023-11-21 13:00:39', '2023-11-21 13:00:39'),
(77, 'App\\Models\\User', 20, 'API TOKEN', '9a1bf722cbba8cb48fe675237fdf79dcc6712f7fe89b8a22cd5fb0f757c8bf58', '[\"*\"]', NULL, NULL, '2023-11-21 13:14:33', '2023-11-21 13:14:33'),
(78, 'App\\Models\\User', 21, 'API TOKEN', '207bac10103665a8d9f71dbc9e43dae22475560478e99f59a898151036f951ab', '[\"*\"]', NULL, NULL, '2023-11-21 13:18:16', '2023-11-21 13:18:16'),
(79, 'App\\Models\\User', 22, 'API TOKEN', 'a433178c7954a2f11fd7c6da4565f2885bfafe45cb507129eb2defb743b70d60', '[\"*\"]', NULL, NULL, '2023-11-21 13:43:47', '2023-11-21 13:43:47'),
(80, 'App\\Models\\User', 23, 'API TOKEN', 'ba9e4c10b5a7e98cfd677257fbed0740ab14e251a3970dd0926a20965da932b0', '[\"*\"]', NULL, NULL, '2023-11-21 13:45:21', '2023-11-21 13:45:21'),
(81, 'App\\Models\\User', 24, 'API TOKEN', 'f4650d39953eaaea5dd6b422cd1bde02714c713140606dd0ef521647083edd1f', '[\"*\"]', NULL, NULL, '2023-11-21 13:48:40', '2023-11-21 13:48:40'),
(88, 'App\\Models\\User', 25, 'API TOKEN', '790550b6e72adfcc9de3e3aa1a7f0ff8f7ab1c02d07af2d6fefd70ac48704f54', '[\"*\"]', NULL, NULL, '2023-11-23 06:18:41', '2023-11-23 06:18:41'),
(89, 'App\\Models\\User', 26, 'API TOKEN', '306411dbcb53718b5e26f67da5de9fe582a3392da8b80e93cf8b7e358f174d91', '[\"*\"]', NULL, NULL, '2023-11-23 06:46:58', '2023-11-23 06:46:58'),
(90, 'App\\Models\\User', 27, 'API TOKEN', 'd84572439ccda4fe4a935ab7105579332c2bfa50ec32a9e8168d52003306d52a', '[\"*\"]', NULL, NULL, '2023-11-23 06:56:04', '2023-11-23 06:56:04'),
(91, 'App\\Models\\User', 28, 'API TOKEN', 'e918dc9551754b975944faafe68cd67276ec5040b6b35fa20ce19a5b8e997d63', '[\"*\"]', NULL, NULL, '2023-11-23 06:57:45', '2023-11-23 06:57:45'),
(92, 'App\\Models\\User', 28, 'API TOKEN', '370350e123739c8ed510643f2c67761e2112b7106f36fe929da7a5b344e08b2d', '[\"*\"]', NULL, NULL, '2023-11-23 07:00:53', '2023-11-23 07:00:53'),
(93, 'App\\Models\\User', 29, 'API TOKEN', '6893fffcbd20e614d0dc01ac475f818292b461b24206591b01d2a433452841b6', '[\"*\"]', NULL, NULL, '2023-11-26 09:53:28', '2023-11-26 09:53:28'),
(94, 'App\\Models\\User', 30, 'API TOKEN', '5331d7aa99a13bdd111577bbfa4da32cb3492ce48d0234fb0ff447032de1a096', '[\"*\"]', NULL, NULL, '2023-11-26 09:55:21', '2023-11-26 09:55:21'),
(105, 'App\\Models\\User', 31, 'API TOKEN', '9d01388830679ce7edaff4dda92e705a540c917c9f2b97923c46f146f629a181', '[\"*\"]', NULL, NULL, '2023-11-28 09:23:02', '2023-11-28 09:23:02'),
(106, 'App\\Models\\User', 31, 'API TOKEN', '7b847596394785f5f3ae2632fbd1214818122f577af67a5cc25d598ea1455297', '[\"*\"]', '2023-11-28 09:56:50', NULL, '2023-11-28 09:23:07', '2023-11-28 09:56:50'),
(107, 'App\\Models\\User', 31, 'API TOKEN', '2bc3f9e3269adf2c67f934b916129a1c24fb4d96cd961fc750e85e6b2aa78e7b', '[\"*\"]', '2023-11-30 11:13:54', NULL, '2023-11-28 09:29:58', '2023-11-30 11:13:54'),
(108, 'App\\Models\\User', 31, 'API TOKEN', '5f9c250b96c539e56401ee3497c40909b6d9dfd5a7335d62fb090ed0b4045485', '[\"*\"]', '2023-11-28 13:06:52', NULL, '2023-11-28 11:10:56', '2023-11-28 13:06:52'),
(127, 'App\\Models\\User', 1, 'API TOKEN', '808ed06c3d27f1f29dae7879db29fc418ba72cfda8f9e06cbd2200e1f1946aa9', '[\"*\"]', '2023-12-02 14:08:14', NULL, '2023-12-02 11:47:02', '2023-12-02 14:08:14'),
(128, 'App\\Models\\User', 1, 'API TOKEN', '6f20824e44d84f56bf8f52cb79159cd15d6c589cdbb6b725702b371fb0288e03', '[\"*\"]', '2023-12-02 14:01:44', NULL, '2023-12-02 13:44:36', '2023-12-02 14:01:44');

-- --------------------------------------------------------

--
-- Table structure for table `post_images`
--

CREATE TABLE `post_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED DEFAULT NULL,
  `creator_id` bigint(20) UNSIGNED DEFAULT NULL,
  `image` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_images`
--

INSERT INTO `post_images` (`id`, `post_id`, `creator_id`, `image`, `created_at`, `updated_at`) VALUES
(5, 7, 1, 'http://192.168.1.15:8000/storage/uploadImages/1699105915_1695127412.png', '2023-11-07 11:15:30', '2023-11-07 11:15:30'),
(6, 8, 1, 'http://192.168.1.15:8000/storage/uploadImages/1699105915_1695127412.png', '2023-11-07 11:17:09', '2023-11-07 11:17:09'),
(8, 9, 1, 'http://192.168.1.15:8000/storage/uploadImages/1699443663_1695127412.png', '2023-11-08 09:41:09', '2023-11-08 09:41:09'),
(13, 12, 1, 'http://192.168.1.15:8000/storage/uploadImages/1699970910_5.jpg', '2023-11-14 12:08:38', '2023-11-14 12:08:38'),
(14, 12, 1, 'http://192.168.1.15:8000/storage/uploadImages/1699970910_11.jpg', '2023-11-14 12:08:38', '2023-11-14 12:08:38'),
(15, 13, 1, 'http://192.168.1.15:8000/storage/uploadImages/1699970910_5.jpg', '2023-11-14 12:08:38', '2023-11-14 12:08:38'),
(16, 13, 1, 'http://192.168.1.15:8000/storage/uploadImages/1699970910_11.jpg', '2023-11-14 12:08:38', '2023-11-14 12:08:38'),
(23, 36, 1, 'http://192.168.1.15:8000/storage/uploadImages/1701357529_ui.png', '2023-11-30 13:18:52', '2023-11-30 13:18:52'),
(24, 38, 1, 'http://192.168.1.15:8000/storage/uploadImages/1701360926_ui.png', '2023-11-30 14:15:29', '2023-11-30 14:15:29');

-- --------------------------------------------------------

--
-- Table structure for table `post_videos`
--

CREATE TABLE `post_videos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `video` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_videos`
--

INSERT INTO `post_videos` (`id`, `post_id`, `creator_id`, `video`, `created_at`, `updated_at`) VALUES
(1, 37, 1, 'http://192.168.1.15:8000/storage/uploadVideos/sec.mp4', '2023-11-30 13:26:14', '2023-11-30 13:26:14');

-- --------------------------------------------------------

--
-- Table structure for table `publish_posts`
--

CREATE TABLE `publish_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `creator_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_type` varchar(255) NOT NULL,
  `account_id` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `thumbnail` text DEFAULT NULL,
  `link` text DEFAULT NULL,
  `post_title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `youtube_privacy` varchar(255) DEFAULT NULL,
  `youtube_tags` text DEFAULT NULL,
  `youtube_category` bigint(20) UNSIGNED DEFAULT NULL,
  `scheduledTime` varchar(255) NOT NULL,
  `tokenApp` varchar(1000) NOT NULL,
  `token_secret` varchar(1000) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `publish_posts`
--

INSERT INTO `publish_posts` (`id`, `creator_id`, `account_type`, `account_id`, `account_name`, `status`, `thumbnail`, `link`, `post_title`, `content`, `youtube_privacy`, `youtube_tags`, `youtube_category`, `scheduledTime`, `tokenApp`, `token_secret`, `created_at`, `updated_at`) VALUES
(1, 2, 'twitter', '1708817086653386752', 'evolve inc', 'published', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-11-09 10:55:57', '1708817086653386752-rrWnFqX4ALKzZMXWgYdhaatcj7Il2f', 'FFgoPOWMuGuI1Zfx5pZBphOEWBPSpyYtS7G4jPPs1K5X0', '2023-11-07 07:17:56', '2023-11-09 06:55:57'),
(7, 1, 'twitter', '1708817086653386752', 'evolve inc', 'published', NULL, NULL, NULL, 'lklkm', NULL, NULL, NULL, '2023-11-07 15:15:00', '1708817086653386752-rrWnFqX4ALKzZMXWgYdhaatcj7Il2f', 'FFgoPOWMuGuI1Zfx5pZBphOEWBPSpyYtS7G4jPPs1K5X0', '2023-11-07 11:15:30', '2023-11-07 11:15:30'),
(8, 2, 'twitter', '1708817086653386752', 'evolve inc', 'published', NULL, NULL, NULL, 'test', NULL, NULL, NULL, '2023-11-08 13:09:28', '1708817086653386752-rrWnFqX4ALKzZMXWgYdhaatcj7Il2f', 'FFgoPOWMuGuI1Zfx5pZBphOEWBPSpyYtS7G4jPPs1K5X0', '2023-11-07 11:17:09', '2023-11-07 11:17:09'),
(9, 2, 'twitter', '1708817086653386752', 'evolve inc', 'published', NULL, NULL, NULL, '', NULL, NULL, NULL, '2023-11-08 17:12:49', '1708817086653386752-rrWnFqX4ALKzZMXWgYdhaatcj7Il2f', 'FFgoPOWMuGuI1Zfx5pZBphOEWBPSpyYtS7G4jPPs1K5X0', '2023-11-08 09:41:09', '2023-11-08 09:41:09'),
(12, 1, 'twitter', '1708817086653386752', 'evolve inc', 'published', NULL, NULL, NULL, 'text', NULL, NULL, NULL, '2023-11-14 16:08:00', '1708817086653386752-rrWnFqX4ALKzZMXWgYdhaatcj7Il2f', 'FFgoPOWMuGuI1Zfx5pZBphOEWBPSpyYtS7G4jPPs1K5X0', '2023-11-14 12:08:38', '2023-11-14 12:08:38'),
(13, 1, 'twitter', '1719665953183506432', 'evolve inc', 'published', NULL, NULL, NULL, 'text', NULL, NULL, NULL, '2023-11-14 16:08:00', '1719665953183506432-RlFCBFhbNfwBHbR5l30ZzyMyQXhjgk', 'UARlQWfxAUdvi9kRQBNF3w7SgSUSAxqMhXRyj2b3YWalL', '2023-11-14 12:08:38', '2023-11-14 12:08:38'),
(20, 1, 'twitter', '1708817086653386752', 'evolve inc', 'published', NULL, NULL, NULL, 'k', NULL, NULL, NULL, '2023-11-30 13:51:00', '1708817086653386752-rrWnFqX4ALKzZMXWgYdhaatcj7Il2f', 'FFgoPOWMuGuI1Zfx5pZBphOEWBPSpyYtS7G4jPPs1K5X0', '2023-11-07 07:17:56', '2023-11-23 09:51:25'),
(27, 1, 'youtube', '1708817086653386752', 'evolve inc', 'published', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-11-09 10:55:57', '1708817086653386752-rrWnFqX4ALKzZMXWgYdhaatcj7Il2f', 'FFgoPOWMuGuI1Zfx5pZBphOEWBPSpyYtS7G4jPPs1K5X0', '2023-11-07 07:17:56', '2023-11-09 06:55:57'),
(34, 1, 'twitter', '1708817086653386752', 'evolve inc', 'published', NULL, NULL, NULL, 'cgvfgfgfgfgfg', NULL, NULL, NULL, '2023-11-24 15:50', '1708817086653386752-rrWnFqX4ALKzZMXWgYdhaatcj7Il2f', 'FFgoPOWMuGuI1Zfx5pZBphOEWBPSpyYtS7G4jPPs1K5X0', '2023-11-27 11:51:05', '2023-11-27 13:55:25'),
(36, 1, 'twitter', '1719665953183506432', 'evolve inc', 'published', NULL, NULL, NULL, '', NULL, NULL, NULL, '2023-11-30 17:18:49', '1719665953183506432-RlFCBFhbNfwBHbR5l30ZzyMyQXhjgk', 'UARlQWfxAUdvi9kRQBNF3w7SgSUSAxqMhXRyj2b3YWalL', '2023-11-30 13:18:52', '2023-11-30 13:18:52'),
(37, 1, 'youtube', 'UCH3ydgnuMGdAer1YlZ4HCJQ', 'evolve inc', 'published', NULL, NULL, 'video1', '', 'public', NULL, 1, '2023-11-30 17:26:12', 'ya29.a0AfB_byB8ITNsbG3qzvOLVYt_ehB_tSgq2F8b0zoltz6_53V8MafMWaZHWegiC5j6AHF-Yv-fB_u84pof3ANEo15mx43TVnJd4eMV7P9oi0z8wVkiugA2H_77dZFP0aCkz5vHt3r-ffgNJ5ebzCWS-1kZbAdUGsBAtNEOaCgYKAdgSARESFQHGX2MiR3vC9IaDAzznkkk3Idb1lQ0171', '1\\/\\/06bj1xwtcz7fwCgYIARAAGAYSNwF-L9IryCu9vP2V5blvvBI3Xe4K10tAhCB7mbDVD65QoeAxEAhzarVw7R4kgJ8lmJuOP28xkzk', '2023-11-30 13:26:14', '2023-11-30 13:26:14'),
(38, 1, 'twitter', '1708817086653386752', 'evolve inc', 'published', NULL, NULL, NULL, 'ggg', NULL, NULL, NULL, '2023-11-30 18:15:26', '1708817086653386752-rrWnFqX4ALKzZMXWgYdhaatcj7Il2f', 'FFgoPOWMuGuI1Zfx5pZBphOEWBPSpyYtS7G4jPPs1K5X0', '2023-11-30 14:15:29', '2023-11-30 14:15:29');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `color`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', 'primary', NULL, '2023-11-16 13:04:56'),
(2, 'user', 'web', 'success', NULL, NULL),
(3, 'manager', 'web', 'danger', '2023-11-12 07:11:33', '2023-11-12 07:11:33'),
(4, 'Admin', 'web', 'dark', '2023-11-12 07:55:33', '2023-11-16 13:04:39');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 1),
(4, 1),
(5, 1),
(5, 2),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(13, 3),
(13, 4),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings_apis`
--

CREATE TABLE `settings_apis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `appType` varchar(255) NOT NULL,
  `appID` varchar(255) NOT NULL,
  `appSecret` varchar(1000) NOT NULL,
  `apiKey` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings_apis`
--

INSERT INTO `settings_apis` (`id`, `creator_id`, `appType`, `appID`, `appSecret`, `apiKey`, `created_at`, `updated_at`) VALUES
(1, 1, 'facebook', '690179252628964', '9ac7abd4768f0bcf9c92779dd406b4d0', NULL, '2023-11-07 07:14:39', '2023-11-27 12:38:54'),
(2, 1, 'twitter', 'fz6f2j9VUq8KFoE6b8mzLXAOg', '0APmDjmsBNAHgS4kyZQ1iZ5byCP8jItcx0WNmWMPnVv8ahPDuK', NULL, '2023-11-07 07:15:05', '2023-11-07 11:08:56'),
(3, 1, 'youtube', '400800346626-3pj9lb5923bmurej4bk6ql2v2rm29kco.apps.googleusercontent.com', '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', 'AIzaSyCZhW13YQV1En4FEtVET312rRwIbAj3Rp4', '2023-11-07 07:15:38', '2023-11-27 13:01:16');

-- --------------------------------------------------------

--
-- Table structure for table `social_posts`
--

CREATE TABLE `social_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `api_account_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` varchar(255) NOT NULL,
  `post_img` text DEFAULT NULL,
  `post_video` text DEFAULT NULL,
  `post_link` text NOT NULL,
  `post_title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `post_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`id`, `email`, `created_at`, `updated_at`) VALUES
(1, 'admin@gmail.com', '2023-11-09 08:56:26', '2023-11-09 08:56:26'),
(2, 'user@gmail.com', '2023-11-09 08:56:41', '2023-11-09 08:56:41'),
(3, 'user2@gmail.com', '2023-11-09 08:56:41', '2023-11-09 08:56:41'),
(8, 'omar@gmail.com', '2023-11-29 06:56:24', '2023-11-29 06:56:24');

-- --------------------------------------------------------

--
-- Table structure for table `subscriber_requests`
--

CREATE TABLE `subscriber_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subscriber_id` bigint(20) UNSIGNED NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `reason` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriber_requests`
--

INSERT INTO `subscriber_requests` (`id`, `subscriber_id`, `service_name`, `reason`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'v', 'This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.', NULL, '2023-11-09 08:56:26', '2023-11-09 08:56:26'),
(2, 2, 'v', NULL, NULL, '2023-11-09 08:57:02', '2023-11-09 08:57:02'),
(3, 1, 'v', NULL, NULL, '2023-11-09 09:04:19', '2023-11-09 09:04:19'),
(4, 1, 'instagram', NULL, NULL, '2023-11-11 07:38:39', '2023-11-11 07:38:39');

-- --------------------------------------------------------

--
-- Table structure for table `time_thinks`
--

CREATE TABLE `time_thinks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `time` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_thinks`
--

INSERT INTO `time_thinks` (`id`, `creator_id`, `time`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2023-11-07 07:17:23', '2023-11-07 07:17:23'),
(2, 2, 2, '2023-11-07 07:17:23', '2023-11-07 07:17:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL DEFAULT 'user',
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_for_messages` varchar(255) NOT NULL DEFAULT 'user',
  `status_for_messages` varchar(255) NOT NULL DEFAULT 'offline',
  `image` text DEFAULT NULL,
  `verification_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `user_type`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_for_messages`, `status_for_messages`, `image`, `verification_token`) VALUES
(1, 'admin', 'admin', 'admin@gmail.com', NULL, '$2y$10$TKQCNYI4Ez2nvn9dr8gWce9wOisr0vV7EvWomqk0QP5Km7Nrkn7rG', 'fj5mV2ouwFATxrjiZytTByUDeJSje0b3OxP2PSd4Jx3jh2IYHJxIMi2ufuy6', '2023-11-07 07:07:42', '2023-12-02 11:51:14', 'admin', 'online', 'http://192.168.1.15:8000/storage/profile_images/1701360539_friend-01.jpg', ''),
(2, 'user', 'user', 'user@gmail.com', NULL, '$2y$10$CbTxOypBImvObySujDqqduNj/TYx05GkL6wYEX/Lw.sUy0H3Cllfy', 'gMTC0XPTElODarnEbs5HcgRuhT8VYZ9n8VXdkblhg9ePooMlRhZSUQ83yVCq', '2023-11-07 07:07:42', '2023-11-13 09:32:00', 'user', 'online', '/storage/profile_images/1699875120_7.png', ''),
(8, 'omar', 'user', 'omar1231234@gmail.com', NULL, '$2y$10$Kd4IXu75/FBpYOAcGtCV5e6k2xJtyhOY//imEEc5wjmNMwzk1Cjxe', NULL, '2023-11-20 09:13:15', '2023-11-20 09:51:41', 'user', 'offline', 'http://192.168.1.15:8000/storage/profile_images/1700481101_1.jpg', ''),
(9, 'omar', 'user', 'omar123@gmail.com', NULL, '$2y$10$fgwMq5ufmZXtSGqDvtVuKOvPBDCbgMOs6.WkHups7wjLz.lqDSS4i', 'MrreWHOfZ5EeRY0oeXFIKdbCLi6XzNxyttMqI5VEk88twUNBaS8fIId8vYAY', '2023-11-21 06:49:04', '2023-11-22 06:36:07', 'user', 'offline', NULL, ''),
(31, 'ali5', 'user', 'ali5@gmail.com', NULL, '$2y$10$boQPqhBOQTZJbQy/dJeOq.DzkAfaI8RNImwEpxnGmk5QncTISt3QK', NULL, '2023-11-28 09:22:59', '2023-11-28 12:20:52', 'user', 'offline', NULL, '548718');

-- --------------------------------------------------------

--
-- Table structure for table `user_has_roles`
--

CREATE TABLE `user_has_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_has_roles`
--

INSERT INTO `user_has_roles` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(2, 1, 1, NULL, NULL),
(7, 2, 2, NULL, NULL),
(14, 8, 2, NULL, NULL),
(15, 9, 2, NULL, NULL),
(25, 1, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `youtube_categories`
--

CREATE TABLE `youtube_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `youtube_categories`
--

INSERT INTO `youtube_categories` (`id`, `category_id`, `category_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Film & Animation', '2023-11-27 08:08:25', '2023-11-27 08:08:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apis`
--
ALTER TABLE `apis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `apis_creator_id_foreign` (`creator_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `instagrams`
--
ALTER TABLE `instagrams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `media_uuid_unique` (`uuid`),
  ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `media_order_column_index` (`order_column`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `news_letters`
--
ALTER TABLE `news_letters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news_letters_creator_id_foreign` (`creator_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `post_images`
--
ALTER TABLE `post_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_images_post_id_foreign` (`post_id`),
  ADD KEY `post_images_creator_id_foreign` (`creator_id`);

--
-- Indexes for table `post_videos`
--
ALTER TABLE `post_videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_videos_post_id_foreign` (`post_id`),
  ADD KEY `post_videos_creator_id_foreign` (`creator_id`);

--
-- Indexes for table `publish_posts`
--
ALTER TABLE `publish_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `publish_posts_creator_id_foreign` (`creator_id`),
  ADD KEY `publish_posts_youtube_category_foreign` (`youtube_category`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `settings_apis`
--
ALTER TABLE `settings_apis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `settings_apis_creator_id_foreign` (`creator_id`);

--
-- Indexes for table `social_posts`
--
ALTER TABLE `social_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `social_posts_api_account_id_foreign` (`api_account_id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriber_requests`
--
ALTER TABLE `subscriber_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriber_requests_subscriber_id_foreign` (`subscriber_id`);

--
-- Indexes for table `time_thinks`
--
ALTER TABLE `time_thinks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `time_thinks_creator_id_foreign` (`creator_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_has_roles`
--
ALTER TABLE `user_has_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_has_roles_user_id_foreign` (`user_id`),
  ADD KEY `user_has_roles_role_id_foreign` (`role_id`);

--
-- Indexes for table `youtube_categories`
--
ALTER TABLE `youtube_categories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apis`
--
ALTER TABLE `apis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instagrams`
--
ALTER TABLE `instagrams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `news_letters`
--
ALTER TABLE `news_letters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `post_images`
--
ALTER TABLE `post_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `post_videos`
--
ALTER TABLE `post_videos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `publish_posts`
--
ALTER TABLE `publish_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings_apis`
--
ALTER TABLE `settings_apis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `social_posts`
--
ALTER TABLE `social_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `subscriber_requests`
--
ALTER TABLE `subscriber_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `time_thinks`
--
ALTER TABLE `time_thinks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `user_has_roles`
--
ALTER TABLE `user_has_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `youtube_categories`
--
ALTER TABLE `youtube_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `apis`
--
ALTER TABLE `apis`
  ADD CONSTRAINT `apis_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news_letters`
--
ALTER TABLE `news_letters`
  ADD CONSTRAINT `news_letters_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_images`
--
ALTER TABLE `post_images`
  ADD CONSTRAINT `post_images_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `publish_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `post_images_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `publish_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_videos`
--
ALTER TABLE `post_videos`
  ADD CONSTRAINT `post_videos_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `publish_posts` (`creator_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `post_videos_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `publish_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `publish_posts`
--
ALTER TABLE `publish_posts`
  ADD CONSTRAINT `publish_posts_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `publish_posts_youtube_category_foreign` FOREIGN KEY (`youtube_category`) REFERENCES `youtube_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `settings_apis`
--
ALTER TABLE `settings_apis`
  ADD CONSTRAINT `settings_apis_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `social_posts`
--
ALTER TABLE `social_posts`
  ADD CONSTRAINT `social_posts_api_account_id_foreign` FOREIGN KEY (`api_account_id`) REFERENCES `apis` (`id`);

--
-- Constraints for table `subscriber_requests`
--
ALTER TABLE `subscriber_requests`
  ADD CONSTRAINT `subscriber_requests_subscriber_id_foreign` FOREIGN KEY (`subscriber_id`) REFERENCES `subscribers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `time_thinks`
--
ALTER TABLE `time_thinks`
  ADD CONSTRAINT `time_thinks_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_has_roles`
--
ALTER TABLE `user_has_roles`
  ADD CONSTRAINT `user_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `user_has_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
