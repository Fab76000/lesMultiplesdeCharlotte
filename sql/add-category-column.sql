-- ================================
-- Ajouter la colonne category_id à la table articles
-- ================================

-- Ajouter la colonne category_id
ALTER TABLE articles 
ADD COLUMN category_id INT DEFAULT NULL AFTER excerpt;

-- Ajouter la contrainte de clé étrangère
ALTER TABLE articles 
ADD CONSTRAINT fk_articles_category 
FOREIGN KEY (category_id) REFERENCES categories(id) 
ON DELETE SET NULL ON UPDATE CASCADE;

-- Ajouter un index sur category_id pour les performances
CREATE INDEX idx_category_id ON articles(category_id);

-- Vérification : afficher la structure mise à jour
DESCRIBE articles;