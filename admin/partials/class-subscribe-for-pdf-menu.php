<?php
class Subscribe_For_Pdf_Menu
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    public function add_admin_menu()
    {
        add_menu_page(
            __('Subscribe For PDF Settings', 'subscribe-for-pdf'),
            __('Subscribe For PDF', 'subscribe-for-pdf'),
            'manage_options',
            'Subscribe_For_Pdf_settings',
            array($this, 'settings_page_content'),
            'dashicons-admin-settings',
            20
        );
        add_submenu_page(
            'Subscribe_For_Pdf_settings',
            __('Subscribers List', 'subscribe-for-pdf'),
            __('Subscribers List', 'subscribe-for-pdf'),
            'manage_options',
            'Subscribe_For_Pdf_subscribers_list',
            array($this, 'subscribers_list_page_content')
        );
    }

    public function settings_page_content()
    {
        // Sprawdzanie uprawnień użytkownika
        if (!current_user_can('manage_options')) {
            return;
        }

        printf('<h2>%s</h2>', esc_html__('Welcome to WP Subscribe for PDF', 'subscribe-for-pdf'));
        printf(esc_html__('To display the form, add the shortcode %s to your code', 'subscribe-for-pdf'), '[wp_subscribe_for_pdf]');
        printf('<h3>%s</h3>', esc_html__('Upload a Document:', 'subscribe-for-pdf'));

        if (isset($_POST['save_settings']) && check_admin_referer('save_settings_action', 'save_settings_nonce')) {
            if (!empty($_FILES['document']['name'])) {
                $file_type = wp_check_filetype($_FILES['document']['name']);
                $allowed_types = array('pdf' => 'application/pdf');
                if (!array_key_exists($file_type['ext'], $allowed_types)) {
                    printf(esc_html__('Only PDF files are allowed. Your file type: %s', 'subscribe-for-pdf'), esc_html($file_type['type']));
                } else {
                    $uploaded_document = media_handle_upload('document', 0);
                    if (is_wp_error($uploaded_document)) {
                        printf(
                            esc_html__('Error uploading the document: %s', 'subscribe-for-pdf'),
                            $uploaded_document->get_error_message()
                        );
                    } else {
                        printf(esc_html__('Document uploaded successfully!', 'subscribe-for-pdf'));

                        $uploaded_document_url = wp_get_attachment_url($uploaded_document);
                        global $wpdb;
                        $table_name = $wpdb->prefix . 'subscribe_for_pdf_settings';

                        $record_exists = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE id = 1");
                        if ($record_exists) {
                            $wpdb->update(
                                $table_name,
                                array('document_url' => $uploaded_document_url),
                                array('id' => 1),
                                array('%s'),
                                array('%d')
                            );
                        } else {
                            $wpdb->insert(
                                $table_name,
                                array(
                                    'id' => 1,
                                    'document_url' => $uploaded_document_url
                                ),
                                array(
                                    '%d',
                                    '%s'
                                )
                            );
                        }

                        if ($wpdb->last_error) {
                            printf(esc_html__('Error inserting or updating document URL: %s', 'subscribe-for-pdf'), $wpdb->last_error);
                        }
                    }
                }
            }
            $delete_tables_on_deactivation = isset($_POST['delete_tables_on_deactivation']) ? 1 : 0;

            global $wpdb;
            $table_name = $wpdb->prefix . 'subscribe_for_pdf_settings';
            $record_exists = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE id = 1");

            if ($record_exists) {
                $wpdb->update(
                    $table_name,
                    array(
                        'delete_tables_on_deactivation' => $delete_tables_on_deactivation
                    ),
                    array('id' => 1),
                    array('%d'),
                    array('%d')
                );
            } else {
                $wpdb->insert(
                    $table_name,
                    array(
                        'id' => 1,
                        'delete_tables_on_deactivation' => $delete_tables_on_deactivation
                    ),
                    array(
                        '%d',
                        '%d'
                    )
                );
            }

            if ($wpdb->last_error) {
                printf(esc_html__('Error updating settings: %s', 'subscribe-for-pdf'), $wpdb->last_error);
            }

            printf(esc_html__('Settings updated successfully!', 'subscribe-for-pdf'));
        }

?>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('save_settings_action', 'save_settings_nonce'); ?>
            <input type="file" name="document" id="document" />
            <h3><?php esc_html_e('Settings:', 'subscribe-for-pdf'); ?></h3>
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . 'subscribe_for_pdf_settings';
            $delete_tables_on_deactivation = $wpdb->get_var("SELECT delete_tables_on_deactivation FROM $table_name WHERE id = 1");
            ?>
            <p>
                <input type="checkbox" name="delete_tables_on_deactivation" id="delete_tables_on_deactivation" value="1" <?php checked($delete_tables_on_deactivation, 1); ?> />
                <label for="delete_tables_on_deactivation"><?php esc_html_e('Delete tables on plugin deactivation', 'subscribe-for-pdf'); ?></label>
            </p>
            <input type="submit" name="save_settings" value="<?php esc_attr_e('Save Settings', 'subscribe-for-pdf'); ?>" />
        </form>
<?php
        global $wpdb;
        $table_name = $wpdb->prefix . 'subscribe_for_pdf_settings';
        $saved_document_url = $wpdb->get_var("SELECT document_url FROM $table_name ORDER BY id DESC LIMIT 1");

        if ($saved_document_url) {
            printf('<h3>%s</h3>', esc_html__('Your file:', 'subscribe-for-pdf'));
            echo esc_url($saved_document_url);
        }
    }


    public function subscribers_list_page_content()
    {
        echo '<div class="wrap">';
        echo '<h1>' . __('Subscribers List', 'subscribe-for-pdf') . '</h1>';
        echo '</div>';
    }
}
