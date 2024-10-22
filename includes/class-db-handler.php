<?php
class CM_Gamerecipe_DB_Handler
{
    // Skapa databastabeller när pluginet aktiveras
    public static function create_db_tables()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cm_gamerecipe_games';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id INT(11) NOT NULL AUTO_INCREMENT,
        post_id BIGINT(20) UNSIGNED NOT NULL,
        min_players INT(3) NOT NULL,
        max_players INT(3) NOT NULL,
        typical_duration INT(3) NOT NULL,
        game_type VARCHAR(100) NOT NULL,
        materials TEXT NOT NULL,
        suitable_for TEXT NOT NULL,
        difficulty VARCHAR(50) NOT NULL,
        preparation VARCHAR(50) NOT NULL,
        tips TEXT,
        img_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        FOREIGN KEY (post_id) REFERENCES {$wpdb->prefix}posts(ID) ON DELETE CASCADE
    ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }


    // Rensa regler och tabeller när pluginet avaktiveras
    public static function deactivate_plugin()
    {
        // Här kan du rensa permalänksstrukturer eller andra regler om nödvändigt
        flush_rewrite_rules();
    }

    // Ta bort tabellen om pluginet avinstalleras (använd vid total borttagning av pluginet)
    public static function delete_db_tables()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cm_gamerecipe_games';

        // Ta bort tabellen vid avinstallation
        $sql = "DROP TABLE IF EXISTS $table_name;";
        $wpdb->query($sql);
    }
}

// Lägg till hook för att ta bort tabellen om pluginet avinstalleras
register_uninstall_hook(__FILE__, array('CM_Gamerecipe_DB_Handler', 'delete_db_tables'));
