-- Ajouter la colonne full_name à la table admin_users
-- Pour afficher le prénom et nom complet de l'administrateur

ALTER TABLE admin_users 
ADD COLUMN full_name VARCHAR(100) NULL AFTER email;

-- Mettre à jour les utilisateurs existants avec une valeur par défaut
UPDATE admin_users 
SET full_name = CONCAT(UPPER(LEFT(username, 1)), SUBSTRING(username, 2))
WHERE full_name IS NULL;

-- Rendre la colonne obligatoire après la mise à jour
ALTER TABLE admin_users 
MODIFY full_name VARCHAR(100) NOT NULL;

-- Vérification
SELECT id, username, full_name, email FROM admin_users;
