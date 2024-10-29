<?php

namespace ASLQ;

/**
 * The Installer class
 */
class Installer
{
  /**
   * Run the installer
   *
   * @return void
   */
  public function run()
  {
    $this->add_version();
    set_transient('aslq_show_admin_notice', true, 5);
  }

  public function add_version()
  {
    $installed = get_option('ASLQ_INSTALLED');
    if (!$installed) {
      update_option("ASLQ_INSTALLED", time());
    }

    update_option("ASLQ_VERSION", ASLQ_VERSION);
  }
}