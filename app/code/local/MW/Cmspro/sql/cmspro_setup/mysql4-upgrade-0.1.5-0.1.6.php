<?php
$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('cmspro_news')} ADD `feature` smallint(6) default '0';

");

$installer->endSetup();