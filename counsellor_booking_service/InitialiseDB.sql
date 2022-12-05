DROP USER IF EXISTS 'websiteuser'@'localhost';
DROP DATABASE IF EXISTS bookingsystem;

CREATE DATABASE bookingsystem;

USE bookingsystem;

CREATE USER 'websiteuser'@'localhost'
IDENTIFIED BY 'coit13230user';

GRANT CREATE,ALTER,SELECT,INSERT,UPDATE,DELETE,DROP
ON bookingsystem.*
TO 'websiteuser'@'localhost';
