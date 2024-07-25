<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'user')) {
    header("Location: ../session/login");
    exit;
}

include '../db.php';

if (isset($_SESSION['user_id'])) {
    $user_role = $_SESSION['role'];
}

// Gestion des types d'actes
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['act_type'])) {
    $act_type = $_POST['act_type'];
    $stmt = $conn->prepare("INSERT INTO act_types (name) VALUES (:act_type)");
    $stmt->bindParam(':act_type', $act_type);
    if ($stmt->execute()) {
        echo "Nouveau type d'acte ajouté avec succès.";
    } else {
        echo "Erreur: " . $stmt->errorInfo()[2];
    }
}

// Gestion des types d'instances
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['instance_type'])) {
    $instance_type = $_POST['instance_type'];
    $stmt = $conn->prepare("INSERT INTO instance_type (name) VALUES (:instance_type)");
    $stmt->bindParam(':instance_type', $instance_type);
    if ($stmt->execute()) {
        echo "Nouveau type d'instance ajouté avec succès.";
    } else {
        echo "Erreur: " . $stmt->errorInfo()[2];
    }
}

// Gestion des catégories
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category'])) {
    $category = $_POST['category'];
    $stmt = $conn->prepare("INSERT INTO category (name) VALUES (:category)");
    $stmt->bindParam(':category', $category);
    if ($stmt->execute()) {
        echo "Nouvelle catégorie ajoutée avec succès.";
    } else {
        echo "Erreur: " . $stmt->errorInfo()[2];
    }
}

// Gestion des utilisateurs
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);
    if ($stmt->execute()) {
        echo "Nouvel utilisateur ajouté avec succès.";
    } else {
        echo "Erreur: " . $stmt->errorInfo()[2];
    }
}

// Récupérer les types d'actes, les catégories et les utilisateurs pour affichage
$act_types_stmt = $conn->prepare("SELECT * FROM act_types");
$act_types_stmt->execute();
$act_types_result = $act_types_stmt->fetchAll(PDO::FETCH_ASSOC);

$categories_stmt = $conn->prepare("SELECT * FROM category");
$categories_stmt->execute();
$categories_result = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);

$instance_type_stmt = $conn->prepare("SELECT * FROM instance_type");
$instance_type_stmt->execute();
$instance_type_result = $instance_type_stmt->fetchAll(PDO::FETCH_ASSOC);

$users_stmt = $conn->prepare("SELECT * FROM users");
$users_stmt->execute();
$users_result = $users_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Panneau d'administration</title>
    <!-- Inclure Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inclure jQuery et Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <?php include ('../header.php') ?>
    <div class="container mt-5">
        <h1>Panneau d'administration</h1>
        <ul class="nav nav-tabs" id="adminTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="act-types-tab" data-toggle="tab" href="#act-types" role="tab"
                    aria-controls="act-types" aria-selected="true">Types d'actes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="categories-tab" data-toggle="tab" href="#categories" role="tab"
                    aria-controls="categories" aria-selected="false">Catégories</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" id="categories-tab" data-toggle="tab" href="#instance_type" role="tab"
                    aria-controls="instance_type" aria-selected="false">Type d'instance (soon)</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="users-tab" data-toggle="tab" href="#users" role="tab" aria-controls="users"
                    aria-selected="false">Utilisateurs</a>
            </li>
        </ul>
        <div class="tab-content" id="adminTabContent">
            <div class="tab-pane fade show active" id="act-types" role="tabpanel" aria-labelledby="act-types-tab">
                <?php if ($user_role === 'admin') { ?>
                    <h2 class="mt-3">Types d'actes</h2>
                    <form action="admin.php" method="post">
                        <div class="form-group">
                            <label for="act_type">Ajouter un nouveau type d'acte:</label>
                            <input type="text" name="act_type" id="act_type" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </form>
                <?php } ?>
                <h3 class="mt-3">Types d'actes existants:</h3>
                <ul class="list-group">
                    <?php foreach ($act_types_result as $row) { ?>
                        <li class="list-group-item"><?php echo htmlspecialchars($row['name']); ?></li>
                    <?php } ?>
                </ul>
            </div>

            <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                <?php if ($user_role === 'admin') { ?>
                    <h2 class="mt-3">Catégories</h2>
                    <form action="admin.php" method="post">
                        <div class="form-group">
                            <label for="category">Ajouter une nouvelle catégorie:</label>
                            <input type="text" name="category" id="category" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </form>
                <?php } ?>
                <h3 class="mt-3">Catégories existantes:</h3>
                <ul class="list-group">
                    <?php foreach ($categories_result as $row) { ?>
                        <li class="list-group-item"><?php echo htmlspecialchars($row['name']); ?></li>
                    <?php } ?>
                </ul>
            </div>

            <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
                <?php if ($user_role === 'admin') { ?>
                    <h2 class="mt-3">Utilisateurs</h2>
                    <form action="admin.php" method="post">
                        <div class="form-group">
                            <label for="username">Nom d'utilisateur:</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Mot de passe:</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Rôle:</label>
                            <select name="role" id="role" class="form-control" required>
                                <option value="admin">Administrateur</option>
                                <option value="user">Utilisateur</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </form>
                <?php } ?>
                <h3 class="mt-3">Utilisateurs existants:</h3>
                <table class="table table">
                    <thead>
                        <tr>
                            <th>Nom d'utilisateur</th>
                            <?php if ($user_role === 'admin') { ?>
                                <th>Action</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users_result as $row) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['username']); ?>
                                    (<?php echo htmlspecialchars($row['role']); ?>)</td>
                                <?php if ($user_role === 'admin') { ?>
                                    <td>
                                        <button class="btn btn-primary edit-user-btn" data-toggle="modal"
                                            data-target="#editUserModal" data-id="<?php echo $row['id']; ?>"
                                            data-username="<?php echo htmlspecialchars($row['username']); ?>"
                                            data-role="<?php echo htmlspecialchars($row['role']); ?>">
                                            Modifier
                                        </button>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <a href="../index" class="btn btn-secondary mt-3">Retour</a>
    </div>
    <!-- Modal pour l'édition des utilisateurs -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Modifier l'utilisateur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" action="edit.php" method="post">
                        <input type="hidden" name="id" id="editUserId">
                        <div class="form-group">
                            <label for="editUsername">Nom d'utilisateur</label>
                            <input type="text" class="form-control" name="username" id="editUsername" required>
                        </div>
                        <div class="form-group">
                            <label for="editPassword">Mot de passe</label>
                            <input type="password" class="form-control" name="password" id="editPassword">
                            <small>Laissez vide si vous ne souhaitez pas modifier le mot de passe</small>
                        </div>
                        <div class="form-group">
                            <label for="editRole">Rôle</label>
                            <select class="form-control" name="role" id="editRole" required>
                                <option value="admin">Administrateur</option>
                                <option value="user">Utilisateur</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.edit-user-btn').on('click', function () {
                var userId = $(this).data('id');
                var username = $(this).data('username');
                var role = $(this).data('role');

                $('#editUserId').val(userId);
                $('#editUsername').val(username);
                $('#editRole').val(role);
            });
        });
    </script>
    <?php include '../footer.php'; ?>
</body>

</html>
<?php
$conn = null;
?>