CREATE TABLE api_db.task (
id INT NOT NULL AUTO_INCREMENT,
name VARCHAR(128) NOT NULL,
priority INT DEFAULT NULL,
is_completed boolean NOT NULL DEFAULT false,
PRIMARY KEY (id),
INDEX (name));