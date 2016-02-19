<?php
$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('cmspro_category')} CHANGE `order` `order_cat` int(11) default 0;

");

$installer->endSetup();