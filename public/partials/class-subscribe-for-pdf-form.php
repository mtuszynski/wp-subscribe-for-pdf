<?php
class Subscribe_For_Pdf_Form
{
    public function __construct()
    {
        add_shortcode('wp_subscribe_for_pdf', array($this, 'output_shortcode'));
        add_action('init', array($this, 'handle_cookie')); // Dodajemy hook do init
    }

    public function handle_cookie()
    {
        if (isset($_POST['submit']) && isset($_POST['email'])) {
            $email = sanitize_email($_POST['email']);
            $this->set_cookie($email);
        }
    }

    public function set_cookie($email)
    {
        if (!isset($_COOKIE['submitted_email'])) {
            setcookie('submitted_email', $email, time() + (6 * 30 * 24 * 60 * 60), COOKIEPATH, COOKIE_DOMAIN, false);
        }
    }
    public function display_saved_file()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'subscribe_for_pdf_settings';
        $savedDocumentURL = $wpdb->get_var("SELECT document_url FROM $table_name ORDER BY id DESC LIMIT 1");
        if ($savedDocumentURL) {
            printf(
                '<div class="sutp-container"><h3>%s</h3><a class="pdf-link" href="%s">%s</a></div>',
                esc_html__('Download PDF:', 'subscribe-for-pdf'),
                esc_url($savedDocumentURL),
                esc_html__('Click here', 'subscribe-for-pdf')
            );
        } else {
            printf(
                '<div class="sutp-container"><h3>%s</h3><p>%s</p></div>',
                esc_html__('No PDF available', 'subscribe-for-pdf'),
                esc_html__('No document has been uploaded yet.', 'subscribe-for-pdf')
            );
        }
    }

    /**
     * Shortcode output function for displaying the subscription form or the saved file.
     *
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function output_shortcode($atts)
    {
        ob_start();
        if (!is_admin()) {
            if (isset($_COOKIE['submitted_email'])) {
                $this->display_saved_file();
            } else {
                $this->sign_up_form();
            }
        }
        $content = ob_get_clean();

        return $content;
    }
    /**
     * Processes and displays the sign-up form for PDF subscriptions.
     *
     * This function checks if the form has been submitted and if the nonce is valid for security.
     * It validates the user inputs (first name, last name, and email), sanitizes the inputs, 
     * and, if valid, registers the user by saving the information.
     * After registration, it either shows a success message or displays error messages if validation fails.
     *
     * - Uses nonce for security verification.
     * - Sanitizes and validates first name, last name, and email fields.
     * - On successful submission, triggers user registration and form rendering.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function sign_up_form()
    {
        if (isset($_POST['submit'])) {
            if (!isset($_POST['user_registration_nonce']) || !wp_verify_nonce($_POST['user_registration_nonce'], 'user_registration_nonce')) {
                wp_die(__('Security check failed. Please try again.', 'subscribe-for-pdf'));
            }
            $validation_result = $this->sign_up_validation($_POST['first_name'], $_POST['last_name'], $_POST['email']);
            if ($validation_result) {
                $first_name = sanitize_text_field($_POST['first_name']);
                $last_name = sanitize_text_field($_POST['last_name']);
                $email = sanitize_email($_POST['email']);
                $registration_result = $this->sign_up_registration($first_name, $last_name, $email);
            }
        }
        $this->render_sign_up_form();
    }

    public function render_sign_up_form()
    {
        $nonce = wp_create_nonce('user_registration_nonce');
        $form_action_url = esc_url($_SERVER['REQUEST_URI']);
        printf(
            '
    <div class="sutp-container">
    <form action="%s" method="post" class="sutp-form">
        <input type="hidden" name="user_registration_nonce" value="%s">
        <div class="sutp-form-group">
            <input type="text" name="first_name" id="firstname" class="sutp-form-control" value="" maxlength="50" pattern="[A-Za-z\s]*" required>
            <label for="firstname">%s<span class="sutp-form-asterisk">*</span></label>
        </div>
        <div class="sutp-form-group">
            <input type="text" name="last_name" id="lastname" class="sutp-form-control" value="" maxlength="50" pattern="[A-Za-z\s]*" required>
            <label for="lastname">%s<span class="sutp-form-asterisk">*</span></label>
        </div>
        <div class="sutp-form-group">
            <input type="email" name="email" id="email" class="sutp-form-control" value="" required>
            <label for="email">%s<span class="sutp-form-asterisk">*</span></label>
        </div>
        <div class="form-field">
            <input type="submit" name="submit" value="%s"/>
        </div>
    </form>
    </div>
    ',
            esc_url($form_action_url),
            esc_attr($nonce),
            esc_html__('First Name', 'subscribe-for-pdf'),
            esc_html__('Last Name', 'subscribe-for-pdf'),
            esc_html__('Email', 'subscribe-for-pdf'),
            esc_html__('Register', 'subscribe-for-pdf')
        );
    }
    /**
     * Validate sign-up form data.
     *
     * @param string $firstName The user's first name.
     * @param string $lastName The user's last name.
     * @param string $email The user's email address.
     * @return bool True if validation passes, false if errors exist.
     */
    public function sign_up_validation($firstName, $lastName, $email)
    {
        $this->regErrors = new WP_Error;

        if (empty($firstName) || empty($lastName) || empty($email)) {
            $this->regErrors->add('field', __('Required form field is missing', 'subscribe-for-pdf'));
        }

        if (strlen($firstName) < 4) {
            $this->regErrors->add('firstname_length', __('First Name too short. At least 4 characters are required', 'subscribe-for-pdf'));
        }
        if (preg_match('/\d/', $firstName)) {
            $this->regErrors->add('firstname_numbers', __('First Name should not contain numbers.', 'subscribe-for-pdf'));
        }
        if (strlen($firstName) > 25) {
            $this->regErrors->add('firstname_length', __('First Name is too long. Maximum 25 characters allowed.', 'subscribe-for-pdf'));
        }

        if (preg_match('/\d/', $lastName)) {
            $this->regErrors->add('lastname_numbers', __('Last Name should not contain numbers.', 'subscribe-for-pdf'));
        }
        if (strlen($lastName) < 4) {
            $this->regErrors->add('lastname_length', __('Last Name too short. At least 4 characters are required.', 'subscribe-for-pdf'));
        }
        if (strlen($lastName) > 50) {
            $this->regErrors->add('lastname_length', __('Last Name is too long. Maximum 50 characters allowed.', 'subscribe-for-pdf'));
        }
        if (!is_email($email)) {
            $this->regErrors->add('email_invalid', __('Email is not valid.', 'subscribe-for-pdf'));
        }
        if (is_wp_error($this->regErrors) && count($this->regErrors->get_error_messages()) > 0) {
            foreach ($this->regErrors->get_error_messages() as $error) {
                printf('<div><strong>%s</strong>: %s</div>', esc_html__('ERROR', 'subscribe-for-pdf'), esc_html($error));
            }
            return false;
        }

        return true;
    }

    public function sign_up_registration($firstName, $lastName, $email)
    {
        if ($this->regErrors instanceof WP_Error && count($this->regErrors->get_error_messages()) > 0) {
            return false;
        }
        global $wpdb;
        $table_name = $wpdb->prefix . 'subscribe_for_pdf_subscribers';
        $submissionDate = date('Y-m-d H:i:s');
        $userData = array(
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'submission_date' => $submissionDate,
        );
        $wpdb->insert($table_name, $userData, array('%s', '%s', '%s', '%s'));

        $this->display_success_message();

        return true;
    }

    public function display_success_message()
    {
        printf('<div class="sutp-overlay"><div class="sutp-container"><h2 class="sutp-red">%s</h2></div></div>', esc_html__('You have successfully registered. Please wait, you will be redirected.', 'subscribe-for-pdf'));

        printf('<script>
            setTimeout(function () {
                location.href = location.href + "?result=success";
            }, 5000);
        </script>');
    }
}
