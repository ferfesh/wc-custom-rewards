<?php

// إضافة الرصيد إلى حساب المستخدم
function wc_custom_rewards_add_balance( $user_id, $amount ) {
    $current_balance = get_user_meta( $user_id, '_wc_custom_rewards_balance', true );
    $new_balance = $current_balance + $amount;
    update_user_meta( $user_id, '_wc_custom_rewards_balance', $new_balance );
}

// عرض الرصيد في حساب المستخدم
function wc_custom_rewards_display_balance() {
    $user_id = get_current_user_id();
    $balance = get_user_meta( $user_id, '_wc_custom_rewards_balance', true );
    echo '<p>رصيد المكافآت: ' . esc_html( $balance ) . '</p>';
}
add_action( 'woocommerce_account_dashboard', 'wc_custom_rewards_display_balance' );

// تخصيص الأسعار في سلة الشراء
function wc_custom_rewards_apply_discount( $cart ) {
    $user_id = get_current_user_id();
    $balance = get_user_meta( $user_id, '_wc_custom_rewards_balance', true );

    if ( $balance > 0 ) {
        foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
            $product = $cart_item['data'];
            $price = $product->get_price();
            $discounted_price = $price - $balance; // خصم الرصيد من السعر

            if ( $discounted_price < 0 ) {
                $discounted_price = 0;
            }

            $product->set_price( $discounted_price );
        }
    }
}
add_action( 'woocommerce_before_calculate_totals', 'wc_custom_rewards_apply_discount' );

// حفظ ملاحظة في سلة الشراء
function wc_custom_rewards_add_cart_notice() {
    $user_id = get_current_user_id();
    $balance = get_user_meta( $user_id, '_wc_custom_rewards_balance', true );

    if ( $balance > 0 ) {
        wc_add_notice( 'تم تطبيق خصم رصيد المكافآت: ' . esc_html( $balance ) . ' على سلتك.', 'notice' );
    }
}
add_action( 'woocommerce_check_cart_items', 'wc_custom_rewards_add_cart_notice' );

// إضافة الرصيد إلى حساب المستخدم
/* function wc_custom_rewards_add_balance( $user_id, $amount ) {
    $current_balance = get_user_meta( $user_id, '_wc_custom_rewards_balance', true );
    $new_balance = $current_balance + $amount;
    update_user_meta( $user_id, '_wc_custom_rewards_balance', $new_balance );
    
    // إرسال إشعار بريدي للمستخدم
    $user_info = get_userdata( $user_id );
    $to = $user_info->user_email;
    $subject = 'تم تحديث رصيد المكافآت الخاص بك';
    $message = 'عزيزي ' . $user_info->display_name . '،' . "\r\n\r\n" .
               'تم إضافة ' . $amount . ' إلى رصيد المكافآت الخاص بك. رصيدك الحالي هو: ' . $new_balance . '.';
    wp_mail( $to, $subject, $message );
} */

// تعريف الدالة wc_custom_rewards_add_balance في ملف functions.php
if ( ! function_exists( 'wc_custom_rewards_add_balance' ) ) {
  function wc_custom_rewards_add_balance($user_id, $amount) {
      // جلب الرصيد الحالي للمستخدم
      $current_balance = get_user_meta($user_id, '_wc_custom_rewards_balance', true);
      
      // إذا كانت القيمة غير عددية أو فارغة، تعيينها إلى صفر
      if (empty($current_balance) || !is_numeric($current_balance)) {
          $current_balance = 0;
      }

      // تحويل الرصيد الحالي والمبلغ إلى عدد عشري
      $current_balance = (float) $current_balance;
      $amount = (float) $amount;
      
      // جمع الرصيد الحالي مع المبلغ الجديد
      $new_balance = $current_balance + $amount;
      
      // تحديث رصيد المستخدم في قاعدة البيانات
      update_user_meta($user_id, '_wc_custom_rewards_balance', $new_balance);
  }
}

// إضافة خيار الرصيد في صفحة تحرير المستخدم في لوحة التحكم
function wc_custom_rewards_user_profile_fields( $user ) {
  ?>
  <h3>رصيد المكافآت</h3>
  <table class="form-table">
      <tr>
          <th><label for="wc_custom_rewards_balance">رصيد المكافآت</label></th>
          <td>
              <input type="number" id="wc_custom_rewards_balance" name="wc_custom_rewards_balance" value="<?php echo esc_attr( get_user_meta( $user->ID, '_wc_custom_rewards_balance', true ) ); ?>" class="regular-text" />
          </td>
      </tr>
  </table>
  <?php
}
add_action( 'show_user_profile', 'wc_custom_rewards_user_profile_fields' );
add_action( 'edit_user_profile', 'wc_custom_rewards_user_profile_fields' );

// حفظ الرصيد عند تحديث الملف الشخصي
function wc_custom_rewards_save_user_profile_fields( $user_id ) {
  if ( isset( $_POST['wc_custom_rewards_balance'] ) ) {
      update_user_meta( $user_id, '_wc_custom_rewards_balance', sanitize_text_field( $_POST['wc_custom_rewards_balance'] ) );
  }
}
add_action( 'personal_options_update', 'wc_custom_rewards_save_user_profile_fields' );
add_action( 'edit_user_profile_update', 'wc_custom_rewards_save_user_profile_fields' );
?>
