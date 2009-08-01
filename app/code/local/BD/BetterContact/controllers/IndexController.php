<?php
/**
 * @category   BD
 * @package    BD_BetterContact
 * @author     Tristan Blease <tristan@bleasedesign.com>
 * @copyright  Copyright (c) 2009 Blease Design LLC (http://bleasedesign.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class BD_BetterContact_IndexController extends Mage_Core_Controller_Front_Action
{

    const XML_PATH_EMAIL_RECIPIENT  = 'bettercontact/email/recipient_email';
    const XML_PATH_EMAIL_SENDER     = 'bettercontact/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE   = 'bettercontact/email/email_template';
    const XML_PATH_ENABLED          = 'bettercontact/bettercontact/enabled';

    public function preDispatch()
    {
        parent::preDispatch();

        if( !Mage::getStoreConfigFlag(self::XML_PATH_ENABLED) ) {
            $this->norouteAction();
        }
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('betterContactForm')
            ->setFormAction( Mage::getUrl('*/*/post') );

        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }

    public function postAction()
    {
        $post = $this->getRequest()->getPost();
	
		//default redirectURL
		$redirectURL = '*/*/';
		

       // var_dump($post); exit();
		if ( $post ) {
            $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate->setTranslateInline(false);

            try {


                $error = false;

				$post['comment'] = "";
				
                if (isset($post['redirectUrl'])) {
					//redirect if told to do so, or just return to the BetterContact page
                    $redirectURL = trim($post['redirectUrl']);
                }
				
				//pull all post data for custom_title_* and custom_field_*
				foreach ($post AS $field => $val) {
					//check if this is a custom field
					if (substr($field,0,13) == "custom_title_" ) {
						 
						//field value
						$fieldValue = "custom_field_".substr($field,13);
						
						//check if the actual field exists and append to the comments
						if (array_key_exists($fieldValue,$post))
							$post['comment'] .= ($val . ": " . $post[$fieldValue] . "\n");
				
					}					
				}
								
                $postObject = new Varien_Object();
                $postObject->setData($post);

                if ($error) {
                    throw new Exception('Generic Error');
                }
                $mailTemplate = Mage::getModel('core/email_template');
                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->setReplyTo($post['email'])
                    ->sendTransactional(
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
                        null,
                        array('data' => $postObject)
                    );

                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception('Email couldn\'t be sent');
                }

                $translate->setTranslateInline(true);

                Mage::getSingleton('catalog/session')->addSuccess(Mage::helper('bettercontact')->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.'));
                $this->_redirect($redirectURL);

                return;
            } catch (Exception $e) {
                $translate->setTranslateInline(true);

                Mage::getSingleton('catalog/session')->addError(Mage::helper('bettercontact')->__('Unable to submit your request. Please, try again later:'.$e->getMessage()));

                $this->_redirect($redirectURL);
                return;
            }

        } else {
            $this->_redirect($redirectURL);
        }
    }

}
