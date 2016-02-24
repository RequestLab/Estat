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

use RequestLab\Estat\Query;

/**
 * Class QueryTest
 *
 * @package RequestLab\Tests\Estat
 */
class QueryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \RequestLab\Estat\Query
     */
    protected $query;


    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->query = new Query();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->query);
    }

    public function testDefaultState()
    {
        $this->assertFalse($this->query->hasFilters());
        $this->assertSame(1, $this->query->getPage());
        $this->assertSame(10000, $this->query->getPageSize());
        $this->assertSame(array('unit' => 'LECTURES', 'order' => 'DESC'), $this->query->getTop());
        $this->assertSame('D', $this->query->getPeriodType());
    }

    public function testSerial()
    {
        $serial = '999999999';
        $this->query->setSerial($serial);
        $this->assertSame($serial, $this->query->getSerial());
    }

    public function testStartDate()
    {
        $startDate = new \DateTime();
        $this->query->setStartDate($startDate);
        $this->assertSame($startDate, $this->query->getStartDate());
    }

    public function testEndDate()
    {
        $endDate = new \DateTime();
        $this->query->setEndDate($endDate);
        $this->assertSame($endDate, $this->query->getEndDate());
    }

    public function testIndicator()
    {
        $indicator = 'INDICATOR';
        $this->query->setIndicator($indicator);
        $this->assertSame($indicator, $this->query->getIndicator());
    }

    public function testPage()
    {
        $page = 1;
        $this->query->setPage($page);
        $this->assertSame($page, $this->query->getPage());
    }

    public function testPageSize()
    {
        $pageSize = 1000;
        $this->query->setPageSize($pageSize);
        $this->assertSame($pageSize, $this->query->getPageSize());
    }

    public function testPeriodeType()
    {
        $periodeType = 'D';
        $this->query->setPeriodType($periodeType);
        $this->assertSame($periodeType, $this->query->getPeriodType());
    }

    public function testFilters()
    {
        $filters = array('foo', 'bar');
        $this->query->setFilters($filters);
        $this->assertTrue($this->query->hasFilters());
        $this->assertSame($filters, $this->query->getFilters());
    }

    public function testTop()
    {
        $top = array('unit' => 'LECTURES', 'order' => 'DESC');
        $this->query->setTop($top);
        $this->assertSame($top, $this->query->getTop());
    }

    public function testBuild()
    {
        $this->query->setSerial('serial');
        $this->query->setStartDate($startDate = new \DateTime('2015-08-02'));
        $this->query->setEndDate($endDate = new \DateTime('2015-08-02'));
        $this->query->setIndicator('INDICATOR');

        $expected = ['{"gosuRequest":{"tokenId":"token","page":1,"pageSize":10000,"startDate":"2015-08-02","endDate":"2015-08-02","indicator":"INDICATOR","serial":"serial","periodType":"D","top":{"unit":"LECTURES","order":"DESC"}}}'];

        $this->assertSame($expected, $this->query->build('token'));
    }
}