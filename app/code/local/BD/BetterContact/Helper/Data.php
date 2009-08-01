<?php
/**
 * @category   BD
 * @package    BD_BetterContact
 * @author     Tristan Blease <tristan@bleasedesign.com>
 * @copyright  Copyright (c) 2009 Blease Design LLC (http://bleasedesign.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class BD_BetterContact_Helper_Data extends Mage_Core_Helper_Abstract
{

    const XML_PATH_ENABLED   = 'bettercontact/bettercontact/enabled';

    public function isEnabled()
    {
        return Mage::getStoreConfig( self::XML_PATH_ENABLED );
    }

    public function getUserName()
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return '';
        }
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return trim($customer->getName());
    }

    public function getUserEmail()
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return '';
        }
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return $customer->getEmail();
    }
}
