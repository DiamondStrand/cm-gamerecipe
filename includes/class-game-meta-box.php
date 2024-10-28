<?php
class CM_Gamerecipe_Game_Meta_Box
{
    // Registrera meta box för att hantera anpassade fält i adminpanelen
    public static function add_meta_box()
    {
        add_meta_box(
            'cm_gamerecipe_game_details',
            __('Speldetaljer', 'cm-gamerecipe'),
            array('CM_Gamerecipe_Game_Meta_Box', 'render_meta_box'),
            'cm_game',
            'normal',
            'high'
        );
    }

    // Rendera meta boxen i adminpanelen
    public static function render_meta_box($post)
    {
        // Hämta speldata från metadata och säkerställ att de är av rätt typ
        $min_players = get_post_meta($post->ID, 'min_players', true);
        $max_players = get_post_meta($post->ID, 'max_players', true);
        $typical_duration = get_post_meta($post->ID, 'typical_duration', true);
        $game_type = get_post_meta($post->ID, 'game_type', true);
        $materials = maybe_unserialize(get_post_meta($post->ID, 'materials', true)) ?: array();
        $suitable_for = maybe_unserialize(get_post_meta($post->ID, 'suitable_for', true)) ?: array();
        $difficulty = get_post_meta($post->ID, 'difficulty', true);
        $preparation = get_post_meta($post->ID, 'preparation', true);
        $tips = get_post_meta($post->ID, 'tips', true);
        $img_url = get_post_meta($post->ID, 'img_url', true);

        echo '<div class="cm-gamerecipe-meta-box">';
        wp_nonce_field('cm_gamerecipe_save_meta_box_data', 'cm_gamerecipe_meta_box_nonce'); // Nonce-fält

        // Fält för antal spelare
        echo '<label for="cm_gamerecipe_min_players">' . __('Minsta antal spelare', 'cm-gamerecipe') . '</label>';
        echo '<input type="number" id="cm_gamerecipe_min_players" name="cm_gamerecipe_min_players" value="' . esc_attr($min_players) . '" />';

        echo '<label for="cm_gamerecipe_max_players">' . __('Maximala antal spelare', 'cm-gamerecipe') . '</label>';
        echo '<input type="number" id="cm_gamerecipe_max_players" name="cm_gamerecipe_max_players" value="' . esc_attr($max_players) . '" />';

        // Fält för speltid
        echo '<label for="cm_gamerecipe_typical_duration">' . __('Ungefärlig speltid (minuter)', 'cm-gamerecipe') . '</label>';
        echo '<input type="number" id="cm_gamerecipe_typical_duration" name="cm_gamerecipe_typical_duration" value="' . esc_attr($typical_duration) . '" />';

        // Fält för typ av spel
        echo '<label for="cm_gamerecipe_game_type">' . __('Typ av spel', 'cm-gamerecipe') . '</label>';
        echo '<select id="cm_gamerecipe_game_type" name="cm_gamerecipe_game_type">';
        echo '<option value="kortspel" ' . selected($game_type, 'kortspel', false) . '>' . __('Kortspel', 'cm-gamerecipe') . '</option>';
        echo '<option value="brädspel" ' . selected($game_type, 'brädspel', false) . '>' . __('Brädspel', 'cm-gamerecipe') . '</option>';
        echo '<option value="utomhuslek" ' . selected($game_type, 'utomhuslek', false) . '>' . __('Utomhuslek', 'cm-gamerecipe') . '</option>';
        echo '</select>';

        // Hämta tillgängliga material från materialtabellen
        global $wpdb;
        $material_table = $wpdb->prefix . 'cm_gamerecipe_materials';
        $available_materials = $wpdb->get_results("SELECT name FROM $material_table");

        // Fält för material (checkbox-grupp)
        echo '<label>' . __('Material', 'cm-gamerecipe') . '</label>';
        foreach ($available_materials as $material) {
            $checked = in_array($material->name, $materials) ? 'checked' : '';
            echo '<label><input type="checkbox" name="cm_gamerecipe_materials[]" value="' . esc_attr($material->name) . '" ' . $checked . '> ' . ucfirst($material->name) . '</label><br>';
        }

        // Fält för lämplig åldersgrupp (checkbox-grupp)
        $available_groups = array('vuxen', 'ungdom', 'barn');
        echo '<label>' . __('Lämplig åldersgrupp', 'cm-gamerecipe') . '</label>';
        foreach ($available_groups as $group) {
            $checked = in_array($group, (array) $suitable_for) ? 'checked' : ''; // Konvertera $suitable_for till array om tom
            echo '<label><input type="checkbox" name="cm_gamerecipe_suitable_for[]" value="' . esc_attr($group) . '" ' . $checked . '> ' . ucfirst($group) . '</label><br>';
        }

        // Fält för svårighetsgrad
        echo '<label for="cm_gamerecipe_difficulty">' . __('Svårighetsgrad', 'cm-gamerecipe') . '</label>';
        echo '<select id="cm_gamerecipe_difficulty" name="cm_gamerecipe_difficulty">';
        echo '<option value="lätt" ' . selected($difficulty, 'lätt', false) . '>' . __('Lätt', 'cm-gamerecipe') . '</option>';
        echo '<option value="medel" ' . selected($difficulty, 'medel', false) . '>' . __('Medel', 'cm-gamerecipe') . '</option>';
        echo '<option value="avancerat" ' . selected($difficulty, 'avancerat', false) . '>' . __('Avancerat', 'cm-gamerecipe') . '</option>';
        echo '</select>';

        // Fält för förberedelser
        echo '<label for="cm_gamerecipe_preparation">' . __('Förberedelser', 'cm-gamerecipe') . '</label>';
        echo '<select id="cm_gamerecipe_preparation" name="cm_gamerecipe_preparation">';
        echo '<option value="inga" ' . selected($preparation, 'inga', false) . '>' . __('Inga', 'cm-gamerecipe') . '</option>';
        echo '<option value="lite" ' . selected($preparation, 'lite', false) . '>' . __('Lite', 'cm-gamerecipe') . '</option>';
        echo '</select>';

        // Bild-URL med knapp för mediauppladdare
        echo '<label for="cm_gamerecipe_img">' . __('Bild-URL', 'cm-gamerecipe') . '</label>';
        echo '<input type="text" id="cm_gamerecipe_img" name="cm_gamerecipe_img" value="' . esc_url($img_url) . '" />';
        echo '<input type="button" id="cm_gamerecipe_img_button" class="button" value="' . __('Välj bild', 'cm-gamerecipe') . '" />';

        // Fält för tips
        echo '<label for="cm_gamerecipe_tips">' . __('Tips', 'cm-gamerecipe') . '</label>';
        echo '<textarea id="cm_gamerecipe_tips" name="cm_gamerecipe_tips" rows="4" cols="50">' . esc_textarea($tips) . '</textarea>';

        echo '</div>';
    }

