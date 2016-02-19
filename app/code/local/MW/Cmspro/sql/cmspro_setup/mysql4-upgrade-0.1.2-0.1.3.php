<?php
$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('cmspro_category')} ADD `order` int(11) default 0;

");

$installer->endSetup();
?>