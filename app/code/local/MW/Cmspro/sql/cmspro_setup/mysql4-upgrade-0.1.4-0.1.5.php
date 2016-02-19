<?php
$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('cmspro_news_category')} ADD `active` smallint(6) default '1';

");

$installer->endSetup();