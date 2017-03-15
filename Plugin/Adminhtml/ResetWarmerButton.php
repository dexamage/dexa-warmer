<?php
/**
 * Copyright © 2015 Dexa. All rights reserved.
 * See Dexa.txt for license details.
 */
namespace Dexa\Warmer\Plugin\Adminhtml;

/**
 * Class ResetWarmerButton
 * @package Dexa\Warmer\Plugin\Adminhtml
 */
class ResetWarmerButton
{
    /**
     * @var \Dexa\Warmer\Block\Adminhtml\ResetWarmerButton
     */
    protected $resetWarmerButton;

    /**
     * CacheWarmerReset constructor.
     * @param \Dexa\Warmer\Helper\Config $config
     */
    public function __construct(
        \Dexa\Warmer\Block\Adminhtml\ResetWarmerButton $resetWarmerButton
    ) {
        $this->resetWarmerButton = $resetWarmerButton;
    }

    /**
     * @param \Magento\Backend\Block\Cache $subject
     * @return null
     */
    public function beforeGetCreateUrl(
        \Magento\Backend\Block\Cache $subject
    ){
        $subject->addButton(
            'reset_warmer',
            $this->resetWarmerButton->getButtonData('cache'),
            0,
            0
        );

        return null;
    }
}