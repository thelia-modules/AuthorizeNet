<?php

namespace AuthorizeNet\Config;

/**
 * Module configuration keys.
 */
class ConfigKeys
{
    /**
     * API login ID.
     * @var string
     */
    const API_LOGIN_ID = 'api_login_id';

    /**
     * Transaction key.
     * @var string
     */
    const TRANSACTION_KEY = 'transaction_key';

    /**
     * Hash value/salt for gateway response authentication.
     * @var string
     */
    const HASH_VALUE = 'hash_value';

    /**
     * Transaction version.
     * @var string
     */
    const TRANSACTION_VERSION = 'transaction_version';

    /**
     * Payment gateway URL.
     * @var string
     */
    const GATEWAY_URL = 'gateway_url';

    /**
     * The URL to use as the gateway callback.
     * @var string
     */
    const CALLBACK_URL = 'callback_url';

    /**
     * Method to be used to get a response from the gateway.
     * @var string
     */
    const GATEWAY_RESPONSE_TYPE = 'gateway_response_type';

    /**
     * Text for the link back to the store on the receipt page.
     * @var string
     */
    const RECEIPT_LINK_TEXT = 'receipt_link_text';

    /**
     * Whether the gateway should send a relay response even for decline, error and partial authorization.
     * @var string
     */
    const RELAY_RESPONSE_ALWAYS = 'relay_response_always';
}
