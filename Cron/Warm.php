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
class Warm extends AbstractCron
{
    const AGENT = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

    /**
     * @return $this
     */
    public function execute()
    {
        if (!$this->config->isCronEnabled()) {
            return $this;
        }

        $this->logger->info('Warmer Cron');

        if ($this->config->isWarmerWorking()) {
            $this->logger->info('Warmer Working');

            if ($this->isCronTimeouted()) {
                $this->logger->info('Stop Cron, Timeout');
                $this->config->validateWarmer();
            }

            return $this;
        }

        if ($this->config->isWarmerInvalid() || $this->config->isCronNonStop()) {
            $this->logger->info('Warmer Cron Run');
            $this->config->updatedCronStartedTime();


            $time = time();
            $cnt = $this->warm();
            $delta = time() - $time;

            $this->logger->info("End Warmer count of url {$cnt} in {$delta} seconds");
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isCronTimeouted()
    {
        try {
            $timeout = $this->config->getCronTimeout();
            $startedTime = $this->config->getCronStartedTime();

            if ($timeout > 0 && $startedTime) {
                $currDate = $this->config->getDateTimeObject()->gmtTimestamp($startedTime);
                $maxOffset = $this->config->getDateTimeObject()->gmtTimestamp($this->config->now()) - $timeout;

                if ($currDate > 0 && $currDate < $maxOffset) {
                    return true;
                }
            }

        } catch (\Exception $ex) {
            $this->logger->error($ex->getTraceAsString());
        }

        return false;
    }

    /**
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    protected function getCollection()
    {
        $collection = $this->warmerUrlFactory->create()->getCollection();
        $collection->addFieldToFilter('ignore_url', 0);
        $collection->getSelect()->order('order_position DESC')->order('view_count DESC');

        $limit = $this->config->getCronPagesLimit();
        if ($limit > 0) {
            $collection->getSelect()->limit($limit);
        }

        $minOrder = $this->config->getCronMinOrder();
        if ($minOrder > 0) {
            $collection->addFieldToFilter('order_position', ['gteq' => $minOrder]);
        }

        return $collection;
    }

    /**
     * doWarm
     */
    public function warm($console = false)
    {
        $this->config->setWarmerWorking();

        $ch = $this->initCurl();
        $collection = $this->getCollection();

        $i = 0;
        $time = time();
        foreach ($collection as $urlModel) {
            /** @var \Dexa\Warmer\Model\Url $urlModel */
            if (!$this->config->isWarmerWorking()) {
                break;
            }

            try {
                $url = $urlModel->getUrl();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                $urlModel->incrementWarmStats($httpCode);
                $urlModel->getResource()->save($urlModel);

                $delta = time() - $time;
                $this->_logger("Dexa Warmer: {$i} +{$delta}s, grub url: {$url}", $console);
                $i++;
            } catch (\Exception $ex) {
                $this->_logger("Dexa Warmer: {$i} +{$delta}s, grub url: {$url}, error " . $ex->getMessage(), $console);
            }
        }
        $this->config->validateWarmer();

        return $i;
    }

    /**
     * @return resource
     */
    protected function initCurl()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, self::AGENT);

        return $ch;
    }

    /**
     * @param $message
     * @param bool $console
     */
    private function _logger($message, $console = false)
    {
        if ($console){
            echo $message . "\n";
        } else {
            $this->logger->info($message);
        }
    }
}

/*
[Informational 1xx]
100="Continue"
101="Switching Protocols"

[Successful 2xx]
200="OK"
201="Created"
202="Accepted"
203="Non-Authoritative Information"
204="No Content"
205="Reset Content"
206="Partial Content"

[Redirection 3xx]
300="Multiple Choices"
301="Moved Permanently"
302="Found"
303="See Other"
304="Not Modified"
305="Use Proxy"
306="(Unused)"
307="Temporary Redirect"

[Client Error 4xx]
400="Bad Request"
401="Unauthorized"
402="Payment Required"
403="Forbidden"
404="Not Found"
405="Method Not Allowed"
406="Not Acceptable"
407="Proxy Authentication Required"
408="Request Timeout"
409="Conflict"
410="Gone"
411="Length Required"
412="Precondition Failed"
413="Request Entity Too Large"
414="Request-URI Too Long"
415="Unsupported Media Type"
416="Requested Range Not Satisfiable"
417="Expectation Failed"

[Server Error 5xx]
500="Internal Server Error"
501="Not Implemented"
502="Bad Gateway"
503="Service Unavailable"
504="Gateway Timeout"
505="HTTP Version Not Supported"
*/
