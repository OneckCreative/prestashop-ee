<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/prestashop-ee/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/prestashop-ee/blob/master/LICENSE
 * @author Wirecard AG
 * @copyright Copyright (c) 2020 Wirecard AG, Einsteinring 35, 85609 Aschheim, Germany
 * @license MIT License
 */

namespace WirecardEE\Prestashop\Classes\Notification\PostProcessing;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use WirecardEE\Prestashop\Classes\Notification\Success as AbstractSuccess;

class Success extends AbstractSuccess
{
    /**
     * @inheritDoc
     */
    public function getOrderStateProcessType()
    {
        return Constant::PROCESS_TYPE_POST_PROCESSING_NOTIFICATION;
    }

    public function process()
    {
        parent::process();
        $this->orderAmountCalculator->markSettledParentAsClosed(
            $this->notification->getParentTransactionId()
        );
        $this->order_service->setOrderAmountCalculatorService(
            $this->orderAmountCalculator
        );
        $this->order_service->createOrderPayment(
            $this->notification,
            $this->notification->getRequestedAmount()->getValue()
        );
    }
}
