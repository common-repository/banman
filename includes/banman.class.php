<?php  
  
class BanMan
{
    
    function admin_menu()
    {
        add_menu_page(__('Banners management','banman'), __('Banners management','banman'), 'manage_categories', 'banman.php', array('BanMan', 'admin'));
    
        add_submenu_page('banman.php', __('List of banners','banman'), __('List of banners','banman'), 'manage_categories', 'banman.php', array('BanMan', 'admin'));
        
        add_submenu_page('banman.php', __('Add new','banman'), __('Add new','banman'), 'manage_categories', 'edit', array('BanMan', 'edit'));
    }
    
    function addUploadMimes($mimes)
    {
        $mimes['swf'] = 'application/x-shockwave-flash';
        return $mimes;
    }
    
    function addSave()
    {                
        global $wpdb;
        
        $row = $_POST['row'];
        $row['attach_id'] = (int) $_POST['attach_id'];

        $row['show_start'] = date('Y-m-d', strtotime($row['show_start'].' 00:00:00'));
        if ($row['show_end'])
            $row['show_end'] = date('Y-m-d', strtotime($row['show_end'].' 00:00:00'));

        $row['params'] = json_encode($row['params']);
        
        $row['code'] = wp_unslash($row['code']);
        
        $result = $wpdb->insert("{$wpdb->prefix}banman", 
            array(
                'position'=>$row['position'],
                'name'=>$row['name'],
                'type'=>$row['type'],
                'attach_id'=>$row['attach_id'],
                'code'=>$row['code'],
                'url'=>$row['url'],
                'show_start'=>$row['show_start'],
                'show_end'=>$row['show_end'],
                'params'=>$row['params'],
                'publish'=>$row['publish']
            )
        );
        if (!$wpdb->last_error) {
            BanMan::add_admin_notice(__('The new banner is created successfully!','banman'));
        } else {
            BanMan::add_admin_notice(__('Failed to create a banner!','banman'), 'error');
        }   
        
    }
    
    function editSave()
    {
        global $wpdb;
        $row = $_POST['row'];
        $row['attach_id'] = (int) $_POST['attach_id'];

        $row['show_start'] = date('Y-m-d', strtotime($row['show_start'].' 00:00:00'));
        if ($row['show_end'])
            $row['show_end'] = date('Y-m-d', strtotime($row['show_end'].' 00:00:00'));

        $row['params'] = json_encode($row['params']);
        
        $row['code'] = wp_unslash($row['code']);
        
        $result = $wpdb->update("{$wpdb->prefix}banman", 
            array(
                'position'=>$row['position'],
                'name'=>$row['name'],
                'type'=>$row['type'],
                'attach_id'=>$row['attach_id'],
                'code'=>$row['code'],
                'url'=>$row['url'],
                'show_start'=>$row['show_start'],
                'show_end'=>$row['show_end'],
                'params'=>$row['params'],
                'publish'=>$row['publish']
            ),
            array('id'=>$row['id'])
        );
        
        if (!$wpdb->last_error) {
            BanMan::add_admin_notice(__('Banner successfully changed','banman'));
        } else {
            BanMan::add_admin_notice(__('Error editing banner!','banman'), 'error');
        }
    }
    
    function loadJSCSSSite()
    {
        wp_enqueue_script('jquery');        
    }
    
