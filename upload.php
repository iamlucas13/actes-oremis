<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit;
}

include 'db.php';

// Générer un token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Vérifiez si le répertoire "uploads" existe, sinon le créer
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérifier le token CSRF
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        echo "Erreur CSRF.";
        exit;
    }

    // Assainir les entrées
    $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
    $category_id = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
    $act_type_id = filter_var($_POST['act_type'], FILTER_SANITIZE_NUMBER_INT);
    $instance_type_id = filter_var($_POST['instance_type'], FILTER_SANITIZE_NUMBER_INT);
    $act_date = htmlspecialchars($_POST['act_date'], ENT_QUOTES, 'UTF-8');

    // Validation du fichier
    $filename = basename($_FILES['file']['name']);
    $filetype = mime_content_type($_FILES['file']['tmp_name']);
    $filesize = $_FILES['file']['size'];
    $target_dir = "uploads/";
    $unique_name = uniqid() . "-" . $filename;
    $target_file = $target_dir . $unique_name;

    $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
    if ($file_ext !== 'pdf' || $filetype !== 'application/pdf') {
        echo "Seuls les fichiers PDF sont autorisés.";
    } elseif ($filesize > 10485760) { // Limitez la taille du fichier à 10 MB
        echo "Le fichier est trop volumineux. La taille maximale est de 10 MB.";
    } elseif (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
        // Utiliser une requête préparée pour éviter les injections SQL
        $stmt = $conn->prepare("INSERT INTO documents (title, description, category_id, act_type_id, instance_type, act_date, filename) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $category_id, $act_type_id, $instance_type_id, $act_date, $unique_name]);
        if ($stmt->rowCount()) {
            echo "Le document a été téléchargé avec succès.";

            // Récupérer le nom de la catégorie
            $stmtCategory = $conn->prepare("SELECT name FROM category WHERE id = ?");
            $stmtCategory->execute([$category_id]);
            $category_name = $stmtCategory->fetchColumn();

            // Récupérer le nom du type d'acte
            $stmtActType = $conn->prepare("SELECT name FROM act_types WHERE id = ?");
            $stmtActType->execute([$act_type_id]);
            $act_type_name = $stmtActType->fetchColumn();

            // Vérifier si l'utilisateur souhaite envoyer une notification
            if (isset($_POST['send_notification']) && $_POST['send_notification'] == '1') {
                // URL du Webhook Discord
                $webhookUrl = "https://discord.com/api/webhooks/1272097374315221013/oyMhSIV23Lv46WEc0kzNTCZoisXP-sgz_17YHhq_o4jWOVL6LpZv6GkEK3laFTs7GbAb";

                // Mise en forme de la date
                $formatted_date = DateTime::createFromFormat('Y-m-d', $act_date)->format('d/m/Y');

                // Message à envoyer sur Discord
                $message = ":scroll: **- OREMIS -** _Un nouvel acte administratif a été publié_ :scroll:\n\n" .
                    "-----------------------------------\n" .
                    ":label: **Identifiant de l'acte :** " . html_entity_decode($title, ENT_QUOTES, 'UTF-8') . "\n" .
                    ":page_facing_up: **Type d'acte :** " . htmlspecialchars($act_type_name, ENT_QUOTES, 'UTF-8') . "\n" .
                    ":file_folder: **Catégorie :** " . htmlspecialchars($category_name, ENT_QUOTES, 'UTF-8') . "\n" .
                    ":pencil: **Description :** " . html_entity_decode($description, ENT_QUOTES, 'UTF-8') . "\n" .
                    ":calendar: **Date de l'acte :** " . htmlspecialchars($formatted_date, ENT_QUOTES, 'UTF-8') . "\n \n" .
                    ":link: **L'ensemble des actes sont disponible ici :** [actes.oremis.fr](https://actes.oremis.fr)\n" .
                    "-----------------------------------\n\n";

                    function sendDiscordNotification($webhookUrl, $message)
                    {
                        $json_data = json_encode([
                            "content" => $message
                        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        
                        $ch = curl_init($webhookUrl);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
                        $response = curl_exec($ch);
                        curl_close($ch);
        
                        return $response;
                    }
        

                // Envoi de la notification
                sendDiscordNotification($webhookUrl, $message);
            }

        } else {
            echo "Erreur lors de l'insertion du document.";
        }
    } else {
        echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
    }
}

// Récupérer les catégories pour le formulaire
$categories_result = $conn->query("SELECT * FROM category");
$act_types_result = $conn->query("SELECT * FROM act_types");
$instance_type_result = $conn->query("SELECT * FROM instance_type");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upload Document</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            // Masquer le conteneur de type d'instance au chargement de la page
            $('#instance_type_container').hide();

            // Gestion de l'affichage en fonction du type d'acte sélectionné
            $('#act_type').change(function () {
                var selectedText = $('#act_type').find('option:selected').text().toLowerCase();
                if (selectedText === 'procès verbal' || selectedText === 'délibération') {
                    $('#instance_type_container').show();
                } else {
                    $('#instance_type_container').hide();
                }
            });

            // Vérifier au chargement si une option est déjà sélectionnée
            if ($('#act_type').find('option:selected').text().toLowerCase() === 'procès verbal') {
                $('#instance_type_container').show();
            }

            // Ajouter une confirmation avant l'envoi du formulaire
            $('form').on('submit', function (e) {
                var confirmation = confirm("Une fois publié, aucun élément légal ne peut être édité. Voulez-vous continuer ?");
                if (!confirmation) {
                    e.preventDefault(); // Empêche l'envoi du formulaire si l'utilisateur clique sur Annuler
                }
            });
        });
    </script>
</head>

<body>
    <div class="container mt-5">
        <h1>Ajouter un document</h1>
        <form action="upload" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="form-group">
                <label for="title">Titre:</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="category">Catégorie:</label>
                <select name="category" id="category" class="form-control" required>
                    <?php while ($row = $categories_result->fetch(PDO::FETCH_ASSOC)) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="act_type">Type d'acte:</label>
                <select name="act_type" id="act_type" class="form-control" required>
                    <?php while ($row = $act_types_result->fetch(PDO::FETCH_ASSOC)) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group" id="instance_type_container">
                <label for="instance_type">Type d'instance:</label>
                <select name="instance_type" id="instance_type" class="form-control" required>
                    <?php while ($row = $instance_type_result->fetch(PDO::FETCH_ASSOC)) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="act_date">Date de l'acte:</label>
                <input type="date" name="act_date" id="act_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="file">Fichier PDF:</label>
                <input type="file" name="file" id="file" class="form-control-file" accept="application/pdf" required>
            </div>
            <div class="form-group">
                <input type="checkbox" name="send_notification" id="send_notification" value="1">
                <label for="send_notification">Envoyer une notification Discord</label>
            </div>
            <button type="submit" class="btn btn-danger">Publier l'acte administratif</button>
        </form>
        <a href="index" class="btn btn-secondary mt-3">Retour à la liste des documents</a>
    </div>
</body>

</html>
<?php
$conn = null;
?>
