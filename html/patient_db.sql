CREATE DATABASE careplus_test;

USE careplus_test;

CREATE TABLE patients (
    pid INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    date_reg DATE,
    name_title VARCHAR(10),
    name_first VARCHAR(50),
    name_middle VARCHAR(50),
    name_last VARCHAR(50),
    education VARCHAR(50),
    date_birth DATE,
    civil_status VARCHAR(20),
    sex ENUM('Male', 'Female'),
    ethnic_orig VARCHAR(50),
    home_town VARCHAR(50),
    place_birth VARCHAR(50),
    religion VARCHAR(50),
    contact_person VARCHAR(100),
    death_date DATE
);
