<?php
/**
 * Copyright © 2015 Dexa. All rights reserved.
 * See LABELS.txt for license details.
 * http://dexamage.com
 */
namespace Dexa\Warmer\Observer\Frontend;

/**
 * Class WarmerDispatch
 * @package Dexa\Warmer\Observer\Frontend
 */
class WarmerDispatch implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Dexa\Warmer\Helper\Config
     */
    protected $config;

    /**
     * @var \Dexa\Warmer\Model\UrlFactory
     */
    protected $warmerUrlFactory;

    /**
     * WarmerPredispatch constructor.
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Dexa\Warmer\Helper\Config $config
     * @param \Dexa\Warmer\Model\UrlFactory $warmerUrlFactory
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Dexa\Warmer\Helper\Config $config,
        \Dexa\Warmer\Model\UrlFactory $warmerUrlFactory
    ) {
        $this->logger = $logger;
        $this->config = $config;
        $this->warmerUrlFactory = $warmerUrlFactory;
    }

    /**
     * @param $url
     */
    protected function _filter($url)
    {
        if (strpos($url, 'page_cache') !== false) return null;
        if (strpos($url, '.json') !== false) return null;
        if (strpos($url, '/static/') !== false) return null;
        if (strpos($url, '/rest/') !== false) return null;
        if (strpos($url, 'section/load/') !== false) return null;
        if (strpos($url, 'robots.txt') !== false) return null;
        if (strpos($url, '/rma/') !== false) return null;
        if (strpos($url, '/customer') !== false) return null;
        if (strpos($url, '/checkout') !== false) return null;
        if (strpos($url, '?SID') !== false) return null;
        if (strpos($url, 'dexa_warmer') !== false) return null;

        return $url;
    }

    /**
     * Predispath admin action controller
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Framework\App\RequestInterface $request */
        //$request = $observer->getData('request');

        if ($this->config->isNoRoute()) {
            return $this;
        }

        try {

            $url = $this->config->getUri();
            $url = $this->_filter($url);

            if ($url) {
                /** @var \Dexa\Warmer\Model\Url $urlModel */
                $urlModel = $this->warmerUrlFactory->create();
                $urlModel->loadByUrl($url);

                $urlModel->setUrl($url);
                $urlModel->setPath($this->config->getPath());
                $urlModel->setCmsId($this->config->getCurrentCmsId());
                $urlModel->setProductId($this->config->getCurrentProductId());
                $urlModel->setCategoryId($this->config->getCurrentCategoryId());
                $urlModel->setIgnoreUrl(0);

                $urlModel->getResource()->save($urlModel);
            }

        } catch (\Exception $exception) {

        }

        return $this;
    }
}
