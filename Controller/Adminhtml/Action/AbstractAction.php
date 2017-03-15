<?php
/**
 * Copyright © 2015 Dexa. All rights reserved.
 * See DEXA.txt for license details.
 */
namespace Dexa\Warmer\Controller\Adminhtml\Action;

abstract class AbstractAction extends \Magento\Backend\App\Action
{
    /**
     * Massactions filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Dexa\Warmer\Helper\Config
     */
    protected $config;

    /**
     * @var \Dexa\Warmer\Model\UrlFactory
     */
    protected $warmerUrlFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Dexa\Warmer\Helper\Config $config,
        \Dexa\Warmer\Model\UrlFactory $warmerUrlFactory
    ) {
        parent::__construct($context);

        $this->filter = $filter;
        $this->logger = $logger;
        $this->config = $config;
        $this->warmerUrlFactory = $warmerUrlFactory;
    }

    /**
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    protected function getCollection()
    {
        return $this->warmerUrlFactory->create()->getCollection();
    }

    /**
     * @return \Magento\Framework\Data\Collection\AbstractDb
     */
    protected function getFilteredCollection()
    {
        return $this->filter->getCollection($this->getCollection());
    }
}
