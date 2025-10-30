<?php

namespace Fdv\Plugin;

class FdvTemplate
{
    /**
     * Load a template file with variables
     *
     * @param string $template_name Template file name (relative to templates/)
     * @param array $args Variables to pass to template
     * @param bool $echo Whether to echo or return output
     * @return string|null
     */
    public static function fdv_get_template(string $template_name, array $args = [], bool $echo = true): ?string
    {
        $template_path = FDV_PLUGIN_DIR.'templates/'.$template_name;

        // Allow themes to override plugin templates
        $theme_template = locate_template([
            'fdv/'.$template_name,
            'fdv-templates/'.$template_name,
        ]);

        // Use theme template if found, otherwise use plugin template
        $template_file = $theme_template ?: $template_path;

        if (!file_exists($template_file)) {
            error_log('FDV Plugin: Template file not found - '.$template_file);

            return '';
        }

        // Extract variables to make them available in template
        extract($args); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

        if ($echo) {
            include $template_file;
            return null;
        } else {
            ob_start();
            include $template_file;

            return ob_get_clean();
        }
    }

    /**
     * Get plant image URL - use first photo if available, otherwise use placeholder
     *
     * @param array $plant Plant data
     * @return string Image URL
     */
    public static function fdv_get_plant_image_url(array $plant): string
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

    /**
     * Get plant single page URL
     *
     * @param array $plant Plant data
     * @return string Plant URL
     */
    public static function fdv_get_plant_url(array $plant): string
    {
        // Get plant code/slug from various possible fields
        $plantCode = $plant['code'] ?? $plant['slug'] ?? $plant['id'] ?? '';

        if (empty($plantCode)) {
            return '';
        }

        // Build URL using the route from FdvRouter
        return home_url('/'.FdvRouter::ROUTE.'/'.$plantCode);
    }
}