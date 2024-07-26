<?php
header("Content-Type: application/json");

// Inclure la base de données
include '../db.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', trim($uri, '/'));

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($uri_segments[2]) && is_numeric($uri_segments[2])) {
        // Récupérer l'ID passé dans l'URL
        $document_id = intval($uri_segments[2]);
        
        // Préparez et exécutez la requête SQL pour l'acte spécifique
        $sql = "SELECT documents.id, documents.title, category.name AS category_name 
                FROM documents 
                LEFT JOIN category ON documents.category_id = category.id 
                WHERE documents.id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $document_id, PDO::PARAM_INT);
        $stmt->execute();
        $document = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifiez si un document a été trouvé
        if ($document) {
            echo json_encode(['status' => 'success', 'data' => $document]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Document not found']);
        }
    } else {
        // Préparez et exécutez la requête SQL pour récupérer tous les actes
        $sql = "SELECT documents.id, documents.title, documents.description, category.name AS category_name 
                FROM documents 
                LEFT JOIN category ON documents.category_id = category.id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Vérifiez s'il y a des documents
        if ($documents) {
            echo json_encode(['status' => 'success', 'data' => $documents]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No documents found']);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$conn = null;
?>
