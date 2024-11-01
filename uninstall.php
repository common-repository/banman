<?php
  
defined('WP_UNINSTALL_PLUGIN') or die();

global $wpdb;

$wpdb->query("
DROP TABLE IF EXISTS `{$wpdb->prefix}banman`;
");
