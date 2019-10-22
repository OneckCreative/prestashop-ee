<?php

namespace WirecardEE\Prestashop\Classes\Config;

/**
 * Class Constants
 *
 * @package WirecardEE\Prestashop\Classes\Config
 * @since 2.3.1
 */
class Constants
{
    const CONFIGURATION_GENERAL_AUTOMATIC_CAPTURE_ENABLED = 'WIRECARD_PAYMENT_GATEWAY_GENERAL_AUTOMATIC_CAPTURE_ENABLED';
    const CONFIGURATION_GENERAL_FORCE_ORDER_STATE_CHANGE_ENABLED = 'WIRECARD_PAYMENT_GATEWAY_GENERAL_FORCE_ORDER_STATE_CHANGE_ENABLED';

    /**
     * @return array
     */
    public static function getGeneralSettings() {
        return [
            self::CONFIGURATION_GENERAL_AUTOMATIC_CAPTURE_ENABLED,
            self::CONFIGURATION_GENERAL_FORCE_ORDER_STATE_CHANGE_ENABLED
        ];
    }
}