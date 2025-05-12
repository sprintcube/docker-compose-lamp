CREATE TABLE IF NOT EXISTS device_returns (
    id INT(11) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id),
    username VARCHAR(7) NOT NULL,
    CONSTRAINT device_returns_username_constraint
        FOREIGN KEY (username) REFERENCES users(username),
    device_sn VARCHAR(32) NOT NULL,
    device_name VARCHAR(128) NOT NULL
);