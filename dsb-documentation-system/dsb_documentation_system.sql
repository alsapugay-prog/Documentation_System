-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2026 at 06:07 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dsb_documentation_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_name` varchar(255) NOT NULL,
  `date_received` date NOT NULL,
  `status` enum('Pending','On going','Completed') NOT NULL DEFAULT 'Pending',
  `tracker_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tracker_data`)),
  `requirements_checklist` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '[]' CHECK (json_valid(`requirements_checklist`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `service_id`, `client_name`, `date_received`, `status`, `tracker_data`, `requirements_checklist`, `created_at`, `updated_at`) VALUES
(1, 1, 'John Paul Sapugay', '2026-06-08', 'On going', '{\"agencies\":{\"ROD\":{\"status\":\"waiting\"},\"LRA\":{\"status\":null},\"DAR\":{\"status\":\"submitted\"},\"DENR\":{\"status\":\"waiting\"},\"ASSESSORS\":{\"status\":null},\"TREASURY\":{\"status\":\"waiting\"}},\"docs\":{\"tax_dec\":{\"submitted\":false,\"location\":null},\"birth_cert\":{\"submitted\":true,\"location\":null},\"survey_plan\":{\"submitted\":false,\"location\":null},\"id_copy\":{\"submitted\":false,\"location\":null},\"land_title\":{\"submitted\":false,\"location\":null},\"brgy_clearance\":{\"submitted\":false,\"location\":null},\"spa\":{\"submitted\":false,\"location\":null},\"deed_of_sale\":{\"submitted\":false,\"location\":null},\"property_title\":{\"submitted\":false,\"location\":null}},\"notes\":null}', '[\"Tax Declaration\"]', '2026-06-08 00:48:53', '2026-06-12 03:30:46'),
(2, 2, 'Jofin Solivan', '2026-06-09', 'Pending', NULL, '[]', '2026-06-09 06:35:32', '2026-06-09 06:35:32'),
(3, 3, 'Aldrin Sapugay', '2026-06-09', 'Pending', NULL, '[]', '2026-06-09 06:37:53', '2026-06-09 06:37:53'),
(5, 4, 'Andrei Sapugay', '2026-06-12', 'On going', '{\"agencies\":{\"ROD\":{\"status\":\"processing\"},\"LRA\":{\"status\":null},\"DAR\":{\"status\":null},\"DENR\":{\"status\":null},\"ASSESSORS\":{\"status\":null},\"TREASURY\":{\"status\":null}},\"docs\":{\"tax_dec\":{\"submitted\":true,\"location\":\"DENR\"},\"birth_cert\":{\"submitted\":false,\"location\":null},\"survey_plan\":{\"submitted\":false,\"location\":null},\"id_copy\":{\"submitted\":false,\"location\":null},\"land_title\":{\"submitted\":false,\"location\":null},\"brgy_clearance\":{\"submitted\":false,\"location\":null},\"spa\":{\"submitted\":false,\"location\":null},\"deed_of_sale\":{\"submitted\":false,\"location\":null},\"property_title\":{\"submitted\":false,\"location\":null}},\"notes\":null}', '[\"Tax Declaration\"]', '2026-06-12 03:32:09', '2026-06-12 03:35:07');

-- --------------------------------------------------------

--
-- Table structure for table `client_documents`
--

CREATE TABLE `client_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `stored_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client_documents`
--

INSERT INTO `client_documents` (`id`, `client_id`, `original_name`, `stored_name`, `file_path`, `mime_type`, `file_size`, `created_at`, `updated_at`) VALUES
(1, 1, 'Blackfyre_TD-RSC-revised-chap-1-3.pdf', 'KpzL6Gy5OhLCX0wfTBCdAK2mD9tP7rUti1b8Wc0p.pdf', 'clients/1/documents/KpzL6Gy5OhLCX0wfTBCdAK2mD9tP7rUti1b8Wc0p.pdf', 'application/pdf', 323490, '2026-06-08 00:49:06', '2026-06-08 00:49:06'),
(2, 2, 'download.jpg', '2fLMpk5432E2PWYDiDTdr4ZpsVBxEq6ccwJm2LLg.jpg', 'clients/2/documents/2fLMpk5432E2PWYDiDTdr4ZpsVBxEq6ccwJm2LLg.jpg', 'image/jpeg', 66073, '2026-06-09 06:35:46', '2026-06-09 06:35:46'),
(4, 1, 'Blackfyre_UI.pdf', 'i6M5lb82UH94A8K8YdBbJ8g9ipVVQc8xTDQZpuVf.pdf', 'clients/1/documents/i6M5lb82UH94A8K8YdBbJ8g9ipVVQc8xTDQZpuVf.pdf', 'application/pdf', 388414, '2026-06-12 03:30:20', '2026-06-12 03:30:20'),
(5, 5, 'ph no!.png', 'f4kN2dpl89mW6AbM86cdHF4rL7EMXwFmfApLTaPm.png', 'clients/5/documents/f4kN2dpl89mW6AbM86cdHF4rL7EMXwFmfApLTaPm.png', 'image/png', 108279, '2026-06-12 03:32:28', '2026-06-12 03:32:28'),
(6, 5, 'LOC MALA.png', 'fnpfhl6RaDTEgxbWhFZnkqWzRAQrxETlEKIAILtt.png', 'clients/5/documents/fnpfhl6RaDTEgxbWhFZnkqWzRAQrxETlEKIAILtt.png', 'image/png', 668025, '2026-06-12 03:34:22', '2026-06-12 03:34:22');

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
-- Table structure for table `login_tokens`
--

