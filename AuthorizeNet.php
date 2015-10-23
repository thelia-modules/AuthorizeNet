<?php

namespace AuthorizeNet;

use AuthorizeNet\Config\ConfigKeys;
use AuthorizeNet\Service\SIM\RequestServiceInterface;
use Thelia\Model\Order;
use Thelia\Module\AbstractPaymentModule;

class AuthorizeNet extends AbstractPaymentModule
{
    protected static $defaultConfigValues = [
        ConfigKeys::GATEWAY_URL => 'https://secure2.authorize.net/gateway/transact.dll',
        ConfigKeys::TRANSACTION_VERSION => '3.1',
    ];

    public function pay(Order $order)
    {
        /** @var RequestServiceInterface $SIMRequestService */
        $SIMRequestService = $this->getContainer()->get('authorize_net.service.sim.request');

        return $this->generateGatewayFormResponse(
            $order,
            $SIMRequestService->getGatewayURL(),
            $SIMRequestService->getRequestFields($order, $this->getRequest())
        );
    }

    public function isValidPayment()
    {
        return true;
    }

    public static function getConfigValue($variableName, $defaultValue = null, $valueLocale = null)
    {
        if ($defaultValue === null && isset(static::$defaultConfigValues[$variableName])) {
            $defaultValue = static::$defaultConfigValues[$variableName];
        }

        return parent::getConfigValue($variableName, $defaultValue, $valueLocale);
    }
}
