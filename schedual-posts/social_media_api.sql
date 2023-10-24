-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2023 at 09:57 AM
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
  `creator_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_pic` varchar(1000) DEFAULT NULL,
  `social_type` varchar(255) NOT NULL,
  `user_account_id` varchar(255) NOT NULL,
  `token` varchar(1000) NOT NULL,
  `token_secret` varchar(1000) DEFAULT NULL,
  `user_status` varchar(255) DEFAULT NULL,
  `page_name` varchar(255) DEFAULT NULL,
  `update_interval` int(11) NOT NULL DEFAULT 60,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `apis`
--

INSERT INTO `apis` (`id`, `creator_id`, `user_name`, `email`, `user_pic`, `social_type`, `user_account_id`, `token`, `token_secret`, `user_status`, `page_name`, `update_interval`, `created_at`, `updated_at`) VALUES
(2, 1, 'EvolveTeck', 'EvolveTeck', NULL, 'twitter', '1708817086653386752', '1708817086653386752-WRyD6t3aHPtXsqQeyjokEhdjOlnrYq', '8MdlSMKR1hkgAsOKTPhMttQTZKFRoGmTs4GrPYFMEokNY', NULL, NULL, 1000, '2023-10-12 07:47:11', '2023-10-23 05:01:40'),
(3, 1, 'evovle inc', 'evovle inc', 'https://yt3.ggpht.com/ytc/APkrFKZhta5mRe5QbypayrwKrcYOl2L65mX_uncGM8F4yVLU3GonpgFTrQ6j22XgIdLg=s240-c-k-c0xffffffff-no-rj-mo', 'youtube', 'UCDtui0aBpRnx1eNAzq7eUHQ', 'ya29.a0AfB_byB9ZUyzCgAo7b5FlMknnV7SYCrX4pzdzpaoFipC-TEMt6KCD4v-JXsvVoPi_usZwGMVp-agZb8wHLbpiCP8Td-FjN9FBdM-EYMznCVO0FqqpwoxmEwLEttzPlvUHjz-L6KKeMs64dnJuwqwZE6u0apzFxSAyyemaCgYKAW0SARMSFQGOcNnCiGhnaAspFUBNCMqzILsxbQ0171', '1//03OvEbi37WB3TCgYIARAAGAMSNwF-L9IreW7zjQ2dmbRQsoUx9UwIhY3-n4v--YSy4gw3s6KVuQLvDnRoGJNS0heO13RQr5-rSsQ', NULL, NULL, 1000, '2023-10-15 10:16:39', '2023-10-23 05:01:40'),
(4, 1, 'technology', 'technology', 'https://yt3.ggpht.com/ytc/APkrFKZXFiTCCxbhxXfRcKFRhOaYzS0vBwSrqko1PsRT38ibgk9arbawHBA_hpYP5Bx_=s88-c-k-c0x00ffffff-no-rj', 'youtube', 'UCFfozYKZZoCfh_Rs9gMujhQ', 'ya29.a0AfB_byChe5H4itzm6VWMu_iUdBdmZmnMPTRLj1TfA_DToPfRyPWE6KzaDKEP88niqDZfTauH1iEVlchYEdf9drvD49hW3V2xXTyloym72e4HBS6PjtFJ7mTCMGF3zFZpNjl1tNzt_A1JRw_zMjH1NKK3cabPj3Xm-XasaCgYKAa4SARISFQGOcNnCYuYuOvzDGOjcCxr2rdtSpw0171', '1//03jU0iAd0UcmKCgYIARAAGAMSNwF-L9IrtNFMW05K7WAZ9EcbQeWoELQywWqbXsGbiwKjNhcFh1iizgAKcKxzDKdHdJ0PP5gzqow', NULL, NULL, 1000, '2023-10-19 04:52:16', '2023-10-23 05:01:40');

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(9, '2023_08_19_100354_create_social_accounts_table', 1),
(10, '2023_08_20_082346_create_publish__posts_table', 1),
(11, '2023_08_21_101501_create_time_thinks_table', 1),
(12, '2023_09_06_141453_create_settings_apis_table', 1),
(13, '2023_07_08_112058_create_messages_table', 2),
(14, '2023_07_19_081931_update_users', 2),
(15, '2023_10_17_133937_create_post_images_table', 3),
(20, '2023_10_21_124254_create_permission_tables', 4),
(21, '2023_10_21_145259_create_user_has_roles_table', 4),
(22, '2023_10_22_140331_create_youtube_categories_table', 4),
(23, '2023_10_23_113515_alter_instagrams_table', 5);

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
(1, 'create', 'web', '2023-10-23 09:08:39', '2023-10-23 09:08:39'),
(2, 'Edit', 'web', '2023-10-23 09:08:45', '2023-10-23 09:08:45'),
(3, 'delete', 'web', '2023-10-23 09:08:48', '2023-10-23 09:08:48');

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

