<?php
/**
 * Copyright © 2015 Dexa. All rights reserved.
 * See LABELS.txt for license details.
 */

namespace Dexa\Warmer\Model;

/**
 * Class Url
 *
 * @method string getCreatedAt()
 * @method string getUpdatedAt()
 * @method string getVisitedAt()
 * @method string getWarmedAt()
 * @method int getViewCount()
 * @method float getOrderPosition()
 * @method void setCreatedAt(string)
 * @method void setUpdatedAt(string)
 * @method void setVisitedAt(string)
 * @method void setWarmedAt(string)
 * @method void setViewCount(int)
 * @method void setOrderPosition(float)
 *
 * @package Dexa\Warmer\Model
 */
class Url extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * Url constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ResourceModel\Url|null $resource
     * @param ResourceModel\Url\Collection|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Dexa\Warmer\Model\ResourceModel\Url $resource = null,
        \Dexa\Warmer\Model\ResourceModel\Url\Collection $resourceCollection = null,
        array $data = []
    ){
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->date = $date;
    }


    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_init('Dexa\Warmer\Model\ResourceModel\Url');
    }

    /**
     * @param $url
     */
    public function loadByUrl($url)
    {
        $hash = $this->genUrlHash($url);
        $this->getResource()->load($this, $hash, 'hash');

        return $this;
    }

    /**
     * @return string
     */
    public function gmtDate()
    {
        return $this->date->gmtDate();
    }

    /**
     * @param $value
     */
    public function genUrlHash($url = null)
    {
        return hash('md5', $url ?: $this->getUrl());
    }

    /**
     * @return $this
     */
    public function incrementViewStats()
    {
        $this->setVisitedAt($this->gmtDate());
        $this->setViewCount(1 + $this->getViewCount() ?: 0);
        $this->setOrderPosition(1 + $this->getOrderPosition() ?: 0);

        return $this;
    }

    /**
     * @param $httpCode
     */
    public function incrementWarmStats($httpCode)
    {
        $prevHttpCode = $this->getLastHttpCode();

        $this->setWarmedAt($this->gmtDate());
        $this->setLastHttpCode($httpCode);

        if ($prevHttpCode >= 400 && $httpCode >= 400) {
            $this->setIgnoreUrl(1);
        }
    }
}
