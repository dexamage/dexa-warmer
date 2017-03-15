<?php
/**
 * Copyright © 2015 Dexa. All rights reserved.
 * See DEXA.txt for license details.
 */
namespace Dexa\Warmer\Block\Adminhtml;

class ResetWarmerButton implements \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface
{
    /**
     * Url Builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Dexa\Warmer\Helper\Config
     */
    protected $config;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Dexa\Warmer\Helper\Config $config
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->config = $config;
    }

    /**
     * @return array
     * @codeCoverageIgnore
     */
    public function getButtonData($back = null)
    {
        $data = $back ? ['back' => $back] : [];
        $url = $this->urlBuilder->getUrl('dexa_warmer/action/resetWarmer', $data);

        $subTitle = 'Start Warm Job';
        $subTitle = $this->config->isWarmerInvalid() ? 'Stop Warm Job (Status Planned)' : $subTitle;
        $subTitle = $this->config->isWarmerWorking() ? 'Stop Warm Job (Status Working)' : $subTitle;

        return [
            'label' => __($subTitle),
            'class' => 'add primary',
            'on_click' => sprintf("location.href = '%s';", $url)
        ];
    }
}
