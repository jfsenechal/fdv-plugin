<?php
/**
 * Template for displaying a single plant card
 *
 * Available variables:
 * @var array $plant Plant data
 * @var string $bgColor Background color for the card
 */

use Fdv\Plugin\FdvTemplate;

if (empty($plant)) {
    return;
}

$frenchName = esc_html($plant['french_name'] ?? 'Nom inconnu');
$latinName = esc_html($plant['latin_name'] ?? '');
$conservation_status = strip_tags($plant['conservation_status'] ?? '');
$description = strip_tags($plant['description'] ?? '');
$id = (int)$plant['id'] ?? 0;
$imageUrl = FdvTemplate::fdv_get_plant_image_url($plant);
$plantUrl = FdvTemplate::fdv_get_plant_url($plant);
?>
<!-- wp:column {"width":"33.33%","style":{"spacing":{"blockGap":"0"}}} -->
<div class="wp-block-column" style="flex-basis:33.33%">
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}},"border":{"radius":"10px"}},"layout":{"type":"constrained","justifyContent":"center"}} -->
    <div class="wp-block-group"
         style="border-radius:10px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
        <!-- wp:image {"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":{"topLeft":"20px","topRight":"20px"}}}} -->
        <figure class="wp-block-image size-full has-custom-border">
            <a href="<?php echo esc_url($plantUrl) ?>">
                <img
                        src="<?php echo esc_url($imageUrl); ?>"
                        alt="" style="border-top-left-radius:20px;border-top-right-radius:20px"/>
            </a>
        </figure>
        <!-- /wp:image -->
    </div>
    <!-- /wp:group -->

    <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"0"},"color":{"background":"#ffc000"},"border":{"radius":{"bottomLeft":"20px","bottomRight":"20px"}}},"layout":{"type":"constrained","justifyContent":"left"}} -->
    <div class="wp-block-group has-background"
         style="border-bottom-left-radius:20px;border-bottom-right-radius:20px;background-color:#ffc000;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
        <!-- wp:heading {"textAlign":"left","style":{"typography":{"lineHeight":"1.1"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}},"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}},"textColor":"base","fontSize":"large"} -->
        <h2 class="wp-block-heading has-text-align-left has-base-color has-text-color has-link-color has-large-font-size"
            style="margin-bottom:var(--wp--preset--spacing--40);line-height:1.1">
            <a href="<?php echo esc_url($plantUrl) ?>" data-type="page" data-id="<?php echo $id ?>">
                <?php echo $frenchName ?>
            </a>
        </h2>
        <!-- /wp:heading -->

        <!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base","fontSize":"extra-small"} -->
        <p class="has-base-color has-text-color has-link-color has-extra-small-font-size"><?php echo $conservation_status ?></p>
        <!-- /wp:paragraph -->

        <!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base","fontSize":"extra-small"} -->
        <p class="has-base-color has-text-color has-link-color has-extra-small-font-size"><?php echo $description ?></p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:column -->