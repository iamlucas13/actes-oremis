<?php
session_start(); // Démarre la session pour accéder aux variables de session

include '../db.php'; // Inclut le fichier de connexion à la base de données

// Vérifie si l'utilisateur est connecté et s'il a le rôle d'admin ou d'utilisateur
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'user')) {
    header("Location: ../session/login"); // Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
    exit;
}

$user_role = $_SESSION['role']; // Récupère le rôle de l'utilisateur à partir de la session

// Initialisation des variables pour stocker les résultats des requêtes SQL
$act_types_result = [];
$categories_result = [];
$instance_type_result = [];
$users_result = [];

// Récupère les types d'actes depuis la base de données
try {
    $act_types_stmt = $conn->prepare("SELECT * FROM act_types");
    $act_types_stmt->execute();
    $act_types_result = $act_types_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erreur lors de la récupération des types d'actes: " . $e->getMessage());
}

// Récupère les catégories depuis la base de données
try {
    $categories_stmt = $conn->prepare("SELECT * FROM category");
    $categories_stmt->execute();
    $categories_result = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erreur lors de la récupération des catégories: " . $e->getMessage());
}

// Récupère les types d'instances depuis la base de données
try {
    $instance_type_stmt = $conn->prepare("SELECT * FROM instance_type");
    $instance_type_stmt->execute();
    $instance_type_result = $instance_type_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erreur lors de la récupération des types d'instances: " . $e->getMessage());
}

