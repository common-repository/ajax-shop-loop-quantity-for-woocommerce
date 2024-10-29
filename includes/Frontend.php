<?php

namespace ASLQ;

/**
 * The Frontend Class
 */
class Frontend
{

  /**
   * Initializing the frontend Class
   */
  public function __construct()
  {
    add_action('wp_enqueue_scripts', [$this, 'aslq_enqueues']);
    new Frontend\WC_Hooks();
  }

  /**
   * enqueueing plugin's frontend styles and js
   */
  public function aslq_enqueues()
  {
    wp_enqueue_style('aslq_styles', ASLQ_ASSETS.'/css/style.css');
    wp_enqueue_script('aslq_script', ASLQ_ASSETS.'/js/script.js', array('jquery'), '1.0', true);

    $product_id_nonce = wp_create_nonce('wc_product_id');
    wp_localize_script('aslq_script', 'wc_product_id_obj', array(
      'ajaxurl' => admin_url('admin-ajax.php'),
      // 'nonce' => $product_id_nonce
    ));
  }
}
