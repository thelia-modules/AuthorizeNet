<?php

namespace AuthorizeNet;

use AuthorizeNet\Config\ConfigKeys;
use Thelia\Model\Order;
use Thelia\Module\AbstractPaymentModule;

class AuthorizeNet extends AbstractPaymentModule
{
    const GATEWAY_URL = 'https://test.authorize.net/gateway/transact.dll';
    const TRANSACTION_VERSION = '3.1';

    public function pay(Order $order)
    {
        $fields = [];

        $fields['x_login'] = static::getConfigValue(ConfigKeys::API_LOGIN_ID);
        $fields['x_amount'] = $order->getTotalAmount();
        $fields['x_currency_code'] = $order->getCurrency()->getCode();
        $fields['x_show_form'] = 'PAYMENT_FORM';
        $fields['x_relay_response'] = 'FALSE';
        $fields['x_version'] = static::TRANSACTION_VERSION;

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

        return $this->generateGatewayFormResponse($order, static::GATEWAY_URL, $fields);
    }

    public function isValidPayment()
    {
        return true;
    }
}
