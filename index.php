<?php

/**
 * Sample PHP code for youtube.channels.list
 * See instructions for running these code samples locally:
 * https://developers.google.com/explorer-help/code-samples#php
 */

// Exchange authorization code for an access token.
require_once __DIR__ . '/google-api/vendor/autoload.php';
if (isset($_GET['code'])) {
    $client = new Google_Client();
    $client->setAuthConfig('./authData.json');

    // Exchange authorization code for an access token.
    $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($accessToken);
    
    //user details
    $oauth = new Google_Service_Oauth2($client);
    $userProfile = $oauth->userinfo->get();
    $output = "<p>Name: " .  $userProfile['name'] . '</p>';
    $output = $output . "<p>Family Name: " .  $userProfile['familyName'] . '</p>';
    $output = $output . "<p>Given Name: " .  $userProfile['givenName'] . '</p>';
    $output = $output . "<p>GID: " .  $userProfile['id'] . '</p>';
    $output = $output . "<p>Email Address: " .  $userProfile['email'] . '</p>';
    $output = $output . "<p>Verified Email Address: " .  $userProfile['verifiedEmail'] . '</p>';
    $output = $output . "<p>Picture: " .  $userProfile['picture'] . '</p>';
    print($output);
    echo "<br>";
    echo "<br>";
    echo "<br>";
    
    // Define service object for making API requests.
    $service = new Google_Service_YouTube($client);
    $queryParams = [
        'mine' => true
    ];
    
    $response = $service->channels->listChannels('statistics', $queryParams);
    
    //getting required data
    
    $response = (array)$response;
    $page = (array)$response['pageInfo'];
    $totalResult = $page['totalResults'];
    
    echo "Access code: " . $_GET['code'];
    echo "<br> Result: " . $totalResult;
    if($totalResult != 0){
        $uid = $response['items'][0]['id'];

        $stats = (array)$response['items'][0]['statistics'];
        $subscribers = $stats['subscriberCount'];

        $totalVideos = $stats['videoCount'];
    
        echo "<br> YT id: " . $uid;
        echo "<br> Subscribers: " . $subscribers;
        echo "<br> Videos: " . $totalVideos;

    }
    exit;
}

$client = new Google_Client();
$client->setApplicationName('API code samples');
$client->setScopes([
    'https://www.googleapis.com/auth/youtube.readonly',
    'https://www.googleapis.com/auth/userinfo.profile',
    'https://www.googleapis.com/auth/userinfo.email',
]);

// TODO: For this request to work, you must replace
//       "YOUR_CLIENT_SECRET_FILE.json" with a pointer to your
//       client_secret.json file. For more information, see
//       https://cloud.google.com/iam/docs/creating-managing-service-account-keys
$client->setAuthConfig('./authData.json');
$client->setAccessType('offline');
$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] .'/youtube-login/index.php';
$client->setRedirectUri($redirect_uri);


// Request authorization from the user.
$authUrl = $client->createAuthUrl();
header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));


?>