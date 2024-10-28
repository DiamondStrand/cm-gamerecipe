<?php
class CM_Gamerecipe_Material_Admin
{
    // Lägg till Material-meny i adminpanelen
    public static function add_material_menu()
    {
        add_submenu_page(
            'edit.php?post_type=cm_game',        // Underordnad till cm_game CPT
            __('Hantera Material', 'cm-gamerecipe'),
            __('Material', 'cm-gamerecipe'),
            'manage_options',
            'cm_gamerecipe_materials',
            array('CM_Gamerecipe_Material_Admin', 'render_material_page')
        );
    }

    // Rendera Material-sidan
    public static function render_material_page()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cm_gamerecipe_materials';

        // Lägg till nytt material
        if (isset($_POST['cm_gamerecipe_add_material']) && !empty($_POST['material_name'])) {
            $material_name = sanitize_text_field($_POST['material_name']);
            $description = sanitize_textarea_field($_POST['material_description']);

            $wpdb->insert(
                $table_name,
                array('name' => $material_name, 'description' => $description),
                array('%s', '%s')
            );
            echo '<div class="updated"><p>' . __('Material tillagt!', 'cm-gamerecipe') . '</p></div>';
        }

        // Ta bort ett material
        if (isset($_GET['delete_material'])) {
            $material_id = intval($_GET['delete_material']);
            $wpdb->delete($table_name, array('id' => $material_id), array('%d'));
            echo '<div class="updated"><p>' . __('Material raderat!', 'cm-gamerecipe') . '</p></div>';
        }

        // Hämta alla material
        $materials = $wpdb->get_results("SELECT * FROM $table_name");

        // Formulär och lista över material
?>
        <div class="wrap">
            <h1><?php _e('Hantera Material', 'cm-gamerecipe'); ?></h1>
            <form method="post">
                <table class="form-table">
                    <tr>
                        <th><label for="material_name"><?php _e('Materialnamn', 'cm-gamerecipe'); ?></label></th>
                        <td><input type="text" name="material_name" id="material_name" required /></td>
                    </tr>
                    <tr>
                        <th><label for="material_description"><?php _e('Beskrivning', 'cm-gamerecipe'); ?></label></th>
                        <td><textarea name="material_description" id="material_description"></textarea></td>
                    </tr>
                </table>
                <?php submit_button(__('Lägg till Material', 'cm-gamerecipe'), 'primary', 'cm_gamerecipe_add_material'); ?>
            </form>

            <h2><?php _e('Existerande Material', 'cm-gamerecipe'); ?></h2>
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php _e('ID', 'cm-gamerecipe'); ?></th>
                        <th><?php _e('Namn', 'cm-gamerecipe'); ?></th>
                        <th><?php _e('Beskrivning', 'cm-gamerecipe'); ?></th>
                        <th><?php _e('Åtgärder', 'cm-gamerecipe'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materials as $material) : ?>
                        <tr>
                            <td><?php echo esc_html($material->id); ?></td>
                            <td><?php echo esc_html($material->name); ?></td>
                            <td><?php echo esc_html($material->description); ?></td>
                            <td><a href="<?php echo add_query_arg(array('delete_material' => $material->id)); ?>" onclick="return confirm('<?php _e('Är du säker på att du vill ta bort detta material?', 'cm-gamerecipe'); ?>');"><?php _e('Radera', 'cm-gamerecipe'); ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
<?php
    }
}

// Lägg till admin-meny för material vid admin-init
add_action('admin_menu', array('CM_Gamerecipe_Material_Admin', 'add_material_menu'));
