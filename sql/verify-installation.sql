-- ================================
-- VÉRIFICATION DE L'INSTALLATION
-- ================================

-- Vérifier que les tables existent
SHOW TABLES;

-- Compter les enregistrements
SELECT 'Articles' as Table_Name, COUNT(*) as Count FROM articles
UNION ALL
SELECT 'Admin Users' as Table_Name, COUNT(*) as Count FROM admin_users
UNION ALL
SELECT 'Categories' as Table_Name, COUNT(*) as Count FROM categories;

-- Afficher l'article de test
SELECT id, title, slug, status, created_at FROM articles LIMIT 1;

-- Afficher les catégories
SELECT * FROM categories;