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

use RequestLab\Estat\Service;

/**
 * Class ServiceTest
 *
 * @package RequestLab\Tests\Estat
 */
class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \RequestLab\Estat\Service
     */
    protected $service;

    /**
     * @var \RequestLab\Estat\Client
     */
    protected $clientMock;

    /**
     * @var \Widop\HttpAdapter\HttpAdapterInterface
     */
    protected $httpAdapterMock;

    /**
     * @var \RequestLab\Estat\Query
     */
    protected $queryMock;

    /**
     * @var string
     */
    protected $login = 'login';

    /**
     * @var string
     */
    protected $password = 'password';


    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->httpAdapterMock = $this->getMock('Widop\HttpAdapter\HttpAdapterInterface');
        $this->clientMock = $this->getMockBuilder('RequestLab\Estat\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $this->clientMock
            ->expects($this->any())
            ->method('getHttpAdapter')
            ->will($this->returnValue($this->httpAdapterMock));
        $this->service = new Service($this->clientMock);
        $this->queryMock = $this->getMockBuilder('RequestLab\Estat\Query')
            ->disableOriginalConstructor()
            ->getMock();
    }
    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->service);
        unset($this->clientMock);
        unset($this->queryMock);
    }

    public function testDefaultState()
    {
        $this->assertSame($this->clientMock, $this->service->getClient());
    }

    public function testQuery()
    {
        $this->clientMock
            ->expects($this->once())
            ->method('getAccessToken')
            ->will($this->returnValue('token'));
        $this->queryMock
            ->expects($this->once())
            ->method('build')
            ->with($this->equalTo('token'))
            ->will($this->returnValue(array('content')));


        $expected = [
            'gosuResponse' => [
                'tokenId'       => 'token',
                'page'          => 1,
                'pageSize'      => 50,
                'nbLinesTotal'  => 50,
                'serial'        => '9999999999',
                'startDate'     => '2015-08-02',
                'endDate'       => '2015-08-02',
                'indicator'     => 'INDICATOR',
                'periodType'    => 'D',
                'labels'        => [
                    'label' => [
                        'code' => 'DATE',
                        'name' => 'Date',
                        'pos'  => 1,
                        'type' => 'STRING'
                    ]
                ],
                "units" => [
                    'unit' => [
                        [
                            "code" => "CONNEXIONS",
                            "name" => "Connexions",
                            "pos"  => 2,
                            "type" => "NUMBER"
                        ],
                        [
                            "code" => "LECTURES",
                            "name" => "Sessions",
                            "pos"  => 3,
                            "type" => "NUMBER"
                        ],
                        [
                            "code" => "LECTEURS_QUOTIDIENS",
                            "name" => "Utilisateurs+quotidiens",
                            "pos"  => 4,
                            "type" => "NUMBER"
                        ],
                        [
                            "code" => "DUREE_TOTALE",
                            "name" => "Dur%C3%A9e+totale+des+sessions",
                            "pos"  => 5,
                            "type" => "TIME"
                        ],
                        [
                            "code" => "DUREE_MAX",
                            "name" => "Dur%C3%A9e+maximale",
                            "pos" => 6,
                            "type" => "TIME"
                        ],
                        [
                            "code" => "DUREE_TOTALE_AFFICHAGE_PAGE",
                            "name" => "Dur%C3%A9e+totale+d%27affichage+page",
                            "pos" => 7,
                            "type" => "TIME"
                        ],
                        [
                            "code" => "DUREE_MAX_AFFICHAGE_PAGE",
                            "name" => "Dur%C3%A9e+d%27affichage+maximum",
                            "pos" => 8,
                            "type" => "TIME"
                        ],
                        [
                            "code" => "DUREE_MOYENNE_LECTURES",
                            "name" => "Dur%C3%A9e+moyenne+des+session",
                            "pos" => 9,
                            "type" => "TIME"
                        ],
                        [
                            "code" => "VISITES",
                            "name" => "Visites",
                            "pos" => 10,
                            "type" => "NUMBER"
                        ],
                        [
                            "code" => "UTILISATEURS_QUOTIDIENS_NORMES",
                            "name" => "Utilisateurs+quotidiens+norm%C3%A9s",
                            "pos" => 11,
                            "type" => "NUMBER"
                        ]
                    ]
                ],
                'data' => array(
                    'row' => array(
                        array(
                            'col' => array(
                                array(
                                    "2015-04-28",
                                    "789970",
                                    "759041",
                                    "301370",
                                    "497469834",
                                    "30600",
                                    "879199998",
                                    "86342",
                                    "655.3926",
                                    "378155",
                                    "301370"
                                )
                            )
                        )
                    )
                )
            ]
        ];

        $this->httpAdapterMock
            ->expects($this->once())
            ->method('postContent')
            ->with(
                'https://ws.estat.com/gosu/rest/data/json',
                array('Content-Type' => 'application/json'),
                $this->equalTo(array('content'))
            )
            ->will($this->returnValue(json_encode($expected)));

        $response = $this->service->query($this->queryMock);

        $this->assertSame($expected['gosuResponse']['tokenId'],         $response->getTokenId());
        $this->assertSame($expected['gosuResponse']['page'],            $response->getPage());
        $this->assertSame($expected['gosuResponse']['pageSize'],        $response->getPageSize());
        $this->assertSame($expected['gosuResponse']['nbLinesTotal'],    $response->getNbLinesTotal());
        $this->assertSame($expected['gosuResponse']['serial'],          $response->getSerial());
        $this->assertSame($expected['gosuResponse']['startDate'],       $response->getStartDate()->format('Y-m-d'));
        $this->assertSame($expected['gosuResponse']['endDate'],         $response->getEndDate()->format('Y-m-d'));
        $this->assertSame($expected['gosuResponse']['indicator'],       $response->getIndicator());
        $this->assertSame($expected['gosuResponse']['periodType'],      $response->getPeriodType());
        $this->assertSame($expected['gosuResponse']['labels']['label'], $response->getLabels());
        $this->assertSame($expected['gosuResponse']['units']['unit'],   $response->getUnits());
        $this->assertTrue($response->hasData());
        //$this->assertSame($expected['gosuResponse']['data'], $response->getData());
    }

    /**
     * @expectedException \RequestLab\Estat\EstatException
     */
    public function testQueryWithJsonError()
    {
        $this->clientMock
            ->expects($this->once())
            ->method('getAccessToken')
            ->will($this->returnValue('token'));
        $this->queryMock
            ->expects($this->once())
            ->method('build')
            ->with($this->equalTo('token'))
            ->will($this->returnValue(array('content')));

        $this->httpAdapterMock
            ->expects($this->once())
            ->method('postContent')
            ->with(
                'https://ws.estat.com/gosu/rest/data/json',
                array('Content-Type' => 'application/json'),
                $this->equalTo(array('content'))
            )
            ->will($this->returnValue(
                json_encode(
                    array(
                        'gosuResponse' => array(
                            'errorCode' => '999',
                            'errorMessage' => 'error'
                        )
                    )
                )
            ));

        $this->service->query($this->queryMock);

    }
}