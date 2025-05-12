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
