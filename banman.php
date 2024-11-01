<?php
/*
Plugin Name: BanMan
Plugin URI: http://tushov.ru
Description: Простой и удобный плагин для управления баннерами на сайте. Managing banners on your site
Version: 1.0.1.0
Author: Leonid Tushov
Author URI: http://tushov.ru
Copyright: 2016
Text Domain: banman
Domain Path: /languages/
License : GPL
*/
    
define('BMAN_ROOT', plugin_dir_path(__FILE__));
 
require_once(BMAN_ROOT.'includes'.DIRECTORY_SEPARATOR.'banman.class.php');
require_once(BMAN_ROOT.'includes'.DIRECTORY_SEPARATOR.'banman.widget.php');
    
if ($_GET['bman_click_id']) {
    $id = (int) $_GET['bman_click_id'];
    global $wpdb;
    $wpdb->query("UPDATE {$wpdb->prefix}banman SET `click_count` = `click_count` + 1 WHERE `id` = $id");  
    exit();
}

register_activation_hook(__FILE__, array('BanMan', 'install')); 
register_uninstall_hook(__FILE__, array('BanMan', 'uninstall')); 

add_action( 'plugins_loaded', 'load_banman_textdomain' );
add_action( 'admin_menu', array('BanMan', 'admin_menu') );
add_filter( 'wp_footer', array('BanMan', 'wp_footer') );
add_action( 'wp_ajax_sortable', array('BanMan', 'sortable') );
add_action( 'admin_enqueue_scripts', array('BanMan', 'loadJSCSSAdmin') );
add_action( 'wp_enqueue_scripts', array('BanMan', 'loadJSCSSSite') );
add_filter( 'upload_mimes', array('BanMan', 'addUploadMimes'));
add_action( 'widgets_init', function () {
    register_widget("BanManWidget");
});

function load_banman_textdomain() {
    load_plugin_textdomain( 'banman', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}

function bman_add_positions($positions=array())
{
    static $_positions = array();
    
    if (count($_positions) and is_array($positions))
        $_positions = array_merge($_positions, $positions);
    elseif (is_array($positions)) 
        $_positions = $positions;
    
    return $_positions;
}

function bman_banners($position, $return=false)
{
    global $wpdb;
    static $banners = false;
    if ($banners===false) {
        $banners = array();
        $rows = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}banman WHERE CURDATE() >= `show_start` AND (`show_end`='0000-00-00' OR CURDATE() <= `show_end`) AND `publish` = 1 ORDER BY `ordering`, `id`", 'ARRAY_A');
        if (count($rows)) {
            foreach ($rows as $row) {
                $banners[$row['position']][] = $row;
            }
        }
    }
    if (count($banners[$position])) {
        foreach ($banners[$position] as $_banner) {
            $_banner['params'] = json_decode($_banner['params'], true);
            if ($_banner['params']['on_count_show']) {
                $wpdb->query("UPDATE {$wpdb->prefix}banman SET `show_count` = `show_count` + 1 WHERE `id` = {$_banner['id']}");
            }
            $out .= '<div class="banman-banner-box">';
            if ($_banner['type']==1) { // code
                $out .= $_banner['code'];
            } else { // file
                $pathFile = get_attached_file($_banner['attach_id'], false);
                if (strtolower(pathinfo($pathFile, PATHINFO_EXTENSION))=='swf') {
                    $swfPath = wp_get_attachment_url($_banner['attach_id']);
                    $out .= $_banner['url'] ? '<div style="position:relative;">' : '<div>';
                    $wh = ($_banner['params']['height'] ? " height=\"{$_banner['params']['height']}\"" : '') . ($_banner['params']['width'] ? " width=\"{$_banner['params']['width']}\"" : '');
                    if ($_banner['url']) {
                        $out .= '<a href="' . $_banner['url'] . '"';
                        if (!$_banner['params']['nofollow']) $out .= ' rel="nofollow"';
                        if (!$_banner['params']['blank']) $out .= ' target="_blank"';
                        if ($_banner['params']['on_count_click']) $out .= ' onclick="jQuery.get(\'/?bman_click_id=\' + ' . $_banner['id'] . ')"'; 
                        if ($wh) $out .= ' style="position:absolute;top:0;left:0;z-index:99999;display:block;' . str_replace('"', '', str_replace(' ', 'px;', str_replace('=', ':', trim($wh)))) . 'px;"';
                        $out .= '></a>';
                    }
                    $out .= '<object' . $wh . '><param name="movie" value="' . $swfPath . '"><embed src="' . $swfPath . '"' . $wh . '></embed></object>';
                    $out .= '</div>';
                } else {
                    if ($_banner['url']) {
                        $out .= '<a href="' . $_banner['url'] . '"';
                        if (!$_banner['params']['nofollow']) $out .= ' rel="nofollow"';
                        if (!$_banner['params']['blank']) $out .= ' target="_blank"';
                        if ($_banner['params']['on_count_click']) $out .= ' onclick="jQuery.get(\'/?bman_click_id=\' + ' . $_banner['id'] . ')"'; 
                        $out .= '>';
                    }
                    $wh = ($_banner['params']['height'] ? " height=\"{$_banner['params']['height']}\"" : '') . ($_banner['params']['width'] ? " width=\"{$_banner['params']['width']}\"" : '');
                    $out .= '<img src="' . wp_get_attachment_image_url($_banner['attach_id'], 'large') . '"' . $wh . ' />';
                    if ($_banner['url']) $out .= '</a>';    
                }  
            }
            $out .= '</div>';      
        }
    }  
    if ($return) return $out; else echo $out; 
}

bman_add_positions(array(
    'pop-up'=>__('Pop-up banner', 'banman'),
    'body-bg'=>__('Background banner page', 'banman'),
));

