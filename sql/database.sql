CREATE DATABASE IF NOT EXISTS webchat;

use webchat;

CREATE TABLE credentials(
    credentials_id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(15) NOT NULL UNIQUE,
    name VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    creation_time DATETIME DEFAULT CURRENT_TIMESTAMP,     -- that will seamlessly insert the current timestamp of the row insertion without the need for php passing it 

    PRIMARY KEY (credentials_id)
);