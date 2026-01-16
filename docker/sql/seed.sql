CREATE TABLE categories (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            name VARCHAR(100) NOT NULL,
                            description TEXT NULL
);

CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(50) NOT NULL,
                       email VARCHAR(100),
                       password VARCHAR(255) NOT NULL,
                       role ENUM('user','admin') DEFAULT 'user'
);

CREATE TABLE posts (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       user_id INT,
                       category_id INT,
                       title VARCHAR(200) NOT NULL,
                       content TEXT NOT NULL,
                       image VARCHAR(255) NULL,
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                       FOREIGN KEY (user_id) REFERENCES users(id),
                       FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE comments (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          post_id INT,
                          user_id INT,
                          content TEXT NOT NULL,
                          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          FOREIGN KEY (post_id) REFERENCES posts(id),
                          FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE logs (
                      id INT AUTO_INCREMENT PRIMARY KEY,
                      user_id INT,
                      action VARCHAR(255),
                      timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                      FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Vloženie kategórií šitých na mieru pre RealmBase
INSERT INTO categories (name, description) VALUES
                                               ('Novinky', 'Horúce aktuality z herného sveta a vývoja.'),
                                               ('Bugy a Fixy', 'Hlásenia o chybách a informácie o opravách systému.'),
                                               ('Tipy a Triky', 'Návody a odporúčania pre lepšie hranie a používanie portálu.'),
                                               ('Aktualizácie', 'Zoznam zmien a nových verzií aplikácie.');

-- Vloženie testovacích používateľov s tvojimi vlastnými hashmi
-- admin123 (admin) a user123 (user)
INSERT INTO users (username, email, password, role) VALUES
                                                        ('adminMe', 'admin@example.com', '$2y$12$k3KcFTCxAfrvKReItpfelO7VruLiJhBnWGXjqiFsIZQLeqG/HnEZq', 'admin'),
                                                        ('userMe', 'user@example.com', '$2y$10$dVi.S7pMLBhTcfIH.DovYuZsXks/CureemqUfMoLCNx1Ovc7Fgr.u', 'user');