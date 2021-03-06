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

namespace WirecardEE\Prestashop\Classes\Notification;

/**
 * Interface ProcessablePaymentNotification
 * @package WirecardEE\Prestashop\Classes\Notification
 * @since 2.1.0
 */
interface ProcessablePaymentNotification
{
    /**
     * @since 2.1.0
     */
    public function process();
}
