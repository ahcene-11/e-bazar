-- Le prof copie/colle ce fichier dans phpMyAdmin onglet SQL

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
);

-- Données de test
INSERT INTO categories (name) VALUES ('Électronique'), ('Meubles'), ('Vêtements');

INSERT INTO users (email, password, role) VALUES 
('admin@test.com', '$2y$10$abcdefghijklmnopqrstuv', 'admin'),
('user@test.com', '$2y$10$abcdefghijklmnopqrstuv', 'user');