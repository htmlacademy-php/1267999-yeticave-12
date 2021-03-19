DROP DATABASE IF EXISTS yeticave;
CREATE DATABASE yeticave
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

CREATE TABLE `yeticave`.`category` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(256) NOT NULL,
  `code` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `yeticave`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date_registration` TIMESTAMP NOT NULL,
  `email` VARCHAR(256) NOT NULL,
  `name` VARCHAR(256) NOT NULL,
  `password` VARCHAR(256) NOT NULL,
  `contacts` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `yeticave`.`lot` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_category` INT NOT NULL,
  `id_user_create` INT NOT NULL,
  `date_creation` TIMESTAMP NOT NULL,
  `name` VARCHAR(256) NOT NULL,
  `description` VARCHAR(256) NULL,
  `image` VARCHAR(256) NULL,
  `price_starting` DECIMAL(10,0) NOT NULL,
  `date_completion` TIMESTAMP NOT NULL,
  `step_rate` DECIMAL(10,0) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_category_lot_idx` (`id_category` ASC),
  INDEX `fk_user_lot_idx` (`id_user_create` ASC),
  CONSTRAINT `fk_category_lot`
    FOREIGN KEY (`id_category`)
    REFERENCES `yeticave`.`category` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_lot`
    FOREIGN KEY (`id_user_create`)
    REFERENCES `yeticave`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION);

CREATE TABLE `yeticave`.`rate` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_user_game` INT NOT NULL,
  `id_lot` INT NOT NULL,
  `date_rate` TIMESTAMP NOT NULL,
  `price_rate` DECIMAL(10,0) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_rate_idx` (`id_user_game` ASC),
  INDEX `fk_lot_rate_idx` (`id_lot` ASC),
  CONSTRAINT `fk_user_rate`
    FOREIGN KEY (`id_user_game`)
    REFERENCES `yeticave`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lot_rate`
    FOREIGN KEY (`id_lot`)
    REFERENCES `yeticave`.`lot` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION);