CREATE TABLE `login_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(6) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_tokens`
--

INSERT INTO `login_tokens` (`id`, `email`, `token`, `expires_at`, `used`, `created_at`, `updated_at`) VALUES
(4, 'aldrinsapugay@gmail.com', '515768', '2026-06-12 06:30:57', 0, '2026-06-12 06:20:57', '2026-06-12 06:20:57');

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
(3, '0001_01_01_000005_create_jobs_table', 1),
(4, '2024_01_01_000000_create_services_table  ', 1),
(5, '2024_01_01_000002_create_clients_table', 1),
(6, '2024_01_01_000003_create_client_documents_table', 1),
(7, '2024_01_01_000004_create_user_profiles_table', 1),
(8, '2026_06_03_095000_create_attachments_table', 1),
(9, '2026_06_06_140430_add_tracker_data_to_clients_table', 1),
(10, '2026_06_08_082913_add_google_id_to_users_table', 2),
(11, '2026_06_09_072554_create_user_assignments_tables', 3),
(12, '2026_06_10_133650_make_service_id_nullable_on_clients_table', 3),
(13, '2026_06_12_131928_crete_login_tokens_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('aldrinsapugay@gmail.com', '$2y$12$kzh5MCueCu346eLhNNt9bOK8DQnTPwEGFZzDY4CjBC8insCrUOiQm', '2026-06-11 06:06:58');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `service_type_id` varchar(255) NOT NULL,
  `primary_contact` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `service_type_id`, `primary_contact`, `created_at`, `updated_at`) VALUES
(1, 'Land & Property Titling', '0001', 'John Paul Sapugay', '2026-06-08 00:48:53', '2026-06-08 00:48:53'),
(2, 'Land Conversion', '0002', 'Jofin Solivan', '2026-06-09 06:35:32', '2026-06-09 06:35:32'),
(3, 'BIR Taxation Services', '0003', 'Aldrin Sapugay', '2026-06-09 06:37:53', '2026-06-09 06:37:53'),
(4, 'Legal & Notarial', '0004', 'Andrei Sapugay', '2026-06-12 03:32:09', '2026-06-12 03:32:09');

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
('vWkZQQXCZGDdxwPPjfDqYHmqJGYKu7P1RykBNbic', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUnV5UTdNNHBZbGRoRndqRE8yak1oVkF2SHVuV2U1MlUxYTlmWVVDQSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9', 1781279308);

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
(1, 'Aldrin Sapugay', 'aldrinsapugay@gmail.com', NULL, '$2y$12$dFJ9nn.K423ALW8faZ2JwOFcmUTeAehaH081ZmvwjK7cAwpQ.IGxe', 'dZrsz39ZzEoolizi3P7MZ3uIORJ61hR2coCMOaZhRTTSGYC8eLq4rxYN0J7F', '2026-06-08 00:48:15', '2026-06-12 03:29:25'),
(2, 'Ayvee', 'ayvee@gmail.com', NULL, '$2y$12$geS6Ulcui/jiriwavFc2C./fS1ZIfLD0zzI41Rx.G6PU88g59/0Pq', NULL, '2026-06-09 06:21:52', '2026-06-09 06:21:52'),
(3, 'nonoy', 'nonoy@gmail.com', NULL, '$2y$12$12J12wU9XUuajkaR4CSrM.P.j115SPagiwqTm7RhqRnZJeKGH4XdG', NULL, '2026-06-09 06:54:21', '2026-06-09 06:54:21'),
(4, 'John Paul Sapugay', 'Jaypee@gmail.com', NULL, '$2y$12$7JTKgwzoDOCdS9Q2r.yNzuMx0DgsJJ.w1H0k4J4pDEOqQuOT2BQ3C', NULL, '2026-06-12 03:36:56', '2026-06-12 03:36:56'),
(5, 'Admin', 'admin@gmail.com', NULL, '$2y$12$bKs7aDsfVe23qp6LXRIm1.22KybmlYiI6W5ui/LxiZlJkaihXL63e', NULL, '2026-06-12 06:59:53', '2026-06-12 06:59:53');

-- --------------------------------------------------------

--
-- Table structure for table `user_documents`
--

CREATE TABLE `user_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `document_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_services`
--

CREATE TABLE `user_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attachments_client_id_foreign` (`client_id`);

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
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clients_service_id_foreign` (`service_id`);

--
-- Indexes for table `client_documents`
--
ALTER TABLE `client_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_documents_client_id_foreign` (`client_id`);

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
-- Indexes for table `login_tokens`
--
ALTER TABLE `login_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_tokens_email_index` (`email`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `user_documents`
--
ALTER TABLE `user_documents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_documents_user_id_document_id_unique` (`user_id`,`document_id`),
  ADD KEY `user_documents_document_id_foreign` (`document_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_profiles_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_services`
--
ALTER TABLE `user_services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_services_user_id_service_id_unique` (`user_id`,`service_id`),
  ADD KEY `user_services_service_id_foreign` (`service_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `client_documents`
--
ALTER TABLE `client_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- AUTO_INCREMENT for table `login_tokens`
--
ALTER TABLE `login_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_documents`
--
ALTER TABLE `user_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_services`
--
ALTER TABLE `user_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attachments`
--
ALTER TABLE `attachments`
  ADD CONSTRAINT `attachments_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `client_documents`
--
ALTER TABLE `client_documents`
  ADD CONSTRAINT `client_documents_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_documents`
--
ALTER TABLE `user_documents`
  ADD CONSTRAINT `user_documents_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `client_documents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_services`
--
ALTER TABLE `user_services`
  ADD CONSTRAINT `user_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_services_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
