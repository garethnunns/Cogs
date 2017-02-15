/*
Creates the database (and wipes the existing one)

Change log
==========

15/2/17 - Gareth Nunns
Added owned boolean to hardItem
Changed call.title to subject
Removed the probCall table as there's no need to have a call related to more than one problem
Added autoincrement to timezone table
idProblem auto increments
Changed call.time to date

14/2/17 - Gareth Nunns
Renamed constraints to make them unique names

14/2/17 - Gareth Nunns
Added changelog

*/

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `team21` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `team21` ;

-- -----------------------------------------------------
-- Table `team21`.`site`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`site` ;

CREATE TABLE IF NOT EXISTS `team21`.`site` (
  `idSite` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(60) NULL,
  PRIMARY KEY (`idSite`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`jobTitle`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`jobTitle` ;

CREATE TABLE IF NOT EXISTS `team21`.`jobTitle` (
  `idJobTitle` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(60) NULL,
  PRIMARY KEY (`idJobTitle`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`emp`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`emp` ;

CREATE TABLE IF NOT EXISTS `team21`.`emp` (
  `idEmp` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `site` INT UNSIGNED NOT NULL,
  `jobTitle` INT UNSIGNED NULL,
  `firstName` VARCHAR(45) NOT NULL,
  `surname` VARCHAR(45) NOT NULL,
  `tel` VARCHAR(20) NULL,
  `email` VARCHAR(60) NULL,
  PRIMARY KEY (`idEmp`),
  INDEX `site_idx` (`site` ASC),
  INDEX `jobTitle_idx` (`jobTitle` ASC),
  CONSTRAINT `site`
    FOREIGN KEY (`site`)
    REFERENCES `team21`.`site` (`idSite`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `jobTitle`
    FOREIGN KEY (`jobTitle`)
    REFERENCES `team21`.`jobTitle` (`idJobTitle`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`lang`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`lang` ;

CREATE TABLE IF NOT EXISTS `team21`.`lang` (
  `idLang` VARCHAR(10) NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idLang`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`timezone`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`timezone` ;

CREATE TABLE IF NOT EXISTS `team21`.`timezone` (
  `idTimezone` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idTimezone`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`login`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`login` ;

CREATE TABLE IF NOT EXISTS `team21`.`login` (
  `idEmp` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(30) NOT NULL,
  `password` VARCHAR(250) NOT NULL,
  `timezone` INT UNSIGNED NULL,
  `lang` VARCHAR(10) NOT NULL,
  `autoTrans` TINYINT(1) NULL,
  `impaired` TINYINT(1) NULL,
  `availablity` VARCHAR(150) NULL,
  INDEX `idemp_idx` (`idEmp` ASC),
  PRIMARY KEY (`idEmp`, `username`),
  INDEX `lang_idx` (`lang` ASC),
  INDEX `timezone_idx` (`timezone` ASC),
  CONSTRAINT `emp`
    FOREIGN KEY (`idEmp`)
    REFERENCES `team21`.`emp` (`idEmp`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `lang`
    FOREIGN KEY (`lang`)
    REFERENCES `team21`.`lang` (`idLang`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `timezone`
    FOREIGN KEY (`timezone`)
    REFERENCES `team21`.`timezone` (`idTimezone`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`dept`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`dept` ;

CREATE TABLE IF NOT EXISTS `team21`.`dept` (
  `idDept` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idDept`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`type` ;

CREATE TABLE IF NOT EXISTS `team21`.`type` (
  `idType` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(70) NOT NULL,
  `category` INT UNSIGNED NULL,
  PRIMARY KEY (`idType`),
  INDEX `cat_idx` (`category` ASC),
  CONSTRAINT `cat`
    FOREIGN KEY (`category`)
    REFERENCES `team21`.`type` (`idType`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`specialist`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`specialist` ;

CREATE TABLE IF NOT EXISTS `team21`.`specialist` (
  `idEmp` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `idType` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idEmp`, `idType`),
  INDEX `type_idx` (`idType` ASC),
  CONSTRAINT `idEmp`
    FOREIGN KEY (`idEmp`)
    REFERENCES `team21`.`emp` (`idEmp`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `type`
    FOREIGN KEY (`idType`)
    REFERENCES `team21`.`type` (`idType`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`problem`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`problem` ;

CREATE TABLE IF NOT EXISTS `team21`.`problem` (
  `idProblem` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `idType` INT UNSIGNED NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`idProblem`),
  INDEX `type_idx` (`idType` ASC),
  CONSTRAINT `probType`
    FOREIGN KEY (`idType`)
    REFERENCES `team21`.`type` (`idType`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`call`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`call` ;

CREATE TABLE IF NOT EXISTS `team21`.`call` (
  `idCall` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `idProblem` INT UNSIGNED NOT NULL,
  `caller` INT UNSIGNED NOT NULL,
  `op` INT UNSIGNED NOT NULL,
  `date` DATETIME NOT NULL,
  `subject` VARCHAR(45) NOT NULL,
  `notes` TEXT NULL,
  PRIMARY KEY (`idCall`),
  INDEX `caller_idx` (`caller` ASC),
  INDEX `operator_idx` (`op` ASC),
  INDEX `callProblem_idx` (`idProblem` ASC),
  CONSTRAINT `caller`
    FOREIGN KEY (`caller`)
    REFERENCES `team21`.`emp` (`idEmp`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `operator`
    FOREIGN KEY (`op`)
    REFERENCES `team21`.`emp` (`idEmp`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `callProblem`
    FOREIGN KEY (`idProblem`)
    REFERENCES `team21`.`problem` (`idProblem`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`solved`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`solved` ;

CREATE TABLE IF NOT EXISTS `team21`.`solved` (
  `idProblem` INT UNSIGNED NOT NULL,
  `specialist` INT UNSIGNED NOT NULL,
  `message` TEXT NOT NULL,
  `date` DATETIME NOT NULL,
  PRIMARY KEY (`idProblem`, `specialist`),
  INDEX `specialist_idx` (`specialist` ASC),
  CONSTRAINT `solvedProblem`
    FOREIGN KEY (`idProblem`)
    REFERENCES `team21`.`problem` (`idProblem`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `solvedSpecialist`
    FOREIGN KEY (`specialist`)
    REFERENCES `team21`.`emp` (`idEmp`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`soft`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`soft` ;

CREATE TABLE IF NOT EXISTS `team21`.`soft` (
  `idSoft` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `license` VARCHAR(100) NULL,
  `notes` TEXT NULL,
  PRIMARY KEY (`idSoft`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`OS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`OS` ;

CREATE TABLE IF NOT EXISTS `team21`.`OS` (
  `idOS` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idOS`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`softProb`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`softProb` ;

CREATE TABLE IF NOT EXISTS `team21`.`softProb` (
  `idProblem` INT UNSIGNED NOT NULL,
  `idSoft` INT UNSIGNED NOT NULL,
  `idOS` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idProblem`, `idSoft`, `idOS`),
  INDEX `software_idx` (`idSoft` ASC),
  INDEX `OS_idx` (`idOS` ASC),
  CONSTRAINT `softProblem`
    FOREIGN KEY (`idProblem`)
    REFERENCES `team21`.`problem` (`idProblem`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `software`
    FOREIGN KEY (`idSoft`)
    REFERENCES `team21`.`soft` (`idSoft`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `OS`
    FOREIGN KEY (`idOS`)
    REFERENCES `team21`.`OS` (`idOS`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`hardType`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`hardType` ;

CREATE TABLE IF NOT EXISTS `team21`.`hardType` (
  `idHardType` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idHardType`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`hard`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`hard` ;

CREATE TABLE IF NOT EXISTS `team21`.`hard` (
  `idHard` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `idHardType` INT UNSIGNED NOT NULL,
  `make` VARCHAR(70) NULL,
  `model` VARCHAR(45) NULL,
  `notes` TEXT NULL,
  PRIMARY KEY (`idHard`),
  INDEX `type_idx` (`idHardType` ASC),
  CONSTRAINT `hardType`
    FOREIGN KEY (`idHardType`)
    REFERENCES `team21`.`hardType` (`idHardType`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`hardProb`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`hardProb` ;

CREATE TABLE IF NOT EXISTS `team21`.`hardProb` (
  `idProblem` INT UNSIGNED NOT NULL,
  `idHard` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idProblem`, `idHard`),
  INDEX `hardware_idx` (`idHard` ASC),
  CONSTRAINT `hardProblem`
    FOREIGN KEY (`idProblem`)
    REFERENCES `team21`.`problem` (`idProblem`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `hardware`
    FOREIGN KEY (`idHard`)
    REFERENCES `team21`.`hard` (`idHard`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`deptEmp`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`deptEmp` ;

CREATE TABLE IF NOT EXISTS `team21`.`deptEmp` (
  `idDept` INT UNSIGNED NOT NULL,
  `idEmp` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idDept`, `idEmp`),
  INDEX `emp_idx` (`idEmp` ASC),
  CONSTRAINT `consDept`
    FOREIGN KEY (`idDept`)
    REFERENCES `team21`.`dept` (`idDept`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `consEmp`
    FOREIGN KEY (`idEmp`)
    REFERENCES `team21`.`emp` (`idEmp`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`assign`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`assign` ;

CREATE TABLE IF NOT EXISTS `team21`.`assign` (
  `idAssign` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `idProblem` INT UNSIGNED NOT NULL,
  `assBy` INT UNSIGNED NOT NULL,
  `assTo` INT UNSIGNED NOT NULL,
  `assDate` DATETIME NOT NULL,
  PRIMARY KEY (`idAssign`, `idProblem`, `assBy`, `assTo`),
  INDEX `problem_idx` (`idProblem` ASC),
  INDEX `assBy_idx` (`assBy` ASC),
  INDEX `assTo_idx` (`assTo` ASC),
  CONSTRAINT `assProblem`
    FOREIGN KEY (`idProblem`)
    REFERENCES `team21`.`problem` (`idProblem`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `assBy`
    FOREIGN KEY (`assBy`)
    REFERENCES `team21`.`emp` (`idEmp`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `assTo`
    FOREIGN KEY (`assTo`)
    REFERENCES `team21`.`emp` (`idEmp`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`message`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`message` ;

CREATE TABLE IF NOT EXISTS `team21`.`message` (
  `idMessage` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `idProblem` INT UNSIGNED NOT NULL,
  `date` DATETIME NOT NULL,
  `subject` VARCHAR(100) NOT NULL,
  `message` TEXT NOT NULL,
  `specialist` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idMessage`),
  INDEX `specialist_idx` (`specialist` ASC),
  INDEX `problem_idx` (`idProblem` ASC),
  CONSTRAINT `messySpecialist`
    FOREIGN KEY (`specialist`)
    REFERENCES `team21`.`emp` (`idEmp`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `messyProblem`
    FOREIGN KEY (`idProblem`)
    REFERENCES `team21`.`problem` (`idProblem`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`langStor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`langStor` ;

CREATE TABLE IF NOT EXISTS `team21`.`langStor` (
  `idLangStor` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `orig` VARCHAR(255) NOT NULL,
  `transLang` VARCHAR(10) NOT NULL,
  `trans` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idLangStor`),
  INDEX `transLang_idx` (`transLang` ASC),
  CONSTRAINT `transLang`
    FOREIGN KEY (`transLang`)
    REFERENCES `team21`.`lang` (`idLang`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `team21`.`hardItem`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `team21`.`hardItem` ;

CREATE TABLE IF NOT EXISTS `team21`.`hardItem` (
  `idhardItem` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `idHard` INT UNSIGNED NOT NULL,
  `owned` TINYINT(1) NULL DEFAULT 1,
  PRIMARY KEY (`idhardItem`, `idHard`),
  INDEX `hardThing_idx` (`idHard` ASC),
  CONSTRAINT `hardThing`
    FOREIGN KEY (`idHard`)
    REFERENCES `team21`.`hard` (`idHard`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
