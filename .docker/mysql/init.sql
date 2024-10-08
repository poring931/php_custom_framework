CREATE DATABASE IF NOT EXISTS testdb;

USE testdb;

CREATE USER IF NOT EXISTS 'user'@'%' IDENTIFIED BY 'user_password';
GRANT ALL PRIVILEGES ON testdb.* TO 'user'@'%';

FLUSH PRIVILEGES;