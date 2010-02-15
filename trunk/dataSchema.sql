-- phpMyAdmin SQL Dump
-- version 2.11.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 09, 2010 at 08:00 PM
-- Server version: 5.0.41
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `ubp2`
--

-- --------------------------------------------------------

--
-- Table structure for table `blacklists`
--

CREATE TABLE `blacklists` (
  `blacklistID` int(11) NOT NULL auto_increment,
  `userID` int(11) NOT NULL default '0',
  `blogID` int(11) NOT NULL default '0',
  PRIMARY KEY  (`blacklistID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `blogID` int(11) NOT NULL auto_increment,
  `title` varchar(450) collate ascii_bin NOT NULL,
  `post` varchar(15000) collate ascii_bin NOT NULL,
  `userID` int(11) NOT NULL default '0',
  `blacklistCount` int(11) NOT NULL default '0',
  `isBlacklisted` int(11) NOT NULL default '0',
  `cannotBeBlacklisted` int(11) NOT NULL default '0',
  `datePosted` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`blogID`)
) ENGINE=MyISAM  DEFAULT CHARSET=ascii COLLATE=ascii_bin COMMENT='This stores blog posts.' AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Table structure for table `passwordResets`
--

CREATE TABLE `passwordResets` (
  `passwordResetID` int(11) NOT NULL auto_increment,
  `generatedDate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `userID` int(11) NOT NULL default '0',
  `uniqueIdentifier` varchar(50) character set ascii collate ascii_bin NOT NULL,
  `used` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`passwordResetID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL auto_increment,
  `username` varchar(60) character set ascii collate ascii_bin NOT NULL,
  `password` varchar(150) character set ascii collate ascii_bin NOT NULL,
  `email` varchar(30) character set ascii collate ascii_bin default NULL,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `feedPageSize` int(11) NOT NULL default '5',
  PRIMARY KEY  (`userID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;
