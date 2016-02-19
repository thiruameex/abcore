<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('mw_distributor')};
CREATE TABLE {$this->getTable('mw_distributor')} (
  `distributor_id` int(11) unsigned NOT NULL auto_increment,
  `country` varchar(10) NOT NULL default '',
  `distributor_link` varchar(255) NOT NULL default '',
  PRIMARY KEY (`distributor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");

$installer->endSetup(); 