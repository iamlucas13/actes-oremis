<?php
if (file_exists(__DIR__ . '/env.php')) {
    echo "L'application est déjà installée.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $org_name = htmlspecialchars($_POST['org_name']);
    $org_email = htmlspecialchars($_POST['org_email']);
    $org_website = htmlspecialchars($_POST['org_website']);
    $db_host = htmlspecialchars($_POST['db_host']);
    $db_user = htmlspecialchars($_POST['db_user']);
    $db_pass = htmlspecialchars($_POST['db_pass']);
    $db_name = htmlspecialchars($_POST['db_name']);
    $google_client_id = htmlspecialchars($_POST['google_client_id']);
    $google_client_secret = htmlspecialchars($_POST['google_client_secret']);
    $redirect_uri = htmlspecialchars($_POST['redirect_uri']);
    $webhook_url = htmlspecialchars($_POST['webhook_url']);
    $webhook_website_link = htmlspecialchars($_POST['webhook_website_link']);

    $env_content = "<?php\n";
    $env_content .= "define('ORG_NAME', '$org_name');\n";
    $env_content .= "define('ORG_EMAIL', '$org_email');\n";
    $env_content .= "define('ORG_WEBSITE', '$org_website');\n";
    $env_content .= "define('DB_HOST', '$db_host');\n";
    $env_content .= "define('DB_USER', '$db_user');\n";
    $env_content .= "define('DB_PASS', '$db_pass');\n";
    $env_content .= "define('DB_NAME', '$db_name');\n";
    $env_content .= "define('GOOGLE_CLIENT_ID', '$google_client_id');\n";
    $env_content .= "define('GOOGLE_CLIENT_SECRET', '$google_client_secret');\n";
    $env_content .= "define('REDIRECT_URI', '$redirect_uri');\n";
    $env_content .= "define('WEBHOOK_URL', '$webhook_url');\n";
    $env_content .= "define('WEBHOOK_WEBSITE_LINK', '$webhook_website_link');\n";

    file_put_contents(__DIR__ . '/env.php', $env_content);

    try {
        // Connexion à la base de données avec PDO
        $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $pdo = new PDO($dsn, $db_user, $db_pass, $options);

        // Créer les tables nécessaires
        $sql = "
        CREATE TABLE IF NOT EXISTS act_types (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        
        CREATE TABLE IF NOT EXISTS category (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        
        CREATE TABLE IF NOT EXISTS instance_type (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        
        CREATE TABLE IF NOT EXISTS documents (
            id INT NOT NULL AUTO_INCREMENT,
            title VARCHAR(150) NOT NULL,
            description TEXT NOT NULL,
            filename VARCHAR(150) NOT NULL,
            category_id INT DEFAULT NULL,
            act_type VARCHAR(50) DEFAULT NULL,
            act_date DATE DEFAULT NULL,
            act_type_id INT DEFAULT NULL,
            instance_type INT DEFAULT NULL,
            confidential TINYINT DEFAULT '0',
            PRIMARY KEY (id),
            KEY category_id (category_id),
            KEY instance_type (instance_type),
            CONSTRAINT documents_ibfk_1 FOREIGN KEY (category_id) REFERENCES category (id),
            CONSTRAINT instance_type FOREIGN KEY (instance_type) REFERENCES instance_type (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        
        CREATE TABLE IF NOT EXISTS users (
            id INT NOT NULL AUTO_INCREMENT,
            username VARCHAR(50) NOT NULL,
            password VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
            role ENUM('admin','user') NOT NULL DEFAULT 'user',
            email VARCHAR(255) DEFAULT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY username (username)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        
        -- Insérer des données initiales
        INSERT INTO category (name) VALUES ('Exemple Catégorie');
        INSERT INTO act_types (name) VALUES ('Exemple Act Type');
        INSERT INTO instance_type (name) VALUES ('Exemple Instance Type');
        INSERT INTO users (username, password, role, email) VALUES ('admin', '" . password_hash('admin', PASSWORD_BCRYPT) . "', 'admin', 'admin@example.com');
        ";

        $pdo->exec($sql);

        echo "Tables créées et données initiales insérées avec succès.";
    } catch (PDOException $e) {
        echo "Erreur lors de la connexion à la base de données ou de la création des tables: " . $e->getMessage();
    }

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation de l'application</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <h2>Configuration de l'application</h2>
        <form action="install.php" method="post">
            <div class="form-group">
                <label for="org_name">Nom de l'organisation:</label>
                <input type="text" name="org_name" id="org_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="org_email">Email de l'organisation:</label>
                <input type="email" name="org_email" id="org_email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="org_website">Site web de l'organisation:</label>
                <input type="url" name="org_website" id="org_website" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="db_host">Hôte de la base de données:</label>
                <input type="text" name="db_host" id="db_host" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="db_user">Utilisateur de la base de données:</label>
                <input type="text" name="db_user" id="db_user" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="db_pass">Mot de passe de la base de données:</label>
                <input type="password" name="db_pass" id="db_pass" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="db_name">Nom de la base de données:</label>
                <input type="text" name="db_name" id="db_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="google_client_id">Google Client ID:</label>
                <input type="text" name="google_client_id" id="google_client_id" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="google_client_secret">Google Client Secret:</label>
                <input type="text" name="google_client_secret" id="google_client_secret" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="redirect_uri">URI de redirection:</label>
                <input type="url" name="redirect_uri" id="redirect_uri" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="webhook_url">URL du webhook:</label>
                <input type="url" name="webhook_url" id="webhook_url" class="form-control">
            </div>
            <div class="form-group">
                <label for="webhook_website_link">Lien du site web du webhook:</label>
                <input type="url" name="webhook_website_link" id="webhook_website_link" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Installer</button>
        </form>
    </div>
</body>

</html>