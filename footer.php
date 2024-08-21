<?php

require_once __DIR__ . '/env.php';

// Obtenir le chemin absolu vers le fichier CHANGELOG.md en utilisant __DIR__
$changelog_path = __DIR__ . '/CHANGELOG.md';

if (file_exists($changelog_path)) {
    // Lire le contenu du fichier CHANGELOG.md
    $changelog = file_get_contents($changelog_path);

    // Extraire la première section (dernière version)
    $sections = preg_split('/^## /m', $changelog, -1, PREG_SPLIT_NO_EMPTY);

    // Extraire le numéro de la dernière version (uniquement)
    $latest_version = '';
    if (!empty($sections) && preg_match('/^Version\s+(\S+)/', $sections[0], $matches)) {
        $latest_version = trim($matches[1]);

        // Vérifier si "beta" est présent dans la version
        if (preg_match('/\bbeta\b/i', $sections[0])) {
            $latest_version .= ' (beta)';
        }
    }
} else {
    // Gérer le cas où le fichier CHANGELOG.md est introuvable
    $latest_version = 'N/A';
}
?>

<!-- Footer -->
<footer class="mt-4">
    <div class="container-custom mt-5">
        <div class="row">
            <div class="col-md-6">
                <h5 class="text-uppercase font-weight-bold">Association <?php echo ORG_NAME; ?></h5>
                <ul class="list-unstyled">
                    <li><a href="changelog" class="text-dark">Changelog</a></li>
                    <li><span class="text-dark">Dernière version : <?php echo htmlspecialchars($latest_version); ?></span></li>
                </ul>
            </div>
            <div class="col-md-6 text-md-right">
                <h5 class="text-uppercase font-weight-bold">Contact</h5>
                <ul class="list-unstyled">
                    <li><a href="mailto:<?php echo ORG_EMAIL; ?>" class="text-dark"><?php echo ORG_EMAIL; ?></a></li>
                    <li><a href="<?php echo ORG_WEBSITE; ?>" target="_blank" class="text-dark">Notre site internet</a></li>
                </ul>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col text-center">
                <a href="https://facebook.com" target="_blank" class="text-dark mx-2"><i class="bi bi-facebook"></i></a>
                <a href="https://twitter.com" target="_blank" class="text-dark mx-2"><i class="bi bi-instagram"></i></a>
                <a href="https://linkedin.com" target="_blank" class="text-dark mx-2"><i class="bi bi-linkedin"></i></a>
                <p>© <?php echo date("Y"); ?> OREMIS. Tous droits réservés.</p>
            </div>
        </div>
    </div>
</footer>
