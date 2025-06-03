# demka_raf

-- Создание базы данных
CREATE DATABASE IF NOT EXISTS gruzovozoff CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gruzovozoff;

-- Таблица пользователей
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблица заявок
CREATE TABLE requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    cargo_type ENUM('Хрупкое', 'Скоропортящееся', 'Требуется рефрижератор', 'Животные', 'Жидкость', 'Мебель', 'Мусор') NOT NULL,
    weight DECIMAL(10, 2) NOT NULL,
    dimensions VARCHAR(100) NOT NULL,
    departure_address VARCHAR(255) NOT NULL,
    delivery_address VARCHAR(255) NOT NULL,
    transport_datetime DATETIME NOT NULL,
    status ENUM('Новая', 'В работе', 'Отменена', 'Доставлено', 'Завершено') DEFAULT 'Новая',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Таблица отзывов
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Вставка администратора
INSERT INTO users (full_name, phone, email, username, password, role)
VALUES ('Администратор', '+7(900)-000-00-00', 'admin@gruzovozoff.local', 'admin', SHA2('gruzovik2024', 256), 'admin');
