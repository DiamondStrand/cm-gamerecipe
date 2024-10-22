<?php
// CM Gamerecipe Shortcodes

class CM_Gamerecipe_Shortcodes
{

    // Shortcode för att visa nedladdningslänk för spelplan/regelverk
    public static function img_download_shortcode($atts)
    {
        $post_id = get_the_ID();
        $img_url = get_post_meta($post_id, 'cm_gamerecipe_pdf', true);
        return $img_url ? '<a href="' . esc_url($img_url) . '" target="_blank">Ladda ner spelplan/regelverk (IMG)</a>' : '';
    }

    // Shortcode för att visa minsta antal spelare
    public static function min_players_shortcode($atts)
    {
        $post_id = get_the_ID();
        $game_data = CM_Gamerecipe_Game_Handler::get_game_data($post_id);
        return isset($game_data->min_players) ? esc_html($game_data->min_players) : '';
    }

    // Shortcode för att visa maximala antal spelare
    public static function max_players_shortcode($atts)
    {
        $post_id = get_the_ID();
        $game_data = CM_Gamerecipe_Game_Handler::get_game_data($post_id);
        return isset($game_data->max_players) ? esc_html($game_data->max_players) : '';
    }

    // Shortcode för att visa speltid
    public static function typical_duration_shortcode($atts)
    {
        $post_id = get_the_ID();
        $game_data = CM_Gamerecipe_Game_Handler::get_game_data($post_id);
        return isset($game_data->typical_duration) ? esc_html($game_data->typical_duration) . ' minuter' : '';
    }

    // Shortcode för att visa material
    public static function materials_shortcode($atts)
    {
        $post_id = get_the_ID();
        $game_data = CM_Gamerecipe_Game_Handler::get_game_data($post_id);
        $materials = maybe_unserialize($game_data->materials);
        return isset($materials) ? implode(', ', $materials) : '';
    }

    // Shortcode för att visa tips
    public static function tips_shortcode($atts)
    {
        $post_id = get_the_ID();
        $game_data = CM_Gamerecipe_Game_Handler::get_game_data($post_id);
        return isset($game_data->tips) ? esc_html($game_data->tips) : '';
    }

    // Shortcode för att visa svårighetsgrad
    public static function difficulty_shortcode($atts)
    {
        $post_id = get_the_ID();
        $game_data = CM_Gamerecipe_Game_Handler::get_game_data($post_id);
        return isset($game_data->difficulty) ? esc_html($game_data->difficulty) : '';
    }

    // Shortcode för att visa typ av spel
    public static function game_type_shortcode($atts)
    {
        $post_id = get_the_ID();
        $game_data = CM_Gamerecipe_Game_Handler::get_game_data($post_id);
        return isset($game_data->game_type) ? esc_html($game_data->game_type) : '';
    }

    // Shortcode för att visa förberedelser
    public static function preparation_shortcode($atts)
    {
        $post_id = get_the_ID();
        $game_data = CM_Gamerecipe_Game_Handler::get_game_data($post_id);
        return isset($game_data->preparation) ? esc_html($game_data->preparation) : '';
    }

    // Registrera alla shortcodes
    public static function register_shortcodes()
    {
        add_shortcode('cm_gamerecipe_img_download', array(__CLASS__, 'img_download_shortcode'));
        add_shortcode('cm_gamerecipe_min_players', array(__CLASS__, 'min_players_shortcode'));
        add_shortcode('cm_gamerecipe_max_players', array(__CLASS__, 'max_players_shortcode'));
        add_shortcode('cm_gamerecipe_typical_duration', array(__CLASS__, 'typical_duration_shortcode'));
        add_shortcode('cm_gamerecipe_materials', array(__CLASS__, 'materials_shortcode'));
        add_shortcode('cm_gamerecipe_tips', array(__CLASS__, 'tips_shortcode'));
        add_shortcode('cm_gamerecipe_difficulty', array(__CLASS__, 'difficulty_shortcode'));
        add_shortcode('cm_gamerecipe_game_type', array(__CLASS__, 'game_type_shortcode'));
        add_shortcode('cm_gamerecipe_preparation', array(__CLASS__, 'preparation_shortcode'));
    }
}

// Registrera alla shortcodes vid init
add_action('init', array('CM_Gamerecipe_Shortcodes', 'register_shortcodes'));
