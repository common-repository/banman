<?php
  
class BanmanTable extends WP_List_Table {
    
    var $positions;
    
    function get_columns(){
      return array(
        'cb'        => '<input type="checkbox" />',
        'img' => __('Picture','banman'),
        'name' => __('Name','banman'),
        'position' => __('Position','banman'),
        'type' => __('Type','banman'),
        'url'  => __('Link','banman'),
        'publish'  => __('Pub.','banman'),
        'id'  => 'ID',
        'show_count'  => __('Views', 'banman'),
        'click_count'  => __('Clicks','banman'),
      );
    }
    
    function column_default( $item, $column_name ) {
          switch( $column_name ) {
            case 'position':
                return $this->positions[$item['position']];
            case 'img':
                if ($_REQUEST['position_filter']) {
                    $_sortLabel = '<span title="' . __('drag to reorder','banman') . '" class="dashicons dashicons-sort"></span><input type="hidden" name="banners[]" value="' . $item['id'] . '" />';
                }
                if ($item['attach_id']) {
                    $src = wp_get_attachment_image_url($item['attach_id'], 'thumbnail');
                    return $_sortLabel . '<a class="banner-thumb" title="' . __('Edit banner','banman') . '..." href="?page=edit&id='.$item['id'].'"><img src="'.$src.'" /></a>';    
                } else {
                    return $_sortLabel . '&nbsp;';
                }                
                break;
            case 'type':
                return $item['type'] ? ('<span title="' . __('Code','banman') . '" class="dashicons dashicons-editor-code"></span>') : ('<span title="' . __('File','banman') . '" class="dashicons dashicons-format-image"></span>');
            case 'name':
              return '<a title="' . __('Edit banner','banman') . '..." style="font-weight:bold;" href="?page=edit&id='.$item['id'].'">'.$item['name'].'</a>';
            case 'url':
              return $item['url'] ? '<a target="_blank" title="' . $item['url']. '" href="'.$item['url'].'"><span class="dashicons dashicons-admin-links"></span></a>' : '';
            case 'publish':
              return '<a title="' . ($item['publish'] ? __('Enable','banman') : __('Disable','banman')) . '?" href="?page=banman.php&action=' . ($item['publish'] ? 'unpublish' : 'publish') . '&rows[]='.$item['id'].'">'. ($item['publish'] ? '<span class="dashicons dashicons-visibility"></span>' : '<span class="dashicons dashicons-hidden"></span>') .'</a>';
            default:
              return $item[$column_name];
          }
    }
    
    function prepare_items() {
        
        global $wpdb;    
        
        $this->positions = BanMan::getPositions();
                
        $per_page = 10;
        $current_page = $this->get_pagenum();            
        
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array(
            'id'  => array('id',false), 
            'name'  => array('name',false), 
            'publish'  => array('publish',false), 
            'type'  => array('type',false), 
            'url'  => array('url',false), 
            'position'  => array('position',false), 
            'show_count'  => array('show_count',false), 
            'click_count'  => array('click_count',false), 
        );
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        $addSql = array();
        if (trim($_POST['s'])!='') $addSql[] = "`name` LIKE '%{$_POST['s']}%'";
        if (trim($_REQUEST['position_filter'])!='') $addSql[] = "`position` = '{$_REQUEST['position_filter']}'";
        if (count($addSql)) $addSql = "WHERE " . implode(' AND ', $addSql);
        
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}banman $addSql");
        
        if (trim($_GET['orderby'])) {
            $order = "ORDER BY {$_GET['orderby']} {$_GET['order']}";
        } elseif ($_REQUEST['position_filter']) {
            $order = 'ORDER BY `ordering`';            
        } else {
            $order = 'ORDER BY `id` DESC';            
        }
        
        $this->items = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}banman $addSql $order LIMIT " . (($current_page-1)*$per_page) . ',' . $per_page, 'ARRAY_A');
        
         $this->set_pagination_args( array(
            'total_items' => $total_items,                  
            'per_page'    => $per_page,
         ));
    }
    
    function column_name($item) {
      $actions = array(
        'edit'  => sprintf('<a href="?page=edit&id=' . $item['id'] . '">' . __('Edit', 'banman') . '</a>',$_REQUEST['page'],'edit',$item['id']),
        'del'  => sprintf('<a onclick="if(!confirm(\'' . __('Are you sure you want to delete this banner?', 'banman') . '\')) return false;" href="?page=banman.php&action=del&rows[]=' . $item['id'] . '">' . __('delete', 'banman') . '?</a>',$_REQUEST['page'],'del',$item['id']),
      );
      return sprintf('%1$s %2$s', $item['name'], $this->row_actions($actions));
    }
    
    function get_bulk_actions() {
          return array(
            'publish'    => __('Enable','banman'),
            'unpublish'    => __('Disable','banman'),
            'clear_shows'    => __('Reset views','banman'),
            'clear_clicks'    => __('Reset clicks','banman'),
            'del'    => __('Delete','banman'),
          );
    }
    
    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="rows[]" value="%s" />', $item['id']
        );
    }
    
    function no_items() {
        echo '<br>';
        _e('Banners are not found!','banman');
        echo '<br><br>';
    }
    
    function extra_tablenav( $which ) {
?>
        <div class="alignleft actions">
<?php
        if ($which == 'top') {
            echo BanMan::selectList(array(
                'empty'=>' - ' . __('Choose position','banman') . ' - ',
                'name'=>'position_filter',
                'values'=>BanMan::getPositions(),
                'select'=>$_REQUEST['position_filter'],
                'assoc'=>true,
                'html'=>'id="position_filter"'
            ));
            submit_button(__('Filter','banman'),'button',false,false,array('onclick'=>"this.form.action='?page=banman.php&position_filter=' + jQuery('#position_filter').val();"));
        }
?>
        </div>
<?php
    }
}

$BanmanTable = new BanmanTable();
$BanmanTable->prepare_items();
