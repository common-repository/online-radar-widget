<?php
class onlineradar_widget_admin{
    private static $initiated = false;
    
    public function init(){
        if (!self::$initiated) {
            self::load_res();

            add_action('admin_menu', ['onlineradar_widget_admin', 'create_menu']);
            add_action('admin_init', ['onlineradar_widget_admin', 'register_settings']);
        }
    }

    public static function register_settings(){
        register_setting('onlineradar_widget_settings', 'onlineusers_widget_txtcolor', 'sanitize_text_field');
        register_setting('onlineradar_widget_settings', 'onlineusers_widget_bgcolor', 'sanitize_text_field');
        register_setting('onlineradar_widget_settings', 'onlineusers_widget_bghovercolor', 'sanitize_text_field');
        register_setting('onlineradar_widget_settings', 'onlineusers_widget_cntcolor', 'sanitize_text_field');
        register_setting('onlineradar_widget_settings', 'onlineusers_widget_bgcntcolor', 'sanitize_text_field');
        register_setting('onlineradar_widget_settings', 'onlineusers_widget_txtfont', 'sanitize_text_field');
        register_setting('onlineradar_widget_settings', 'onlineusers_widget_position', 'intval');
    }
    
    public static function load_res(){
        wp_register_script('onlineradar_widget_fljs', plugin_dir_url(__FILE__) . 'js/fontlist.min.js', ['jquery'], false);
        wp_register_script('onlineradar_widget_sbjs', plugin_dir_url(__FILE__) . 'js/bootstrap-formhelpers-selectbox.min.js', ['jquery'], false);
        wp_register_script('onlineradar_widget_cpjs', plugin_dir_url(__FILE__) . 'js/bootstrap-formhelpers-colorpicker.min.js', ['jquery', 'onlineradar_widget_sbjs'], false);
        wp_register_script('onlineradar_widget_fsizesjs', plugin_dir_url(__FILE__) . 'js/bootstrap-formhelpers-fontsizes.min.js', ['jquery', 'onlineradar_widget_fljs'], false);
        wp_register_script('onlineradar_widget_fontsjs', plugin_dir_url(__FILE__) . 'js/bootstrap-formhelpers-fonts.min.js', ['jquery', 'onlineradar_widget_fsizesjs'], false);
        
        wp_register_style('onlineradar_widget_fhcss', plugin_dir_url(__FILE__) . 'css/bootstrap-formhelpers.min.css', [], '');
        wp_register_style('onlineradar_widget_bcss', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', [], '');
        
        wp_register_script('onlineradar_widget_gbjs', ONLINERADAR_WIDGET_GETBLOCK_SCRIPT_URL, ['jquery'], false);
        wp_register_script('onlineradar_widget_admininitjs', plugin_dir_url(__FILE__) . 'js/settings_init.js', ['jquery', 'onlineradar_widget_gbjs'], false);
    }
    
    public static function print_res() {
        $param = [];
        $param['domain'] = ONLINERADAR_WIDGET_SERVICE_URL;
        $param['txtcolor'] = get_option('onlineusers_widget_txtcolor');
        $param['bgcolor'] = get_option('onlineusers_widget_bgcolor');
        $param['bghovercolor'] = get_option('onlineusers_widget_bghovercolor');
        $param['cntcolor'] = get_option('onlineusers_widget_cntcolor');
        $param['bgcntcolor'] = get_option('onlineusers_widget_bgcntcolor');
        $param['txtfont'] = get_option('onlineusers_widget_txtfont');
        $param['position'] = get_option('onlineusers_widget_position');
        wp_localize_script('onlineradar_widget_admininitjs', 'param', $param);
        
        wp_enqueue_script('onlineradar_widget_cpjs');
        wp_enqueue_script('onlineradar_widget_fontsjs');
        wp_enqueue_script('onlineradar_widget_sbjs');
        wp_enqueue_script('onlineradar_widget_gbjs');
        wp_enqueue_script('onlineradar_widget_admininitjs');
        
        wp_enqueue_style('onlineradar_widget_fhcss');
        wp_enqueue_style('onlineradar_widget_bcss');
    }
    
    function create_menu() {
        add_filter('plugin_action_links_'.plugin_basename(plugin_dir_path(__FILE__) . 'onlineradar_widget.php'), ['onlineradar_widget_admin', 'onlineradar_widget_settings_link']);

	//register settings
        add_options_page('online radar', 'Online Radar', 'manage_options', 'onlineradar_widget_settings', ['onlineradar_widget_admin', 'show_settings_page']);
    }
    
    public static function onlineradar_widget_settings_link($links){
        $settings_link = '<a href="'.esc_url(self::get_settings_url()).'">'.__('Change widget', 'onlineradar_widget').'</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
    
    public static function get_settings_url(){
        $args = array('page' => 'onlineradar_widget_settings');
        $url = add_query_arg($args, admin_url('options-general.php'));

        return $url;
    }

    function show_settings_page(){
?>
<h2>Online-Radar widget</h2>
<form action="options.php" method="post" class="form-horizontal col-sm-6">
    <?php settings_fields('onlineradar_widget_settings'); ?>
    <h5><?= __('HTML block parametres:', 'onlineradar_widget'); ?></h5>
    <div class="form-group">
        <label for="txtcolor" class="col-sm-3 control-label"><?= __('Text color', 'onlineradar_widget'); ?></label>
        <input type="text" name="onlineusers_widget_txtcolor" hidden value="<?php $p = get_option('onlineusers_widget_txtcolor'); echo $p ? $p : "#337ab7"; ?>" />
        <div class="col-sm-6">
        <div id="txtcolor" class="bfh-colorpicker" data-color="<?php echo $p ? $p : "#337ab7"; ?>"></div></div>
    </div>
    <div class="form-group">
        <label for="bgcolor" class="col-sm-3 control-label"><?= __('Background color', 'onlineradar_widget'); ?></label>
        <input type="text" name="onlineusers_widget_bgcolor" hidden value="<?php $p = get_option('onlineusers_widget_bgcolor'); echo $p ? $p : "#fff"; ?>" />
        <div class="col-sm-6">
        <div id="bgcolor" class="bfh-colorpicker" data-color="<?php echo $p ? $p : "#fff"; ?>"></div></div>
    </div>
    <div class="form-group">
        <label for="bghovercolor" class="col-sm-3 control-label"><?= __('Hovered background color', 'onlineradar_widget'); ?></label>
        <input type="text" name="onlineusers_widget_bghovercolor" hidden value="<?php $p = get_option('onlineusers_widget_bghovercolor'); echo $p ? $p : "#eee"; ?>" />
        <div class="col-sm-6">
        <div id="bghovercolor" class="bfh-colorpicker" data-color="<?php echo $p ? $p : "#eee"; ?>"></div></div>
    </div>
    <div class="form-group">
        <label for="cntcolor" class="col-sm-3 control-label"><?= __('Counter color', 'onlineradar_widget'); ?></label>
        <input type="text" name="onlineusers_widget_cntcolor" hidden value="<?php $p = get_option('onlineusers_widget_cntcolor'); echo $p ? $p : "#fff"; ?>" />
        <div class="col-sm-6">
        <div id="cntcolor" class="bfh-colorpicker" data-color="<?php echo $p ? $p : "#fff"; ?>"></div></div>
    </div>
    <div class="form-group">
        <label for="bgcntcolor" class="col-sm-3 control-label"><?= __('Counter background color', 'onlineradar_widget'); ?></label>
        <input type="text" name="onlineusers_widget_bgcntcolor" hidden value="<?php $p = get_option('onlineusers_widget_bgcntcolor'); echo $p ? $p : "#777"; ?>" />
        <div class="col-sm-6">
        <div id="bgcntcolor" class="bfh-colorpicker" data-color="<?php echo $p ? $p : "#777"; ?>"></div></div>
    </div>
    <div class="form-group">
        <label for="txtfont" class="col-sm-3 control-label"><?= __('Text font', 'onlineradar_widget'); ?></label>
        <input type="text" name="onlineusers_widget_txtfont" hidden value="<?php $p = get_option('onlineusers_widget_txtfont'); echo $p ? $p : "Helvetica"; ?>" />
        <div class="col-sm-6">
        <div id="txtfont" class="bfh-selectbox bfh-fonts" data-font="<?php echo $p ? $p : "Helvetica"; ?>"></div></div>
    </div>
    <input type="text" name="onlineusers_widget_position" hidden value="<?php $p = get_option('onlineusers_widget_position'); echo $p ? $p : 0; ?>" />
    <hr>
    <?= __('Accommodation:', 'onlineradar_widget'); ?><br>
    <div class="form-group">
        <label for="position" class="col-sm-3 control-label"><?= __('Location', 'onlineradar_widget'); ?></label>
        <div class="col-sm-6">
            <div id="position" class="bfh-selectbox" data-value="0">
                <div data-value="0"><?= __('In text', 'onlineradar_widget'); ?></div>
                <div data-value="1"><?= __('Left', 'onlineradar_widget'); ?></div>
                <div data-value="3"><?= __('Right', 'onlineradar_widget'); ?></div>
                <div data-value="6"><?= __('Top', 'onlineradar_widget'); ?></div>
                <div data-value="7"><?= __('Bottom', 'onlineradar_widget'); ?></div>
                <div data-value="4"><?= __('Header', 'onlineradar_widget'); ?></div>
                <div data-value="5"><?= __('Footer', 'onlineradar_widget'); ?></div>
            </div>
        </div>
    </div>
    <div class="form-group" id="alignForm" style="display:none">
        <div id="alignForm">
            <label for="align" class="col-sm-3 control-label"><?= __('Align', 'onlineradar_widget'); ?></label>
            <div class="col-sm-6">
                <div id="align" class="bfh-selectbox" data-value="0">
                </div>
            </div>
        </div>
    </div>
    <?= __('Parametres:', 'onlineradar_widget'); ?><br>
    <div class="form-group">
        <div class="col-sm-6">
            <div class="checkbox">
                <label><input type="checkbox" id="closeCB" disabled value="" checked><?= __('Show the "Close" button on mouse over', 'onlineradar_widget'); ?></label>
            </div>
            <fieldset disabled="" id="flipCBdis">
                <div class="checkbox">
                    <label><input type="checkbox" id="flipCB" value=""><?= __('Attach to the edge', 'onlineradar_widget'); ?></label>
                </div>
            </fieldset>
            <fieldset disabled="" id="hideCBdis">
                <div class="checkbox">
                    <label><input type="checkbox" id="hideCB" value=""><?= __('Auto-hide', 'onlineradar_widget'); ?></label>
                </div>
            </fieldset>
        </div>
    </div><hr>
    <div>
        <H5><?= __('Preview:', 'onlineradar_widget'); ?></H5>
        <div class="thumbnail" style="width:800px; height:405px; position:relative; z-index:1;">
            <img src="<?php echo ONLINERADAR_WIDGET_SERVICE_URL; ?>/images/site.jpg">
        </div>
    </div><hr>
    <input type="hidden" name="page_options" value="onlineusers_widget_txtcolor,onlineusers_widget_bgcolor,onlineusers_widget_bghovercolor,onlineusers_widget_cntcolor,onlineusers_widget_bgcntcolor,onlineusers_widget_txtfont,onlineusers_widget_position" />
    <p class="submit"><input type="submit" class="button-primary" value="<?= __('Change widget', 'onlineradar_widget'); ?>"/></p>
</form>
<?php
    self::print_res();
    }
}