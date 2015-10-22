<?php

namespace AuthorizeNet\Service\SIM;

use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\Order;

/**
 * Service to build SIM (Server Integration Method) payment form requests.
 */
interface RequestServiceInterface
{
    /**
     * @return string Payment gateway URL.
     */
    public function getGatewayURL();

    /**
     * @return string Callback URL to be called by the payment gateway.
     */
    public function getCallbackURL();

    /**
     * Build the payment form request fields.
     * @param Order $order Order to send for payment.
     * @param Request $httpRequest HTTP request.
     * @return array Request fields.
     */
    public function getRequestFields(Order $order, Request $httpRequest);
}
