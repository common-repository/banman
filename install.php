<?php

defined('WP_INSTALL_PLUGIN') or die();

global $wpdb;

$wpdb->query("
CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}banman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` varchar(30) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `attach_id` int(11) NOT NULL,
  `code` text NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `show_start` date NOT NULL,
  `show_end` date NOT NULL,
  `ordering` int(11) NOT NULL,
  `show_count` int(11) NOT NULL,
  `click_count` int(11) NOT NULL,
  `params` text NOT NULL,
  `publish` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
");
