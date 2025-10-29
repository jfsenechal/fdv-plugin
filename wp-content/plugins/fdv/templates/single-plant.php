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

// Set document title for SEO
add_filter('pre_get_document_title', function() use ($plant) {
    return esc_html($plant['french_name'] ?? 'Plante') . ' - ' . get_bloginfo('name');
}, 10);

// For FSE themes, we need to render within the site editor context
block_template_part('header');

// Plant data available
$frenchName = esc_html($plant['french_name'] ?? 'Nom inconnu');
$latinName = esc_html($plant['latin_name'] ?? '');
$category = esc_html($plant['category'] ?? '');
$description = wp_kses_post($plant['description'] ?? '');
$imageUrl = FdvTemplate::fdv_get_plant_image_url($plant);
?>

<main id="main" class="site-main">
    <article id="plant-<?php echo esc_attr($plantCode); ?>" class="plant-single">

        <!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
        <div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)">

            <!-- Header Section with Image -->
            <!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
            <div class="wp-block-columns alignwide">

                <!-- Image Column -->
                <!-- wp:column {"width":"40%"} -->
                <div class="wp-block-column" style="flex-basis:40%">
                    <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"border":{"radius":"20px"}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
                    <div class="wp-block-group has-base-background-color has-background" style="border-radius:20px;padding:var(--wp--preset--spacing--50)">
                        <img src="<?php echo esc_url($imageUrl); ?>"
                             alt="<?php echo esc_attr($frenchName); ?>"
                             style="width:100%;height:auto;border-radius:10px;object-fit:cover;" />
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:column -->

                <!-- Content Column -->
                <!-- wp:column {"width":"60%"} -->
                <div class="wp-block-column" style="flex-basis:60%">

                    <!-- Breadcrumb / Back Link -->
                    <p style="margin-bottom:var(--wp--preset--spacing--40)">
                        <a href="<?php echo esc_url(home_url('/plantes')); ?>" style="color:#ffc000;text-decoration:none;">
                            ← <?php esc_html_e('Retour à la liste', 'fdv'); ?>
                        </a>
                    </p>

                    <!-- Plant Title -->
                    <h1 class="wp-block-heading" style="font-size:var(--wp--preset--font-size--xx-large);line-height:1.1;margin-bottom:var(--wp--preset--spacing--20)">
                        <?php echo $frenchName; ?>
                    </h1>

                    <?php if (!empty($latinName)): ?>
                        <!-- Latin Name -->
                        <p style="font-style:italic;font-size:1.2em;color:#666;margin-bottom:var(--wp--preset--spacing--40)">
                            <?php echo $latinName; ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($category)): ?>
                        <!-- Category Badge -->
                        <!-- wp:group {"style":{"spacing":{"padding":{"top":"0.5rem","bottom":"0.5rem","left":"1rem","right":"1rem"}},"border":{"radius":"50px"}},"backgroundColor":"contrast","layout":{"type":"constrained","justifyContent":"left"}} -->
                        <div class="wp-block-group has-contrast-background-color has-background" style="border-radius:50px;padding:0.5rem 1rem;display:inline-block;margin-bottom:var(--wp--preset--spacing--50)">
                            <p class="has-base-color has-text-color" style="margin:0;font-size:0.9em;">
                                <?php echo sprintf(__('Catégorie : %s', 'fdv'), $category); ?>
                            </p>
                        </div>
                        <!-- /wp:group -->
                    <?php endif; ?>

                </div>
                <!-- /wp:column -->

            </div>
            <!-- /wp:columns -->

            <?php if (!empty($description)): ?>
                <!-- Description Section -->
                <!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
                <div class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--80)">

                    <h2 class="wp-block-heading" style="font-size:var(--wp--preset--font-size--x-large);margin-bottom:var(--wp--preset--spacing--40)">
                        <?php esc_html_e('Description', 'fdv'); ?>
                    </h2>

                    <div class="plant-description" style="font-size:1.1em;line-height:1.8">
                        <?php echo $description; ?>
                    </div>

                </div>
                <!-- /wp:group -->
            <?php endif; ?>

            <?php if (!empty($plant['photos']) && is_array($plant['photos']) && count($plant['photos']) > 1): ?>
                <!-- Photo Gallery Section -->
                <!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
                <div class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--80)">

                    <h2 class="wp-block-heading" style="font-size:var(--wp--preset--font-size--x-large);margin-bottom:var(--wp--preset--spacing--40)">
                        <?php esc_html_e('Galerie Photos', 'fdv'); ?>
                    </h2>

                    <!-- wp:gallery {"columns":3,"linkTo":"none"} -->
                    <div class="wp-block-gallery has-nested-images columns-3">
                        <?php foreach ($plant['photos'] as $photo):
                            $photoUrl = $photo['url'] ?? $photo['image_url'] ?? $photo['path'] ?? (is_string($photo) ? $photo : '');
                            if (!empty($photoUrl)): ?>
                                <figure class="wp-block-image">
                                    <img src="<?php echo esc_url($photoUrl); ?>"
                                         alt="<?php echo esc_attr($frenchName); ?>"
                                         style="border-radius:10px;object-fit:cover;aspect-ratio:1;" />
                                </figure>
                            <?php endif;
                        endforeach; ?>
                    </div>
                    <!-- /wp:gallery -->

                </div>
                <!-- /wp:group -->
            <?php endif; ?>

            <?php
            // Additional plant data fields (if available)
            $additionalFields = [
                'height' => __('Hauteur', 'fdv'),
                'width' => __('Largeur', 'fdv'),
                'flowering_period' => __('Période de floraison', 'fdv'),
                'exposure' => __('Exposition', 'fdv'),
                'soil_type' => __('Type de sol', 'fdv'),
                'hardiness' => __('Rusticité', 'fdv'),
                'growth_rate' => __('Vitesse de croissance', 'fdv'),
            ];

            $hasAdditionalInfo = false;
            foreach ($additionalFields as $key => $label) {
                if (!empty($plant[$key])) {
                    $hasAdditionalInfo = true;
                    break;
                }
            }

            if ($hasAdditionalInfo): ?>
                <!-- Additional Information Section -->
                <!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|80"}},"color":{"background":"#f5f5f5"},"border":{"radius":"20px"},"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|60","right":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
                <div class="wp-block-group alignwide has-background" style="background-color:#f5f5f5;border-radius:20px;margin-top:var(--wp--preset--spacing--80);padding:var(--wp--preset--spacing--60)">

                    <h2 class="wp-block-heading" style="font-size:var(--wp--preset--font-size--x-large);margin-bottom:var(--wp--preset--spacing--40)">
                        <?php esc_html_e('Informations Complémentaires', 'fdv'); ?>
                    </h2>

                    <dl style="display:grid;grid-template-columns:200px 1fr;gap:1rem;margin:0;">
                        <?php foreach ($additionalFields as $key => $label):
                            if (!empty($plant[$key])): ?>
                                <dt style="font-weight:600;color:#333;"><?php echo esc_html($label); ?> :</dt>
                                <dd style="margin:0;color:#666;"><?php echo esc_html($plant[$key]); ?></dd>
                            <?php endif;
                        endforeach; ?>
                    </dl>

                </div>
                <!-- /wp:group -->
            <?php endif; ?>

        </div>
        <!-- /wp:group -->

    </article>
</main>

<?php
block_template_part('footer');