<?php

// إضافة صفحة إدارة المكافآت في لوحة التحكم
function wc_custom_rewards_admin_page() {
    add_menu_page(
        'إدارة المكافآت',
        'مكافآت ووكومرس',
        'manage_options',
        'wc-custom-rewards-admin',
        'wc_custom_rewards_admin_page_html',
        'dashicons-awards',
        30
    );
}
add_action( 'admin_menu', 'wc_custom_rewards_admin_page' );

// عرض محتوى صفحة إدارة المكافآت
function wc_custom_rewards_admin_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    if ( isset( $_POST['wc_custom_rewards_user_id'] ) && isset( $_POST['wc_custom_rewards_balance'] ) ) {
        $user_id = intval( $_POST['wc_custom_rewards_user_id'] );
        $balance = floatval( $_POST['wc_custom_rewards_balance'] );
        wc_custom_rewards_add_balance( $user_id, $balance );
        echo '<div class="updated"><p>تم تحديث رصيد المستخدم بنجاح.</p></div>';
    }

    ?>
    <div class="wrap">
        <h1>إدارة المكافآت</h1>
        <form method="post">
            <label for="wc_custom_rewards_user_id">معرف المستخدم:</label>
            <input type="number" id="wc_custom_rewards_user_id" name="wc_custom_rewards_user_id" required />
            <br><br>
            <label for="wc_custom_rewards_balance">رصيد المكافآت:</label>
            <input type="number" id="wc_custom_rewards_balance" name="wc_custom_rewards_balance" required />
            <br><br>
            <input type="submit" value="تحديث الرصيد" class="button button-primary" />
        </form>
    </div>
    <?php
}

?>
