<?php
/* // إضافة خيار الرصيد في صفحة تحرير المستخدم في لوحة التحكم
function wc_custom_rewards_user_profile_fields( $user ) {
    ?>
    <h3><?php _e( 'رصيد المكافآت', 'wc-custom-rewards' ); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="wc_custom_rewards_balance"><?php _e( 'رصيد المكافآت', 'wc-custom-rewards' ); ?></label></th>
            <td>
                <input type="number" id="wc_custom_rewards_balance" name="wc_custom_rewards_balance" value="<?php echo esc_attr( get_user_meta( $user->ID, '_wc_custom_rewards_balance', true ) ); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
    <?php
}
add_action( 'show_user_profile', 'wc_custom_rewards_user_profile_fields' );
add_action( 'edit_user_profile', 'wc_custom_rewards_user_profile_fields' ) */;

/* // حفظ الرصيد عند تحديث الملف الشخصي
function wc_custom_rewards_save_user_profile_fields( $user_id ) {
    if ( isset( $_POST['wc_custom_rewards_balance'] ) ) {
        update_user_meta( $user_id, '_wc_custom_rewards_balance', sanitize_text_field( $_POST['wc_custom_rewards_balance'] ) );
    }
}
add_action( 'personal_options_update', 'wc_custom_rewards_save_user_profile_fields' );
add_action( 'edit_user_profile_update', 'wc_custom_rewards_save_user_profile_fields' ); */

// إضافة صفحة إدارة المكافآت في لوحة التحكم
function wc_custom_rewards_admin_page() {
    add_menu_page(
        __( 'إدارة المكافآت', 'wc-custom-rewards' ),
        __( 'مكافآت ووكومرس', 'wc-custom-rewards' ),
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
        echo '<div class="updated"><p>' . __( 'تم تحديث رصيد المستخدم بنجاح.', 'wc-custom-rewards' ) . '</p></div>';
    }

    ?>
    <div class="wrap">
        <h1><?php echo esc_html(__('إدارة المكافآت', 'wc-custom-rewards')); ?></h1>
        <form method="post">
            <label for="wc_custom_rewards_user_id"><?php echo esc_html(__('معرف المستخدم:', 'wc-custom-rewards')); ?></label>
            <input type="number" id="wc_custom_rewards_user_id" name="wc_custom_rewards_user_id" required />
            <br><br>
            <label for="wc_custom_rewards_balance"><?php echo esc_html(__('رصيد المكافآت:', 'wc-custom-rewards')); ?></label>
            <input type="number" id="wc_custom_rewards_balance" name="wc_custom_rewards_balance" required />
            <br><br>
            <input type="submit" value="<?php echo esc_attr(__('تحديث الرصيد', 'wc-custom-rewards')); ?>" class="button button-primary" />
        </form>
    </div>
    <?php
}
?>
