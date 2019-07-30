<?php
require_once '../vendor/autoload.php';
require_once 'config/config.php';
$url = "http://localhost:8080";
session_start();

// algoritme pour la connexion via google
//$_SESSION["user"] si connecter 
//Step 1: Enter you google account credentials
$g_client = new Google_Client();
$g_client->setClientId("$ClientId");
$g_client->setClientSecret("$ClientSecret");
$g_client->setRedirectUri("$Url");
$g_client->setScopes("email");
//Step 2 : Create the url
$auth_url = $g_client->createAuthUrl();
echo "<a href='$auth_url'>Login Through Google </a>";
//Step 3 : Get the authorization  code
$code = isset($_GET['code']) ? $_GET['code'] : NULL;
//Step 4: Get access token
if(isset($code)) {
    try {
        $token = $g_client->fetchAccessTokenWithAuthCode($code);
        $g_client->setAccessToken($token);
    }catch (Exception $e){
        echo $e->getMessage();
    }
    try {
        $pay_load = $g_client->verifyIdToken();
    }catch (Exception $e) {
        echo $e->getMessage();
    }
} else{
    $pay_load = null;
}
 echo $pay_load["email"]; 


if(isset($pay_load)){
    $_SESSION['user']['mail'] = $pay_load["email"];
}
echo $_SESSION['user']['mail'];


if (isset($_SESSION["user"])) {
    header('Location: ' . $url . "/protected.php");
}

include 'header.php';
?>



<div class="jumbotron p-4 p-md-5 text-white rounded bg-dark">
    <div class="col-md-6 px-0">
        <h1 class="display-4 font-italic">Protected Zone</h1>
        <p class="lead my-3"><a href='<?php $auth_url ?>' class="btn btn-primary">Login Through Google </a></p>
        <div class="g-signin2" data-onsuccess="onSignIn"></div>
        <a href="#" onclick="signOut();">Sign out</a>
    </div>
</div>

<?php
include 'footer.php';
