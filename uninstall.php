<?php
// Kontrollera om WordPress kallar på avinstallationen, annars avsluta
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;
$table_name = $wpdb->prefix . 'cm_gamerecipe_games';

// Radera databastabellen för spelrecept
$wpdb->query("DROP TABLE IF EXISTS $table_name;");
