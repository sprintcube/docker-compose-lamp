CREATE TABLE IF NOT EXISTS devices (
    sn VARCHAR(32) NOT NULL,
    name VARCHAR(128),
    category VARCHAR(64),
    description VARCHAR(1000),
    PRIMARY KEY (sn)
);

CREATE TABLE IF NOT EXISTS users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(7) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('superadmin', 'admin', 'user') NOT NULL DEFAULT 'user',
    email VARCHAR(127) NOT NULL,
    name VARCHAR(127) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY (username)
);

CREATE TABLE IF NOT EXISTS device_bookings (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    loan_start DATETIME NOT NULL,
    loan_end DATETIME NOT NULL,
    teacher_id VARCHAR(7) NOT NULL,
    device_sn VARCHAR(32) NOT NULL,
    booking_status ENUM('booked', 'loaned') NOT NULL DEFAULT 'booked',
    CONSTRAINT device_bookings_teacher_id_constraint
        FOREIGN KEY (teacher_id) REFERENCES users(username),
    CONSTRAINT device_bookings_device_sn_constraint
        FOREIGN KEY (device_sn) REFERENCES devices(sn),
    CONSTRAINT device_bookings_unique_booking UNIQUE (teacher_id, device_sn, booking_status)
);

CREATE TABLE IF NOT EXISTS device_returns (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(7) NOT NULL,
    CONSTRAINT device_returns_username_constraint
        FOREIGN KEY (username) REFERENCES users(username),
    device_sn VARCHAR(32) NOT NULL,
    device_name VARCHAR(128) NOT NULL,
    device_category VARCHAR(64),
    device_description VARCHAR(1000),
    teacher_id VARCHAR(7) NOT NULL,
    CONSTRAINT device_returns_teacher_id_constraint
        FOREIGN KEY (teacher_id) REFERENCES users(username)
);

CREATE TABLE IF NOT EXISTS booking_notifications (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(7) NOT NULL,
    CONSTRAINT teacher_id_constraint
        FOREIGN KEY (username) REFERENCES users(username),
    device_sn VARCHAR(32) NOT NULL,
    device_name VARCHAR(128) NOT NULL,
    message VARCHAR(175) NOT NULL
);