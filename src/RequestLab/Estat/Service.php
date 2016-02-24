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

/**
 * Class Service
 *
 * @package RequestLab\Estat
 * @author ylcdx <yann@requestlab.fr>
 */
class Service
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @param Client $client
     */
    function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param Query $query
     * @return Response
     * @throws EstatException
     */
    public function query(Query $query)
    {
        $tokenId = $this->getClient()->getAccessToken();
        $uri     = Query::URL;
        $headers = array('Content-Type' => 'application/json');
        $content = $query->build($tokenId);
        $content = $this->getClient()->getHttpAdapter()->postContent($uri, $headers, $content);

        if ($content === null) {
            throw EstatException::invalidResponse('No content');
        }

        $json = json_decode($content, true);

        if (!is_array($json) || isset($json['gosuResponse']['errorCode'])) {
            throw EstatException::invalidQuery(isset($json['gosuResponse']['errorCode']) ? $json['gosuResponse']['errorMessage'] : 'Invalid json');
        }
        return new Response($json);
    }


}