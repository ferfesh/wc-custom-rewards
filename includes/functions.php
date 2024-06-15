<?php
// إضافة الرصيد إلى حساب المستخدم
function wc_custom_rewards_add_balance( $user_id, $amount ) {
  $current_balance = get_user_meta( $user_id, '_wc_custom_rewards_balance', true );
  $current_balance = floatval($current_balance); // تحويل السلسلة النصية إلى عدد عشري
  $new_balance = $current_balance + $amount;
  $new_balance = floatval($new_balance); // التأكد من أن المتغير الثاني أيضاً عدد عشري
  return $current_balance + $new_balance;
  update_user_meta( $user_id, '_wc_custom_rewards_balance', $new_balance );

  // إرسال إشعار بريدي للمستخدم
  $user_info = get_userdata( $user_id );
  $to = $user_info->user_email;
  $subject = __( 'تم تحديث رصيد المكافآت الخاص بك', 'wc-custom-rewards' );
  $message = sprintf(
      __( 'عزيزي %s،' . "\r\n\r\n" . 'تم إضافة %s إلى رصيد المكافآت الخاص بك. رصيدك الحالي هو: %s.', 'wc-custom-rewards' ),
      $user_info->display_name,
      $amount,
      $new_balance
  );
  wp_mail( $to, $subject, $message );
}

/* // إضافة الرصيد إلى حساب المستخدم
function wc_custom_rewards_add_balance($current_balance, $amount_to_add) {
  $current_balance = floatval($current_balance); // تحويل السلسلة النصية إلى عدد عشري
  $amount_to_add = floatval($amount_to_add); // التأكد من أن المتغير الثاني أيضاً عدد عشري

  return $current_balance + $amount_to_add;
} */


// عرض الرصيد في صفحة الحساب
add_action('woocommerce_before_my_account', 'wc_custom_rewards_show_balance');
function wc_custom_rewards_show_balance() {
    $user_id = get_current_user_id();
    $balance = get_user_meta($user_id, '_wc_custom_rewards_balance', true);
    if ($balance) {
        echo '<p>' . __('رصيد المكافآت: ', 'wc-custom-rewards') . wc_price($balance) . '</p>';
    }
}

add_action( 'woocommerce_account_dashboard', 'wc_custom_rewards_display_balance' );

// تطبيق الخصم بناءً على رصيد المكافآت
add_action('woocommerce_cart_calculate_fees', 'wc_custom_rewards_apply_discount_purchase');
function wc_custom_rewards_apply_discount_purchase() {
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    $user_id = get_current_user_id();
    $balance = get_user_meta($user_id, '_wc_custom_rewards_balance', true);

    if ($balance && $balance > 0) {
        WC()->cart->add_fee(__('خصم رصيد المكافآت', 'wc-custom-rewards'), -$balance);
        update_user_meta($user_id, '_wc_custom_rewards_balance', 0);
    }
}

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

/* // تعريف الدالة wc_custom_rewards_add_balance في ملف functions.php
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
} */

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
