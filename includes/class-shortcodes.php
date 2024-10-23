<?php
// CM Gamerecipe Shortcodes
class CM_Gamerecipe_Shortcodes
{
    // Hämta speldata från den anpassade tabellen baserat på post_ID
    private static function get_game_data($post_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cm_gamerecipe_games'; // Din anpassade tabell

        // Hämta speldata från den anpassade databastabellen baserat på post-ID
        $game_data = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM $table_name WHERE post_id = %d", $post_id)
        );

        return $game_data; // Returnera resultatet (eller null om ingen data hittades)
    }

    // Shortcode för att visa material
    public static function materials_shortcode($atts)
    {
        global $post;
        $game_data = self::get_game_data($post->ID); // Hämta speldata baserat på post-ID

        if ($game_data) {
            $materials = maybe_unserialize($game_data->materials);
            return isset($materials) ? implode(', ', $materials) : 'Inget material tillgängligt';
        } else {
            return 'Ingen speldata hittades för post_id: ' . $post->ID;
        }
    }

    // Shortcode för att visa minsta antal spelare
    public static function min_players_shortcode($atts)
    {
        global $post;
        $game_data = self::get_game_data($post->ID);
        return isset($game_data->min_players) ? esc_html($game_data->min_players) : 'Ingen speldata hittades';
    }

    // Shortcode för att visa maximala antal spelare
    public static function max_players_shortcode($atts)
    {
        global $post;
        $game_data = self::get_game_data($post->ID);
        return isset($game_data->max_players) ? esc_html($game_data->max_players) : 'Ingen speldata hittades';
    }

    // Shortcode för att visa speltid
    public static function typical_duration_shortcode($atts)
    {
        global $post;
        $game_data = self::get_game_data($post->ID);
        return isset($game_data->typical_duration) ? esc_html($game_data->typical_duration) . ' minuter' : 'Ingen speltid hittades';
    }

    // Shortcode för att visa tips
    public static function tips_shortcode($atts)
    {
        global $post;
        $game_data = self::get_game_data($post->ID);
        return isset($game_data->tips) ? esc_html($game_data->tips) : 'Inga tips tillgängliga';
    }

    // Shortcode för att visa svårighetsgrad
    public static function difficulty_shortcode($atts)
    {
        global $post;
        $game_data = self::get_game_data($post->ID);
        return isset($game_data->difficulty) ? esc_html($game_data->difficulty) : 'Ingen svårighetsgrad angiven';
    }

    // Shortcode för att visa typ av spel
    public static function game_type_shortcode($atts)
    {
        global $post;
        $game_data = self::get_game_data($post->ID);
        return isset($game_data->game_type) ? esc_html($game_data->game_type) : 'Ingen typ av spel angiven';
    }

    // Shortcode för att visa förberedelser
    public static function preparation_shortcode($atts)
    {
        global $post;
        $game_data = self::get_game_data($post->ID);
        return isset($game_data->preparation) ? esc_html($game_data->preparation) : 'Inga förberedelser angivna';
    }

    // Shortcode för att visa 'Passar för'
    public static function suitable_for_shortcode($atts)
    {
        global $post;
        $game_data = self::get_game_data($post->ID); // Hämta spelets data med post-ID
        $suitable_for = maybe_unserialize($game_data->suitable_for);
        return isset($suitable_for) ? implode(', ', $suitable_for) : 'Ingen information om lämplig åldersgrupp';
    }


    // Registrera alla shortcodes
    public static function register_shortcodes()
    {
        add_shortcode('cm_gamerecipe_min_players', array(__CLASS__, 'min_players_shortcode'));
        add_shortcode('cm_gamerecipe_max_players', array(__CLASS__, 'max_players_shortcode'));
        add_shortcode('cm_gamerecipe_typical_duration', array(__CLASS__, 'typical_duration_shortcode'));
        add_shortcode('cm_gamerecipe_materials', array(__CLASS__, 'materials_shortcode'));
        add_shortcode('cm_gamerecipe_tips', array(__CLASS__, 'tips_shortcode'));
        add_shortcode('cm_gamerecipe_difficulty', array(__CLASS__, 'difficulty_shortcode'));
        add_shortcode('cm_gamerecipe_game_type', array(__CLASS__, 'game_type_shortcode'));
        add_shortcode('cm_gamerecipe_preparation', array(__CLASS__, 'preparation_shortcode'));
        add_shortcode('cm_gamerecipe_suitable_for', array(__CLASS__, 'suitable_for_shortcode'));
    }
}

// Registrera alla shortcodes vid init
add_action('init', array('CM_Gamerecipe_Shortcodes', 'register_shortcodes'));
