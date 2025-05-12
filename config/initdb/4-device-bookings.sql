CREATE TABLE IF NOT EXISTS device_bookings (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    loan_start DATETIME NOT NULL,
    loan_end DATETIME NOT NULL,
    teacher_id VARCHAR(30) NOT NULL,
    device_sn VARCHAR(32) NOT NULL,
    CONSTRAINT teacher_id_constraint
        FOREIGN KEY (teacher_id) REFERENCES users(username),
    CONSTRAINT device_sn_constraint
        FOREIGN KEY (device_sn) REFERENCES laite(sn)
);