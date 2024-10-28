<?php
/*
Plugin Name: CM Gamerecipe
Plugin URI: https://github.com/DiamondStrand/cm-gamerecipe
Description: Ett flexibelt och kraftfullt plugin för att skapa och hantera spelrecept. Med CM Gamerecipe kan du enkelt lägga till spel med detaljerade regler, antal deltagare, material, speltid, och andra spelrelaterade data.
Version: 1.0.16
Author: Diamond Strand - CookifyMedia
Text Domain: cm-gamerecipe
Domain Path: /languages
GitHub Plugin URI: https://github.com/DiamondStrand/cm-gamerecipe
GitHub Branch: main
*/

if (!defined('ABSPATH')) {
    exit; // Förhindra direkt åtkomst
}

// Inkludera nödvändiga filer
require_once plugin_dir_path(__FILE__) . 'includes/class-db-handler.php';         // Databashantering
require_once plugin_dir_path(__FILE__) . 'includes/class-game-handler.php';       // Hantering av speldata
require_once plugin_dir_path(__FILE__) . 'includes/class-game-post-type.php';     // Custom Post Type för "Spel"
require_once plugin_dir_path(__FILE__) . 'includes/class-game-meta-box.php';      // Anpassade fält för "Spel"
require_once plugin_dir_path(__FILE__) . 'includes/class-shortcodes.php';         // Shortcodes för att visa speldata
require_once plugin_dir_path(__FILE__) . 'includes/class-material-admin.php';     // Admin-sida för Material
require_once plugin_dir_path(__FILE__) . 'includes/class-csv-import-handler.php'; // CSV Import

// Aktivera pluginet och skapa materialtabellen
register_activation_hook(__FILE__, array('CM_Gamerecipe_DB_Handler', 'create_material_table'));

// Ladda pluginets textdomän för översättningar
function cm_gamerecipe_load_textdomain()
{
    load_plugin_textdomain('cm-gamerecipe', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'cm_gamerecipe_load_textdomain');

// Ladda JavaScript-fil för media-upload (upload.js)
function cm_gamerecipe_enqueue_admin_scripts()
{
    wp_enqueue_media(); // WordPress inbyggd media-uploader
    wp_enqueue_script('cm-gamerecipe-upload', plugin_dir_url(__FILE__) . 'assets/js/upload.js', array('jquery'), '1.0.2', true);
}
add_action('admin_enqueue_scripts', 'cm_gamerecipe_enqueue_admin_scripts');

// Ladda admin-specifik CSS för meta boxarna
function cm_gamerecipe_admin_styles()
{
    wp_enqueue_style('cm-gamerecipe-admin', plugin_dir_url(__FILE__) . 'assets/css/admin-style.css', array(), '1.0.1');
}
add_action('admin_enqueue_scripts', 'cm_gamerecipe_admin_styles');

// Lägg till standard Markdown-text för nya spel i beskrivningsfältet
function cm_gamerecipe_set_default_content($content, $post)
{
    if ($post->post_type === 'cm_game' && $post->post_status === 'auto-draft') {
        $content = "<h3>Start</h3>
                    Skriv hur spelet startar här.
                    <h3>Spelgång</h3>
                    Beskriv hur spelet spelas.
                    <h3>Slut</h3>
                    Beskriv hur spelet avslutas.
                    <h3>Variationer</h3>
                    Beskriv spelet kan variaeras på något sätt.
                    <h3>Inställningar</h3>
                    Beskriv om spelet har särskilda inställningar eller förberedelser.
                    ";
    }

    return $content;
}
add_filter('default_content', 'cm_gamerecipe_set_default_content', 10, 2);

// Lägg till en menyflik för CSV-import
function cm_gamerecipe_add_admin_menu()
{
    add_menu_page(
        __('Importera spel', 'cm-gamerecipe'),
        __('Importera spel', 'cm-gamerecipe'),
        'manage_options',
        'cm-gamerecipe-import',
        array('CM_Gamerecipe_CSV_Import_Handler', 'display_import_page'),
        'dashicons-upload',
        26
    );
}
add_action('admin_menu', 'cm_gamerecipe_add_admin_menu');

// Ta bort standard "Anpassade fält"-metaboxen från "cm_game"-posttypen
function cm_gamerecipe_remove_custom_fields_metabox()
{
    remove_meta_box('postcustom', 'cm_game', 'normal');
}
add_action('admin_menu', 'cm_gamerecipe_remove_custom_fields_metabox');
