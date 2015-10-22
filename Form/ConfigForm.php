<?php

namespace AuthorizeNet\Form;

use AuthorizeNet\AuthorizeNet;
use AuthorizeNet\Config\ConfigKeys;
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
                ]
            )
            ->add(
                ConfigKeys::GATEWAY_URL,
                'text',
                [
                    'label' => $this->translator->trans('Payement gateway URL'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::GATEWAY_URL),
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
                ConfigKeys::RECEIPT_LINK_TEXT,
                'text',
                [
                    'label' => $this->translator->trans('Receipt link text'),
                    'data' => AuthorizeNet::getConfigValue(ConfigKeys::RECEIPT_LINK_TEXT),
                ]
            );
    }
}
