-- phpMyAdmin SQL Dump
-- version 2.6.1-rc1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Apr 07, 2005 at 10:57 AM
-- Server version: 4.0.23
-- PHP Version: 4.3.10
-- 
-- Database: `xentcvmanager`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `xent_cm_cat`
-- 

CREATE TABLE `xent_cm_cat` (
    `ID_CAT`        INT(5)       NOT NULL AUTO_INCREMENT,
    `name`          VARCHAR(255) NOT NULL DEFAULT '',
    `description`   VARCHAR(255)          DEFAULT NULL,
    `prefix`        VARCHAR(255)          DEFAULT NULL,
    `id_cat_parent` INT(5)       NOT NULL DEFAULT '0',
    KEY `ID_CAT` (`ID_CAT`)
)
    ENGINE = ISAM COMMENT ='Table des différentes catégories de cd';

-- --------------------------------------------------------

-- 
-- Table structure for table `xent_cm_cd`
-- 

CREATE TABLE `xent_cm_cd` (
    `ID_CD`         INT(5)       NOT NULL AUTO_INCREMENT,
    `name`          TEXT         NOT NULL,
    `number`        VARCHAR(11)  NOT NULL DEFAULT '0',
    `description`   VARCHAR(255)          DEFAULT NULL,
    `nogroup`       INT(5)       NOT NULL DEFAULT '0',
    `copy`          INT(5)       NOT NULL DEFAULT '0',
    `status`        INT(5)       NOT NULL DEFAULT '0',
    `id_cat`        INT(5)       NOT NULL DEFAULT '0',
    `id_group`      INT(5)       NOT NULL DEFAULT '0',
    `language`      VARCHAR(255) NOT NULL DEFAULT '',
    `date_parution` VARCHAR(255) NOT NULL DEFAULT '',
    `cdkey`         VARCHAR(255) NOT NULL DEFAULT '',
    KEY `ID_CD` (`ID_CD`)
)
    ENGINE = ISAM COMMENT ='Les cdpour les différentes catégories';

-- --------------------------------------------------------

-- 
-- Table structure for table `xent_cm_cd_group`
-- 

CREATE TABLE `xent_cm_cd_group` (
    `ID_GROUP`    INT(5)       NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(255) NOT NULL DEFAULT '',
    `description` VARCHAR(255)          DEFAULT NULL,
    KEY `ID_GROUP` (`ID_GROUP`)
)
    ENGINE = ISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `xent_cm_rent`
-- 

CREATE TABLE `xent_cm_rent` (
    `ID_RENT`       INT(5)       NOT NULL AUTO_INCREMENT,
    `date_rent`     VARCHAR(255) NOT NULL DEFAULT '0',
    `date_back`     VARCHAR(255) NOT NULL DEFAULT '0',
    `date_returned` VARCHAR(255) NOT NULL DEFAULT '',
    `status`        INT(5)       NOT NULL DEFAULT '0',
    `description`   VARCHAR(255)          DEFAULT NULL,
    `id_user`       INT(5)       NOT NULL DEFAULT '0',
    `id_cd`         INT(5)       NOT NULL DEFAULT '0',
    `isPrint`       INT(5)       NOT NULL DEFAULT '0',
    `emailSent`     INT(5)       NOT NULL DEFAULT '0',
    `res_group`     INT(5)       NOT NULL DEFAULT '0',
    PRIMARY KEY (`ID_RENT`),
    KEY `ID_RENT` (`ID_RENT`)
)
    ENGINE = ISAM COMMENT ='La liste de tous les emprunts';

-- --------------------------------------------------------

-- 
-- Table structure for table `xent_cm_searchcat`
-- 

CREATE TABLE `xent_cm_searchcat` (
    `ID_SEARCHCAT` INT(5)       NOT NULL AUTO_INCREMENT,
    `name`         VARCHAR(255) NOT NULL DEFAULT '',
    KEY `ID_SEARCHCAT` (`ID_SEARCHCAT`)
)
    ENGINE = ISAM;
