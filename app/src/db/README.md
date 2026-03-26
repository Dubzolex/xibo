Commande Terminal:
 - docker exec -it mariadb mariadb -u user -ppassword digital_signage
 - docker exec -it mariadb mariadb -u root -p

 docker-compose -f local.yml up -d


//users

CREATE TABLE IF NOT EXISTS screens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    duration INT DEFAULT 10,
    is_running BOOLEAN DEFAULT FALSE,
    is_updating BOOLEAN DEFAULT FALSE,
    is_visible BOOLEAN DEFAULT TRUE,
    is_readable BOOLEAN DEFAULT FALSE,
    is_controlled BOOLEAN DEFAULT FALSE,
    format VARCHAR(255) DEFAULT NULL,
    description VARCHAR(255) DEFAULT NULL,
    user_id INT DEFAULT NULL
);


//roles

CREATE TABLE IF NOT EXISTS roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    role VARCHAR(255) NOT NULL
);


//screens

CREATE TABLE IF NOT EXISTS screens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    duration INT DEFAULT 10,
    is_running BOOLEAN DEFAULT FALSE,
    is_updating BOOLEAN DEFAULT FALSE,
    is_visible BOOLEAN DEFAULT TRUE,
    is_readable BOOLEAN DEFAULT FALSE,
    is_controlled BOOLEAN DEFAULT FALSE,
    format VARCHAR(255) DEFAULT NULL,
    description VARCHAR(255) DEFAULT NULL,
    user_id INT DEFAULT NULL
);


//permissions

CREATE TABLE IF NOT EXISTS permissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    screen_id INT NOT NULL,
    role_id INT DEFAULT 1,
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


//sessions

CREATE TABLE IF NOT EXISTS sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    token VARCHAR(255) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    connected_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at DATETIME NOT NULL,
    is_revoked BOOLEAN DEFAULT FALSE
);




// fk

ALTER TABLE users ADD CONSTRAINT fk_users_role_id FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE sessions ADD CONSTRAINT fk_sessions_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE permissions ADD CONSTRAINT fk_permissions_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE permissions ADD CONSTRAINT fk_permissions_screen_id FOREIGN KEY (screen_id) REFERENCES screens(id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE screens ADD CONSTRAINT fk_screens_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE;

