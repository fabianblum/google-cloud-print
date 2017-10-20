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
    private $refreshToken;

    /**
     * @var string
     */
    private $tokenType;

    /**
     * TokenResponse constructor.
     * @param string $accessToken
     * @param string $refreshToken
     * @param string $tokenType
     */
    public function __construct($accessToken, $refreshToken, $tokenType)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
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
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @return string
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }


}