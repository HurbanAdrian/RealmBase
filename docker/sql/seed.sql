CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(50) NOT NULL UNIQUE,
                       email VARCHAR(100) NOT NULL UNIQUE,
                       password VARCHAR(255) NOT NULL,
                       role ENUM('user','admin') DEFAULT 'user'
);

CREATE TABLE categories (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            name VARCHAR(100) NOT NULL,
                            description TEXT NULL
);

CREATE TABLE posts (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       user_id INT NOT NULL,
                       category_id INT NOT NULL,
                       title VARCHAR(200) NOT NULL,
                       content TEXT NOT NULL,
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                       FOREIGN KEY (user_id) REFERENCES users(id),
                       FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE comments (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          post_id INT NOT NULL,
                          user_id INT NOT NULL,
                          content TEXT NOT NULL,
                          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          FOREIGN KEY (post_id) REFERENCES posts(id),
                          FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE logs (
                      id INT AUTO_INCREMENT PRIMARY KEY,
                      user_id INT NOT NULL,
                      action VARCHAR(255),
                      timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                      FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Default categories
INSERT INTO categories (name, description)
VALUES ('General', 'General discussion'),
       ('RPG Tips', 'Advice and guides'),
       ('Stories', 'Player stories and experiences');
