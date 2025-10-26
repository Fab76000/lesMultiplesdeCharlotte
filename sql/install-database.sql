-- =================================================
-- SCRIPT D'INSTALLATION BASE DE DONNÉES BLOG
-- Charlotte Goupil - Les Multiples
-- =================================================

-- 1. CRÉATION DE LA BASE DE DONNÉES
CREATE DATABASE IF NOT EXISTS charlotte_blog 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- 2. UTILISER LA BASE DE DONNÉES
USE charlotte_blog;

-- 3. CRÉATION DE L'UTILISATEUR DÉDIÉ
-- (Exécuter ces commandes en tant qu'admin MySQL)
CREATE USER IF NOT EXISTS 'charlotte_user'@'localhost' IDENTIFIED BY 'Charlotte2025Blog!';
GRANT ALL PRIVILEGES ON charlotte_blog.* TO 'charlotte_user'@'localhost';
FLUSH PRIVILEGES;

-- 4. CRÉATION DES TABLES

-- Table des articles de blog
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content LONGTEXT NOT NULL,
    excerpt TEXT,
    featured_image VARCHAR(255) DEFAULT NULL,
    status ENUM('draft', 'published') DEFAULT 'draft',
    author VARCHAR(100) DEFAULT 'Charlotte Goupil',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    meta_description VARCHAR(160) DEFAULT NULL,
    tags VARCHAR(500) DEFAULT NULL,
    
    -- Index pour les performances
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des utilisateurs admin
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL DEFAULT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des catégories (optionnel pour plus tard)
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table de liaison articles-catégories (optionnel)
CREATE TABLE article_categories (
    article_id INT,
    category_id INT,
    PRIMARY KEY (article_id, category_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. INSERTION DE DONNÉES DE TEST

-- Création de l'admin principal
INSERT INTO admin_users (username, email, password_hash) VALUES 
('charlotte', 'charlotte.transcourt@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- Mot de passe par défaut : 'password' (à changer après première connexion)

-- Catégories par défaut
INSERT INTO categories (name, slug, description) VALUES 
('Spectacles', 'spectacles', 'Articles sur les créations et représentations'),
('Médiation', 'mediation', 'Ateliers et projets de médiation artistique'),
('Actualités', 'actualites', 'Nouvelles et événements'),
('Coulisses', 'coulisses', 'En coulisses de la création artistique');

-- Article de test
INSERT INTO articles (title, slug, content, excerpt, status, meta_description, tags) VALUES 
(
    'Bienvenue sur le blog des Multiples de Charlotte',
    'bienvenue-blog-multiples-charlotte',
    '<p>Bienvenue sur le nouveau blog des Multiples de Charlotte !</p>
    <p>Vous découvrirez ici mes créations, mes projets de médiation artistique, et les coulisses de mon univers créatif.</p>
    <p>Entre spectacles vivants, ateliers d\'écriture et rencontres inspirantes, ce blog sera le reflet de ma démarche artistique pluridisciplinaire.</p>
    <p>N\'hésitez pas à partager vos impressions et à suivre mes actualités !</p>
    <p>À bientôt,<br>Charlotte</p>',
    'Découvrez le nouveau blog de Charlotte Goupil, artiste normande aux multiples facettes. Spectacles, médiation artistique et coulisses créatives au programme.',
    'published',
    'Blog de Charlotte Goupil - Artiste multidisciplinaire, spectacles vivants et médiation artistique en Normandie',
    'blog, charlotte goupil, spectacles, médiation artistique, normandie'
);

-- 6. VÉRIFICATIONS
SELECT 'Base de données créée avec succès!' AS message;
SELECT COUNT(*) AS nombre_tables FROM information_schema.tables WHERE table_schema = 'charlotte_blog';
SELECT COUNT(*) AS nombre_articles FROM articles;
SELECT COUNT(*) AS nombre_admins FROM admin_users;

-- 7. AFFICHAGE DES INFORMATIONS DE CONNEXION
SELECT 
    'charlotte_blog' AS nom_base,
    'charlotte_user' AS utilisateur,
    'C6tkyW272ZGs4qJ' AS mot_de_passe,
    'localhost' AS serveur,
    '3306' AS port;