<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Newsletter
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
require("Mage/Newsletter/controllers/SubscriberController.php");

class Rahil_Constantcontact_SubscriberController extends Mage_Core_Controller_Front_Action
{
	
	public function indexAction(){
		$model = Mage::getSingleton('constantcontact/list');
		$data = $model->toOptionArray();
	}
    /**
      * New subscription action
      */
    public function newAction()
    {
		
        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
            $session            = Mage::getSingleton('core/session');
            $customerSession    = Mage::getSingleton('customer/session');
            $email              = (string) $this->getRequest()->getPost('email');

            try {
                if (!Zend_Validate::is($email, 'EmailAddress')) {
                    Mage::throwException($this->__('Please enter a valid email address.'));
                }

                if (Mage::getStoreConfig(Mage_Newsletter_Model_Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG) != 1 && 
                    !$customerSession->isLoggedIn()) {
                    Mage::throwException($this->__('Sorry, but administrator denied subscription for guests. Please <a href="%s">register</a>.', Mage::helper('customer')->getRegisterUrl()));
                }

                $ownerId = Mage::getModel('customer/customer')
                        ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                        ->loadByEmail($email)
                        ->getId();
                if ($ownerId !== null && $ownerId != $customerSession->getId()) {
                    Mage::throwException($this->__('This email address is already assigned to another user.'));
                }
				
				

                $status = Mage::getModel('newsletter/subscriber')->subscribe($email);
				
				$config = Mage::getStoreConfig('constantcontact');
				
				if($email!='' && $config['settings']['constant_status']==1){
					$path =  Mage::getBaseDir().'/app/code/local/Rahil/Constantcontact/api';
					require_once($path."/ctctWrapper.php");
					
					$listObj = new ListsCollection();
					$lists = $listObj->getLists();
					$contactList = array();
					$contactList [] = $config['settings']['constant_list'];
					
					$contactObj = new ContactsCollection();
					$fname = (($firstname = $this->getRequest()->getPost('firstname')) != null) ? $firstname : null;
					$lname = (($lastname = $this->getRequest()->getPost('lastname')) != null) ? $lastname : null;
					$cname = (($compname = $this->getRequest()->getPost('company')) != null) ? $compname : null;
					$notes = (($notes = $this->getRequest()->getPost('comment')) != null) ? $notes : null;
					$cntObj =  new Contact(array('email_address'=>$email,
							'lists'=>$contactList,
							'first_name'=>$fname,
							'last_name'=>$lname,
							'company_name'=>$cname,
							'notes'=>$notes,
						));
					$statusCode = $contactObj->createContact($cntObj);
				}
				if ($status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE) {
                    $session->addSuccess($this->__('Confirmation request has been sent.'));
                }
				
				
                else {
					$from_email = Mage::getStoreConfig('trans_email/ident_general/email'); //fetch sender email Admin
					//$from_name = Mage::getStoreConfig('trans_email/ident_general/name'); //fetch sender name Admin		
					
					$subject = 'New User Subscription';
					
					$message= 'The below new user has subscibed'."\r\n";
					$message.= 'Name : '.$_POST['firstname'].' '.$_POST['lastname']."\r\n";
					$message.= 'Email : '.$_POST['email']."\r\n";
					$message.= 'Company/Institution : '.$_POST['company']."\r\n"; 
					$message.= 'Comment : '.$_POST['comment']."\r\n"; 
   		
					$headers = 'From: cserve@cytoskeleton.com' . "\r\n" .
					'Reply-To: cserve@cytoskeleton.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
					
					mail($from_email, $subject, $message, $headers);
					
					mail('daren@cytoskeleton.com', $subject, $message, $headers);
					 $session->addSuccess($this->__('Thank you for your subscription.'));
					
					/*if($config['settings']['constant_status']==1){
						if(isset($statusCode) && $statusCode==201){
		                    $session->addSuccess($this->__('Thank you for your subscription.'));
						}
					}else{
						$session->addSuccess($this->__('Thank you for your subscription.'));
					}*/
					

                }
            }
            catch (Mage_Core_Exception $e) {
                $session->addException($e, $this->__('There was a problem with the subscription: %s', $e->getMessage()));
            }
            catch (Exception $e) {
                $session->addException($e, $this->__('There was a problem with the subscription.'));
            }
        }
		//exit('here last');
        $this->_redirectReferer();
    }

}
