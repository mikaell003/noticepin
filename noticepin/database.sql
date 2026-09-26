CREATE DATABASE IF NOT EXISTS noticepin
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE noticepin;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    site_id VARCHAR(32) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE notices (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,

    title VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    button_text VARCHAR(100) DEFAULT NULL,
    button_url VARCHAR(500) DEFAULT NULL,

    position ENUM(
        'bottom-right',
        'bottom-left',
        'top-right',
        'top-left'
    ) NOT NULL DEFAULT 'bottom-right',

    note_color VARCHAR(20) NOT NULL DEFAULT '#fff4a3',
    text_color VARCHAR(20) NOT NULL DEFAULT '#222222',

    is_active TINYINT(1) NOT NULL DEFAULT 1,

    views INT UNSIGNED NOT NULL DEFAULT 0,
    clicks INT UNSIGNED NOT NULL DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_notices_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

CREATE INDEX idx_notices_site_active
ON notices(user_id, is_active);