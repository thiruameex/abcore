<?php
$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('cmspro_category')} DROP `store_id` ;

ALTER TABLE {$this->getTable('cmspro_news')} DROP `store_id` ;

DROP TABLE IF EXISTS {$this->getTable('cmspro_category_store')};
CREATE TABLE {$this->getTable('cmspro_category_store')}(
	`category_id` int(11) unsigned NOT NULL,
	`store_id` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('cmspro_news_store')};
CREATE TABLE {$this->getTable('cmspro_news_store')}(
	`news_id` int(11) unsigned NOT NULL,
	`store_id` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();
?>