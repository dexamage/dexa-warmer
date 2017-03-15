<?php
/**
 *
 * Copyright © 2016 Dexa. All rights reserved.
 * See LABLES.txt for license details.
 */
namespace Dexa\Warmer\Controller\Adminhtml\Action;

class ResetWarmer extends AbstractAction
{
    /**
     * Check ACL permissions
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }

    /**
     * execute
     */
    public function execute()
    {
        if ($this->config->isWarmerValid()) {
            $this->config->invalidateWarmer();
            $this->messageManager->addSuccessMessage(__('Warm Job was added to cron queue'));
            $this->logger->info(__('Warm Job was added to cron queue'));

        } else {
            $this->config->validateWarmer();
            $this->messageManager->addSuccessMessage(__('Warm Job was stopped'));
            $this->logger->info(__('Warm Job was stopped'));
        }

        $back = $this->getRequest()->getParam('back');

        if ($back == 'cache') {
            return $this->_redirect('adminhtml/cache/index');
        }

        return $this->_redirect('dexa_warmer/url/grid');
    }
}
