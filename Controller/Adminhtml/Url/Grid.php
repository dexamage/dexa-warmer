<?php
/**
 *
 * Copyright © 2016 Dexa. All rights reserved.
 * See LABLES.txt for license details.
 */
namespace Dexa\Warmer\Controller\Adminhtml\Url;

class Grid extends \Magento\Backend\App\Action
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var \Dexa\Warmer\Helper\Config
     */
    protected $config;

    /**
     * @var \Dexa\Warmer\Model\Url
     */
    protected $warmerUrl;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Dexa\Warmer\Helper\Config $config,
        \Dexa\Warmer\Model\Url $warmerUrl
    ) {
        parent::__construct($context);

        $this->logger = $logger;
        $this->_resultPageFactory = $resultPageFactory;

        $this->config = $config;
        $this->warmerUrl = $warmerUrl;
    }

    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Dexa_Warmer::warmer');
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->addBreadcrumb(__('Dexa Warmer'), __('Dexa Warmer'));

        return $resultPage;
    }
}
