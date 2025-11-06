-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 06, 2025 at 10:16 AM
-- Server version: 10.11.14-MariaDB-ubu2204
-- PHP Version: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `requisition`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_heads`
--

CREATE TABLE `account_heads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `head_category` enum('0','1','2','3','4','5') NOT NULL DEFAULT '0',
  `account_head_name` varchar(128) NOT NULL,
  `parent_id` bigint(20) NOT NULL DEFAULT 0,
  `status` smallint(6) NOT NULL DEFAULT 0,
  `created_by` bigint(20) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Foreign Key - id:companies',
  `bank_name` varchar(255) NOT NULL,
  `account_holder_name` varchar(255) NOT NULL,
  `account_no` varchar(255) NOT NULL,
  `account_type` enum('current','savings','fdr','cc') DEFAULT NULL,
  `branch_name` varchar(255) NOT NULL,
  `branch_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cheques`
--

CREATE TABLE `cheques` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bank_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Foreign Key - id : banks',
  `requisition_id` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Foreign Key - id : requisitions',
  `cheque_no` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1 - Active, 2 - Inactive, 3 - Used',
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cheque_files`
--

CREATE TABLE `cheque_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cheque_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Foreign Key : id - cheques',
  `file_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1 - Active, 2 - Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_income_expense`
--

CREATE TABLE `daily_income_expense` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_head_id` int(11) NOT NULL DEFAULT 0,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `remarks` varchar(255) NOT NULL DEFAULT '',
  `created_by` bigint(20) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(128) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
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
(4, '2018_08_08_100000_create_telescope_entries_table', 1),
(5, '2019_08_19_000000_create_failed_jobs_table', 1),
(6, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(7, '2024_07_29_171929_create_sessions_table', 1),
(8, '2024_11_14_161232_create_account_heads_table', 1),
(9, '2024_12_05_124850_create_daily_income_expense_table', 1),
(10, '2024_12_08_131231_create_requisitions_table', 1),
(11, '2024_12_08_175702_create_requisition_approval_table', 1),
(12, '2025_10_21_112235_create_companies_table', 1),
(13, '2025_10_26_144152_add_company_id_to_requisitions_table', 1),
(14, '2025_10_26_150917_add_nullable_to_transaction_mode_and_bank_check_in_requisitions_table', 1),
(15, '2025_10_26_155937_create_requisition_files_table', 1),
(16, '2025_10_27_124800_create_purposes_table', 1),
(17, '2025_10_27_152729_add_purpose_id_to_requisitions_table', 1),
(18, '2025_10_27_192205_add_title_nullable_to_requisitions_table', 1),
(19, '2025_10_29_181344_add_remarks_nullable_to_requisition_approval_table', 1),
(20, '2025_10_29_184120_create_payees_table', 1),
(21, '2025_10_30_120508_add_payee_id_to_requisitions_table', 1),
(22, '2025_10_30_144724_change_requisition_status_in_requisition_approval_table', 1),
(23, '2025_11_02_122955_change_requisition_status_in_requisitions_table', 1),
(24, '2025_11_02_125310_change_requisition_status_in_requisition_approval_table', 1),
(25, '2025_11_02_155758_create_banks_table', 1),
(26, '2025_11_03_130544_create_cheques_table', 1),
(27, '2025_11_04_161453_create_cheque_files_table', 1);

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
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payees`
--

CREATE TABLE `payees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payee_name` varchar(255) NOT NULL,
  `account_holder_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `purposes`
--

CREATE TABLE `purposes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purpose_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requisitions`
--

CREATE TABLE `requisitions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purpose_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payee_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Foreign Key - payee_id:payees',
  `others` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `requested_to` enum('managing_director','ceo','manager','accountant') NOT NULL DEFAULT 'accountant',
  `amount` decimal(11,3) NOT NULL DEFAULT 0.000,
  `transaction_mode` enum('cash','bank','due') DEFAULT NULL,
  `bank_check_info` varchar(255) DEFAULT NULL,
  `status` enum('approved','rejected','pending','returned') NOT NULL DEFAULT 'pending',
  `created_by` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requisition_approval`
--

CREATE TABLE `requisition_approval` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `requisition_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Foreign Key - id: requisitions',
  `status` enum('approved','rejected','pending','returned') DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requisition_files`
--

CREATE TABLE `requisition_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `requisition_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Foreign Key : id - requisitions',
  `file_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('RB4InxGA2XzcbNfCZ3QcA39PPUiG0sjIQoJiPIiH', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:144.0) Gecko/20100101 Firefox/144.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNzc4N1VhNUNpbnRLcGtsUklUVWFmdFRNN1FvYVFzQnFGelc5dWRNMyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7fX0=', 1762423331);

-- --------------------------------------------------------

--
-- Table structure for table `telescope_entries`
--

