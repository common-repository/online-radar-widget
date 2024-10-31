<?php
class onlineradar_widget_widget extends WP_Widget
{
    private static $has_publicated = false;
    
    function onlineradar_widget_widget()
    {
        load_plugin_textdomain('onlineradar_widget', false, '/' . plugin_basename(plugin_dir_path(__FILE__)) . '/languages/');
        parent::WP_Widget(false,
                        'Online-Radar widget',
                        [
                            'classname' => 'onlineradar_widget_widget',
                            'description' => __('Online-Radar widget.', 'onlineradar_widget')
                        ]);
        self::load_script();
    }

    public static function load_script(){
        $param = [];
        $param['domain'] = ONLINERADAR_WIDGET_SERVICE_URL;
        $param['txtcolor'] = get_option('onlineusers_widget_txtcolor');
        $param['bgcolor'] = get_option('onlineusers_widget_bgcolor');
        $param['bghovercolor'] = get_option('onlineusers_widget_bghovercolor');
        $param['cntcolor'] = get_option('onlineusers_widget_cntcolor');
        $param['bgcntcolor'] = get_option('onlineusers_widget_bgcntcolor');
        $param['txtfont'] = get_option('onlineusers_widget_txtfont');
        $param['position'] = (get_option('onlineusers_widget_position') % 1000) + 11000; // set "closebutton" and show user notify
        wp_localize_script('onlineradar_widget_script_init', 'param', $param);
        wp_enqueue_script('onlineradar_widget_script_init');
        
        wp_register_script('onlineradar_widget_ifvisible', ONLINERADAR_WIDGET_IFVISIBLE_URL, ['jquery'], true);
        wp_register_script('onlineradar_widget_script', ONLINERADAR_WIDGET_SCRIPT_URL, ['jquery', 'onlineradar_widget_ifvisible'], true);
        wp_register_script('onlineradar_widget_script_init', plugin_dir_url(__FILE__) . 'js/init_widget.js', ['onlineradar_widget_script'], false);
    }
    
    function form($instance){
        $args = array('page' => 'onlineradar_widget_settings');
        $url = add_query_arg($args, admin_url('options-general.php'));
        echo '<p><a href="' . $url . '"><button type="button" class="button">' . __('Change widget', 'onlineradar_widget') . '</button></a></p>';
    }

    // widget update
    function update($new_instance, $old_instance){
        $instance = $old_instance;
        return $instance;
    }
    
    public static function print_form_js(){
        if (!self::$has_publicated){
            wp_enqueue_script('onlineradar_widget_script');
            
            echo '<div id="onlineusers_widget"></div>';
            
            self::$has_publicated = true;
        }
    }
    
    // Display the widget
    function widget($args, $instance)
    {
        $position = get_option('onlineradar_widget_position');
        if (!$position || ($position === '1') || ($position === '0')){ // in text position
            extract($args);
            echo $before_widget;
            self::print_form_js();
            echo $after_widget;
        }
    }
    
    public static function onlineradar_widget_reg(){
        return register_widget("onlineradar_widget_widget");
    }
}