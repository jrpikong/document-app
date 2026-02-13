-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 13, 2026 at 02:06 AM
-- Server version: 11.4.10-MariaDB
-- PHP Version: 8.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tur4n994t0san_doc_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('document-app-cache-902ba3cda1883801594b6e1b452790cc53948fda', 'i:3;', 1770785154),
('document-app-cache-902ba3cda1883801594b6e1b452790cc53948fda:timer', 'i:1770785154;', 1770785154),
('document-app-cache-livewire-rate-limiter:36b52c0f5d5715e53392a43242a6dac327368b4a', 'i:1;', 1770706596),
('document-app-cache-livewire-rate-limiter:36b52c0f5d5715e53392a43242a6dac327368b4a:timer', 'i:1770706596;', 1770706596),
('document-app-cache-livewire-rate-limiter:4b2546cf4f575745f2d59ab8025ceda49d78167e', 'i:1;', 1770784103),
('document-app-cache-livewire-rate-limiter:4b2546cf4f575745f2d59ab8025ceda49d78167e:timer', 'i:1770784103;', 1770784103),
('document-app-cache-livewire-rate-limiter:7bab03fd7377d616a438856b8e9680155ed1e345', 'i:1;', 1770948039),
('document-app-cache-livewire-rate-limiter:7bab03fd7377d616a438856b8e9680155ed1e345:timer', 'i:1770948039;', 1770948039),
('document-app-cache-livewire-rate-limiter:c2f049dab1a13f2cbd555300f311bed1de2cacb1', 'i:1;', 1770877893),
('document-app-cache-livewire-rate-limiter:c2f049dab1a13f2cbd555300f311bed1de2cacb1:timer', 'i:1770877893;', 1770877893),
('document-app-cache-livewire-rate-limiter:d2e2c9deeb919868618190432c8f17f0cbd20157', 'i:1;', 1770716278),
('document-app-cache-livewire-rate-limiter:d2e2c9deeb919868618190432c8f17f0cbd20157:timer', 'i:1770716278;', 1770716278),
('document-app-cache-livewire-rate-limiter:eb9a80a8dbc5dc980d756c1e5862fbbeb86e0b31', 'i:1;', 1770878017),
('document-app-cache-livewire-rate-limiter:eb9a80a8dbc5dc980d756c1e5862fbbeb86e0b31:timer', 'i:1770878017;', 1770878017),
('document-app-cache-livewire-rate-limiter:ed2778c755bf78b9937fa55e66f3226e78977eef', 'i:1;', 1770782023),
('document-app-cache-livewire-rate-limiter:ed2778c755bf78b9937fa55e66f3226e78977eef:timer', 'i:1770782023;', 1770782023),
('document-app-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:51:{i:0;a:4:{s:1:\"a\";s:1:\"1\";s:1:\"b\";s:16:\"ViewAny:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:3;i:2;i:4;i:3;i:5;i:4;i:6;i:5;i:7;}}i:1;a:4:{s:1:\"a\";s:1:\"2\";s:1:\"b\";s:13:\"View:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:3;i:2;i:4;i:3;i:5;i:4;i:6;i:5;i:7;}}i:2;a:4:{s:1:\"a\";s:1:\"3\";s:1:\"b\";s:15:\"Create:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:6;}}i:3;a:4:{s:1:\"a\";s:1:\"4\";s:1:\"b\";s:15:\"Update:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:6;}}i:4;a:4:{s:1:\"a\";s:1:\"5\";s:1:\"b\";s:15:\"Delete:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";s:1:\"6\";s:1:\"b\";s:16:\"Restore:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";s:1:\"7\";s:1:\"b\";s:20:\"ForceDelete:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";s:1:\"8\";s:1:\"b\";s:15:\"Reject:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";s:1:\"9\";s:1:\"b\";s:19:\"RestoreAny:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";s:2:\"10\";s:1:\"b\";s:16:\"Approve:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";s:2:\"11\";s:1:\"b\";s:16:\"Reorder:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";s:2:\"12\";s:1:\"b\";s:17:\"Download:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";s:2:\"13\";s:1:\"b\";s:16:\"ViewAny:Document\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:6;}}i:13;a:4:{s:1:\"a\";s:2:\"14\";s:1:\"b\";s:13:\"View:Document\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:6;}}i:14;a:4:{s:1:\"a\";s:2:\"15\";s:1:\"b\";s:15:\"Create:Document\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:6;}}i:15;a:4:{s:1:\"a\";s:2:\"16\";s:1:\"b\";s:15:\"Update:Document\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:6;}}i:16;a:4:{s:1:\"a\";s:2:\"17\";s:1:\"b\";s:15:\"Delete:Document\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:17;a:4:{s:1:\"a\";s:2:\"18\";s:1:\"b\";s:16:\"Restore:Document\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:18;a:4:{s:1:\"a\";s:2:\"19\";s:1:\"b\";s:20:\"ForceDelete:Document\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:19;a:4:{s:1:\"a\";s:2:\"20\";s:1:\"b\";s:15:\"Reject:Document\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:20;a:4:{s:1:\"a\";s:2:\"21\";s:1:\"b\";s:19:\"RestoreAny:Document\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:21;a:4:{s:1:\"a\";s:2:\"22\";s:1:\"b\";s:16:\"Approve:Document\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:22;a:4:{s:1:\"a\";s:2:\"23\";s:1:\"b\";s:16:\"Reorder:Document\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:23;a:4:{s:1:\"a\";s:2:\"24\";s:1:\"b\";s:17:\"Download:Document\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:6;}}i:24;a:4:{s:1:\"a\";s:2:\"25\";s:1:\"b\";s:12:\"ViewAny:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";s:2:\"26\";s:1:\"b\";s:9:\"View:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";s:2:\"27\";s:1:\"b\";s:11:\"Create:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";s:2:\"28\";s:1:\"b\";s:11:\"Update:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";s:2:\"29\";s:1:\"b\";s:11:\"Delete:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:29;a:4:{s:1:\"a\";s:2:\"30\";s:1:\"b\";s:12:\"Restore:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:30;a:4:{s:1:\"a\";s:2:\"31\";s:1:\"b\";s:16:\"ForceDelete:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:31;a:4:{s:1:\"a\";s:2:\"32\";s:1:\"b\";s:11:\"Reject:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:32;a:4:{s:1:\"a\";s:2:\"33\";s:1:\"b\";s:15:\"RestoreAny:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:33;a:4:{s:1:\"a\";s:2:\"34\";s:1:\"b\";s:12:\"Approve:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:4:{s:1:\"a\";s:2:\"35\";s:1:\"b\";s:12:\"Reorder:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:4:{s:1:\"a\";s:2:\"36\";s:1:\"b\";s:13:\"Download:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:36;a:4:{s:1:\"a\";s:2:\"37\";s:1:\"b\";s:12:\"ViewAny:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:37;a:4:{s:1:\"a\";s:2:\"38\";s:1:\"b\";s:9:\"View:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:38;a:4:{s:1:\"a\";s:2:\"39\";s:1:\"b\";s:11:\"Create:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:39;a:4:{s:1:\"a\";s:2:\"40\";s:1:\"b\";s:11:\"Update:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:40;a:4:{s:1:\"a\";s:2:\"41\";s:1:\"b\";s:11:\"Delete:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:41;a:4:{s:1:\"a\";s:2:\"42\";s:1:\"b\";s:12:\"Restore:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:42;a:4:{s:1:\"a\";s:2:\"43\";s:1:\"b\";s:16:\"ForceDelete:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:43;a:4:{s:1:\"a\";s:2:\"44\";s:1:\"b\";s:11:\"Reject:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:44;a:4:{s:1:\"a\";s:2:\"45\";s:1:\"b\";s:15:\"RestoreAny:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:45;a:4:{s:1:\"a\";s:2:\"46\";s:1:\"b\";s:12:\"Approve:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:46;a:4:{s:1:\"a\";s:2:\"47\";s:1:\"b\";s:12:\"Reorder:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:47;a:4:{s:1:\"a\";s:2:\"48\";s:1:\"b\";s:13:\"Download:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:48;a:4:{s:1:\"a\";s:2:\"49\";s:1:\"b\";s:14:\"View:Dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:7:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;}}i:49;a:4:{s:1:\"a\";s:2:\"50\";s:1:\"b\";s:18:\"View:StatsOverview\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:6;}}i:50;a:4:{s:1:\"a\";s:2:\"51\";s:1:\"b\";s:23:\"View:ApprovalTrendChart\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:6;}}}s:5:\"roles\";a:7:{i:0;a:3:{s:1:\"a\";s:1:\"1\";s:1:\"b\";s:11:\"super_admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";s:1:\"3\";s:1:\"b\";s:22:\"approver_all_documents\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";s:1:\"4\";s:1:\"b\";s:18:\"approver_lab_lspro\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";s:1:\"5\";s:1:\"b\";s:18:\"uploader_lab_lspro\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";s:1:\"6\";s:1:\"b\";s:12:\"uploader_lab\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";s:1:\"7\";s:1:\"b\";s:10:\"viewer_lab\";s:1:\"c\";s:3:\"web\";}i:6;a:3:{s:1:\"a\";s:1:\"2\";s:1:\"b\";s:10:\"panel_user\";s:1:\"c\";s:3:\"web\";}}}', 1770964117);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(2, 'HRD', '2026-02-11 04:30:55', '2026-02-11 04:30:55');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `status` enum('draft','submitted','approved','rejected') NOT NULL DEFAULT 'draft',
  `uploaded_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approval_note` text DEFAULT NULL,
  `sla_hours` int(11) NOT NULL DEFAULT 24,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `title`, `category_id`, `file_path`, `original_name`, `status`, `uploaded_by`, `approved_by`, `approved_at`, `approval_note`, `sla_hours`, `created_at`, `updated_at`) VALUES
