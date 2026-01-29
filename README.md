# Les Multiples de Charlotte

Site web pour Charlotte Goupil - Artiste multi - talents

## 🚀 Installation

1. Clonez le repository
```bash
git clone https://github.com/votre-username/Multiples_Charlotte.git
```

2. Copiez le fichier de configuration
```bash
cp php/db-config.example.php php/db-config.php
```

3. Configurez la base de données dans `php/db-config.php`

4. Importez la base de données
- Créez une base de données MySQL
- Importez le fichier SQL fourni

5. Configurez votre serveur web (Apache/XAMPP)

## 🔧 Configuration de la base de données

Le fichier `php/db-config.php` n'est pas versionné pour des raisons de sécurité.
Utilisez le template `php/db-config.example.php` comme base.

### Environnement local (XAMPP)
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'charlotte_blog');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### Environnement production (O2switch)
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'prefix_multiples_charlotte');
define('DB_USER', 'prefix_charlotte_user');
define('DB_PASS', 'mot_de_passe_fort');
```

## 📦 Technologies utilisées

- PHP
- MySQL
- jQuery
- Bootstrap
- JavaScript

## 🛡️ Sécurité

- Ne jamais commiter `php/db-config.php`
- Ne jamais laisser de fichiers de test (`test-db.php`) sur le serveur de production
- Toujours utiliser des mots de passe forts en production

## 👩‍💻 Développement

Développé par Fabienne Bergès

## 📝 License

Tous droits réservés © 2026
