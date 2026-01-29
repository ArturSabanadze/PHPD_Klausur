CREATE DATABASE IF NOT EXISTS media_library
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE media_library;

CREATE TABLE admins (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username       VARCHAR(100) NOT NULL UNIQUE,
  password_hash  VARCHAR(255) NOT NULL,
  email          VARCHAR(255) NOT NULL UNIQUE,
  role           ENUM('admin','superadmin') NOT NULL DEFAULT 'admin'
);

CREATE TABLE users (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'active', 
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    last_login_at TIMESTAMP NULL
);

CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    google_id VARCHAR(50) UNIQUE NOT NULL,
    title VARCHAR(255) UNIQUE NOT NULL,
    subtitle VARCHAR(255),
    author VARCHAR(255),
    description TEXT,
    thumbnail VARCHAR(255),
    preview_link VARCHAR(255),
    publisher VARCHAR(255),
    published_date DATE,
    language VARCHAR(10),
    page_count INT,
    category VARCHAR(100),
    price DECIMAL(10, 2),
    currency VARCHAR(10),
    saleability VARCHAR(50)
);

CREATE TABLE movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) UNIQUE NOT NULL,
    year INT,
    director VARCHAR(255),
    actors TEXT,
    rating VARCHAR(50),
    runtime VARCHAR(50),
    genre VARCHAR(100),
    plot TEXT,
    thumbnail VARCHAR(255)
);

CREATE TABLE movies_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_type ENUM('books','movies','music') NOT NULL,
    product_id INT NOT NULL,
    user_id BIGINT NOT NULL,
    comment TEXT NOT NULL,
    rating TINYINT NULL,
    reported BOOLEAN DEFAULT FALSE,
    hidden BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES movies(id) ON DELETE CASCADE
);

CREATE TABLE books_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_type ENUM('books','movies','music') NOT NULL,
    product_id INT NOT NULL,
    user_id BIGINT NOT NULL,
    comment TEXT NOT NULL,
    rating TINYINT NULL,
    reported BOOLEAN DEFAULT FALSE,
    hidden BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES books(id) ON DELETE CASCADE
);

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new','in_progress','resolved') NOT NULL DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ========================================
-- USERS and ADMINS (1 Admin, 1 User)
-- ========================================
INSERT INTO admins (username, password_hash, email, role) VALUES
('admin', '$2y$12$wTvJXqcfg1e6df0RtRUFB.7vkDvRWdCHJlO/S/6veiGYCJIar5khG', 'admin@example.com', 'admin');

INSERT INTO users (username, password_hash, email, status) VALUES
('user', '$2y$12$E6xVt0Pb6FiK99JK9A67DeLZ5cK8vdm/tsjtkW0BoDr.0kSfEU.uK', 'user@example.com', 'active'),
('john_doe', '$2y$12$E6xVt0Pb6FiK99JK9A67DeLZ5cK8vdm/tsjtkW0BoDr.0kSfEU.uK', 'john_doe@example.com', 'active'),
('jane_smith', '$2y$12$E6xVt0Pb6FiK99JK9A67DeLZ5cK8vdm/tsjtkW0BoDr.0kSfEU.uK', 'jane_smith@example.com', 'active');

INSERT INTO contact_messages (user_id, subject, message, status) VALUES
(2, 'Issue with book data', 'I found some incorrect information in the book database.', 'new'),
(3, 'Feature Request', 'Can you add more categories for movies?', 'in_progress'),
(1, 'General Inquiry', 'How often is the movie database updated?', 'resolved');


/* Only after importing movies data  into Database*/
/* INSERT INTO movies_comments (product_type, product_id, user_id, comment, reported,  hidden, rating) VALUES
('movies', 1, 2, 'Amazing movie! A must-watch for everyone.', FALSE,  FALSE, 9),
('movies', 1, 3, 'Holly Sh1t that was a mess ! WTF ! (test comment for ban test)', TRUE,  FALSE, 1),
('movies', 2, 2, 'Not my cup of tea. Found it quite boring.', FALSE,  FALSE, 4),
('movies', 2, 2, 'A visual masterpiece with a compelling plot.', FALSE,  FALSE, 10),
('movies', 3, 3, 'Good movie but could have been better.', FALSE,  FALSE, 6),
('movies', 3, 2, 'Enjoyed every moment of it!', FALSE,  FALSE, 8); */

/* Only after importing books data into Database */
/* INSERT INTO books_comments (product_type, product_id, user_id, comment, reported,  hidden, rating) VALUES
('books', 1, 2, 'Amazing book! A must-read for everyone.', FALSE,  FALSE, 9),
('books', 1, 3, 'Holly Sh1t that was a mess ! WTF ! (test comment for ban test)', TRUE,  FALSE, 1),
('books', 2, 2, 'Not my cup of tea. Found it quite boring.', FALSE,  FALSE, 4),
('books', 2, 2, 'A visual masterpiece with a compelling plot.', FALSE,  FALSE, 10),
('books', 3, 3, 'Good book but could have been better.', FALSE,  FALSE, 6),
('books', 3, 2, 'Enjoyed every moment of it!', FALSE,  FALSE, 8); */