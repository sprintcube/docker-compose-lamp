Initial DB Setup
================

## Logging in to the database
The DB runs inside the container. Once you have ran `docker compose up -d` you can look up the container ID for 
the database:

```
docker-compose-lamp % docker ps
CONTAINER ID   IMAGE            COMMAND                  CREATED          STATUS          PORTS                                         NAMES
232a7ec81072   phpmyadmin       "/docker-entrypoint.…"   36 seconds ago   Up 34 seconds   0.0.0.0:8080->80/tcp, 0.0.0.0:8443->443/tcp   lamp-phpmyadmin
220ae6711909   lamp-webserver   "docker-php-entrypoi…"   36 seconds ago   Up 34 seconds   0.0.0.0:80->80/tcp, 0.0.0.0:443->443/tcp      lamp-php83
8b932a308a65   redis:latest     "docker-entrypoint.s…"   36 seconds ago   Up 35 seconds   127.0.0.1:6379->6379/tcp                      lamp-redis
1ca96af04153   lamp-database    "docker-entrypoint.s…"   36 seconds ago   Up 35 seconds   127.0.0.1:3306->3306/tcp, 33060/tcp           lamp-mysql8
```

We are interested in the `lamp-database` container, which in the case of this illustration has the id `1ca96af04153`.
We ask docker for an interactive shell session inside the container with this id:

```
docker-compose-lamp % docker exec -it 1ca96af04153 sh
sh-5.1# 
```

Once we are inside the shell environment, we can login to the database inputing `docker` as both username and password and start working with tables:

```
sh-5.1# mysql -u docker -p
Enter password: 
Welcome to the MySQL monitor.  Commands end with ; or \g.
Your MySQL connection id is 8
Server version: 8.4.3 MySQL Community Server - GPL

Copyright (c) 2000, 2024, Oracle and/or its affiliates.

Oracle is a registered trademark of Oracle Corporation and/or its
affiliates. Other names may be trademarks of their respective
owners.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

mysql> 
```

Before working with tables make sure to switch to the `docker` database - it is the one that is used for local development.

```
mysql> show databases;
+--------------------+
| Database           |
+--------------------+
| docker             |
| information_schema |
| performance_schema |
+--------------------+
3 rows in set (0.04 sec)

mysql> use docker;
Reading table information for completion of table and column names
You can turn off this feature to get a quicker startup with -A

Database changed
mysql> show tables;
+------------------+
| Tables_in_docker |
+------------------+
| laite            |
| loan             |
+------------------+
2 rows in set (0.02 sec)
```
## Creating the tables

Creating the `laite` table:

```sql
CREATE TABLE laite (
    sn VARCHAR(32) NOT NULL,
    name VARCHAR(128),
    category VARCHAR(64),
    PRIMARY KEY (sn)
);
```

Creating the `loan` table:

```sql
CREATE TABLE loan (
    id INT(11) NOT NULL AUTO_INCREMENT,
    device_sn VARCHAR(32) NOT NULL,
    teacher_id VARCHAR(8) NOT NULL,
    loan_start DATETIME NOT NULL,
    loan_end DATETIME NOT NULL,
    returned TINYINT(1) DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (device_sn) REFERENCES laite(sn)
);
```