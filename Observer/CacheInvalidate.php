<?php
/**
 * Copyright © 2015 Dexa. All rights reserved.
 * See DEXA.txt for license details.
 * http://dexamage.com
 */
namespace Dexa\Warmer\Observer;

/**
 * Class WarmerDispatch
 * @package Dexa\Warmer\Observer\Frontend
 */
class CacheInvalidate implements \Magento\Framework\Event\ObserverInterface
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
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->invalidate()) {
            $this->logger->info('WarmerInvalidate by `' . $observer->getEvent()->getName() . '`');
        }

        return $this;
    }

    /**
     * @return bool
     */
    protected function invalidate()
    {
        if ($this->config->isEnabled() && $this->config->isObservingCache() && !$this->config->isWarmerInvalid()) {
            $this->config->invalidateWarmer();
            return true;
        }

        return false;
    }
}
