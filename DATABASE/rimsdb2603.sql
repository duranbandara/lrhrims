-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table rimsdb.activity_logs
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `role` varchar(20) NOT NULL,
  `action` varchar(50) NOT NULL,
  `module` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rimsdb.activity_logs: ~4 rows (approximately)
INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `role`, `action`, `module`, `description`, `created_at`) VALUES
	(1, '1', 'Administrator', 'admin', 'create', 'Incoming Items', 'Received reagent: 3 units, Lot: 4757547, Item ID: RG0003', '2026-03-18 16:10:39'),
	(2, '1', 'Administrator', 'admin', 'delete', 'Lot Management', 'Deleted lot: 6856754634 for Roche Cobas HbA1c Reagent, qty: 0, reason: test', '2026-03-18 16:24:55'),
	(3, '1', 'Administrator', 'admin', 'delete', 'Lot Management', 'Deleted lot: test345 for Roche Cobas HbA1c Reagent, qty: 5, reason: test2', '2026-03-18 16:25:06');

-- Dumping structure for table rimsdb.items
CREATE TABLE IF NOT EXISTS `items` (
  `id_item` char(7) NOT NULL,
  `des_item` varchar(255) NOT NULL,
  `item_code` varchar(50) DEFAULT NULL COMMENT 'Manufacturer catalog code (QR field 1)',
  `stock` int(11) NOT NULL DEFAULT 0,
  `min_stock` int(11) NOT NULL DEFAULT 10 COMMENT 'Low-stock alert threshold',
  `unit_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `price` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_item`),
  KEY `unit_id` (`unit_id`),
  KEY `type_id` (`type_id`),
  CONSTRAINT `items_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id_unit`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `items_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `type` (`id_type`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rimsdb.items: ~20 rows (approximately)
INSERT INTO `items` (`id_item`, `des_item`, `item_code`, `stock`, `min_stock`, `unit_id`, `type_id`, `price`) VALUES
	('RG0001', 'Sysmex XN-Series CELLPACK DCL Reagent', '00842768014130', 25, 10, 1, 1, 0),
	('RG0002', 'Sysmex XN-Series WBC/DIFF Reagent', 'XN-WBCDIFF', 23, 12, 1, 1, 0),
	('RG0003', 'Sysmex XN-Series Reticulocyte Reagent (RET-SEARCH)', 'XN-RETSRCH', 9, 5, 2, 1, 0),
	('RG0004', 'Roche Cobas Total Cholesterol Reagent', 'CHOL3-COBAS', 14, 5, 2, 2, 0),
	('RG0005', 'Roche Cobas ALT/SGPT Reagent', '00842768020544', 3, 5, 2, 2, 0),
	('RG0006', 'Roche Cobas Creatinine Enzymatic Reagent', 'CREAL-COBAS', 18, 5, 2, 2, 0),
	('RG0007', 'Roche Cobas Glucose Reagent (Hexokinase)', 'GLUC3-COBAS', 25, 5, 2, 2, 0),
	('RG0008', 'Roche Cobas HbA1c Reagent', 'TINA-COBAS', 12, 4, 2, 2, 0),
	('RG0009', 'Roche Cobas Sodium (Na+) Electrolyte Reagent', 'NAREF-COBAS', 10, 5, 2, 2, 0),
	('RG0010', 'Roche Cobas Potassium (K+) Electrolyte Reagent', 'KREF-COBAS', 10, 5, 2, 2, 0),
	('RG0011', 'Abbott Architect Troponin I (cTnI) Reagent', 'TNNI3-ARCH', 8, 3, 3, 3, 0),
	('RG0012', 'Abbott Architect CRP High-Sensitivity Reagent', 'HSCRP-ARCH', 12, 4, 3, 3, 0),
	('RG0013', 'Abbott Architect TSH Reagent', 'TSH-ARCH', 4, 3, 3, 3, 0),
	('RG0014', 'Beckman Coulter PT Reagent (Thromboplastin)', 'HemosIL-PT', 15, 5, 3, 4, 0),
	('RG0015', 'Beckman Coulter APTT Reagent (SynthASil)', 'HemosIL-APTT', 15, 5, 3, 4, 0),
	('RG0016', 'BioMerieux Vidas D-Dimer Reagent', 'DDIM-VIDAS', 5, 4, 3, 3, 0),
	('RG0017', 'Siemens CLINITEK Urine Chemistry Strips (100/box)', 'MULTI10-SIE', 30, 20, 1, 5, 0),
	('RG0018', 'Sysmex TriCheck Hematology Control (Level 1)', 'SYS-TRI-L1', 8, 5, 5, 7, 0),
	('RG0019', 'Sysmex TriCheck Hematology Control (Level 2)', 'SYS-TRI-L2', 8, 5, 5, 7, 0),
	('RG0020', 'Roche PreciControl Clinical Chemistry QC Reagent', 'PCC1-ROCHE', 6, 5, 5, 7, 0);

-- Dumping structure for table rimsdb.item_in
CREATE TABLE IF NOT EXISTS `item_in` (
  `id_item_in` char(16) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` char(7) NOT NULL,
  `amount_in` int(11) NOT NULL,
  `date_in` date NOT NULL,
  `lot_number` varchar(50) DEFAULT NULL COMMENT 'Lot/batch from QR',
  `expiry_date` date DEFAULT NULL COMMENT 'Expiry date from QR',
  PRIMARY KEY (`id_item_in`),
  KEY `user_id` (`user_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `item_in_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `item_in_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id_supplier`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `item_in_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `items` (`id_item`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rimsdb.item_in: ~37 rows (approximately)
INSERT INTO `item_in` (`id_item_in`, `supplier_id`, `user_id`, `item_id`, `amount_in`, `date_in`, `lot_number`, `expiry_date`) VALUES
	('I2501050001', 1, 1, 'RG0007', 3, '2025-01-05', 'RCH-GL-240601', '2026-03-10'),
	('I2601100001', 2, 1, 'RG0001', 20, '2026-01-10', 'SYS-CP-241101', '2026-12-31'),
	('I2601100002', 2, 1, 'RG0002', 15, '2026-01-10', 'SYS-WB-241102', '2026-11-30'),
	('I2601100003', 2, 1, 'RG0003', 8, '2026-01-10', 'SYS-RT-241103', '2026-10-31'),
	('I2601120001', 1, 1, 'RG0004', 12, '2026-01-12', 'RCH-CH-250101', '2027-01-15'),
	('I2601120002', 1, 1, 'RG0005', 10, '2026-01-12', 'RCH-AL-250102', '2027-02-28'),
	('I2601120003', 1, 1, 'RG0006', 10, '2026-01-12', 'RCH-CR-250103', '2027-01-31'),
	('I2601120004', 1, 1, 'RG0007', 15, '2026-01-12', 'RCH-GL-250104', '2027-03-31'),
	('I2601140001', 1, 1, 'RG0008', 4, '2026-01-14', 'RCH-HA-250105', '2027-01-31'),
	('I2601140002', 1, 1, 'RG0009', 12, '2026-01-14', 'RCH-NA-250106', '2027-02-28'),
	('I2601140003', 1, 1, 'RG0010', 12, '2026-01-14', 'RCH-K-250107', '2027-02-28'),
	('I2601150001', 3, 1, 'RG0011', 6, '2026-01-15', 'ABT-TN-260101', '2027-04-30'),
	('I2601150002', 3, 1, 'RG0012', 8, '2026-01-15', 'ABT-CR-260102', '2027-05-31'),
	('I2601150003', 3, 1, 'RG0013', 6, '2026-01-15', 'ABT-TS-260103', '2027-04-30'),
	('I2601170001', 5, 1, 'RG0016', 5, '2026-01-17', 'BMX-DD-250601', '2026-06-30'),
	('I2601180001', 6, 1, 'RG0017', 40, '2026-01-18', 'SIE-US-251201', '2027-06-30'),
	('I2601200001', 2, 1, 'RG0018', 10, '2026-01-20', 'SYS-QC1-260101', '2026-12-31'),
	('I2601200002', 2, 1, 'RG0019', 10, '2026-01-20', 'SYS-QC2-260101', '2026-12-31'),
	('I2601200003', 1, 1, 'RG0020', 8, '2026-01-20', 'RCH-QC-260101', '2027-01-31'),
	('I2602050001', 2, 7, 'RG0001', 10, '2026-02-05', 'SYS-CP-250201', '2027-02-28'),
	('I2602050002', 2, 7, 'RG0002', 12, '2026-02-05', 'SYS-WB-250202', '2027-01-31'),
	('I2602050003', 1, 7, 'RG0004', 8, '2026-02-05', 'RCH-CH-260201', '2027-06-30'),
	('I2602050004', 1, 7, 'RG0007', 10, '2026-02-05', 'RCH-GL-260202', '2027-07-31'),
	('I2602100001', 3, 7, 'RG0011', 4, '2026-02-10', 'ABT-TN-260201', '2027-08-31'),
	('I2602100002', 3, 7, 'RG0012', 6, '2026-02-10', 'ABT-CR-260203', '2027-07-31'),
	('I2602250000', NULL, 1, 'RG0016', 2, '2026-02-25', '4757547', '2026-04-30'),
	('I2602250001', NULL, 1, 'RG0008', 3, '2026-02-25', '6856754634', '2026-02-28'),
	('I2602260000', NULL, 1, 'RG0008', 10, '2026-02-26', 'test123', '2026-04-15'),
	('I2603030000', NULL, 1, 'RG0001', 10, '2026-03-03', 'GB6105', '2026-04-15'),
	('I2603030001', NULL, 1, 'RG0005', 3, '2026-03-03', 'GA6171', '2026-06-20'),
	('I2603030002', NULL, 1, 'RG0005', 2, '2026-03-03', 'GA6171', '2026-06-20'),
	('I2603090001', NULL, 1, 'RG0001', 5, '2026-03-09', 'GB6105', '2026-04-15'),
	('I2603180001', NULL, 1, 'RG0003', 3, '2026-03-18', '4757547', '2026-04-04');

-- Dumping structure for table rimsdb.item_out
CREATE TABLE IF NOT EXISTS `item_out` (
  `id_item_out` char(16) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_out` date NOT NULL,
  `section_id` int(11) DEFAULT NULL COMMENT 'FK to lab_sections',
  PRIMARY KEY (`id_item_out`),
  KEY `user_id` (`user_id`),
  KEY `section_id` (`section_id`),
  CONSTRAINT `item_out_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `item_out_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `lab_sections` (`id_section`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rimsdb.item_out: ~13 rows (approximately)
INSERT INTO `item_out` (`id_item_out`, `user_id`, `date_out`, `section_id`) VALUES
	('O2601280001', 1, '2026-01-28', 1),
	('O2601300001', 1, '2026-01-30', 2),
	('O2602030001', 7, '2026-02-03', 3),
	('O2602100001', 7, '2026-02-10', 1),
	('O2602140001', 1, '2026-02-14', 4),
	('O2602200001', 7, '2026-02-20', 5),
	('O2602240001', 1, '2026-02-24', 2),
	('O2602250001', 7, '2026-02-25', 3),
	('S2602250000', 1, '2026-02-25', 6),
	('S2602260000', 1, '2026-02-26', 1),
	('S2603030000', 1, '2026-03-03', 1),
	('S2603030001', 1, '2026-03-03', 1),
	('S2603090001', 1, '2026-03-09', 1),
	('S2603260001', 1, '2026-03-26', 1);

-- Dumping structure for table rimsdb.item_out_dtl
CREATE TABLE IF NOT EXISTS `item_out_dtl` (
  `id_detail` int(11) NOT NULL AUTO_INCREMENT,
  `id_item_out` char(16) NOT NULL,
  `item_id` char(7) NOT NULL,
  `amount_out` int(11) NOT NULL,
  `lot_number` varchar(50) DEFAULT NULL COMMENT 'Which lot was issued (FEFO)',
  PRIMARY KEY (`id_detail`),
  KEY `id_item_out` (`id_item_out`),
  CONSTRAINT `item_out_dtl_ibfk_1` FOREIGN KEY (`id_item_out`) REFERENCES `item_out` (`id_item_out`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rimsdb.item_out_dtl: ~28 rows (approximately)
INSERT INTO `item_out_dtl` (`id_detail`, `id_item_out`, `item_id`, `amount_out`, `lot_number`) VALUES
	(1, 'O2601280001', 'RG0001', 5, 'SYS-CP-241101'),
	(2, 'O2601280001', 'RG0002', 4, 'SYS-WB-241102'),
	(3, 'O2601280001', 'RG0003', 2, 'SYS-RT-241103'),
	(4, 'O2601300001', 'RG0004', 3, 'RCH-CH-250101'),
	(5, 'O2601300001', 'RG0005', 2, 'RCH-AL-250102'),
	(6, 'O2601300001', 'RG0006', 2, 'RCH-CR-250103'),
	(7, 'O2601300001', 'RG0007', 3, 'RCH-GL-240601'),
	(8, 'O2602030001', 'RG0011', 2, 'ABT-TN-260101'),
	(9, 'O2602030001', 'RG0012', 2, 'ABT-CR-260102'),
	(10, 'O2602030001', 'RG0016', 1, 'BMX-DD-250601'),
	(11, 'O2602100001', 'RG0018', 2, 'SYS-QC1-260101'),
	(12, 'O2602100001', 'RG0019', 2, 'SYS-QC2-260101'),
	(13, 'O2602100001', 'RG0020', 2, 'RCH-QC-260101'),
	(14, 'O2602140001', 'RG0014', 3, 'BCK-PT-260101'),
	(15, 'O2602140001', 'RG0015', 3, 'BCK-AP-260102'),
	(16, 'O2602200001', 'RG0017', 10, 'SIE-US-251201'),
	(17, 'O2602240001', 'RG0004', 3, 'RCH-CH-250101'),
	(18, 'O2602240001', 'RG0005', 2, 'RCH-AL-250102'),
	(19, 'O2602240001', 'RG0008', 2, 'RCH-HA-250105'),
	(20, 'O2602240001', 'RG0009', 2, 'RCH-NA-250106'),
	(21, 'O2602240001', 'RG0010', 2, 'RCH-K-250107'),
	(22, 'O2602250001', 'RG0013', 2, 'ABT-TS-260103'),
	(23, 'O2602250001', 'RG0016', 1, 'BMX-DD-250601'),
	(24, 'S2602250000', 'RG0005', 3, 'RCH-AL-250102'),
	(25, 'S2602260000', 'RG0008', 3, '6856754634'),
	(26, 'S2603030000', 'RG0001', 2, 'GB6105'),
	(27, 'S2603030001', 'RG0005', 1, 'GA6171'),
	(28, 'S2603090001', 'RG0001', 1, 'GB6105'),
	(29, 'S2603260001', 'RG0005', 4, 'GA6171');

-- Dumping structure for table rimsdb.lab_sections
CREATE TABLE IF NOT EXISTS `lab_sections` (
  `id_section` int(11) NOT NULL AUTO_INCREMENT,
  `section_name` varchar(100) NOT NULL,
  `d_status` int(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_section`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rimsdb.lab_sections: ~6 rows (approximately)
INSERT INTO `lab_sections` (`id_section`, `section_name`, `d_status`) VALUES
	(1, 'Hematology', 1),
	(2, 'Chemistry', 0),
	(3, 'Immunology', 0),
	(4, 'Coagulation', 0),
	(5, 'Urinalysis', 0),
	(6, 'Blood Bank', 0);

-- Dumping structure for table rimsdb.lot_deletion_logs
CREATE TABLE IF NOT EXISTS `lot_deletion_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lot_id` int(11) NOT NULL,
  `lot_number` varchar(100) NOT NULL,
  `reagent_id` varchar(50) NOT NULL,
  `reagent_name` varchar(200) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `quantity_deleted` decimal(10,2) NOT NULL DEFAULT 0.00,
  `deleted_by_id` varchar(50) NOT NULL,
  `deleted_by_name` varchar(100) NOT NULL,
  `reason` varchar(500) DEFAULT NULL,
  `deleted_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rimsdb.lot_deletion_logs: ~3 rows (approximately)
INSERT INTO `lot_deletion_logs` (`id`, `lot_id`, `lot_number`, `reagent_id`, `reagent_name`, `expiry_date`, `quantity_deleted`, `deleted_by_id`, `deleted_by_name`, `reason`, `deleted_at`) VALUES
	(1, 35, 'GB6105', 'RG0001', 'Sysmex XN-Series CELLPACK DCL Reagent', '2026-04-15', 12.00, '1', 'Administrator', 'test', '2026-03-18 15:58:21'),
	(2, 31, '6856754634', 'RG0008', 'Roche Cobas HbA1c Reagent', '2026-02-28', 0.00, '1', 'Administrator', 'test', '2026-03-18 16:24:55'),
	(3, 34, 'test345', 'RG0008', 'Roche Cobas HbA1c Reagent', '2026-03-10', 5.00, '1', 'Administrator', 'test2', '2026-03-18 16:25:06');

-- Dumping structure for table rimsdb.reagent_lots
CREATE TABLE IF NOT EXISTS `reagent_lots` (
  `id_lot` int(11) NOT NULL AUTO_INCREMENT,
  `reagent_id` char(7) NOT NULL COMMENT 'FK to items.id_item',
  `lot_number` varchar(50) NOT NULL,
  `expiry_date` date NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_lot`),
  UNIQUE KEY `uq_reagent_lot` (`reagent_id`,`lot_number`),
  CONSTRAINT `reagent_lots_ibfk_1` FOREIGN KEY (`reagent_id`) REFERENCES `items` (`id_item`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rimsdb.reagent_lots: ~36 rows (approximately)
INSERT INTO `reagent_lots` (`id_lot`, `reagent_id`, `lot_number`, `expiry_date`, `quantity`) VALUES
	(1, 'RG0001', 'SYS-CP-241101', '2026-12-31', 15),
	(2, 'RG0002', 'SYS-WB-241102', '2026-11-30', 11),
	(3, 'RG0003', 'SYS-RT-241103', '2026-10-31', 6),
	(4, 'RG0004', 'RCH-CH-250101', '2027-01-15', 6),
	(5, 'RG0005', 'RCH-AL-250102', '2027-02-28', 3),
	(6, 'RG0006', 'RCH-CR-250103', '2027-01-31', 8),
	(7, 'RG0007', 'RCH-GL-250104', '2027-03-31', 15),
	(8, 'RG0008', 'RCH-HA-250105', '2027-01-31', 2),
	(9, 'RG0009', 'RCH-NA-250106', '2027-02-28', 10),
	(10, 'RG0010', 'RCH-K-250107', '2027-02-28', 10),
	(11, 'RG0011', 'ABT-TN-260101', '2027-04-30', 4),
	(12, 'RG0012', 'ABT-CR-260102', '2027-05-31', 6),
	(13, 'RG0013', 'ABT-TS-260103', '2027-04-30', 4),
	(14, 'RG0014', 'BCK-PT-260101', '2027-02-28', 7),
	(15, 'RG0015', 'BCK-AP-260102', '2027-02-28', 7),
	(16, 'RG0016', 'BMX-DD-250601', '2026-06-30', 3),
	(17, 'RG0017', 'SIE-US-251201', '2027-06-30', 30),
	(18, 'RG0018', 'SYS-QC1-260101', '2026-12-31', 8),
	(19, 'RG0019', 'SYS-QC2-260101', '2026-12-31', 8),
	(20, 'RG0020', 'RCH-QC-260101', '2027-01-31', 6),
	(21, 'RG0007', 'RCH-GL-240601', '2026-03-10', 0),
	(22, 'RG0001', 'SYS-CP-250201', '2027-02-28', 10),
	(23, 'RG0002', 'SYS-WB-250202', '2027-01-31', 12),
	(24, 'RG0004', 'RCH-CH-260201', '2027-06-30', 8),
	(25, 'RG0007', 'RCH-GL-260202', '2027-07-31', 10),
	(26, 'RG0011', 'ABT-TN-260201', '2027-08-31', 4),
	(27, 'RG0012', 'ABT-CR-260203', '2027-07-31', 6),
	(28, 'RG0014', 'BCK-PT-260201', '2027-06-30', 8),
	(29, 'RG0015', 'BCK-AP-260202', '2027-06-30', 8),
	(30, 'RG0016', '4757547', '2026-04-30', 2),
	(32, 'RG0006', 'i868876', '2026-04-22', 10),
	(33, 'RG0008', 'test123', '2026-04-15', 10),
	(36, 'RG0005', 'GA6171', '2026-06-20', 0),
	(39, 'RG0003', '4757547', '2026-04-04', 3);

-- Dumping structure for table rimsdb.supplier
CREATE TABLE IF NOT EXISTS `supplier` (
  `id_supplier` int(11) NOT NULL AUTO_INCREMENT,
  `des_supplier` varchar(100) NOT NULL,
  `no_telp` varchar(15) NOT NULL DEFAULT '',
  `address` text NOT NULL,
  PRIMARY KEY (`id_supplier`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rimsdb.supplier: ~6 rows (approximately)
INSERT INTO `supplier` (`id_supplier`, `des_supplier`, `no_telp`, `address`) VALUES
	(1, 'Roche Diagnostics Malaysia', '0361418888', 'Suite 16.01, Level 16, Menara Pelangi, Jalan Kuning, Johor Bahru'),
	(2, 'Sysmex Malaysia Sdn Bhd', '0356289800', '2 Jalan 19/1, 46300 Petaling Jaya, Selangor'),
	(3, 'Abbott Laboratories Sdn Bhd', '0361561888', 'Lot 13 Jalan Pelabuhan Utama, Pulau Indah, 42920 Klang, Selangor'),
	(5, 'BioMerieux Malaysia Sdn Bhd', '0387366800', 'Unit 8-12, Level 8, Block B, Menara PKNS-PJ, 46050 Petaling Jaya'),
	(6, 'Siemens Healthineers Malaysia', '0376502000', 'Level 21, Menara Citibank, 165 Jalan Ampang, 50450 Kuala Lumpur'),
	(7, 'test', '45757856865', 'test');

-- Dumping structure for table rimsdb.type
CREATE TABLE IF NOT EXISTS `type` (
  `id_type` int(11) NOT NULL AUTO_INCREMENT,
  `des_type` varchar(50) NOT NULL,
  PRIMARY KEY (`id_type`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rimsdb.type: ~7 rows (approximately)
INSERT INTO `type` (`id_type`, `des_type`) VALUES
	(1, 'Hematology'),
	(2, 'Clinical Chemistry'),
	(3, 'Immunoassay'),
	(4, 'Coagulation'),
	(5, 'Urinalysis'),
	(6, 'Blood Bank'),
	(7, 'Quality Control');

-- Dumping structure for table rimsdb.unit
CREATE TABLE IF NOT EXISTS `unit` (
  `id_unit` int(11) NOT NULL AUTO_INCREMENT,
  `des_unit` varchar(15) NOT NULL,
  PRIMARY KEY (`id_unit`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rimsdb.unit: ~6 rows (approximately)
INSERT INTO `unit` (`id_unit`, `des_unit`) VALUES
	(1, 'Box'),
	(2, 'Bottle'),
	(3, 'Kit'),
	(4, 'Cartridge'),
	(5, 'Vial'),
	(6, 'Strip');

-- Dumping structure for table rimsdb.user
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `des` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_telp` varchar(15) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `password` varchar(255) NOT NULL,
  `created_at` int(11) NOT NULL DEFAULT 0,
  `photo` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rimsdb.user: ~4 rows (approximately)
INSERT INTO `user` (`id_user`, `des`, `username`, `email`, `no_telp`, `role`, `password`, `created_at`, `photo`, `is_active`) VALUES
	(1, 'Administrator', 'admin', 'admin@admin.com', '025123456789', 'admin', '$2y$10$7rLSvRVyTQORapkDOqmkhetjF6H9lJHngr4hJMSM2lHObJbW5EQh6', 1568689561, 'admin-icn.png', 1),
	(7, 'Will Williams', 'williams', 'williams@gmail.com', '7401000010', 'user', '$2y$10$5es8WhFQj8xCmrhDtH86Fu71j97og9f8aR4T22soa7716kAusmaeK', 1568691611, 'user.png', 1),
	(15, 'Liam Moore', 'liamoore', 'liamoore@gmail.com', '7474754520', 'user', '$2y$10$1Yxca5aH4q8XZpMZm0kE..IZk1L/tqDYS0JkAr.mWJ7BeNmRzYdCq', 1622746060, 'user.png', 1),
	(16, 'Demo User 1', 'demouser', 'demo@mail.lk', '07754643736', 'user', '$2y$10$H9uofX26Rzcg8h1gI8g/4eLeDPD4vlJ9Kqy8PoYHqIiB6Q5KnoB3K', 1772091296, 'user.png', 1);

-- Dumping structure for trigger rimsdb.delete_stock_out
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `delete_stock_out`
AFTER DELETE ON `item_out_dtl`
FOR EACH ROW
BEGIN
  UPDATE `items`
    SET `stock` = `stock` + OLD.amount_out
    WHERE `id_item` = OLD.item_id;

  IF OLD.lot_number IS NOT NULL THEN
    UPDATE `reagent_lots`
      SET `quantity` = `quantity` + OLD.amount_out
      WHERE `reagent_id` = OLD.item_id
        AND `lot_number`  = OLD.lot_number;
  END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger rimsdb.update_stock_in
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `update_stock_in`
AFTER INSERT ON `item_in`
FOR EACH ROW
BEGIN
  UPDATE `items`
    SET `stock` = `stock` + NEW.amount_in
    WHERE `id_item` = NEW.item_id;

  IF NEW.lot_number IS NOT NULL AND NEW.expiry_date IS NOT NULL THEN
    INSERT INTO `reagent_lots` (`reagent_id`, `lot_number`, `expiry_date`, `quantity`)
      VALUES (NEW.item_id, NEW.lot_number, NEW.expiry_date, NEW.amount_in)
    ON DUPLICATE KEY UPDATE
      `quantity`    = `quantity` + NEW.amount_in,
      `expiry_date` = NEW.expiry_date;
  END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger rimsdb.update_stock_out
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `update_stock_out`
BEFORE INSERT ON `item_out_dtl`
FOR EACH ROW
BEGIN
  UPDATE `items`
    SET `stock` = `stock` - NEW.amount_out
    WHERE `id_item` = NEW.item_id;

  IF NEW.lot_number IS NOT NULL THEN
    UPDATE `reagent_lots`
      SET `quantity` = `quantity` - NEW.amount_out
      WHERE `reagent_id` = NEW.item_id
        AND `lot_number`  = NEW.lot_number;
  END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
