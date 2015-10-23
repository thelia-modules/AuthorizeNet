<?php

namespace AuthorizeNet\Controller\Front;

use AuthorizeNet\AuthorizeNet;
use AuthorizeNet\Config\ConfigKeys;
use AuthorizeNet\Config\GatewayResponseType;
use AuthorizeNet\Service\SIM\ResponseServiceInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Core\HttpKernel\Exception\RedirectException;
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
     * @return Response|null The rendered page for non-redirection responses (when relay response is configured).
     * @throws RedirectException For redirection responses (when receipt link is configured).
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

        $orderPaid = $SIMResponseService->payOrderFromResponse($response, $order);

        if (AuthorizeNet::getConfigValue(ConfigKeys::GATEWAY_RESPONSE_TYPE) === GatewayResponseType::RELAY_RESPONSE) {
            // for relay response, we have to send a manual redirection page since the Authorize.Net gateway
            // seems to interpret an HTTP 302 response code as an error

            if ($orderPaid) {
                $storeURL = $this->retrieveUrlFromRouteId(
                    'order.placed',
                    [],
                    [
                        'order_id' => $order->getId(),
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );
            } else {
                $storeURL = $this->retrieveUrlFromRouteId(
                    'order.failed',
                    [],
                    [
                        'order_id' => $order->getId(),
                        'message' => $this->getTranslator()->trans('Payment error.'),
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );
            }

            return $this->render(
                'authorize-net-order-payment-callback',
                [
                    'store_url' => $storeURL,
                ]
            );
        }

        if ($orderPaid) {
            $this->redirectToSuccessPage($order->getId());
        } else {
            $this->redirectToFailurePage($order->getId(), $this->getTranslator()->trans('Payment error.'));
        }

        return null;
    }
}
