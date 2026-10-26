<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Admin Dashboard Pages class
*/

class RB_UC_AdminDashboardPages
{
    private $after_head = "<?php do_action('rb_uc_after_head'); ?>";
    private $after_body = "<?php do_action('rb_uc_after_body'); ?>";

   function __construct()
    {
        add_action( 'admin_menu', [ $this, 'rb_uc_register_admin_menu_page'] ) ;
        add_action( 'admin_enqueue_scripts', [ $this, 'rb_uc_admin_assets'] );
        add_action( 'wp_ajax_rb_uc_actions_adding', [ $this, 'rb_uc_actions_adding_callback'], 10 );
    }

    function rb_uc_register_admin_menu_page()
    {
        add_submenu_page( 'options-general.php', 'RB User Consent', 'RB User Consent', 'manage_options', 'rb-user-consent', [$this, 'rb_uc_tracking_code_callback'], 10 );
    }

    function rb_uc_tracking_code_callback()
    {
        $save_settings  = $this->rb_uc_saving_form_settings();
        $update_options = $this->rb_uc_update_plugin_options();

        $forms_values   = $this->rb_uc_get_forms_values();

        $header_content = $this->rb_uc_get_theme_header_file(); 

        $html = '<div class="wrap">';
            $html .= '<h2>'. get_admin_page_title() .'</h2>';
            $html .= '<form action="'.htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'utf-8').'?page=rb-user-consent&update=true" method="POST" id="rb_uc_save_form_settings">';
                if ( function_exists('wp_nonce_field') ) {
                    $html .= wp_nonce_field('rb_uc_save_form_settings');
                }

                $html .= '<p>Code after<strong><code>'.htmlspecialchars('<head>').'</code></strong>tag:</br>';
                $html .= 'For using this field you need to edit your template files and add the following line just after the opening <strong><code>'.htmlspecialchars('<head>').'</code></strong> tag</br><code>&lt;?php do_action(\'rb_uc_after_head\'); ?&gt;</code></p>';
                $installed = $this->rb_uc_check_if_action_installed($header_content, $this->after_head);
                if ( $installed )
                    $html .= '<p class="status-success">rb_uc_after_head action detected in header.php</p>';
                else
                    $html .= '<p class="status-warning">rb_uc_after_head action not detected in header.php</p>';

                $html .= '<p><textarea name="rb_uc_form_after_head">'.$forms_values['form_after_head'].'</textarea></p>';

                $html .= '<p>Code after<strong><code>'.htmlspecialchars('<body>').'</code></strong>tag:</br>';
                $html .= 'For using this field you need to edit your template files and add the following line just after the opening <strong><code>'.htmlspecialchars('<body>').'</code></strong> tag</br><code>&lt;?php do_action(\'rb_uc_after_body\'); ?&gt;</code></p>';

                $installed = $this->rb_uc_check_if_action_installed($header_content, $this->after_body);
                if ( $installed )
                    $html .= '<p class="status-success">rb_uc_after_body action detected in header.php</p>';
                else
                    $html .= '<p class="status-warning">rb_uc_after_body action not detected in header.php</p>';

                $html .= '<p><textarea name="rb_uc_form_after_body">'.$forms_values['form_after_body'].'</textarea></p>';

                $html .= '<p>Code in <strong>'.htmlspecialchars('<footer>').'</strong> tag:</p>';
                $html .= '<p><textarea name="rb_uc_form_in_footer">'.$forms_values['form_in_footer'].'</textarea></p>';

                $html .= '<input name="rb_uc_save_form_settings" id="rb_uc_save_form_settings_submit" class="button button-primary" value="Save Changes" type="submit">'; 
            $html .= '</form>';

