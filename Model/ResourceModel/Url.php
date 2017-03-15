<?php
/**
 * Copyright © 2015 Dexa. All rights reserved.
 * See LABELS.txt for license details.
 */
namespace Dexa\Warmer\Model\ResourceModel;

/**
 * Class Url
 * @package Dexa\Warmer\Model\ResourceModel
 */
class Url extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Url constructor.
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param null $resourcePrefix
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);

        $this->storeManager = $storeManager;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('dexa_warmer_url', 'url_id');
    }

    /**
     * @param \Dexa\Warmer\Model\Url $urlModel
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $urlModel)
    {
        /** @var \Dexa\Warmer\Model\Url $urlModel */
        if (!$urlModel->getId()) {
            $urlModel->setData('hash', $urlModel->genUrlHash());
            $urlModel->setData('created_at', $urlModel->gmtDate());
            $urlModel->setData('visited_at', $urlModel->gmtDate());
            $urlModel->setData('view_count', 0);
            $urlModel->setData('order_position', 0);
        }

        $urlModel->setData('updated_at', $urlModel->gmtDate());

        return parent::_beforeSave($urlModel);
    }
}