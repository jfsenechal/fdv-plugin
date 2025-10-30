<?php
/**
 * Single Plant Template
 *
 * This template displays a single plant page.
 * URL: /lesplantes/{plant-code}
 *
 * This template is designed for FSE (Full Site Editing) themes.
 *
 * @package Fdv\Plugin
 */

use Fdv\Plugin\FdvRepository;
use Fdv\Plugin\FdvTemplate;

// Get plant code from URL
$plantCode = get_query_var('codePlant');

if (empty($plantCode)) {
    // No plant code provided - show 404
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    // Let WordPress handle 404 display
    include(get_query_template('404'));
    exit;
}

// Fetch plant data from API
$plant = FdvRepository::getPlantByCode($plantCode);

if (empty($plant)) {
    // Plant not found - show 404
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    include(get_query_template('404'));
    exit;
}
$plant = $plant['data'];
// Extract plant data
$frenchName = esc_html($plant['french_name'] ?? 'Nom inconnu');
$latinName = esc_html($plant['latin_name'] ?? '');
$category = esc_html($plant['category'] ?? '');
$description = wp_kses_post($plant['description'] ?? '');
$imageUrl = FdvTemplate::fdv_get_plant_image_url($plant);
$height = esc_html($plant['height'] ?? '');
$width = esc_html($plant['width'] ?? '');
$floweringPeriod = esc_html($plant['flowering_period'] ?? '');
$photos = $plant['photos'] ?? [];
// Set document title for SEO
add_filter('pre_get_document_title', function () use ($plant) {
    return esc_html($plant['french_name'] ?? 'Plante').' - '.get_bloginfo('name');
}, 10);

// Plant data available
$frenchName = esc_html($plant['french_name'] ?? 'Nom inconnu');
$latinName = esc_html($plant['latin_name'] ?? '');
$category = esc_html($plant['category'] ?? '');
$description = wp_kses_post($plant['description'] ?? '');
$imageUrl = FdvTemplate::fdv_get_plant_image_url($plant);

$content = FdvTemplate::fdv_get_template('plant-content.php', [
        'plant' => $plant,
], false);

$content = apply_filters('the_content', $content);
$content = str_replace(']]>', ']]&gt;', $content);

echo $content;