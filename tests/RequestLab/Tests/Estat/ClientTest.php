<?php

/*
 * This file is part of the RequestLab package.
 *
 * (c) RequestLab <hello@requestlab.fr>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace RequestLab\Tests\Estat;

use RequestLab\Estat\Client;


class ClientTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \RequestLab\Estat\Client
     */
    protected $client;

    /**
     * @var \RequestLab\HttpAdapter\JsonHttpAdapter
     */
    protected $httpAdapterMock;

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
    protected $url;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->login            = 'login';
        $this->password         = 'password';
        $this->url              = 'https://ws.estat.com/gosu/rest/auth/json';
        $this->httpAdapterMock  = $this->getMock('Widop\HttpAdapter\HttpAdapterInterface');
        $this->client           = new Client($this->login, $this->password, $this->httpAdapterMock);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->client);
        unset($this->login);
        unset($this->password);
        unset($this->httpAdapterMock);
        unset($this->url);
    }

    public function testDefaultState()
    {
        $this->assertSame($this->login, $this->client->getLogin());
        $this->assertSame($this->password, $this->client->getPassword());
        $this->assertSame($this->httpAdapterMock, $this->client->getHttpAdapter());
        $this->assertSame($this->url, $this->client->getUrl());
    }

    public function testAccessToken()
    {

        $mockResponse = $this->getMockBuilder('Widop\HttpAdapter\HttpResponse')->disableOriginalConstructor()->getMock();

        $mockResponse->method('getBody')->will($this->returnValue(
            json_encode([
                'tokenResponse' => [
                    'tokenId'   => 'token',
                    'universe'  => []
                ]
            ])
        ));


        $this->httpAdapterMock
            ->expects($this->once())
            ->method('getContent')
            ->with(
                $this->equalTo(sprintf('%s?%s', $this->url, http_build_query(['login' => $this->login, 'password' => $this->password]))),
                $this->equalTo(['Content-Type' => 'application/json'])
            )
            ->will($this->returnValue(
                    $mockResponse
            ));

        $this->assertSame('token', $this->client->getAccessToken());
    }

    public function testAccessTokenError()
    {

        $mockResponse = $this->getMockBuilder('Widop\HttpAdapter\HttpResponse')->disableOriginalConstructor()->getMock();

        $mockResponse->method('getBody')->will($this->returnValue(
            json_encode([
                'tokenResponse' => [
                    'errorCode'     => 999,
                    'errorMessage'  => 'error'
                ]
            ])
        ));

        $this->setExpectedException('RequestLab\Estat\EstatException');
        $this->httpAdapterMock
            ->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue(
                $mockResponse
            ));
        $this->client->getAccessToken();
    }
}