<?php
/**
 * @package Chrome on Android Meta Theme
 * @version 0.1
 */
/*
Plugin Name: Chrome on Android Meta Theme
Plugin URI: http://www.hellobrave.com
Description: Chrome on Android Meta Theme
Author: Brave
Version: 0.1
Author URI: http://www.hellobrave.com
*/
add_action('wp_head','wp_head_chrome_android_meta_theme');

function wp_head_chrome_android_meta_theme(){
	$meta_theme_color = get_option('chrome_android_meta_theme',array());
	$meta_theme_color['meta_theme_color'];
	if(isset($meta_theme_color['meta_theme_color']) && strlen($meta_theme_color['meta_theme_color'])>0){
		echo '<meta name="theme-color" content="'.$meta_theme_color['meta_theme_color'].'">';
	}
}



class chromeAndroidMetaThemeSettingsPage
{
    private $options;
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Chrome Meta Theme', 
            'manage_options', 
            'chrome-meta-theme', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'chrome_android_meta_theme' );
        ?>
        <div class="wrap">    
        	<?php screen_icon(); ?>  
        	<h2>Chrome Meta Theme</h2>   
            <form method="post" action="options.php">
            <?php
                settings_fields( 'chrome_android_meta_theme_group' );   
                do_settings_sections( 'chrome-meta-theme' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'chrome_android_meta_theme_group', // Option group
            'chrome_android_meta_theme', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'meta_theme_color_section_id', // ID
            '', // Title
            array( $this, 'print_section_info' ), // Callback
            'chrome-meta-theme' // Page
        );  

        add_settings_field(
            'meta_theme_color', // ID
            'Color', // Title 
            array( $this, 'meta_theme_color_callback' ), // Callback
            'chrome-meta-theme', // Page
            'meta_theme_color_section_id' // Section           
        );      

    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        
        if( isset( $input['meta_theme_color'] ) )
            $new_input['meta_theme_color'] = sanitize_text_field( $input['meta_theme_color'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your main theme color below. i.e. #dd4b39';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function meta_theme_color_callback()
    {
        printf(
            '<input type="text" id="meta_theme_color" name="chrome_android_meta_theme[meta_theme_color]" value="%s" />',
            isset( $this->options['meta_theme_color'] ) ? esc_attr( $this->options['meta_theme_color']) : ''
        );
    }

}

if( is_admin() )
    $chromeAndroidMetaThemeSettingsPage = new chromeAndroidMetaThemeSettingsPage();