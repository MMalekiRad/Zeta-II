<?php
/**
 * Created by PhpStorm.
 * User: Mr OK
 * Date: 2/12/2019
 * Time: 21:10
 */

class AuthorConfigsNiceName
{
    public function __construct()
    {
        /**
         * Rewrite author base to custom
         *
         * @return void
         */
        function lwp_2610_author_base_rewrite()
        {
            global $wp_rewrite;
            $author_base_db = get_option('lwp_author_base');
            if (!empty($author_base_db)) {
                $wp_rewrite->author_base = $author_base_db;
            }
        }

        add_action('init', 'lwp_2610_author_base_rewrite');

        /**
         * Render textinput for Author base
         * Callback for the add_settings_function()
         *
         * @return void
         */
        function lwp_2610_author_base_render_field()
        {
            global $wp_rewrite;
            printf(
                '<input name="lwp_author_base" id="lwp_author_base" type="text" value="%s" class="regular-text code">',
                esc_attr($wp_rewrite->author_base)
            );
        }

        /**
         * Add a setting field for Author Base to the "Optional" Section
         * of the Permalinks Page
         *
         * @return void
         */
        function lwp_2610_author_base_add_settings_field()
        {
            add_settings_field(
                'lwp_author_base',
                esc_html__('ساختار برای پروفایل ها'),
                'lwp_2610_author_base_render_field',
                'permalink',
                'optional',
                array('label_for' => 'lwp_uthor_base')
            );
        }

        add_action('admin_init', 'lwp_2610_author_base_add_settings_field');

        /**
         * Sanitize and save the given Author Base value to the database
         *
         * @return void
         */
        function lwp_2610_author_base_update()
        {
            $author_base_db = get_option('lwp_author_base');

            if (isset($_POST['lwp_author_base']) &&
                isset($_POST['permalink_structure']) &&
                check_admin_referer('update-permalink')
            ) {
                $author_base = sanitize_title($_POST['lwp_author_base']);

                if (empty($author_base)) {
                    add_settings_error(
                        'lwp_author_base',
                        'lwp_author_base',
                        esc_html__('Invalid Author Base.'),
                        'error'
                    );
                } elseif ($author_base_db != $author_base) {
                    update_option('lwp_author_base', $author_base);
                }

            }
        }

        add_action('admin_init', 'lwp_2610_author_base_update');

        //==============================================================================================================

        /**
         * Start output buffering
         *
         * @return void
         */

        function lwp_2629_user_edit_ob_start()
        {
            ob_start();
        }

        add_action('personal_options', 'lwp_2629_user_edit_ob_start');

        /**
         * Insert a new textinput for Nicename below the Username row on User/Profile page
         *
         * @param object $user The current WP_User object.
         * @return void
         */
        function lwp_2629_insert_nicename_input($user)
        {
            if (Initializer::anjdf_is_admin()) {
                $content = ob_get_clean();

                // Find the proper class, try to be future proof
                $regex = '/<tr(.*)class="(.*)\buser-user-login-wrap\b(.*)"(.*)>([\s\S]*?)<\/tr>/';

                // HTML code of the table row
                $nicename_row = sprintf(
                    '<tr class="user-user-nicename-wrap"><th><label for="user_nicename">%1$s</label></th><td><input type="text"
                    name="user_nicename" id="user_nicename" value="%2$s" class="regular-text" />' . "\n" . '<span class="description">%3$s</span></td></tr>',
                    esc_html__('نام نویسندگی: '),
                    esc_attr($user->user_nicename),
                    esc_html__('اجباری است')
                );

                // Insert the row in the content
                echo preg_replace($regex, '\0' . $nicename_row, $content);
            }
        }

        add_action('show_user_profile', 'lwp_2629_insert_nicename_input');
        add_action('edit_user_profile', 'lwp_2629_insert_nicename_input');

        /**
         * Handle user profile updates
         *
         * @param object  &$errors Instance of WP_Error class.
         * @param boolean $update True if updating an existing user, false if saving a new user.
         * @param object  &$user User object for user being edited.
         * @return void
         */
        function lwp_2629_profile_update($errors, $update, $user)
        {

            // Return if not update
            if (!$update) return;
            if (is_admin() && Initializer::anjdf_is_admin()):
                if (empty($_POST['user_nicename'])) {
                    $errors->add(
                        'empty_nicename',
                        sprintf(
                            '<strong>%1$s</strong>: %2$s',
                            esc_html__('خطا رخ داده است.'),
                            esc_html__('نام نویسنده')
                        ),
                        array('form-field' => 'user_nicename')
                    );
                } else {
                    // Set the nicename
                    $user->user_nicename = $_POST['user_nicename'];
                }
            endif;
        }

        add_action('user_profile_update_errors', 'lwp_2629_profile_update', 10, 3);
    }


}