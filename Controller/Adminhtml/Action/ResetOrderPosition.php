<?php
/**
 *
 * Copyright © 2016 Dexa. All rights reserved.
 * See LABLES.txt for license details.
 */
namespace Dexa\Warmer\Controller\Adminhtml\Action;

class ResetOrderPosition extends AbstractAction
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
        $collection = $this->getCollection();
        foreach ($collection as $urlModel) {
            /** @var \Dexa\Warmer\Model\Url $urlModel */

            $urlModel->setVisitedAt($this->config->now());
            $urlModel->setOrderPosition($urlModel->getViewCount());

            $urlModel->getResource()->save($urlModel);
        }
    }
}
