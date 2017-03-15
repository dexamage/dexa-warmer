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
class CacheInvalidateCond extends CacheInvalidate
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $object = $observer->getEvent()->getObject();
        if ($object && $object instanceof \Magento\Framework\DataObject\IdentityInterface) {
            $tags = [];
            foreach ($object->getIdentities() as $tag) {
                $tags[] = $tag;
            }
            if (!empty($tags)) {
                if ($this->invalidate()) {
                    $this->logger->info('WarmerInvalidateCond by `' . $observer->getEvent()->getName() .'`, tags: `' . implode(',', $tags) . '`');
                }
            }
        }

        return $this;
    }
}
