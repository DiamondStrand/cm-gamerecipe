<?php
class CM_Gamerecipe_Game_Meta_Box
{
    // Registrera meta box för att hantera anpassade fält i adminpanelen
    public static function add_meta_box()
    {
        add_meta_box(
            'cm_gamerecipe_game_details',          // Unikt ID för metaboxen
            __('Speldetaljer', 'cm-gamerecipe'),   // Titel på metaboxen
            array('CM_Gamerecipe_Game_Meta_Box', 'render_meta_box'), // Callback-funktion som renderar metaboxen
            'cm_game',                            // Posttypen där metaboxen ska visas (spel)
            'normal',                             // Placering (normal)
            'high'                                // Prioritet (high)
        );
    }

    // Rendera meta boxen i adminpanelen
    public static function render_meta_box($post)
    {
        // Hämta speldata från databasen
        $game_data = CM_Gamerecipe_Game_Handler::get_game_data($post->ID);


        // Förifyll fälten med befintlig data om den finns
        $min_players = $game_data ? esc_attr($game_data->min_players) : '';
        $max_players = $game_data ? esc_attr($game_data->max_players) : '';
        $typical_duration = $game_data ? esc_attr($game_data->typical_duration) : '';
        $game_type = $game_data ? esc_attr($game_data->game_type) : '';
        $materials = $game_data ? maybe_unserialize($game_data->materials) : array();
        $suitable_for = $game_data ? maybe_unserialize($game_data->suitable_for) : array();
        $difficulty = $game_data ? esc_attr($game_data->difficulty) : '';
        $preparation = $game_data ? esc_attr($game_data->preparation) : '';
        $tips = $game_data ? esc_textarea($game_data->tips) : '';
        $img_url = isset($game_data->img_url) ? esc_url($game_data->img_url) : '';

        // Rendera fält i adminpanelen
        echo '<div class="cm-gamerecipe-meta-box">';
        wp_nonce_field('cm_gamerecipe_save_meta_box_data', 'cm_gamerecipe_meta_box_nonce'); // Lägg till nonce-fält

        // Fält för antal spelare
        echo '<label for="cm_gamerecipe_min_players">' . __('Minsta antal spelare', 'cm-gamerecipe') . '</label>';
        echo '<input type="number" id="cm_gamerecipe_min_players" name="cm_gamerecipe_min_players" value="' . $min_players . '" />';

        echo '<label for="cm_gamerecipe_max_players">' . __('Maximala antal spelare', 'cm-gamerecipe') . '</label>';
        echo '<input type="number" id="cm_gamerecipe_max_players" name="cm_gamerecipe_max_players" value="' . $max_players . '" />';

        // Fält för speltid
        echo '<label for="cm_gamerecipe_typical_duration">' . __('Ungefärlig speltid (minuter)', 'cm-gamerecipe') . '</label>';
        echo '<input type="number" id="cm_gamerecipe_typical_duration" name="cm_gamerecipe_typical_duration" value="' . $typical_duration . '" />';

        // Fält för typ av spel
        echo '<label for="cm_gamerecipe_game_type">' . __('Typ av spel', 'cm-gamerecipe') . '</label>';
        echo '<select id="cm_gamerecipe_game_type" name="cm_gamerecipe_game_type">';
        echo '<option value="kortspel" ' . selected($game_type, 'kortspel', false) . '>' . __('Kortspel', 'cm-gamerecipe') . '</option>';
        echo '<option value="brädspel" ' . selected($game_type, 'brädspel', false) . '>' . __('Brädspel', 'cm-gamerecipe') . '</option>';
        echo '<option value="utomhuslek" ' . selected($game_type, 'utomhuslek', false) . '>' . __('Utomhuslek', 'cm-gamerecipe') . '</option>';
        echo '</select>';

        // Fält för material (checkbox-grupp)
        $available_materials = array('penna', 'papper', 'kortlek', 'tärningar', 'tidtagarur');
        echo '<label>' . __('Material', 'cm-gamerecipe') . '</label>';
        foreach ($available_materials as $material) {
            $checked = in_array($material, $materials) ? 'checked' : '';
            echo '<label><input type="checkbox" name="cm_gamerecipe_materials[]" value="' . esc_attr($material) . '" ' . $checked . '> ' . ucfirst($material) . '</label><br>';
        }

        // Fält för lämplig åldersgrupp (checkbox-grupp)
        $available_groups = array('vuxen', 'ungdom', 'barn');
        echo '<label>' . __('Lämplig åldersgrupp', 'cm-gamerecipe') . '</label>';
        foreach ($available_groups as $group) {
            $checked = in_array($group, $suitable_for) ? 'checked' : '';
            echo '<label><input type="checkbox" name="cm_gamerecipe_suitable_for[]" value="' . esc_attr($group) . '" ' . $checked . '> ' . ucfirst($group) . '</label><br>';
        }

        // Fält för svårighetsgrad (dropdown)
        echo '<label for="cm_gamerecipe_difficulty">' . __('Svårighetsgrad', 'cm-gamerecipe') . '</label>';
        echo '<select id="cm_gamerecipe_difficulty" name="cm_gamerecipe_difficulty">';
        echo '<option value="lätt" ' . selected($difficulty, 'lätt', false) . '>' . __('Lätt', 'cm-gamerecipe') . '</option>';
        echo '<option value="medel" ' . selected($difficulty, 'medel', false) . '>' . __('Medel', 'cm-gamerecipe') . '</option>';
        echo '<option value="avancerat" ' . selected($difficulty, 'avancerat', false) . '>' . __('Avancerat', 'cm-gamerecipe') . '</option>';
        echo '</select>';

        // Fält för förberedelser (dropdown)
        echo '<label for="cm_gamerecipe_preparation">' . __('Förberedelser', 'cm-gamerecipe') . '</label>';
        echo '<select id="cm_gamerecipe_preparation" name="cm_gamerecipe_preparation">';
        echo '<option value="inga" ' . selected($preparation, 'inga', false) . '>' . __('Inga', 'cm-gamerecipe') . '</option>';
        echo '<option value="lite" ' . selected($preparation, 'lite', false) . '>' . __('Lite', 'cm-gamerecipe') . '</option>';
        echo '<option value="vissa" ' . selected($preparation, 'vissa', false) . '>' . __('Vissa', 'cm-gamerecipe') . '</option>';
        echo '<option value="en_del" ' . selected($preparation, 'en_del', false) . '>' . __('En del', 'cm-gamerecipe') . '</option>';
        echo '<option value="mycket" ' . selected($preparation, 'mycket', false) . '>' . __('Mycket', 'cm-gamerecipe') . '</option>';
        echo '</select>';

        // Fält för bilduppladdning
        $img_url = isset($game_data->img_url) ? esc_url($game_data->img_url) : '';
        if (!empty($img_url)) {
            echo '<img src="' . esc_url($img_url) . '" style="max-width: 100%; height: auto;" />';
        }
        echo '<label for="cm_gamerecipe_img">' . __('Ladda upp spelplan eller annan grafisk info', 'cm-gamerecipe') . '</label>';
        echo '<input type="text" id="cm_gamerecipe_img" name="cm_gamerecipe_img" value="' . esc_url($img_url) . '" />';
        echo '<input type="button" id="cm_gamerecipe_img_button" class="button" value="' . __('Välj bild', 'cm-gamerecipe') . '" />';

        // Fält för tips (enkel textarea)
        echo '<label for="cm_gamerecipe_tips">' . __('Tips', 'cm-gamerecipe') . '</label>';
        echo '<textarea id="cm_gamerecipe_tips" name="cm_gamerecipe_tips" rows="4" cols="50">' . $tips . '</textarea>';

        echo '</div>';
    }

