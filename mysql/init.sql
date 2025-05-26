CREATE TABLE admin(
    username VARCHAR(50) PRIMARY KEY,
    hashedPassword VARCHAR(255) NOT NULL
);

INSERT INTO admin (username, hashedPassword)
VALUES ('adminOne', '$2a$12$WuJ0oebqICPQwaxeehszkeSo348AeehDN2Wkar.0bfx4EBto9byiW');

CREATE TABLE judges (
    username VARCHAR(50) PRIMARY KEY,
    display_name VARCHAR(100) NOT NULL,
    hashedPassword VARCHAR(255) NOT NULL
);

CREATE TABLE users (
    username VARCHAR(100) PRIMARY KEY
);

CREATE TABLE scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(100) NOT NULL,
    judge_name VARCHAR(100) NOT NULL,
    points INT NOT NULL,
    FOREIGN KEY (user_name) REFERENCES users(username) ON DELETE CASCADE,
    FOREIGN KEY (judge_name) REFERENCES judges(username) ON DELETE CASCADE
);