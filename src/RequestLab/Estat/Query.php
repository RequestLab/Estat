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
 * Class Query
 *
 * @package RequestLab\Estat
 * @author ylcdx <yann@requestlab.fr>
 */
class Query
{
    /**
     *
     */
    const URL = 'https://ws.estat.com/gosu/rest/data/json';

    /**
     * @var string
     */
    protected $serial;

    /**
     * @var \DateTime
     */
    protected $startDate;

    /**
     * @var \DateTime
     */
    protected $endDate;

    /**
     * @var string
     */
    protected $indicator;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $pageSize;

    /**
     * @var string
     */
    protected $periodType;

    /**
     * @var array
     */
    protected $filters;

    /**
     * @var array
     */
    protected $top;

    function __construct()
    {
        $this->page       = 1;
        $this->pageSize   = 10000;
        $this->filters    = array();
        $this->top        = array('unit' => 'LECTURES', 'order' => 'DESC');
        $this->periodType = 'D';

    }

    /**
     * @return string
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * @param string $serial
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     */
    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return string
     */
    public function getIndicator()
    {
        return $this->indicator;
    }

    /**
     * @param string $indicator
     */
    public function setIndicator($indicator)
    {
        $this->indicator = $indicator;
    }

    /**
     * @return integer
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param integer $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return integer
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @param integer $pageSize
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
    }

    /**
     * @return string
     */
    public function getPeriodType()
    {
        return $this->periodType;
    }

    /**
     * @param string $periodType
     */
    public function setPeriodType($periodType)
    {
        $this->periodType = $periodType;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param array $filters
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    /**
     * @return bool
     */
    public function hasFilters()
    {
        return !empty($this->filters);
    }

    /**
     * @return int
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * @param int $top
     */
    public function setTop($top)
    {
        $this->top = $top;
    }

    /**
     * @param $tokenId
     * @return string
     */
    public function build($tokenId)
    {

        $query = array(
            'gosuRequest'  =>
                array(
                    'tokenId'    => $tokenId,
                    'page'       => $this->getPage(),
                    'pageSize'   => $this->getPageSize(),
                    'startDate'  => $this->getStartDate()->format('Y-m-d'),
                    'endDate'    => $this->getEndDate()->format('Y-m-d'),
                    'indicator'  => $this->getIndicator(),
                    'serial'     => $this->getSerial(),
                    'periodType' => $this->getPeriodType(),
                    'top'        => $this->getTop()
                )
        );

        if ($this->hasFilters()) {
            $query['gosuRequest']['filters'] = $this->getFilters();
        }

        return array(json_encode($query));
    }

}