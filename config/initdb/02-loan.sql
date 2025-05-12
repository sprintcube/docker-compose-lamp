CREATE TABLE IF NOT EXISTS loan (
    id INT(11) NOT NULL AUTO_INCREMENT,
    device_sn VARCHAR(32) NOT NULL,
    teacher_id VARCHAR(8) NOT NULL,
    loan_start DATETIME NOT NULL,
    loan_end DATETIME NOT NULL,
    returned TINYINT(1) DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (device_sn) REFERENCES laite(sn)
);