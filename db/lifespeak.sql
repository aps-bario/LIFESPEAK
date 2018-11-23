# Dump File
#
# Database is ported from MS Access
#--------------------------------------------------------
# Program Version 4.0.192

#CREATE DATABASE IF NOT EXISTS `db423381339`;
#USE `db423381339`;

#
# Table structure for table 'Bookings'
#

DROP TABLE IF EXISTS `Bookings`;

CREATE TABLE `Bookings` (
  `BookingID` INTEGER NOT NULL AUTO_INCREMENT, 
  `ClientID` INTEGER DEFAULT 0, 
  `SessionsPurchased` INTEGER DEFAULT 0, 
  `SessionsBooked` INTEGER DEFAULT 0, 
  `SessionsUsed` INTEGER DEFAULT 0, 
  `Price` DECIMAL(19,4) DEFAULT 0, 
  `Discount` DECIMAL(19,4) DEFAULT 0, 
  `Status` VARCHAR(50), 
  INDEX (`BookingID`), 
  INDEX (`ClientID`), 
  PRIMARY KEY (`BookingID`)
) ENGINE=myisam DEFAULT CHARSET=utf8;

SET autocommit=1;

#
# Dumping data for table 'Bookings'
#

# 0 records

#
# Table structure for table 'BookingStatus'
#

DROP TABLE IF EXISTS `BookingStatus`;

CREATE TABLE `BookingStatus` (
  `Status` VARCHAR(50) NOT NULL, 
  `Description` VARCHAR(50), 
  `ListOrder` INTEGER DEFAULT 0, 
  PRIMARY KEY (`Status`)
) ENGINE=myisam DEFAULT CHARSET=utf8;

SET autocommit=1;

#
# Dumping data for table 'BookingStatus'
#

# 0 records

#
# Table structure for table 'Clients'
#

DROP TABLE IF EXISTS `Clients`;

CREATE TABLE `Clients` (
  `ClientID` INTEGER NOT NULL AUTO_INCREMENT, 
  `Email` VARCHAR(50) NOT NULL, 
  `Password` VARCHAR(50) NOT NULL, 
  `Reminder` VARCHAR(50), 
  `Referrer_Email` VARCHAR(50), 
  `FirstName` VARCHAR(50) NOT NULL, 
  `LastName` VARCHAR(50) NOT NULL, 
  `Salutation` VARCHAR(50), 
  `Title` VARCHAR(50), 
  `Initials` VARCHAR(50), 
  `Address` VARCHAR(50), 
  `City` VARCHAR(50), 
  `County` VARCHAR(50), 
  `Postcode` VARCHAR(50), 
  `Country` VARCHAR(50), 
  `HomePhone` VARCHAR(50), 
  `MobilePhone` VARCHAR(50), 
  `WorkPhone` VARCHAR(50), 
  `Status` VARCHAR(50), 
  `Registered` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  INDEX (`Status`), 
  INDEX (`Postcode`), 
  PRIMARY KEY (`ClientID`)
) ENGINE=myisam DEFAULT CHARSET=utf8;

SET autocommit=1;

#
# Dumping data for table 'Clients'
#

# 0 records

#
# Table structure for table 'ClientStatus'
#

DROP TABLE IF EXISTS `ClientStatus`;

CREATE TABLE `ClientStatus` (
  `Status` VARCHAR(50) NOT NULL, 
  `Description` VARCHAR(50), 
  `ListOrder` INTEGER DEFAULT 0, 
  PRIMARY KEY (`Status`)
) ENGINE=myisam DEFAULT CHARSET=utf8;

SET autocommit=1;

#
# Dumping data for table 'ClientStatus'
#

# 0 records

#
# Table structure for table 'Sessions'
#

DROP TABLE IF EXISTS `Sessions`;

CREATE TABLE `Sessions` (
  `SessionID` INTEGER NOT NULL AUTO_INCREMENT, 
  `CoachID` INTEGER DEFAULT 0, 
  `SessionTime` DATETIME, 
  `Status` VARCHAR(50), 
  `ClientID` INTEGER DEFAULT 0, 
  `BookingID` INTEGER DEFAULT 0, 
  `SessionRate` DECIMAL(19,4) DEFAULT 0, 
  `FeesPaid` DECIMAL(19,4) DEFAULT 0, 
  `CoachPaid` DECIMAL(19,4) DEFAULT 0, 
  `DurationMins` INTEGER DEFAULT 0, 
  INDEX (`BookingID`), 
  INDEX (`ClientID`), 
  INDEX (`CoachID`), 
  PRIMARY KEY (`SessionID`), 
  INDEX (`SessionID`), 
  INDEX (`Status`)
) ENGINE=myisam DEFAULT CHARSET=utf8;

