<?php
/*
Plugin Name: CM Gamerecipe
Plugin URI: https://github.com/DiamondStrand/cm-gamerecipe
Description: Ett flexibelt och kraftfullt plugin för att skapa och hantera spelrecept. Med CM Gamerecipe kan du enkelt lägga till spel med detaljerade regler, antal deltagare, material, speltid, och andra spelrelaterade data.
Version: 1.0.10
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
require_once plugin_dir_path(__FILE__) . 'includes/class-shortcodes.php';         // Shortcodes för speldata

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

// Lägga till en ny menyflik för CSV-import i adminpanelen
function cm_gamerecipe_add_admin_menu()
{
    add_menu_page(
        __('Importera spel', 'cm-gamerecipe'),
        __('Importera spel', 'cm-gamerecipe'),
        'manage_options',
        'cm-gamerecipe-import',
        'cm_gamerecipe_import_page',
        'dashicons-upload',
        26
    );
}
add_action('admin_menu', 'cm_gamerecipe_add_admin_menu');

// Sidlayout för import av CSV-fil
function cm_gamerecipe_import_page()
{
?>
    <div class="wrap">
        <h1><?php _e('Importera spel från CSV', 'cm-gamerecipe'); ?></h1>
        <p><?php _e('CSV-filen bör innehålla följande kolumner i denna ordning: Titel, Minsta spelare, Maximala spelare, Speltid, Material, Passar för, Svårighetsgrad, Förberedelser, Tips.', 'cm-gamerecipe'); ?></p>
        <form method="post" enctype="multipart/form-data" action="">
            <input type="file" name="csv_file" accept=".csv" required>
            <?php submit_button(__('Importera', 'cm-gamerecipe')); ?>
        </form>
    </div>
<?php

    // Hantera CSV-filuppladdning
    if (isset($_FILES['csv_file']) && !empty($_FILES['csv_file']['tmp_name'])) {
        cm_gamerecipe_handle_csv_upload($_FILES['csv_file']);
    }
}

function cm_gamerecipe_handle_csv_upload($file)
{
    // Öppna CSV-filen
    if (($handle = fopen($file['tmp_name'], 'r')) !== false) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cm_gamerecipe_games'; // Variabel för din anpassade tabell

        $successful_imports = 0;
        $failed_imports = 0;

        // Läs CSV-filen, hoppa över första raden (rubriker)
        $is_first_row = true;
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            // Hoppa över rubrikraden
            if ($is_first_row) {
                $is_first_row = false;
                continue;
            }

            // Kontrollera att rätt antal kolumner finns i varje rad
            if (count($data) < 9) {
                $failed_imports++;
                continue;
            }

            // Hämta och sanera CSV-data
            $title = sanitize_text_field($data[0]);
            $min_players = intval($data[1]);
            $max_players = intval($data[2]);
            $typical_duration = intval($data[3]);
            $materials = maybe_serialize(explode(',', sanitize_text_field($data[4])));
            $suitable_for = maybe_serialize(explode(',', sanitize_text_field($data[5])));
            $difficulty = sanitize_text_field($data[6]);
            $preparation = sanitize_text_field($data[7]);
            $tips = sanitize_textarea_field($data[8]);

            // Skapa ett nytt inlägg (CPT)
            $post_id = wp_insert_post(array(
                'post_title' => $title,
                'post_type' => 'cm_game',
                'post_status' => 'publish',
            ));

            // Kontrollera om inlägget skapades korrekt
            if ($post_id) {
                $successful_imports++;

                // Spara spelets metadata i den anpassade databastabellen
                $wpdb->insert(
                    $table_name, // Användning av $table_name här
                    array(
                        'post_id' => $post_id,
                        'min_players' => $min_players,
                        'max_players' => $max_players,
                        'typical_duration' => $typical_duration,
                        'materials' => $materials,
                        'suitable_for' => $suitable_for,
                        'difficulty' => $difficulty,
                        'preparation' => $preparation,
                        'tips' => $tips,
                        'img_url' => '', // IMG-url hanteras inte här men kan läggas till
                        'created_at' => current_time('mysql'),
                        'updated_at' => current_time('mysql')
                    ),
                    array('%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
                );
            } else {
                $failed_imports++;
            }
        }
        fclose($handle);

        // Visa resultat av importen
        echo '<div class="updated notice"><p>' . sprintf(__('%d spel importerades framgångsrikt. %d rader misslyckades.', 'cm-gamerecipe'), $successful_imports, $failed_imports) . '</p></div>';
    } else {
        echo '<div class="error notice"><p>' . __('Fel vid uppladdning av CSV-filen.', 'cm-gamerecipe') . '</p></div>';
    }
}
