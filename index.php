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

// Pagination
$limit = 10; // Nombre de documents par page
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

// Récupérer le nombre total de documents
$total_stmt = $conn->prepare("SELECT COUNT(*) AS total FROM documents");
$total_stmt->execute();
$total_result = $total_stmt->fetch(PDO::FETCH_ASSOC);
$total_documents = $total_result['total'];
$total_pages = ceil($total_documents / $limit);

$sql = "SELECT documents.*, category.name AS category_name, act_types.name AS act_type, instance_type.name AS instance_type 
        FROM documents 
        LEFT JOIN category ON documents.category_id = category.id 
        LEFT JOIN act_types ON documents.act_type_id = act_types.id 
        LEFT JOIN instance_type ON documents.instance_type = instance_type.id
        ORDER BY documents.act_date DESC
        LIMIT :start, :limit";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':start', $start, PDO::PARAM_INT);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les catégories pour le formulaire de recherche
$categories_stmt = $conn->prepare("SELECT * FROM category");
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
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link href="css/style.css" rel="stylesheet">
    <!-- Inclure jQuery et Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Toggle description
        $(document).on('click', '.toggle-btn', function() {
            var id = $(this).data('id');
            $('#description-' + id).toggle();
            if ($(this).text() === '+') {
                $(this).text('-').addClass('red-btn');
            } else {
                $(this).text('+').removeClass('red-btn');
            }
        });

        // Show PDF in modal
        $(document).on('click', '.view-btn', function() {
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
    <?php include('header.php') ?>
    <div class="container-custom">
        <div class="container-custom mt-5">
            <div class="row">
                <div class="col-md-2">
                    <img src="assets/oremis-logo.svg" alt="OREMIS Logo" class="img-fluid"
                        style="max-height: 60px; width: auto;">
                </div>
                <div class="col-md-8 text-center">
                    <h1>Actes administratifs de l'association OREMIS</h1>
                </div>
            </div>

            <!-- Afficher les messages de succès ou d'erreur -->
            <?php if (isset($_GET['message'])): ?>
                <div class="alert <?php echo $_GET['success'] == 1 ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_GET['message']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <div class="text-right">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="session/logout" class="btn btn-secondary">Déconnexion</a>
                    <a href="profil" class="btn btn-info">Mon profil</a>
                    <?php if ($user_role === 'admin' || $user_role === 'user'): ?>
                        <a href="admin/admin" class="btn btn-primary">Administration</a>
                        <p>Bonjour <?php echo htmlspecialchars($username); ?>, <?php echo htmlspecialchars($user_role); ?></p>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="session/login" class="btn btn-primary">Connexion</a>
                <?php endif; ?>
            </div>

            <br><br>

            <!-- <form method="get" action="index" class="mt-3">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="search">Recherche (soon)</label>
                        <input type="text" id="search" name="search" readonly="readonly" class="form-control"
                            placeholder="Rechercher...">
                    </div>
                </div>
            </form> -->

            <table id="documents-table" class="table table-hover mt-3">
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
                            <?php if ($document['confidential'] && !isset($_SESSION['user_id']))
                                continue; ?>
                            <tr>
                                <td>
                                    <button class="toggle-btn" data-id="<?php echo $document['id']; ?>">+</button>
                                    <span style="<?php echo $document['confidential'] ? 'color: red;' : ''; ?>">
                                        <?php echo htmlspecialchars($document['act_type']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($document['title']); ?></td>
                                <td><?php echo htmlspecialchars($document['category_name']); ?></td>
                                <td><?php echo htmlspecialchars($document['instance_type']); ?></td>
                                <td><?php echo htmlspecialchars($document['act_date']); ?></td>
                                <td><button class="view-btn btn btn-info btn-sm mt-1" data-toggle="modal"
                                        data-target="#pdfModal"
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
                                    <?php echo htmlspecialchars($document['title']); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="7" class="text-center">Aucun document trouvé.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>">Précédent</a></li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if ($i == $page)
                                                    echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>">Suivant</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

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