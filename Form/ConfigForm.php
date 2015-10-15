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
            );
    }
}
