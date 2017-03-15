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
class Position extends AbstractCron
{
    /**
     * @return $this
     */
    public function execute()
    {
        if (!$this->config->isCronEnabled() || !$this->config->isStatsEnabled()) {
            return $this;
        }

        $this->logger->info('Warmer Position Cron');

        /** @var \Dexa\Warmer\Model\ResourceModel\Url\Collection $collection */
        $collection = $this->warmerUrlFactory->create()->getCollection();
        $collection->getSelect()->where('visited_at < date_sub(NOW(), interval 1 hour)');

        foreach ($collection as $urlModel) {
            /** @var \Dexa\Warmer\Model\Url $urlModel */

            $lastVisited = $this->config->getDateTimeObject()->gmtTimestamp($urlModel->getVisitedAt());
            $now = $this->config->getDateTimeObject()->gmtTimestamp($this->config->now());

            if ($now - $lastVisited > 3600) {
                $urlModel->setOrderPosition(0.99 * $urlModel->getOrderPosition());
                $urlModel->getResource()->save($urlModel);
            }
        }

        return $this;
    }
}