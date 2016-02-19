<?php

$installer = $this;
$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->removeAttribute('customer','company');
$setup->addAttribute('customer','company', array(
    'label' => 'Company / Institution',
    'type' => 'varchar',
    'input' => 'text',
    'visible' => true,
    'required' => true,    
    'position' => 100,
	'user_defined' => 1,
));
$eavConfig = Mage::getSingleton('eav/config');
$attribute = $eavConfig->getAttribute('customer', 'company');
$attribute->setData('used_in_forms', array('customer_account_edit','customer_account_create','adminhtml_customer'));
$attribute->save();

$installer->endSetup(); 