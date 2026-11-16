<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::disableForeignKeyConstraints();
        if (!Schema::hasTable('achievement_revisions')) DB::statement('CREATE TABLE IF NOT EXISTS `achievement_revisions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `achievement_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  PRIMARY KEY (`id`),
  KEY `achievement_revisions_achievement_id_foreign` (`achievement_id`),
  KEY `achievement_revisions_user_id_foreign` (`user_id`),
  CONSTRAINT `achievement_revisions_achievement_id_foreign` FOREIGN KEY (`achievement_id`) REFERENCES `achievements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `achievement_revisions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `twill_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('achievement_slugs')) DB::statement('CREATE TABLE IF NOT EXISTS `achievement_slugs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `achievement_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_achievement_slugs_achievement_id` (`achievement_id`),
  KEY `achievement_slugs_locale_index` (`locale`),
  CONSTRAINT `fk_achievement_slugs_achievement_id` FOREIGN KEY (`achievement_id`) REFERENCES `achievements` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('achievement_translations')) DB::statement('CREATE TABLE IF NOT EXISTS `achievement_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `achievement_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `title` varchar(200) DEFAULT NULL,
  `student_name` varchar(200) DEFAULT NULL,
  `organizer` varchar(200) DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `meta_title` varchar(70) DEFAULT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `achievement_id_locale_unique` (`achievement_id`,`locale`),
  KEY `achievement_translations_locale_index` (`locale`),
  CONSTRAINT `fk_achievement_translations_achievement_id` FOREIGN KEY (`achievement_id`) REFERENCES `achievements` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('achievements')) DB::statement('CREATE TABLE IF NOT EXISTS `achievements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `position` int(10) unsigned DEFAULT NULL,
  `level` varchar(30) DEFAULT NULL,
  `rank` varchar(30) DEFAULT NULL,
  `achievement_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('agenda_revisions')) DB::statement('CREATE TABLE IF NOT EXISTS `agenda_revisions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `agenda_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  PRIMARY KEY (`id`),
  KEY `agenda_revisions_agenda_id_foreign` (`agenda_id`),
  KEY `agenda_revisions_user_id_foreign` (`user_id`),
  CONSTRAINT `agenda_revisions_agenda_id_foreign` FOREIGN KEY (`agenda_id`) REFERENCES `agendas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `agenda_revisions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `twill_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('agenda_slugs')) DB::statement('CREATE TABLE IF NOT EXISTS `agenda_slugs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `agenda_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_agenda_slugs_agenda_id` (`agenda_id`),
  KEY `agenda_slugs_locale_index` (`locale`),
  CONSTRAINT `fk_agenda_slugs_agenda_id` FOREIGN KEY (`agenda_id`) REFERENCES `agendas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('agenda_translations')) DB::statement('CREATE TABLE IF NOT EXISTS `agenda_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `agenda_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `meta_title` varchar(70) DEFAULT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `agenda_id_locale_unique` (`agenda_id`,`locale`),
  KEY `agenda_translations_locale_index` (`locale`),
  CONSTRAINT `fk_agenda_translations_agenda_id` FOREIGN KEY (`agenda_id`) REFERENCES `agendas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('agendas')) DB::statement('CREATE TABLE IF NOT EXISTS `agendas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `position` int(10) unsigned DEFAULT NULL,
  `agenda_date` datetime DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `_lft` int(10) unsigned NOT NULL DEFAULT 0,
  `_rgt` int(10) unsigned NOT NULL DEFAULT 0,
  `parent_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agendas__lft__rgt_parent_id_index` (`_lft`,`_rgt`,`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('brosur_revisions')) DB::statement('CREATE TABLE IF NOT EXISTS `brosur_revisions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `brosur_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  PRIMARY KEY (`id`),
  KEY `brosur_revisions_brosur_id_foreign` (`brosur_id`),
  KEY `brosur_revisions_user_id_foreign` (`user_id`),
  CONSTRAINT `brosur_revisions_brosur_id_foreign` FOREIGN KEY (`brosur_id`) REFERENCES `brosurs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `brosur_revisions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `twill_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('brosur_slugs')) DB::statement('CREATE TABLE IF NOT EXISTS `brosur_slugs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `brosur_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_brosur_slugs_brosur_id` (`brosur_id`),
  KEY `brosur_slugs_locale_index` (`locale`),
  CONSTRAINT `fk_brosur_slugs_brosur_id` FOREIGN KEY (`brosur_id`) REFERENCES `brosurs` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('brosur_translations')) DB::statement('CREATE TABLE IF NOT EXISTS `brosur_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `brosur_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `meta_title` varchar(70) DEFAULT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brosur_id_locale_unique` (`brosur_id`,`locale`),
  KEY `brosur_translations_locale_index` (`locale`),
  CONSTRAINT `fk_brosur_translations_brosur_id` FOREIGN KEY (`brosur_id`) REFERENCES `brosurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('brosurs')) DB::statement('CREATE TABLE IF NOT EXISTS `brosurs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `position` int(10) unsigned DEFAULT NULL,
  `brosur_date` timestamp NULL DEFAULT NULL,
  `_lft` int(10) unsigned NOT NULL DEFAULT 0,
  `_rgt` int(10) unsigned NOT NULL DEFAULT 0,
  `parent_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `brosurs__lft__rgt_parent_id_index` (`_lft`,`_rgt`,`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('checkpoint_scan_results')) DB::statement('CREATE TABLE IF NOT EXISTS `checkpoint_scan_results` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `triggered_by` varchar(255) NOT NULL DEFAULT \'manual\',
  `total_checks` int(11) NOT NULL DEFAULT 0,
  `pass_count` int(11) NOT NULL DEFAULT 0,
  `warn_count` int(11) NOT NULL DEFAULT 0,
  `fail_count` int(11) NOT NULL DEFAULT 0,
  `duration_seconds` decimal(8,2) NOT NULL DEFAULT 0.00,
  `exit_code` int(11) NOT NULL DEFAULT 0,
  `raw_output` longtext DEFAULT NULL,
  `parsed_output` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_output`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `checkpoint_scan_results_user_id_foreign` (`user_id`),
  KEY `checkpoint_scan_results_triggered_by_index` (`triggered_by`),
  KEY `checkpoint_scan_results_created_at_index` (`created_at`),
  CONSTRAINT `checkpoint_scan_results_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `twill_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('extracurricular_revisions')) DB::statement('CREATE TABLE IF NOT EXISTS `extracurricular_revisions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `extracurricular_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  PRIMARY KEY (`id`),
  KEY `extracurricular_revisions_extracurricular_id_foreign` (`extracurricular_id`),
  KEY `extracurricular_revisions_user_id_foreign` (`user_id`),
  CONSTRAINT `extracurricular_revisions_extracurricular_id_foreign` FOREIGN KEY (`extracurricular_id`) REFERENCES `extracurriculars` (`id`) ON DELETE CASCADE,
  CONSTRAINT `extracurricular_revisions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `twill_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('extracurricular_slugs')) DB::statement('CREATE TABLE IF NOT EXISTS `extracurricular_slugs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `extracurricular_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_extracurricular_slugs_extracurricular_id` (`extracurricular_id`),
  KEY `extracurricular_slugs_locale_index` (`locale`),
  CONSTRAINT `fk_extracurricular_slugs_extracurricular_id` FOREIGN KEY (`extracurricular_id`) REFERENCES `extracurriculars` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('extracurricular_translations')) DB::statement('CREATE TABLE IF NOT EXISTS `extracurricular_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `extracurricular_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `meta_title` varchar(70) DEFAULT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `extracurricular_id_locale_unique` (`extracurricular_id`,`locale`),
  KEY `extracurricular_translations_locale_index` (`locale`),
  CONSTRAINT `fk_extracurricular_translations_extracurricular_id` FOREIGN KEY (`extracurricular_id`) REFERENCES `extracurriculars` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('extracurriculars')) DB::statement('CREATE TABLE IF NOT EXISTS `extracurriculars` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `position` int(10) unsigned DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `schedule` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `advisor_name` varchar(255) DEFAULT NULL,
  `advisor_role` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('facilities')) DB::statement('CREATE TABLE IF NOT EXISTS `facilities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `position` int(10) unsigned DEFAULT NULL,
  `icon` text DEFAULT NULL,
  `stat_value` varchar(255) DEFAULT NULL,
  `capacity_value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('facility_revisions')) DB::statement('CREATE TABLE IF NOT EXISTS `facility_revisions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `facility_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  PRIMARY KEY (`id`),
  KEY `facility_revisions_facility_id_foreign` (`facility_id`),
  KEY `facility_revisions_user_id_foreign` (`user_id`),
  CONSTRAINT `facility_revisions_facility_id_foreign` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `facility_revisions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `twill_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('facility_slugs')) DB::statement('CREATE TABLE IF NOT EXISTS `facility_slugs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `facility_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_facility_slugs_facility_id` (`facility_id`),
  KEY `facility_slugs_locale_index` (`locale`),
  CONSTRAINT `fk_facility_slugs_facility_id` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('facility_translations')) DB::statement('CREATE TABLE IF NOT EXISTS `facility_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `facility_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `title` varchar(200) DEFAULT NULL,
  `badge` varchar(50) DEFAULT NULL,
  `stat_label` varchar(100) DEFAULT NULL,
  `capacity_label` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `meta_title` varchar(70) DEFAULT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `facility_id_locale_unique` (`facility_id`,`locale`),
  KEY `facility_translations_locale_index` (`locale`),
  CONSTRAINT `fk_facility_translations_facility_id` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('job_vacancies')) DB::statement('CREATE TABLE IF NOT EXISTS `job_vacancies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `position` int(10) unsigned DEFAULT NULL,
  `company_name` varchar(200) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `job_type` varchar(30) DEFAULT NULL,
  `salary_range` varchar(100) DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `apply_url` varchar(500) DEFAULT NULL,
  `apply_email` varchar(150) DEFAULT NULL,
  `contact_person` varchar(150) DEFAULT NULL,
  `contact_phone` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('job_vacancy_revisions')) DB::statement('CREATE TABLE IF NOT EXISTS `job_vacancy_revisions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `job_vacancy_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  PRIMARY KEY (`id`),
  KEY `job_vacancy_revisions_job_vacancy_id_foreign` (`job_vacancy_id`),
  KEY `job_vacancy_revisions_user_id_foreign` (`user_id`),
  CONSTRAINT `job_vacancy_revisions_job_vacancy_id_foreign` FOREIGN KEY (`job_vacancy_id`) REFERENCES `job_vacancies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `job_vacancy_revisions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `twill_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('job_vacancy_slugs')) DB::statement('CREATE TABLE IF NOT EXISTS `job_vacancy_slugs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `job_vacancy_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_job_vacancy_slugs_job_vacancy_id` (`job_vacancy_id`),
  KEY `job_vacancy_slugs_locale_index` (`locale`),
  CONSTRAINT `fk_job_vacancy_slugs_job_vacancy_id` FOREIGN KEY (`job_vacancy_id`) REFERENCES `job_vacancies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('job_vacancy_translations')) DB::statement('CREATE TABLE IF NOT EXISTS `job_vacancy_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `job_vacancy_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `title` varchar(200) DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `meta_title` varchar(70) DEFAULT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `job_vacancy_id_locale_unique` (`job_vacancy_id`,`locale`),
  KEY `job_vacancy_translations_locale_index` (`locale`),
  CONSTRAINT `fk_job_vacancy_translations_job_vacancy_id` FOREIGN KEY (`job_vacancy_id`) REFERENCES `job_vacancies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('jurusan_revisions')) DB::statement('CREATE TABLE IF NOT EXISTS `jurusan_revisions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `jurusan_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  PRIMARY KEY (`id`),
  KEY `jurusan_revisions_jurusan_id_foreign` (`jurusan_id`),
  KEY `jurusan_revisions_user_id_foreign` (`user_id`),
  CONSTRAINT `jurusan_revisions_jurusan_id_foreign` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jurusan_revisions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `twill_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('jurusan_slugs')) DB::statement('CREATE TABLE IF NOT EXISTS `jurusan_slugs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `jurusan_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_jurusan_slugs_jurusan_id` (`jurusan_id`),
  KEY `jurusan_slugs_locale_index` (`locale`),
  CONSTRAINT `fk_jurusan_slugs_jurusan_id` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('jurusan_translations')) DB::statement('CREATE TABLE IF NOT EXISTS `jurusan_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `jurusan_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `title` varchar(200) DEFAULT NULL,
  `short_name` varchar(20) DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `vision` text DEFAULT NULL,
  `mission` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `meta_title` varchar(70) DEFAULT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jurusan_id_locale_unique` (`jurusan_id`,`locale`),
  KEY `jurusan_translations_locale_index` (`locale`),
  CONSTRAINT `fk_jurusan_translations_jurusan_id` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('jurusans')) DB::statement('CREATE TABLE IF NOT EXISTS `jurusans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `position` int(10) unsigned DEFAULT NULL,
  `code` varchar(20) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `head_of_department` varchar(255) DEFAULT NULL,
  `accreditation` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('menu_links')) DB::statement('CREATE TABLE IF NOT EXISTS `menu_links` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `position` int(10) unsigned DEFAULT NULL,
  `_lft` int(10) unsigned NOT NULL DEFAULT 0,
  `_rgt` int(10) unsigned NOT NULL DEFAULT 0,
  `parent_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_links__lft__rgt_parent_id_index` (`_lft`,`_rgt`,`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('newsletter_subscribers')) DB::statement('CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` enum(\'pending\',\'active\',\'unsubscribed\') NOT NULL DEFAULT \'pending\',
  `token` varchar(64) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `unsubscribed_at` timestamp NULL DEFAULT NULL,
  `last_sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `newsletter_subscribers_email_unique` (`email`),
  UNIQUE KEY `newsletter_subscribers_token_unique` (`token`),
  KEY `newsletter_subscribers_email_index` (`email`),
  KEY `newsletter_subscribers_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('page_revisions')) DB::statement('CREATE TABLE IF NOT EXISTS `page_revisions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  PRIMARY KEY (`id`),
  KEY `page_revisions_page_id_foreign` (`page_id`),
  KEY `page_revisions_user_id_foreign` (`user_id`),
  CONSTRAINT `page_revisions_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `page_revisions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `twill_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('page_slugs')) DB::statement('CREATE TABLE IF NOT EXISTS `page_slugs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_page_slugs_page_id` (`page_id`),
  KEY `page_slugs_locale_index` (`locale`),
  CONSTRAINT `fk_page_slugs_page_id` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('page_translations')) DB::statement('CREATE TABLE IF NOT EXISTS `page_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `meta_title` varchar(70) DEFAULT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_id_locale_unique` (`page_id`,`locale`),
  KEY `page_translations_locale_index` (`locale`),
  CONSTRAINT `fk_page_translations_page_id` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('pages')) DB::statement('CREATE TABLE IF NOT EXISTS `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('post_revisions')) DB::statement('CREATE TABLE IF NOT EXISTS `post_revisions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  PRIMARY KEY (`id`),
  KEY `post_revisions_post_id_foreign` (`post_id`),
  KEY `post_revisions_user_id_foreign` (`user_id`),
  CONSTRAINT `post_revisions_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `post_revisions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `twill_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('post_slugs')) DB::statement('CREATE TABLE IF NOT EXISTS `post_slugs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_post_slugs_post_id` (`post_id`),
  KEY `post_slugs_locale_index` (`locale`),
  CONSTRAINT `fk_post_slugs_post_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('post_translations')) DB::statement('CREATE TABLE IF NOT EXISTS `post_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `meta_title` varchar(70) DEFAULT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_id_locale_unique` (`post_id`,`locale`),
  KEY `post_translations_locale_index` (`locale`),
  CONSTRAINT `fk_post_translations_post_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('posts')) DB::statement('CREATE TABLE IF NOT EXISTS `posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `position` int(10) unsigned DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `post_date` timestamp NULL DEFAULT NULL,
  `_lft` int(10) unsigned NOT NULL DEFAULT 0,
  `_rgt` int(10) unsigned NOT NULL DEFAULT 0,
  `parent_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `posts__lft__rgt_parent_id_index` (`_lft`,`_rgt`,`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('project_revisions')) DB::statement('CREATE TABLE IF NOT EXISTS `project_revisions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  PRIMARY KEY (`id`),
  KEY `project_revisions_project_id_foreign` (`project_id`),
  KEY `project_revisions_user_id_foreign` (`user_id`),
  CONSTRAINT `project_revisions_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_revisions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `twill_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('project_slugs')) DB::statement('CREATE TABLE IF NOT EXISTS `project_slugs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_project_slugs_project_id` (`project_id`),
  KEY `project_slugs_locale_index` (`locale`),
  CONSTRAINT `fk_project_slugs_project_id` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('project_translations')) DB::statement('CREATE TABLE IF NOT EXISTS `project_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `meta_title` varchar(70) DEFAULT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_id_locale_unique` (`project_id`,`locale`),
  KEY `project_translations_locale_index` (`locale`),
  CONSTRAINT `fk_project_translations_project_id` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('projects')) DB::statement('CREATE TABLE IF NOT EXISTS `projects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `position` int(10) unsigned DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `project_date` timestamp NULL DEFAULT NULL,
  `_lft` int(10) unsigned NOT NULL DEFAULT 0,
  `_rgt` int(10) unsigned NOT NULL DEFAULT 0,
  `parent_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `projects__lft__rgt_parent_id_index` (`_lft`,`_rgt`,`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('team_member_revisions')) DB::statement('CREATE TABLE IF NOT EXISTS `team_member_revisions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `team_member_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  PRIMARY KEY (`id`),
  KEY `team_member_revisions_team_member_id_foreign` (`team_member_id`),
  KEY `team_member_revisions_user_id_foreign` (`user_id`),
  CONSTRAINT `team_member_revisions_team_member_id_foreign` FOREIGN KEY (`team_member_id`) REFERENCES `team_members` (`id`) ON DELETE CASCADE,
  CONSTRAINT `team_member_revisions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `twill_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('team_member_slugs')) DB::statement('CREATE TABLE IF NOT EXISTS `team_member_slugs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `team_member_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_team_member_slugs_team_member_id` (`team_member_id`),
  KEY `team_member_slugs_locale_index` (`locale`),
  CONSTRAINT `fk_team_member_slugs_team_member_id` FOREIGN KEY (`team_member_id`) REFERENCES `team_members` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('team_member_translations')) DB::statement('CREATE TABLE IF NOT EXISTS `team_member_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `team_member_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `locale` varchar(7) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `meta_title` varchar(70) DEFAULT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_member_id_locale_unique` (`team_member_id`,`locale`),
  KEY `team_member_translations_locale_index` (`locale`),
  CONSTRAINT `fk_team_member_translations_team_member_id` FOREIGN KEY (`team_member_id`) REFERENCES `team_members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!Schema::hasTable('team_members')) DB::statement('CREATE TABLE IF NOT EXISTS `team_members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `position` int(10) unsigned DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `youtube_url` varchar(255) DEFAULT NULL,
  `whatsapp_url` varchar(255) DEFAULT NULL,
  `is_leadership` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        Schema::enableForeignKeyConstraints();
    }
    public function down(): void {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('achievement_revisions');
        Schema::dropIfExists('achievement_slugs');
        Schema::dropIfExists('achievement_translations');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('agenda_revisions');
        Schema::dropIfExists('agenda_slugs');
        Schema::dropIfExists('agenda_translations');
        Schema::dropIfExists('agendas');
        Schema::dropIfExists('brosur_revisions');
        Schema::dropIfExists('brosur_slugs');
        Schema::dropIfExists('brosur_translations');
        Schema::dropIfExists('brosurs');
        Schema::dropIfExists('checkpoint_scan_results');
        Schema::dropIfExists('extracurricular_revisions');
        Schema::dropIfExists('extracurricular_slugs');
        Schema::dropIfExists('extracurricular_translations');
        Schema::dropIfExists('extracurriculars');
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('facility_revisions');
        Schema::dropIfExists('facility_slugs');
        Schema::dropIfExists('facility_translations');
        Schema::dropIfExists('job_vacancies');
        Schema::dropIfExists('job_vacancy_revisions');
        Schema::dropIfExists('job_vacancy_slugs');
        Schema::dropIfExists('job_vacancy_translations');
        Schema::dropIfExists('jurusan_revisions');
        Schema::dropIfExists('jurusan_slugs');
        Schema::dropIfExists('jurusan_translations');
        Schema::dropIfExists('jurusans');
        Schema::dropIfExists('menu_links');
        Schema::dropIfExists('newsletter_subscribers');
        Schema::dropIfExists('page_revisions');
        Schema::dropIfExists('page_slugs');
        Schema::dropIfExists('page_translations');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('post_revisions');
        Schema::dropIfExists('post_slugs');
        Schema::dropIfExists('post_translations');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('project_revisions');
        Schema::dropIfExists('project_slugs');
        Schema::dropIfExists('project_translations');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('team_member_revisions');
        Schema::dropIfExists('team_member_slugs');
        Schema::dropIfExists('team_member_translations');
        Schema::dropIfExists('team_members');
        Schema::enableForeignKeyConstraints();
    }
};
