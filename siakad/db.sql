/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.7.2-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: dbkegiatan_dosen
-- ------------------------------------------------------
-- Server version	11.7.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

CREATE DATABASE IF NOT EXISTS `dbkegiatan_dosen` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci */;
USE `dbkegiatan_dosen`;

--
-- Table structure for table `bidang_ilmu`
--

DROP TABLE IF EXISTS `bidang_ilmu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `bidang_ilmu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bidang_ilmu`
--

LOCK TABLES `bidang_ilmu` WRITE;
/*!40000 ALTER TABLE `bidang_ilmu` DISABLE KEYS */;
INSERT INTO `bidang_ilmu` VALUES
(1,'Ilmu Komputer',NULL),
(2,'Teknik Informatika',NULL),
(3,'Sistem Informasi',NULL),
(4,'Teknik Elektro',NULL),
(5,'Matematika',NULL),
(6,'Fisika',NULL),
(7,'Kimia',NULL),
(8,'Biologi',NULL),
(9,'Ekonomi',NULL),
(10,'Manajemen',NULL);
/*!40000 ALTER TABLE `bidang_ilmu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dosen`
--

DROP TABLE IF EXISTS `dosen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dosen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nidn` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telp` varchar(20) DEFAULT NULL,
  `gelar_belakang` varchar(30) DEFAULT NULL,
  `gelar_depan` varchar(20) DEFAULT NULL,
  `jenis_kelamin` char(1) DEFAULT NULL,
  `tempat_lahir` varchar(45) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `tahun_masuk` int(11) DEFAULT NULL,
  `prodi_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dosen_ibfk_1` (`prodi_id`),
  CONSTRAINT `dosen_ibfk_1` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dosen`
--

LOCK TABLES `dosen` WRITE;
/*!40000 ALTER TABLE `dosen` DISABLE KEYS */;
INSERT INTO `dosen` VALUES
(18,'9012345678','Test','test@mail.co','test','08123456789','S.Test','Dr','L','Bogor','2025-05-14',2022,1);
/*!40000 ALTER TABLE `dosen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dosen_kegiatan`
--

DROP TABLE IF EXISTS `dosen_kegiatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dosen_kegiatan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dosen_id` int(11) NOT NULL,
  `kegiatan_id` int(11) NOT NULL,
  `peran` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dosen_id` (`dosen_id`),
  KEY `kegiatan_id` (`kegiatan_id`),
  CONSTRAINT `dosen_kegiatan_ibfk_1` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dosen_kegiatan_ibfk_2` FOREIGN KEY (`kegiatan_id`) REFERENCES `kegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dosen_kegiatan`
--

LOCK TABLES `dosen_kegiatan` WRITE;
/*!40000 ALTER TABLE `dosen_kegiatan` DISABLE KEYS */;
/*!40000 ALTER TABLE `dosen_kegiatan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jenis_kegiatan`
--

DROP TABLE IF EXISTS `jenis_kegiatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jenis_kegiatan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jenis_kegiatan`
--

LOCK TABLES `jenis_kegiatan` WRITE;
/*!40000 ALTER TABLE `jenis_kegiatan` DISABLE KEYS */;
INSERT INTO `jenis_kegiatan` VALUES
(1,'Penelitian Dasar'),
(2,'Penelitian Terapan'),
(3,'Penelitian Pengembangan'),
(4,'Pengabdian Desa Binaan'),
(5,'Pengabdian Kemitraan'),
(6,'Seminar Nasional'),
(7,'Seminar Internasional'),
(8,'Workshop Teknologi'),
(9,'Workshop Pendidikan'),
(10,'Pelatihan Soft Skill'),
(11,'Pelatihan Hard Skill'),
(12,'Jurnal Nasional'),
(13,'Jurnal Internasional'),
(14,'Konferensi Nasional'),
(15,'Konferensi Internasional'),
(20,'TEST');
/*!40000 ALTER TABLE `jenis_kegiatan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kegiatan`
--

DROP TABLE IF EXISTS `kegiatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `kegiatan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `jenis_kegiatan_id` int(11) DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `tempat` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jenis_kegiatan_id` (`jenis_kegiatan_id`),
  CONSTRAINT `kegiatan_ibfk_1` FOREIGN KEY (`jenis_kegiatan_id`) REFERENCES `jenis_kegiatan` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kegiatan`
--

LOCK TABLES `kegiatan` WRITE;
/*!40000 ALTER TABLE `kegiatan` DISABLE KEYS */;
INSERT INTO `kegiatan` VALUES
(1,'Pengembangan Sistem Informasi Akademik','Penelitian untuk mengembangkan sistem informasi akademik yang terintegrasi','2023-01-15',3,NULL,NULL),
(2,'Workshop Pemrograman Web','Workshop tentang pemrograman web modern dengan framework terbaru','2023-02-20',8,NULL,NULL),
(3,'Seminar Kecerdasan Buatan','Seminar nasional tentang perkembangan kecerdasan buatan di Indonesia','2023-03-10',6,NULL,NULL),
(4,'Penelitian Algoritma Machine Learning','Penelitian dasar tentang pengembangan algoritma machine learning','2023-04-05',1,NULL,NULL),
(5,'Pengabdian Masyarakat Desa Digital','Program pengabdian untuk mengembangkan desa digital','2023-05-12',4,NULL,NULL),
(6,'Publikasi Jurnal Internasional','Publikasi hasil penelitian di jurnal internasional bereputasi','2023-06-18',13,NULL,NULL),
(7,'Konferensi Internasional Big Data','Konferensi internasional tentang big data dan analitiknya','2023-07-22',15,NULL,NULL),
(8,'Pelatihan Data Science','Pelatihan tentang data science dan implementasinya','2023-08-14',11,NULL,NULL),
(9,'Penelitian Energi Terbarukan','Penelitian terapan tentang energi terbarukan','2023-09-09',2,NULL,NULL),
(10,'Workshop Artificial Intelligence','Workshop tentang implementasi AI dalam berbagai bidang','2023-10-30',8,NULL,NULL),
(11,'Seminar Blockchain','Seminar nasional tentang teknologi blockchain','2023-11-15',6,NULL,NULL),
(12,'Penelitian Internet of Things','Penelitian pengembangan teknologi IoT','2023-12-05',3,NULL,NULL),
(13,'Pengabdian Literasi Digital','Program pengabdian untuk meningkatkan literasi digital masyarakat','2024-01-20',4,NULL,NULL),
(14,'Publikasi Jurnal Nasional','Publikasi hasil penelitian di jurnal nasional terakreditasi','2024-02-10',12,NULL,NULL),
(15,'Konferensi Nasional Informatika','Konferensi nasional tentang perkembangan informatika di Indonesia','2024-03-18',14,NULL,NULL);
/*!40000 ALTER TABLE `kegiatan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penelitian`
--

DROP TABLE IF EXISTS `penelitian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `penelitian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` text DEFAULT NULL,
  `mulai` date DEFAULT NULL,
  `akhir` date DEFAULT NULL,
  `tahun_ajaran` varchar(5) DEFAULT NULL,
  `bidang_ilmu_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penelitian_ibfk_1` (`bidang_ilmu_id`),
  CONSTRAINT `penelitian_ibfk_1` FOREIGN KEY (`bidang_ilmu_id`) REFERENCES `bidang_ilmu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penelitian`
