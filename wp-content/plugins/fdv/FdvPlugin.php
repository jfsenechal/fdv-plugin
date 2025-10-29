<?php
/**
 * Plugin Name: Fond des Vaulx
 * Plugin URI: https://fonddesvaulx.be
 * Description: Custom functionality for the Fond des Vaulx website
 * Version: 1.0.0
 * Author: Jean-Francois Senechal
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: fdv
 * Domain Path: /languages
 */

namespace Fdv\Plugin;

// If this file is called directly, abort.

if (!defined('WPINC')) {
    die;
}
/**
 * Plugin version.
 */
define('FDV_VERSION', '1.0.0');

/**
 * Plugin directory path.
 */
define('FDV_PLUGIN_DIR', plugin_dir_path(__FILE__));

/**
 * Plugin directory URL.
 */
define('FDV_PLUGIN_URL', plugin_dir_url(__FILE__));

class FdvPlugin
{
    public function __construct()
    {
        register_activation_hook(__FILE__, [$this, 'fdv_activate']);
        register_deactivation_hook(__FILE__, [$this, 'fdv_deactivate']);
        add_action('plugins_loaded', [$this, 'fdv_init']);
    }

    /**
     * Code that runs during plugin activation.
     */
    function fdv_activate()
    {
        // Flush rewrite rules
    }

    /**
     * Code that runs during plugin deactivation.
     */
    function fdv_deactivate()
    {
        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Initialize the plugin.
     */
    function fdv_init()
    {
        new FdvRouter();
        new ShortCode();
    }

}

new FdvPlugin();