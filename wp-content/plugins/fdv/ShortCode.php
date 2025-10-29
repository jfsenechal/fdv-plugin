<?php

namespace Fdv\Plugin;

class ShortCode
{
    public function __construct()
    {
        add_shortcode('lesplantes', [$this, 'lesplantes']);
    }

    public function lesplantes(): string
    {
        $plants = FdvRepository::getPlants();

        // If no plants data, show appropriate message
        if (empty($plants)) {
            // In debug mode, show error message
            if (defined('WP_DEBUG') && WP_DEBUG) {
                return '<div class="fdv-plants-error"><p>Unable to load plants data. Check error logs for details.</p></div>';
            }

            // In production, show user-friendly message
            return '<div class="fdv-plants-empty"><p>No plants available at the moment.</p></div>';
        }

        return $this->renderPlantsGrid($plants['data']);
    }

    /**
     * Render plants in a grid layout using theme's styling patterns
     *
     * @param array $plants
     * @return string
     */
    private function renderPlantsGrid(array $plants): string
    {
        // Define color palette from emerge-preschool theme
        $colors = ['#ff6666', '#ffc000', '#abcd52', '#1ab9ff', '#92278f'];

        $output = '<!-- wp:group {"metadata":{"name":"Plants Section"},"style":{"spacing":{"blockGap":"var:preset|spacing|70","padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"0","right":"0"},"margin":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->';
        $output .= '<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--80);margin-bottom:var(--wp--preset--spacing--80);padding-top:var(--wp--preset--spacing--50);padding-right:0;padding-bottom:var(--wp--preset--spacing--50);padding-left:0">';

        // Header section
        $output .= '<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20","margin":{"top":"0","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->';
        $output .= '<div class="wp-block-group" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--70)">';
        $output .= '<h6 class="wp-block-heading has-text-align-center has-text-color has-large-font-size" style="border-style:none;border-width:0px;color:#ffc000;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">'.esc_html__('Nos Plantes', 'fdv').'</h6>';
        $output .= '<h3 class="wp-block-heading has-text-align-center has-xx-large-font-size" style="padding-top:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--40);line-height:1.1">'.esc_html__('Découvrez Notre Collection', 'fdv').'</h3>';
        $output .= '</div></div>';

        // Grid container - split into rows of 4
        $chunks = array_chunk($plants, 4);

        foreach ($chunks as $chunk) {
            $output .= '<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->';
            $output .= '<div class="wp-block-columns">';

            foreach ($chunk as $index => $plant) {
                $color = $colors[$index % count($colors)];
                $output .= $this->renderPlantCard($plant, $color);
            }

            $output .= '</div><!-- /wp:columns -->';
        }

        $output .= '</div><!-- /wp:group -->';

        return $output;
    }

    /**
     * Render a single plant card
     *
     * @param array $plant
     * @param string $bgColor
     * @return string
     */
    private function renderPlantCard(array $plant, string $bgColor): string
    {
        $frenchName = esc_html($plant['french_name'] ?? 'Nom inconnu');
        $latinName = esc_html($plant['latin_name'] ?? '');
        $category = esc_html($plant['category'] ?? '');
        $imageUrl = $this->getPlantImageUrl($plant);

        $output = '<!-- wp:column -->';
        $output .= '<div class="wp-block-column">';
        $output .= '<!-- wp:group {"style":{"color":{"background":"'.$bgColor.'"},"border":{"radius":"20px"},"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->';
        $output .= '<div class="wp-block-group has-background" style="border-radius:20px;background-color:'.$bgColor.';padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--40)">';

        // Image section - always show an image (real or placeholder)
        $output .= '<div class="wp-block-group" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--30)">';
        $output .= '<div class="wp-block-group has-base-background-color has-background" style="border-style:none;border-width:0px;border-radius:100px;margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--30);padding:var(--wp--preset--spacing--50);display:flex;align-items:center;justify-content:center;width:120px;height:120px;margin-left:auto;margin-right:auto;overflow:hidden;">';
        $output .= '<img src="'.esc_url($imageUrl).'" alt="'.esc_attr($frenchName).'" style="width:100%;height:100%;object-fit:cover;border-radius:50%;"/>';
        $output .= '</div></div>';

        // Text content
        $output .= '<div class="wp-block-group">';
        $output .= '<h2 class="wp-block-heading has-text-align-center has-base-color has-text-color has-link-color has-large-font-size" style="line-height:1.1">'.$frenchName.'</h2>';

        if (!empty($latinName)) {
            $output .= '<p class="has-text-align-center has-base-color has-text-color has-link-color" style="font-style:italic;font-size:0.9em;">'.$latinName.'</p>';
        }

        if (!empty($category)) {
            $output .= '<p class="has-text-align-center has-base-color has-text-color has-link-color has-extra-small-font-size">'.sprintf(__('Catégorie : %s', 'fdv'), $category).'</p>';
        }

        $output .= '</div>';
        $output .= '</div><!-- /wp:group -->';
        $output .= '</div><!-- /wp:column -->';

        return $output;
    }

    /**
     * Get plant image URL - use first photo if available, otherwise use placeholder
     *
     * @param array $plant
     * @return string
     */
    private function getPlantImageUrl(array $plant): string
    {
        // Check if plant has photos array
        if (!empty($plant['photos']) && is_array($plant['photos'])) {
            $firstPhoto = reset($plant['photos']);

            // Check for different possible image field names
            if (!empty($firstPhoto['url'])) {
                return $firstPhoto['url'];
            }
            if (!empty($firstPhoto['image_url'])) {
                return $firstPhoto['image_url'];
            }
            if (!empty($firstPhoto['path'])) {
                return $firstPhoto['path'];
            }
            if (is_string($firstPhoto)) {
                return $firstPhoto;
            }
        }

        // Check for direct image_url field
        if (!empty($plant['image_url'])) {
            return $plant['image_url'];
        }

        // Check for direct image field
        if (!empty($plant['image'])) {
            return $plant['image'];
        }

        // Fallback to placeholder service
        // Using Lorem Picsum with plant ID as seed for consistent images
        $plantId = $plant['id'] ?? rand(1, 1000);
        return 'https://picsum.photos/seed/plant-'.$plantId.'/300/300';
    }
}