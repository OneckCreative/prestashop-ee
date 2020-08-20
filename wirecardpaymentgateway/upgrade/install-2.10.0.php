<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/prestashop-ee/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/prestashop-ee/blob/master/LICENSE
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Upgrade script v2.10.0
 * @param WirecardPaymentGateway $module
 * @return bool
 * @since 2.10.0
 */
function upgrade_module_2_10_0($module)
{
    $module->addUpdateOrderStatuses();
    $module->addEmailTemplatesToPrestashop();

    return true;
}