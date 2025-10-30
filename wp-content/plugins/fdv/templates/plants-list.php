<?php
/**
 * Title: Classes
 * Slug: emerge-preschool/classes
 * Categories: theme
 *
 * @package emerge-preschool
 * @since 1.0.0
 */

use Fdv\Plugin\FdvTemplate;

if (empty($plants)) {
    return;
}
?>

<!-- wp:group {"metadata":{"name":"Classes Section","categories":["theme"],"patternName":"emerge-preschool/classes"},"align":"full","style":{"spacing":{"blockGap":"var:preset|spacing|60","padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"margin":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}},"color":{"background":"#92278f"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-background"
     style="background-color:#92278f;margin-top:var(--wp--preset--spacing--80);margin-bottom:var(--wp--preset--spacing--80);padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)">
    <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20","margin":{"top":"0","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
    <div class="wp-block-group" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--70)">
        <!-- wp:heading {"textAlign":"center","level":6,"style":{"border":{"width":"0px","style":"none"},"layout":{"selfStretch":"fit","flexSize":null},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}},"color":{"text":"#ffc000"}},"fontSize":"large"} -->
        <h6 class="wp-block-heading has-text-align-center has-text-color has-large-font-size"
            style="border-style:none;border-width:0px;color:#ffc000;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
            <?php esc_html_e('Nos Plantes', 'fdv'); ?>
        </h6>
        <!-- /wp:heading -->

        <!-- wp:heading {"textAlign":"center","level":3,"style":{"typography":{"lineHeight":"1.1"},"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|40"}},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base","fontSize":"xx-large"} -->
        <h3 class="wp-block-heading has-text-align-center has-base-color has-text-color has-link-color has-xx-large-font-size"
            style="padding-top:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--40);line-height:1.1">
         <?php esc_html_e('Découvrez Notre Collection', 'fdv'); ?>
        </h3>
        <!-- /wp:heading -->
    </div>
    <!-- /wp:group -->


    <?php
    // Grid container - split into rows of 4
    $chunks = array_chunk($plants, 3);
    foreach ($chunks as $chunk): ?>
        <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
        <div class="wp-block-columns">
            <?php
            foreach ($chunk as $index => $plant):
                // Load plant card template
                FdvTemplate::fdv_get_template('plant-inline.php', [
                        'plant' => $plant,
                ]);
            endforeach;
            ?>
        </div>
        <!-- /wp:columns -->
    <?php endforeach; ?>
</div>
<!-- /wp:group -->