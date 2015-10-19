<?php

namespace AuthorizeNet\Controller\Front;

use AuthorizeNet\AuthorizeNet;
use AuthorizeNet\Config\ConfigKeys;
use AuthorizeNet\ResponseCode;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Model\OrderQuery;
use Thelia\Model\OrderStatus;
use Thelia\Model\OrderStatusQuery;
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
        $request = $this->getRequest()->request;

        // challenge the gateway authentication hash with our local hash
        $gatewayHash = $request->get('x_MD5_Hash');

        $hashValue = AuthorizeNet::getConfigValue(ConfigKeys::HASH_VALUE, '');
        $APILoginID = AuthorizeNet::getConfigValue(ConfigKeys::API_LOGIN_ID);
        $transactionID = $request->get('x_trans_id');
        $amount = $request->get('x_amount');

        $localHash = md5($hashValue . $APILoginID . $transactionID . $amount);

        if (strtolower($gatewayHash) !== strtolower($localHash)) {
            throw new AccessDeniedHttpException();
        }

        $orderRef = $request->get('x_invoice_num');
        $order = OrderQuery::create()->findOneByRef($orderRef);
        if ($order === null) {
            throw new NotFoundHttpException('Order not found.');
        }

        $orderEvent = new OrderEvent($order);

        $responseCode = $this->getRequest()->get('x_response_code');
        switch ($responseCode) {
            case ResponseCode::APPROVED:
                $orderStatusPaid = OrderStatusQuery::create()->findOneByCode(OrderStatus::CODE_PAID);
                $orderEvent->setStatus($orderStatusPaid->getId());
                $this->getDispatcher()->dispatch(TheliaEvents::ORDER_UPDATE_STATUS, $orderEvent);
                $this->redirectToSuccessPage($order->getId());
                break;
            case ResponseCode::DECLINED:
            default:
                $this->redirectToFailurePage($order->getId(), $this->getTranslator()->trans('Payment error.'));
                break;
        }
    }
}