    function loadJSCSSAdmin()
    {
        $screen = get_current_screen();
        
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        
        if ( ! did_action( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }
        
        if (!in_array($_GET['page'], array('banman.php','edit'))) return ;
        
        wp_enqueue_style('jquery-ui.css', plugins_url('css/jquery-ui.css', dirname(__FILE__)));

        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jquery-ui-sortable');
        
        if (get_locale()=='ru_RU')
            wp_enqueue_script('datepicker', plugins_url('js/jquery.ui.datepicker-ru.js', dirname(__FILE__)));    
        else 
            wp_enqueue_script('datepicker', plugins_url('js/jquery.ui.datepicker-en-GB.js', dirname(__FILE__)));
        
        wp_enqueue_script('validate.min.js', plugins_url('js/jquery.validate.min.js', dirname(__FILE__)));
    }
    
    function add_admin_notice($text='', $class='success')
    {
        printf( '<div class="notice notice-%1$s"><p>%2$s</p></div>', $class, $text );
    }
    
    function wp_footer()
    {
        if ($_COOKIE['nopopup']!=1) {
            $out = bman_banners('pop-up', true);
            if ($out!='') {
                ?>
                <div style="position:fixed;left:0;bottom:0;z-index:9999;">
                <img src="<?php echo plugins_url('images/close.png', dirname(__FILE__)); ?>" style="cursor: pointer; position: absolute; top: -16px; right: -16px;z-index:99999;" title="<?php _e('Close this banner?','banman'); ?>" onclick="document.cookie='nopopup=1;expires=<?php echo date('d/m/Y H:i:s', time()+(60*60*24*30*6)); ?>';jQuery(this).parent().hide();" />
                <?php echo $out; ?>
                </div>
                <?php
            }
        }
        $out = bman_banners('body-bg', true);
        if ($out!='') {
            preg_match('|src="(.*?)"|is', $out, $buff);
            if ($buff[1]!='') {
            ?>
            <style>
            body {
                background-image: url('<?php echo $buff[1]; ?>') !important;
                background-position: top center;
                background-repeat: no-repeat;
            }
            </style>
            <?php
            }
        }    
    }
    
    function admin()
    {
        if (!empty($_REQUEST['action']) && $_REQUEST['action']!='-1') {
            call_user_func(array('BanMan', $_REQUEST['action']));
        }
        include_once(BMAN_ROOT.'includes'.DIRECTORY_SEPARATOR.'admin.list_table.php');
        include_once(BMAN_ROOT.'tmpl'.DIRECTORY_SEPARATOR.'admin.php');
    }
    
    function getPositions()
    {
        static $positions=false;
        if ($positions===false) {
            $positions = bman_add_positions();
            $options = get_option('widget_banman');            
            if (count($options) and is_array($options)) {
                foreach ($options as $option) {
                    if ($option['code']!='')
                        $widget_position[$option['code']] = $option['name'];
                }
                if (count($widget_position) and is_array($widget_position))
                    $positions = array_merge($positions, $widget_position);
            }
        }
        return $positions;
    }
    
    function sortable()
    {
        global $wpdb;
        if (count($_REQUEST['banners'])) {
            foreach ($_REQUEST['banners'] as $ordering => $id) {
                $wpdb->update("{$wpdb->prefix}banman", 
                    array(
                        'ordering'=>$ordering
                    ),
                    array('id'=>$id)
                );
            }
        }
        wp_die();
    }
    
    function sorttable_init()
    {
        if (!$_REQUEST['position_filter']) return '';
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery(".wp-list-table tbody").sortable({
                 items: 'tr',
                 cursor: 'pointer',
                 axis: 'y',
                 connectWith: '.dashicons-sort',
                 update: function() {
                    jQuery.ajax({
                      type: 'POST',
                      url: ajaxurl,
                      data: jQuery('#banman-list-form').serialize()+'&action=sortable'
                    });
                 }
            });
        });
        </script>
        <?php
    }
    
    function edit()
    {
        global $wpdb;
        
        if ($_GET['id']) { // edit banner
            $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}banman WHERE id = {$_GET['id']}", 'ARRAY_A');
            $row['params'] = json_decode($row['params'], true);
        } else { // create new banner
            $row['params']['nofollow'] = 0;
            $row['params']['blank'] = 0;
            $row['publish'] = 1;
            $isNew = true;
        }
        include_once(BMAN_ROOT.'tmpl'.DIRECTORY_SEPARATOR.'edit.php');
    } 
    
    function del()
    {
        global $wpdb;
        if (count($_REQUEST['rows'])) {
            $in = implode(',', $_REQUEST['rows']);
            $result = $wpdb->query("DELETE FROM {$wpdb->prefix}banman WHERE id IN ($in)");
            if (!$wpdb->last_error) {
                BanMan::add_admin_notice(__('Selected banners successfully removed!','banman'));
            } else {
                BanMan::add_admin_notice(__('Failed to delete the selected banners','banman'), 'error');           
            }
        }     
    }
    
    function publish()
    {
        global $wpdb;
        if (count($_REQUEST['rows'])) {
            $in = implode(',', $_REQUEST['rows']);
            $result = $wpdb->query("UPDATE {$wpdb->prefix}banman SET `publish` = 1 WHERE id IN ($in)");
            if (!$wpdb->last_error) {
                BanMan::add_admin_notice(__('Selected banner successfully published','banman'));
            } else {
                BanMan::add_admin_notice(__('Error publishing selected banners!','banman'), 'error');           
            }
        }
    }
    
    function unpublish()
    {
        global $wpdb;
        if (count($_REQUEST['rows'])) {
            $in = implode(',', $_REQUEST['rows']);
            $result = $wpdb->query("UPDATE {$wpdb->prefix}banman SET `publish` = 0 WHERE id IN ($in)");
            if (!$wpdb->last_error) {
                BanMan::add_admin_notice(__('Selected banner successfully unpublished!','banman'));
            } else {
                BanMan::add_admin_notice(__('Error removing from the publication of selected banners','banman'), 'error');           
            }
        }
    }
    
    function clear_shows()
    {
        global $wpdb;
        if (count($_POST['rows'])) {
            $in = implode(',', $_POST['rows']);
            $result = $wpdb->query("UPDATE {$wpdb->prefix}banman SET `show_count` = 0 WHERE id IN ($in)");
            if (!$wpdb->last_error) {
                BanMan::add_admin_notice(__('For a selected number of views banners dropped','banman'));
            } else {
                BanMan::add_admin_notice(__('Error', 'banman'), 'error');           
            }
        }
    }
    
    function clear_clicks()
    {
        global $wpdb;
        if (count($_POST['rows'])) {
            $in = implode(',', $_POST['rows']);
            $result = $wpdb->query("UPDATE {$wpdb->prefix}banman SET `click_count` = 0 WHERE id IN ($in)");
            if (!$wpdb->last_error) {
                BanMan::add_admin_notice(__('For selected banners count clicks reset!','banman'));
            } else {
                BanMan::add_admin_notice(__('Error', 'banman'), 'error');           
            }
        }
    }

    
    function uploader( $name, $row) {
        echo '<div id="uploader-box">
        <div>
        ';
        if ($row['attach_id']) {
            $pathFile = get_attached_file($row['attach_id'], false);
            $ext = strtolower(pathinfo($pathFile, PATHINFO_EXTENSION));
            if ($ext=='swf') {
                $swfPath = wp_get_attachment_url($row['attach_id']);
                $wh = ($row['params']['height'] ? " height=\"{$row['params']['height']}\"" : '') . ($row['params']['width'] ? " width=\"{$row['params']['width']}\"" : '');
                echo '<object' . $wh . '><param name="movie" value="' . $swfPath . '"><embed src="' . $swfPath . '"' . $wh . '></embed></object>';
            } else {
                $image_src = wp_get_attachment_image_url($row['attach_id'], 'large');
                echo '<img' . ($image_src ? " src=\"$image_src\"" : '') . ' />';
            }
        }
        echo '
        </div>
        <input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $row['attach_id'] . '" />
        <button type="submit" class="upload_image_button button">' . __('Upload','banman') . '</button>
        </div>';
    }  
    
    function selectList($options)
    {
        if (!count($options['values'])) return;
        $out .= '<select name="' . $options['name'] . '"';
        if (trim($options['html']) != '') $out .= " {$options['html']}>"; else $out .= '>';
        if ($options['empty']!='') $out .= '<option value="">' . $options['empty'] . '</option>';
        if (is_string($options['values'])) $options['values'] = explode(',', $options['values']);
        if (is_string($options['select'])) $options['select'] = explode(',', $options['select']);
        if ($options['values']) {
            foreach ($options['values'] as $key => $val) {
                if ($options['assoc']) {
                    $value = $key;
                    $option = $val;
                }
                else {
                    $value = $val;
                    $option = $val;
                }
                if (@in_array($value, $options['select'])) {
                    $out .= '<option value="' . $value . '" selected>' . $option . '</option>';
                }
                else {
                    $out .= '<option value="' . $value . '">' . $option . '</option>';
                }
            }
        }
        $out .= '</select>';
        return $out;
    } 
    
    function install()
    {
        define('WP_INSTALL_PLUGIN', true);
        include_once(BMAN_ROOT.'install.php'); 
    }
    
    function uninstall()
    {
        define('WP_UNINSTALL_PLUGIN', true);
        include_once(BMAN_ROOT.'uninstall.php'); 
    }
}

