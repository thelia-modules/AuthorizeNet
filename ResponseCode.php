<?php

namespace AuthorizeNet;

/**
 * Authorize.Net payment gateway response codes.
 */
class ResponseCode
{
    /**
     * Transaction approved.
     * @var int
     */
    const APPROVED = 1;

    /**
     * Transaction declined.
     * @var int
     */
    const DECLINED = 2;

    /**
     * Error while processing the transaction.
     * @var int
     */
    const ERROR = 3;

    /**
     * Transaction held for review.
     * @var int
     */
    const HELD = 4;
}
