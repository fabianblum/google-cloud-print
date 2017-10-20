<?php

namespace HanischIt\GoogleCloudPrint;


use GuzzleHttp\Exception\ClientException;
use HanischIt\GoogleCloudPrint\Exception\CouldNotAuthenticateException;
use HanischIt\GoogleCloudPrint\Exception\CouldNotReadPrintersException;
use HanischIt\GoogleCloudPrint\Exception\CouldNotSendPrintJobException;
use HanischIt\GoogleCloudPrint\Model\Authentication;
use HanischIt\GoogleCloudPrint\Model\Printer;
use HanischIt\GoogleCloudPrint\Model\TokenResponse;
use HanischIt\GoogleCloudPrint\Wrapper\HttpClient;

class GoogleCloudPrint
{
    /**
     * @var String
     */
    const AUTHORIZATION_URL = "https://accounts.google.com/o/oauth2/auth";
    const ACCESSTOKEN_URL = "https://accounts.google.com/o/oauth2/token";
    const REFRESHTOKEN_URL = "https://www.googleapis.com/oauth2/v3/token";
    /**
     * @var Authentication
     */
    private $authentication;

    /**
     * @var string
     */
    private $redirectUrl;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * GoogleCloudPrint constructor.
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUrl
     */
    public function __construct($clientId, $clientSecret, $redirectUrl)
    {
        $this->authentication = new Authentication($clientId, $clientSecret);
        $this->redirectUrl = $redirectUrl;
        $this->httpClient = new HttpClient([
            'verify' => false,
            'headers' => ['Content-Type' => 'multipart/form-data']

        ]);

    }

    /**
     * Returns a URL which should redirect to
     *
     * @return string
     */
    public function authenticate()
    {
        $params = array(
            'response_type' => 'code',
            'client_id' => $this->authentication->getClientId(),
            'redirect_uri' => $this->redirectUrl,
            'scope' => 'https://www.googleapis.com/auth/cloudprint',
            'access_type' => 'offline'

        );

        return self::AUTHORIZATION_URL . "?" . http_build_query($params);
    }

    /**
     * @param string $code
     * @return TokenResponse
     * @throws CouldNotAuthenticateException
     */
    public function getAccessToken($code)
    {
        try {
            $response = $this->httpClient->post(self::ACCESSTOKEN_URL, [
                'form_params' => [
                    'code' => $code,
                    'client_id' => $this->authentication->getClientId(),
                    'client_secret' => $this->authentication->getClientSecret(),
                    'redirect_uri' => $this->redirectUrl,
                    'grant_type' => 'authorization_code'
                ]

            ]);

        } catch (ClientException $e) {
            throw new CouldNotAuthenticateException("Could not authenticate: " . $e->getMessage());
        }

        $response = json_decode($response->getBody());

        return new TokenResponse($response->access_token, $response->refresh_token, $response->token_type);
    }

    /**
     * @param TokenResponse $tokenResponse
     * @return Printer[]
     * @throws CouldNotReadPrintersException
     */
    public function getPrinters(TokenResponse $tokenResponse)
    {
        try {
            $response = $this->httpClient->get("https://www.google.com/cloudprint/search", [
                'headers' => [
                    'Authorization' => $tokenResponse->getTokenType() . ' ' . $tokenResponse->getAccessToken()
                ]
            ]);
        } catch (ClientException $e) {
            throw new CouldNotReadPrintersException("Could not read Printers: " . $e->getMessage());
        }

        $response = json_decode($response->getBody());

        $ret = [];
        foreach ($response->printers as $printer) {
            if ($printer->type === "DRIVE") {
                continue;
            }
            $ret[] = new Printer($printer->id, $printer->displayName);
        }

        return $ret;
    }

    public function printPdf(TokenResponse $tokenResponse, Printer $printer, $fileContent)
    {
        try {
            $response = $this->httpClient->post("https://www.google.com/cloudprint/submit", [
                'form_params' => [
                    'printerid' => $printer->getId(),
                    'title' => uniqid(),
                    'contentTransferEncoding' => 'base64',
                    'content' => base64_encode($fileContent), // encode file content as base64
                    'contentType' => "application/pdf"
                ],
                'headers' => [
                    'Authorization' => $tokenResponse->getTokenType() . ' ' . $tokenResponse->getAccessToken()
                ]
            ]);
        } catch (ClientException $e) {
            throw new CouldNotReadPrintersException("Could not read Printers: " . $e->getMessage());
        }

        $response = json_decode($response->getBody());

        if ($response->success !== true) {
            throw new CouldNotSendPrintJobException("Could not send print job: " . $response->errormessage);
        }

        return $response->success;
    }
}