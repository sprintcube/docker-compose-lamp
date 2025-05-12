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
        FOREIGN KEY (device_sn) REFERENCES laite(sn),
    CONSTRAINT device_bookings_unique_booking UNIQUE (teacher_id, device_sn, booking_status)
);