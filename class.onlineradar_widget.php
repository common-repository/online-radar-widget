<?php
class onlineradar_widget{
    private static $initiated = false;
    
    public static function init() {
        if (!self::$initiated) {
            load_plugin_textdomain('onlineradar_widget', false, '/' . plugin_basename(plugin_dir_path(__FILE__)) . '/languages/');
            self::init_hooks();
        }
    }

    public function validate($input) {
        return $input;
    }
    
    /**
     * Initializes WordPress hooks
     */
    private static function init_hooks(){
        self::$initiated = true;
        add_action('wp_footer', ['onlineradar_widget_widget', 'load_script']);
        $position = get_option('onlineradar_widget_position');
        if ($position > 1){
            add_action('wp_footer', ['onlineradar_widget_widget', 'print_form_js']); // display widget
        }
    }
    
    /**
     * Attached to activate_{plugin_basename(__FILES__)} by register_activation_hook()
     * @static
     */
    public static function plugin_activation(){
        if (version_compare($GLOBALS['wp_version'], ONLINERADAR_WIDGET_MINIMUM_WP_VERSION, '<')){
            $message = '<strong>'.sprintf(esc_html__('onlineradar_widget %s requires WordPress %s or higher.' , 'onlineradar_widget'), 
                    ONLINERADAR_WIDGET_VERSION, ONLINERADAR_WIDGET_MINIMUM_WP_VERSION).'</strong>';
            echo $message;
        }
    }
    
    /**
     * Removes all options
     * @static
     */
    public static function plugin_deactivation(){
        remove_action('wp_footer', ['onlineradar_widget', 'load_script']);
        remove_action('wp_footer', ['onlineradar_widget', 'print_form_js']);
        delete_option('onlineradar_widget_txtcolor');
        delete_option('onlineradar_widget_bgcolor');
        delete_option('onlineradar_widget_bghovercolor');
        delete_option('onlineradar_widget_cntcolor');
        delete_option('onlineradar_widget_bgcntcolor');
        delete_option('onlineradar_widget_txtfont');
        delete_option('onlineradar_widget_position');
    }
}