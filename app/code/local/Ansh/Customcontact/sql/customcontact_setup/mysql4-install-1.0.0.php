<?php

$installer = $this;

$installer->startSetup();

$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('customcontact')};
CREATE TABLE {$this->getTable('customcontact')} (
  `id` int(11) unsigned NOT NULL auto_increment COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT 'Customer Name',
  `email` varchar(255) NOT NULL  COMMENT 'Customer Email',
  `mobile_number` varchar(255) NOT NULL  COMMENT 'Customer Mobile Number',  
  `queries` text NOT NULL  COMMENT 'Customer queries',
  `status` smallint(6) NOT NULL default '1',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");


$installer->endSetup();
