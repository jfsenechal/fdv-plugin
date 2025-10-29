<?php
/**
 * Template for displaying plants grid
 *
 * Available variables:
 * @var array $plants Array of plant data
 */

use Fdv\Plugin\FdvTemplate;

if (empty($plants)) {
    return;
}

// Define color palette from emerge-preschool theme
$colors = ['#ff6666', '#ffc000', '#abcd52', '#1ab9ff', '#92278f'];
?>

<!-- wp:group {"metadata":{"name":"Plants Section"},"style":{"spacing":{"blockGap":"var:preset|spacing|70","padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"0","right":"0"},"margin":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--80);margin-bottom:var(--wp--preset--spacing--80);padding-top:var(--wp--preset--spacing--50);padding-right:0;padding-bottom:var(--wp--preset--spacing--50);padding-left:0">

    <!-- Header section -->
    <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20","margin":{"top":"0","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
    <div class="wp-block-group" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--70)">
        <h6 class="wp-block-heading has-text-align-center has-text-color has-large-font-size" style="border-style:none;border-width:0px;color:#ffc000;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
            <?php esc_html_e('Nos Plantes', 'fdv'); ?>
        </h6>
        <h3 class="wp-block-heading has-text-align-center has-xx-large-font-size" style="padding-top:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--40);line-height:1.1">
            <?php esc_html_e('Découvrez Notre Collection', 'fdv'); ?>
        </h3>
    </div>
    <!-- /wp:group -->

    <?php
    // Grid container - split into rows of 4
    $chunks = array_chunk($plants, 4);

    foreach ($chunks as $chunk): ?>
        <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
        <div class="wp-block-columns">
            <?php
            foreach ($chunk as $index => $plant):
                $color = $colors[$index % count($colors)];

                // Load plant card template
                FdvTemplate::fdv_get_template('plant-card.php', [
                    'plant' => $plant,
                    'bgColor' => $color
                ]);
            endforeach;
            ?>
        </div>
        <!-- /wp:columns -->
    <?php endforeach; ?>

</div>
<!-- /wp:group -->