-- Create the database
CREATE DATABASE IF NOT EXISTS laitery;

-- Switch to the database
USE laitery;

-- Create the laite table
CREATE TABLE laite (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(128),
    sn VARCHAR(32),
    category VARCHAR(64),
    PRIMARY KEY (id)
);

-- Create the loan table
CREATE TABLE loan (
    id INT(11) NOT NULL AUTO_INCREMENT,
    device_id INT(11) NOT NULL,
    teacher_id VARCHAR(8) NOT NULL,
    loan_start DATETIME NOT NULL,
    loan_end DATETIME NOT NULL,
    returned TINYINT(1) DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (device_id) REFERENCES laite(id)
);