# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a WordPress 6.8.3 installation for the Fond des Vaulx website. The site uses the **emerge-preschool** Full Site Editing (FSE) theme, designed for educational/preschool websites.

**Primary Development Focus**: Custom plugin development in `wp-content/plugins/fdv/`. The theme should NOT be modified.

## System Requirements

- **PHP**: 8.4.14 (with Xdebug 3.4.5 and Zend OPcache)
- **WordPress**: 6.8.3
- **Database**: fonddesvaulx_site (MySQL/MariaDB)
- **WP-CLI**: Available at `/usr/local/bin/wp`

## Key Configuration

Database configuration and authentication keys are stored in `wp-config.php`:
- Database: `fonddesvaulx_site`
- Debug mode: `WP_DEBUG` is set to `true`
- Post revisions disabled: `WP_POST_REVISIONS = false`
- Autosave interval: 120 seconds (default is 60)
- Table prefix: `wp_`

## Directory Structure

```
/var/www/fonddesvaulx/
├── wp-admin/               # WordPress admin interface
├── wp-includes/            # WordPress core files
├── wp-content/
│   ├── themes/
│   │   ├── emerge-preschool/    # Active FSE theme
│   │   ├── twentytwentyfour/    # Default theme
│   │   └── twentytwentyfive/    # Default theme
│   ├── plugins/
│   │   ├── fdv/                 # Custom plugin (currently empty)
│   │   ├── ga-google-analytics/ # Google Analytics plugin
│   │   └── google-sitemap-generator/
│   └── uploads/                 # Media files organized by year/month
├── wp-config.php           # WordPress configuration
└── .idea/                  # PhpStorm/IntelliJ IDE configuration
```

## Active Theme: emerge-preschool

The site uses the **emerge-preschool** Full Site Editing (FSE) theme. This is a third-party theme and should NOT be modified. All customizations should be implemented in the **fdv** plugin.

Theme location: `wp-content/themes/emerge-preschool/`

## Custom Plugin: fdv (PRIMARY DEVELOPMENT AREA)

**Location**: `wp-content/plugins/fdv/`
**API URL**: `http://localhost:8000/api/plants/list`

This is the primary area for all custom development. All site-specific functionality should be implemented here.

### API Configuration

The plugin connects to a local API service:
- **Base API URL**: `http://localhost:8000/api`
- **Plants endpoint**: `/plants/list`
- **Full URL**: `http://localhost:8000/api/plants/list`

The API URL is configured via the `FDV_API_URL` environment variable, which should be set to `http://localhost:8000/api`.

### Plugin Structure (Current)

```
wp-content/plugins/fdv/
├── FdvPlugin.php              # Main plugin file (plugin header and initialization)
├── FdvRepository.php          # API data repository
├── FdvRouter.php              # Router (if applicable)
├── ShortCode.php              # Shortcode handler
├── includes/
│   └── template-functions.php # Template helper functions
└── templates/                 # Template files
    ├── README.md              # Template documentation
    ├── plants-grid.php        # Plants grid layout
    └── plant-card.php         # Individual plant card
```

### Template System

The plugin uses a WordPress-style template system that separates logic from presentation:

**Loading Templates:**
```php
fdv_get_template('plants-grid.php', [
    'plants' => $plants_data
]);
```

**Template Hierarchy** (checked in order):
1. `wp-content/themes/emerge-preschool/fdv/[template].php` (theme override)
2. `wp-content/themes/emerge-preschool/fdv-templates/[template].php` (theme override)
3. `wp-content/plugins/fdv/templates/[template].php` (default)

**Helper Functions:**
- `FdvTemplate::fdv_get_template($template_name, $args, $echo)` - Load template with variables
- `FdvTemplate::fdv_get_plant_image_url($plant)` - Get plant image URL or placeholder
- `FdvTemplate::fdv_get_plant_url($plant)` - Get URL for single plant page

### URL Routing System

The plugin implements custom URL routing for single plant pages via `FdvRouter`:

**URL Pattern:** `/lesplantes/{plant-code}`

**Example:** `https://fonddesvaulx.be/lesplantes/rose-123`

**Query Variables:**
- `single_plant` - Flag to indicate single plant page (value: 1)
- `codePlant` - Plant code/slug/ID from the URL

**Template Resolution:**
1. Checks for `single_plant.php` in active theme directory
2. Falls back to default template if not found

**Accessing Plant Code in Template:**
```php
$plantCode = get_query_var('codePlant');
// Use $plantCode to fetch plant data from API
```

**Important:** After modifying routing rules, flush rewrite rules:
```bash
wp rewrite flush
```

### Plugin File Header (Required)

The main `fdv.php` file must start with a plugin header:

```php
<?php
/**
 * Plugin Name: Fond des Vaulx
 * Plugin URI: https://fonddesvaulx.be
 * Description: Custom functionality for the Fond des Vaulx website
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: fdv
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'FDV_VERSION', '1.0.0' );
define( 'FDV_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'FDV_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
```

### Common Plugin Development Patterns

#### 1. Custom Post Types

```php
function fdv_register_post_types() {
    register_post_type( 'custom_type', array(
        'labels' => array(
            'name' => __( 'Custom Types', 'fdv' ),
            'singular_name' => __( 'Custom Type', 'fdv' )
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true, // Enable Gutenberg editor
        'supports' => array( 'title', 'editor', 'thumbnail' ),
    ));
}
add_action( 'init', 'fdv_register_post_types' );
```

#### 2. Custom Taxonomies

```php
function fdv_register_taxonomies() {
    register_taxonomy( 'custom_category', 'custom_type', array(
        'labels' => array(
            'name' => __( 'Categories', 'fdv' ),
            'singular_name' => __( 'Category', 'fdv' )
        ),
        'hierarchical' => true,
        'show_in_rest' => true,
    ));
}
add_action( 'init', 'fdv_register_taxonomies' );
```