-- --------------------------------------------------------

--
-- Table structure for table `post_images`
--

CREATE TABLE `post_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` int(11) NOT NULL,
  `image` varchar(1000) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `publish__posts`
--

CREATE TABLE `publish__posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `creator_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `postData` varchar(1000) NOT NULL,
  `pageName` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(1000) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `scheduledTime` varchar(255) NOT NULL,
  `tokenApp` varchar(1000) NOT NULL,
  `token_secret` varchar(1000) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `publish__posts`
--

INSERT INTO `publish__posts` (`id`, `creator_id`, `type`, `postData`, `pageName`, `image`, `link`, `status`, `scheduledTime`, `tokenApp`, `token_secret`, `created_at`, `updated_at`) VALUES
(1, 1, 'twitter', 'test', 'EvolveTeck', NULL, NULL, 'published', '2023-10-17 13:33', '1708817086653386752-WRyD6t3aHPtXsqQeyjokEhdjOlnrYq', '8MdlSMKR1hkgAsOKTPhMttQTZKFRoGmTs4GrPYFMEokNY', NULL, NULL),
(2, 1, 'youtube', 'fhryh', 'technology', 'uploadVideos/vecteezy_square-shape-tech-background-hud-small-squares-shape-loop_13449649_472.mp4', NULL, 'published', '2023-10-22 16:38', 'ya29.a0AfB_byChe5H4itzm6VWMu_iUdBdmZmnMPTRLj1TfA_DToPfRyPWE6KzaDKEP88niqDZfTauH1iEVlchYEdf9drvD49hW3V2xXTyloym72e4HBS6PjtFJ7mTCMGF3zFZpNjl1tNzt_A1JRw_zMjH1NKK3cabPj3Xm-XasaCgYKAa4SARISFQGOcNnCYuYuOvzDGOjcCxr2rdtSpw0171', '1//03jU0iAd0UcmKCgYIARAAGAMSNwF-L9IrtNFMW05K7WAZ9EcbQeWoELQywWqbXsGbiwKjNhcFh1iizgAKcKxzDKdHdJ0PP5gzqow', NULL, NULL),
(3, 1, 'youtube', 'fhryh', 'technology', 'uploadVideos/vecteezy_square-shape-tech-background-hud-small-squares-shape-loop_13449649_472.mp4', NULL, 'published', '2023-10-22 16:38', 'ya29.a0AfB_byChe5H4itzm6VWMu_iUdBdmZmnMPTRLj1TfA_DToPfRyPWE6KzaDKEP88niqDZfTauH1iEVlchYEdf9drvD49hW3V2xXTyloym72e4HBS6PjtFJ7mTCMGF3zFZpNjl1tNzt_A1JRw_zMjH1NKK3cabPj3Xm-XasaCgYKAa4SARISFQGOcNnCYuYuOvzDGOjcCxr2rdtSpw0171', '1//03jU0iAd0UcmKCgYIARAAGAMSNwF-L9IrtNFMW05K7WAZ9EcbQeWoELQywWqbXsGbiwKjNhcFh1iizgAKcKxzDKdHdJ0PP5gzqow', NULL, NULL),
(4, 1, 'youtube', 'video min descreption', 'technology', 'uploadVideos/videoMinute.mp4', NULL, 'published', '2023-10-22 17:02', 'ya29.a0AfB_byChe5H4itzm6VWMu_iUdBdmZmnMPTRLj1TfA_DToPfRyPWE6KzaDKEP88niqDZfTauH1iEVlchYEdf9drvD49hW3V2xXTyloym72e4HBS6PjtFJ7mTCMGF3zFZpNjl1tNzt_A1JRw_zMjH1NKK3cabPj3Xm-XasaCgYKAa4SARISFQGOcNnCYuYuOvzDGOjcCxr2rdtSpw0171', '1//03jU0iAd0UcmKCgYIARAAGAMSNwF-L9IrtNFMW05K7WAZ9EcbQeWoELQywWqbXsGbiwKjNhcFh1iizgAKcKxzDKdHdJ0PP5gzqow', NULL, NULL),
(5, 1, 'youtube', 'video min descreption', 'technology', 'uploadVideos/videoMinute.mp4', NULL, 'published', '2023-10-22 17:02', 'ya29.a0AfB_byChe5H4itzm6VWMu_iUdBdmZmnMPTRLj1TfA_DToPfRyPWE6KzaDKEP88niqDZfTauH1iEVlchYEdf9drvD49hW3V2xXTyloym72e4HBS6PjtFJ7mTCMGF3zFZpNjl1tNzt_A1JRw_zMjH1NKK3cabPj3Xm-XasaCgYKAa4SARISFQGOcNnCYuYuOvzDGOjcCxr2rdtSpw0171', '1//03jU0iAd0UcmKCgYIARAAGAMSNwF-L9IrtNFMW05K7WAZ9EcbQeWoELQywWqbXsGbiwKjNhcFh1iizgAKcKxzDKdHdJ0PP5gzqow', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `role_color` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `role_color`, `created_at`, `updated_at`) VALUES
(1, 'Adminstrator', 'web', 'primary', '2023-10-23 09:18:38', '2023-10-23 09:18:38'),
(2, 'User', 'web', 'warning', '2023-10-23 09:19:00', '2023-10-23 10:52:41');

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
(3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings_apis`
--

CREATE TABLE `settings_apis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `appType` varchar(255) NOT NULL,
  `appID` varchar(255) NOT NULL,
  `appSecret` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings_apis`
--

INSERT INTO `settings_apis` (`id`, `appType`, `appID`, `appSecret`, `created_at`, `updated_at`) VALUES
(1, 'facebook', '690179252628964', '9ac7abd4768f0bcf9c92779dd406b4d0', '2023-10-12 07:42:25', '2023-10-12 07:42:25'),
(2, 'twitter', 'tBoZ80ztGOfOjMOx4VOwmdG2G', 'qQjq9BgXxPLc9TQXAtrnXHuRB2vtgSg9fljFUHq2K6ZrV2v56n', '2023-10-12 07:42:44', '2023-10-12 07:42:44'),
(3, 'youtube', '400800346626-3pj9lb5923bmurej4bk6ql2v2rm29kco.apps.googleusercontent.com', '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', '2023-10-15 04:37:44', '2023-10-15 06:18:32');

-- --------------------------------------------------------

--
-- Table structure for table `social_accounts`
--

CREATE TABLE `social_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `social_type` varchar(255) NOT NULL,
  `token` varchar(1000) NOT NULL,
  `facePage_name` varchar(255) DEFAULT NULL,
  `update_interval` int(11) NOT NULL DEFAULT 60,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `social_posts`
--

CREATE TABLE `social_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `page_id` varchar(255) NOT NULL,
  `page_name` varchar(255) NOT NULL,
  `page_link` varchar(1000) NOT NULL,
  `page_img` varchar(1000) DEFAULT NULL,
  `post_id` varchar(255) NOT NULL,
  `post_img` varchar(1000) DEFAULT NULL,
  `post_link` varchar(1000) NOT NULL,
  `post_caption` varchar(1000) DEFAULT NULL,
  `post_date` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `social_posts`
--

INSERT INTO `social_posts` (`id`, `type`, `page_id`, `page_name`, `page_link`, `page_img`, `post_id`, `post_img`, `post_link`, `post_caption`, `post_date`, `created_at`, `updated_at`) VALUES
(47, 'youtube', 'UCFfozYKZZoCfh_Rs9gMujhQ', 'technology', 'https://www.youtube.com/channel/UCFfozYKZZoCfh_Rs9gMujhQ', 'https://yt3.ggpht.com/ytc/APkrFKZXFiTCCxbhxXfRcKFRhOaYzS0vBwSrqko1PsRT38ibgk9arbawHBA_hpYP5Bx_=s88-c-k-c0x00ffffff-no-rj', 'mUZxvThNxGc', 'https://i.ytimg.com/vi/mUZxvThNxGc/hqdefault.jpg', 'https://www.youtube.com/watch?v=mUZxvThNxGc', 'video for min', '2023-10-22T14:03:31Z', NULL, NULL),
(48, 'youtube', 'UCFfozYKZZoCfh_Rs9gMujhQ', 'technology', 'https://www.youtube.com/channel/UCFfozYKZZoCfh_Rs9gMujhQ', 'https://yt3.ggpht.com/ytc/APkrFKZXFiTCCxbhxXfRcKFRhOaYzS0vBwSrqko1PsRT38ibgk9arbawHBA_hpYP5Bx_=s88-c-k-c0x00ffffff-no-rj', 'QlQPjdf9z-s', 'https://i.ytimg.com/vi/QlQPjdf9z-s/hqdefault.jpg', 'https://www.youtube.com/watch?v=QlQPjdf9z-s', 'video youtube', '2023-10-22T13:38:46Z', NULL, NULL),
(49, 'youtube', 'UCFfozYKZZoCfh_Rs9gMujhQ', 'technology', 'https://www.youtube.com/channel/UCFfozYKZZoCfh_Rs9gMujhQ', 'https://yt3.ggpht.com/ytc/APkrFKZXFiTCCxbhxXfRcKFRhOaYzS0vBwSrqko1PsRT38ibgk9arbawHBA_hpYP5Bx_=s88-c-k-c0x00ffffff-no-rj', '385TQq0zN4Q', 'https://i.ytimg.com/vi/385TQq0zN4Q/hqdefault.jpg', 'https://www.youtube.com/watch?v=385TQq0zN4Q', 'video youtube', '2023-10-22T13:37:19Z', NULL, NULL),
(50, 'youtube', 'UCFfozYKZZoCfh_Rs9gMujhQ', 'technology', 'https://www.youtube.com/channel/UCFfozYKZZoCfh_Rs9gMujhQ', 'https://yt3.ggpht.com/ytc/APkrFKZXFiTCCxbhxXfRcKFRhOaYzS0vBwSrqko1PsRT38ibgk9arbawHBA_hpYP5Bx_=s88-c-k-c0x00ffffff-no-rj', 'LdTagvygxU8', 'https://i.ytimg.com/vi/LdTagvygxU8/hqdefault.jpg', 'https://www.youtube.com/watch?v=LdTagvygxU8', 'video youtube', '2023-10-22T13:35:21Z', NULL, NULL),
(51, 'youtube', 'UCFfozYKZZoCfh_Rs9gMujhQ', 'technology', 'https://www.youtube.com/channel/UCFfozYKZZoCfh_Rs9gMujhQ', 'https://yt3.ggpht.com/ytc/APkrFKZXFiTCCxbhxXfRcKFRhOaYzS0vBwSrqko1PsRT38ibgk9arbawHBA_hpYP5Bx_=s88-c-k-c0x00ffffff-no-rj', 'vXF2i_9sea8', 'https://i.ytimg.com/vi/vXF2i_9sea8/hqdefault.jpg', 'https://www.youtube.com/watch?v=vXF2i_9sea8', 'technology video', '2023-10-19T07:50:01Z', NULL, NULL),
(61, 'youtube', 'UCDtui0aBpRnx1eNAzq7eUHQ', 'evovle inc', 'https://www.youtube.com/channel/UCDtui0aBpRnx1eNAzq7eUHQ', 'https://yt3.ggpht.com/ytc/APkrFKZhta5mRe5QbypayrwKrcYOl2L65mX_uncGM8F4yVLU3GonpgFTrQ6j22XgIdLg=s240-c-k-c0xffffffff-no-rj-mo', 'hSO9P8e1uKA', 'https://i.ytimg.com/vi/hSO9P8e1uKA/hqdefault.jpg', 'https://www.youtube.com/watch?v=hSO9P8e1uKA', 'gh', '2023-10-21T09:11:46Z', NULL, NULL),
(62, 'youtube', 'UCDtui0aBpRnx1eNAzq7eUHQ', 'evovle inc', 'https://www.youtube.com/channel/UCDtui0aBpRnx1eNAzq7eUHQ', 'https://yt3.ggpht.com/ytc/APkrFKZhta5mRe5QbypayrwKrcYOl2L65mX_uncGM8F4yVLU3GonpgFTrQ6j22XgIdLg=s240-c-k-c0xffffffff-no-rj-mo', 'jf5kHCwgaJk', 'https://i.ytimg.com/vi/jf5kHCwgaJk/hqdefault.jpg', 'https://www.youtube.com/watch?v=jf5kHCwgaJk', 'Test title', '2023-10-21T09:02:11Z', NULL, NULL),
(63, 'youtube', 'UCDtui0aBpRnx1eNAzq7eUHQ', 'evovle inc', 'https://www.youtube.com/channel/UCDtui0aBpRnx1eNAzq7eUHQ', 'https://yt3.ggpht.com/ytc/APkrFKZhta5mRe5QbypayrwKrcYOl2L65mX_uncGM8F4yVLU3GonpgFTrQ6j22XgIdLg=s240-c-k-c0xffffffff-no-rj-mo', 'AjtRl4s5CPQ', 'https://i.ytimg.com/vi/AjtRl4s5CPQ/hqdefault.jpg', 'https://www.youtube.com/watch?v=AjtRl4s5CPQ', 'laravel youtube test', '2023-10-15T12:58:42Z', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `time_thinks`
--

CREATE TABLE `time_thinks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `creator_id` int(11) NOT NULL,
  `time` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_thinks`
--

INSERT INTO `time_thinks` (`id`, `creator_id`, `time`, `created_at`, `updated_at`) VALUES
(1, 1, '3', '2023-10-12 07:04:37', '2023-10-12 07:04:37');

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
  `status_for_messages` varchar(255) NOT NULL DEFAULT 'offline'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `user_type`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_for_messages`, `status_for_messages`) VALUES
