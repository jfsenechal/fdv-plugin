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
        $this->add_rewrite_rule();
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
            $codeCgt = get_query_var(self::PARAM_PLANT);

            // Check if codeCgt exists
            if ($codeCgt) {
                // Look for template in theme directory
                $custom_template = locate_template('single_plant.php');

                if ($custom_template) {
                    return $custom_template;
                }
            }
        }

        return $template;
    }

}