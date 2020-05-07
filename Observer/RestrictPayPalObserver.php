<?php
/**
 * Copyright © Betzal. All rights reserved.
 *
 * @category   Betzal
 * @package    Betzal_RestrictPayPal
 * @Author     jeyaraman.jawahar@gmail.com
 * @copyright  2020
 */
namespace Betzal\RestrictPayPal\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Psr\Log\LoggerInterface;
use Magento\Paypal\Model\Config;

class RestrictPayPalObserver implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * RestrictPayPalObserver constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger
    )
    {
        $this->logger = $logger;
    }

    /**
     * Restrict PayPal method
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        $event = $observer->getEvent();
        $method = $event->getMethodInstance();
        $result = $event->getResult();
        $paypal = false;
        $quote = $event->getQuote();
        if ($quote)
        {
            foreach ($quote->getAllVisibleItems() as $item)
            {
                if($item->getProduct()->getRestrictPaypalExpress()){
                    $paypal = true;
                    break;
                }
            }
            if($method->getCode() == Config::METHOD_EXPRESS && $paypal){
                $result->setData('is_available', false);
            }
        }
    }
}
