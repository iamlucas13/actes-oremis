<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['name'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];

    $stmt = $conn->prepare("UPDATE instance_type SET name = :name WHERE id = :id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header("Location: admin.php?success=1&message=Type d'instance mis à jour avec succès.");
    } else {
        header("Location: admin.php?success=0&message=Erreur lors de la mise à jour du type d'instance.");
    }
    exit;
}
?>
