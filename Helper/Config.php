<?php
/**
 * Copyright © 2015 SaM Solutions. All rights reserved.
 * See Wgaca.txt for license details.
 */

namespace Dexa\Warmer\Helper;

/**
 * Class Config
 * @package Dexa\Warmer\Helper
 */
class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Consts
     */
    const NOROUTE = 'cms/noroute/index';

    const NO = 0;
    const YES = 1;

    const INVALID = 0;
    const VALID = 1;
    const WORKING = 2;

    const XML_NODE_WARMER_GENERAL_ENABLED = 'dexa_warmer/general/enabled';
    const XML_NODE_WARMER_GENERAL_OBSERVINGCACHE = 'dexa_warmer/general/observing_cache';

    const XML_NODE_WARMER_STATS_ENABLED = 'dexa_warmer/stats/enabled';

    const XML_NODE_WARMER_CRON_ENABLED = 'dexa_warmer/cron/enabled';
    const XML_NODE_WARMER_CRON_NONSTOP = 'dexa_warmer/cron/nonstop';
    const XML_NODE_WARMER_CRON_TIMEOUT = 'dexa_warmer/cron/timeout';
    const XML_NODE_WARMER_CRON_LIMIT = 'dexa_warmer/general/limit';
    const XML_NODE_WARMER_CRON_MINORDER = 'dexa_warmer/general/min_order';

    const XML_NODE_WARMER_SYS_STATUS = 'dexa_warmer/sys/status';
    const XML_NODE_WARMER_SYS_STARTEDTIME = 'dexa_warmer/sys/started_time';

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $datetime;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @param Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Stdlib\DateTime\DateTime $datetime
    ){
        parent::__construct($context);

        $this->resource = $resource;
        $this->request = $request;
        $this->registry = $registry;
        $this->datetime = $datetime;
    }

    /**
     * GENERAL CONFIGS
     */
    public function isEnabled()
    {
        return $this->scopeConfig->getValue(self::XML_NODE_WARMER_GENERAL_ENABLED) == self::YES ? true : false;
    }

    public function isObservingCache()
    {
        return $this->_loadConfigValue(self::XML_NODE_WARMER_GENERAL_OBSERVINGCACHE) == self::YES ? true : false;
    }

    /**
     * STATS CONFIGS
     */
    public function isStatsEnabled()
    {
        return $this->isEnabled() && $this->scopeConfig->getValue(self::XML_NODE_WARMER_STATS_ENABLED) == self::YES ? true : false;
    }

    /**
     * SYS CONFIGS
     */
    public function isWarmerInvalid()
    {
        return $this->_loadConfigValue(self::XML_NODE_WARMER_SYS_STATUS) == self::INVALID ? true : false;
    }

    public function isWarmerValid()
    {
        return $this->_loadConfigValue(self::XML_NODE_WARMER_SYS_STATUS) == self::VALID ? true : false;
    }

    public function isWarmerWorking()
    {
        return $this->_loadConfigValue(self::XML_NODE_WARMER_SYS_STATUS) == self::WORKING ? true : false;
    }

    public function invalidateWarmer()
    {
        return $this->_saveConfig(self::XML_NODE_WARMER_SYS_STATUS, self::INVALID);
    }

    public function validateWarmer()
    {
        return $this->_saveConfig(self::XML_NODE_WARMER_SYS_STATUS, self::VALID);
    }

    public function setWarmerWorking()
    {
        return $this->_saveConfig(self::XML_NODE_WARMER_SYS_STATUS, self::WORKING);
    }

    /**
     * CRON CONFIGS
     */
    public function isCronEnabled()
    {
        return $this->isEnabled() && $this->scopeConfig->getValue(self::XML_NODE_WARMER_CRON_ENABLED) == self::YES ? true : false;
    }

    public function isCronNonStop()
    {
        return $this->_loadConfigValue(self::XML_NODE_WARMER_CRON_NONSTOP) == self::YES ? true : false;
    }

    public function getCronPagesLimit()
    {
        return $this->_loadConfigValue(self::XML_NODE_WARMER_CRON_LIMIT);
    }

    public function getCronTimeout()
    {
        $timeout = $this->_loadConfigValue(self::XML_NODE_WARMER_CRON_TIMEOUT);

        return $timeout > 0 ? 60 * $timeout : $timeout;
    }

    public function getCronMinOrder()
    {
        return $this->_loadConfigValue(self::XML_NODE_WARMER_CRON_MINORDER);
    }

    public function getCronStartedTime()
    {
        return $this->_loadConfigValue(self::XML_NODE_WARMER_SYS_STARTEDTIME);
    }

    public function updatedCronStartedTime()
    {
        $this->_saveConfig(self::XML_NODE_WARMER_SYS_STARTEDTIME, $this->now());
    }

    /**
     * PATH/URL FUNCTIONS
     */
    public function getPath()
    {
        return $this->request->getModuleName() . '/' . $this->request->getControllerName() . '/' . $this->request->getActionName();
    }

    public function getUri()
    {
        return $this->request->getUriString();
        //return (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}\n";
    }

    public function isNoRoute()
    {
        return $this->getPath() == self::NOROUTE;
    }

    public function getCurrentCmsId()
    {
        return $this->getPath() == 'cms/page/view' ? $this->request->getParam('page_id', $this->request->getParam('id', false)) : null;
    }

    public function getCurrentProductId()
    {
        if ($this->getPath() == 'catalog/product/view') {
            $product = $this->registry->registry('product');
            return $product && $product->getId() ? $product->getId() : null;
        }

        return null;
    }

    public function getCurrentCategoryId()
    {
        if ($this->getPath() == 'catalog/category/view') {
            $category = $this->registry->registry('current_category');
            return $category && $category->getId() ? $category->getId() : null;
        }

        return null;
    }

    /**
     * ORTHER FUNCIOTNS
     */
    public function getDateTimeObject()
    {
        return $this->datetime;
    }

    public function now()
    {
        return $this->getDateTimeObject()->gmtDate();
    }

    public function getConnection()
    {
        return $this->resource->getConnection();
    }

    /**
     * CONFIG FUNCTIONB
     */
    private function _saveConfig($path, $value, $scope = \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0)
    {
        $newData = [
            'scope' => $scope,
            'scope_id' => $scopeId,
            'path' => $path,
            'value' => $value
        ];

        if ($row = $this->_loadConfig($path, $scope, $scopeId)) {
            $this->getConnection()->update('core_config_data', $newData, [
                'config_id = ?' => $row['config_id']
            ]);

        } else {
            $this->getConnection()->insert('core_config_data', $newData);
        }
    }

    private function _loadConfig($path, $scope, $scopeId = 0)
    {
        $select = $this->getConnection()->select()
            ->from('core_config_data')
            ->where('path = ?', $path)
            ->where('scope = ?', $scope)
            ->where('scope_id = ?', $scopeId);

        return $this->getConnection()->fetchRow($select);
    }

    private function _loadConfigValue($path, $scope = \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0)
    {
        $row = $this->_loadConfig($path, $scope, $scopeId);

        return isset($row['value']) ? $row['value'] : null;
    }
}
