<?php
namespace HanischIt\GoogleCloudPrint\Model;

/**
 * Class AuthenticationModel
 * @package HanischIt\GoogleCloudPrint\Model
 */
class Authentication
{
    /**
     * @var string
     */
    private $clientId;
    /**
     * @var string
     */
    private $clientSecret;

    /**
     * AuthenticationModel constructor.
     * @param string $clientId
     * @param string $clientSecret
     */
    public function __construct($clientId, $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

}