<?php
session_start();
include '../db.php';

require_once '../vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('442575193619-ss1ahop1o7eoca88ov1t8e1anktaptqu.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/session/verify_google_login.php');
$client->addScope('email');

if (isset($_POST['id_token'])) {
    $id_token = $_POST['id_token'];
    $payload = $client->verifyIdToken($id_token);
    if ($payload) {
        $email = $payload['email'];

        // Vérifier si l'email existe dans la base de données
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            echo 'success';
        } else {
            echo 'Échec de la connexion Google.';
        }
    } else {
        echo 'Échec de la vérification du token Google.';
    }
} else {
    echo 'Aucun token fourni.';
}
?>