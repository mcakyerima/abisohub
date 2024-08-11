-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2024 at 11:41 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `abisodata`
--

-- --------------------------------------------------------

--
-- Table structure for table `airtime`
--

CREATE TABLE `airtime` (
  `aId` int(100) NOT NULL,
  `aNetwork` varchar(10) NOT NULL,
  `aBuyDiscount` float NOT NULL DEFAULT 96,
  `aUserDiscount` float NOT NULL,
  `aAgentDiscount` float NOT NULL,
  `aVendorDiscount` float NOT NULL,
  `aType` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `airtime`
--

INSERT INTO `airtime` (`aId`, `aNetwork`, `aBuyDiscount`, `aUserDiscount`, `aAgentDiscount`, `aVendorDiscount`, `aType`) VALUES
(1, '1', 97, 99, 98, 98, 'VTU'),
(2, '2', 97, 99, 98, 98, 'VTU'),
(3, '3', 97, 99, 98, 98, 'VTU'),
(4, '4', 97, 99, 98, 98, 'VTU'),
(5, '1', 96, 99, 98, 97, 'Share And Sell'),
(6, '2', 96, 99, 98, 97, 'Share And Sell'),
(7, '3', 96, 99, 98, 97, 'Share And Sell'),
(8, '4', 96, 99, 98, 97, 'Share And Sell');

-- --------------------------------------------------------

--
-- Table structure for table `airtimepinprice`
--

CREATE TABLE `airtimepinprice` (
  `aId` int(100) NOT NULL,
  `aNetwork` varchar(10) NOT NULL,
  `aUserDiscount` float NOT NULL,
  `aAgentDiscount` float NOT NULL,
  `aVendorDiscount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `airtimepinprice`
--

INSERT INTO `airtimepinprice` (`aId`, `aNetwork`, `aUserDiscount`, `aAgentDiscount`, `aVendorDiscount`) VALUES
(1, '1', 99, 98, 97),
(2, '2', 99, 98, 97),
(3, '3', 99, 98, 97),
(4, '4', 99, 98, 97);

-- --------------------------------------------------------

--
-- Table structure for table `alphatopupprice`
--

CREATE TABLE `alphatopupprice` (
  `alphaId` int(200) NOT NULL,
  `buyingPrice` int(100) NOT NULL,
  `sellingPrice` int(100) NOT NULL,
  `agent` int(100) NOT NULL,
  `vendor` int(100) NOT NULL,
  `dPosted` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `apiconfigs`
--

CREATE TABLE `apiconfigs` (
  `aId` int(200) NOT NULL,
  `name` varchar(30) NOT NULL,
  `value` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `apiconfigs`
--

INSERT INTO `apiconfigs` (`aId`, `name`, `value`) VALUES
(1, 'monifyCharges', '1.075'),
(2, 'monifyApi', 'MK_PROD_4JNNXBZGEJ'),
(3, 'monifySecrete', 'DG2P8BTAC5MLE8G5MUS1WJDT'),
(4, 'monifyContract', '4582551373'),
(5, 'monifyWeStatus', 'On'),
(6, 'monifyMoStatus', 'On'),
(7, 'monifyFeStatus', 'On'),
(8, 'monifySaStatus', 'On'),
(9, 'monifyStatus', 'On'),
(10, 'paystackCharges', '1.5'),
(11, 'paystackApi', ''),
(12, 'paystackStatus', 'Off'),
(13, 'mtnVtuKey', 'a87741c7edc71443230966cc8eac684003'),
(14, 'mtnVtuProvider', 'https://husmodataapi.com/api/topup/'),
(15, 'mtnSharesellKey', 'a87741c7edc71443230966cc8eac684003'),
(16, 'mtnSharesellProvider', 'https://husmodataapi.com/api/topup/'),
(17, 'airtelVtuKey', 'a87741c7edc71443230966cc8eac684003'),
(18, 'airtelVtuProvider', 'https://husmodataapi.com/api/topup/'),
(19, 'airtelSharesellKey', 'a87741c7edc71443230966cc8eac684003'),
(20, 'airtelSharesellProvider', 'https://husmodataapi.com/api/topup/'),
(21, 'gloVtuKey', 'a87741c7edc71443230966cc8eac684003'),
(22, 'gloVtuProvider', 'https://husmodataapi.com/api/topup/'),
(23, 'gloSharesellKey', 'a87741c7edc71443230966cc8eac684003'),
(24, 'gloSharesellProvider', 'https://husmodataapi.com/api/topup/'),
(25, '9mobileVtuKey', 'a87741c7edc71443230966cc8eac684003'),
(26, '9mobileVtuProvider', 'https://husmodataapi.com/api/topup/'),
(27, '9mobileSharesellKey', 'a87741c7edc71443230966cc8eac684003'),
(28, '9mobileSharesellProvider', 'https://husmodataapi.com/api/topup/'),
(29, 'mtnSmeApi', 'YWJpc29raGFsaWmFiaXNvMTEyMg='),
(30, 'mtnSmeProvider', 'https://n3tdata.com/api/data/'),
(31, 'mtnGiftingApi', 'live_3448776f6826416191abfeea15d8hyfwt57'),
(32, 'mtnGiftingProvider', 'https://autopilotng.com/api/data/'),
(33, 'mtnCorporateApi', 'YWJpc29raGFsmFiaXNvMTEyMg=='),
(34, 'mtnCorporateProvider', 'https://legitdataway.com/api/data/'),
(35, 'airtelSmeApi', '2a5fd5a41ca9aa1960d75c5dfb3981fe'),
(36, 'airtelSmeProvider', 'https://www.gladtidingsdata.com/api/data/'),
(37, 'airtelGiftingApi', '2a5fd5a41ca9aa1960d75c5dfb3981fe'),
(38, 'airtelGiftingProvider', 'https://www.gladtidingsdata.com/api/data/'),
(39, 'airtelCorporateApi', '2a5fd5a41ca9aa1960d75c5dfb3981fe'),
(40, 'airtelCorporateProvider', 'https://www.gladtidingsdata.com/api/data/'),
(41, 'gloSmeApi', '2a5fd5a41ca9aa1960d75c5dfb3981fe'),
(42, 'gloSmeProvider', 'https://www.gladtidingsdata.com/api/data/'),
(43, 'gloGiftingApi', '2a5fd5a41ca9aa1960d75c5dfb3981fe'),
(44, 'gloGiftingProvider', 'https://www.gladtidingsdata.com/api/data/'),
(45, 'gloCorporateApi', '2a5fd5a41ca9aa1960d75c5dfb3981fe'),
(46, 'gloCorporateProvider', 'https://www.gladtidingsdata.com/api/data/'),
(47, '9mobileSmeApi', '2a5fd5a41ca9aa1960d75c5dfb3981fe'),
(48, '9mobileSmeProvider', 'https://www.gladtidingsdata.com/api/data/'),
(49, '9mobileGiftingApi', '2a5fd5a41ca9aa1960d75c5dfb3981fe'),
(50, '9mobileGiftingProvider', 'https://www.gladtidingsdata.com/api/data/'),
(51, '9mobileCorporateApi', '2a5fd5a41ca9aa1960d75c5dfb3981fe'),
(52, '9mobileCorporateProvider', 'https://www.gladtidingsdata.com/api/data/'),
(53, 'cableVerificationApi', 'a2hhbGlmYWEudWjb206MmVnZ3MzZm91bHM='),
(54, 'cableVerificationProvider', 'https://api-service.vtpass.com/api/merchant-verify'),
(55, 'cableApi', 'a2hhbGlmYWEudWjb206MmVnZ3MzZm91bHM='),
(56, 'cableProvider', 'https://api-service.vtpass.com/api/pay'),
(57, 'meterVerificationApi', 'a2hhbGlmYWEudWjb206MmVnZ3MzZm91bHM='),
(58, 'meterVerificationProvider', 'https://api-service.vtpass.com/api/merchant-verify'),
(59, 'meterApi', 'a2hhbGlmYWEudmVnZ3MzZm91bHM='),
(60, 'meterProvider', 'https://api-service.vtpass.com/api/pay'),
(61, 'examApi', 'a2hhbGlmYWEudWjmVnZ3MzZm91bHM==='),
(62, 'examProvider', 'https://n3tdata.com/api/exam/'),
(63, 'rechargePinApi', 'v='),
(64, 'rechargePinProvider', 'x'),
(65, 'walletOneApi', 'YWJpc29raGFsFiaXNvMTEyMg=='),
(66, 'walletOneProvider', 'https://legitdataway.com/api/user/'),
(67, 'walletOneProviderName', 'Legitdataway'),
(68, 'walletTwoApi', 'QWJpc29raGFsiaXNvMTEyMg=='),
(69, 'walletTwoProvider', 'https://n3tdata.com/api/user/'),
(70, 'walletTwoProviderName', 'N3TDATA'),
(71, 'walletThreeApi', '2a5fd5a2c960d75c5dfb3981fe'),
(72, 'walletThreeProvider', 'https://www.gladtidingsdata.com/api/user/'),
(73, 'walletThreeProviderName', 'Gladtidings'),
(74, 'dataPinApi', 'QWJpc29raGFsaWZiaXNvMTEyMg=='),
(75, 'dataPinProvider', ''),
(76, 'alphaApi', ''),
(77, 'alphaProvider', ''),
(78, 'walletFourApi', ''),
(79, 'walletFourProvider', ''),
(80, 'walletFourProviderName', ''),
(81, 'walletFiveApi', ''),
(82, 'walletFiveProvider', ''),
(83, 'walletFiveProviderName', ''),
(84, 'walletSixApi', ''),
(85, 'walletSixProvider', ''),
(86, 'walletSixProviderName', ''),
(87, 'kudaEmail', ''),
(88, 'kudaApi', ''),
(89, 'kudaWebhookUser', ''),
(90, 'kudaWebhookPass', ''),
(91, 'kudaChargesType', 'flat'),
(92, 'kudaCharges', '10'),
(93, 'kudaStatus', 'Off'),
(94, 'monifyGtStatus', 'Off'),
(95, 'airtime2cashstatus', 'On'),
(96, 'airtime2cashmtnno', '080656018553'),
(97, 'airtime2cashmtnrate', '91'),
(98, 'airtime2cashairtelno', '09011111111'),
(99, 'airtime2cashairtelrate', '80'),
(100, 'airtime2cashglono', '09011111111'),
(101, 'airtime2cashglorate', '80'),
(102, 'airtime2cash9mobileno', '09011111111'),
(103, 'airtime2cash9mobilerate', '80'),
(104, 'mtnShareApi', 'live_3448776f682641ea15d8hyfwt57'),
(105, 'mtnShareProvider', 'https://autopilotng.com/api/data/'),
(106, 'airtelShareApi', NULL),
(107, 'airtelShareProvider', NULL),
(108, 'gloShareApi', NULL),
(109, 'gloShareProvider', NULL),
(110, '9mobileShareApi', NULL),
(111, '9mobileShareProvider', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `apilinks`
--

CREATE TABLE `apilinks` (
  `aId` int(200) NOT NULL,
  `name` varchar(30) NOT NULL,
  `value` varchar(100) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `apilinks`
--

INSERT INTO `apilinks` (`aId`, `name`, `value`, `type`) VALUES
(1, 'Abisohub', 'https://abisohub.com/api/user/', 'Wallet'),
(2, 'Abisohub', 'https://abisohub.com/api/airtime/', 'Airtime'),
(3, 'Abisohub', 'https://abisohub.com/api/data/', 'Data'),
(4, 'Abisohub', 'https://abisohub.com/api/cabletv/verify/', 'CableVer'),
(5, 'Abisohub', 'https://abisohub.com/api/cabletv/', 'Cable'),
(6, 'Abisohub', 'https://abisohub.com/api/electricity/verify/', 'ElectricityVer'),
(7, 'Abisohub', 'https://abisohub.com/api/electricity/', 'Electricity'),
(8, 'Abisohub', 'https://abisohub.com/api/exam/', 'Exam'),
(9, 'N3T Data', 'https://n3tdata.com/api/user/', 'Wallet'),
(10, 'N3T Data', 'https://n3tdata.com/api/topup/', 'Airtime'),
(11, 'N3T Data', 'https://n3tdata.com/api/data/', 'Data'),
(12, 'N3T Data', 'https://n3tdata.com/api/cable/cable-validation/', 'CableVer'),
(13, 'N3T Data', 'https://n3tdata.com/api/cable/', 'Cable'),
(14, 'N3T Data', 'https://n3tdata.com/api/bill/bill-validation/', 'ElectricityVer'),
(15, 'N3T Data', 'https://n3tdata.com/api/bill/', 'Electricity'),
(16, 'N3T Data', 'https://n3tdata.com/api/exam/', 'Exam'),
(17, 'Legitdataway', 'https://legitdataway.com/api/user/', 'Wallet'),
(18, 'Legitdataway', 'https://legitdataway.com/api/topup/', 'Airtime'),
(19, 'Legitdataway', 'https://legitdataway.com/api/data/', 'Data'),
(20, 'Legitdataway', 'https://legitdataway.com/api/cable/cable-validation', 'CableVer'),
(21, 'Legitdataway', 'https://legitdataway.com/api/cable/', 'Cable'),
(22, 'Legitdataway', 'https://legitdataway.com/api/bill/bill-validation/', 'ElectricityVer'),
(23, 'Legitdataway', 'https://legitdataway.com/api/bill/', 'Electricity'),
(24, 'Legitdataway', 'https://legitdataway.com/api/exam/', 'Exam'),
(25, 'Aabaxztech', 'https://aabaxztech.com/api/user/', 'Wallet'),
(26, 'Aabaxztech', 'https://aabaxztech.com/api/topup/', 'Airtime'),
(27, 'Aabaxztech', 'https://aabaxztech.com/api/data/', 'Data'),
(28, 'Aabaxztech', 'https://aabaxztech.com/api/validateiuc', 'CableVer'),
(29, 'Aabaxztech', 'https://aabaxztech.com/api/cablesub/', 'Cable'),
(30, 'Aabaxztech', 'https://aabaxztech.com/api/validatemeter', 'ElectricityVer'),
(31, 'Aabaxztech', 'https://aabaxztech.com/api/billpayment/', 'Electricity'),
(32, 'Aabaxztech', 'https://aabaxztech.com/api/epin/', 'Exam'),
(33, 'Maskawasub', 'https://maskawasub.com/api/user/', 'Wallet'),
(34, 'Maskawasub', 'https://maskawasub.com/api/topup/', 'Airtime'),
(35, 'Maskawasub', 'https://maskawasub.com/api/data/', 'Data'),
(36, 'Maskawasub', 'https://maskawasub.com/api/validateiuc', 'CableVer'),
(37, 'Maskawasub', 'https://maskawasub.com/api/cablesub/', 'Cable'),
(38, 'Maskawasub', 'https://maskawasub.com/api/validatemeter', 'ElectricityVer'),
(39, 'Maskawasub', 'https://maskawasub.com/api/billpayment/', 'Electricity'),
(40, 'Maskawasub', 'https://maskawasub.com/api/epin/', 'Exam'),
(41, 'Husmodataapi', 'https://husmodataapi.com/api/user/', 'Wallet'),
(42, 'Husmodataapi', 'https://husmodataapi.com/api/topup/', 'Airtime'),
(43, 'Husmodataapi', 'https://husmodataapi.com/api/data/', 'Data'),
(44, 'Opendatasub', 'https://opendatasub.com/ajax/validate_iuc/', 'CableVer'),
(45, 'Husmodataapi', 'https://husmodataapi.com/api/cablesub/', 'Cable'),
(46, 'Opendatasub', 'https://opendatasub.com/ajax/validate_meter_number/', 'ElectricityVer'),
(47, 'Husmodataapi', 'https://husmodataapi.com/api/billpayment/', 'Electricity'),
(48, 'Husmodataapi', 'https://husmodataapi.com/api/epin/', 'Exam'),
(49, 'Gladtidingsdata', 'https://www.gladtidingsdata.com/api/user/', 'Wallet'),
(50, 'Gladtidingsdata', 'https://www.gladtidingsdata.com/api/topup/', 'Airtime'),
(51, 'Gladtidingsdata', 'https://www.gladtidingsdata.com/api/data/', 'Data'),
(52, 'Gladtidingsdata', 'https://www.gladtidingsdata.com/ajax/validate_iuc/', 'CableVer'),
(53, 'Gladtidingsdata', 'https://gladtidingsdata.com/api/cablesub/', 'Cable'),
(54, 'Gladtidingsdata', 'https://www.gladtidingsdata.com/ajax/validate_meter_number/', 'ElectricityVer'),
(55, 'Gladtidingsdata', 'https://gladtidingsdata.com/api/billpayment/', 'Electricity'),
(56, 'Gladtidingsdata', 'https://www.gladtidingsdata.com/api/epin/', 'Exam'),
(57, 'Sabrdataapi', 'https://sabrdataapi.com/api/user/', 'Wallet'),
(58, 'Sabrdataapi', 'https://sabrdataapi.com/api/topup/', 'Airtime'),
(59, 'Sabrdataapi', 'https://sabrdataapi.com/api/data/', 'Data'),
(60, 'Sabrdataapi', 'https://sabrdataapi.com/ajax/validate_iuc', 'CableVer'),
(61, 'Sabrdataapi', 'https://sabrdataapi.com/api/cablesub/', 'Cable'),
(62, 'Sabrdataapi', 'https://sabrdataapi.com/api/validatemeter', 'ElectricityVer'),
(63, 'Sabrdataapi', 'https://sabrdataapi.com/api/billpayment/', 'Electricity'),
(64, 'Sabrdataapi', 'https://sabrdataapi.com/api/epin/', 'Exam'),
(65, 'N3tdata247', 'https://n3tdata247.com/api/user/', 'Wallet'),
(66, 'N3tdata247', 'https://n3tdata247.com/api/data_card/', 'Data Pin'),
(67, 'Beensade', 'https://beensadeprint.com/api/user/', 'Wallet'),
(68, 'Beensade', 'https://beensadeprint.com/api/data_card/', 'Data Pin'),
(69, 'VTPass', 'https://api-service.vtpass.com/api/merchant-verify', 'ElectricityVer'),
(70, 'VTPass', 'https://api-service.vtpass.com/api/pay', 'Electricity'),
(71, 'Autopilotng', 'https://autopilotng.com/api/data/', 'Data'),
(73, 'VTPass', 'https://api-service.vtpass.com/api/merchant-verify', 'CableVer'),
(74, 'VTPass', 'https://api-service.vtpass.com/api/pay', 'Cable');

-- --------------------------------------------------------

--
-- Table structure for table `cableid`
--

CREATE TABLE `cableid` (
  `cId` int(11) NOT NULL,
  `cableid` varchar(10) DEFAULT NULL,
  `provider` varchar(10) NOT NULL,
  `providerStatus` varchar(10) NOT NULL DEFAULT 'On'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cableid`
--

INSERT INTO `cableid` (`cId`, `cableid`, `provider`, `providerStatus`) VALUES
(1, '1', 'GOTV', 'On'),
(2, '2', 'DSTV', 'On'),
(3, '3', 'STARTIMES', 'On');

-- --------------------------------------------------------

--
-- Table structure for table `cableplans`
--

CREATE TABLE `cableplans` (
  `cpId` int(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `userprice` varchar(255) NOT NULL,
  `agentprice` varchar(255) NOT NULL,
  `vendorprice` varchar(255) NOT NULL,
  `planid` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `cableprovider` tinyint(10) NOT NULL,
  `day` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cableplans`
--

INSERT INTO `cableplans` (`cpId`, `name`, `price`, `userprice`, `agentprice`, `vendorprice`, `planid`, `type`, `cableprovider`, `day`) VALUES
(1, 'GOtv Max N7,200', '7200', '7200', '7200', '7200', 'gotv-max', NULL, 1, '30'),
(2, 'GOtv Jolli N4,850', '4850', '4850', '4850', '4850', 'gotv-jolli', NULL, 1, '30'),
(3, 'GOtv Jinja N3,300', '3300', '3300', '3300', '3300', 'gotv-jinja', NULL, 1, '30'),
(4, 'GOtv Smallie - monthly N1575', '1575', '1575', '1575', '1575', 'gotv-smallie', NULL, 1, '30'),
(5, 'GOtv Smallie - quarterly N4,175', '4175', '4175', '4175', '4175', 'gotv-smallie-3months', NULL, 1, '90'),
(6, 'GOtv Smallie - yearly N12,300', '12300', '12300', '12300', '12300', 'gotv-smallie-1year', NULL, 1, '365'),
(7, 'GOtv Supa - monthly N9,600', '9600', '9600', '9600', '9600', 'gotv-supa', NULL, 1, '30'),
(8, 'GOtv Supa Plus - monthly N15,700', '15700', '15700', '15700', '15700', 'gotv-supa-plus', NULL, 1, '30'),
(9, 'DStv Padi N3,600', '3600', '3600', '3600', '3600', 'dstv-padi', NULL, 2, '30'),
(10, 'DStv Yanga N5,100', '5100', '5100', '5100', '5100', 'dstv-yanga', NULL, 2, '30'),
(11, 'Dstv Confam N9,300', '9300', '9300', '9300', '9300', 'dstv-confam', NULL, 2, '30'),
(12, 'DStv  Compact N15,700', '15700', '15700', '15700', '15700', 'dstv79', NULL, 2, '30'),
(13, 'DStv Premium N37,000', '37000', '37000', '37000', '37000', 'dstv3', NULL, 2, '30'),
(14, 'DStv Asia N12,400', '12400', '12400', '12400', '12400', 'dstv6', NULL, 2, '30'),
(15, 'DStv Compact Plus N25,000', '25000', '25000', '25000', '25000', 'dstv7', NULL, 2, '30'),
(16, 'DStv Premium-French N57,500', '57500', '57500', '57500', '57500', 'dstv9', NULL, 2, '30'),
(17, 'DStv Premium-Asia N42,000', '42000', '42000', '42000', '42000', 'dstv10', NULL, 2, '30'),
(18, 'DStv Confam + ExtraView N14,300', '14300', '14300', '14300', '14300', 'confam-extra', NULL, 2, '30'),
(19, 'DStv Yanga + ExtraView N10,100', '10100', '10100', '10100', '10100', 'yanga-extra', NULL, 2, '30'),
(20, 'DStv Padi + ExtraView N8,600', '8600', '8600', '8600', '8600', 'padi-extra', NULL, 2, '30'),
(21, 'DStv Compact + Asia N28,100', '28100', '28100', '28100', '28100', 'com-asia', NULL, 2, '30'),
(22, 'DStv Compact + Extra View N20,700', '20700', '20700', '20700', '20700', 'dstv30', NULL, 2, '30'),
(23, 'Nova (Antenna) - 500 Naira', '500', '500', '500', '500', 'nova-weekly', NULL, 3, '7'),
(24, 'Nova (Dish) - 1700 Naira', '1700', '1700', '1700', '1700', 'nova', NULL, 3, '30'),
(25, 'Basic (Antenna) - 1100 Naira', '1100', '1100', '1100', '1100', 'basic-weekly', NULL, 3, '7'),
(26, 'Basic (Antenna) - 3,300 Naira', '3300', '3300', '3300', '3300', 'basic', NULL, 3, '30'),
(27, 'Smart (Dish) - 1,400 Naira', '1400', '1400', '1400', '1400', 'smart-weekly', NULL, 3, '7'),
(28, 'Smart (Dish) - 4,200 Naira', '4200', '4200', '4200', '4200', 'smart', NULL, 3, '30'),
(29, 'Classic (Antenna) - 1700 Naira', '1700', '1700', '1700', '1700', 'classic-weekly', NULL, 3, '7'),
(30, 'Classic (Antenna) - 5,000 Naira', '5000', '5000', '5000', '5000', 'classic', NULL, 3, '30'),
(31, 'Super (Dish) - 2,800 Naira', '2800', '2800', '2800', '2800', 'super-weekly', NULL, 3, '7'),
(32, 'Super (Dish) - 8,200 Naira', '8200', '8200', '8200', '8200', 'super', NULL, 3, '30');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `msgId` int(200) NOT NULL,
  `sId` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` varchar(200) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `dPosted` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`msgId`, `sId`, `name`, `contact`, `subject`, `message`, `dPosted`) VALUES
(1, 0, 'Topupmate', 'ibyusuf31@gmail.com', 'Testing', 'Test', '2022-06-21 17:06:56'),
(2, 0, 'Ibrahim Ahmed', 'ibyusuf31@gmail.com', 'Test From Landing Page', 'Test From Landing Page', '2022-06-23 13:08:11'),
(3, 0, 'Abisodata ', 'umar.sh.aliyu@gmail.com', 'Connect ', 'Abisodata ', '2023-02-23 12:48:26');

-- --------------------------------------------------------

--
-- Table structure for table `datapins`
--

CREATE TABLE `datapins` (
  `dpId` int(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `userprice` varchar(255) NOT NULL,
  `agentprice` varchar(255) NOT NULL,
  `vendorprice` varchar(255) NOT NULL,
  `planid` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `datanetwork` tinyint(10) NOT NULL,
  `day` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `datapins`
--

INSERT INTO `datapins` (`dpId`, `name`, `price`, `userprice`, `agentprice`, `vendorprice`, `planid`, `type`, `datanetwork`, `day`) VALUES
(1, '1.5GB', '200', '300', '300', '300', '1', 'Gifting', 1, '30'),
(2, '500 MB', '108', '120', '120', '120', '2', 'SME', 1, '30'),
(3, '1GB', '215', '220', '220', '220', '3', 'SME', 1, '30'),
(4, '2GB', '430', '450', '450', '450', '4', 'SME', 1, '30'),
(5, '3GB', '645', '650', '650', '650', '5', 'SME', 1, '30'),
(6, '5GB', '1075', '1090', '1090', '1090', '6', 'SME', 1, '30'),
(7, '10GB', '2150', '2200', '2200', '2200', '7', 'SME', 1, '30'),
(8, '500 MB', '120', '135', '135', '130', '80', 'Corporate', 2, '30'),
(9, '1GB', '200', '220', '220', '220', '9', 'Corporate', 2, '30'),
(10, '2GB', '400', '420', '420', '420', '10', 'Corporate', 2, '30'),
(11, '5GB', '1000', '1200', '1200', '1200', '11', 'Corporate', 2, '30'),
(12, '10GB', '2000', '2200', '2200', '2200', '12', 'Corporate', 2, '30'),
(13, '500 MB', '120', '135', '135', '130', '8', 'Corporate', 4, '30');

-- --------------------------------------------------------

--
-- Table structure for table `dataplans`
--

CREATE TABLE `dataplans` (
  `pId` int(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `userprice` varchar(255) NOT NULL,
  `agentprice` varchar(255) NOT NULL,
  `vendorprice` varchar(255) NOT NULL,
  `planid` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `datanetwork` tinyint(10) NOT NULL,
  `day` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `dataplans`
--

INSERT INTO `dataplans` (`pId`, `name`, `price`, `userprice`, `agentprice`, `vendorprice`, `planid`, `type`, `datanetwork`, `day`) VALUES
(23, '100MB', '21', '30', '60', '35', '264', 'Corporate', 4, '30'),
(24, '300MB', '60', '120', '120', '70', '265', 'Corporate', 4, '30'),
(25, '500MB', '99', '140', '140', '105', '266', 'Corporate', 4, '30'),
(26, '1GB', '198', '250', '220', '210', '267', 'Corporate', 4, '30'),
(27, '2GB', '396', '500', '440', '420', '268', 'Corporate', 4, '30'),
(28, '5GB', '990', '1550', '1550', '1050', '269', 'Corporate', 4, '30'),
(29, '10GB', '1980', '3100', '3100', '2100', '273', 'Corporate', 4, '30'),
(37, '1.8GB = 800MB(DAY) + 1GB(NIGHT)', '470', '470', '470', '470', '37', 'Gifting', 2, '30'),
(38, '3.9GB = 1.9GB(DAY) + 2GB(NIGHT)', '920', '950', '950', '920', '13', 'Gifting', 2, '30'),
(39, '7.5GB = 3.5GB(DAY) + 4GB(NIGHT)', '1380', '1550', '1550', '1380', '180', 'Gifting', 2, '30'),
(40, '9.2GB = 5.2GB(DAY) + 4GB(NIGHT)', '1840', '1950', '1950', '1840', '14', 'Gifting', 2, '30'),
(41, '10.8GB = 6.8GB(DAY) + 4GB(NIGHT)', '2300', '2750', '2750', '2300', '15', 'Gifting', 2, '30'),
(42, '14GB = 10GB(DAY) + 4GB(NIGHT)', '2760', '3250', '3250', '2760', '16', 'Gifting', 2, '30'),
(45, '18GB=14GB(DAY) + 4GB(NIGHT)', '3680', '4350', '4350', '3680', '18', 'Gifting', 2, '30'),
(46, '24GB=20GB(DAY) + 4GB(NIGHT)', '4600', '5350', '5350', '4600', '65', 'Gifting', 2, '30'),
(47, '29.5GB=27.5GB(DAY) + 2GB(NIGHT)', '7360', '8350', '8350', '7360', '218', 'Gifting', 2, '30'),
(48, '50GB=46GB(DAY) + 4GB(NIGHT)', '9200', '10350', '10350', '9200', '219', 'Gifting', 2, '30'),
(50, '750MB', '468', '480', '480', '468', '144', 'Gifting', 4, '14'),
(51, '1.5GB', '9700000', '970', '970', '97000000', '145', 'Gifting', 4, '30'),
(52, '2GB', '1164', '1200', '1160', '1164', '146', 'Gifting', 4, '30'),
(53, '3GB', '1455', '1500', '1450', '1455', '147', 'Gifting', 4, '30'),
(54, '4.5GB', '1940', '2000', '1880', '1940', '148', 'Gifting', 4, '30'),
(55, '6GB', '2425', '2500', '2350', '2425', '149', 'Gifting', 4, '30'),
(56, '10GB', '2910', '3050', '2800', '2910', '150', 'Gifting', 4, '30'),
(57, '11GB', '3880', '3980', '3880', '3880', '163', 'Gifting', 4, '30'),
(59, '40GB', '9700', '9900', '9600', '9700', '209', 'Gifting', 4, '30'),
(60, '75GB', '14550', '15200', '14400', '14550', '255', 'Gifting', 4, '30'),
(61, '110GB', '19400', '20200', '19200', '19400', '253', 'Gifting', 4, '30'),
(62, '500MB', '410', '430', '430', '210', '182', 'Gifting', 3, '30'),
(63, '1.5GB', '820', '840', '840', '820', '183', 'Gifting', 3, '30'),
(64, '2GB', '984', '1000', '1000', '984', '184', 'Gifting', 3, '30'),
(65, '3GB', '1230', '1400', '1400', '1230', '185', 'Gifting', 3, '30'),
(66, '4.5GB', '1640', '1700', '1700', '1640', '186', 'Gifting', 3, '30'),
(67, '11GB', '3280', '3400', '3400', '3280', '187', 'Gifting', 3, '30'),
(68, '15GB', '4100', '4300', '4300', '4100', '188', 'Gifting', 3, '30'),
(69, '40GB', '8200', '8500', '8500', '8200', '189', 'Gifting', 3, '30'),
(70, '75GB', '12300', '13100', '13100', '12300', '190', 'Gifting', 3, '30'),
(71, '1GB', '240', '270', '250', '315', '298', 'SME', 3, '30'),
(72, '2GB', '480', '540', '500', '630', '299', 'SME', 3, '30'),
(73, '3GB', '720', '810', '750', '945', '303', 'SME', 3, '30'),
(74, '5GB', '1200', '1350', '1050', '1575', '304', 'SME', 3, '30'),
(75, '10GB', '2400', '2700', '2000', '3150', '305', 'SME', 3, '30'),
(78, '40MB', '48', '60', '60', '48', '297', 'Gifting', 1, '1'),
(79, '100MB', '95', '105', '226', '95', '226', 'Gifting', 1, '1'),
(80, '200MB', '209', '220', '205', '209', '229', 'Gifting', 1, '2'),
(81, '350MB', '285', '300', '300', '285', '230', 'Gifting', 1, '7'),
(82, '1GB', '285', '300', '300', '285', '291', 'Gifting', 1, '1'),
(83, '2.5GB', '470', '490', '490', '470', '294', 'Gifting', 1, '2'),
(84, '2GB', '940', '500', '500', '940', '293', 'Gifting', 1, '7'),
(85, '750MB', '470', '500', '500', '470', '232', 'Gifting', 1, '3'),
(86, '1GB', '470', '500', '500', '470', '233', 'Gifting', 1, '7'),
(87, '1.5GB', '940', '970', '970', '940', '221', 'Gifting', 1, '30'),
(89, '2GB', '1140', '1200', '1145', '1140', '222', 'Gifting', 1, '30'),
(90, '6GB', '1410', '1500', '1450', '1410', '231', 'Gifting', 1, '7'),
(92, '3GB', '1410', '1500', '1500', '1410', '295', 'Gifting', 1, '30'),
(93, '4.5GB', '1880', '2050', '2000', '1880', '223', 'Gifting', 1, '30'),
(94, '10GB', '2820', '3000', '3000', '2820', '256', 'Gifting', 1, '30'),
(95, '20GB', '4700', '4900', '4900', '4700', '279', 'Gifting', 1, '30'),
(96, '25GB', '5640', '5900', '5900', '5640', '314', 'Gifting', 1, '30'),
(97, '75GB', '14100', '17500', '17500', '14100', '280', 'Gifting', 1, '30'),
(98, '120GB', '18800', '19500', '19500', '18800', '315', 'Gifting', 1, '30'),
(99, '50MB', '35', '70', '70', '34', '43', 'Corporate', 1, '30'),
(100, '150MB', '65', '70', '70', '65', '271', 'Corporate', 1, '30'),
(101, '250MB', '85', '100', '100', '85', '272', 'Corporate', 1, '30'),
(102, '500MB', '115', '130', '130', '115', '225', 'Corporate', 1, '30'),
(103, '1GB', '223', '235', '235', '225', '213', 'Corporate', 1, '30'),
(104, '2GB', '446', '470', '470', '450', '215', 'Corporate', 1, '30'),
(105, '3GB', '669', '705', '705', '675', '216', 'Corporate', 1, '30'),
(106, '5GB', '1115', '1175', '1175', '1125', '217', 'Corporate', 1, '30'),
(107, '10GB', '2230', '2350', '2350', '2250', '257', 'Corporate', 1, '30'),
(108, '15GB', '3345', '3525', '3525', '3375', '259', 'Corporate', 1, '30'),
(109, '20GB', '4460', '4700', '4700', '4500', '258', 'Corporate', 1, '30'),
(111, '500MB', '108', '117', '117', '110', '36', 'SME', 1, '30'),
(112, '1GB', '216', '225', '224', '219', '166', 'SME', 1, '30'),
(113, '2GB', '432', '450', '448', '438', '167', 'SME', 1, '30'),
(114, '3GB', '648', '675', '672', '657', '168', 'SME', 1, '30'),
(115, '5GB', '1080', '1125', '1120', '1095', '169', 'SME', 1, '30'),
(116, '10GB', '2165', '2250', '2240', '2190', '260', 'SME', 1, '30'),
(117, '200MB', '47', '90', '90', '60', '333', 'Corporate', 2, '30'),
(118, '500MB', '107', '135', '135', '120', '331', 'Corporate', 2, '30'),
(119, '1GB', '213', '240', '240', '227', '334', 'Corporate', 2, '30'),
(120, '2GB', '426', '480', '480', '454', '332', 'Corporate', 2, '30'),
(121, '3GB', '639', '720', '720', '681', '336', 'Corporate', 2, '30'),
(122, '5GB', '1065', '1200', '1200', '1135', '329', 'Corporate', 2, '30'),
(123, '10GB', '2130', '2400', '2400', '2270', '335', 'Corporate', 2, '30'),
(124, '1GB', '95', '220', '220', '220', 'MTN_DT_500MB', 'Share', 1, '30');

-- --------------------------------------------------------

--
-- Table structure for table `datatokens`
--

CREATE TABLE `datatokens` (
  `tId` int(100) NOT NULL,
  `sId` int(100) NOT NULL,
  `tRef` varchar(255) NOT NULL,
  `business` varchar(30) NOT NULL,
  `network` varchar(30) NOT NULL,
  `datasize` varchar(30) NOT NULL,
  `quantity` int(100) NOT NULL,
  `serial` text NOT NULL,
  `tokens` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `loadpin` varchar(30) DEFAULT NULL,
  `checkbalance` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `datatokens`
--

INSERT INTO `datatokens` (`tId`, `sId`, `tRef`, `business`, `network`, `datasize`, `quantity`, `serial`, `tokens`, `date`, `loadpin`, `checkbalance`) VALUES
(1, 2, '24661679138472', 'Abisodata', 'AIRTEL', '500 MB', 1, 'SN90418687A', '6844090257', '2023-03-18 07:23:06', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `electricityid`
--

CREATE TABLE `electricityid` (
  `eId` int(11) NOT NULL,
  `electricityid` varchar(50) DEFAULT NULL,
  `provider` varchar(50) NOT NULL,
  `abbreviation` varchar(5) NOT NULL,
  `providerStatus` varchar(10) NOT NULL DEFAULT 'On'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `electricityid`
--

INSERT INTO `electricityid` (`eId`, `electricityid`, `provider`, `abbreviation`, `providerStatus`) VALUES
(1, 'ikeja-electric', 'Ikeja Electric', 'IE', 'On'),
(2, 'eko-electric', 'Eko Electric', 'EKEDC', 'On'),
(3, 'kano-electric', 'Kano Electric', 'KEDCO', 'On'),
(4, 'portharcourt-electric', 'Port Harcourt Electric', 'PHEDC', 'On'),
(5, 'jos-electric', 'Jos Electric', 'JED', 'On'),
(6, 'ibadan-electric', 'Ibadan Electric', 'IBEDC', 'On'),
(7, 'kaduna-electric', 'Kaduna Electric', 'KEDC', 'On'),
(8, 'abuja-electric', 'Abuja Electric', 'AEDC', 'On'),
(9, 'enugu-electric', 'Enugu Electric', 'ENUGU', 'On'),
(10, 'benin-electric', 'Benin Electric', 'BENIN', 'On'),
(11, 'yola-electric', 'Yola Electric', 'YOLA', 'On');

-- --------------------------------------------------------

--
-- Table structure for table `examid`
--

CREATE TABLE `examid` (
  `eId` int(11) NOT NULL,
  `examid` varchar(10) DEFAULT NULL,
  `provider` varchar(50) NOT NULL,
  `price` int(20) NOT NULL DEFAULT 0,
  `buying_price` int(20) NOT NULL DEFAULT 0,
  `providerStatus` varchar(10) NOT NULL DEFAULT 'On'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `examid`
--

INSERT INTO `examid` (`eId`, `examid`, `provider`, `price`, `buying_price`, `providerStatus`) VALUES
(1, '1', 'WAEC', 1800, 0, 'On'),
(2, '2', 'NECO', 800, 0, 'On'),
(3, '3', 'NABTEB', 950, 0, 'On');

-- --------------------------------------------------------

--
-- Table structure for table `networkid`
--

CREATE TABLE `networkid` (
  `nId` int(11) NOT NULL,
  `networkid` varchar(10) NOT NULL,
  `smeId` varchar(10) NOT NULL,
  `giftingId` varchar(10) NOT NULL,
  `corporateId` varchar(10) NOT NULL,
  `shareId` varchar(10) NOT NULL,
  `vtuId` varchar(10) NOT NULL,
  `sharesellId` varchar(10) NOT NULL,
  `network` varchar(20) NOT NULL,
  `networkStatus` varchar(10) NOT NULL DEFAULT 'Off',
  `vtuStatus` varchar(10) NOT NULL DEFAULT 'Off',
  `sharesellStatus` varchar(10) NOT NULL DEFAULT 'Off',
  `airtimepinStatus` varchar(10) NOT NULL DEFAULT 'Off',
  `smeStatus` varchar(10) NOT NULL DEFAULT 'Off',
  `giftingStatus` varchar(10) NOT NULL DEFAULT 'Off',
  `corporateStatus` varchar(10) NOT NULL DEFAULT 'Off',
  `shareStatus` varchar(10) NOT NULL DEFAULT 'Off',
  `datapinStatus` varchar(10) NOT NULL DEFAULT 'Off',
  `manualOrderStatus` varchar(10) NOT NULL DEFAULT 'Off',
  `momoStatus` varchar(10) NOT NULL DEFAULT 'Off'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `networkid`
--

INSERT INTO `networkid` (`nId`, `networkid`, `smeId`, `giftingId`, `corporateId`, `shareId`, `vtuId`, `sharesellId`, `network`, `networkStatus`, `vtuStatus`, `sharesellStatus`, `airtimepinStatus`, `smeStatus`, `giftingStatus`, `corporateStatus`, `shareStatus`, `datapinStatus`, `manualOrderStatus`, `momoStatus`) VALUES
(1, '1', '1', '1', '1', '1', '1', '1', 'MTN', 'On', 'On', 'Off', 'Off', 'On', 'On', 'On', 'On', 'On', 'Off', 'Off'),
(2, '2', '2', '2', '2', '2', '2', '2', 'GLO', 'On', 'On', 'Off', 'Off', 'Off', 'On', 'On', 'Off', 'On', 'Off', 'Off'),
(3, '6', '6', '6', '6', '3', '3', '3', '9MOBILE', 'On', 'On', 'Off', 'Off', 'On', 'On', 'Off', 'Off', 'Off', 'Off', 'Off'),
(4, '2', '3', '3', '3', '4', '4', '4', 'AIRTEL', 'On', 'On', 'Off', 'Off', 'Off', 'On', 'On', 'Off', 'On', 'Off', 'Off');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `msgId` int(200) NOT NULL,
  `msgfor` tinyint(4) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 0,
  `dPosted` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`msgId`, `msgfor`, `subject`, `message`, `status`, `dPosted`) VALUES
(1, 3, 'Welcome Message', 'Hi There! You are welcome, we are your one-stop platform for all for bills payment, airtime, data plans, and cable tv subscription. All our services are available to you at a discount rate. Our customer support team is available to you 24/7.', 0, '2022-06-21 17:05:02');

-- --------------------------------------------------------

--
-- Table structure for table `sitesettings`
--

CREATE TABLE `sitesettings` (
  `sId` int(200) NOT NULL,
  `sitename` varchar(20) DEFAULT NULL,
  `siteurl` varchar(100) DEFAULT NULL,
  `agentupgrade` varchar(20) DEFAULT NULL,
  `vendorupgrade` varchar(20) DEFAULT NULL,
  `apidocumentation` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `whatsappgroup` varchar(100) DEFAULT NULL,
  `facebook` varchar(10) DEFAULT NULL,
  `twitter` varchar(200) DEFAULT NULL,
  `instagram` varchar(200) DEFAULT NULL,
  `telegram` varchar(100) DEFAULT NULL,
  `referalupgradebonus` float NOT NULL DEFAULT 100,
  `referalairtimebonus` float NOT NULL DEFAULT 1,
  `referaldatabonus` float NOT NULL DEFAULT 1,
  `referalwalletbonus` float NOT NULL DEFAULT 1,
  `referalcablebonus` float NOT NULL DEFAULT 1,
  `referalexambonus` float NOT NULL DEFAULT 1,
  `referalmeterbonus` float NOT NULL DEFAULT 1,
  `wallettowalletcharges` float NOT NULL DEFAULT 50,
  `sitecolor` varchar(10) NOT NULL DEFAULT '#0000e6',
  `logindesign` varchar(10) NOT NULL DEFAULT '5',
  `homedesign` varchar(10) NOT NULL DEFAULT '5',
  `notificationStatus` varchar(5) NOT NULL DEFAULT 'Off',
  `accountname` varchar(50) DEFAULT NULL,
  `accountno` varchar(15) DEFAULT NULL,
  `bankname` varchar(20) DEFAULT NULL,
  `electricitycharges` varchar(5) DEFAULT NULL,
  `airtimemin` varchar(10) NOT NULL DEFAULT '50',
  `airtimemax` varchar(10) NOT NULL DEFAULT '500'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sitesettings`
--

INSERT INTO `sitesettings` (`sId`, `sitename`, `siteurl`, `agentupgrade`, `vendorupgrade`, `apidocumentation`, `phone`, `email`, `whatsapp`, `whatsappgroup`, `facebook`, `twitter`, `instagram`, `telegram`, `referalupgradebonus`, `referalairtimebonus`, `referaldatabonus`, `referalwalletbonus`, `referalcablebonus`, `referalexambonus`, `referalmeterbonus`, `wallettowalletcharges`, `sitecolor`, `logindesign`, `homedesign`, `notificationStatus`, `accountname`, `accountno`, `bankname`, `electricitycharges`, `airtimemin`, `airtimemax`) VALUES
(1, 'Abisodata', 'https://vtu.abisodata.com', '1000', '2000', 'https://vtu.abisodata.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 100, 0, 0, 0, 0, 0, 0, 0, '#0c0c64', '5', '5', 'Off', 'Umar Aliyu Shehu', '2020686714', 'KUDA Bank', '0', '100', '5000');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `sId` int(200) NOT NULL,
  `sApiKey` varchar(200) NOT NULL,
  `sFname` varchar(50) NOT NULL,
  `sLname` varchar(50) NOT NULL,
  `sEmail` varchar(50) DEFAULT NULL,
  `sPhone` varchar(20) NOT NULL,
  `sPass` varchar(150) NOT NULL,
  `sState` varchar(50) NOT NULL,
  `sPin` int(10) NOT NULL DEFAULT 1234,
  `sPinStatus` tinyint(3) DEFAULT 0,
  `sType` tinyint(10) NOT NULL DEFAULT 1,
  `sWallet` float NOT NULL DEFAULT 0,
  `sRefWallet` float NOT NULL DEFAULT 0,
  `sBankNo` varchar(20) DEFAULT NULL,
  `sRolexBank` varchar(20) DEFAULT NULL,
  `sSterlingBank` varchar(20) DEFAULT NULL,
  `sFidelityBank` varchar(20) DEFAULT NULL,
  `sBankName` varchar(30) DEFAULT NULL,
  `sRegStatus` tinyint(5) NOT NULL DEFAULT 3,
  `sVerCode` smallint(20) NOT NULL DEFAULT 0,
  `sRegDate` datetime NOT NULL DEFAULT current_timestamp(),
  `sLastActivity` datetime DEFAULT NULL,
  `sReferal` varchar(15) DEFAULT NULL,
  `sAccountref` varchar(20) DEFAULT NULL,
  `sVerified` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`sId`, `sApiKey`, `sFname`, `sLname`, `sEmail`, `sPhone`, `sPass`, `sState`, `sPin`, `sPinStatus`, `sType`, `sWallet`, `sRefWallet`, `sBankNo`, `sRolexBank`, `sSterlingBank`, `sFidelityBank`, `sBankName`, `sRegStatus`, `sVerCode`, `sRegDate`, `sLastActivity`, `sReferal`, `sAccountref`, `sVerified`) VALUES
(2, 'yg35i26JCCm3BAvt0142kcAoBxarwdAdhsAIFC3nb6bEA7xcqACCC7GAflp21676193928', 'Umar Aliyu', 'Shehu', 'khalifaa.umar@gmail.com', '08065601853', '11fc98bb7b', 'Borno', 1122, 1, 3, 0, 0, NULL, NULL, NULL, NULL, NULL, 0, 3414, '2023-02-12 04:25:28', '2024-05-01 11:35:00', '', NULL, NULL),
(3, 'CA1l0nco9AC2d9rhtIC64GCfB3iAxdBCADx38HCxkA13s78BCwg2bexAyp4b1677172304', 'Ineye', 'Mark-balm', 'ineyemark001@gmail.com', '08101398211', '985a2b4cd7', 'Rivers', 1234, 0, 3, 97, 0, NULL, NULL, NULL, NULL, NULL, 0, 3125, '2023-02-23 12:11:44', '2023-02-23 18:16:39', '', NULL, NULL),
(4, '1BwFrCoDemtC4BBk6f3db39CcG8zAa5AJ1CHx7CBA5A6xA2lhiCCC38dCsqA1678046880', 'Ibrahim', 'Mohammed Jajere', 'kingjajere@gmail.com', '08088721114', 'ad93b16103', 'Borno', 8806, 0, 3, 0, 0, NULL, NULL, NULL, NULL, NULL, 0, 1509, '2023-03-05 15:08:00', '2023-10-04 09:39:29', '', NULL, NULL),
(7, 'BCAkBBC6lAGyAC78o6BbJ25Aa7AsdeCIF3Adx91xccCExw2mbqCA542ChCf01680182668', 'Musa Usman ', 'Mustapha', 'mustyelmore1@gmail.com', '08033396164', 'adb94dd92c', 'Borno', 1122, 0, 3, 210.18, 0, NULL, NULL, NULL, NULL, NULL, 0, 7981, '2023-03-30 09:24:28', '2023-07-16 13:23:51', '', NULL, NULL),
(9, 'CBk2pGA5C3C84I7ba1A0rbxfyg3A2Bz2FtsiBcq89B5AedCv1nAH34E6l9CD1714559764', 'Umar', 'Shehu', 'aka@gmail.com', '08022248006', '11fc98bb7b', 'Borno', 1122, 0, 3, 5795, 0, '9769436039', '', '9056077029', '', 'Wema bank', 0, 5031, '2024-05-01 11:36:04', '2024-07-14 13:32:28', '', '66374efbdb1745340', 'aaa'),
(10, 'A2B4ht4gczB936w98AHyA7FBAAB52f3C3CC2dCJAGCCaxo0sqbBv3re1pCn51714561249', 'Umar', 'Shehu', 'asdulsa@gmail.com', '08065601852', '11fc98bb7b', 'Borno', 1234, 1, 1, 229, 0, NULL, NULL, NULL, NULL, NULL, 0, 7211, '2024-05-01 12:00:49', '2024-05-02 16:32:28', '', NULL, NULL),
(11, 'd5A1b3Cq6B2rIg7CBCCxvAACE4ACCAhc1A7x52mwAt0x9cfCBanlD3JCd62x1714725847', 'Umar', 'Shehu', 'admin@gmail.com', '08031230236', '11fc98bb7b', 'Borno', 1122, 0, 1, 0, 0, '9735721697', '', '9985589631', '', 'Wema bank', 0, 3721, '2024-05-03 09:44:07', '2024-05-03 16:15:18', '', '6634a783960266109', 'MjIyNjU2NTg5OTA=');

-- --------------------------------------------------------

--
-- Table structure for table `sysusers`
--

CREATE TABLE `sysusers` (
  `sysId` int(100) NOT NULL,
  `sysName` varchar(50) NOT NULL,
  `sysRole` tinyint(2) NOT NULL,
  `sysUsername` varchar(20) NOT NULL,
  `sysToken` varchar(30) NOT NULL,
  `sysStatus` tinyint(2) NOT NULL DEFAULT 0,
  `sysPinToken` varchar(30) NOT NULL DEFAULT '03d258c7ef',
  `sysPinStatus` tinyint(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sysusers`
--

INSERT INTO `sysusers` (`sysId`, `sysName`, `sysRole`, `sysUsername`, `sysToken`, `sysStatus`, `sysPinToken`, `sysPinStatus`) VALUES
(1, 'Abisodata', 1, 'admin', '1122', 0, '03d258c7ef', 0);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `tId` int(200) NOT NULL,
  `sId` int(100) NOT NULL,
  `transref` varchar(255) NOT NULL,
  `servicename` varchar(100) NOT NULL,
  `servicedesc` varchar(255) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `status` tinyint(5) NOT NULL,
  `oldbal` varchar(100) NOT NULL,
  `newbal` varchar(100) NOT NULL,
  `profit` float NOT NULL DEFAULT 0,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `api_response_log` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userlogin`
--

CREATE TABLE `userlogin` (
  `id` int(200) NOT NULL,
  `user` int(100) NOT NULL,
  `token` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `userlogin`
--

INSERT INTO `userlogin` (`id`, `user`, `token`) VALUES
(1, 2, '1714559242BoqrDvCJEA761'),
(2, 9, '1714559764xIzoFlwJvD672'),
(3, 10, '1714561249qvwGDlrszB171'),
(4, 10, '1714561307DsAozmJCtp578'),
(5, 10, '1714565879DzxJpsywHq883'),
(6, 10, '1714569248CrDHpGnmst205'),
(7, 10, '1714636024IpDBAzGrHm733'),
(8, 10, '1714640032nCzxJGtEHl906'),
(9, 10, '1714643003vysHIqAJom163'),
(10, 10, '1714643842EtkyAInGJl667'),
(11, 10, '1714648526vknlCIpwED924'),
(12, 10, '1714656895qEBmtAolrI271'),
(13, 11, '1714725848GCBHnyzlsw728'),
(14, 11, '1714728611AzyokxFCpE227'),
(15, 11, '1714728781nxIoGkzptw153'),
(16, 11, '1714739382xrqskyAFlI906'),
(17, 11, '1714741826qIpmlknHCE988'),
(18, 11, '1714748580DyHmpAGklt536'),
(19, 9, '1714900722CmylxrzkJI580'),
(20, 9, '1714909458yFqpCDrtxl849'),
(21, 9, '1714909625loHtnDzyxE932'),
(22, 9, '1714909628BzpyGxnoIw412'),
(23, 9, '1714911878JsDxErBGvz901'),
(24, 9, '1714915440kJDtwrnpGm228'),
(25, 9, '1714917741vJxDIqEszo489'),
(26, 9, '1714920683HnkGoACBlJ233'),
(27, 9, '1714995797otFBDsGnEz455'),
(28, 9, '1714995932zGBmywEDAo674'),
(29, 9, '1714996005nyHFEsqpwo685'),
(30, 9, '1714996135JvynqAImCx702'),
(31, 9, '1717415493pvAsEnBFCl634'),
(32, 9, '1717503061towGFIqHsl916'),
(33, 9, '1717576751oAtkICsxqp825'),
(34, 9, '1717751237BDAozkpCrq840'),
(35, 9, '1717755648JDmnCBwktA165'),
(36, 9, '1717764767yDpFlqICHm102'),
(37, 9, '1718798867GmxkClrAEB338'),
(38, 9, '1719499264pGCsoqyxBr603'),
(39, 9, '1719500273wJslrpoqvz761'),
(40, 9, '1720688539vyICGqnlDE516'),
(41, 9, '1720698098skHArmzBqy611'),
(42, 9, '1720703641woFHDmxrBl431'),
(43, 9, '1720825451vtlEFJnmqH635'),
(44, 9, '1720873388IBJsAtHElG946'),
(45, 9, '1720954587kCqErzvGyA903');

-- --------------------------------------------------------

--
-- Table structure for table `uservisits`
--

CREATE TABLE `uservisits` (
  `id` int(200) NOT NULL,
  `user` int(100) NOT NULL,
  `state` varchar(10) NOT NULL,
  `visitTime` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `uservisits`
--

INSERT INTO `uservisits` (`id`, `user`, `state`, `visitTime`) VALUES
(1, 9, 'Borno', '1714559769'),
(2, 10, 'Borno', '1714561254'),
(3, 10, 'Borno', '1714561312'),
(4, 11, 'Borno', '1714728616'),
(5, 11, 'Borno', '1714728786'),
(6, 9, 'Borno', '1717415500'),
(7, 9, 'Borno', '1720688545'),
(8, 9, 'Borno', '1720703647');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `airtime`
--
ALTER TABLE `airtime`
  ADD PRIMARY KEY (`aId`);

--
-- Indexes for table `airtimepinprice`
--
ALTER TABLE `airtimepinprice`
  ADD PRIMARY KEY (`aId`);

--
-- Indexes for table `alphatopupprice`
--
ALTER TABLE `alphatopupprice`
  ADD PRIMARY KEY (`alphaId`);

--
-- Indexes for table `apiconfigs`
--
ALTER TABLE `apiconfigs`
  ADD PRIMARY KEY (`aId`);

--
-- Indexes for table `apilinks`
--
ALTER TABLE `apilinks`
  ADD PRIMARY KEY (`aId`);

--
-- Indexes for table `cableid`
--
ALTER TABLE `cableid`
  ADD PRIMARY KEY (`cId`);

--
-- Indexes for table `cableplans`
--
ALTER TABLE `cableplans`
  ADD PRIMARY KEY (`cpId`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`msgId`);

--
-- Indexes for table `datapins`
--
ALTER TABLE `datapins`
  ADD PRIMARY KEY (`dpId`);

--
-- Indexes for table `dataplans`
--
ALTER TABLE `dataplans`
  ADD PRIMARY KEY (`pId`),
  ADD UNIQUE KEY `planid` (`planid`);

--
-- Indexes for table `datatokens`
--
ALTER TABLE `datatokens`
  ADD PRIMARY KEY (`tId`);

--
-- Indexes for table `electricityid`
--
ALTER TABLE `electricityid`
  ADD PRIMARY KEY (`eId`);

--
-- Indexes for table `examid`
--
ALTER TABLE `examid`
  ADD PRIMARY KEY (`eId`);

--
-- Indexes for table `networkid`
--
ALTER TABLE `networkid`
  ADD PRIMARY KEY (`nId`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`msgId`);

--
-- Indexes for table `sitesettings`
--
ALTER TABLE `sitesettings`
  ADD PRIMARY KEY (`sId`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`sId`),
  ADD UNIQUE KEY `sApiKey` (`sApiKey`),
  ADD UNIQUE KEY `sPhone` (`sPhone`),
  ADD UNIQUE KEY `sEmail` (`sEmail`);

--
-- Indexes for table `sysusers`
--
ALTER TABLE `sysusers`
  ADD PRIMARY KEY (`sysId`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`tId`),
  ADD UNIQUE KEY `transref` (`transref`);

--
-- Indexes for table `userlogin`
--
ALTER TABLE `userlogin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uservisits`
--
ALTER TABLE `uservisits`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `airtime`
--
ALTER TABLE `airtime`
  MODIFY `aId` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `airtimepinprice`
--
ALTER TABLE `airtimepinprice`
  MODIFY `aId` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `alphatopupprice`
--
ALTER TABLE `alphatopupprice`
  MODIFY `alphaId` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `apiconfigs`
--
ALTER TABLE `apiconfigs`
  MODIFY `aId` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `apilinks`
--
ALTER TABLE `apilinks`
  MODIFY `aId` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `cableid`
--
ALTER TABLE `cableid`
  MODIFY `cId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cableplans`
--
ALTER TABLE `cableplans`
  MODIFY `cpId` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `msgId` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `datapins`
--
ALTER TABLE `datapins`
  MODIFY `dpId` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `dataplans`
--
ALTER TABLE `dataplans`
  MODIFY `pId` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `datatokens`
--
ALTER TABLE `datatokens`
  MODIFY `tId` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `electricityid`
--
ALTER TABLE `electricityid`
  MODIFY `eId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `examid`
--
ALTER TABLE `examid`
  MODIFY `eId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `networkid`
--
ALTER TABLE `networkid`
  MODIFY `nId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `msgId` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sitesettings`
--
ALTER TABLE `sitesettings`
  MODIFY `sId` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `sId` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `sysusers`
--
ALTER TABLE `sysusers`
  MODIFY `sysId` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `tId` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userlogin`
--
ALTER TABLE `userlogin`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `uservisits`
--
ALTER TABLE `uservisits`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
