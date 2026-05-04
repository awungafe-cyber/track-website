DROP DATABASE IF EXISTS logistics_db;
CREATE DATABASE logistics_db;
USE logistics_db;

CREATE TABLE shipments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tracking_number VARCHAR(50) NOT NULL UNIQUE,
    status VARCHAR(100) DEFAULT 'Processing',
    location VARCHAR(255),
    rec_name VARCHAR(100),
    rec_address TEXT,
    rec_email VARCHAR(100),
    rec_phone VARCHAR(50),
    send_name VARCHAR(100),
    send_info TEXT,
    package_pic VARCHAR(255),
    weight VARCHAR(20),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);