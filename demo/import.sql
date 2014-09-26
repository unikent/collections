-- phpMyAdmin SQL Dump
-- version 4.2.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 26, 2014 at 09:46 AM
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

CREATE TABLE IF NOT EXISTS `v_accession` (
`id` int(11) NOT NULL,
  `accno` int(11) NOT NULL,
  `name` varchar(75) COLLATE utf8_bin NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=903 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `v_collections`
--

CREATE TABLE IF NOT EXISTS `v_collections` (
`id` int(11) NOT NULL,
  `code` varchar(255) COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `date` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `level_t` varchar(255) COLLATE utf8_bin NOT NULL,
  `extent_t` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `v_people`
--

CREATE TABLE IF NOT EXISTS `v_people` (
  `code` varchar(255) COLLATE utf8_bin NOT NULL,
  `personname` varchar(255) COLLATE utf8_bin NOT NULL,
  `fullname` varchar(255) COLLATE utf8_bin NOT NULL,
  `nationality` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `v_subjects`
--

CREATE TABLE IF NOT EXISTS `v_subjects` (
`id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=375 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `v_subjects_related`
--

CREATE TABLE IF NOT EXISTS `v_subjects_related` (
`id` int(11) NOT NULL,
  `subj` int(11) NOT NULL,
  `related` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=211 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `v_accession`
--
ALTER TABLE `v_accession`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `u_accno_name` (`accno`,`name`), ADD KEY `accno` (`accno`), ADD KEY `key` (`name`);

--
-- Indexes for table `v_collections`
--
ALTER TABLE `v_collections`
 ADD PRIMARY KEY (`id`), ADD KEY `code` (`code`);

--
-- Indexes for table `v_people`
--
ALTER TABLE `v_people`
 ADD PRIMARY KEY (`code`);

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
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=903;
--
-- AUTO_INCREMENT for table `v_collections`
--
ALTER TABLE `v_collections`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=132;
--
-- AUTO_INCREMENT for table `v_subjects`
--
ALTER TABLE `v_subjects`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=375;
--
-- AUTO_INCREMENT for table `v_subjects_related`
--
ALTER TABLE `v_subjects_related`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=211;