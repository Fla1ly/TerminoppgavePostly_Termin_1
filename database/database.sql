-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema blog_db
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema blog_db
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `blog_db` DEFAULT CHARACTER SET latin1 ;
USE `blog_db` ;

-- -----------------------------------------------------
-- Table `blog_db`.`admin`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog_db`.`admin` ;

CREATE TABLE IF NOT EXISTS `blog_db`.`admin` (
  `id` INT(100) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  `password` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `blog_db`.`comments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog_db`.`comments` ;

CREATE TABLE IF NOT EXISTS `blog_db`.`comments` (
  `id` INT(100) NOT NULL AUTO_INCREMENT,
  `post_id` INT(100) NOT NULL,
  `admin_id` INT(100) NOT NULL,
  `user_id` INT(100) NOT NULL,
  `user_name` VARCHAR(50) NOT NULL,
  `comment` VARCHAR(1000) NOT NULL,
  `date` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 12
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `blog_db`.`likes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog_db`.`likes` ;

CREATE TABLE IF NOT EXISTS `blog_db`.`likes` (
  `id` INT(100) NOT NULL AUTO_INCREMENT,
  `user_id` INT(100) NOT NULL,
  `admin_id` INT(100) NOT NULL,
  `post_id` INT(100) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `user_id` (`user_id` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 13
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `blog_db`.`posts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog_db`.`posts` ;

CREATE TABLE IF NOT EXISTS `blog_db`.`posts` (
  `id` INT(100) NOT NULL AUTO_INCREMENT,
  `admin_id` INT(100) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `title` VARCHAR(100) NOT NULL,
  `content` VARCHAR(10000) NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `image` VARCHAR(100) NOT NULL,
  `date` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `status` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `blog_db`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog_db`.`users` ;

CREATE TABLE IF NOT EXISTS `blog_db`.`users` (
  `id` INT(100) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `password` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 11
DEFAULT CHARACTER SET = utf8mb4;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
