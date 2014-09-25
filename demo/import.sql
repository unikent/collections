-- phpMyAdmin SQL Dump
-- version 4.2.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 25, 2014 at 05:23 PM
-- Server version: 5.5.39-log
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `verdi`
--

-- --------------------------------------------------------

--
-- Table structure for table `v_accession`
--

DROP TABLE IF EXISTS `v_accession`;
CREATE TABLE IF NOT EXISTS `v_accession` (
`id` int(11) NOT NULL,
  `accno` int(11) NOT NULL,
  `key` varchar(75) COLLATE utf8_bin NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `v_collections`
--

DROP TABLE IF EXISTS `v_collections`;
CREATE TABLE IF NOT EXISTS `v_collections` (
`id` int(11) NOT NULL,
  `RecordType` text COLLATE utf8_bin,
  `IDENTITY` text COLLATE utf8_bin,
  `Level` text COLLATE utf8_bin,
  `Repository` text COLLATE utf8_bin,
  `RefNo` text COLLATE utf8_bin,
  `AltRefNo` text COLLATE utf8_bin,
  `AccNo` text COLLATE utf8_bin,
  `Extent` text COLLATE utf8_bin,
  `Artist` text COLLATE utf8_bin,
  `Title` text COLLATE utf8_bin,
  `Date` text COLLATE utf8_bin,
  `UserText7` text COLLATE utf8_bin,
  `UserWrapped7` text COLLATE utf8_bin,
  `UserWrapped8` text COLLATE utf8_bin,
  `Publisher` text COLLATE utf8_bin,
  `UserWrapped6` text COLLATE utf8_bin,
  `PubDate` text COLLATE utf8_bin,
  `CONTENT` text COLLATE utf8_bin,
  `Description` text COLLATE utf8_bin,
  `UserText4` text COLLATE utf8_bin,
  `Format` text COLLATE utf8_bin,
  `Technique` text COLLATE utf8_bin,
  `UserText6` text COLLATE utf8_bin,
  `UserText1` text COLLATE utf8_bin,
  `UserWrapped2` text COLLATE utf8_bin,
  `UserWrapped3` text COLLATE utf8_bin,
  `Series` text COLLATE utf8_bin,
  `Thumbnail` text COLLATE utf8_bin,
  `Notes` text COLLATE utf8_bin,
  `UserWrapped5` text COLLATE utf8_bin,
  `Appraisal` text COLLATE utf8_bin,
  `Arrangement` text COLLATE utf8_bin,
  `Ignition` text COLLATE utf8_bin,
  `UserText5` text COLLATE utf8_bin,
  `UserWrapped9` text COLLATE utf8_bin,
  `UserText3` text COLLATE utf8_bin,
  `UserText8` text COLLATE utf8_bin,
  `UserText9` text COLLATE utf8_bin,
  `ACCESS` text COLLATE utf8_bin,
  `Location` text COLLATE utf8_bin,
  `ClosedUntil` text COLLATE utf8_bin,
  `AccessConditions` text COLLATE utf8_bin,
  `Copyright` text COLLATE utf8_bin,
  `Suspension` text COLLATE utf8_bin,
  `ChassisNo` text COLLATE utf8_bin,
  `AccessStatus` text COLLATE utf8_bin,
  `PhysicalDescription` text COLLATE utf8_bin,
  `CONSERVATIONREQUIRED` text COLLATE utf8_bin,
  `ConservationPriority` text COLLATE utf8_bin,
  `ALLIED_MATERIALS` text COLLATE utf8_bin,
  `Originals` text COLLATE utf8_bin,
  `Copies` text COLLATE utf8_bin,
  `RelatedMaterial` text COLLATE utf8_bin,
  `RelatedRecord` text COLLATE utf8_bin,
  `URL` text COLLATE utf8_bin,
  `CATALOGUE_STATUS` text COLLATE utf8_bin,
  `CatalogueStatus` text COLLATE utf8_bin,
  `CountryCode` text COLLATE utf8_bin,
  `RepositoryCode` text COLLATE utf8_bin,
  `EHFDPublisher` text COLLATE utf8_bin,
  `EHPDLanguage` text COLLATE utf8_bin,
  `ADMIN_DETAILS` text COLLATE utf8_bin,
  `RecordID` text COLLATE utf8_bin,
  `Creator` text COLLATE utf8_bin,
  `Created` text COLLATE utf8_bin,
  `Modifier` text COLLATE utf8_bin,
  `Modified` text COLLATE utf8_bin,
  `UserWrapped4` text COLLATE utf8_bin,
  `RCN` text COLLATE utf8_bin
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `v_subjects`
--

DROP TABLE IF EXISTS `v_subjects`;
CREATE TABLE IF NOT EXISTS `v_subjects` (
`id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=347 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `v_subjects_related`
--

DROP TABLE IF EXISTS `v_subjects_related`;
CREATE TABLE IF NOT EXISTS `v_subjects_related` (
`id` int(11) NOT NULL,
  `subj` int(11) NOT NULL,
  `related` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `v_accession`
--
ALTER TABLE `v_accession`
 ADD PRIMARY KEY (`id`), ADD KEY `accno` (`accno`), ADD KEY `key` (`key`);

--
-- Indexes for table `v_collections`
--
ALTER TABLE `v_collections`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `v_subjects`
--
ALTER TABLE `v_subjects`
 ADD PRIMARY KEY (`id`), ADD KEY `name` (`name`);

--
-- Indexes for table `v_subjects_related`
--
ALTER TABLE `v_subjects_related`
 ADD PRIMARY KEY (`id`), ADD KEY `subj` (`subj`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `v_accession`
--
ALTER TABLE `v_accession`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `v_collections`
--
ALTER TABLE `v_collections`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `v_subjects`
--
ALTER TABLE `v_subjects`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=347;
--
-- AUTO_INCREMENT for table `v_subjects_related`
--
ALTER TABLE `v_subjects_related`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=196;