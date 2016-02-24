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
 * Class Response
 *
 * @package RequestLab\Estat
 * @author ylcdx <yann@requestlab.fr>
 */
class Response
{
    /**
     * @var string
     */
    protected $tokenId;

    /**
     * @var integer
     */
    protected $page;

    /**
     * @var integer
     */
    protected $pageSize;

    /**
     * @var integer
     */
    protected $nbLinesTotal;

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
     * @var string
     */
    protected $periodType;

    /**
     * @var array
     */
    protected $labels;

    /**
     * @var array
     */
    protected $units;

    /**
     * @var array
     */
    protected $data;

    /**
     * @param array $response
     */
    function __construct(array $response)
    {
        $gosuResponse = array();
        if (isset($response['gosuResponse'])) {
            $gosuResponse = $response['gosuResponse'];
        }

        if (isset($gosuResponse['tokenId'])) {
            $this->tokenId = $gosuResponse['tokenId'];
        }

        if (isset($gosuResponse['page'])) {
            $this->page = $gosuResponse['page'];
        }

        if (isset($gosuResponse['pageSize'])) {
            $this->pageSize = $gosuResponse['pageSize'];
        }

        if (isset($gosuResponse['nbLinesTotal'])) {
            $this->nbLinesTotal = $gosuResponse['nbLinesTotal'];
        }

        if (isset($gosuResponse['serial'])) {
            $this->serial = $gosuResponse['serial'];
        }

        if (isset($gosuResponse['startDate'])) {
            $this->startDate = new \DateTime($gosuResponse['startDate']);
        }

        if (isset($gosuResponse['endDate'])) {
            $this->endDate = new \DateTime($gosuResponse['endDate']);
        }

        if (isset($gosuResponse['indicator'])) {
            $this->indicator = $gosuResponse['indicator'];
        }

        if (isset($gosuResponse['periodType'])) {
            $this->periodType = $gosuResponse['periodType'];
        }

        $this->labels = array();
        if (isset($gosuResponse['labels']) && isset($gosuResponse['labels']['label'])) {
            $this->labels = $gosuResponse['labels']['label'];
        }

        $this->units = array();
        if (isset($gosuResponse['units']) && isset($gosuResponse['units']['unit'])) {
            $this->units = $gosuResponse['units']['unit'];
        }

        $this->data = array();
        if (isset($gosuResponse['data']) && isset($gosuResponse['data']['row'])) {
            foreach ($gosuResponse['data']['row'] as $row) {
                if (isset($row['col'])) {
                    $this->data[] = $row['col'];
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function getTokenId()
    {
        return $this->tokenId;
    }

    /**
     * @param mixed $tokenId
     */
    public function setTokenId($tokenId)
    {
        $this->tokenId = $tokenId;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return mixed
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @param mixed $pageSize
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
    }

    /**
     * @return mixed
     */
    public function getNbLinesTotal()
    {
        return $this->nbLinesTotal;
    }

    /**
     * @param mixed $nbLinesTotal
     */
    public function setNbLinesTotal($nbLinesTotal)
    {
        $this->nbLinesTotal = $nbLinesTotal;
    }

    /**
     * @return mixed
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * @param mixed $serial
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return mixed
     */
    public function getIndicator()
    {
        return $this->indicator;
    }

    /**
     * @param mixed $indicator
     */
    public function setIndicator($indicator)
    {
        $this->indicator = $indicator;
    }

    /**
     * @return mixed
     */
    public function getPeriodType()
    {
        return $this->periodType;
    }

    /**
     * @param mixed $periodType
     */
    public function setPeriodType($periodType)
    {
        $this->periodType = $periodType;
    }

    /**
     * @return array
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @param array $labels
     */
    public function setLabels($labels)
    {
        $this->labels = $labels;
    }

    /**
     * @return array
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * @param mixed $units
     */
    public function setUnits($units)
    {
        $this->units = $units;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        return !empty($this->data);
    }

    /**
     * @return array
     */
    public function getTotals()
    {
        $total = array();
        $positions = array();

        foreach ($this->getUnits() as $unit) {
            if (isset($unit['type']) && $unit['type'] == 'NUMBER') {
                $total[$unit['code']]       = 0;
                $positions[(int) $unit['pos'] - 1]    = $unit['code'];
            }
        }


        foreach ($this->getData() as $datas) {
            foreach ($datas as $key => $data) {
                if (array_key_exists($key, $positions)) {
                    $total[$positions[$key]] = $total[$positions[$key]] + $data;
                }
            }
        }

        return $total;
    }


}