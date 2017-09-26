<?php
namespace Training\Helloworld\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class PredispatchLogUrl implements ObserverInterface {

    protected $logger;


    public function __construct(LoggerInterface $loggerInterface)
    {
        $this->logger = $loggerInterface;
    }


    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $url = $observer->getEvent()->getRequest()->getPathInfo();
        $this->logger->debug($url);
    }

}