<?php

/*
 * This file is part of the RequestLab package.
 *
 * (c) RequestLab <hello@requestlab.fr>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace RequestLab\Estat;

use Widop\HttpAdapter\HttpAdapterInterface;

/**
 * Class Client
 *
 * @package RequestLab\Estat
 * @author ylcdx <yann@requestlab.fr>
 */
class Client
{
    /**
     * @var string
     */
    protected $login;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $universeFilters;

    /**
     * @var string
     */
    protected $url = 'https://ws.estat.com/gosu/rest/auth/json';

    /**
     * @var HttpAdapterInterface
     */
    protected $httpAdapter;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @param $login
     * @param $password
     * @param string $url
     * @param HttpAdapterInterface $httpAdapter
     */
    function __construct($login, $password, HttpAdapterInterface $httpAdapter)
    {
        $this->login            = $login;
        $this->password         = $password;
        $this->httpAdapter      = $httpAdapter;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getUniverseFilters()
    {
        return $this->universeFilters;
    }

    /**
     * @param string $universeFilters
     */
    public function setUniverseFilters($universeFilters)
    {
        $this->universeFilters = $universeFilters;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return HttpAdapterInterface
     */
    public function getHttpAdapter()
    {
        return $this->httpAdapter;
    }

    /**
     * @param HttpAdapterInterface $httpAdapter
     */
    public function setHttpAdapter(HttpAdapterInterface $httpAdapter)
    {
        $this->httpAdapter = $httpAdapter;
    }

    /**
     * @return string
     * @throws EstatException
     */
    public function getAccessToken()
    {
        if ($this->accessToken === null) {

            $headers = array('Content-Type' => 'application/json');
            $params = array(
                'login'    => $this->getLogin(),
                'password' => $this->getPassword()
            );

            $uri = sprintf('%s?%s', $this->url, http_build_query($params));

            $response = $this->httpAdapter->getContent($uri, $headers);

            if ($response === null) {
                throw EstatException::invalidResponse($response);
            }

            $response = json_decode($response->getBody());

            if (isset($response->tokenResponse->errorCode)) {
                throw EstatException::invalidAccessToken($response->tokenResponse->errorMessage);
            }
            $this->accessToken = $response->tokenResponse->tokenId;

        }
        return $this->accessToken;
    }



}