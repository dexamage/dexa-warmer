<?php
/**
 * Copyright © 2015 Dexa. All rights reserved.
 * See DEXA.txt for license details.
 */
namespace Dexa\Warmer\Controller\Adminhtml\Action;

class MassDelete extends AbstractAction
{
    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->getFilteredCollection();

        $i = 0;
        /** @var \Dexa\Warmer\Model\Url $urlModel */
        foreach ($collection->getItems() as $urlModel) {
            $urlModel->getResource()->delete($urlModel);
            $i++;
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $i));

        return $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT)->setPath('dexa_warmer/url/grid');
    }
}
