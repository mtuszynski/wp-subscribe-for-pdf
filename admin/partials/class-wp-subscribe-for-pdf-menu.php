<?php
class Wp_Subscribe_For_Pdf_Menu
{
    public function wp_subscribe_for_pdf_menu()
    {
        add_menu_page(
            __('Wp Subscribe for PDF', 'wp-subscribe-for-pdf'),
            __('Wp Subscribe for PDF', 'wp-subscribe-for-pdf'),
            'manage_options',
            'wp-subscribe-for-pdf',
            array($this, 'wp_subscribe_for_pdf_page'),
        );
        add_submenu_page(
            'wp-subscribe-for-pdf',
            __('Settings', 'wp-subscribe-for-pdf'),
            __('Settings', 'wp-subscribe-for-pdf'),
            'manage_options',
            'wp-subscribe-for-pdf',
            array($this, 'wp_subscribe_for_pdf_page')
        );
        add_submenu_page(
            'wp-subscribe-for-pdf',
            __('Subscribers List', 'wp-subscribe-for-pdf'),
            __('Subscribers List', 'wp-subscribe-for-pdf'),
            'manage_options',
            'wp-subscribe-for-pdf-list',
            array($this, 'wp_subscribe_for_pdf_list')
        );
    }

    public function wp_subscribe_for_pdf_page()
    {
        printf('<h2>%s</h2>', esc_html__('Welcome to WP Subscribe for PDF', 'wp-subscribe-for-pdf'));
        printf(esc_html__('To display the form, add the shortcode %s to your code', 'wp-subscribe-for-pdf'), '[wp_subscribe_for_pdf]');
        printf('<h3>%s</h3>', esc_html__('Upload an Document:', 'wp-subscribe-for-pdf'));


        if (isset($_POST['upload_document'])) {
            $uploaded_document = media_handle_upload('document', 0);
            if (is_wp_error($uploaded_document)) {
                printf(
                    esc_html__('Error uploading the document: %s', 'wp-subscribe-for-pdf'),
                    $uploaded_document->get_error_message()
                );
            } else {
                printf(esc_html__('Document uploaded successfully!', 'wp-subscribe-for-pdf'));

                $uploaded_document_url = wp_get_attachment_url($uploaded_document);

                // Save the document URL to the database
                global $wpdb;
                $table_name = $wpdb->prefix . 'wp_subscribe_for_pdf_settings';
                $wpdb->insert(
                    $table_name,
                    array(
                        'document_url' => $uploaded_document_url
                    ),
                    array(
                        '%s'
                    )
                );
            }
        }
?>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="document" id="document" />
            <input type="submit" name="upload_document" value="Upload" />
        </form>
<?php

        global $wpdb;
        $table_name = $wpdb->prefix . 'wp_subscribe_for_pdf_settings';
        $saved_document_url = $wpdb->get_var("SELECT document_url FROM $table_name ORDER BY id DESC LIMIT 1");

        if ($saved_document_url) {
            printf('<h3>%s</h3>', esc_html__('Your file:', 'wp-subscribe-for-pdf'));
            echo esc_url($saved_document_url);
        }
    }

    public function wp_subscribe_for_pdf_list()
    {

        global $post;
        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'any',
        );

        $myposts = get_posts($args);

        printf('<div class="sutp-admin-container">');
        printf('<table id="sutp-table" class="display">');
        printf('<thead>');
        printf(
            '<tr>
            <th>%s:</th>
            <th>%s:</th>
            <th>%s:</th>
            <th>%s:</th>
            <th>%s:</th>
        </tr>',
            esc_html__('First Name', 'wp-subscribe-for-pdf'),
            esc_html__('Last Name', 'wp-subscribe-for-pdf'),
            esc_html__('Email', 'wp-subscribe-for-pdf'),
            esc_html__('Submission Date', 'wp-subscribe-for-pdf'),
            esc_html('Post id', 'wp-subscribe-for-pdf')
        );
        printf('</thead>');
        printf('<tbody>');

        foreach ($myposts as $post) {
            setup_postdata($post);
            $custom_req_data = get_post_meta($post->ID, 'custom_req', true);

            if (is_array($custom_req_data) && !empty($custom_req_data)) {
                printf('<tr>');
                foreach ($custom_req_data as $user_data) {
                    printf('<td>%s</td>', esc_html($user_data['first_name']));
                    printf('<td>%s</td>', esc_html($user_data['last_name']));
                    printf('<td>%s</td>', esc_html($user_data['user_email']));
                    printf('<td>%s</td>', esc_html($user_data['submission_date']));
                    printf('<td>%s</td>', esc_html($post->ID));
                    printf('</tr>');
                }
            }

            wp_reset_postdata();
        }

        printf('</tbody>');
        printf('</table>');
        printf('</div>');
    }
}
