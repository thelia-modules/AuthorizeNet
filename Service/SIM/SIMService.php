<?php

namespace AuthorizeNet\Service\SIM;

use AuthorizeNet\AuthorizeNet;
use AuthorizeNet\Config\ConfigKeys;
use AuthorizeNet\ResponseCode;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Model\Order;
use Thelia\Model\OrderQuery;
use Thelia\Model\OrderStatus;
use Thelia\Model\OrderStatusQuery;
use Thelia\Tools\URL;

/**
 * SIM (Server Integration Method) implementation.
 */
class SIMService implements SIMServiceInterface
{
    /**
     * Router for this module.
     * @var RouterInterface
     */
    protected $moduleRouter;

    /**
     * URL tools.
     * @var URL
     */
    protected $URLTools;

    /**
     * Event dispatcher.
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param RouterInterface $moduleRouter Router for this module.
     * @param URL $URLTools URL tools.
     * @param EventDispatcherInterface $eventDispatcher Event dispatcher.
     */
    public function __construct(
        RouterInterface $moduleRouter,
        URL $URLTools,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->moduleRouter = $moduleRouter;
        $this->URLTools = $URLTools;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getGatewayURL()
    {
        return AuthorizeNet::getConfigValue(ConfigKeys::GATEWAY_URL);
    }

    public function getCallbackURL()
    {
        $callbackURL = AuthorizeNet::getConfigValue(ConfigKeys::CALLBACK_URL);
        if (empty($callbackURL)) {
            $callbackURL = $this->URLTools->absoluteUrl(
                $this->moduleRouter->generate('authorize_net.front.gateway.callback')
            );
        }

        return $callbackURL;
    }

    public function getRequestFields(Order $order)
    {
        $request = [];

        // base fields
        $request['x_login'] = AuthorizeNet::getConfigValue(ConfigKeys::API_LOGIN_ID);
        $request['x_show_form'] = 'PAYMENT_FORM';
        $request['x_relay_response'] = 'FALSE';
        $request['x_version'] = AuthorizeNet::getConfigValue(ConfigKeys::TRANSACTION_VERSION);
        $request['x_amount'] = $order->getTotalAmount();
        $request['x_currency_code'] = $order->getCurrency()->getCode();

        // order
        $request['x_invoice_num'] = $order->getRef();

        // customer
        $customer = $order->getCustomer();
        $request['x_email'] = $customer->getEmail();
        $request['x_cust_id'] = $customer->getRef();

        // billing address
        $billingAddress = $order->getOrderAddressRelatedByInvoiceOrderAddressId();
        $request['x_first_name'] = $billingAddress->getFirstname();
        $request['x_last_name'] = $billingAddress->getLastname();
        $request['x_company'] = $billingAddress->getCompany();
        $request['x_address']
            = $billingAddress->getAddress1() . ', '
            . $billingAddress->getAddress2() . ', '
            . $billingAddress->getAddress3();
        $request['x_city'] = $billingAddress->getCity();
        $request['x_zip'] = $billingAddress->getZipcode();
        $request['x_country'] = $billingAddress->getCountry()->getTitle();
        $request['x_phone'] = $billingAddress->getPhone();

        // delivery address
        $shippingAddress = $order->getOrderAddressRelatedByDeliveryOrderAddressId();
        $request['x_ship_to_first_name'] = $shippingAddress->getFirstname();
        $request['x_ship_to_last_name'] = $shippingAddress->getLastname();
        $request['x_ship_to_company'] = $shippingAddress->getCompany();
        $request['x_ship_to_address']
            = $shippingAddress->getAddress1() . ', '
            . $shippingAddress->getAddress2() . ', '
            . $shippingAddress->getAddress3();
        $request['x_ship_to_city'] = $shippingAddress->getCity();
        $request['x_ship_to_zip'] = $shippingAddress->getZipcode();
        $request['x_ship_to_country'] = $shippingAddress->getCountry()->getTitle();

        // receipt link
        $request['x_receipt_link_method'] = 'POST';
        $request['x_receipt_link_url'] = $this->getCallbackURL();
        $request['x_receipt_link_text'] = AuthorizeNet::getConfigValue(ConfigKeys::RECEIPT_LINK_TEXT);

        // fingerprint
        $request['x_fp_sequence'] = $order->getId();
        $request = $this->setRequestFingerprint($request);

        return $request;
    }

    public function setRequestFingerprint(array $request)
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $request['x_fp_timestamp'] = $now->getTimestamp();

        $fingerprint
            = $request['x_login']
            . '^'
            . $request['x_fp_sequence']
            . '^'
            . $request['x_fp_timestamp']
            . '^'
            . $request['x_amount']
            . '^'
            . $request['x_currency_code'];

        $request['x_fp_hash'] = hash_hmac(
            'md5',
            $fingerprint,
            AuthorizeNet::getConfigValue(ConfigKeys::TRANSACTION_KEY)
        );

        return $request;
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