    // Spara metadata när spelet sparas
    public static function save_meta_box_data($post_id)
    {
        // Säkerhetskontroll: Kontrollera att data kan sparas
        if (!isset($_POST['cm_gamerecipe_meta_box_nonce']) || !wp_verify_nonce($_POST['cm_gamerecipe_meta_box_nonce'], 'cm_gamerecipe_save_meta_box_data')) {
            return;
        }

        // Kontrollera användarrättigheter
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Hämta och sanera data från formuläret
        $min_players = isset($_POST['cm_gamerecipe_min_players']) ? intval($_POST['cm_gamerecipe_min_players']) : 0;
        $max_players = isset($_POST['cm_gamerecipe_max_players']) ? intval($_POST['cm_gamerecipe_max_players']) : 0;
        $typical_duration = isset($_POST['cm_gamerecipe_typical_duration']) ? intval($_POST['cm_gamerecipe_typical_duration']) : 0;
        $game_type = isset($_POST['cm_gamerecipe_game_type']) ? sanitize_text_field($_POST['cm_gamerecipe_game_type']) : '';
        $materials = isset($_POST['cm_gamerecipe_materials']) ? array_map('sanitize_text_field', $_POST['cm_gamerecipe_materials']) : array();
        $suitable_for = isset($_POST['cm_gamerecipe_suitable_for']) ? array_map('sanitize_text_field', $_POST['cm_gamerecipe_suitable_for']) : array();
        $difficulty = isset($_POST['cm_gamerecipe_difficulty']) ? sanitize_text_field($_POST['cm_gamerecipe_difficulty']) : '';
        $preparation = isset($_POST['cm_gamerecipe_preparation']) ? sanitize_text_field($_POST['cm_gamerecipe_preparation']) : '';
        $tips = isset($_POST['cm_gamerecipe_tips']) ? sanitize_textarea_field($_POST['cm_gamerecipe_tips']) : '';

        // Spara IMG-url
        if (isset($_POST['cm_gamerecipe_img'])) {
            $img_url = sanitize_text_field($_POST['cm_gamerecipe_img']);
            update_post_meta($post_id, 'cm_gamerecipe_img', $img_url);
        }


        // Anropa funktionen för att spara data i den anpassade databastabellen
        CM_Gamerecipe_Game_Handler::save_game_data($post_id, $min_players, $max_players, $typical_duration, $materials, $suitable_for, $difficulty, $preparation, $tips, $img_url);
    }
}

// Registrera meta box
add_action('add_meta_boxes', array('CM_Gamerecipe_Game_Meta_Box', 'add_meta_box'));

// Spara metadata när ett spel sparas
add_action('save_post', array('CM_Gamerecipe_Game_Meta_Box', 'save_meta_box_data'));
