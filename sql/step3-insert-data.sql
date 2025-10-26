-- ================================
-- ÉTAPE 3 : INSÉRER LES DONNÉES
-- ================================

-- Création de l'admin principal (mot de passe : "password")
INSERT INTO admin_users (username, email, password_hash) VALUES 
('charlotte', 'charlotte.transcourt@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Catégories par défaut
INSERT INTO categories (name, slug, description) VALUES 
('Spectacles', 'spectacles', 'Articles sur les créations et représentations'),
('Médiation', 'mediation', 'Ateliers et projets de médiation artistique'),
('Actualités', 'actualites', 'Nouvelles et événements'),
('Coulisses', 'coulisses', 'En coulisses de la création artistique');

-- Article de bienvenue
INSERT INTO articles (title, slug, content, excerpt, status, meta_description, tags) VALUES 
(
    'Bienvenue sur le blog des Multiples de Charlotte',
    'bienvenue-blog-multiples-charlotte',
    '<p>Bienvenue sur le nouveau blog des Multiples de Charlotte !</p>
    <p>Vous découvrirez ici mes créations, mes projets de médiation artistique, et les coulisses de mon univers créatif.</p>
    <p>Entre spectacles vivants, ateliers d''écriture et rencontres inspirantes, ce blog sera le reflet de ma démarche artistique pluridisciplinaire.</p>
    <p>N''hésitez pas à partager vos impressions et à suivre mes actualités !</p>
    <p>À bientôt,<br>Charlotte</p>',
    'Découvrez le nouveau blog de Charlotte Goupil, artiste normande aux multiples facettes. Spectacles, médiation artistique et coulisses créatives au programme.',
    'published',
    'Blog de Charlotte Goupil - Artiste multidisciplinaire, spectacles vivants et médiation artistique en Normandie',
    'blog, charlotte goupil, spectacles, médiation artistique, normandie'
);