#### 3. Enqueuing Scripts and Styles

```php
function fdv_enqueue_scripts() {
    wp_enqueue_style( 'fdv-style', FDV_PLUGIN_URL . 'public/css/fdv-public.css', array(), FDV_VERSION );
    wp_enqueue_script( 'fdv-script', FDV_PLUGIN_URL . 'public/js/fdv-public.js', array( 'jquery' ), FDV_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'fdv_enqueue_scripts' );
```

#### 4. Admin Settings Page

```php
function fdv_add_admin_menu() {
    add_menu_page(
        'FDV Settings',           // Page title
        'FDV Settings',           // Menu title
        'manage_options',         // Capability
        'fdv-settings',           // Menu slug
        'fdv_settings_page',      // Callback function
        'dashicons-admin-generic', // Icon
        100                       // Position
    );
}
add_action( 'admin_menu', 'fdv_add_admin_menu' );

function fdv_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <!-- Settings form here -->
    </div>
    <?php
}
```

#### 5. Custom Gutenberg Blocks

For creating custom blocks, use the `@wordpress/create-block` package or register blocks manually:

```php
function fdv_register_blocks() {
    register_block_type( FDV_PLUGIN_DIR . 'blocks/custom-block' );
}
add_action( 'init', 'fdv_register_blocks' );
```

### Plugin Activation/Deactivation Hooks

```php
// In fdv.php
register_activation_hook( __FILE__, 'fdv_activate' );
register_deactivation_hook( __FILE__, 'fdv_deactivate' );

function fdv_activate() {
    // Run on plugin activation
    // Create database tables, set default options, etc.
    flush_rewrite_rules();
}

function fdv_deactivate() {
    // Run on plugin deactivation
    flush_rewrite_rules();
}
```

## Common Development Commands

### WordPress CLI (WP-CLI)

All commands should be run from `/var/www/fonddesvaulx/`:

```bash
# Check WordPress status and info
wp core version
wp core check-update

# Plugin management
wp plugin list
wp plugin activate [plugin-name]
wp plugin deactivate [plugin-name]

# Theme management
wp theme list
wp theme activate [theme-name]

# Database operations
wp db query "SELECT * FROM wp_posts LIMIT 5"
wp db export
wp db import [file.sql]

# Cache management
wp cache flush
wp transient delete --all

# User management
wp user list
wp user create [username] [email]

# Post/Page management
wp post list
wp post create --post_type=page --post_title='New Page' --post_status=publish
```

### Theme Development

```bash
# Check for theme errors
wp theme status emerge-preschool

# Clear all caches after theme changes
wp cache flush
wp transient delete --all
```

### Debugging

When debugging is needed:
1. `WP_DEBUG` is already enabled in `wp-config.php`
2. Debug logs are typically in `wp-content/debug.log`
3. Use `error_log()` for custom logging

To enable additional debugging options in `wp-config.php`:
```php
define( 'WP_DEBUG_LOG', true );     // Log to wp-content/debug.log
define( 'WP_DEBUG_DISPLAY', false ); // Don't display errors on page
define( 'SCRIPT_DEBUG', true );      // Use non-minified versions of core JS/CSS
```

## Architecture Notes

### Full Site Editing (FSE) Theme Architecture

The emerge-preschool theme follows the FSE paradigm:

1. **Block-Based**: Everything is built using WordPress blocks
2. **theme.json**: Central configuration file defining colors, typography, spacing, and layout settings
3. **HTML Templates**: Template files use `.html` extension and contain block markup
4. **Template Parts**: Reusable components stored in `parts/`
5. **Block Patterns**: Pre-designed block layouts in `patterns/`

### WordPress Hook System

The theme and plugins extend WordPress through hooks:
- **Actions**: Execute code at specific points (registered with `add_action()`)
- **Filters**: Modify data (registered with `add_filter()`)

Example from `functions.php:44`:
```php
add_action( 'after_setup_theme', 'emerge_preschool_setup' );
```

### Block Registration Flow

1. Block styles defined in `inc/register-block-styles.php` register UI options
2. CSS for blocks in `assets/css/blocks/` provides styling
3. Blocks are enqueued in `functions.php` using `wp_enqueue_block_style()`

### Template Hierarchy

WordPress FSE uses this template hierarchy (from specific to general):
1. Custom templates from `templates/` directory
2. Template parts from `parts/` directory
3. Default WordPress templates

Available templates in this theme:
- `404.html` - Not found page
- `archive.html` - Archive pages
- `single.html` - Single posts
- `page.html` - Pages
- `home.html` - Blog home
- `search.html` - Search results
- `sidebar-page-template.html` - Page with sidebar
- `sidebar-post-template.html` - Post with sidebar

## Important Paths

- WordPress root: `/var/www/fonddesvaulx/`
- Active theme: `/var/www/fonddesvaulx/wp-content/themes/emerge-preschool/`
- Plugins: `/var/www/fonddesvaulx/wp-content/plugins/`
- Uploads: `/var/www/fonddesvaulx/wp-content/uploads/`
- Config: `/var/www/fonddesvaulx/wp-config.php`

## Testing

There are no automated tests currently configured for this project. When modifying:

1. **Theme changes**: Test in WordPress block editor and front-end
2. **Plugin changes**: Verify activation/deactivation works via WP-CLI or admin
3. **Database changes**: Always backup first with `wp db export`

## Git Configuration

This is not currently a git repository. To initialize version control:

```bash
cd /var/www/fonddesvaulx
git init
# Add appropriate .gitignore for WordPress projects
```

Important: Never commit `wp-config.php` as it contains sensitive database credentials.