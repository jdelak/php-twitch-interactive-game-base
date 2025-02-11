<?php
session_start();
require_once('constants.php');

$client_id = CLIENT_ID;
$client_secret = CLIENT_SECRET;
$redirect_uri = REDIRECT_URI;
$scope = SCOPES;

if (!isset($_GET['code'])) {
    $auth_url = "https://id.twitch.tv/oauth2/authorize"
        . "?response_type=code"
        . "&client_id=$client_id"
        . "&redirect_uri=$redirect_uri"
        . "&scope=$scope";

    header("Location: $auth_url");
    exit();
} else {
    $code = $_GET['code'];
    $token_url = "https://id.twitch.tv/oauth2/token";

    $params = [
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'code' => $code,
        'grant_type' => 'authorization_code',
        'redirect_uri' => $redirect_uri
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $token_data = json_decode($response, true);
    $_SESSION['access_token'] = $token_data['access_token'];
    header("Location: dashboard.php");
    exit();
}
?>