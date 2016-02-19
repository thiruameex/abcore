<?php

$installer = $this;
$installer->startSetup();
$setup = new Mage_Eav_Model_Entity_Setup;
/*$setup->removeAttribute('catalog_product','MSDS');
$setup->removeAttribute('catalog_product','msds');*/
$setup->removeAttribute('catalog_product','manual');
$setup->addAttribute('catalog_product','msds', array(
       					'position' 	   		=> 1,
                      	'type'              => 'varchar',
                        'label'             => 'MSDS File',
                        'input'             => 'text',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'visible'           => false,
                        'required'          => false,
                        'user_defined'      => false,
                        'searchable'        => false,
                        'filterable'        => false,
                        'comparable'        => false,
                        'visible_on_front'  => true,
        				'wysiwyg_enabled'   => false,
                        'visible_in_advanced_search' => false,
                        'unique'            => false,
                    )); 
$setup->addAttribute('catalog_product','manual', array(
       					'position' 	   		=> 2,
                      	'type'              => 'varchar',
                        'label'             => 'Manual File',
                        'input'             => 'text',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'visible'           => false,
                        'required'          => false,
                        'user_defined'      => false,
                        'searchable'        => false,
                        'filterable'        => false,
                        'comparable'        => false,
                        'visible_on_front'  => true,
        				'wysiwyg_enabled'   => false,
                        'visible_in_advanced_search' => false,
                        'unique'            => false,
                    ));                    
$installer->endSetup(); 