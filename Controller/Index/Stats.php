<?php
/**
 * Copyright © 2016 Dexa. All rights reserved.
 * See DEXA.txt for license details.
 */
namespace Dexa\Warmer\Controller\Index;

class Stats extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Dexa\Warmer\Model\UrlFactory
     */
    protected $warmerUrlFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Psr\Log\LoggerInterface $logger,
        \Dexa\Warmer\Model\UrlFactory $warmerUrlFactory
    ) {
        parent::__construct($context);

        $this->logger = $logger;
        $this->warmerUrlFactory = $warmerUrlFactory;
    }

    /**
     * Create customer account action
     *
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        $url = $this->getRequest()->getParam('url');

        if ($url) {
            /** @var \Dexa\Warmer\Model\Url $urlModel */
            $urlModel = $this->warmerUrlFactory->create();
            $urlModel->loadByUrl($url);

            if ($urlModel->getId()) {
                $path = $this->getRequest()->getParam('path');

                if ($path !== 'cms/noroute/index') {
                    $urlModel->incrementViewStats($path);
                    $urlModel->getResource()->save($urlModel);
                }
            }
        }

        exit (0);
    }
}
