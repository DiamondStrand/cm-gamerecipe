<?php

class CM_Gamerecipe_Game_Post_Type
{
    // Registrera Custom Post Type (Spel)
    public static function register_post_type()
    {
        $labels = array(
            'name'               => _x('Spel', 'post type general name', 'cm-gamerecipe'),
            'singular_name'      => _x('Spel', 'post type singular name', 'cm-gamerecipe'),
            'menu_name'          => _x('Spel', 'admin menu', 'cm-gamerecipe'),
            'name_admin_bar'     => _x('Spel', 'add new on admin bar', 'cm-gamerecipe'),
            'add_new'            => _x('Lägg till nytt spel', 'spel', 'cm-gamerecipe'),
            'add_new_item'       => __('Lägg till nytt spel', 'cm-gamerecipe'),
            'new_item'           => __('Nytt spel', 'cm-gamerecipe'),
            'edit_item'          => __('Redigera spel', 'cm-gamerecipe'),
            'view_item'          => __('Visa spel', 'cm-gamerecipe'),
            'all_items'          => __('Alla spel', 'cm-gamerecipe'),
            'search_items'       => __('Sök spel', 'cm-gamerecipe'),
            'not_found'          => __('Inga spel funna', 'cm-gamerecipe'),
            'not_found_in_trash' => __('Inga spel funna i papperskorgen', 'cm-gamerecipe')
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => true,
            'rewrite'            => array('slug' => 'spel'),  // Slug som används i URL:en för spelen
            'supports'           => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields'), // 'editor' för att redigera beskrivning (spelregler)
            'taxonomies'         => array('category', 'post_tag'), // Använd WordPress inbyggda kategorier och taggar
            'show_in_rest'       => true,  // Gör CPT kompatibel med blockredigeraren (Gutenberg)
            'menu_icon'          => 'dashicons-games',  // Ikon för CPT i adminpanelen
            'capability_type'    => 'post',  // Använd standardkapaciteten för posts
            'publicly_queryable' => true,  // Gör det möjligt att visa CPT på frontend
            'show_ui'            => true,  // Visa CPT i adminpanelen
            'show_in_menu'       => true,  // Visa CPT i adminmenyn
            'menu_position'      => 20,    // Placering i adminmenyn
        );

        register_post_type('cm_game', $args);  // Registrera posttypen "cm_game"
    }
}

// Registrera posttypen vid WordPress init
add_action('init', array('CM_Gamerecipe_Game_Post_Type', 'register_post_type'));

// Flush omskrivningsregler vid aktivering för att säkerställa att reglerna uppdateras
function cm_gamerecipe_flush_rewrite_rules()
{
    CM_Gamerecipe_Game_Post_Type::register_post_type();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'cm_gamerecipe_flush_rewrite_rules');
