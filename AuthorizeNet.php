<?php

namespace AuthorizeNet;

use AuthorizeNet\Config\ConfigKeys;
use Symfony\Component\Routing\RouterInterface;
use Thelia\Model\Order;
use Thelia\Module\AbstractPaymentModule;
use Thelia\Tools\URL;

class AuthorizeNet extends AbstractPaymentModule
{
    const GATEWAY_URL = 'https://test.authorize.net/gateway/transact.dll';
    const TRANSACTION_VERSION = '3.1';

    public function pay(Order $order)
    {
        /** @var RouterInterface $router */
        $router = $this->getContainer()->get('router.' . static::getModuleCode());

        $fields = [];

        // base fields
        $fields['x_login'] = static::getConfigValue(ConfigKeys::API_LOGIN_ID);
        $fields['x_amount'] = $order->getTotalAmount();
        $fields['x_currency_code'] = $order->getCurrency()->getCode();
        $fields['x_show_form'] = 'PAYMENT_FORM';
        $fields['x_relay_response'] = 'FALSE';
        $fields['x_version'] = static::TRANSACTION_VERSION;

        // fingerprint
        $fields['x_fp_sequence'] = $order->getId();

        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $fields['x_fp_timestamp'] = $now->getTimestamp();

        $fingerprint
            = $fields['x_login']
            . '^'
            . $fields['x_fp_sequence']
            . '^'
            . $fields['x_fp_timestamp']
            . '^'
            . $fields['x_amount']
            . '^'
            . $fields['x_currency_code']
        ;

        $fields['x_fp_hash'] = hash_hmac('md5', $fingerprint, static::getConfigValue(ConfigKeys::TRANSACTION_KEY));

        // additional information
        // order
        $fields['x_invoice_num'] = $order->getRef();

        // customer
        $customer = $order->getCustomer();
        $fields['x_email'] = $customer->getEmail();
        $fields['x_cust_id'] = $customer->getRef();

        // billing address
        $billingAddress = $order->getOrderAddressRelatedByInvoiceOrderAddressId();
        $fields['x_first_name'] = $billingAddress->getFirstname();
        $fields['x_last_name'] = $billingAddress->getLastname();
        $fields['x_company'] = $billingAddress->getCompany();
        $fields['x_address']
            = $billingAddress->getAddress1() . ', '
            . $billingAddress->getAddress2() . ', '
            . $billingAddress->getAddress3();
        $fields['x_city'] = $billingAddress->getCity();
        $fields['x_zip'] = $billingAddress->getZipcode();
        $fields['x_country'] = $billingAddress->getCountry()->getTitle();
        $fields['x_phone'] = $billingAddress->getPhone();

        // delivery address
        $shippingAddress = $order->getOrderAddressRelatedByDeliveryOrderAddressId();
        $fields['x_ship_to_first_name'] = $shippingAddress->getFirstname();
        $fields['x_ship_to_last_name'] = $shippingAddress->getLastname();
        $fields['x_ship_to_company'] = $shippingAddress->getCompany();
        $fields['x_ship_to_address']
            = $shippingAddress->getAddress1() . ', '
            . $shippingAddress->getAddress2() . ', '
            . $shippingAddress->getAddress3();
        $fields['x_ship_to_city'] = $shippingAddress->getCity();
        $fields['x_ship_to_zip'] = $shippingAddress->getZipcode();
        $fields['x_ship_to_country'] = $shippingAddress->getCountry()->getTitle();

        // receipt link
        $fields['x_receipt_link_method'] = 'POST';

        $callbackURL = AuthorizeNet::getConfigValue(ConfigKeys::CALLBACK_URL);
        if (empty($callbackURL)) {
            $callbackURL = URL::getInstance()->absoluteUrl($router->generate('authorize_net.front.gateway.callback'));
        }
        $fields['x_receipt_link_url'] = $callbackURL;
        $fields['x_receipt_link_text'] = static::getConfigValue(ConfigKeys::RECEIPT_LINK_TEXT);

        return $this->generateGatewayFormResponse($order, static::GATEWAY_URL, $fields);
    }

    public function isValidPayment()
    {
        return true;
    }
}
