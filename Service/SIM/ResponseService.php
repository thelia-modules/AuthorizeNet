<?php

namespace AuthorizeNet\Service\SIM;

use AuthorizeNet\AuthorizeNet;
use AuthorizeNet\Config\ConfigKeys;
use AuthorizeNet\ResponseCode;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Model\Order;
use Thelia\Model\OrderQuery;
use Thelia\Model\OrderStatus;
use Thelia\Model\OrderStatusQuery;

/**
 * Implementation of the SIM (Server Integration Method) gateway response processing.
 */
class ResponseService implements ResponseServiceInterface
{
    /**
     * Event dispatcher.
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher Event dispatcher.
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function isResponseHashValid(array $response)
    {
        $gatewayHash = $response['x_MD5_Hash'];

        $hashValue = AuthorizeNet::getConfigValue(ConfigKeys::HASH_VALUE);
        $APILoginID = AuthorizeNet::getConfigValue(ConfigKeys::API_LOGIN_ID);
        $transactionID = $response['x_trans_id'];
        $amount = $response['x_amount'];

        $localHash = md5($hashValue . $APILoginID . $transactionID . $amount);

        return strtolower($gatewayHash) === strtolower($localHash);
    }

    public function getOrderFromResponse(array $response)
    {
        return OrderQuery::create()->findOneByRef(
            $response['x_invoice_num']
        );
    }

    public function payOrderFromResponse(array $response, Order $order)
    {
        $orderEvent = new OrderEvent($order);

        $responseCode = $response['x_response_code'];
        switch ($responseCode) {
            case ResponseCode::APPROVED:
                $orderStatusPaid = OrderStatusQuery::create()->findOneByCode(OrderStatus::CODE_PAID);
                $orderEvent->setStatus($orderStatusPaid->getId());
                $this->eventDispatcher->dispatch(TheliaEvents::ORDER_UPDATE_STATUS, $orderEvent);
                return true;
            case ResponseCode::DECLINED:
            default:
                return false;
        }
    }
}
