-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.20-log - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for backendexampletest
DROP DATABASE IF EXISTS `backendexampletest`;
CREATE DATABASE IF NOT EXISTS `backendexampletest` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `backendexampletest`;

-- Dumping structure for table backendexampletest.account
DROP TABLE IF EXISTS `account`;
CREATE TABLE IF NOT EXISTS `account` (
  `idAccount` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idCustomer` int(11) unsigned DEFAULT NULL,
  `BonusParameter` int(3) unsigned DEFAULT NULL,
  `AccountBalance` int(11) DEFAULT '0',
  `BonusBalance` int(11) unsigned DEFAULT '0',
  `NumberOfDeposits` int(11) unsigned DEFAULT '0',
  `DateTimeAdded` datetime DEFAULT CURRENT_TIMESTAMP,
  `DateTimeModified` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idAccount`),
  KEY `FK_account_customer` (`idCustomer`),
  CONSTRAINT `FK_account_customer` FOREIGN KEY (`idCustomer`) REFERENCES `customer` (`IdCustomer`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table backendexampletest.customer
DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `IdCustomer` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(15) CHARACTER SET utf8 NOT NULL,
  `LastName` varchar(15) CHARACTER SET utf8 NOT NULL,
  `EmailAddress` varchar(150) CHARACTER SET utf8 NOT NULL,
  `Country` varchar(3) CHARACTER SET utf8 NOT NULL,
  `Gender` enum('Male','Female','Other') CHARACTER SET utf8 NOT NULL DEFAULT 'Other',
  `DateTimeAdded` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DateTimeModified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`IdCustomer`),
  UNIQUE KEY `EmailAddress` (`EmailAddress`),
  KEY `Country` (`Country`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for procedure backendexampletest.sp_CustomerAdd
DROP PROCEDURE IF EXISTS `sp_CustomerAdd`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_CustomerAdd`(
	IN `p_FirstName` VARCHAR(15),
	IN `p_LastName` VARCHAR(15),
	IN `p_EmailAddress` VARCHAR(150),
	IN `p_Country` VARCHAR(3),
	IN `p_Gender` ENUM('Male','Female','Other')






)
BEGIN
	INSERT INTO customer
	(FirstName,LastName,EmailAddress,Country,Gender)
	VALUES
	(p_FirstName,p_LastName,p_EmailAddress,p_Country,p_Gender);

	SELECT LAST_INSERT_ID() INTO @UserId;

	INSERT INTO account (account.idCustomer,account.BonusParameter)
	VALUES (@UserId,ROUND((RAND() * (20-5))+5));

	SELECT @UserId as CustomerId;
END//
DELIMITER ;

-- Dumping structure for procedure backendexampletest.sp_CustomerEdit
DROP PROCEDURE IF EXISTS `sp_CustomerEdit`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_CustomerEdit`(
	IN `p_IdCustomer` INT,
	IN `p_FirstName` VARCHAR(15),
	IN `p_LastName` VARCHAR(15),
	IN `p_EmailAddress` VARCHAR(150),
	IN `p_Country` VARCHAR(3),
	IN `p_Gender` ENUM('Male','Female','Other')




)
BEGIN
	UPDATE customer SET
	customer.FirstName    =p_FirstName,
	customer.LastName     =p_LastName,
	customer.EmailAddress =p_EmailAddress,
	customer.Country      =p_Country,
	customer.Gender       =p_Gender
	WHERE
	customer.IdCustomer = p_IdCustomer;
	CALL sp_CustomerGetById(p_IdCustomer);
END//
DELIMITER ;

-- Dumping structure for procedure backendexampletest.sp_CustomerGetById
DROP PROCEDURE IF EXISTS `sp_CustomerGetById`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_CustomerGetById`(
	IN `p_IdCustomer` INT
)
BEGIN
	SELECT customer.IdCustomer,customer.FirstName,customer.LastName,customer.EmailAddress,customer.Country,customer.Gender
	FROM customer
	WHERE customer.IdCustomer = p_IdCustomer;
END//
DELIMITER ;

-- Dumping structure for procedure backendexampletest.sp_Deposit
DROP PROCEDURE IF EXISTS `sp_Deposit`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_Deposit`(
	IN `p_IdCustomer` INT,
	IN `p_Ammount` INT









)
BEGIN
	SELECT account.idAccount,account.NumberOfDeposits INTO @idAccount,@NumberOfDeposits FROM account WHERE account.idCustomer=p_IdCustomer;

	INSERT INTO transactions (transactions.idAccount,transactions.`Type`,transactions.Value)
	VALUES ( @idAccount,'Deposit',p_Ammount );

	IF( ( (@NumberOfDeposits+1 ) % 3 ) = 0 )
	THEN
		UPDATE account
		SET 	account.AccountBalance = account.AccountBalance + p_Ammount ,
				account.NumberOfDeposits = account.NumberOfDeposits + 1 ,
				account.BonusBalance = account.BonusBalance + ( p_Ammount * account.BonusParameter / 100 )
		WHERE account.idCustomer=p_IdCustomer;
	ELSE
		UPDATE account
		SET 	account.AccountBalance = account.AccountBalance + p_Ammount ,
				account.NumberOfDeposits = account.NumberOfDeposits + 1
		WHERE account.idCustomer=p_IdCustomer;
	END IF;

	SELECT p_IdCustomer as IdCustomer,( account.AccountBalance + account.BonusBalance ) as TotalBalance, account.BonusBalance as BonusBalance
	FROM account
	WHERE account.idCustomer=p_IdCustomer;
END//
DELIMITER ;

-- Dumping structure for procedure backendexampletest.sp_GetTotalDepositAndWithdrawals
DROP PROCEDURE IF EXISTS `sp_GetTotalDepositAndWithdrawals`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetTotalDepositAndWithdrawals`()
BEGIN
	SELECT t1.Country,t1.`No Of Costumers`,t1.`No Of Deposits`,t1.`Total Deposit Amount`,t2.`No Of Withdrawals`,t2.`Total Withdrawal Amount`
	FROM (
	SELECT customer.Country,COUNT( DISTINCT(customer.IdCustomer)) as 'No Of Costumers',COUNT( deposits.idAccount) as 'No Of Deposits', SUM( deposits.Value ) as 'Total Deposit Amount'
	FROM customer
	INNER JOIN account ON account.idCustomer=customer.IdCustomer
	LEFT JOIN transactions as deposits ON deposits.`Type`='Deposit' AND account.idAccount=deposits.idAccount
	GROUP BY customer.Country
	) as t1
	RIGHT JOIN (
	SELECT customer.Country,COUNT( withdrawals.idAccount) as 'No Of Withdrawals',SUM( withdrawals.Value ) * -1 as 'Total Withdrawal Amount'
	FROM customer
	INNER JOIN account ON account.idCustomer=customer.IdCustomer
	LEFT JOIN transactions as withdrawals ON withdrawals.`Type`='Withdrawal' AND account.idAccount=withdrawals.idAccount
	GROUP BY customer.Country
	) as t2 ON t1.Country=t2.Country;
END//
DELIMITER ;

-- Dumping structure for procedure backendexampletest.sp_GetTotalDepositAndWithdrawalsByGivenDate
DROP PROCEDURE IF EXISTS `sp_GetTotalDepositAndWithdrawalsByGivenDate`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetTotalDepositAndWithdrawalsByGivenDate`(
	IN `p_GivenDate` VARCHAR(50)
)
BEGIN
	IF( p_GivenDate='' )
	THEN 
		SELECT DATE_SUB( NOW() , INTERVAL 7 DAY ) INTO @GivenDate;
	ELSE
		SELECT p_GivenDate INTO @GivenDate;
	END IF;

	SELECT @GivenDate,t1.Country,t1.`No Of Costumers`,t1.`No Of Deposits`,t1.`Total Deposit Amount`,t2.`No Of Withdrawals`,t2.`Total Withdrawal Amount`
	FROM (
	SELECT customer.Country,COUNT( DISTINCT(customer.IdCustomer)) as 'No Of Costumers',COUNT( deposits.idAccount) as 'No Of Deposits', SUM( deposits.Value ) as 'Total Deposit Amount'
	FROM customer
	INNER JOIN account ON account.idCustomer=customer.IdCustomer
	LEFT JOIN transactions as deposits ON deposits.`Type`='Deposit' AND account.idAccount=deposits.idAccount AND DATE( deposits.DateTimeAdded )>=@GivenDate
	GROUP BY customer.Country
	) as t1
	RIGHT JOIN (
	SELECT customer.Country,COUNT( withdrawals.idAccount) as 'No Of Withdrawals',SUM( withdrawals.Value ) * -1 as 'Total Withdrawal Amount'
	FROM customer
	INNER JOIN account ON account.idCustomer=customer.IdCustomer
	LEFT JOIN transactions as withdrawals ON withdrawals.`Type`='Withdrawal' AND account.idAccount=withdrawals.idAccount AND DATE( withdrawals.DateTimeAdded )>@GivenDate
	GROUP BY customer.Country
	) as t2 ON t1.Country=t2.Country;	
END//
DELIMITER ;

-- Dumping structure for procedure backendexampletest.sp_Withdraw
DROP PROCEDURE IF EXISTS `sp_Withdraw`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_Withdraw`(
	IN `p_IdCustomer` INT,
	IN `p_Amount` INT




)
BEGIN
	SELECT account.idAccount,(account.AccountBalance-p_Amount) INTO @idAccount,@NewBalance FROM account WHERE account.idCustomer=p_IdCustomer;

	INSERT INTO transactions (transactions.idAccount,transactions.`Type`,transactions.Value)
	VALUES ( @idAccount,'Withdrawal',p_Amount );

	IF( @NewBalance < 0 )
	THEN

		SELECT 'This Customer does not have enough balance to withdraw this ammount' as Error;

	ELSE

		UPDATE account SET account.AccountBalance = @NewBalance WHERE account.idCustomer=p_IdCustomer;

		SELECT p_IdCustomer as IdCustomer,( account.AccountBalance + account.BonusBalance ) as TotalBalance, account.BonusBalance as BonusBalance
		FROM account
		WHERE account.idCustomer=p_IdCustomer;

	END IF;
END//
DELIMITER ;

-- Dumping structure for table backendexampletest.transactions
DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `idAccount` int(11) unsigned DEFAULT NULL,
  `Type` enum('Deposit','Withdrawal') CHARACTER SET utf8 DEFAULT NULL,
  `Value` int(10) unsigned DEFAULT NULL,
  `DateTimeAdded` datetime DEFAULT CURRENT_TIMESTAMP,
  KEY `FK_transactions_account` (`idAccount`),
  CONSTRAINT `FK_transactions_account` FOREIGN KEY (`idAccount`) REFERENCES `account` (`idAccount`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
