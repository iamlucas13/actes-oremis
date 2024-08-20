<?php
session_start();
require_once '../vendor/autoload.php';
include '../db.php';
require_once __DIR__ . '/../env.php';

use Google\Service\Oauth2;

$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(REDIRECT_URI);
$client->addScope('email');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (isset($token['error'])) {
        die('Erreur lors de la récupération du token: ' . htmlspecialchars($token['error']));
    }

    $client->setAccessToken($token);

    // Récupérer les informations de l'utilisateur
    
    $oauth2 = new Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    $email = $userInfo->email;

    // Vérifier si l'utilisateur existe dans la base de données
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header('Location: ../index.php'); // Redirection après succès
        exit();
    } else {
        echo 'Utilisateur non trouvé.';
    }
} else {
    echo 'Code non trouvé dans l\'URL de retour.';
}
