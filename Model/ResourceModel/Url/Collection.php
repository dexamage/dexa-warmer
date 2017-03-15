<?php
/**
 * Copyright © 2015 Dexa. All rights reserved.
 * See Dexa.txt for license details.
 */
namespace Dexa\Warmer\Model\ResourceModel\Url;

/**
 * Class Collection
 * @package Dexa\Warmer\Model\ResourceModel\Url
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'url_id';

    /**
     * Collection resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Dexa\Warmer\Model\Url', 'Dexa\Warmer\Model\ResourceModel\Url');
    }
}
