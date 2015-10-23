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

    /**
     * Merchant's return policy URL.
     * @var string
     */
    const RETURN_POLICY_URL = 'return_policy_url';

    /**
     * Payment form header text.
     * @var string
     */
    const HOSTED_PAYMENT_FORM_HEADER_HTML = 'hosted_payment_form_header_html';

    /**
     * Payment form top of header text.
     * @var string
     */
    const HOSTED_PAYMENT_FORM_HEADER2_HTML = 'hosted_payment_form_header2_html';

    /**
     * Payment form footer text.
     * @var string
     */
    const HOSTED_PAYMENT_FORM_FOOTER_HTML = 'hosted_payment_form_footer_html';

    /**
     * Payment form top of footer text.
     * @var string
     */
    const HOSTED_PAYMENT_FORM_FOOTER2_HTML = 'hosted_payment_form_footer2_html';

    /**
     * Payment form/receipt page background color.
     * @var string
     */
    const HOSTED_COLOR_BACKGROUND = 'hosted_color_background';

    /**
     * Payment form/receipt page hyperlinks color.
     * @var string
     */
    const HOSTED_COLOR_LINK = 'hosted_color_link';

    /**
     * Payment form/receipt page text color.
     * @var string
     */
    const HOSTED_COLOR_TEXT = 'hosted_color_text';

    /**
     * Payment form/receipt page logo URL.
     * @var string
     */
    const HOSTED_LOGO_URL = 'hosted_logo_url';

    /**
     * Payment form/receipt page background image URL.
     * @var string
     */
    const HOSTED_BACKGROUND_URL = 'hosted_background_url';

    /**
     * Payment form cancel link target URL.
     * @var string
     */
    const HOSTED_CANCEL_URL = 'hosted_cancel_url';

    /**
     * Payment form cancel link text.
     * @var string
     */
    const HOSTED_CANCEL_URL_TEXT = 'hosted_cancel_url_text';

    /**
     * Payment form font family.
     * @var string
     */
    const HOSTED_PAYMENT_FORM_FONT_FAMILY = 'hosted_payment_form_font_family';

    /**
     * Payment form font size.
     * @var string
     */
    const HOSTED_PAYMENT_FORM_FONT_SIZE = 'hosted_payment_form_font_size';

    /**
     * Payment form header text color.
     * @var string
     */
    const HOSTED_PAYMENT_FORM_HEADER_COLOR_TEXT = 'hosted_payment_form_header_color_text';

    /**
     * Payment form header font family.
     * @var string
     */
    const HOSTED_PAYMENT_FORM_HEADER_FONT_FAMILY = 'hosted_payment_form_header_font_family';

    /**
     * Payment form header font size.
     * @var string
     */
    const HOSTED_PAYMENT_FORM_HEADER_FONT_SIZE = 'hosted_payment_form_header_font_size';

    /**
     * Whether the payment form header should be in bold text.
     * @var string
     */
    const HOSTED_PAYMENT_FORM_HEADER_FONT_BOLD = 'hosted_payment_form_header_font_bold';

    /**
     * Whether the payment form header should be in italicized text.
     * @var string
     */
    const HOSTED_PAYMENT_FORM_HEADER_FONT_ITALIC = 'hosted_payment_form_header_font_bold';
}