SET autocommit=1;

#
# Dumping data for table 'Sessions'
#

INSERT INTO `Sessions` (`SessionID`, `CoachID`, `SessionTime`, `Status`, `ClientID`, `BookingID`, `SessionRate`, `FeesPaid`, `CoachPaid`, `DurationMins`) VALUES (705, 1, '2007-05-07 20:00:00', 'Booked', 15, 0, 0, 0, 0, 0);
INSERT INTO `Sessions` (`SessionID`, `CoachID`, `SessionTime`, `Status`, `ClientID`, `BookingID`, `SessionRate`, `FeesPaid`, `CoachPaid`, `DurationMins`) VALUES (706, 1, '2007-04-30 20:00:00', 'Booked', 15, 0, 0, 0, 0, 0);
INSERT INTO `Sessions` (`SessionID`, `CoachID`, `SessionTime`, `Status`, `ClientID`, `BookingID`, `SessionRate`, `FeesPaid`, `CoachPaid`, `DurationMins`) VALUES (707, 1, '2007-04-23 20:00:00', 'Booked', 15, 0, 0, 0, 0, 0);
INSERT INTO `Sessions` (`SessionID`, `CoachID`, `SessionTime`, `Status`, `ClientID`, `BookingID`, `SessionRate`, `FeesPaid`, `CoachPaid`, `DurationMins`) VALUES (712, 1, '2007-05-14 20:00:00', 'Booked', 15, 0, 0, 0, 0, 0);
# 4 records

#
# Table structure for table 'SessionStatus'
#

DROP TABLE IF EXISTS `SessionStatus`;

CREATE TABLE `SessionStatus` (
  `Status` VARCHAR(50) NOT NULL, 
  `Description` VARCHAR(50), 
  `ListOrder` INTEGER DEFAULT 0, 
  PRIMARY KEY (`Status`)
) ENGINE=myisam DEFAULT CHARSET=utf8;

SET autocommit=1;

#
# Dumping data for table 'SessionStatus'
#

INSERT INTO `SessionStatus` (`Status`, `Description`, `ListOrder`) VALUES ('Session', NULL, 2);
INSERT INTO `SessionStatus` (`Status`, `Description`, `ListOrder`) VALUES ('Request', NULL, 1);
INSERT INTO `SessionStatus` (`Status`, `Description`, `ListOrder`) VALUES ('Selected', NULL, 0);
INSERT INTO `SessionStatus` (`Status`, `Description`, `ListOrder`) VALUES ('Booked', NULL, 5);
INSERT INTO `SessionStatus` (`Status`, `Description`, `ListOrder`) VALUES ('Booking', NULL, 4);
INSERT INTO `SessionStatus` (`Status`, `Description`, `ListOrder`) VALUES ('Cancelled', NULL, 3);
INSERT INTO `SessionStatus` (`Status`, `Description`, `ListOrder`) VALUES ('Complete', NULL, 6);
# 7 records

#
# Table structure for table 'User_Values'
#

DROP TABLE IF EXISTS `User_Values`;