    // Spara metadata när spelet sparas
    public static function save_meta_box_data($post_id)
    {
        if (!isset($_POST['cm_gamerecipe_meta_box_nonce']) || !wp_verify_nonce($_POST['cm_gamerecipe_meta_box_nonce'], 'cm_gamerecipe_save_meta_box_data')) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Spara metadata direkt
        update_post_meta($post_id, 'min_players', sanitize_text_field($_POST['cm_gamerecipe_min_players']));
        update_post_meta($post_id, 'max_players', sanitize_text_field($_POST['cm_gamerecipe_max_players']));
        update_post_meta($post_id, 'typical_duration', sanitize_text_field($_POST['cm_gamerecipe_typical_duration']));
        update_post_meta($post_id, 'game_type', sanitize_text_field($_POST['cm_gamerecipe_game_type']));
        update_post_meta($post_id, 'materials', maybe_serialize(array_map('sanitize_text_field', $_POST['cm_gamerecipe_materials'] ?? [])));
        update_post_meta($post_id, 'suitable_for', maybe_serialize(array_map('sanitize_text_field', $_POST['cm_gamerecipe_suitable_for'] ?? [])));
        update_post_meta($post_id, 'difficulty', sanitize_text_field($_POST['cm_gamerecipe_difficulty']));
        update_post_meta($post_id, 'preparation', sanitize_text_field($_POST['cm_gamerecipe_preparation']));
        update_post_meta($post_id, 'tips', sanitize_textarea_field($_POST['cm_gamerecipe_tips']));
        update_post_meta($post_id, 'img_url', esc_url($_POST['cm_gamerecipe_img']));
    }
}

// Registrera meta box
add_action('add_meta_boxes', array('CM_Gamerecipe_Game_Meta_Box', 'add_meta_box'));

// Spara metadata när ett spel sparas
add_action('save_post', array('CM_Gamerecipe_Game_Meta_Box', 'save_meta_box_data'));
