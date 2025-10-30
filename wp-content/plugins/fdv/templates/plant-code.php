<?php

use Fdv\Plugin\FdvTemplate;

if (empty($plant)) {
    return;
}

$frenchName = $plant['french_name'] ?? 'Nom inconnu';
$latinName = $plant['latin_name'] ?? '';
$englishName = $plant['english_name'] ?? '';
$conservation_status = strip_tags($plant['conservation_status']) ?? '';
$description = $plant['description'] ?? '';
$usages = $plant['usages'] ?? '';
$ecological_role = strip_tags($plant['ecological_role']) ?? '';
$habitat = strip_tags($plant['habitat']) ?? '';
$flowering_period = strip_tags($plant['flowering_period']) ?? '';
$fruiting_period = strip_tags($plant['fruiting_period']) ?? '';
$etymology = strip_tags($plant['etymology']) ?? '';
$family = $plant['family'] ?? null;
$type = $plant['type'] ?? null;
$genus = $plant['genus'] ?? null;
$id = (int)$plant['id'] ?? 0;
$photos = $plant['photos'] ?? [];
$imageUrl = FdvTemplate::fdv_get_plant_image_url($plant);
?>
<!-- wp:group {"metadata":{"name":"About Section","categories":["theme"],"patternName":"emerge-preschool/about"},"align":"full","style":{"background":{"backgroundRepeat":"repeat","backgroundSize":"contain"},"spacing":{"padding":{"top":"0","bottom":"0","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"margin":{"top":"var:preset|spacing|80","bottom":"0"}}},"layout":{"type":"constrained","justifyContent":"center"}} -->
<div class="wp-block-group alignfull"
     style="margin-top:var(--wp--preset--spacing--80);margin-bottom:0;padding-top:0;padding-right:var(--wp--preset--spacing--50);padding-bottom:0;padding-left:var(--wp--preset--spacing--50)">
    <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|70"},"padding":{"top":"0","bottom":"0"}}}} -->
    <div class="wp-block-columns" style="padding-top:0;padding-bottom:0">
        <!-- wp:column {"verticalAlignment":"center","width":"55%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"bottom"}} -->
            <div class="wp-block-group"
                 style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
                <!-- wp:image {"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"10px"}}} -->
                <figure class="wp-block-image size-full has-custom-border"><img
                            src="<?php echo esc_url($imageUrl); ?>"
                            alt="" style="border-radius:10px"/></figure>
                <!-- /wp:image --></div>
            <!-- /wp:group --></div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"45%","style":{"spacing":{"blockGap":"0"}}} -->
        <div class="wp-block-column" style="flex-basis:45%">
            <!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"constrained"}} -->
            <div class="wp-block-group">
                <!-- wp:heading {"textAlign":"left","level":6,"style":{"border":{"radius":"100px","width":"0px","style":"none"},"layout":{"selfStretch":"fit","flexSize":null},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"var:preset|spacing|40"}},"color":{"text":"#ffc000"}},"fontSize":"large"} -->
                <h6 class="wp-block-heading has-text-align-left has-text-color has-large-font-size"
                    style="border-style:none;border-width:0px;border-radius:100px;color:#ffc000;margin-bottom:var(--wp--preset--spacing--40);padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
                    <?php echo $latinName; ?> / <?php echo $englishName; ?>
                </h6>
                <!-- /wp:heading -->

                <!-- wp:heading {"textAlign":"left","level":3,"style":{"typography":{"lineHeight":"1.3"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","bottom":"0","left":"0","right":"0"}},"elements":{"link":{"color":{"text":"var:preset|color|custom-heading"}}}},"textColor":"custom-heading","fontSize":"xx-large"} -->
                <h3 class="wp-block-heading has-text-align-left has-custom-heading-color has-text-color has-link-color has-xx-large-font-size"
                    style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;line-height:1.3">
                    <?php echo $frenchName; ?>
                </h3>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|50"}}},"textColor":"custom-body"} -->
                <p class="has-custom-body-color has-text-color"
                   style="margin-top:var(--wp--preset--spacing--60);margin-bottom:var(--wp--preset--spacing--50)">
                    <?php echo $description; ?>
                </p>
                <!-- /wp:paragraph -->

                <!-- wp:paragraph -->
                <p> <?php echo $usages; ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:paragraph -->
                <p> <?php echo $conservation_status; ?></p>
                <!-- /wp:paragraph --></div>
            <!-- /wp:group -->

            <!-- wp:paragraph -->
            <p> <?php echo $ecological_role; ?></p>
            <!-- /wp:paragraph --></div>
        <!-- /wp:column --></div>
    <!-- /wp:columns -->

    <!-- wp:paragraph -->
    <p><strong>Rôle écologique:</strong><?php echo $ecological_role; ?></p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph -->
    <p><strong>Habitat:</strong>: <?php echo $habitat; ?></p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph -->
    <p><strong>Saison de fleurs:</strong> <?php echo $flowering_period; ?></p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph -->
    <p><strong>Saison de fruits:</strong> <?php echo $fruiting_period; ?></p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph -->
    <p>
        <strong>Étymologie:</strong> <?php echo $etymology; ?>
    </p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph -->
    <p><strong>Family</strong>
        <?php echo($family['name']); ?>
    </p>

    <!-- /wp:paragraph -->

    <!-- wp:paragraph -->
    <p><strong>Genus</strong>
        <?php echo($genus['name']); ?>
    </p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph -->
    <p><strong>Type</strong>
        <?php echo($type['name']); ?>
    </p>
    <!-- /wp:paragraph -->
</div>
<!-- /wp:group -->