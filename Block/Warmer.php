<?php
/**
 * Copyright © 2015 Dexa. All rights reserved.
 * See DEXA.txt for license details.
 */
namespace Dexa\Warmer\Block;

use Magento\Framework\View\Element\Template;

/**
 * class Who
 */
class Warmer extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Dexa\Warmer\Helper\Config
     */
    protected $config;

    /**
     * Warmer constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Dexa\Warmer\Helper\Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
    }

    /**
     * isEnabled
     */
    public function isEnabled()
    {
        return $this->config->isStatsEnabled() && !$this->config->isNoRoute();
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->config->getPath();
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->config->getUri();
    }

}
