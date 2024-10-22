<?php
/*
Plugin Name: CM Gamerecipe
Plugin URI: https://github.com/DiamondStrand/cm-gamerecipe
Description: Ett flexibelt och kraftfullt plugin för att skapa och hantera spelrecept. Med CM Gamerecipe kan du enkelt lägga till spel med detaljerade regler, antal deltagare, material, speltid, och andra spelrelaterade data.
Version: 1.0.4
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

// Aktivera pluginet och skapa databastabellen
register_activation_hook(__FILE__, array('CM_Gamerecipe_DB_Handler', 'create_db_tables'));

// Avaktiveringshook för att rensa regler och tabeller
register_deactivation_hook(__FILE__, array('CM_Gamerecipe_DB_Handler', 'deactivate_plugin'));

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
    // Kontrollera om det är ett nytt spel
    if ($post->post_type === 'cm_game' && $post->post_status === 'auto-draft') {
        // Här är den förinställda Markdown-texten (mallen)
        $content = "### Start\n\nSkriv hur spelet startar här.\n\n### Spelgång\n\nBeskriv hur spelet spelas.\n\n### Slut\n\nBeskriv hur spelet avslutas.";
    }

    return $content;
}
add_filter('default_content', 'cm_gamerecipe_set_default_content', 10, 2);

// Funktion för att skapa shortcode för PDF-länk
function cm_gamerecipe_img_shortcode($atts)
{
    // Hämta nuvarande post-ID (måste vara inom en loop)
    $post_id = get_the_ID();

    // Hämta PDF-url från metadata
    $img_url = get_post_meta($post_id, 'cm_gamerecipe_pdf', true);

    // Om det finns en PDF, skapa nedladdningslänk
    if ($img_url) {
        return '<a href="' . esc_url($img_url) . '" target="_blank" class="cm-gamerecipe-img-download">Ladda ner spelplan/regelverk (IMG)</a>';
    } else {
        // Inget PDF tillgängligt, returnera tomt
        return '';
    }
}

// Registrera shortcoden
add_shortcode('cm_gamerecipe_pdf_download', 'cm_gamerecipe_pdf_shortcode');
