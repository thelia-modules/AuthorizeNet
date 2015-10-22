<?php

namespace AuthorizeNet\Controller\Front;

use AuthorizeNet\AuthorizeNet;
use AuthorizeNet\Service\SIM\ResponseServiceInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Thelia\Module\BasePaymentModuleController;

/**
 * Controller for requests from the payment gateway.
 */
class GatewayController extends BasePaymentModuleController
{
    protected function getModuleCode()
    {
        return AuthorizeNet::getModuleCode();
    }

    /**
     * Process the callback from the payment gateway.
     */
    public function callbackAction()
    {
        $response = $this->getRequest()->request->all();

        /** @var ResponseServiceInterface $SIMResponseService */
        $SIMResponseService = $this->getContainer()->get('authorize_net.service.sim.response');

        if (!$SIMResponseService->isResponseHashValid($response)) {
            throw new AccessDeniedHttpException('Invalid response hash.');
        }

        $order = $SIMResponseService->getOrderFromResponse($response);
        if ($order === null) {
            throw new NotFoundHttpException('Order not found.');
        }

        if ($SIMResponseService->payOrderFromResponse($response, $order)) {
            $this->redirectToSuccessPage($order->getId());
        } else {
            $this->redirectToFailurePage($order->getId(), $this->getTranslator()->trans('Payment error.'));
        }
    }
}