--

LOCK TABLES `penelitian` WRITE;
/*!40000 ALTER TABLE `penelitian` DISABLE KEYS */;
/*!40000 ALTER TABLE `penelitian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prodi`
--

DROP TABLE IF EXISTS `prodi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `prodi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(10) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `telpon` varchar(20) DEFAULT NULL,
  `ketua` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prodi`
--

LOCK TABLES `prodi` WRITE;
/*!40000 ALTER TABLE `prodi` DISABLE KEYS */;
INSERT INTO `prodi` VALUES
(1,'TI','Teknik Informatika','Test','0812345678','Test');
/*!40000 ALTER TABLE `prodi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tim_penelitian`
--

DROP TABLE IF EXISTS `tim_penelitian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tim_penelitian` (
  `dosen_id` int(11) NOT NULL,
  `penelitian_id` int(11) NOT NULL,
  `peran` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`dosen_id`,`penelitian_id`),
  KEY `tim_penelitian_ibfk_2` (`penelitian_id`),
  CONSTRAINT `tim_penelitian_ibfk_1` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tim_penelitian_ibfk_2` FOREIGN KEY (`penelitian_id`) REFERENCES `penelitian` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tim_penelitian`
--

LOCK TABLES `tim_penelitian` WRITE;
/*!40000 ALTER TABLE `tim_penelitian` DISABLE KEYS */;
/*!40000 ALTER TABLE `tim_penelitian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','dosen','staff') NOT NULL DEFAULT 'dosen',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Administrator','admin@example.com','admin',1,'2025-05-12 05:07:32','2025-05-12 05:07:32',NULL,NULL,NULL,NULL,NULL),
(2,'budi','$2y$10$zKlO9nKDa.WwJTACQ7aeA.6NM1GBhfVQSlmYUH3UZAZlrZYvwCJI6','Dr. Budi Santoso','budi.santoso@example.com','dosen',1,'2025-05-12 05:07:32','2025-05-12 05:07:32',NULL,NULL,NULL,NULL,NULL),
(3,'siti','$2y$10$zKlO9nKDa.WwJTACQ7aeA.6NM1GBhfVQSlmYUH3UZAZlrZYvwCJI6','Prof. Siti Rahayu','siti.rahayu@example.com','dosen',1,'2025-05-12 05:07:32','2025-05-12 05:07:32',NULL,NULL,NULL,NULL,NULL),
(4,'ahmad','$2y$10$zKlO9nKDa.WwJTACQ7aeA.6NM1GBhfVQSlmYUH3UZAZlrZYvwCJI6','Dr. Ahmad Wijaya','ahmad.wijaya@example.com','dosen',1,'2025-05-12 05:07:32','2025-05-12 05:07:32',NULL,NULL,NULL,NULL,NULL),
(5,'staff','$2y$10$Uj7OxWY0RzpzJ1.4LJIgXu9RwSIS0KR0LCEWFn1xAEpLOQZ5J8Bre','Staff Akademik','staff@example.com','staff',1,'2025-05-12 05:07:32','2025-05-12 05:07:32',NULL,NULL,NULL,NULL,NULL),
(9,'testing','$2y$10$RoGa2ARpwD4gtLy78zQUDukTXCUhdTKnGfcMWbOcFWIyQn7p1ggYa','test','test@mail.co','dosen',1,'2025-05-14 09:12:44','2025-05-14 09:46:55','08123456789','test',NULL,'test',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-05-14 16:49:18
