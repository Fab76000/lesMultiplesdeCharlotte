<footer class="footer container-fluid">
    <div class="footer-content">
        <p>Copyright © <?php echo date('Y'); ?> - Tous droits réservés</p>
        <p><a href="politiqueConfidentialite.php">Politique de confidentialité</a></p>
        <p><a href="mentionsLegales.php">Mentions légales</a></p>
    </div>
    <div class="Logo md-6 col-auto">
        <a href="https://piaille.fr/@LesMultiples2Charlotte" aria-label="Lien vers ma page Mastodon" title="Lien vers ma page Mastodon">
            <picture>
                <source srcset="images/Mastodon-logo.webp" type="image/webp" media="(max-width: 674px)">
                <source srcset="images/Mastodon-logo.png" type="image/png">
                <img class="lazy-image logo" src="images/Mastodon-logo.png" alt="Logo Mastodon" loading="lazy">
            </picture>
        </a>
        <a href="https://www.instagram.com/chafoxil/" aria-label="Lien vers ma page Instagram" title="Lien vers ma page Instagram">
            <picture>
                <source srcset="images/instagram-Logo-PNG-Transparent-Background-download_500.webp" type="image/webp" media="(max-width: 674px)">
                <source srcset="images/instagram-Logo-PNG-Transparent-Background-download.png" type="image/png">
                <img class="lazy-image logo" data-src="images/instagram-Logo-PNG-Transparent-Background-download_500.png" alt="Logo Instagram" loading="lazy">
            </picture>
        </a>
    </div>
    <p style="margin-bottom:-10px">Site web développé par Fabienne Bergès
        <?php
        // Charger les fonctions admin
        require_once __DIR__ . '/php/admin-functions.php';

        // Icône visible SEULEMENT aux administrateurs reconnus (connectés ou avec cookie valide)
        if (isRecognizedAdmin()): ?>
            <span style="margin-left: 10px; font-size: 0.8em;">
                <a href="admin/login.php" style="color: #666; text-decoration: none; opacity: 0.7;" title="Administration">⚙</a>
            </span>
        <?php endif; ?>
    </p>
</footer>