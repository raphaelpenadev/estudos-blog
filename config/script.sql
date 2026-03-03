CREATE DATABASE IF NOT EXISTS news_portal
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE news_portal;


CREATE TABLE roles (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    nome      VARCHAR(50)  NOT NULL UNIQUE,
    descricao VARCHAR(255)
);

CREATE TABLE users (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    nome      VARCHAR(100) NOT NULL,
    email     VARCHAR(150) NOT NULL UNIQUE,
    senha     VARCHAR(255) NOT NULL,
    role_id   INT NOT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE posts (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    titulo       VARCHAR(255) NOT NULL,
    slug         VARCHAR(255) NOT NULL UNIQUE,
    conteudo     LONGTEXT,
    status       ENUM('draft','published') DEFAULT 'draft',
    autor_id     INT NOT NULL,
    publicado_em DATETIME,   -- null até ser publicado
    criado_em    DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (autor_id) REFERENCES users(id)
);

CREATE TABLE categories (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE
);

-- Relação N:N
CREATE TABLE post_category (
    post_id     INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (post_id, category_id),
    FOREIGN KEY (post_id)     REFERENCES posts(id)      ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE sections (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    nome  VARCHAR(100) NOT NULL,
    slug  VARCHAR(100) NOT NULL UNIQUE,
    ordem INT DEFAULT 0 -- define a ordem de exibição na home
);

CREATE TABLE section_post (
    section_id INT NOT NULL,
    post_id    INT NOT NULL,
    ordem      INT DEFAULT 0,
    PRIMARY KEY (section_id, post_id),
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id)    REFERENCES posts(id)    ON DELETE CASCADE
);

-- Dados iniciais--
INSERT INTO roles (nome, descricao) VALUES
    ('admin',   'Acesso total ao sistema'),
    ('editor',  'Publica e gerencia destaque'),
    ('redator', 'Cria e edita rascunhos');

-- Senha: "admin123"
INSERT INTO users (nome, email, senha, role_id) VALUES
    ('Administrador', 'admin@portal.com',
     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);