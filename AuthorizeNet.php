<?php

namespace AuthorizeNet;

use AuthorizeNet\Service\SIM\SIMServiceInterface;
use Thelia\Model\Order;
use Thelia\Module\AbstractPaymentModule;

class AuthorizeNet extends AbstractPaymentModule
{
    public function pay(Order $order)
    {
        /** @var SIMServiceInterface $SIMService */
        $SIMService = $this->getContainer()->get('authorize_net.service.sim');

        return $this->generateGatewayFormResponse(
            $order,
            $SIMService->getGatewayURL(),
            $SIMService->getRequestFields($order)
        );
    }

    public function isValidPayment()
    {
        return true;
    }
}
