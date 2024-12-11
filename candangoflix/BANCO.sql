CREATE DATABASE candangofilmes;
USE candangofilmes;

CREATE TABLE avaliacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    era_bom ENUM('sim', 'não') NOT NULL,
    comentario TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

select *  from avaliacoes

CREATE USER usuario@'localhost' IDENTIFIED BY 'Usuario!';
GRANT ALL PRIVILEGES ON *.* TO usuario@'localhost' WITH GRANT OPTION