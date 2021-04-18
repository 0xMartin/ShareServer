CREATE DATABASE drawsharing;
CREATE DATABASE chat;

CREATE USER 'user'@'localhost' IDENTIFIED BY 'd43Rd230Dguip778';
GRANT ALL PRIVILEGES ON *.* TO 'user'@'localhost';

USE drawsharing;

--<drawing>########################################################################

CREATE TABLE ImgUpdate (
	client_id   VARCHAR(30) NOT NULL PRIMARY KEY,
	update_time DATETIME(3) NOT NULL,
	image_name 	VARCHAR(30) NOT NULL,
	ip				  VARCHAR(20) NOT NULL
);

--<chat>########################################################################

USE chat;

CREATE TABLE Users (
	id   				VARCHAR(20) NOT NULL PRIMARY KEY,
	name			  VARCHAR(20) NOT NULL UNIQUE,
	password	  VARCHAR(100) NOT NULL,
	last_load_time DATETIME(3) NOT NULL
);

CREATE TABLE Chat (
	id   				INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	user_id 		VARCHAR(20) NOT NULL,
	msg_time 		DATETIME(3) NOT NULL,
	msg				  VARCHAR(1024) NOT NULL,
	img_url		  VARCHAR(40),
	FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);