            $html .= '<h2>Update plugin options</h2>';
            $html .= '<p>You can update Beautiful and responsive cookie plugin options, no need to copy settings manually</p>';
            $html .= '<form action="'.htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'utf-8').'?page=rb-user-consent&update=true" method="POST" id="rb_uc_update_plugin_options">';
                if ( function_exists('wp_nonce_field') ) {
                    $html .= wp_nonce_field('rb_uc_update_plugin_options');
                }
                
                $page = site_url('/vare-retningslinjer-for-informasjonskapsler/');
                $color_page = admin_url( 'options-general.php?page=nsc_bar-cookie-consent&tab=banner_colors' );


                $html .= '<p>1. First of all create a page <code>'.$page.'</code> with next <a target="_blank" href="https://devtemp.no/repository/risingbear-user-consent/gdpr-text.html">content</a>. Don\'t forget to change the company name and email in the text</p>';
                $html .= '<p>2. Click the <strong>"Update options"</strong> button. All necessary settings are automatically added for Beautiful and responsive cookie plugin. You can change the color scheme of popup <a href="'.$color_page.'">here</a> if needed</p>';

                $html .= '<input name="rb_uc_update_plugin_options" id="rb_uc_update_plugin_options_submit" class="button button-primary" value="Update options" type="submit">'; 

                $html .= '<p class="success">'.$update_options.'</p>';
            $html .= '</form>';

            $html .= '<h2>Actions adding</h2>';
            $html .= '<p>You can try install actions automatically. The actions will be added in the header.php file of your current theme.</p>';
            
            $html .= '<form action="'.htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'utf-8').'?page=rb-user-consent&update=true" method="POST" id="rb_uc_actions_adding">';
                if ( function_exists('wp_nonce_field') ) {
                    $html .= wp_nonce_field('rb_uc_actions_adding');
                }

                $html .= '<input name="rb_uc_actions_adding" id="rb_uc_actions_adding_submit" class="button button-primary" value="Add actions" type="submit">';
            $html .= '</form>';
            $html .= '<p class="actions-answers"></p>';
        $html .= '</div>';

        echo $this->rb_uc_tracking_code_admin_styles();

        echo $html;
    }

    private function rb_uc_saving_form_settings()
    {
		if ( isset($_POST['rb_uc_save_form_settings']) ) {
			if ( function_exists('check_admin_referer') ) {
				check_admin_referer('rb_uc_save_form_settings');
			}
            
            $forms = [];

            if ( !empty($_POST['rb_uc_form_after_head']) )
                $forms['form_after_head'] = $_POST['rb_uc_form_after_head'];
            else
                $forms['form_after_head'] = '';

            if ( !empty($_POST['rb_uc_form_after_body']) )
                $forms['form_after_body'] = $_POST['rb_uc_form_after_body'];
            else
                $forms['form_after_body'] = '';

            if ( !empty($_POST['rb_uc_form_in_footer']) )
                $forms['form_in_footer'] = $_POST['rb_uc_form_in_footer'];
            else
                $forms['form_in_footer'] = '';

            update_option( 'rb_uc_forms_values', $forms );

			return true;
		}
    }
    private function rb_uc_update_plugin_options()
    {
        global $wpdb;

		if ( isset($_POST['rb_uc_update_plugin_options']) ) {
			if ( function_exists('check_admin_referer') ) {
				check_admin_referer('rb_uc_update_plugin_options');
			}

            $href = site_url('/vare-retningslinjer-for-informasjonskapsler/');
            
            $bannersettings_json = '{"content":{"deny":"Deny","dismiss":"Got it","allow":"Ok, forst\u00e5tt","link":"Les mer","href":"'.$href.'","message":"Ved \u00e5 trykke p\u00e5 \"Ok, forst\u00e5tt\", godtar du lagring av f\u00f8rste- og tredjeparts informasjonskapsler p\u00e5 enheten din, for \u00e5 forbedre navigeringen p\u00e5 nettsiden, analysering av bruken p\u00e5 nettsiden innsikt til v\u00e5re markedsf\u00f8ringstiltak. Vennligst se v\u00e5re Retningslinjer for Informasjonskapsler for mer informasjon om hvordan vi bruker informasjonskapsler og hvordan du kan h\u00e5ndtere dem.","policy":"Innstillinger for informasjonskapsler","savesettings":"Lagre innstillinger","linksecond":"Just another Link","hrefsecond":"https:\/\/yourdomain\/anotherLink"},"type":"detailedRev","palette":{"popup":{"background":"#448320","text":"#fff"},"button":{"background":"#fff","text":"#448320","border":""},"switches":{"background":"","backgroundChecked":"","switch":"","text":""}},"position":"bottom-right","theme":"classic","cookietypes":[{"label":"N\u00f8dvendige cookies","checked":"checked","disabled":"disabled","cookie_suffix":"required"},{"label":"Informasjonskapsler for publikumsmåling","checked":"","disabled":"","cookie_suffix":"audience"},{"label":"M\u00e5lrettede informasjonskapsler","checked":"","disabled":"","cookie_suffix":"targeted"}],"revokable":"0","dataLayerName":"dataLayer","container":"","customizedFont":"","makeButtonsEqual":"1","setDiffDefaultCookiesFirstPV":"0","blockScreen":"0","disableWithiniFrames":"0"}';


            $check_settings = $wpdb->get_var("SELECT option_id FROM $wpdb->options WHERE option_name = 'nsc_bar_bannersettings_json'");
            if ( !$check_settings ) {
                $wpdb->insert( $wpdb->options, [ 'option_value' => $bannersettings_json, 'option_name' => 'nsc_bar_bannersettings_json' ] );
            } else {
                $wpdb->update( $wpdb->options,
                    [ 'option_value' => $bannersettings_json ],
                    [ 'option_name' => 'nsc_bar_bannersettings_json' ]
                );
            }

            if ( $wpdb->last_error )
                wp_die('wpdb error: ' . $wpdb->last_error);

            $activate_banner = $wpdb->get_var("SELECT option_id FROM $wpdb->options WHERE option_name = 'nsc_bar_activate_banner'");
            if ( !$activate_banner ) {
                $wpdb->insert( $wpdb->options, [ 'option_value' => 1, 'option_name' => 'nsc_bar_activate_banner' ] );
            } else {
                $wpdb->update( $wpdb->options,
                    [ 'option_value' => 1 ],
                    [ 'option_name' => 'nsc_bar_activate_banner' ]
                );
            }

            if ( $wpdb->last_error )
                wp_die('wpdb error: ' . $wpdb->last_error);

            
            return 'Beautiful and responsive cookie consent plugin options updated';
		}
    }

    private function rb_uc_tracking_code_admin_styles()
    {
        $styles = '
            <style>
                textarea {
                    min-width: 700px;
                    height: 220px;
                }
                .success {color:green}
                li.warning {color:red}
                li.success {color:green}
                .status-success,
                .status-warning {
                    display: inline;
                    padding: 10px 20px;
                    color: #721c24;
                    background-color: #f8d7da;
                    border-color: #f5c6cb;
                }
                .status-success {
                    color: #155724;
                    background-color: #d4edda;
                    border-color: #c3e6cb;
                }
            </style>
        ';

        return $styles;
    }

    public static function rb_uc_get_forms_values()
    {
        $forms = [];
        $forms_values = get_option( 'rb_uc_forms_values' );
        $forms['form_after_head'] = ( isset($forms_values['form_after_head']) ) ? stripcslashes($forms_values['form_after_head']) : '';
        $forms['form_after_body'] = ( isset($forms_values['form_after_body']) ) ? stripcslashes($forms_values['form_after_body']) : '';
        $forms['form_in_footer']  = ( isset($forms_values['form_in_footer']) ) ? stripcslashes($forms_values['form_in_footer']) : '';

        return $forms;
    }

    function rb_uc_actions_adding_callback()
    {
        if ( function_exists('check_admin_referer') ) {
            check_admin_referer('rb_uc_actions_adding');
        }
        $answer = $this->rb_uc_maybe_add_actions_in_header();

        wp_send_json($answer);
    }

    private function rb_uc_maybe_add_actions_in_header()
    {
        $head_pos = 0;
        $body_pos = 0;

        $answer = [];

        $header_content = $this->rb_uc_get_theme_header_file(); 
       
        // check if action already added
        $action_pos = $this->rb_uc_check_if_action_installed($header_content, $this->after_head);
        if ( !$action_pos ) {
            $tag = '<head>';
            $head_pos = strpos($header_content, $tag) + strlen($tag);
            if( $head_pos > strlen($tag)) {
                $header_content = substr($header_content, 0, $head_pos)."\r\n    ".$this->after_head."\r\n" . substr($header_content, $head_pos);
                $answer[0]['status']  = 'success';
                $answer[0]['message'] = 'added "rb_uc_after_head" action';
            }
        } else {
            $answer[1]['status']  = 'warning';
            $answer[1]['message'] = 'action "rb_uc_after_head" already added';
        }
        
        // check if action already added
        $action_pos = $this->rb_uc_check_if_action_installed($header_content, $this->after_body);
        if ( !$action_pos ) {
            $action_pos = strpos($header_content, $this->after_body);
            if ( !$action_pos ) {
                preg_match('/<body(.*)>/', $header_content, $matches, PREG_OFFSET_CAPTURE);
                if ( $matches ) {
                    $tag = $matches[0][1];
                    $body_pos = $tag + strlen($matches[0][0]);
                    $header_content = substr($header_content, 0, $body_pos)."\r\n".$this->after_body."\r\n" . substr($header_content, $body_pos);
                    $answer[2]['status']  = 'success';
                    $answer[2]['message'] = 'added "rb_uc_after_body" action';
                }
            }
        } else {
            $answer[3]['status']  = 'warning';
            $answer[3]['message'] = 'action "rb_uc_after_body" already added';
        }
       
        if ( $head_pos > 0 || $body_pos > 0  )
            $this->rb_uc_write_theme_header_file($header_content);

        return $answer;
    }

    function rb_uc_admin_assets($hook)
    {
        if ($hook == 'settings_page_rb-user-consent')
            wp_enqueue_script( 'rb_uc_consent-scripts', RB_UC_PLUGIN_URL . '/assets/js/consent-scripts.js', false, false, 'all' );
    }

    private function rb_uc_get_theme_header_file()
    {
        $theme_path = get_stylesheet_directory();
        $file_header = $theme_path . '/header.php';

        return file_get_contents($file_header);
    }

    private function rb_uc_write_theme_header_file($content)
    {
        $theme_path = get_stylesheet_directory();
        $file_header = $theme_path . '/header.php';

        return file_put_contents($file_header, $content);
    }

    private function rb_uc_check_if_action_installed($content, $action)
    {
        return strpos($content, $action); 
    }
}

new RB_UC_AdminDashboardPages();