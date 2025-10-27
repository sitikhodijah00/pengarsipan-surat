-- Buat database
CREATE DATABASE IF NOT EXISTS db_arsip;
USE db_arsip;

-- Buat tabel user
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(100) NOT NULL
);

-- Tambahkan akun admin default
INSERT INTO users (username, password) VALUES 
('admin', MD5('admin123'));