CREATE TABLE `User_Values` (
  `UserID` INTEGER NOT NULL DEFAULT 0, 
  `ClientID` INTEGER DEFAULT 0, 
  `Value_Name` VARCHAR(50) NOT NULL, 
  `Value_Desc` VARCHAR(50), 
  `Value_Order` INTEGER NOT NULL DEFAULT 0, 
  `Value_Score` INTEGER DEFAULT 0, 
  `Value_Added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  INDEX (`ClientID`), 
  INDEX (`UserID`)
) ENGINE=myisam DEFAULT CHARSET=utf8;

SET autocommit=1;

#
# Dumping data for table 'User_Values'
#

INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (12, 0, 'Loyalty', '', 2, 4, '2006-04-05 23:33:03');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (12, 0, 'Truth', '', 4, 2, '2006-04-05 23:33:08');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (12, 0, 'Faith', '', 1, 5, '2006-04-05 23:33:13');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (12, 0, 'Justice', '', 5, 1, '2006-04-05 23:33:53');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (12, 0, 'Security', '', 6, 0, '2006-04-05 23:34:03');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (12, 0, 'Friendship', '', 3, 3, '2006-04-05 23:34:14');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (1, 0, 'Efficiency', 'Not wasting time, money or resources', 8, 2, '2006-04-06 17:23:05');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (1, 0, 'Righteousness', 'Doing what is Just (By grace rather than Law)', 4, 5, '2006-04-06 17:24:11');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (1, 0, 'Openess', 'Honesty and willingness to share', 7, 2, '2006-04-07 00:58:18');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (1, 0, 'Development', 'Personal Growth', 5, 4, '2006-04-07 00:58:44');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (1, 0, 'Respect', 'Acceptance', 6, 2, '2006-04-07 00:59:42');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (1, 0, 'Purpose', 'A sense of calling and meaning in life', 2, 7, '2006-04-07 01:01:11');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (1, 0, 'Intimacy', 'Trust and closeness in personal relationships', 9, 1, '2006-04-07 01:02:11');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (1, 0, 'Adventure', 'Facing new challenges', 3, 5, '2006-04-07 01:03:47');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (1, 0, 'Discovery', 'New ideas, places, experiences and people', 1, 8, '2006-04-07 01:05:01');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (15, 0, 'freedom', '', 8, 0, '2007-05-06 23:28:50');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (15, 0, 'control', '', 16, 0, '2007-05-06 23:29:04');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (15, 0, 'organisation', '', 5, 0, '2007-05-06 23:29:19');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (15, 0, 'encouragement', '', 10, 0, '2007-05-06 23:29:30');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (15, 0, 'achievement', '', 14, 0, '2007-05-06 23:29:42');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (15, 0, 'creativity', '', 6, 0, '2007-05-06 23:29:51');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (15, 0, 'affection', '', 7, 0, '2007-05-06 23:30:06');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (15, 0, 'communication', '', 2, 0, '2007-05-06 23:30:45');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (15, 0, 'communication', '', 2, 0, '2007-05-06 23:30:48');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (15, 0, 'kindness', '', 13, 0, '2007-05-06 23:31:40');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (15, 0, 'practicality', '', 9, 0, '2007-05-06 23:31:54');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (15, 0, 'spiritual hunger', '', 3, 0, '2007-05-06 23:32:07');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (15, 0, 'reconciliation', '', 4, 0, '2007-05-06 23:32:27');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (15, 0, 'acceptance', '', 12, 0, '2007-05-06 23:32:45');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (15, 0, 'trust', '', 15, 0, '2007-05-06 23:33:07');
INSERT INTO `User_Values` (`UserID`, `ClientID`, `Value_Name`, `Value_Desc`, `Value_Order`, `Value_Score`, `Value_Added`) VALUES (15, 0, 'inspiration', '', 11, 0, '2007-05-06 23:34:23');
# 31 records

#
# Table structure for table 'Users'
#

DROP TABLE IF EXISTS `Users`;

CREATE TABLE `Users` (
  `UserID` INTEGER NOT NULL AUTO_INCREMENT, 
  `ClientID` INTEGER DEFAULT 0, 
  `Email` VARCHAR(50) NOT NULL, 
  `Phone` VARCHAR(50), 
  `Mobile` VARCHAR(50), 
  `Password` VARCHAR(50) NOT NULL, 
  `Reminder` VARCHAR(50), 
  `Registered` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  `FirstName` VARCHAR(50) NOT NULL, 
  `LastName` VARCHAR(50) NOT NULL, 
  `Status` VARCHAR(50) NOT NULL DEFAULT 'Registered', 
  `SessionRate` DECIMAL(19,4) DEFAULT 50, 
  `LastVisited` DATETIME, 
  PRIMARY KEY (`UserID`), 
  INDEX (`Registered`), 
  INDEX (`UserID`)
) ENGINE=myisam DEFAULT CHARSET=utf8;

SET autocommit=1;

#
# Dumping data for table 'Users'
#

INSERT INTO `Users` (`UserID`, `ClientID`, `Email`, `Phone`, `Mobile`, `Password`, `Reminder`, `Registered`, `FirstName`, `LastName`, `Status`, `SessionRate`, `LastVisited`) VALUES (9, 0, 'lynette@bario.co.uk', '024 7667 2903', NULL, 'malatu', 'mother', '2005-11-02 00:50:31', 'Lynette', 'Smith', 'Client', 50, NULL);
INSERT INTO `Users` (`UserID`, `ClientID`, `Email`, `Phone`, `Mobile`, `Password`, `Reminder`, `Registered`, `FirstName`, `LastName`, `Status`, `SessionRate`, `LastVisited`) VALUES (10, 0, 'aps@shaddai.co.uk', '024 7667 2903', '0774 641 2190', 'shaddai', 'mountain', '2005-11-06 01:06:27', 'Andrew', 'Smith', 'Coach', 50, NULL);
INSERT INTO `Users` (`UserID`, `ClientID`, `Email`, `Phone`, `Mobile`, `Password`, `Reminder`, `Registered`, `FirstName`, `LastName`, `Status`, `SessionRate`, `LastVisited`) VALUES (11, 0, 'philr_99@hotmail.com', NULL, NULL, 'asd123', 'a to 3', '2006-01-11 14:11:28', 'Phil', 'Richardson', 'Visitor', 50, NULL);
INSERT INTO `Users` (`UserID`, `ClientID`, `Email`, `Phone`, `Mobile`, `Password`, `Reminder`, `Registered`, `FirstName`, `LastName`, `Status`, `SessionRate`, `LastVisited`) VALUES (12, 0, 'sarah@bario.co.uk', '024 7667 2903', NULL, 'racoon', 'the baboon', '2006-04-05 01:15:21', 'Saz', 'Smith', 'Visitor', 50, NULL);
INSERT INTO `Users` (`UserID`, `ClientID`, `Email`, `Phone`, `Mobile`, `Password`, `Reminder`, `Registered`, `FirstName`, `LastName`, `Status`, `SessionRate`, `LastVisited`) VALUES (13, 0, 'kitty_sniper_on_the_loose@hotmail.com', '024 7667 2903', NULL, 'mathews', 'samantha', '2006-05-23 00:34:36', 'Emma', 'Smith', 'Visitor', 50, '2006-05-23 00:44:00');
INSERT INTO `Users` (`UserID`, `ClientID`, `Email`, `Phone`, `Mobile`, `Password`, `Reminder`, `Registered`, `FirstName`, `LastName`, `Status`, `SessionRate`, `LastVisited`) VALUES (15, 0, 'debs@rumsby.org.uk', '024 7646 9444', NULL, 'shalom', 'peace', '2007-05-06 21:44:32', 'Debbie', 'Rumsby', 'Client', 50, '2007-05-07 00:55:00');
INSERT INTO `Users` (`UserID`, `ClientID`, `Email`, `Phone`, `Mobile`, `Password`, `Reminder`, `Registered`, `FirstName`, `LastName`, `Status`, `SessionRate`, `LastVisited`) VALUES (16, 0, 'helenbalmer@hotmail.co.uk', '(0191) 2461592', NULL, 'cat241171', 'meow', '2007-10-23 00:39:38', 'Helen', 'Balmer', 'Client', 50, '2007-10-23 17:34:00');
INSERT INTO `Users` (`UserID`, `ClientID`, `Email`, `Phone`, `Mobile`, `Password`, `Reminder`, `Registered`, `FirstName`, `LastName`, `Status`, `SessionRate`, `LastVisited`) VALUES (17, 0, 'cyrusc@maximumperformance.co.uk', '0870 0414104', NULL, 'hendonfc1', 'local team', '2009-02-11 16:21:11', 'Cyrus', 'Cooper', 'Client', 50, NULL);
INSERT INTO `Users` (`UserID`, `ClientID`, `Email`, `Phone`, `Mobile`, `Password`, `Reminder`, `Registered`, `FirstName`, `LastName`, `Status`, `SessionRate`, `LastVisited`) VALUES (18, 0, 'bee_chester@yahoo.co.uk', '07828877745', NULL, 'manatee4', 'how many sea cows?', '2009-04-30 16:31:23', 'Bridget', 'Kinnersley', 'Client', 50, '2009-04-30 16:33:00');
INSERT INTO `Users` (`UserID`, `ClientID`, `Email`, `Phone`, `Mobile`, `Password`, `Reminder`, `Registered`, `FirstName`, `LastName`, `Status`, `SessionRate`, `LastVisited`) VALUES (1, 0, 'aps@lifespeak.co.uk', '024 7667 2903', '0774 641 2190', 'shaddai', 'mountain', '2005-10-15 00:00:00', 'Andrew', 'Smith', 'Admin', 50, '2009-10-26 16:42:00');
INSERT INTO `Users` (`UserID`, `ClientID`, `Email`, `Phone`, `Mobile`, `Password`, `Reminder`, `Registered`, `FirstName`, `LastName`, `Status`, `SessionRate`, `LastVisited`) VALUES (3, 0, 'paul@bario.co.uk', '024 7667 2903', '0774 641 2190', 'x', 'x', '2005-10-28 00:30:59', 'Paul', 'Westwood', 'Client', 50, '2006-05-23 01:38:00');
INSERT INTO `Users` (`UserID`, `ClientID`, `Email`, `Phone`, `Mobile`, `Password`, `Reminder`, `Registered`, `FirstName`, `LastName`, `Status`, `SessionRate`, `LastVisited`) VALUES (4, 0, 'aps@bario.co.uk', '024 7667 2903', '0774 641 2190', 'padasaya', 'to me', '2005-10-28 00:35:42', 'Andrew', 'Smith', 'Visitor', 50, '2006-05-25 23:48:00');
INSERT INTO `Users` (`UserID`, `ClientID`, `Email`, `Phone`, `Mobile`, `Password`, `Reminder`, `Registered`, `FirstName`, `LastName`, `Status`, `SessionRate`, `LastVisited`) VALUES (5, 0, 'paul@lifespeak.co.uk', '024 7667 2903', '0774 641 2190', 'westwood', 'church', '2005-10-28 01:15:13', 'Paul', 'Westwood', 'Coach', 50, NULL);
INSERT INTO `Users` (`UserID`, `ClientID`, `Email`, `Phone`, `Mobile`, `Password`, `Reminder`, `Registered`, `FirstName`, `LastName`, `Status`, `SessionRate`, `LastVisited`) VALUES (6, 0, 'lyn@bario.co.uk', '024 7667 2903', NULL, 'Paul', 'Nee', '2005-10-28 01:23:45', 'Lynette', 'Smith', 'Visitor', 50, NULL);
INSERT INTO `Users` (`UserID`, `ClientID`, `Email`, `Phone`, `Mobile`, `Password`, `Reminder`, `Registered`, `FirstName`, `LastName`, `Status`, `SessionRate`, `LastVisited`) VALUES (7, 0, 'sarah@lifespeak.co.uk', '024 7667 2903', NULL, 'Cornelia', 'bugle', '2005-11-01 00:48:38', 'Sarah', 'Smith', 'Coach', 50, NULL);
# 15 records

#
# Table structure for table 'UserStatus'
#

DROP TABLE IF EXISTS `UserStatus`;

CREATE TABLE `UserStatus` (
  `Status` VARCHAR(50) NOT NULL, 
  `Description` VARCHAR(50), 
  `ListOrder` INTEGER DEFAULT 0, 
  PRIMARY KEY (`Status`)
) ENGINE=myisam DEFAULT CHARSET=utf8;

SET autocommit=1;

#
# Dumping data for table 'UserStatus'
#

INSERT INTO `UserStatus` (`Status`, `Description`, `ListOrder`) VALUES ('Admin', 'Admin User', 1);
INSERT INTO `UserStatus` (`Status`, `Description`, `ListOrder`) VALUES ('Register', 'New User waiting for validation of Email', 2);
INSERT INTO `UserStatus` (`Status`, `Description`, `ListOrder`) VALUES ('Visitor', 'Registered User', 3);
INSERT INTO `UserStatus` (`Status`, `Description`, `ListOrder`) VALUES ('Client', 'Registered User and also a Client', 4);
# 4 records

#
# Table structure for table 'ValuesArray'
#

DROP TABLE IF EXISTS `ValuesArray`;

CREATE TABLE `ValuesArray` (
  `UserID` INTEGER DEFAULT 0, 
  `ListOrder` INTEGER DEFAULT 0, 
  `Value1` VARCHAR(50), 
  `Value2` VARCHAR(50), 
  INDEX (`UserID`)
) ENGINE=myisam DEFAULT CHARSET=utf8;

SET autocommit=1;

#
# Dumping data for table 'ValuesArray'
#

INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 14, 'freedom', 'control');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 6, 'organisation', 'freedom');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 90, 'freedom', 'encouragement');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 30, 'achievement', 'freedom');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 11, 'freedom', 'creativity');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 21, 'affection', 'freedom');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 48, 'freedom', 'communication');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 68, 'communication', 'freedom');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 35, 'freedom', 'kindness');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 34, 'practicality', 'freedom');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 10, 'freedom', 'spiritual hunger');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 83, 'reconciliation', 'freedom');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 36, 'freedom', 'acceptance');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 79, 'trust', 'freedom');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 3, 'freedom', 'inspiration');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 30, 'organisation', 'control');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 48, 'control', 'encouragement');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 81, 'achievement', 'control');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 54, 'control', 'creativity');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 62, 'affection', 'control');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 12, 'control', 'communication');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 89, 'communication', 'control');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 84, 'control', 'kindness');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 72, 'practicality', 'control');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 8, 'control', 'spiritual hunger');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 2, 'reconciliation', 'control');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 46, 'control', 'acceptance');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 41, 'trust', 'control');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 88, 'control', 'inspiration');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 99, 'encouragement', 'organisation');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 92, 'organisation', 'achievement');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 14, 'creativity', 'organisation');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 21, 'organisation', 'affection');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 49, 'communication', 'organisation');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 90, 'organisation', 'communication');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 93, 'kindness', 'organisation');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 11, 'organisation', 'practicality');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 30, 'spiritual hunger', 'organisation');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 73, 'organisation', 'reconciliation');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 30, 'acceptance', 'organisation');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 60, 'organisation', 'trust');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 96, 'inspiration', 'organisation');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 35, 'encouragement', 'achievement');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 72, 'creativity', 'encouragement');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 61, 'encouragement', 'affection');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 41, 'communication', 'encouragement');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 1, 'encouragement', 'communication');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 19, 'kindness', 'encouragement');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 20, 'encouragement', 'practicality');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 95, 'spiritual hunger', 'encouragement');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 4, 'encouragement', 'reconciliation');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 76, 'acceptance', 'encouragement');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 62, 'encouragement', 'trust');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 76, 'inspiration', 'encouragement');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 34, 'achievement', 'creativity');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 59, 'affection', 'achievement');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 58, 'achievement', 'communication');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 89, 'communication', 'achievement');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 96, 'achievement', 'kindness');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 1, 'practicality', 'achievement');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 38, 'achievement', 'spiritual hunger');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 86, 'reconciliation', 'achievement');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 16, 'achievement', 'acceptance');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 1, 'trust', 'achievement');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 71, 'achievement', 'inspiration');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 36, 'affection', 'creativity');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 40, 'creativity', 'communication');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 80, 'communication', 'creativity');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 35, 'creativity', 'kindness');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 44, 'practicality', 'creativity');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 70, 'creativity', 'spiritual hunger');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 43, 'reconciliation', 'creativity');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 84, 'creativity', 'acceptance');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 10, 'trust', 'creativity');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 85, 'creativity', 'inspiration');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 59, 'communication', 'affection');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 11, 'affection', 'communication');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 54, 'kindness', 'affection');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 52, 'affection', 'practicality');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 6, 'spiritual hunger', 'affection');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 70, 'affection', 'reconciliation');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 4, 'acceptance', 'affection');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 30, 'affection', 'trust');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 37, 'inspiration', 'affection');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 87, 'communication', 'communication');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 38, 'kindness', 'communication');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 59, 'communication', 'practicality');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 48, 'spiritual hunger', 'communication');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 83, 'communication', 'reconciliation');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 77, 'acceptance', 'communication');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 21, 'communication', 'trust');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 91, 'inspiration', 'communication');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 38, 'communication', 'kindness');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 23, 'practicality', 'communication');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 16, 'communication', 'spiritual hunger');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 38, 'reconciliation', 'communication');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 71, 'communication', 'acceptance');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 99, 'trust', 'communication');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 94, 'communication', 'inspiration');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 70, 'practicality', 'kindness');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 88, 'kindness', 'spiritual hunger');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 34, 'reconciliation', 'kindness');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 24, 'kindness', 'acceptance');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 80, 'trust', 'kindness');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 84, 'kindness', 'inspiration');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 46, 'spiritual hunger', 'practicality');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 85, 'practicality', 'reconciliation');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 96, 'acceptance', 'practicality');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 11, 'practicality', 'trust');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 44, 'inspiration', 'practicality');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 5, 'spiritual hunger', 'reconciliation');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 22, 'acceptance', 'spiritual hunger');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 23, 'spiritual hunger', 'trust');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 20, 'inspiration', 'spiritual hunger');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 30, 'reconciliation', 'acceptance');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 74, 'trust', 'reconciliation');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 87, 'reconciliation', 'inspiration');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 1, 'trust', 'acceptance');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 60, 'acceptance', 'inspiration');
INSERT INTO `ValuesArray` (`UserID`, `ListOrder`, `Value1`, `Value2`) VALUES (15, 38, 'inspiration', 'trust');
# 120 records

