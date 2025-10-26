/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE IF NOT EXISTS `user_management` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `cpf` varchar(50) DEFAULT NULL,
  `whatsapp` varchar(50) DEFAULT NULL,
  `user` varchar(50) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `profile` varchar(200) DEFAULT NULL,
  `mail` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `date_birth` date DEFAULT NULL,
  `zip_code` varchar(50) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

INSERT INTO `user_management` (`id`, `name`, `cpf`, `whatsapp`, `user`, `password`, `profile`, `mail`, `phone`, `date_birth`, `zip_code`, `address`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Gustavo Hammes', '176.339.597-89', '22980558545', '21913558500', '$2y$10$u80q/7KSkg09E1y5bsgOAu75EURedJUSvexux9PSpM2Myfor8Y852', 'User', 'sistema1.gustavo@gmail.com', '(21) 9805-5851', '1977-06-07', '20.710-180', 'Rua Caiapó, nº 15, Complemento: Ap 405, Engenho No', '2025-08-03 14:47:48', '2025-08-10 17:02:22', NULL),
	(2, 'Gustavo Hammes', '276.339.597-89', '31980558545', '21913558501', '$2y$10$VP2HqQGUysRHQ.9EeIfJ0ugBE4MEeNnSac0obf.BNV.zrr7RKMZRW', 'User', 'sistema2.gustavo@gmail.com', '(21) 4545-4545', '1977-06-07', '20.710-180', 'Rua Caiapó, S/N, Complemento: Sem um complemento, ', '2025-08-03 14:52:55', '2025-08-10 17:02:24', NULL),
	(3, 'Gustavo Hammes', '076.339.597-89', '21980558545', '21913558502', '$2y$10$8hsIJUkz0gS8xWB7u8UEa.Owdf5ajnTJXJWkMnQPLn8vwZHGEvxW6', 'User', 'sistema4.gustavo@gmail.com', '(21) 9805-5854', '1977-06-07', '20.710-180', 'Rua Caiapó, nº 15, Complemento: Apt 405, Engenho N', '2025-08-03 14:55:32', '2025-08-10 17:02:27', NULL),
	(4, 'Gustavo Hammes', '07633959703', '21983558502', '21913558503', '$2y$10$9on6kpqUHUPMFYWOIeROueVnLFK/WNPOxYBELGq32LKSShQ2C56Pi', 'User', 'sistema3.gustavo@gmail.com', '(21)4545-4545', '1977-06-07', '20170-180', 'Caiapo, 15', '2025-08-03 20:25:25', '2025-08-10 17:02:30', NULL),
	(5, 'Gustavo Hammes', '07133959704', '21983558504', '21913558505', '$2y$10$h090C3KIHstz5vnyJCdsueqYBoy0tenU5pvbgZ2hAowJU6xrO3kBO', 'User', 'sistema5.gustavo@gmail.com', '(21)4545-4545', '1977-06-07', '20170-180', 'Caiapo, 15', '2025-08-09 20:05:07', '2025-08-10 17:03:08', NULL),
	(6, 'Gustavo Hammes', '07233959700', '21983558500', '21913558505', '$2y$10$724n/RS/gvFxNicMwpj0Z.T89LFfUrB2JHT.NrNKmi0gIRhgCQci.', 'User', 'sistema.6gustavo@gmail.com', '(21)4545-4545', '1977-06-07', '20170-180', 'Caiapo, 15', '2025-08-09 20:21:32', '2025-08-10 17:02:35', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
