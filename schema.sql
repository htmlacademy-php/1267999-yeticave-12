CREATE DATABASE yeticave
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;
USE yeticave;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL UNIQUE,
    date_registration TIMESTAMP,
    email VARCHAR(32) NOT NULL UNIQUE,
    name VARCHAR(32) NOT NULL,
    password VARCHAR(32) NOT NULL,
    contacts VARCHAR(128) NOT NULL UNIQUE
);
CREATE TABLE rate (
    date_rate TIMESTAMP PRIMARY KEY NOT NULL,
    sum INT NOT NULL
);
CREATE TABLE categories (
    cod INT PRIMARY KEY NOT NULL,
    category VARCHAR(32) NOT NULL
);
CREATE TABLE items (
    name VARCHAR(32) PRIMARY KEY NOT NULL,
    creation_date TIMESTAMP NOT NULL,
    description VARCHAR(256) NOT NULL,
    image VARCHAR(64) NOT NULL UNIQUE,
    starting_price INT NOT NULL,
    completion_date TIMESTAMP NOT NULL,
    step_rate INT NOT NULL
);

