<?php
session_start();
include 'db.php';

$sql = "SELECT documents.*, category.name AS category_name, act_types.name AS act_type, instance_type.name AS instance_type 
        FROM documents 
        LEFT JOIN category ON documents.category_id = category.id 
        LEFT JOIN act_types ON documents.act_type_id = act_types.id 
        LEFT JOIN instance_type ON documents.instance_type = instance_type.id
        ORDER BY documents.act_date DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($documents);
?>
