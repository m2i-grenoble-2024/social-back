DROP TABLE IF EXISTS favourite;

DROP TABLE IF EXISTS post;

DROP TABLE IF EXISTS user;

CREATE TABLE user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL,
    ban_date DATETIME,
    role VARCHAR(64) NOT NULL
);

CREATE TABLE post (
    id INT PRIMARY KEY AUTO_INCREMENT,
    content TEXT NOT NULL,
    posted_at DATETIME NOT NULL,
    respond_to INTEGER, -- une des foreign key même si elle s'appelle *_id
    author_id INT NOT NULL,
    FOREIGN KEY (respond_to) REFERENCES post (id),
    FOREIGN KEY (author_id) REFERENCES user (id)
);

CREATE TABLE favourite (
    id_user INT NOT NULL,
    id_post INT NOT NULL,
    PRIMARY KEY (id_user, id_post),
    FOREIGN KEY (id_post) REFERENCES post (id),
    FOREIGN KEY (id_user) REFERENCES user (id)
);

INSERT INTO user (username,email,password,role, created_at) VALUES
('modo','modo@modo.com', '$2y$13$8wuW4SJ.HU2Efim3EyQ.qemT/O1M7blxFoEZQzSOEz6iDCNUZccaO', 'ROLE_MODO', NOW()),
('test', 'test@test.com', '$2y$13$8wuW4SJ.HU2Efim3EyQ.qemT/O1M7blxFoEZQzSOEz6iDCNUZccaO', 'ROLE_USER', NOW());

INSERT INTO post (content, posted_at,respond_to,author_id) VALUES 
('test post', '2024-10-01 10:00:00', NULL, 2),
('test post 2', '2024-10-01 10:00:00', NULL, 2),
('test post 3', '2024-10-01 10:00:00', NULL, 2),
('test post 4', '2024-10-01 10:00:00', NULL, 2),
('test post 5', '2024-10-01 10:00:00', NULL, 2),
('test post 6', '2024-10-01 10:00:00', NULL, 2),
('test post 7', '2024-10-01 10:00:00', NULL, 2),
('test post 8', '2024-10-01 10:00:00', NULL, 2),
('test post 9', '2024-10-01 10:00:00', NULL, 2),
('test post 10', '2024-10-01 10:00:00', NULL, 2),
('test post 11', '2024-10-01 10:00:00', NULL, 2),
('test post 12', '2024-10-01 10:00:00', NULL, 2),
('test post 13', '2024-10-01 10:00:00', NULL, 2),
('test post 14', '2024-10-01 10:00:00', NULL, 2),
('test post 15', '2024-10-01 10:00:00', NULL, 2),
('test post modo', '2024-10-01 10:00:00', NULL, 1),
('test response', '2024-10-01 10:00:00', 1, 2),
('other test post', '2024-10-01 10:00:00', NULL, 2);

INSERT INTO favourite (id_user,id_post) VALUES 
(1,1),
(2,2);