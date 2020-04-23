<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/prestashop-ee/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/prestashop-ee/blob/master/LICENSE
 */

namespace WirecardEE\Prestashop\Classes\Response;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\IgnorableStateException;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\OrderStateInvalidArgumentException;
use Wirecard\PaymentSdk\Entity\StatusCollection;
use Wirecard\PaymentSdk\Response\FailureResponse;
use WirecardEE\Prestashop\Classes\ProcessType;
use WirecardEE\Prestashop\Helper\Logger;
use WirecardEE\Prestashop\Helper\Service\ContextService;
use WirecardEE\Prestashop\Helper\Service\OrderService;
use WirecardEE\Prestashop\Helper\OrderManager;

/**
 * Class Failure
 * @package WirecardEE\Prestashop\Classes\Response
 * @since 2.1.0
 */
final class Failure implements ProcessablePaymentResponse
{
    /** @var \Order */
    private $order;

    /** @var FailureResponse */
    private $response;

    /** @var ContextService */
    private $context_service;

    /** @var OrderService */
    private $order_service;

    /** @var string */
    private $processType;

    /**
     * @var \WirecardPaymentGateway
     */
    private $module;

    /**
     * FailureResponseProcessing constructor.
     *
     * @param \Order $order
     * @param FailureResponse $response
     * @param string $processType
     * @since 2.1.0
     */
    public function __construct($order, $response, $processType)
    {
        $this->order = $order;
        $this->response = $response;
        $this->processType = $processType;
        $this->context_service = new ContextService(\Context::getContext());
        $this->order_service = new OrderService($order);
        $this->module = \Module::getInstanceByName('wirecardpaymentgateway');
    }


    /**
     * @since 2.10.0
     */
    public function process()
    {
        $logger = new Logger();

        $currentState = $this->order_service->getLatestOrderStatusFromHistory();
        $logger->debug("Current state is {$currentState}");
        // #TEST_STATE_LIBRARY
        $logger->debug(print_r($this->response->getData(), true));
        try {
            $nextState = $this->module->orderStateManager()->calculateNextOrderState(
                $currentState,
                Constant::PROCESS_TYPE_INITIAL_RETURN,
                $this->response->getData()
            );
            $logger->debug("Current State : {$currentState}. Next calculated state is {$nextState}");
            if ($currentState === \Configuration::get(OrderManager::WIRECARD_OS_STARTING)) {
                $this->order->setCurrentState($nextState); // _PS_OS_ERROR_
                $this->order->save();
                $this->order_service->updateOrderPaymentTwo($this->response->getData()['transaction-id']);
            }

            if ($this->processType === ProcessType::PROCESS_BACKEND) {
                $this->processBackend();
                return;
            }
        } catch (IgnorableStateException $e) {
            // #TEST_STATE_LIBRARY
            $logger->debug($e->getMessage());
        } catch (OrderStateInvalidArgumentException $e) {
            // #TEST_STATE_LIBRARY
            $logger->debug($e->getMessage());
        }


        $cart_clone = $this->order_service->getNewCartDuplicate();
        $this->context_service->setCart($cart_clone);

        $errors = $this->getErrorsFromStatusCollection($this->response->getStatusCollection());
        $this->context_service->redirectWithError($errors, 'order');
    }

    private function processBackend()
    {
        $errors = $this->getErrorsFromStatusCollection($this->response->getStatusCollection());
        $this->context_service->setErrors(
            \Tools::displayError(
                join('<br>', $errors)
            )
        );
    }

    /**
     * @param StatusCollection $statuses
     *
     * @return array
     * @since 2.1.0
     */
    private function getErrorsFromStatusCollection($statuses)
    {
        $error = array();

        foreach ($statuses->getIterator() as $status) {
            array_push($error, $status->getDescription());
        }

        return $error;
    }
}
