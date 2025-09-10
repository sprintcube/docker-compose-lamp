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
    device_sn VARCHAR(32) NOT NULL,
    device_name VARCHAR(128) NOT NULL,
    device_category VARCHAR(64),
    device_description VARCHAR(1000),
    loan_start DATETIME NOT NULL,
    loan_end DATETIME NOT NULL,
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

CREATE TABLE IF NOT EXISTS one_time_sessions {
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    otk CHAR(16) NOT NULL,
    sk CHAR(16),
    session_start DATETIME,
    username VARCHAR(7) NOT NULL,
    CONSTRAINT otk_username_constraint
        FOREIGN KEY (username) REFERENCES users(username),
    CONSTRAINT unique_otk_per_user UNIQUE (username)
}

SET @@GLOBAL.event_scheduler = ON;

-- Create the event to invalidate one-time password reset sessions
-- based on session age
CREATE EVENT AutoDeleteOldPasswordResetSessions
ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 30 MINUTE 
ON COMPLETION PRESERVE
DO 
DELETE LOW_PRIORITY FROM one_time_sessions WHERE session_start < DATE_SUB(NOW(), INTERVAL 45 MINUTE);
