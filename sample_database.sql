-- --------------------------------------------------------
-- SQL for sample database
-- --------------------------------------------------------

--
-- Database, user, permissions
--

CREATE DATABASE IF NOT EXISTS nth347database;
CREATE USER IF NOT EXISTS 'nth347user'@'localhost' IDENTIFIED BY 'verysecret';
GRANT ALL PRIVILEGES ON nth347database. * TO 'nth347user'@'localhost';
FLUSH PRIVILEGES;

--
-- Tables
--

CREATE TABLE posts (
    id INT NOT NULL AUTO_INCREMENT,
    title VARCHAR (128) NOT NULL,
    content text NOT NULL,
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY created_at (created_at)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Sample data
--

INSERT INTO posts (title, content) VALUES ('First post', 'This is a really interesting post.'),
                                          ('Second post', 'This is a fascinating post!'),
                                          ('Third post', 'This is a very informative post.');