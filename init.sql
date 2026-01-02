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

CREATE TABLE IF NOT EXISTS annonces (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    title VARCHAR(30) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    delivery_postal BOOLEAN DEFAULT FALSE,
    delivery_hand BOOLEAN DEFAULT FALSE,
    status ENUM('available', 'sold', 'confirmed') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    CONSTRAINT check_title_length CHECK (CHAR_LENGTH(title) BETWEEN 5 AND 30),
    CONSTRAINT check_description_length CHECK (CHAR_LENGTH(description) BETWEEN 5 AND 200),
    CONSTRAINT check_price CHECK (price >= 0),
    CONSTRAINT check_delivery CHECK (delivery_postal = TRUE OR delivery_hand = TRUE)
);

-- Données de test
INSERT INTO categories (name) VALUES
('Informatique'),
('Électroménager'),
('Mobilier'),
('Vêtements'),
('Livres'),
('Sports & Loisirs');

INSERT INTO users (email, password, role) VALUES
('admin@test.com', '$2y$10$abcdefghijklmnopqrstuv', 'admin'),
('user@test.com', '$2y$10$abcdefghijklmnopqrstuv', 'user');

INSERT INTO annonces (user_id, category_id, title, description, price, delivery_postal, delivery_hand, status) VALUES
(2, 1, 'Clavier mécanique RGB', 'Clavier mécanique en excellent état, switches Cherry MX Red, rétroéclairage RGB personnalisable. Utilisé 6 mois seulement.', 89.99, TRUE, TRUE, 'available'),
(2, 1, 'Souris gaming Logitech', 'Souris gaming haute précision, 7 boutons programmables, capteur optique 12000 DPI. Comme neuve, jamais servie.', 45.00, TRUE, FALSE, 'available'),
(2, 2, 'Cafetière Nespresso', 'Cafetière à capsules Nespresso, modèle Vertuo. Très bon état, détartrée régulièrement. Vendue avec 20 capsules.', 60.00, FALSE, TRUE, 'available'),
(2, 3, 'Bureau en bois massif', 'Bureau en chêne massif, dimensions 120x80cm. Quelques traces d\'usage mais très solide. Idéal pour télétravail.', 150.00, FALSE, TRUE, 'available');


CREATE TABLE IF NOT EXISTS photos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    annonce_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    annonce_id INT NOT NULL UNIQUE,
    buyer_id INT NOT NULL,
    delivery_mode ENUM('postal', 'hand') NOT NULL,
    confirmed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE
);
