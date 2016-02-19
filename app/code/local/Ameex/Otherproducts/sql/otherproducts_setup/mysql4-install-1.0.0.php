<?php 
 $installer = $this;
 $installer->startSetup();
 $installer->run(
 	"DROP TABLE IF EXISTS {$this->getTable('salecollection')};

CREATE TABLE {$this->getTable('salecollection')} (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL,
  `order_id` int(20) unsigned NOT NULL,
  `created_date` datetime NOT NULL,
  `skus` varchar(500) NOT NULL,
  UNIQUE (`id`, `order_id`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
 $installer->endSetup();