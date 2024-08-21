<?php
// Obtenir le chemin absolu vers le fichier CHANGELOG.md en utilisant __DIR__
$changelog_path = __DIR__ . '/CHANGELOG.md';

$show_beta_alert = false;

if (file_exists($changelog_path)) {
    // Lire le contenu du fichier CHANGELOG.md
    $changelog = file_get_contents($changelog_path);

    // Extraire la première section (dernière version)
    $sections = preg_split('/^## /m', $changelog, -1, PREG_SPLIT_NO_EMPTY);

    // Vérifier si "beta" est présent dans la version ou juste après la version
    if (!empty($sections) && preg_match('/\bbeta\b/i', $sections[0])) {
        $show_beta_alert = true;
    }

    $latest_version = '';
    if (!empty($sections) && preg_match('/^Version\s+(\S+)/', $sections[0], $matches)) {
        $latest_version = trim($matches[1]);

        // Vérifier si "beta" est présent dans la version
        if (preg_match('/\bbeta\b/i', $sections[0])) {
            $latest_version .= ' (beta)';
        }
    }
}
?>

<?php if ($show_beta_alert): ?>
    <div class="alert alert-danger" role="alert">
        <strong>Version actuelle : <?php echo htmlspecialchars($latest_version); ?></strong><br>
        Vous utilisez une version béta de notre application. Certaines fonctionnalités peuvent ne pas
        fonctionner comme prévu.
    </div>
<?php endif; ?>