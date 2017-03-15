<?php
/**
 * Copyright © 2015 Dexa. All rights reserved.
 * See LABELS.txt for license details.
 */
namespace Dexa\Warmer\Cron;

/**
 * Class Warmer
 * @package Dexa\Warmer\Cron
 */
class AbstractCron
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Dexa\Warmer\Helper\Config
     */
    protected $config;

    /**
     * @var \Dexa\Warmer\Helper\Warm
     */
    protected $warm;

    /**
     * @var \Dexa\Warmer\Model\UrlFactory
     */
    protected $warmerUrlFactory;

    /**
     * AbstractCron constructor.
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
}