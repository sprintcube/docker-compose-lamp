CREATE TABLE IF NOT EXISTS laite (
    sn VARCHAR(32) NOT NULL,
    name VARCHAR(128),
    category VARCHAR(64),
    description VARCHAR(1000),
    PRIMARY KEY (sn)
);