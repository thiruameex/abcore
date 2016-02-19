<?php


$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$eId = $setup->getEntityTypeId('catalog_category');
$attSetId = $setup->getAttributeSetId($eId,'Default');

if(!$setup->getAttributeGroup($eId,$attSetId,'Static Content Tabs'))
{
    $setup->addAttributeGroup($eId,$attSetId,'Static Content Tabs');
}
if(!$setup->getAttribute('catalog_category','overview')){
$setup->addAttribute('catalog_category','overview',array(
    'label'        =>'Over View Tab Content ',
    'type'      =>'text',    
    'input'     =>'textarea',   
    'visible'   =>true,
    'required'  =>false,
    'position'  =>1,
    'global'    =>1
    ));
    $setup->addAttributeToGroup('catalog_category',$attSetId,'Static Content Tabs','overview',100);
}

$setup->updateAttribute($eId, 'overview', 'is_wysiwyg_enabled', 1);