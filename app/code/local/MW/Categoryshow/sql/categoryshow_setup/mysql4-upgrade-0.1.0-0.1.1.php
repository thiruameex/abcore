<?php
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$eId = $setup->getEntityTypeId('catalog_category');
$attSetId = $setup->getAttributeSetId($eId,'Default');

if(!$setup->getAttribute('catalog_category','category_about')){
$setup->addAttribute('catalog_category','category_about',array(
    'label'        =>'About Tab Content ',
    'type'      =>'text',    
    'input'     =>'textarea',   
    'visible'   =>true,
    'required'  =>false,
    'position'  =>1,
    'global'    =>1
    ));
    $setup->addAttributeToGroup('catalog_category',$attSetId,'Static Content Tabs','category_about',200);
}

$setup->updateAttribute($eId, 'category_about', 'is_wysiwyg_enabled', 1);

if(!$setup->getAttribute('catalog_category','category_citations')){
$setup->addAttribute('catalog_category','category_citations',array(
    'label'        =>'Citations Tab Content ',
    'type'      =>'text',    
    'input'     =>'textarea',   
    'visible'   =>true,
    'required'  =>false,
    'position'  =>1,
    'global'    =>1
    ));
    $setup->addAttributeToGroup('catalog_category',$attSetId,'Static Content Tabs','category_citations',300);
}

$setup->updateAttribute($eId, 'category_citations', 'is_wysiwyg_enabled', 1);

if(!$setup->getAttribute('catalog_category','category_faq')){
$setup->addAttribute('catalog_category','category_faq',array(
    'label'        =>'FAQs Tab Content ',
    'type'      =>'text',    
    'input'     =>'textarea',   
    'visible'   =>true,
    'required'  =>false,
    'position'  =>1,
    'global'    =>1
    ));
    $setup->addAttributeToGroup('catalog_category',$attSetId,'Static Content Tabs','category_faq',400);
}

$setup->updateAttribute($eId, 'category_faq', 'is_wysiwyg_enabled', 1);


if(!$setup->getAttribute('catalog_category','category_manual')){
$setup->addAttribute('catalog_category','category_manual',array(
    'label'        =>'Manuals & MSDS Tab Content ',
    'type'      =>'text',    
    'input'     =>'textarea',   
    'visible'   =>true,
    'required'  =>false,
    'position'  =>1,
    'global'    =>1
    ));
    $setup->addAttributeToGroup('catalog_category',$attSetId,'Static Content Tabs','category_manual',400);
}

$setup->updateAttribute($eId, 'category_manual', 'is_wysiwyg_enabled', 1);

