CREATE TABLE IF NOT EXISTS booking_notifications (
    id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(7) NOT NULL,
    CONSTRAINT teacher_id_constraint
        FOREIGN KEY (username) REFERENCES users(username),
    device_sn VARCHAR(32) NOT NULL,
    device_name VARCHAR(128) NOT NULL,
    message VARCHAR(175) NOT NULL,
);