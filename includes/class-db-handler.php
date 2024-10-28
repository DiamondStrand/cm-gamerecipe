<?php
class CM_Gamerecipe_DB_Handler
{
    // Skapa materialtabellen när pluginet aktiveras
    public static function create_material_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cm_gamerecipe_materials';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id INT(11) NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            description TEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    // Rensa permalänkar eller andra regler när pluginet avaktiveras
    public static function deactivate_plugin()
    {
        flush_rewrite_rules();
    }

    // Ta bort materialtabellen om pluginet avinstalleras
    public static function delete_material_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cm_gamerecipe_materials';

        // Ta bort tabellen vid avinstallation
        $sql = "DROP TABLE IF EXISTS $table_name;";
        $wpdb->query($sql);
    }
}

// Hook för att skapa tabellen när pluginet aktiveras
register_activation_hook(__FILE__, array('CM_Gamerecipe_DB_Handler', 'create_material_table'));

// Hook för att ta bort materialtabellen om pluginet avinstalleras
register_uninstall_hook(__FILE__, array('CM_Gamerecipe_DB_Handler', 'delete_material_table'));