// Récupère les utilisateurs depuis la base de données
try {
    $users_stmt = $conn->prepare("SELECT * FROM users");
    $users_stmt->execute();
    $users_result = $users_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erreur lors de la récupération des utilisateurs: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Panneau d'administration</title>
    <!-- Inclut Bootstrap pour le style et la mise en page -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <?php include('../header.php') ?> <!-- Inclut l'en-tête du site -->

    <div class="container mt-5">
        <?php if (isset($_GET['message'])): ?>
            <!-- Affiche un message de succès ou d'erreur après une action (par exemple, ajout ou suppression) -->
            <div class="alert <?php echo $_GET['success'] == 1 ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_GET['message']); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <h1>Panneau d'administration</h1>
        
        <!-- Barre de navigation pour les différentes sections de l'administration -->
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
                <a class="nav-link" id="instance_type-tab" data-toggle="tab" href="#instance_type" role="tab"
                    aria-controls="instance_type" aria-selected="false">Types d'instances</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="users-tab" data-toggle="tab" href="#users" role="tab" aria-controls="users"
                    aria-selected="false">Utilisateurs</a>
            </li>
        </ul>

        <!-- Contenu des différents onglets -->
        <div class="tab-content" id="adminTabContent">
            <!-- Onglet des types d'actes -->
            <div class="tab-pane fade show active" id="act-types" role="tabpanel" aria-labelledby="act-types-tab">
                <?php include 'form_act_types.php'; ?> <!-- Formulaire pour ajouter un type d'acte -->
                <h3 class="mt-3">Types d'actes existants:</h3>
                <ul class="list-group">
                    <?php foreach ($act_types_result as $row) { ?>
                        <!-- Liste les types d'actes existants -->
                        <li class="list-group-item"><?php echo htmlspecialchars($row['name']); ?></li>
                    <?php } ?>
                </ul>
            </div>

            <!-- Onglet des catégories -->
            <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                <?php include 'form_categories.php'; ?> <!-- Formulaire pour ajouter une catégorie -->
                <h3 class="mt-3">Catégories existantes:</h3>
                <ul class="list-group">
                    <?php foreach ($categories_result as $row) { ?>
                        <!-- Liste les catégories existantes -->
                        <li class="list-group-item"><?php echo htmlspecialchars($row['name']); ?></li>
                    <?php } ?>
                </ul>
            </div>

            <!-- Onglet des types d'instances -->
            <div class="tab-pane fade" id="instance_type" role="tabpanel" aria-labelledby="instance_type-tab">
                <?php include 'form_instance_types.php'; ?> <!-- Formulaire pour ajouter un type d'instance -->
                <h3 class="mt-3">Types d'instances existants:</h3>
                <ul class="list-group">
                    <?php foreach ($instance_type_result as $row) { ?>
                        <!-- Liste les types d'instances existants -->
                        <li class="list-group-item"><?php echo htmlspecialchars($row['name']); ?></li>
                    <?php } ?>
                </ul>
            </div>

            <!-- Onglet des utilisateurs -->
            <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
                <?php include 'form_users.php'; ?> <!-- Formulaire pour ajouter un utilisateur -->
                <h3 class="mt-3">Utilisateurs existants:</h3>
                <table class="table table">
                    <thead>
                        <tr>
                            <th>Nom d'utilisateur</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <?php if ($user_role === 'admin') { ?>
                                <th>Action</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users_result as $row) { ?>
                            <!-- Liste les utilisateurs existants avec options d'édition et de suppression -->
                            <tr>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['role']); ?></td>
                                <?php if ($user_role === 'admin') { ?>
                                    <td>
                                        <!-- Bouton pour modifier un utilisateur -->
                                        <button class="btn btn-primary edit-user-btn" data-toggle="modal"
                                            data-target="#editUserModal" data-id="<?php echo $row['id']; ?>"
                                            data-username="<?php echo htmlspecialchars($row['username']); ?>"
                                            data-role="<?php echo htmlspecialchars($row['role']); ?>"
                                            data-email="<?php echo htmlspecialchars($row['email']); ?>">
                                            Modifier
                                        </button>
                                        <!-- Bouton pour supprimer un utilisateur -->
                                        <button type="button" class="btn btn-danger btn-sm delete-user-btn" data-id="<?php echo $row['id']; ?>">Supprimer</button>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <a href="../index" class="btn btn-secondary mt-3">Retour</a> <!-- Bouton pour retourner à l'index -->
        <?php include '../footer.php'; ?> <!-- Inclut le pied de page du site -->
    </div>

    <script>
        $(document).ready(function() {
            // Gestion des onglets actifs : mémorise et restaure l'onglet actif après rechargement de la page
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                $('#adminTab a[href="' + activeTab + '"]').tab('show');
            } else {
                $('#adminTab a:first').tab('show');
            }

            $('#adminTab a').on('shown.bs.tab', function(e) {
                var tabId = $(e.target).attr('href');
                localStorage.setItem('activeTab', tabId);
            });

            // Gestion de l'édition des utilisateurs : remplit le formulaire de modification avec les données de l'utilisateur sélectionné
            $('.edit-user-btn').on('click', function() {
                var userId = $(this).data('id');
                var username = $(this).data('username');
                var email = $(this).data('email');
                var role = $(this).data('role');

                $('#editUserId').val(userId);
                $('#editUsername').val(username);
                $('#editEmail').val(email);
                $('#editRole').val(role);
            });

            // Gestion de la suppression des utilisateurs : envoie une requête AJAX pour supprimer l'utilisateur
            $('.delete-user-btn').on('click', function() {
                if (confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?")) {
                    var userId = $(this).data('id');

                    // Envoi de la requête AJAX pour supprimer l'utilisateur
                    $.post('actions.php', {
                        delete_user_id: userId
                    }, function(response) {
                        try {
                            var data = JSON.parse(response);
                            alert(data.message);
                            if (data.success) {
                                location.reload(); // Recharger la page si la suppression est réussie
                            }
                        } catch (e) {
                            console.error("Erreur de traitement de la réponse:", e, response);
                        }
                    }).fail(function() {
                        alert("Erreur lors de la suppression de l'utilisateur.");
                    });
                }
            });
        });
    </script>

  
<?php include 'edit_user_modal.php'; ?> <!-- Inclut le modal Modifier l'utilisateur -->

</body>

</html>

<?php
$conn = null; // Ferme la connexion à la base de données
?>
