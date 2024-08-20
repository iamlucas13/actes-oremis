<?php
require_once '../vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('469603402058-seka5codogk226poc0akq2q3k1vmu84o.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/session/callback.php'); // L'URL de redirection que vous avez configurée
$client->addScope('email');

// Rediriger vers l'URL d'authentification Google
$authUrl = $client->createAuthUrl();
header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
exit();
