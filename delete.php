<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Récupérer le fichier avant de le supprimer de la base de données
    $stmt = $conn->prepare("SELECT filename FROM documents WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $file = 'uploads/' . $row['filename'];
    
    // Supprimer l'enregistrement de la base de données
    $stmt = $conn->prepare("DELETE FROM documents WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        // Supprimer le fichier du serveur
        if (file_exists($file)) {
            unlink($file);
        }
        header("Location: index");
    } else {
        echo "Erreur: " . $stmt->errorInfo()[2];
    }
} else {
    header("Location: index");
}
?>
