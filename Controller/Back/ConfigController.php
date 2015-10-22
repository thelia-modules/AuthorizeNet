<?php

namespace AuthorizeNet\Controller\Back;

use AuthorizeNet\AuthorizeNet;
use AuthorizeNet\Config\ConfigKeys;
use Symfony\Component\HttpFoundation\Response;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;

/**
 * Back-office module configuration controller.
 */
class ConfigController extends BaseAdminController
{
    /**
     * Configuration fields to save directly.
     * @var array
     */
    protected $fieldsToSave = [
        ConfigKeys::API_LOGIN_ID,
        ConfigKeys::TRANSACTION_KEY,
        ConfigKeys::HASH_VALUE,
        ConfigKeys::TRANSACTION_VERSION,
        ConfigKeys::GATEWAY_URL,
        ConfigKeys::CALLBACK_URL,
        ConfigKeys::RECEIPT_LINK_TEXT,
    ];

    /**
     * Save the module configuration.
     * @return Response
     */
    public function saveAction()
    {
        $authResponse = $this->checkAuth(AdminResources::MODULE, AuthorizeNet::getModuleCode(), AccessManager::UPDATE);
        if (null !== $authResponse) {
            return $authResponse;
        }

        $baseForm = $this->createForm('authorize_net.form.config');

        $form = $this->validateForm($baseForm, 'POST');

        foreach ($this->fieldsToSave as $field) {
            AuthorizeNet::setConfigValue($field, $form->get($field)->getData());
        }

        if ($this->getRequest()->get('save_mode') === 'close') {
            return $this->generateRedirectFromRoute('admin.module');
        } else {
            return $this->generateRedirectFromRoute(
                'admin.module.configure',
                [],
                [
                    'module_code' => AuthorizeNet::getModuleCode(),
                ]
            );
        }
    }
}
