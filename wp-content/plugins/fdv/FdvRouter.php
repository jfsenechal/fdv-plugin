<?php

namespace Fdv\Plugin;

class FdvRouter
{
    const PARAM_PLANT = 'codePlant';
    const ROUTE = 'lesplantes';
    const SINGLE_PLANT = 'single_plant';

    public function __construct()
    {
        add_action('init', [$this, 'add_rewrite_rule']);
        add_filter('query_vars', [$this, 'add_query_vars']);
        add_filter('template_include', [$this, 'add_template']);
        //Flush rewrite rules on theme activation (only once)
        register_activation_hook(__FILE__, [$this, 'flush_rules']);
    }

    function flush_rules(): void
    {
        flush_rewrite_rules();
    }

    function add_rewrite_rule(): void
    {
        add_rewrite_rule(
            self::ROUTE.'([a-zA-Z0-9-]+)[/]?$',
            'index.php?single_plant=1&'.self::PARAM_PLANT.'=$matches[1]',  // Query vars
            'top'  // Priority
        );
    }

    function add_query_vars($vars)
    {
        $vars[] = self::SINGLE_PLANT;
        $vars[] = self::PARAM_PLANT;

        return $vars;
    }

    function add_template($template)
    {
        // Check if this is our custom route
        if (get_query_var(self::SINGLE_PLANT)) {
            $codePlant = get_query_var(self::PARAM_PLANT);

            // Check if plant code exists
            if ($codePlant) {
                // First, look for template in theme directory (allows theme override)
                $theme_template = locate_template(['single_plant.php', 'fdv/single-plant.php']);

                if ($theme_template) {
                    return $theme_template;
                }

                // Fall back to plugin template
                $plugin_template = FDV_PLUGIN_DIR.'templates/single-plant.php';

                if (file_exists($plugin_template)) {
                    return $plugin_template;
                }
            }

        }

        return $template;
    }

}