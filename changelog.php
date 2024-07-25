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
        <div class="changelog-section">
            <h3>Version 0.3a <small class="text-muted">- 22 juillet 2024</small></h3>
            <ul>
                <li>Modification de l'ordre d'affichage</li>
                <li>Correction du code CSS</li>
                <li>FIX du type d'instance (seul l'ID était affiché)</li>
            </ul>
        </div>
        <div class="changelog-section">
            <h3>Version 0.2a <small class="text-muted">- 21 juillet 2024</small></h3>
            <ul>
                <li>Ajout de permissions utilisateurs</li>
                <li>Ajout d'un modal d'édition des utilisateurs</li>
                <li>FIX du type d'instance</li>
            </ul>
        </div>
        <div class="changelog-section">
            <h3>Version 0.1a <small class="text-muted">- 18 juillet 2024</small></h3>
            <ul>
                <li>Fonctionnalités principales de gestion des documents ajoutées.</li>
                <li>Interface utilisateur de base mise en place.</li>
            </ul>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>