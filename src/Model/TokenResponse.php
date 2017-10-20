<?php
/**
 * Created by PhpStorm.
 * User: fabia
 * Date: 20.10.2017
 * Time: 15:40
 */

namespace HanischIt\GoogleCloudPrint\Model;


class TokenResponse
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $expiresIn;

    /**
     * @var string
     */
    private $tokenType;

    /**
     * TokenResponse constructor.
     * @param string $accessToken
     * @param string $expiresIn
     * @param string $tokenType
     */
    public function __construct($accessToken, $expiresIn, $tokenType)
    {
        $this->accessToken = $accessToken;
        $this->expiresIn = $expiresIn;
        $this->tokenType = $tokenType;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getExpiresIn()
    {
        return $this->expiresIn;
    }

    /**
     * @return string
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }


}