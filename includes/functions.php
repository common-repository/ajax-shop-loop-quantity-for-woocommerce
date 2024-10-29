<?php

add_action("wp_ajax_prod_cart_update", "aslq_prod_cart_update");
add_action("wp_ajax_nopriv_prod_cart_update", "aslq_prod_cart_update");

/**
 * updating cart on product quantity change
 */
function aslq_prod_cart_update()
{
  if (!wp_verify_nonce($_POST['nonce'], "wc_product_id")) {
    exit("No naughty business please");
  }

  $product_id = sanitize_text_field($_POST["product_id"]);
  $qty_value = sanitize_text_field($_POST["qty_value"]);
  
  foreach (WC()->cart->get_cart() as $existing_cart_item_key => $existing_cart_item) {
    if ($product_id == $existing_cart_item['product_id']) {
      $our_product_values = $existing_cart_item;
      $our_product_quantity = $existing_cart_item['quantity'];
      $cart_item_key = $existing_cart_item_key;
    };
  }

  // Update cart validation
  $passed_validation  = apply_filters('woocommerce_update_cart_validation', true, $cart_item_key, $our_product_values, $our_product_quantity);

  // Update the quantity of the item in the cart
  if ($passed_validation) {
    $new_quantity = $qty_value;
    WC()->cart->set_quantity($cart_item_key, $new_quantity, true);
  }


  if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    esc_html_e( $product_id);
  } else {
    header("Location: " . $_SERVER["HTTP_REFERER"]);
  }
  die();
}


add_filter('woocommerce_add_to_cart_fragments', 'aslq_shop_loop_quantity_fragment');
function aslq_shop_loop_quantity_fragment($fragments)
{  
  ob_start();
  $qty_type = (!empty($_POST['type']) ? filter_var($_POST['type'], FILTER_SANITIZE_STRING) : null);
  $qty_cart_item_key = sanitize_text_field($_POST['cart_item_key']);
  $qty_cart_item = WC()->cart->cart_contents[$qty_cart_item_key];
  if ($qty_cart_item_key && in_array($qty_type, array('update'))) {
    $cart_quantity = $qty_cart_item['quantity'];
    $qty_product = apply_filters(
      'woocommerce_cart_item_product',
      $qty_cart_item['data'],
      $qty_cart_item,
      $qty_cart_item_key
    );
    $qty_product_id = $qty_cart_item['product_id'];
  ?>

    <div class="aslq-qty" data-p-id="<?php _e($qty_product_id) ?>">
      <?php
      woocommerce_quantity_input(
        array(
          'input_name'   => "cart[{$qty_cart_item_key}][qty]",
          'input_value'  => $cart_quantity,
          'max_value'    => $qty_product->get_max_purchase_quantity(),
          'min_value'    => '0',
          'product_name' => $qty_product->get_name(),
        ),
        $qty_product,
        true
      );

      ?>
    </div>

  <?php
    $fragments['.aslq-qty[data-p-id="' . $qty_product_id . '"]'] = ob_get_clean();
  }
  return $fragments;
}