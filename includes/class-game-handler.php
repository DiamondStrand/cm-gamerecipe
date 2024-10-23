<?php
class CM_Gamerecipe_Game_Handler
{
    // Hämta speldata från databasen baserat på post ID
    public static function get_game_data($post_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cm_gamerecipe_games';

        $game_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE post_id = %d", $post_id));

        return $game_data;
    }


    // Spara speldata till databasen
    public static function save_game_data($post_id, $min_players, $max_players, $typical_duration, $materials, $suitable_for, $difficulty, $preparation, $tips, $img_url, $game_type)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cm_gamerecipe_games';

        $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE post_id = %d", $post_id));

        if ($exists) {
            $wpdb->update(
                $table_name,
                array(
                    'min_players' => $min_players,
                    'max_players' => $max_players,
                    'typical_duration' => $typical_duration,
                    'game_type' => sanitize_text_field($game_type), // Lägg till game_type här
                    'materials' => maybe_serialize($materials),
                    'suitable_for' => maybe_serialize($suitable_for),
                    'difficulty' => sanitize_text_field($difficulty),
                    'preparation' => sanitize_text_field($preparation),
                    'tips' => sanitize_textarea_field($tips),
                    'img_url' => sanitize_text_field($img_url),
                    'updated_at' => current_time('mysql')
                ),
                array('post_id' => $post_id),
                array('%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s')
            );
        } else {
            $wpdb->insert(
                $table_name,
                array(
                    'post_id' => $post_id,
                    'min_players' => $min_players,
                    'max_players' => $max_players,
                    'typical_duration' => $typical_duration,
                    'game_type' => sanitize_text_field($game_type), // Lägg till game_type här
                    'materials' => maybe_serialize($materials),
                    'suitable_for' => maybe_serialize($suitable_for),
                    'difficulty' => sanitize_text_field($difficulty),
                    'preparation' => sanitize_text_field($preparation),
                    'tips' => sanitize_textarea_field($tips),
                    'img_url' => sanitize_text_field($img_url),
                    'created_at' => current_time('mysql'),
                    'updated_at' => current_time('mysql')
                ),
                array('%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
            );
        }
    }



    // Ta bort speldata baserat på post ID
    public static function delete_game_data($post_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cm_gamerecipe_games';

        // Ta bort data från databasen
        $wpdb->delete(
            $table_name,
            array('post_id' => $post_id),
            array('%d')
        );
    }
}
