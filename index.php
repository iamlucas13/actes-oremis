<?php
session_start();
include 'db.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT role, username FROM users WHERE id = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_role = $user['role'];
    $username = $user['username'];
} else {
    $user_role = '';
}

$sql = "SELECT documents.*, categories.name AS category_name, act_types.name AS act_type, instance_type.name AS instance_type 
        FROM documents 
        LEFT JOIN categories ON documents.category_id = categories.id 
        LEFT JOIN act_types ON documents.act_type_id = act_types.id 
        LEFT JOIN instance_type ON documents.instance_type = instance_type.id
        ORDER BY documents.act_date DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les catégories pour le formulaire de recherche
$categories_stmt = $conn->prepare("SELECT * FROM categories");
$categories_stmt->execute();
$categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <title>OREMIS Actes administratifs</title>
    <!-- Inclure Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link href="css/style.css" rel="stylesheet">
    <!-- Inclure jQuery et Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Toggle description
        $(document).on('click', '.toggle-btn', function () {
            var id = $(this).data('id');
            $('#description-' + id).toggle();
            if ($(this).text() === '+') {
                $(this).text('-').addClass('red-btn');
            } else {
                $(this).text('+').removeClass('red-btn');
            }
        });

        // Show PDF in modal
        $(document).on('click', '.view-btn', function () {
            var filename = $(this).data('filename');
            $('#pdfModal iframe').attr('src', filename);
        });

        // Script de recherche
        /* $(document).ready(function () {
            $('#search').on('keyup', function () {
                var value = $(this).val().toLowerCase();
                $('#documents-table tbody tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        }); */

    </script>
</head>

<body>
    <?php include ('header.php') ?>
    <div class="container-fluid">
        <div class="container-fluid mt-5">
            <div class="row">
                <div class="col-md-2">
                    <img src="assets/oremis-logo.svg" alt="OREMIS Logo" class="img-fluid"
                        style="max-height: 60px; width: auto;">
                </div>
                <div class="col-md-8 text-center">
                    <h1>Actes administratifs de l'association OREMIS</h1>
                </div>
            </div>
            <div class="text-right">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="session/logout" class="btn btn-secondary">Déconnexion</a>
                    <?php if ($user_role === 'admin' || $user_role === 'user'): ?>
                        <a href="admin/admin" class="btn btn-primary">Administration</a>
                        <p>Bonjour <?php echo htmlspecialchars($username); ?>, <?php echo htmlspecialchars($user_role); ?></p>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="session/login" class="btn btn-primary">Connexion</a>
                <?php endif; ?>
            </div>

            <form method="get" action="index" class="mt-3">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="search">Recherche (soon)</label>
                        <input type="text" id="search" name="search" readonly="readonly" class="form-control" placeholder="Rechercher...">
                    </div>
                </div>
            </form>

            <table id="documents-table" class="table table-striped mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>Nature de l'acte</th>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Instance</th>
                        <th>Date de l'acte</th>
                        <th>Acte</th>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <th>Action (admin)</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($documents)) { ?>
                        <?php foreach ($documents as $document) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($document['act_type']); ?><br><button class="toggle-btn"
                                        data-id="<?php echo $document['id']; ?>">+</button></td>
                                <td><?php echo htmlspecialchars($document['title']); ?></td>
                                <td><?php echo htmlspecialchars($document['category_name']); ?></td>
                                <td><?php echo htmlspecialchars($document['instance_type']); ?></td>
                                <td><?php echo htmlspecialchars($document['act_date']); ?></td>
                                <td><a href="uploads/<?php echo htmlspecialchars($document['filename']); ?>"
                                        target="_blank">Télécharger</a><br><button class="view-btn btn btn-info btn-sm mt-1"
                                        data-toggle="modal" data-target="#pdfModal"
                                        data-filename="uploads/<?php echo htmlspecialchars($document['filename']); ?>">Visualiser</button>
                                </td>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <?php if ($user_role === 'admin') { ?>
                                        <td><a href="edit?id=<?php echo $document['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                                            <a href="delete?id=<?php echo $document['id']; ?>" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document?');">Supprimer</a>
                                        </td>
                                    <?php } else { ?>
                                        <td><a href="edit?id=<?php echo $document['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                                        </td>
                                    <?php } ?>
                                <?php endif; ?>
                            </tr>
                            <tr class="description" id="description-<?php echo $document['id']; ?>" style="display: none;">
                                <td colspan="7"><?php echo htmlspecialchars($document['description']); ?> <br> Réf.
                                    <?php echo htmlspecialchars($document['title']); ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="7" class="text-center">Aucun document trouvé.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="upload" class="btn btn-success">Ajouter un document</a>
            <?php endif; ?>
        </div>

        <!-- Modale pour visualiser le PDF -->
        <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pdfModalLabel">Visualiser le PDF</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe src="" frameborder="0" style="width: 100%; height: 600px;"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'footer.php' ?>
</body>

</html>
<?php
$conn = null;
?>