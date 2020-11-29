-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.7.24 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for mad_rest_api
CREATE DATABASE IF NOT EXISTS `mad_rest_api` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `mad_rest_api`;

-- Dumping structure for table mad_rest_api.attractions
CREATE TABLE IF NOT EXISTS `attractions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tempat_wisata` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provinsi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_pengunjung` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mad_rest_api.attractions: ~0 rows (approximately)
/*!40000 ALTER TABLE `attractions` DISABLE KEYS */;
/*!40000 ALTER TABLE `attractions` ENABLE KEYS */;

-- Dumping structure for table mad_rest_api.dok_approval
CREATE TABLE IF NOT EXISTS `dok_approval` (
  `id_dok_approval` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama_dokumen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_dokumen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_dokumen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_dok_approval`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mad_rest_api.dok_approval: ~0 rows (approximately)
/*!40000 ALTER TABLE `dok_approval` DISABLE KEYS */;
/*!40000 ALTER TABLE `dok_approval` ENABLE KEYS */;

-- Dumping structure for table mad_rest_api.dok_temuan
CREATE TABLE IF NOT EXISTS `dok_temuan` (
  `id_dok_temuan` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama_dokumen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_dokumen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_dokumen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `waktu` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_dok_temuan`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mad_rest_api.dok_temuan: ~4 rows (approximately)
/*!40000 ALTER TABLE `dok_temuan` DISABLE KEYS */;
REPLACE INTO `dok_temuan` (`id_dok_temuan`, `nama_dokumen`, `no_dokumen`, `jenis_dokumen`, `tanggal`, `waktu`, `created_at`, `updated_at`) VALUES
	(1, 'nofile.pdf', NULL, NULL, NULL, NULL, '2020-07-24 18:25:10', '2020-07-24 18:25:10'),
	(2, 'nofile.pdf', NULL, NULL, NULL, NULL, '2020-07-24 18:26:11', '2020-07-24 18:26:11'),
	(3, 'nofile.pdf', NULL, NULL, NULL, NULL, '2020-07-24 18:27:22', '2020-07-24 18:27:22'),
	(4, 'document_1595615423.pdf', NULL, NULL, NULL, NULL, '2020-07-24 18:30:24', '2020-07-24 18:30:24');
/*!40000 ALTER TABLE `dok_temuan` ENABLE KEYS */;

-- Dumping structure for table mad_rest_api.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mad_rest_api.failed_jobs: ~0 rows (approximately)
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;

-- Dumping structure for table mad_rest_api.kpa
CREATE TABLE IF NOT EXISTS `kpa` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `namaKpa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cabang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `isDeleted` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mad_rest_api.kpa: ~2 rows (approximately)
/*!40000 ALTER TABLE `kpa` DISABLE KEYS */;
REPLACE INTO `kpa` (`id`, `namaKpa`, `cabang`, `alamat`, `created_at`, `updated_at`, `isDeleted`) VALUES
	(1, 'HAHA', 'Bogor', 'SGSDF', NULL, '2020-07-27 05:52:53', 0),
	(2, 'GSSD', 'SGDD', 'SGSDG', NULL, NULL, 1);
/*!40000 ALTER TABLE `kpa` ENABLE KEYS */;

-- Dumping structure for table mad_rest_api.log
CREATE TABLE IF NOT EXISTS `log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idUser` int(10) unsigned NOT NULL,
  `namaUser` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cabangUser` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatanUser` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_iduser_foreign` (`idUser`),
  CONSTRAINT `log_iduser_foreign` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mad_rest_api.log: ~1 rows (approximately)
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
REPLACE INTO `log` (`id`, `idUser`, `namaUser`, `cabangUser`, `jabatanUser`, `subject`, `url`, `method`, `ip`, `agent`, `created_at`, `updated_at`) VALUES
	(2, 1, 'Adon', 'Adon', 'Adon', 'Melihat Data KPA', 'http://127.0.0.1:8000/api/kpa/get-data', 'GET', '127.0.0.1', 'PostmanRuntime/7.26.2', '2020-07-27 08:31:27', '2020-07-27 08:31:27');
/*!40000 ALTER TABLE `log` ENABLE KEYS */;

-- Dumping structure for table mad_rest_api.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mad_rest_api.migrations: ~17 rows (approximately)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2019_08_19_000000_create_failed_jobs_table', 1),
	(3, '2020_04_18_132713_create_attractions_table', 1),
	(4, '2020_07_18_223536_create_ppk_table', 1),
	(5, '2020_07_18_224345_create_dok_temuan_table', 1),
	(6, '2020_07_18_224447_create_dok_approval_table', 1),
	(7, '2020_07_20_021133_create_kpa_table', 1),
	(8, '2020_07_20_021356_create_kpas_table', 2),
	(9, '2020_07_24_143330_create_kpa_table', 3),
	(10, '2020_07_24_143524_create_kpa_table', 4),
	(11, '2020_07_24_181714_add_alamat_to_kpa_table', 5),
	(12, '2020_07_25_022357_create_tindak_lanjut_table', 6),
	(13, '2020_07_25_033738_create_tindak_lanjut_table', 7),
	(14, '2020_07_25_122921_add_foto_to_users_table', 8),
	(15, '2020_07_27_055953_create_log_table', 9),
	(16, '2020_07_27_062323_create_log_table', 10),
	(17, '2020_07_27_072542_create_log_table', 11);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Dumping structure for table mad_rest_api.ppk
CREATE TABLE IF NOT EXISTS `ppk` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idKpa` int(11) NOT NULL,
  `namaPpk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `isDeleted` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mad_rest_api.ppk: ~1 rows (approximately)
/*!40000 ALTER TABLE `ppk` DISABLE KEYS */;
REPLACE INTO `ppk` (`id`, `idKpa`, `namaPpk`, `created_at`, `updated_at`, `isDeleted`) VALUES
	(1, 1, 'GSDG', NULL, NULL, 0);
/*!40000 ALTER TABLE `ppk` ENABLE KEYS */;

-- Dumping structure for table mad_rest_api.tindak_lanjut
CREATE TABLE IF NOT EXISTS `tindak_lanjut` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idUser` int(10) unsigned NOT NULL,
  `idPpk` int(10) unsigned NOT NULL,
  `idKpa` int(10) unsigned NOT NULL,
  `namaDokumen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `noDokumen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fileDokumen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isDeleted` int(1) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tglDariBpk` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tglKePpk` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tindak_lanjut_id_user_foreign` (`idUser`),
  KEY `tindak_lanjut_id_ppk_foreign` (`idPpk`),
  KEY `tindak_lanjut_id_kpa_foreign` (`idKpa`),
  CONSTRAINT `tindak_lanjut_id_kpa_foreign` FOREIGN KEY (`idKpa`) REFERENCES `kpa` (`id`),
  CONSTRAINT `tindak_lanjut_id_ppk_foreign` FOREIGN KEY (`idPpk`) REFERENCES `ppk` (`id`),
  CONSTRAINT `tindak_lanjut_id_user_foreign` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mad_rest_api.tindak_lanjut: ~1 rows (approximately)
/*!40000 ALTER TABLE `tindak_lanjut` DISABLE KEYS */;
REPLACE INTO `tindak_lanjut` (`id`, `idUser`, `idPpk`, `idKpa`, `namaDokumen`, `noDokumen`, `fileDokumen`, `isDeleted`, `status`, `tglDariBpk`, `tglKePpk`, `created_at`, `updated_at`) VALUES
	(3, 1, 1, 1, 'HAMR', '884AJA', 'FSD_1595735851.pdf', 0, 'On', NULL, NULL, '2020-07-26 03:57:31', '2020-07-26 03:57:31');
/*!40000 ALTER TABLE `tindak_lanjut` ENABLE KEYS */;

-- Dumping structure for table mad_rest_api.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cabang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isDeleted` int(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mad_rest_api.users: ~1 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
REPLACE INTO `users` (`id`, `nama`, `cabang`, `jabatan`, `username`, `email`, `password`, `role`, `isDeleted`, `remember_token`, `created_at`, `updated_at`, `foto`) VALUES
	(1, 'Adon', 'Pusat', 'CTO', 'adon', 'adon@gmail.com', '$2y$10$4y1GjDfrRmQwX79lQlc82ORhkBTDBmbdxzGQn0S5olBGlDgaRUMqm', 'admin', 0, NULL, '2020-07-20 02:12:20', '2020-07-20 02:12:20', '');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
