<?php

namespace AuthorizeNet\Form;

use AuthorizeNet\AuthorizeNet;
use AuthorizeNet\Config\ConfigKeys;
use AuthorizeNet\Config\GatewayResponseType;
use Thelia\Form\BaseForm;

/**
 * Module configuration form.
 */
class ConfigForm extends BaseForm
{
    public function getName()
    {
        return 'authorize_net_config';
    }

    protected function buildForm()
    {
        $this->formBuilder
            ->add(
                ConfigKeys::API_LOGIN_ID,
                'text',
                [
                    'label' => $this->translator->trans('API login ID'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::API_LOGIN_ID),
                ]
            )
            ->add(
                ConfigKeys::TRANSACTION_KEY,
                'text',
                [
                    'label' => $this->translator->trans('Transaction key'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::TRANSACTION_KEY),
                ]
            )
            ->add(
                ConfigKeys::HASH_VALUE,
                'text',
                [
                    'label' => $this->translator->trans('MD5 hash value'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::HASH_VALUE),
                ]
            )
            ->add(
                ConfigKeys::TRANSACTION_VERSION,
                'text',
                [
                    'label' => $this->translator->trans('Transaction version'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::TRANSACTION_VERSION),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::GATEWAY_URL,
                'text',
                [
                    'label' => $this->translator->trans('Payment gateway URL'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::GATEWAY_URL),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::CALLBACK_URL,
                'text',
                [
                    'label' => $this->translator->trans('Gateway callback URL'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::CALLBACK_URL),
                ]
            )
            ->add(
                ConfigKeys::GATEWAY_RESPONSE_TYPE,
                'choice',
                [
                    'label' => $this->translator->trans('Gateway response type'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::GATEWAY_RESPONSE_TYPE),
                    'choices' => [
                        GatewayResponseType::NONE => $this->translator->trans(
                            'No response'
                        ),
                        GatewayResponseType::RECEIPT_LINK => $this->translator->trans(
                            'Link back to the store on the receipt page'
                        ),
                        GatewayResponseType::RELAY_RESPONSE => $this->translator->trans(
                            'Redirection to the store'
                        ),
                    ]
                ]
            )
            ->add(
                ConfigKeys::RECEIPT_LINK_TEXT,
                'text',
                [
                    'label' => $this->translator->trans('Receipt link text'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::RECEIPT_LINK_TEXT),
                ]
            )
            ->add(
                ConfigKeys::RELAY_RESPONSE_ALWAYS,
                'checkbox',
                [
                    'label' => $this->translator->trans('Redirect in all cases'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::RELAY_RESPONSE_ALWAYS) == 1,
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::RETURN_POLICY_URL,
                'text',
                [
                    'label' => $this->translator->trans('Return policy URL'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::RETURN_POLICY_URL),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::HOSTED_PAYMENT_FORM_HEADER_HTML,
                'text',
                [
                    'label' => $this->translator->trans('Payment form header text'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::HOSTED_PAYMENT_FORM_HEADER_HTML),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::HOSTED_PAYMENT_FORM_HEADER2_HTML,
                'text',
                [
                    'label' => $this->translator->trans('Payment form top of header text'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::HOSTED_PAYMENT_FORM_HEADER2_HTML),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::HOSTED_PAYMENT_FORM_FOOTER_HTML,
                'text',
                [
                    'label' => $this->translator->trans('Payment form footer text'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::HOSTED_PAYMENT_FORM_FOOTER_HTML),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::HOSTED_PAYMENT_FORM_FOOTER2_HTML,
                'text',
                [
                    'label' => $this->translator->trans('Payment form top of footer text'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::HOSTED_PAYMENT_FORM_FOOTER2_HTML),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::HOSTED_COLOR_BACKGROUND,
                'text',
                [
                    'label' => $this->translator->trans('Hosted forms background color'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::HOSTED_COLOR_BACKGROUND),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::HOSTED_COLOR_LINK,
                'text',
                [
                    'label' => $this->translator->trans('Hosted forms hyperlinks color'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::HOSTED_COLOR_LINK),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::HOSTED_COLOR_TEXT,
                'text',
                [
                    'label' => $this->translator->trans('Hosted forms text color'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::HOSTED_COLOR_TEXT),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::HOSTED_LOGO_URL,
                'text',
                [
                    'label' => $this->translator->trans('Hosted forms logo URL'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::HOSTED_LOGO_URL),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::HOSTED_BACKGROUND_URL,
                'text',
                [
                    'label' => $this->translator->trans('Hosted forms background image URL'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::HOSTED_BACKGROUND_URL),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::HOSTED_CANCEL_URL,
                'text',
                [
                    'label' => $this->translator->trans('Payment form cancel link target URL'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::HOSTED_CANCEL_URL),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::HOSTED_CANCEL_URL_TEXT,
                'text',
                [
                    'label' => $this->translator->trans('Payment form cancel link text'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::HOSTED_CANCEL_URL_TEXT),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::HOSTED_PAYMENT_FORM_FONT_FAMILY,
                'text',
                [
                    'label' => $this->translator->trans('Payment form font family'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::HOSTED_PAYMENT_FORM_FONT_FAMILY),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::HOSTED_PAYMENT_FORM_FONT_SIZE,
                'text',
                [
                    'label' => $this->translator->trans('Payment form font size'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::HOSTED_PAYMENT_FORM_FONT_SIZE),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::HOSTED_PAYMENT_FORM_HEADER_COLOR_TEXT,
                'text',
                [
                    'label' => $this->translator->trans('Payment form header text color'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::HOSTED_PAYMENT_FORM_HEADER_COLOR_TEXT),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::HOSTED_PAYMENT_FORM_HEADER_FONT_FAMILY,
                'text',
                [
                    'label' => $this->translator->trans('Payment form header font family'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::HOSTED_PAYMENT_FORM_HEADER_FONT_FAMILY),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::HOSTED_PAYMENT_FORM_HEADER_FONT_SIZE,
                'text',
                [
                    'label' => $this->translator->trans('Payment form header font size'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::HOSTED_PAYMENT_FORM_HEADER_FONT_SIZE),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::HOSTED_PAYMENT_FORM_HEADER_FONT_BOLD,
                'checkbox',
                [
                    'label' => $this->translator->trans('Payment form header bold font'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::HOSTED_PAYMENT_FORM_HEADER_FONT_BOLD) == 1,
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::HOSTED_PAYMENT_FORM_HEADER_FONT_ITALIC,
                'checkbox',
                [
                    'label' => $this->translator->trans('Payment form header italic font'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::HOSTED_PAYMENT_FORM_HEADER_FONT_ITALIC) == 1,
                    'required' => false,
                ]
            );
    }
}
