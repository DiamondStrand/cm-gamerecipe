<?php

class CM_Gamerecipe_CSV_Import_Handler
{
    private $missing_materials = [];

    // Visa sidlayout för import av CSV-fil
    public static function display_import_page()
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

        if (isset($_FILES['csv_file']) && !empty($_FILES['csv_file']['tmp_name'])) {
            self::handle_csv_upload($_FILES['csv_file']);
        }
    }

    // Hantera CSV-filuppladdning och validering
    private static function handle_csv_upload($file)
    {
        if (($handle = fopen($file['tmp_name'], 'r')) !== false) {
            global $wpdb;
            $material_table = $wpdb->prefix . 'cm_gamerecipe_materials';

            // Hämta alla tillgängliga material från databasen
            $available_materials = $wpdb->get_col("SELECT name FROM $material_table");

            $successful_imports = 0;
            $failed_imports = 0;
            $is_first_row = true;
            $missing_materials = []; // Lista för att spara saknade material

            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if ($is_first_row) {
                    $is_first_row = false;
                    continue;
                }
                if (count($data) < 9) {
                    $failed_imports++;
                    continue;
                }

                $title = sanitize_text_field($data[0]);
                $min_players = intval($data[1]);
                $max_players = intval($data[2]);
                $typical_duration = intval($data[3]);
                $materials = explode(',', sanitize_text_field($data[4]));
                $suitable_for = explode(',', sanitize_text_field($data[5]));
                $difficulty = sanitize_text_field($data[6]);
                $preparation = sanitize_text_field($data[7]);
                $tips = sanitize_textarea_field($data[8]);

                // Validera material
                $valid_materials = [];
                foreach ($materials as $material) {
                    $material = trim($material); // Ta bort extra mellanslag
                    if (in_array($material, $available_materials)) {
                        $valid_materials[] = $material;
                    } else {
                        $missing_materials[] = $material; // Lägg till i saknade material
                    }
                }

                // Skapa ett nytt inlägg (CPT)
                $post_id = wp_insert_post(array(
                    'post_title' => $title,
                    'post_type' => 'cm_game',
                    'post_status' => 'publish',
                ));

                if ($post_id) {
                    $successful_imports++;
                    update_post_meta($post_id, 'min_players', $min_players);
                    update_post_meta($post_id, 'max_players', $max_players);
                    update_post_meta($post_id, 'typical_duration', $typical_duration);
                    update_post_meta($post_id, 'materials', maybe_serialize($valid_materials)); // Spara endast giltiga material
                    update_post_meta($post_id, 'suitable_for', maybe_serialize($suitable_for));
                    update_post_meta($post_id, 'difficulty', $difficulty);
                    update_post_meta($post_id, 'preparation', $preparation);
                    update_post_meta($post_id, 'tips', $tips);
                } else {
                    $failed_imports++;
                }
            }
            fclose($handle);

            // Visa resultat av importen med eventuella saknade material
            echo '<div class="updated notice"><p>' . sprintf(__('%d spel importerades framgångsrikt. %d rader misslyckades.', 'cm-gamerecipe'), $successful_imports, $failed_imports) . '</p></div>';

            if (!empty($missing_materials)) {
                $unique_missing_materials = array_unique($missing_materials);
                echo '<div class="error notice"><p>' . __('Följande material fanns inte i materiallistan och importerades inte:', 'cm-gamerecipe') . ' ' . implode(', ', $unique_missing_materials) . '</p></div>';
            }
        } else {
            echo '<div class="error notice"><p>' . __('Fel vid uppladdning av CSV-filen.', 'cm-gamerecipe') . '</p></div>';
        }
    }
}
