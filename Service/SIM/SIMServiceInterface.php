<?php

namespace AuthorizeNet\Service\SIM;

use Thelia\Model\Order;

/**
 * Methods for Authorize.Net SIM (Server Integration Method, i.e. the hosted payment form) integration.
 */
interface SIMServiceInterface
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
     * @return array Request fields.
     */
    public function getRequestFields(Order $order);

    /**
     * Add the timestamp and fingerprint to a request.
     * Replaces any previous timestamp and fingerprint.
     * @param array $request Request fields.
     * @return array Request fields with the added fingerprint.
     */
    public function setRequestFingerprint(array $request);

    /**
     * Authenticate a gateway response from the hash value.
     * @param array $response Response fields.
     * @return bool Whether the response is valid.
     */
    public function isResponseHashValid(array $response);

    /**
     * Get the order associated with a gateway response.
     * @param array $response Response fields.
     * @return Order|null Order Order associated to the response, if found.
     */
    public function getOrderFromResponse(array $response);

    /**
     * Change the order status depending on the gateway response.
     * @param array $response Response fields.
     * @param Order $order Order to process.
     * @return bool Whether the order was paid.
     */
    public function payOrderFromResponse(array $response, Order $order);
}
