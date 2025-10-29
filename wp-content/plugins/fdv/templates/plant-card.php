<?php
/**
 * Template for displaying a single plant card
 *
 * Available variables:
 * @var array  $plant   Plant data
 * @var string $bgColor Background color for the card
 */

use Fdv\Plugin\FdvTemplate;

if (empty($plant)) {
    return;
}

$frenchName = esc_html($plant['french_name'] ?? 'Nom inconnu');
$latinName = esc_html($plant['latin_name'] ?? '');
$category = esc_html($plant['category'] ?? '');
$imageUrl = FdvTemplate::fdv_get_plant_image_url($plant);
$plantUrl = FdvTemplate::fdv_get_plant_url($plant);
?>

<!-- wp:column -->
<div class="wp-block-column">
    <!-- wp:group {"style":{"color":{"background":"<?php echo esc_attr($bgColor); ?>"},"border":{"radius":"20px"},"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
    <div class="wp-block-group has-background" style="border-radius:20px;background-color:<?php echo esc_attr($bgColor); ?>;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--40)">

        <!-- Image section -->
        <div class="wp-block-group" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--30)">
            <div class="wp-block-group has-base-background-color has-background" style="border-style:none;border-width:0px;border-radius:100px;margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--30);padding:var(--wp--preset--spacing--50);display:flex;align-items:center;justify-content:center;width:120px;height:120px;margin-left:auto;margin-right:auto;overflow:hidden;">
                <img src="<?php echo esc_url($imageUrl); ?>"
                     alt="<?php echo esc_attr($frenchName); ?>"
                     style="width:100%;height:100%;object-fit:cover;border-radius:50%;" />
            </div>
        </div>

        <!-- Text content -->
        <div class="wp-block-group">
            <h2 class="wp-block-heading has-text-align-center has-base-color has-text-color has-link-color has-large-font-size" style="line-height:1.1">
                <?php echo $frenchName; ?>
            </h2>

            <?php if (!empty($latinName)): ?>
                <p class="has-text-align-center has-base-color has-text-color has-link-color" style="font-style:italic;font-size:0.9em;">
                    <?php echo $latinName; ?>
                </p>
            <?php endif; ?>

            <?php if (!empty($category)): ?>
                <p class="has-text-align-center has-base-color has-text-color has-link-color has-extra-small-font-size">
                    <?php echo sprintf(__('Catégorie : %s', 'fdv'), $category); ?>
                </p>
            <?php endif; ?>

            <?php if (!empty($plantUrl)): ?>
                <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
                <div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
                    <!-- wp:button {"backgroundColor":"base","textColor":"contrast","style":{"border":{"radius":"50px"}}} -->
                    <div class="wp-block-button">
                        <a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background wp-element-button"
                           href="<?php echo esc_url($plantUrl); ?>"
                           style="border-radius:50px;padding:0.75rem 1.5rem;">
                            <?php esc_html_e('Voir les détails', 'fdv'); ?>
                        </a>
                    </div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            <?php endif; ?>
        </div>

    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:column -->