<?php

/**
 * Plugin Name: WP-Stateless - EWWW Image Optimizer Addon
 * Plugin URI: https://stateless.udx.io/addons/ewww/
 * Description: Provides compatibility between the EWWW Image Optimizer and the WP-Stateless plugins.
 * Author: UDX
 * Version: 0.0.1
 * Text Domain: wpsewww
 * Author URI: https://udx.io
 * License: MIT
 * 
 * Copyright 2021 UDX (email: info@udx.io)
 */

namespace WPSL\Ewww;

add_action('plugins_loaded', function () {
  if (class_exists('wpCloud\StatelessMedia\Compatibility')) {
    require_once 'vendor/autoload.php';
    // Load 
    return new Ewww();
  }

  add_filter('plugin_row_meta', function ($plugin_meta, $plugin_file, $_, $__) {
    if ($plugin_file !== join(DIRECTORY_SEPARATOR, [basename(__DIR__), basename(__FILE__)])) return $plugin_meta;
    $plugin_meta[] = sprintf('<span style="color:red;">%s</span>', __('This plugin requires WP-Stateless plugin version 4.0.0 or greater to be installed and active.'));
    return $plugin_meta;
  }, 10, 4);
});
