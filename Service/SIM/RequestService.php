<?php

namespace AuthorizeNet\Service\SIM;

use AuthorizeNet\AuthorizeNet;
use AuthorizeNet\Config\ConfigKeys;
use Symfony\Component\Routing\RouterInterface;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\Customer;
use Thelia\Model\Order;
use Thelia\Model\OrderAddress;
use Thelia\Tools\URL;

/**
 * Implementation of the SIM (Server Integration Method) payment form request builder.
 */
class RequestService implements RequestServiceInterface
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
     * @param RouterInterface $moduleRouter Router for this module.
     * @param URL $URLTools URL tools.
     */
    public function __construct(
        RouterInterface $moduleRouter,
        URL $URLTools
    ) {
        $this->moduleRouter = $moduleRouter;
        $this->URLTools = $URLTools;
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

    public function getRequestFields(Order $order, Request $httpRequest)
    {
        $request = [];

        $this->addBaseFields($request, $order);

        $customer = $order->getCustomer();
        $this->addCustomerFields($request, $customer);

        $this->addCustomerIPFields($request, $httpRequest);

        $billingAddress = $order->getOrderAddressRelatedByInvoiceOrderAddressId();
        $this->addBillingAddressFields($request, $billingAddress);

        $shippingAddress = $order->getOrderAddressRelatedByDeliveryOrderAddressId();
        $this->addShippingAddressFields($request, $shippingAddress);

        $this->addItemizedOrderFields($request, $order);

        $this->addReceiptLinkFields($request);

        $this->addFingerprintFields($request);

        return $request;
    }

    protected function addBaseFields(array &$request, Order $order)
    {
        $request['x_login'] = AuthorizeNet::getConfigValue(ConfigKeys::API_LOGIN_ID);
        $request['x_show_form'] = 'PAYMENT_FORM';
        $request['x_relay_response'] = 'FALSE';
        $request['x_version'] = AuthorizeNet::getConfigValue(ConfigKeys::TRANSACTION_VERSION);
        $request['x_amount'] = $order->getTotalAmount();
        $request['x_currency_code'] = $order->getCurrency()->getCode();
        $request['x_invoice_num'] = $order->getRef();
        $request['x_fp_sequence'] = $order->getId();
    }

    protected function addCustomerFields(array &$request, Customer $customer)
    {
        $request['x_email'] = $customer->getEmail();
        $request['x_cust_id'] = $customer->getRef();
    }

    protected function addCustomerIPFields(array &$request, Request $httpRequest)
    {
        $request['x_customer_ip'] = $httpRequest->getClientIp();
    }

    protected function addBillingAddressFields(array &$request, OrderAddress $address)
    {
        $request['x_first_name'] = $address->getFirstname();
        $request['x_last_name'] = $address->getLastname();
        $request['x_company'] = $address->getCompany();
        $request['x_address']
            = $address->getAddress1() . ', '
            . $address->getAddress2() . ', '
            . $address->getAddress3();
        $request['x_city'] = $address->getCity();
        $request['x_zip'] = $address->getZipcode();
        $request['x_country'] = $address->getCountry()->getTitle();
        $request['x_phone'] = $address->getPhone();
    }

    protected function addShippingAddressFields(array &$request, OrderAddress $address)
    {
        $request['x_ship_to_first_name'] = $address->getFirstname();
        $request['x_ship_to_last_name'] = $address->getLastname();
        $request['x_ship_to_company'] = $address->getCompany();
        $request['x_ship_to_address']
            = $address->getAddress1() . ', '
            . $address->getAddress2() . ', '
            . $address->getAddress3();
        $request['x_ship_to_city'] = $address->getCity();
        $request['x_ship_to_zip'] = $address->getZipcode();
        $request['x_ship_to_country'] = $address->getCountry()->getTitle();
    }

    protected function addItemizedOrderFields(array &$request, Order $order)
    {
        $items = [];

        foreach ($order->getOrderProducts() as $orderProduct) {
            $items[]
                = $orderProduct->getProductRef()
                . '<|>'
                . $orderProduct->getTitle()
                . '<|>'
                . $orderProduct->getDescription()
                . '<|>'
                . $orderProduct->getQuantity()
                . '<|>'
                . ($orderProduct->getWasInPromo() ? $orderProduct->getPromoPrice() : $orderProduct->getPrice())
                . '<|>';
        }

        $request['x_line_item'] = $items;
    }

    protected function addReceiptLinkFields(array &$request)
    {
        $request['x_receipt_link_method'] = 'POST';
        $request['x_receipt_link_url'] = $this->getCallbackURL();
        $request['x_receipt_link_text'] = AuthorizeNet::getConfigValue(ConfigKeys::RECEIPT_LINK_TEXT);
    }

    protected function addFingerprintFields(array &$request)
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
    }
}