(1, 'FR.PR.16-01 EVALUASI KOMPETENSI PERSONIL', 2, 'documents/FR.PR.16-01 EVALUASI KOMPETENSI PERSONIL - Riska Tamala.pdf', 'FR.PR.16-01 EVALUASI KOMPETENSI PERSONIL - Riska Tamala.pdf', 'draft', 7, NULL, NULL, NULL, 24, '2026-02-11 04:45:50', '2026-02-11 04:45:50');

-- --------------------------------------------------------

--
-- Table structure for table `document_histories`
--

CREATE TABLE `document_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `document_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `from_status` varchar(255) DEFAULT NULL,
  `to_status` varchar(255) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_09_154439_create_categories_table', 1),
(5, '2026_02_09_154509_create_documents_table', 1),
(6, '2026_02_09_154725_create_document_histories_table', 1),
(7, '2026_02_10_003227_create_permission_tables', 1);

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

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(3, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 4),
(4, 'App\\Models\\User', 5),
(5, 'App\\Models\\User', 6),
(5, 'App\\Models\\User', 7),
(6, 'App\\Models\\User', 8),
(6, 'App\\Models\\User', 9),
(7, 'App\\Models\\User', 10),
(7, 'App\\Models\\User', 11),
(1, 'App\\Models\\User', 12);

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
(1, 'ViewAny:Category', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(2, 'View:Category', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(3, 'Create:Category', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(4, 'Update:Category', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(5, 'Delete:Category', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(6, 'Restore:Category', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(7, 'ForceDelete:Category', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(8, 'Reject:Category', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(9, 'RestoreAny:Category', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(10, 'Approve:Category', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(11, 'Reorder:Category', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(12, 'Download:Category', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(13, 'ViewAny:Document', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(14, 'View:Document', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(15, 'Create:Document', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(16, 'Update:Document', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(17, 'Delete:Document', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(18, 'Restore:Document', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(19, 'ForceDelete:Document', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(20, 'Reject:Document', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(21, 'RestoreAny:Document', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(22, 'Approve:Document', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(23, 'Reorder:Document', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(24, 'Download:Document', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(25, 'ViewAny:Role', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(26, 'View:Role', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(27, 'Create:Role', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(28, 'Update:Role', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(29, 'Delete:Role', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(30, 'Restore:Role', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(31, 'ForceDelete:Role', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(32, 'Reject:Role', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(33, 'RestoreAny:Role', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(34, 'Approve:Role', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(35, 'Reorder:Role', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(36, 'Download:Role', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(37, 'ViewAny:User', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(38, 'View:User', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(39, 'Create:User', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(40, 'Update:User', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(41, 'Delete:User', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(42, 'Restore:User', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(43, 'ForceDelete:User', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(44, 'Reject:User', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(45, 'RestoreAny:User', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(46, 'Approve:User', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(47, 'Reorder:User', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(48, 'Download:User', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(49, 'View:Dashboard', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(50, 'View:StatsOverview', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(51, 'View:ApprovalTrendChart', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2026-02-10 06:20:33', '2026-02-10 06:20:33'),
(2, 'panel_user', 'web', '2026-02-10 06:22:16', '2026-02-10 06:22:16'),
(3, 'approver_all_documents', 'web', '2026-02-10 06:22:16', '2026-02-10 06:22:16'),
(4, 'approver_lab_lspro', 'web', '2026-02-10 06:22:16', '2026-02-10 06:22:16'),
(5, 'uploader_lab_lspro', 'web', '2026-02-10 06:22:16', '2026-02-10 06:22:16'),
(6, 'uploader_lab', 'web', '2026-02-10 06:22:16', '2026-02-10 06:22:16'),
(7, 'viewer_lab', 'web', '2026-02-10 06:22:16', '2026-02-10 06:22:16');

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
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(49, 2),
(1, 3),
(2, 3),
(49, 3),
(1, 4),
(2, 4),
(49, 4),
(1, 5),
(2, 5),
(3, 5),
(4, 5),
(13, 5),
(14, 5),
(15, 5),
(16, 5),
(17, 5),
(18, 5),
(19, 5),
(20, 5),
(21, 5),
(22, 5),
(23, 5),
(24, 5),
(49, 5),
(1, 6),
(2, 6),
(3, 6),
(4, 6),
(13, 6),
(14, 6),
(15, 6),
(16, 6),
(24, 6),
(49, 6),
(50, 6),
(51, 6),
(1, 7),
(2, 7),
(49, 7);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('l58HEV1fnbkNXpg4gUdlt6Tz3Txd5CJrETQ0dEbe', 7, '2404:c0:2d10::170e:23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiQkNJQ1d5V3hHclBPdjdSSXN2S0V6V09qcThtWjFXSUtRZVJ4bEp6biI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQ1OiJodHRwczovL2RvYy50dXJhbmdnYXRvc2FuLmNvbS9hZG1pbi9kb2N1bWVudHMiO3M6NToicm91dGUiO3M6NDA6ImZpbGFtZW50LmFkbWluLnJlc291cmNlcy5kb2N1bWVudHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo3O3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiI4MWUwNjU0OGVjOTcwZmI3ODRiZjEzMTQ3ZGZiMmM5ZmE3MzljYzYxMmE4N2IwNDg4NzRjMDAxYjE1NWM3NDQ4IjtzOjY6InRhYmxlcyI7YToyOntzOjQwOiI4ZmIxYjExY2E4ZDg0NDk5NWZkYzAwOTcxNzc1ZDZhZl9jb2x1bW5zIjthOjU6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo1OiJ0aXRsZSI7czo1OiJsYWJlbCI7czo1OiJUaXRsZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTM6ImNhdGVnb3J5Lm5hbWUiO3M6NToibGFiZWwiO3M6ODoiQ2F0ZWdvcnkiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjY6InN0YXR1cyI7czo1OiJsYWJlbCI7czo2OiJTdGF0dXMiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJ1cGxvYWRlci5uYW1lIjtzOjU6ImxhYmVsIjtzOjExOiJVcGxvYWRlZCBCeSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IkNyZWF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9fXM6NDA6ImM4ZDgxMDhmYmNiZjNkODk5ZjdhZjNhMDI1MjhhMGFhX2NvbHVtbnMiO2E6NTp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjk6InVzZXIubmFtZSI7czo1OiJsYWJlbCI7czo5OiJBY3Rpb24gQnkiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjExOiJmcm9tX3N0YXR1cyI7czo1OiJsYWJlbCI7czo0OiJGcm9tIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJ0b19zdGF0dXMiO3M6NToibGFiZWwiO3M6MjoiVG8iO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6Im5vdGUiO3M6NToibGFiZWwiO3M6NDoiTm90ZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6NDoiVGltZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO319fX0=', 1770948329),
('yEnWxN9HHgCBcYyb1HBeQPfevtKT1juHt0ZHGxgr', NULL, '2001:448a:2040:7a15:1d3e:dad6:c81d:e00f', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY1drd3ZlMHFOUnNrY29NRk9kTUpzSDNnME5hTGl1eVlXcnBHSmpWcCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vZG9jLnR1cmFuZ2dhdG9zYW4uY29tL2FkbWluL2xvZ2luIjtzOjU6InJvdXRlIjtzOjI1OiJmaWxhbWVudC5hZG1pbi5hdXRoLmxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1770948038);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Iwan Prijo Santoso', 'iwan.prijo@turanggatosan.com', '2026-02-10 06:22:16', '$2y$12$aEjuSVyZIe2IPbrWRQ.hWe86TBBaToep31IYM7vZe7AgdAqXtX.nm', NULL, '2026-02-10 06:22:16', '2026-02-10 06:22:16'),
(2, 'Syaefu Widodo', 'syaefu.widodo@turanggatosan.com', '2026-02-10 06:22:16', '$2y$12$pKkrdLByo1QjhuTKRirvmuF0hbZemKY/Y99SzrNOHUgbsZLwF8Ya.', NULL, '2026-02-10 06:22:16', '2026-02-10 06:22:16'),
(3, 'Murni Ati Putri', 'murni@turanggatosan.com', '2026-02-10 06:22:16', '$2y$12$u9fnqGlvuKLR7f1QUKFnge6piSVO6UEQE6Ny8EXR5A.6R/NoV76ly', NULL, '2026-02-10 06:22:16', '2026-02-10 06:22:16'),
(4, 'Heru Prasetyo', 'heru@turanggatosan.com', '2026-02-10 06:22:16', '$2y$12$lWTF6hH8syrNLlsjsXC8tOViNNl9PYH483kk4Jx7zrY7pg7QvMDR.', NULL, '2026-02-10 06:22:16', '2026-02-10 06:22:16'),
(5, 'Zahratul Syifa Aisya', 'syifa.aisya@turanggatosan.com', '2026-02-10 06:22:17', '$2y$12$zrRtBJjC9.fcY6D/UH7yoebLrPa4Gka.nyXS/LaiZgT73hwDYTYUW', NULL, '2026-02-10 06:22:17', '2026-02-10 06:22:17'),
(6, 'Widiantari Nofriandani', 'widiantari@turanggatosan.com', '2026-02-10 06:22:17', '$2y$12$pMSJThzQlTxV6J2kltWBC.MG36w255kZu9yq2ImjK2FORjXQEmjDS', NULL, '2026-02-10 06:22:17', '2026-02-10 06:22:17'),
(7, 'Riska Tamala', 'riska.tamala@turanggatosan.com', '2026-02-10 06:22:17', '$2y$12$gH72u.8OH9pCGEkZdus/puSoL5JOIYJO7DXS8mEvkAh1z.0FOYy.S', 'PqE67d65SZMEZj8hCFQ29RuxSRLUKcDFthSCXqWqqZvqr1apkdHJ4rdPSFA5', '2026-02-10 06:22:17', '2026-02-10 06:22:17'),
(8, 'Adi Sanrah', 'adi.sanrah@turanggatosan.com', '2026-02-10 06:22:17', '$2y$12$T53imrY7cqRZ/NKfRFzrJ.HINBJXA/zUaRNjN4s7B./xi0rjCDjSu', NULL, '2026-02-10 06:22:17', '2026-02-10 06:22:17'),
(9, 'M. Anwar Hattabi', 'anwar.hattabi@turanggatosan.com', '2026-02-10 06:22:17', '$2y$12$WYjZ3uR2My.97.M.iW8KnuxaRn6jCdxFYGM9EPkP7wpP6ncCejn1K', NULL, '2026-02-10 06:22:17', '2026-02-10 06:22:17'),
(10, 'M. Solihin', 'm.solihin@turanggatosan.com', '2026-02-10 06:22:18', '$2y$12$mxOUSjcX8mFkrdhbtEaeCOfZr2vPa7o8cuj3cTObvei7x7VTYUp0e', NULL, '2026-02-10 06:22:18', '2026-02-10 06:22:18'),
(11, 'M. Isra', 'm.isra@turanggatosan.com', '2026-02-10 06:22:18', '$2y$12$Ents0JLRADXtjLWetr64YOsW.grctjKWFGDsaYLYWkNSKMo.nkG6C', NULL, '2026-02-10 06:22:18', '2026-02-10 06:22:18'),
(12, 'Super Administrator', 'info@turanggatosan.com', '2026-02-10 06:22:18', '$2y$12$OB.z0VMPSevsfUFp1bypHufZqm1SSS86Xw4gC6nqY/vjd0TRyr.li', 'zDU0siOHCplFx4AwGHrj4pFitPWXKuE6DHi40zA3STcfiY6H8K8lyiUlWqRm', '2026-02-10 06:22:18', '2026-02-10 06:22:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_category_id_foreign` (`category_id`),
  ADD KEY `documents_uploaded_by_foreign` (`uploaded_by`),
  ADD KEY `documents_approved_by_foreign` (`approved_by`),
  ADD KEY `documents_status_category_id_index` (`status`,`category_id`);

--
-- Indexes for table `document_histories`
--
ALTER TABLE `document_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_histories_user_id_foreign` (`user_id`),
  ADD KEY `document_histories_document_id_index` (`document_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
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
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `document_histories`
--
ALTER TABLE `document_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `documents_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `document_histories`
--
ALTER TABLE `document_histories`
  ADD CONSTRAINT `document_histories_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `document_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
