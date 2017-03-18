# LAMP stack built with Docker Compose

This is a basic LAMP stack environment buit using Docker Compose. It consists following:

* PHP 7.1
* Apache 2.4
* MySQL 5.7
* phpMyAdmin

## Installation

Clone this repository on your local computer. Run the `docker-compose up -d`.

```shell
git clone https://github.com/theknightlybuilders/docker-compose-lamp.git
cd docker-compose-lamp/
docker-compose up -d
```
Your LAMP stack is now ready!! You can access it via `http://localhost`.

## Configuration

This package comes with default configuration options. You can modify them by creating `.env` file in your root directory.

To make it easy, just copy the content from `sample.env` file and update the environment variable values as per your need.

## phpMyAdmin

phpMyAdmin is configured to run on port 8080. Use following default credentials.

http://localhost:8080/
username: root
password: tiger
