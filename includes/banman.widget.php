<?php
  
class BanManWidget extends WP_Widget
{
    public function __construct() {
        parent::__construct("banman", __('Display banner','banman'),
            array(
                "description" => __('Display banner position','banman'),
                'classname' => 'banman-widget',
            ));
    }
    
    
    public function widget($args, $instance) {
        
        $out = bman_banners($instance['code'], true);
        
        if (!$out) return '';
        
        if ($instance['max_width'] or $instance['max_height']) {
            echo '<style>';
            echo $instance['max_width'] ? "#{$args['widget_id']}, #{$args['widget_id']} img { max-width: {$instance['max_width']}px; }\n" : '';
            echo $instance['max_height'] ? "#{$args['widget_id']}, #{$args['widget_id']} img { max-height: {$instance['max_height']}px; }\n" : '';
            echo '</style>';
        }
        echo '<div class="banman-widget" id="' . $args['widget_id'] . '">';
        echo $out;
        echo '</div>';
    } 
    
    public function form($instance)
    {
        $instance = wp_parse_args( (array) $instance, array( 'name' => __('Position','banman') . ' №' . date('dHs')) );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('name'); ?>"><?php _e('Title of position','banman'); ?>:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('name'); ?>" name="<?php echo $this->get_field_name('name'); ?>" type="text" value="<?php echo attribute_escape($instance['name']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('max_width'); ?>"><?php _e('Maximum width','banman'); ?> (px):</label>
            <input class="widefat" id="<?php echo $this->get_field_id('max_width'); ?>" name="<?php echo $this->get_field_name('max_width'); ?>" type="text" value="<?php echo attribute_escape($instance['max_width']); ?>" />
        </p>   
        <p>
            <label for="<?php echo $this->get_field_id('max_height'); ?>"><?php _e('Maximum height','banman'); ?> (px):</label>
            <input class="widefat" id="<?php echo $this->get_field_id('max_height'); ?>" name="<?php echo $this->get_field_name('max_height'); ?>" type="text" value="<?php echo attribute_escape($instance['max_height']); ?>" />
        </p> 
        <input class="widefat" type="hidden" id="<?php echo $this->get_field_id('code'); ?>" name="<?php echo $this->get_field_name('code'); ?>" value="<?php echo attribute_escape($instance['code']); ?>" >
        <?php
    }
    
    
    function update($new_instance, $old_instance) {
        if (!count($old_instance) or $old_instance['code']=='') {
            $new_instance['code'] = time() . mt_rand(0,100) . mt_rand(101,200);
        }
        if (!$old_instance['code'])
            $new_instance['code'] = time() . mt_rand(0,100) . mt_rand(101,200);
        return $new_instance;
    }    
}

