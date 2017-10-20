<?php
require_once __DIR__ . '/../vendor/autoload.php';

$googleCloudPrint = new \HanischIt\GoogleCloudPrint\GoogleCloudPrint(
    "ClientId",
    "ClientSecret",
    "RedirectUrl"
);

try {
    if (!isset($_GET["code"]) && !isset($_GET["token"])) {
        $urlToRedirect = $googleCloudPrint->authenticate();
        header("LOCATION: " . $urlToRedirect);
        die();
    }

    if (isset($_GET["code"])) {
        $code = $_GET["code"];
        $token = $googleCloudPrint->getAccessToken($code);
        header("LOCATION: 01-get_printers.php?token=" . $token->getAccessToken() . "&token_type=" . $token->getTokenType());
    }

    if (isset($_GET["token"]) && isset($_GET["token_type"])) {
        $token = new \HanischIt\GoogleCloudPrint\Model\TokenResponse($_GET["token"], 300, $_GET["token_type"]);
        $printers = $googleCloudPrint->getPrinters($token);

        foreach ($printers as $printer) {
            echo "#" . $printer->getId() . " ", $printer->getName() . "<br>\n";

        }

        $googleCloudPrint->printPdf($token, $printers[0], file_get_contents(__DIR__ . "/example.pdf"));
    }


} catch
(Exception $e) {
    echo $e->getMessage();
}