(1, 'Admin', 'admin', 'admin@gmail.com', NULL, '$2y$10$5qKXtOeHE.Jd9rgSQDt1XOWb9v1009OftzWANnCSEIFQVOSLvDW0S', NULL, '2023-10-12 06:59:55', '2023-10-16 05:29:50', 'admin', 'online'),
(2, 'user', 'user', 'user@gmail.com', NULL, '$2y$10$Nl955YhYFujY9RbuneaUeuCyxFnEJmZYYaIP6Y6cOFkBQkV3MxQPu', NULL, '2023-10-12 09:36:25', '2023-10-16 05:28:59', 'user', 'offline');

-- --------------------------------------------------------

--
-- Table structure for table `user_has_roles`
--

CREATE TABLE `user_has_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_has_roles`
--

INSERT INTO `user_has_roles` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 2, 2, NULL, NULL);

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
-- Indexes for dumped tables
--

--
-- Indexes for table `apis`
--
ALTER TABLE `apis`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `publish__posts`
--
ALTER TABLE `publish__posts`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_accounts`
--
ALTER TABLE `social_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_posts`
--
ALTER TABLE `social_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_thinks`
--
ALTER TABLE `time_thinks`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_images`
--
ALTER TABLE `post_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `publish__posts`
--
ALTER TABLE `publish__posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings_apis`
--
ALTER TABLE `settings_apis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `social_accounts`
--
ALTER TABLE `social_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_posts`
--
ALTER TABLE `social_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `time_thinks`
--
ALTER TABLE `time_thinks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_has_roles`
--
ALTER TABLE `user_has_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `youtube_categories`
--
ALTER TABLE `youtube_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

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
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
