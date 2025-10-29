<?php

namespace Fdv\Plugin;

class ShortCode
{
    public function __construct()
    {
        add_shortcode('lesplantes', [$this, 'lesplantes']);
    }

    /**
     * Render plants list shortcode
     *
     * @return string
     */
    public function lesplantes(): string
    {
        $plants = FdvRepository::getPlants();

        // If no plants data, show appropriate message
        if (empty($plants)) {
            return $this->renderEmptyMessage();
        }

        // Use output buffering to capture template output
        ob_start();
        FdvTemplate::fdv_get_template('plants-grid.php', [
            'plants' => $plants['data'],
        ]);

        return ob_get_clean();
    }

    /**
     * Render empty/error message
     *
     * @return string
     */
    private function renderEmptyMessage(): string
    {
        // In debug mode, show error message
        if (defined('WP_DEBUG') && WP_DEBUG) {
            return '<div class="fdv-plants-error"><p>Unable to load plants data. Check error logs for details.</p></div>';
        }

        // In production, show user-friendly message
        return '<div class="fdv-plants-empty"><p>No plants available at the moment.</p></div>';
    }
}