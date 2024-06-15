<?php

// إضافة صفحة إعدادات الإضافة في لوحة التحكم
function wc_custom_rewards_settings_page() {
    add_options_page(
        'إعدادات المكافآت المخصصة',
        'مكافآت ووكومرس',
        'manage_options',
        'wc-custom-rewards',
        'wc_custom_rewards_settings_page_html'
    );
}
add_action( 'admin_menu', 'wc_custom_rewards_settings_page' );

// عرض محتوى صفحة الإعدادات
function wc_custom_rewards_settings_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    if ( isset( $_POST['wc_custom_rewards_settings_submit'] ) ) {
        update_option( 'wc_custom_rewards_discount_percentage', sanitize_text_field( $_POST['wc_custom_rewards_discount_percentage'] ) );
        echo '<div class="updated"><p>تم تحديث الإعدادات بنجاح.</p></div>';
    }

    $discount_percentage = get_option( 'wc_custom_rewards_discount_percentage', '10' );

    ?>
    <div class="wrap">
        <h1>إعدادات المكافآت المخصصة</h1>
        <form method="post">
            <label for="wc_custom_rewards_discount_percentage">نسبة الخصم للمكافآت:</label>
            <input type="number" id="wc_custom_rewards_discount_percentage" name="wc_custom_rewards_discount_percentage" value="<?php echo esc_attr( $discount_percentage ); ?>" />
            <input type="submit" name="wc_custom_rewards_settings_submit" value="حفظ الإعدادات" class="button button-primary" />
        </form>
    </div>
    <?php
}
?>
