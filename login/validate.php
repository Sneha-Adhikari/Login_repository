<?php
if (isset($_POST['g-recaptcha-response']))
{
    $secretkey = "6LcGfUYqAAAAAEBr07uNCnpno4ZGrJVe-4sBpIy0";
    $ip = $_SERVER['remote_ADDR'];
    $response = $_POST['g-recaptcha-response'];
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretkey&response=$response&remoteip=$ip";
$fire = file_get_contents($url);
echo $fire;

}
else{
    echo "Recaptcha Error";
}
?>