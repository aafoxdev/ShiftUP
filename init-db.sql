CREATE DATABASE IF NOT EXISTS mydatabase;
USE mydatabase;

CREATE TABLE IF NOT EXISTS members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    password VARCHAR(100),
    email VARCHAR(100),
    picture VARCHAR(255),
    created DATETIME,
    modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    employeenumber VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message TEXT,
    sender_id INT,
    address_id INT,
    created DATETIME,
    modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    send_file VARCHAR(255)
);

INSERT INTO members (name, password, email, picture, created, employeenumber) VALUES
('admin', 'fcbe5b62ba083df3ccf2c3a127ec1ce3ef8fe635', 'admin', '20240302194652user.jpg', '2024-03-02 19:46:55', 'admin'),
('user', 'fcbe5b62ba083df3ccf2c3a127ec1ce3ef8fe635', 'yui@aafox.net', '20240304190216user.jpg', '2024-03-04 19:02:19', '0001');