<?php

namespace Fdv\Plugin;

class ShortCode
{
    public function __construct()
    {
        add_shortcode('fdv_list_plants', [$this, 'listPlants']);
    }

    /**
     * Render plants list shortcode
     *
     * @return string
     */
    public function listPlants(): string
    {
        $plants = FdvRepository::getPlants();

        // If no plants data, show appropriate message
        if (empty($plants)) {
            return $this->renderEmptyMessage();
        }

        // Use output buffering to capture template output
        //   ob_start();
        $content = FdvTemplate::fdv_get_template('plants-list.php', [
            'plants' => $plants['data'],
        ], false);

        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);
        echo $content;

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