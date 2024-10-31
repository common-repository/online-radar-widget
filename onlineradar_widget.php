<?php
/*
Plugin Name: Online Radar widget
Plugin URI: http://wordpress.org/plugins/onlineradar-widget/
Description: This service collect a users visits on your site.
Text Domain: onlineradar_widget
Domain Path: /languages
Author: Online-Radar
Author URI: http://online-radar.ru/about.html
Version: 1.0
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define('ONLINERADAR_WIDGET_SERVICE_URL', 'http://online-radar.ru');
define('ONLINERADAR_WIDGET_SCRIPT_URL', ONLINERADAR_WIDGET_SERVICE_URL . '/js/usersonline_widget.min.js');
define('ONLINERADAR_WIDGET_GETBLOCK_SCRIPT_URL', ONLINERADAR_WIDGET_SERVICE_URL . '/js/getblock.min.js');
define('ONLINERADAR_WIDGET_VERSION', '1.0.0');
define('ONLINERADAR_WIDGET_MINIMUM_WP_VERSION', '3.4');
define('ONLINERADAR_WIDGET_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ONLINERADAR_WIDGET_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ONLINERADAR_WIDGET_IFVISIBLE_URL', ONLINERADAR_WIDGET_SERVICE_URL . '/js/ifvisible.min.js');

require(ONLINERADAR_WIDGET_PLUGIN_DIR . 'class.onlineradar_widget.php');
require(ONLINERADAR_WIDGET_PLUGIN_DIR . 'class.onlineradar_widget_widget.php');

register_activation_hook(__FILE__, ['onlineradar_widget', 'plugin_activation']);
register_deactivation_hook(__FILE__, ['onlineradar_widget', 'plugin_deactivation']);

add_action('init', ['onlineradar_widget', 'init']);
add_action('widgets_init', ['onlineradar_widget_widget', 'onlineradar_widget_reg']);

if (is_admin()){
    require_once(ONLINERADAR_WIDGET_PLUGIN_DIR . 'class.onlineradar_widget_admin.php');
    add_action('init', ['onlineradar_widget_admin', 'init']);
}