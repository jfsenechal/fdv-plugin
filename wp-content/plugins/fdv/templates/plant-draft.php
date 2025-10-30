<?php
/**
 * Plant Content Template
 *
 * This template contains the full page structure with FSE template parts
 * and the plant content in between.
 *
 * @var array $plant Plant data from API
 * @package fdv
 */

use Fdv\Plugin\FdvTemplate;

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
?>

<!-- wp:template-part {"slug":"header","tagName":"header","className":"site-header"} /-->

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--50)">

    <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
    <div class="wp-block-columns">

        <!-- wp:column {"width":"40%"} -->
        <div class="wp-block-column" style="flex-basis:40%">
            <!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
            <figure class="wp-block-image size-large">
                <img src="<?php echo esc_url($imageUrl); ?>" alt="<?php echo esc_attr($frenchName); ?>" />
            </figure>
            <!-- /wp:image -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"60%"} -->
        <div class="wp-block-column" style="flex-basis:60%">

            <!-- wp:heading {"level":1} -->
            <h1 class="wp-block-heading"><?php echo $frenchName; ?></h1>
            <!-- /wp:heading -->

            <?php if (!empty($latinName)): ?>
            <!-- wp:paragraph {"style":{"typography":{"fontStyle":"italic"}},"fontSize":"medium"} -->
            <p class="has-medium-font-size" style="font-style:italic"><?php echo $latinName; ?></p>
            <!-- /wp:paragraph -->
            <?php endif; ?>

            <?php if (!empty($category)): ?>
            <!-- wp:paragraph -->
            <p><strong>Catégorie:</strong> <?php echo $category; ?></p>
            <!-- /wp:paragraph -->
            <?php endif; ?>

            <?php if (!empty($description)): ?>
            <!-- wp:separator {"className":"is-style-wide"} -->
            <hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
            <!-- /wp:separator -->

            <!-- wp:heading {"level":3} -->
            <h3 class="wp-block-heading">Description</h3>
            <!-- /wp:heading -->

            <!-- wp:paragraph -->
            <p><?php echo $description; ?></p>
            <!-- /wp:paragraph -->
            <?php endif; ?>

        </div>
        <!-- /wp:column -->

    </div>
    <!-- /wp:columns -->

    <?php if (!empty($height) || !empty($width) || !empty($floweringPeriod)): ?>
    <!-- wp:separator {"className":"is-style-wide"} -->
    <hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
    <!-- /wp:separator -->

    <!-- wp:heading {"level":3} -->
    <h3 class="wp-block-heading">Informations complémentaires</h3>
    <!-- /wp:heading -->

    <!-- wp:list -->
    <ul class="wp-block-list">
        <?php if (!empty($height)): ?>
        <!-- wp:list-item -->
        <li><strong>Hauteur:</strong> <?php echo $height; ?></li>
        <!-- /wp:list-item -->
        <?php endif; ?>

        <?php if (!empty($width)): ?>
        <!-- wp:list-item -->
        <li><strong>Largeur:</strong> <?php echo $width; ?></li>
        <!-- /wp:list-item -->
        <?php endif; ?>

        <?php if (!empty($floweringPeriod)): ?>
        <!-- wp:list-item -->
        <li><strong>Période de floraison:</strong> <?php echo $floweringPeriod; ?></li>
        <!-- /wp:list-item -->
        <?php endif; ?>
    </ul>
    <!-- /wp:list -->
    <?php endif; ?>

    <?php if (!empty($photos) && count($photos) > 1): ?>
    <!-- wp:separator {"className":"is-style-wide"} -->
    <hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
    <!-- /wp:separator -->

    <!-- wp:heading {"level":3} -->
    <h3 class="wp-block-heading">Galerie photos</h3>
    <!-- /wp:heading -->

    <!-- wp:gallery {"columns":3,"linkTo":"none"} -->
    <figure class="wp-block-gallery has-nested-images columns-3 is-cropped">
        <?php foreach ($photos as $photo):
            $photoUrl = $photo['url'] ?? $photo['image_url'] ?? $photo['path'] ?? (is_string($photo) ? $photo : '');
            if (empty($photoUrl)) continue;
        ?>
        <!-- wp:image -->
        <figure class="wp-block-image">
            <img src="<?php echo esc_url($photoUrl); ?>" alt="<?php echo esc_attr($frenchName); ?>" />
        </figure>
        <!-- /wp:image -->
        <?php endforeach; ?>
    </figure>
    <!-- /wp:gallery -->
    <?php endif; ?>

</div>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer","className":"site-footer"} /-->