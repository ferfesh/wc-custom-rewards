<?php
/*
Plugin Name: WooCommerce Custom Rewards
Description: إضافة مخصصة لتخصيص المكافآت والهدايا على شكل رصيد في ووكومرس.
Version: 1.0
Author: Magdi Taha
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // الخروج إذا تم الوصول إلى الملف مباشرة
}

// تضمين الملفات الضرورية
include_once plugin_dir_path( __FILE__ ) . 'includes/functions.php';
include_once plugin_dir_path( __FILE__ ) . 'includes/settings.php';
include_once plugin_dir_path( __FILE__ ) . 'includes/admin.php';

// تفعيل الإضافة
function wc_custom_rewards_activate() {
    // هنا يمكن إضافة أكواد التهيئة إذا لزم الأمر
}
register_activation_hook( __FILE__, 'wc_custom_rewards_activate' );

// تعطيل الإضافة
function wc_custom_rewards_deactivate() {
    // هنا يمكن إضافة أكواد الإزالة إذا لزم الأمر
}
register_deactivation_hook( __FILE__, 'wc_custom_rewards_deactivate' );
?>
