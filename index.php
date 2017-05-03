<?php
/*
Plugin Name: Ads Duration Time
Plugin URI: http://amfearliath.tk
Description: Make it possible to set the ad duration time for each ad
Version: 1.0.2
Author: Liath
Author URI: http://amfearliath.tk
Short Name: ad_duration
Plugin update URI: ads-duration


DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
Version 2, December 2004

Copyright (C) 2004 Sam Hocevar
14 rue de Plaisance, 75014 Paris, France
Everyone is permitted to copy and distribute verbatim or modified
copies of this license document, and changing it is allowed as long
as the name is changed.

DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION

0. You just DO WHAT THE FUCK YOU WANT TO.

Changelog:

1.0.2 - fixed error while saving new ads, duration was set to categories standard
*/
require_once('classes/class.ad_duration.php');

function addur_install() {
    addur::newInstance()->_install();
}

function addur_uninstall() {
    addur::newInstance()->_uninstall();
}

function addur_configuration() {
    osc_admin_render_plugin(osc_plugin_path(dirname(__FILE__)) . '/admin/config.php');
}

function addur_form($item = false) {
    addur::newInstance()->_form($item);
}

function addur_adform($item = false) {
    if (addur::newInstance()->_get('activated')) {
        return false;
    }
    addur::newInstance()->_form($item);
}

function addur_save() {
    $data = Params::getParamsAsArray();
    addur::newInstance()->_saveData($data);
}
    
osc_register_plugin(osc_plugin_path(__FILE__), 'addur_install') ;

osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'addur_uninstall') ;
osc_add_hook(osc_plugin_path(__FILE__) . '_configure', 'addur_configuration');

if (addur::newInstance()->_get('activated')) {
    osc_add_hook('item_form', 'addur_form');                        
    osc_add_hook('item_edit', 'addur_form');   
}

osc_add_hook('posted_item', 'addur_save');                        
osc_add_hook('edited_item', 'addur_save');

if(osc_version() >= 300) {
    osc_add_hook('admin_menu_init', 'addur_admin_menu_init');
} else {
    osc_add_hook('admin_menu', 'addur_admin_menu');
}

function addur_admin_menu_init() {
    osc_add_admin_menu_page( __('Ad Duration Time', 'ad_duration'), osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'admin/config.php'), 'addur_admin', 'administrator' );
    osc_add_admin_submenu_page('addur_admin', __('Settings', 'ad_duration'), osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'admin/config.php'), 'addur_admin_settings', 'administrator');
}

function addur_admin_menu() {
    echo '
    <h3><a href="#">' . __('Ad Duration Time', 'ad_duration') . '</a></h3>
    <ul>
        <li><a href="' . osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'admin/config.php') . '">&raquo; ' . __('Settings', 'ad_duration') . '</a></li>
    </ul>';    
}                       
?>