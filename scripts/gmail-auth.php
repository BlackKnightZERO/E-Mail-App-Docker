<?php

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Now you can use Laravel helper functions
$client = new Google_Client();
$client->setAuthConfig(storage_path('app/credentials.json'));
$client->addScope(Google_Service_Gmail::GMAIL_SEND);
$client->setAccessType('offline');
$client->setPrompt('select_account consent');
$client->setRedirectUri('http://localhost:8000/callback');

// Request authorization from the user
$authUrl = $client->createAuthUrl();
printf("Open the following link in your browser:\n%s\n", $authUrl);
print('Enter verification code: ');
$authCode = trim(fgets(STDIN));

// Exchange authorization code for an access token
$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
file_put_contents(storage_path('app/token.json'), json_encode($accessToken));

echo "Token saved to token.json\n";
