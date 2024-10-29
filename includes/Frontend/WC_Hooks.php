<?php

namespace ASLQ\Frontend;

/**
 * Class to modify woocommerce hooks
 */
class WC_Hooks
{
  /**
   * Construct the class
   */
  public function __construct()
  {
    add_action('woocommerce_after_shop_loop_item', [$this, 'aslq_before_shop_item_buttons'], 9);
    add_action('woocommerce_before_quantity_input_field', [$this, 'aslq_quantity_minus_btn']);
    add_action('woocommerce_after_quantity_input_field', [$this, 'aslq_quantity_plus_btn']);
  }



  /**
   * Show quantity input field and necessary elements before add to card button in shop loop
   */
  public function aslq_before_shop_item_buttons()
  {
    $template = __DIR__ . '/templates/wc-before-shop-item-buttons.php';
    if (file_exists($template)) {
      include $template;
    }
  }


  /**
   * Show minus button before quantity input field
   */
  public function aslq_quantity_minus_btn()
  {
    echo "<input type='button' value='-' class='minus' >";
  }
  /**
   * Show plus button after quantity input field
   */
  public function aslq_quantity_plus_btn()
  {
    echo "<input type='button' value='+' class='plus' >";
  }
}
