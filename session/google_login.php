<?php
require_once '../vendor/autoload.php';
require_once __DIR__ . '/../env.php';

$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(REDIRECT_URI); 
$client->addScope('email');

// Rediriger vers l'URL d'authentification Google
$authUrl = $client->createAuthUrl();
header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
exit();
