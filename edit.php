<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit;
}

include 'db.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM documents WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$document = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer les catégories pour le formulaire
$categories_stmt = $conn->prepare("SELECT * FROM category");
$categories_stmt->execute();
$categories_result = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
    $category_id = htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8');
    $act_type_id = htmlspecialchars($_POST['act_type'], ENT_QUOTES, 'UTF-8');
    $act_date = htmlspecialchars($_POST['act_date'], ENT_QUOTES, 'UTF-8');
    $instance_type_id = htmlspecialchars($_POST['instance_type'], ENT_QUOTES, 'UTF-8');
    $filename = $document['filename']; // Garder le même fichier si non modifié

    if (!empty($_FILES['file']['name'])) {
        $filename = htmlspecialchars($_FILES['file']['name'], ENT_QUOTES, 'UTF-8');
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($filename);
        move_uploaded_file($_FILES['file']['tmp_name'], $target_file);
    }

    $stmt = $conn->prepare("UPDATE documents SET title = :title, description = :description, category_id = :category_id, instance_type = :instance_type_id, act_type_id = :act_type_id, act_date = :act_date, filename = :filename WHERE id = :id");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':act_type_id', $act_type_id);
    $stmt->bindParam(':act_date', $act_date);
    $stmt->bindParam(':instance_type_id', $instance_type_id);
    $stmt->bindParam(':filename', $filename);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: index");
        exit;
    } else {
        echo "Erreur: " . $stmt->errorInfo()[2];
    }
}

// Récupérer les types d'actes et les types d'instances pour le formulaire
$act_types_stmt = $conn->prepare("SELECT * FROM act_types");
$act_types_stmt->execute();
$act_types_result = $act_types_stmt->fetchAll(PDO::FETCH_ASSOC);

$instance_type_stmt = $conn->prepare("SELECT * FROM instance_type");
$instance_type_stmt->execute();
$instance_type_result = $instance_type_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Modifier Document</title>
    <!-- Inclure Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inclure jQuery et Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#act_type').change(function () {
                if ($(this).val() === '2') { // Assuming 2 is the ID for "procès verbal"
                    $('#instance_type_container').show();
                } else {
                    $('#instance_type_container').hide();
                }
            });
            if ($('#act_type').val() !== '2') { // Assuming 2 is the ID for "procès verbal"
                $('#instance_type_container').hide();
            }
        });
    </script>
</head>

<body>
    <?php include('header.php') ?>
    <div class="container mt-5">
        <h1>Modifier le document</h1>
        <form action="edit?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Titre:</label>
                <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($document['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control" required><?php echo htmlspecialchars($document['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="category">Catégorie:</label>
                <select name="category" id="category" class="form-control" required>
                    <?php foreach ($categories_result as $row) { ?>
                        <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $document['category_id']) echo 'selected'; ?>><?php echo htmlspecialchars($row['name']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="act_type">Type d'acte:</label>
                <select name="act_type" id="act_type" class="form-control" required>
                    <?php foreach ($act_types_result as $row) { ?>
                        <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $document['act_type_id']) echo 'selected'; ?>><?php echo htmlspecialchars($row['name']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group" id="instance_type_container">
                <label for="instance_type">Type d'instance:</label>
                <select name="instance_type" id="instance_type" class="form-control" required>
                    <?php foreach ($instance_type_result as $row) { ?>
                        <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $document['instance_type']) echo 'selected'; ?>><?php echo htmlspecialchars($row['name']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="act_date">Date de l'acte:</label>
                <input type="date" name="act_date" id="act_date" class="form-control" value="<?php echo htmlspecialchars($document['act_date']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
        <a href="index" class="btn btn-secondary mt-3">Retour à la liste des documents</a>
    </div>
</body>

</html>
<?php
$conn = null;
?>
