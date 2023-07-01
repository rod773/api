CREATE TABLE task (
id INT NOT NULL AUTO_INCREMENT,
name VARCHAR(128) NOT NULL,
priority INT DEFAULT NULL,
is_completed boolean NOT NULL DEFAULT false,
PRIMARY KEY (id),
INDEX (name));

CREATE TABLE user (
id INT NOT NULL AUTO_INCREMENT,
name VARCHAR(128) NOT NULL,
username VARCHAR(128) NOT NULL,
password_hash VARCHAR(255) NOT NULL,
api_key VARCHAR(32) NOT NULL,
PRIMARY KEY (id),
UNIQUE (username),
UNIQUE (api_key) );


alter table task
add foreign key(user_id)
references user(id)
on delete cascade on update cascade;