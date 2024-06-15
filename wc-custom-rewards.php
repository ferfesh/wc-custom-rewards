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

// تحميل ملفات الترجمة
function wc_custom_rewards_load_textdomain() {
  load_plugin_textdomain( 'wc-custom-rewards', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'wc_custom_rewards_load_textdomain' );

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

// عرض رصيد المكافآت في صفحة الحساب
add_action('woocommerce_before_my_account', 'wc_custom_rewards_show_balance');
function wc_custom_rewards_show_balance() {
    $user_id = get_current_user_id();
    $balance = get_user_meta($user_id, '_wc_custom_rewards_balance', true);
    if ($balance) {
        echo '<p>' . __('رصيد المكافآت: ', 'wc-custom-rewards') . wc_price($balance) . '</p>';
    }
}

?>
