<?php

namespace AuthorizeNet;

use AuthorizeNet\Service\SIM\RequestServiceInterface;
use Thelia\Model\Order;
use Thelia\Module\AbstractPaymentModule;

class AuthorizeNet extends AbstractPaymentModule
{
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
}
