-- =========================================================
-- Real Estate Agency Portal: full schema
-- Reconstructed from the columns the PHP code uses (RealEstateDatabase.php).
-- Compare with your own phpMyAdmin export and replace anything that differs.
-- =========================================================
CREATE DATABASE IF NOT EXISTS real_estate_portal_db;
USE real_estate_portal_db;

DROP TABLE IF EXISTS Transactions;
DROP TABLE IF EXISTS Favorites;
DROP TABLE IF EXISTS Inquiries;
DROP TABLE IF EXISTS Properties;
DROP TABLE IF EXISTS Users;

CREATE TABLE Users (
    userId       INT NOT NULL AUTO_INCREMENT,
    userName     VARCHAR(50) NOT NULL UNIQUE,
    contactInfo  VARCHAR(200),
    passwordHash VARCHAR(255) NOT NULL,
    userType     ENUM('agent', 'buyer', 'renter') NOT NULL,
    PRIMARY KEY (userId)
);

CREATE TABLE Properties (
    propertyId   INT NOT NULL AUTO_INCREMENT,
    title        VARCHAR(150) NOT NULL,
    propertyType VARCHAR(50)  NOT NULL,
    address      VARCHAR(200) NOT NULL,
    city         VARCHAR(100) NOT NULL,
    price        DECIMAL(12,2) NOT NULL,
    status       ENUM('available', 'sold', 'rented') NOT NULL DEFAULT 'available',
    agentId      INT NOT NULL,
    PRIMARY KEY (propertyId),
    FOREIGN KEY (agentId) REFERENCES Users(userId)
);

CREATE TABLE Inquiries (
    inquiryId   INT NOT NULL AUTO_INCREMENT,
    userId      INT NOT NULL,
    propertyId  INT NOT NULL,
    message     TEXT NOT NULL,
    inquiryDate DATETIME NOT NULL,
    PRIMARY KEY (inquiryId),
    FOREIGN KEY (userId) REFERENCES Users(userId),
    FOREIGN KEY (propertyId) REFERENCES Properties(propertyId)
);

CREATE TABLE Favorites (
    favoriteId INT NOT NULL AUTO_INCREMENT,
    userId     INT NOT NULL,
    propertyId INT NOT NULL,
    PRIMARY KEY (favoriteId),
    UNIQUE (userId, propertyId),
    FOREIGN KEY (userId) REFERENCES Users(userId),
    FOREIGN KEY (propertyId) REFERENCES Properties(propertyId)
);

CREATE TABLE Transactions (
    transactionId   INT NOT NULL AUTO_INCREMENT,
    userId          INT NOT NULL,
    propertyId      INT NOT NULL,
    transactionType ENUM('sale', 'rental') NOT NULL,
    amount          DECIMAL(12,2) NOT NULL,
    transactionDate DATETIME NOT NULL,
    PRIMARY KEY (transactionId),
    FOREIGN KEY (userId) REFERENCES Users(userId),
    FOREIGN KEY (propertyId) REFERENCES Properties(propertyId)
);

-- Sample data: register real accounts through register.php so passwords are hashed properly.
-- (Placeholder hashes cannot be used to log in.)
