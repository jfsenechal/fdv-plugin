<?php

namespace Fdv\Plugin;

class ShortCode
{
    public function __construct()
    {
        add_shortcode('lesplantes', [$this, 'lesplantes']);
    }

    public function lesplantes(): string
    {
        $plants = FdvRepository::getPlants();

        // If no plants data, show appropriate message
        if (empty($plants)) {
            // In debug mode, show error message
            if (defined('WP_DEBUG') && WP_DEBUG) {
                return '<div class="fdv-plants-error"><p>Unable to load plants data. Check error logs for details.</p></div>';
            }

            // In production, show user-friendly message
            return '<div class="fdv-plants-empty"><p>No plants available at the moment.</p></div>';
        }

        // Build HTML output for plants
        $output = '<div class="fdv-plants-list">';
        foreach ($plants['data'] as $plant) {
            $output .= '<div class="fdv-plant-item">';
            $output .= '<h3>'.esc_html($plant['french_name'] ?? 'Unknown').'</h3>';
            // Add more plant details as needed
            $output .= '</div>';
        }
        $output .= '</div>';

        return $output;
    }
}