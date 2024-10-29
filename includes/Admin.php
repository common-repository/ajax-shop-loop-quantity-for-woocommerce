<?php

namespace ASLQ;

/**
 * The Admin Class
 */
class Admin
{
  public function __construct()
  {
    add_action('admin_notices', [$this, 'aslq_admin_notice_success']);
  }

  public function aslq_admin_notice_success()
  {
    /* Check transient, if available display notice */
    if(get_transient('aslq_show_admin_notice')){
      ?>
        <div class="notice notice-success is-dismissible">
          <p><?php _e('Thank you for installing ASLQ plugin!', 'ajax-shop-loop-qty'); ?></p>
        </div>
      <?php
      /* Delete transient, only display this notice once. */
      delete_transient('aslq_show_admin_notice');
    }
  }
}
