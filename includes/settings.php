<?php
// إضافة صفحة إعدادات الإضافة في لوحة التحكم
function wc_custom_rewards_settings_page() {
  add_options_page(
      __( 'إعدادات المكافآت المخصصة', 'wc-custom-rewards' ),
      __( 'مكافآت ووكومرس', 'wc-custom-rewards' ),
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
      update_option( 'wc_custom_rewards_special_users', sanitize_text_field( $_POST['wc_custom_rewards_special_users'] ) );
      echo '<div class="updated"><p>' . __( 'تم تحديث الإعدادات بنجاح.', 'wc-custom-rewards' ) . '</p></div>';
  }

  $discount_percentage = get_option( 'wc_custom_rewards_discount_percentage', '10' );
  $special_users = get_option( 'wc_custom_rewards_special_users', '' );

  ?>
  <div class="wrap">
      <h1><?php echo esc_html( __( 'إعدادات المكافآت المخصصة', 'wc-custom-rewards' ) ); ?></h1>
      <form method="post">
          <label for="wc_custom_rewards_discount_percentage"><?php echo esc_html( __( 'نسبة الخصم للمكافآت:', 'wc-custom-rewards' ) ); ?></label>
          <input type="number" id="wc_custom_rewards_discount_percentage" name="wc_custom_rewards_discount_percentage" value="<?php echo esc_attr( $discount_percentage ); ?>" />
          <br><br>
          <label for="wc_custom_rewards_special_users"><?php echo esc_html( __( 'مستخدمون مميزون (IDs مفصولة بفواصل):', 'wc-custom-rewards' ) ); ?></label>
          <input type="text" id="wc_custom_rewards_special_users" name="wc_custom_rewards_special_users" value="<?php echo esc_attr( $special_users ); ?>" />
          <br><br>
          <input type="submit" name="wc_custom_rewards_settings_submit" value="<?php echo esc_attr( __( 'حفظ الإعدادات', 'wc-custom-rewards' ) ); ?>" class="button button-primary" />
      </form>
  </div>
  <?php
}

?>
