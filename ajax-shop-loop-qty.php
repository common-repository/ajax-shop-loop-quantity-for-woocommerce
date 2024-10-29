<?php

/**
 * Plugin Name:       Ajax Shop Loop Quantity for WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/ajax-shop-loop-quantity-for-woocommerce
 * Description:       A WordPress plugin to enable ajax quantity field in shop loop items for woocommerce.
 * Version:           1.0
 * Author:            Apple Mahmood
 * Author URI:        https://www.applemahmood.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ajax-shop-loop-qty
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
  exit;
}

require_once __DIR__ . "/vendor/autoload.php";

/**
 * Main class for the plugin
 */
final class Ajax_Shop_Loop_Qty
{

  const version = 1.0;
  /**
   * Class Constructor
   */
  private function __construct()
  {
    $this->define_constants();

    register_activation_hook(ASLQ_FILE, [$this, 'activate']);

    add_action('plugins_loaded', [$this, 'plugin_init']);
  }
  /**
   * Singletone instance function
   *
   * @return \ASLQ
   */
  public static function init()
  {
    static $instance = false;

    if (!$instance) {
      $instance = new self();
    }

    return $instance;
  }

  /**
   * Defining necessary constants
   *
   * @return void
   */
  public function define_constants()
  {
    define('ASLQ_VERSION', self::version);
    define('ASLQ_NAME', 'Ajax Shop Loop Quantity');
    define('ASLQ_FILE', __FILE__);
    define('ASLQ_PATH', __DIR__);
    define('ASLQ_URL', plugins_url('', ASLQ_FILE));
    define('ASLQ_ASSETS', ASLQ_URL . '/assets');
  }

  /**
   * Upon activating the plugin
   *
   * @return void
   */
  public function activate()
  {
    $installer = new \ASLQ\Installer();
    $installer->run();
  }


  public function plugin_init()
  {
    if (class_exists('WooCommerce')) {
      if (is_admin()) {
        new \ASLQ\Admin();
      } else {
        new \ASLQ\Frontend();
      }
    } else {
      add_action('admin_notices', [$this, 'aslq_admin_notice_error']);
    }
  }

  public function aslq_admin_notice_error()
  {
  ?>
    <div class="notice notice-error is-dismissible">
      <p><?php
          printf(
            esc_html__('%1$s%2$s%3$s plugin requires %4$s to be installed and active', 'ajax-shop-loop-qty'),
            '<strong>',
            ASLQ_NAME,
            '</strong>',
            '<a target="_blank" href="https://en-ca.wordpress.org/plugins/woocommerce/"><strong>WooCommerce</strong></a>'
          );
          ?></p>
    </div>
  <?php
  }
}

/**
 * Initializing the plugin
 *
 * @return \ASLQ
 */
function ASLQ()
{
  return Ajax_Shop_Loop_Qty::init();
}

/**
 * Starting the plugin
 */
ASLQ();
