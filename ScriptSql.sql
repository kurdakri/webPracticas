-- MySQL Script generated by MySQL Workbench
-- dom 17 abr 2016 22:10:12 CEST
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`Administrador`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Administrador` (
  `login` VARCHAR(10) NOT NULL,
  `password` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`login`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Coordinador`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Coordinador` (
  `idCoordinador` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(30) NULL,
  `apellidos` VARCHAR(30) NULL,
  `dni` VARCHAR(10) NULL,
  `telefono` VARCHAR(15) NULL,
  `email` VARCHAR(30) NULL,
  `login` VARCHAR(30) NULL,
  `password` VARCHAR(30) NULL,
  PRIMARY KEY (`idCoordinador`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Estudiante`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Estudiante` (
  `idEstudiante` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(30) NULL,
  `apellidos` VARCHAR(30) NULL,
  `dni` VARCHAR(10) NULL,
  `telefono` VARCHAR(15) NULL,
  `email` VARCHAR(30) NULL,
  `niu` VARCHAR(15) NULL,
  `login` VARCHAR(30) NULL,
  `password` VARCHAR(30) NULL,
  PRIMARY KEY (`idEstudiante`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Tutor`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Tutor` (
  `idTutor` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(30) NULL,
  `apellidos` VARCHAR(30) NULL,
  `dni` VARCHAR(10) NULL,
  `telefono` VARCHAR(15) NULL,
  `email` VARCHAR(30) NULL,
  `login` VARCHAR(30) NULL,
  `password` VARCHAR(30) NULL,
  PRIMARY KEY (`idTutor`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Empresa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Empresa` (
  `idEmpresa` INT NOT NULL,
  `nombre` VARCHAR(30) NULL,
  `cif` VARCHAR(30) NULL,
  `telefono` VARCHAR(15) NULL,
  `email` VARCHAR(30) NULL,
  `provincia` VARCHAR(30) NULL,
  `localidad` VARCHAR(30) NULL,
  `calle` VARCHAR(30) NULL,
  `login` VARCHAR(30) NULL,
  `password` VARCHAR(30) NULL,
  PRIMARY KEY (`idEmpresa`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`SolicitudesAltaUsuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`SolicitudesAltaUsuario` (
  `idSolicitudes` INT NOT NULL AUTO_INCREMENT,
  `tipo` VARCHAR(10) NULL,
  `nombre` VARCHAR(30) NULL,
  `apellidos` VARCHAR(30) NULL,
  `dni` VARCHAR(10) NULL,
  `telefono` VARCHAR(15) NULL,
  `email` VARCHAR(30) NULL,
  `login` VARCHAR(30) NULL,
  `password` VARCHAR(30) NULL,
  `niu` VARCHAR(15) NULL,
  `cif` VARCHAR(30) NULL,
  `provincia` VARCHAR(30) NULL,
  `localidad` VARCHAR(30) NULL,
  `calle` VARCHAR(30) NULL,
  PRIMARY KEY (`idSolicitudes`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Contenido`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Contenido` (
  `idContenido` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(15) NOT NULL,
  `content` TEXT NULL,
  PRIMARY KEY (`idContenido`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Practica`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Practica` (
  `idPractica` INT NOT NULL AUTO_INCREMENT,
  `codigoIdentificacion` VARCHAR(45) NULL,
  `titulo` VARCHAR(15) NULL,
  `inicio` DATE NULL,
  `fin` DATE NULL,
  `Empresa_idEmpresa` INT NOT NULL,
  `Tutor_idTutor` INT NOT NULL,
  `Estudiante_idEstudiante` INT NOT NULL,
  `Coordinador_idCoordinador` INT NOT NULL,
  PRIMARY KEY (`idPractica`, `Empresa_idEmpresa`, `Tutor_idTutor`, `Estudiante_idEstudiante`, `Coordinador_idCoordinador`),
  INDEX `fk_Practica_Empresa_idx` (`Empresa_idEmpresa` ASC),
  INDEX `fk_Practica_Tutor1_idx` (`Tutor_idTutor` ASC),
  INDEX `fk_Practica_Estudiante1_idx` (`Estudiante_idEstudiante` ASC),
  INDEX `fk_Practica_Coordinador1_idx` (`Coordinador_idCoordinador` ASC),
  CONSTRAINT `fk_Practica_Empresa`
    FOREIGN KEY (`Empresa_idEmpresa`)
    REFERENCES `mydb`.`Empresa` (`idEmpresa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Practica_Tutor1`
    FOREIGN KEY (`Tutor_idTutor`)
    REFERENCES `mydb`.`Tutor` (`idTutor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Practica_Estudiante1`
    FOREIGN KEY (`Estudiante_idEstudiante`)
    REFERENCES `mydb`.`Estudiante` (`idEstudiante`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Practica_Coordinador1`
    FOREIGN KEY (`Coordinador_idCoordinador`)
    REFERENCES `mydb`.`Coordinador` (`idCoordinador`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`SolicitudesBajaUsuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`SolicitudesBajaUsuario` (
  `idSolicitudesBajaUsuario` INT NOT NULL AUTO_INCREMENT,
  `idUsuario` INT NULL,
  `tipoUsuario` VARCHAR(15) NULL,
  PRIMARY KEY (`idSolicitudesBajaUsuario`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`SolicitudesPracticas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`SolicitudesPracticas` (
  `idSolicitudesPracticas` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(30) NULL,
  `prioridad` INT NULL,
  `estudiante` VARCHAR(30) NULL,
  `empresa` VARCHAR(30) NULL,
  `Estudiante_idEstudiante` INT NOT NULL,
  `Coordinador_idCoordinador` INT NOT NULL,
  PRIMARY KEY (`idSolicitudesPracticas`, `Estudiante_idEstudiante`, `Coordinador_idCoordinador`),
  INDEX `fk_SolicitudesPracticas_Estudiante1_idx` (`Estudiante_idEstudiante` ASC),
  INDEX `fk_SolicitudesPracticas_Coordinador1_idx` (`Coordinador_idCoordinador` ASC),
  CONSTRAINT `fk_SolicitudesPracticas_Estudiante1`
    FOREIGN KEY (`Estudiante_idEstudiante`)
    REFERENCES `mydb`.`Estudiante` (`idEstudiante`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_SolicitudesPracticas_Coordinador1`
    FOREIGN KEY (`Coordinador_idCoordinador`)
    REFERENCES `mydb`.`Coordinador` (`idCoordinador`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Propuestas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Propuestas` (
  `idPropuestas` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(45) NULL,
  `empresa` VARCHAR(45) NULL,
  `descripcion` TEXT NULL,
  `Empresa_idEmpresa` INT NOT NULL,
  `Coordinador_idCoordinador` INT NOT NULL,
  PRIMARY KEY (`idPropuestas`, `Empresa_idEmpresa`, `Coordinador_idCoordinador`),
  INDEX `fk_Propuestas_Empresa1_idx` (`Empresa_idEmpresa` ASC),
  INDEX `fk_Propuestas_Coordinador1_idx` (`Coordinador_idCoordinador` ASC),
  CONSTRAINT `fk_Propuestas_Empresa1`
    FOREIGN KEY (`Empresa_idEmpresa`)
    REFERENCES `mydb`.`Empresa` (`idEmpresa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Propuestas_Coordinador1`
    FOREIGN KEY (`Coordinador_idCoordinador`)
    REFERENCES `mydb`.`Coordinador` (`idCoordinador`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
