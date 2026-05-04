-- 1. Create the fresh database
CREATE DATABASE logistics_db;
USE logistics_db;

-- 2. Create the table with all the fields you requested
CREATE TABLE shipments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tracking_number VARCHAR(50) NOT NULL UNIQUE,
    status VARCHAR(100) DEFAULT 'Processing',
    location VARCHAR(255),
    
    -- Recipient Information
    rec_name VARCHAR(100),
    rec_address TEXT,
    rec_email VARCHAR(100),
    rec_phone VARCHAR(50),
    
    -- Sender Information
    send_name VARCHAR(100),
    send_info TEXT,
    
    -- Package Details
    package_pic VARCHAR(255), -- This stores the filename of the photo
    weight VARCHAR(20),
    
    -- Map Coordinates (Western Regions)
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 3. Insert one professional "Western" test case
-- Example: A package currently in London, UK
INSERT INTO shipments (
    tracking_number, status, location, 
    rec_name, rec_address, rec_email, rec_phone, 
    send_name, send_info, 
    latitude, longitude, weight
) VALUES (
    'GLOBAL7734', 'In Transit', 'Heathrow Cargo Centre, London', 
    'John Doe', '123 Baker Street, London, NW1 6XE', 'john.doe@email.com', '+44 20 7946 0000', 
    'Swift Global Shipping', 'Main Hub, NYC, USA', 
    51.4700, -0.4543, '2.5kg'
);