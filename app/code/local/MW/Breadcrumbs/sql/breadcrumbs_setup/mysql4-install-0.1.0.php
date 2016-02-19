<?php

$installer = $this;
$installer->startSetup();
$installer->run("
ALTER TABLE cms_page ADD `area` int NULL default '0';
"); 
 
$installer->endSetup(); 