CREATE TABLE `telescope_entries` (
  `sequence` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `batch_id` char(36) NOT NULL,
  `family_hash` varchar(255) DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT 1,
  `type` varchar(20) NOT NULL,
  `content` longtext NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `telescope_entries_tags`
--

CREATE TABLE `telescope_entries_tags` (
  `entry_uuid` char(36) NOT NULL,
  `tag` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `telescope_monitoring`
--

CREATE TABLE `telescope_monitoring` (
  `tag` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
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
(1, 'Mohammad Amir Hossain', 'ceo@paribahan.com', '2025-11-06 10:14:46', '$2y$12$XAK0L/29vIIrsElEapO5JuIlwFWIDwDeIUK3uK7HpLOGx8uoxEwC.', 'tfzMLPPBnQ', '2025-11-06 10:14:47', '2025-11-06 10:14:47'),
(2, 'Mohammad Monir Hossain', 'monir.ecraft@gmail.com', '2025-11-06 10:14:47', '$2y$12$VydiFricR0JWBnePtZkbKO41xKNneJXul941uca5vR/XRK6HkDB2G', 'MMiwWWuTjc', '2025-11-06 10:14:47', '2025-11-06 10:14:47'),
(3, 'ECL Accounts', 'accounts@electrocraft.org', '2025-11-06 10:14:47', '$2y$12$RIbIrQ0FmCdUJhrL5VZYL.g..3Sf5fCjzGQCynORif60dGdHWDFly', 'B3Ev82Beyx', '2025-11-06 10:14:47', '2025-11-06 10:14:47'),
(4, 'Mohammad Shohidul Islam', 'shohidulislam@electrocraft.org', '2025-11-06 10:14:47', '$2y$12$Lg018MEFjkXEIbAe5OmqDu6h4TxeMiByTne8Dsmwn6hXbE1GxTduy', 'c7mBP4mDER', '2025-11-06 10:14:47', '2025-11-06 10:14:47'),
(5, 'Golam Muktadir', 'cse.limon.33@gmail.com', '2025-11-06 10:14:48', '$2y$12$41e.rz6PSEk3fdfgMlq5v.t4A8QOeRZUVeN9H7P9/4tPpvXEYT8vK', 'm7GVL7WgS0', '2025-11-06 10:14:48', '2025-11-06 10:14:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_heads`
--
ALTER TABLE `account_heads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_heads_account_head_name_unique` (`account_head_name`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bank_account_unique` (`bank_name`,`account_no`),
  ADD KEY `banks_company_id_foreign` (`company_id`);

--
-- Indexes for table `cheques`
--
ALTER TABLE `cheques`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cheques_bank_id_foreign` (`bank_id`);

--
-- Indexes for table `cheque_files`
--
ALTER TABLE `cheque_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cheque_files_cheque_id_foreign` (`cheque_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daily_income_expense`
--
ALTER TABLE `daily_income_expense`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `payees`
--
ALTER TABLE `payees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `purposes`
--
ALTER TABLE `purposes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requisitions`
--
ALTER TABLE `requisitions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requisitions_company_id_foreign` (`company_id`),
  ADD KEY `requisitions_purpose_id_foreign` (`purpose_id`),
  ADD KEY `requisitions_payee_id_foreign` (`payee_id`);

--
-- Indexes for table `requisition_approval`
--
ALTER TABLE `requisition_approval`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requisition_approval_requisition_id_foreign` (`requisition_id`);

--
-- Indexes for table `requisition_files`
--
ALTER TABLE `requisition_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requisition_files_requisition_id_foreign` (`requisition_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `telescope_entries`
--
ALTER TABLE `telescope_entries`
  ADD PRIMARY KEY (`sequence`),
  ADD UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  ADD KEY `telescope_entries_batch_id_index` (`batch_id`),
  ADD KEY `telescope_entries_family_hash_index` (`family_hash`),
  ADD KEY `telescope_entries_created_at_index` (`created_at`),
  ADD KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`);

--
-- Indexes for table `telescope_entries_tags`
--
ALTER TABLE `telescope_entries_tags`
  ADD PRIMARY KEY (`entry_uuid`,`tag`),
  ADD KEY `telescope_entries_tags_tag_index` (`tag`);

--
-- Indexes for table `telescope_monitoring`
--
ALTER TABLE `telescope_monitoring`
  ADD PRIMARY KEY (`tag`);

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
-- AUTO_INCREMENT for table `account_heads`
--
ALTER TABLE `account_heads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cheques`
--
ALTER TABLE `cheques`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cheque_files`
--
ALTER TABLE `cheque_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_income_expense`
--
ALTER TABLE `daily_income_expense`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `payees`
--
ALTER TABLE `payees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purposes`
--
ALTER TABLE `purposes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requisitions`
--
ALTER TABLE `requisitions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requisition_approval`
--
ALTER TABLE `requisition_approval`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requisition_files`
--
ALTER TABLE `requisition_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `telescope_entries`
--
ALTER TABLE `telescope_entries`
  MODIFY `sequence` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `banks`
--
ALTER TABLE `banks`
  ADD CONSTRAINT `banks_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);

--
-- Constraints for table `cheques`
--
ALTER TABLE `cheques`
  ADD CONSTRAINT `cheques_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`);

--
-- Constraints for table `cheque_files`
--
ALTER TABLE `cheque_files`
  ADD CONSTRAINT `cheque_files_cheque_id_foreign` FOREIGN KEY (`cheque_id`) REFERENCES `cheques` (`id`);

--
-- Constraints for table `requisitions`
--
ALTER TABLE `requisitions`
  ADD CONSTRAINT `requisitions_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `requisitions_payee_id_foreign` FOREIGN KEY (`payee_id`) REFERENCES `payees` (`id`),
  ADD CONSTRAINT `requisitions_purpose_id_foreign` FOREIGN KEY (`purpose_id`) REFERENCES `purposes` (`id`);

--
-- Constraints for table `requisition_approval`
--
ALTER TABLE `requisition_approval`
  ADD CONSTRAINT `requisition_approval_requisition_id_foreign` FOREIGN KEY (`requisition_id`) REFERENCES `requisitions` (`id`);

--
-- Constraints for table `requisition_files`
--
ALTER TABLE `requisition_files`
  ADD CONSTRAINT `requisition_files_requisition_id_foreign` FOREIGN KEY (`requisition_id`) REFERENCES `requisitions` (`id`);

--
-- Constraints for table `telescope_entries_tags`
--
ALTER TABLE `telescope_entries_tags`
  ADD CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
