<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Changelog - OREMIS</title>
    <!-- Inclure Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inclure le fichier CSS personnalisé -->
    <link href="css/style.css" rel="stylesheet">
    <!-- Inclure jQuery et Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .changelog-header {
            background-color: white;
            color: black;
            padding: 20px 0;
            text-align: center;
        }

        .changelog-section {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
            padding: 20px;
        }

        .changelog-section h3 {
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
    </style>
</head>

<body>
    <?php include ('header.php') ?>
    <!-- Changelog Header -->
    <div class="changelog-header">
        <h1>Changelog</h1>
        <p>Suivez les dernières mises à jour et modifications de l'application actes.oremis.fr</p>
    </div>

    <!-- Changelog Content -->
    <div class="container">
        <?php
        // Lire le contenu du fichier CHANGELOG.md
        $changelog = file_get_contents('CHANGELOG.md');

        // Diviser le contenu en sections par version
        $sections = preg_split('/^## /m', $changelog, -1, PREG_SPLIT_NO_EMPTY);

        // Afficher chaque section
        foreach ($sections as $section) {
            // Extraire le titre de la version
            if (preg_match('/^(.*?)(\r?\n)+/', $section, $matches)) {
                $versionTitle = $matches[1];
                $sectionContent = str_replace($matches[0], '', $section);

                echo '<div class="changelog-section">';
                echo '<h3>' . htmlspecialchars($versionTitle) . '</h3>';
                echo '<ul>';

                // Diviser le contenu de la section en éléments de liste
                $items = preg_split('/^- /m', $sectionContent, -1, PREG_SPLIT_NO_EMPTY);
                foreach ($items as $item) {
                    echo '<li>' . htmlspecialchars(trim($item)) . '</li>';
                }

                echo '</ul>';
                echo '</div>';
            }
        }
        ?>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>
