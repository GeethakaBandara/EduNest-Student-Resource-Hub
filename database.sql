-- =========================================================
-- EduNest - Student Resource Hub
-- Database: student_resource_hub
-- =========================================================

CREATE DATABASE IF NOT EXISTS student_resource_hub;
USE student_resource_hub;

-- ---------------------------------------------------------
-- Table: users
-- Stores registered students (login/register)
-- ---------------------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,   -- hashed with password_hash() / PASSWORD_BCRYPT
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------
-- Table: messages
-- Stores messages from the Contact page
-- ---------------------------------------------------------
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------
-- Table: resources  (theme-specific table)
-- Stores uploaded study materials (notes, past papers, etc.)
-- ---------------------------------------------------------
CREATE TABLE resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100),
    study_year VARCHAR(50),
    semester VARCHAR(50),
    filename VARCHAR(255) NOT NULL,
    filepath VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
