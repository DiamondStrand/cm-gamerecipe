<?php
class CM_Gamerecipe_Game_Handler
{
    // Hämta speldata från wp_postmeta baserat på post ID
    public static function get_game_data($post_id)
    {
        return (object) array(
            'min_players' => get_post_meta($post_id, 'min_players', true),
            'max_players' => get_post_meta($post_id, 'max_players', true),
            'typical_duration' => get_post_meta($post_id, 'typical_duration', true),
            'materials' => maybe_unserialize(get_post_meta($post_id, 'materials', true)),
            'suitable_for' => maybe_unserialize(get_post_meta($post_id, 'suitable_for', true)),
            'difficulty' => get_post_meta($post_id, 'difficulty', true),
            'preparation' => get_post_meta($post_id, 'preparation', true),
            'tips' => get_post_meta($post_id, 'tips', true),
            'img_url' => get_post_meta($post_id, 'img_url', true),
            'game_type' => get_post_meta($post_id, 'game_type', true)
        );
    }

    // Spara speldata till wp_postmeta
    public static function save_game_data($post_id, $data)
    {
        update_post_meta($post_id, 'min_players', sanitize_text_field($data['min_players']));
        update_post_meta($post_id, 'max_players', sanitize_text_field($data['max_players']));
        update_post_meta($post_id, 'typical_duration', sanitize_text_field($data['typical_duration']));
        update_post_meta($post_id, 'materials', maybe_serialize($data['materials']));
        update_post_meta($post_id, 'suitable_for', maybe_serialize($data['suitable_for']));
        update_post_meta($post_id, 'difficulty', sanitize_text_field($data['difficulty']));
        update_post_meta($post_id, 'preparation', sanitize_text_field($data['preparation']));
        update_post_meta($post_id, 'tips', sanitize_textarea_field($data['tips']));
        update_post_meta($post_id, 'img_url', esc_url($data['img_url']));
        update_post_meta($post_id, 'game_type', sanitize_text_field($data['game_type']));
    }

    // Ta bort speldata (metadata) baserat på post ID
    public static function delete_game_data($post_id)
    {
        delete_post_meta($post_id, 'min_players');
        delete_post_meta($post_id, 'max_players');
        delete_post_meta($post_id, 'typical_duration');
        delete_post_meta($post_id, 'materials');
        delete_post_meta($post_id, 'suitable_for');
        delete_post_meta($post_id, 'difficulty');
        delete_post_meta($post_id, 'preparation');
        delete_post_meta($post_id, 'tips');
        delete_post_meta($post_id, 'img_url');
        delete_post_meta($post_id, 'game_type');
    }
}
