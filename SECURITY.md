# Documentation Sécurité

## Tests de sécurité effectués

### En-têtes de sécurité
- [x] X-XSS-Protection
- [x] X-Content-Type-Options  
- [x] X-Frame-Options
- [x] Referrer-Policy
- [x] Strict-Transport-Security ✅ *Implémenté*
- [x] Content-Security-Policy ✅ *Implémenté*

### Tests d'intrusion
- [x] Test Clickjacking
- [x] Scan Trivy
- [x] Audit des en-têtes

### Outils utilisés
- Trivy (scan vulnérabilités)
- Scripts PowerShell personnalisés
- Tests manuels iframe/XSS

### Dernière vérification
- Date: 2025-10-19
- Statut: ✅ Sécurisé
- Score SSL Labs: A+ (à vérifier)

## Checklist déploiement production

### ✅ Fichiers à déployer
- [x] *.php (pages du site)
- [x] *.css, *.js (ressources)
- [x] .htaccess (avec protection fichiers sensibles)
- [x] images/ (médias)

### ❌ Fichiers à NE JAMAIS déployer
- [ ] security-test.html
- [ ] security-check.ps1
- [ ] deploy-secure.ps1
- [ ] Tout fichier debug-* ou test-*
- [ ] Scripts .ps1, .sh, .bat
- [ ] Fichiers .env, .log, .bak

### ⚠️ Fichiers déployés mais protégés par .htaccess
- [x] SECURITY.md (accessible sur GitHub, bloqué en production)
- [x] README.md (si présent)

## Notes
Les scripts et fichiers de test sont exclus du repository pour des raisons de sécurité.