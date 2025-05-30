<?php
/*
Plugin Name: Botsonic
Plugin URI: https://writesonic.com/botsonic
Description: Embed your Botsonic chatbot on any Wordpress website
Version: 1.0.0
Author: Writesonic
Author URI: https://writesonic.com
*/



// Add the settings page to the admin menu
function botsonic_add_settings_page() {
    add_options_page( 'Botsonic Plugin Settings', 'Botsonic Settings', 'manage_options', 'botsonic-script-plugin', 'botsonic_settings_page' );
}
add_action( 'admin_menu', 'botsonic_add_settings_page' );

// Callback function to create the plugin settings page
function botsonic_settings_page() {
    ?>
    <div class="wrap">
        <h1>Botsonic Widget Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'botsonic_script_plugin_options' ); ?>
            <?php do_settings_sections( 'botsonic_script_plugin' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register the plugin settings
function botsonic_register_settings() {
    add_settings_section( 'botsonic_script_plugin_section', 'Botsonic Widget Plugin Settings', '', 'botsonic_script_plugin' );

    add_settings_field( 'botsonic_token', 'Widget Token', 'botsonic_token_callback', 'botsonic_script_plugin', 'botsonic_script_plugin_section' );

    register_setting( 'botsonic_script_plugin_options', 'botsonic_service_base_url', 'esc_url_raw' );
    register_setting( 'botsonic_script_plugin_options', 'botsonic_token', 'sanitize_text_field' );
}
add_action( 'admin_init', 'botsonic_register_settings' );

// Callback function to display the Service Base URL field
function botsonic_service_base_url_callback() {
    $service_base_url = get_option( 'botsonic_service_base_url' );
    echo '<input type="text" name="botsonic_service_base_url" value="' . esc_attr( $service_base_url ) . '" />';
}

// Callback function to display the Token field
function botsonic_token_callback() {
    $token = get_option( 'botsonic_token' );
    echo '<input type="text" name="botsonic_token" value="' . esc_attr( $token ) . '" />';
    echo '<p class="description">Note: You can find your token in the share section of your bot.</p>';
}

// Function to insert the Botsonic script
function botsonic_insert_script() {
    $service_base_url = get_option( 'botsonic_service_base_url' );
    $token = get_option( 'botsonic_token' );

    echo '<script>
      (function (w, d, s, o, f, js, fjs) {
        w["botsonic_widget"] = o;
        w[o] =
          w[o] ||
          function () {
            (w[o].q = w[o].q || []).push(arguments);
          };
        (js = d.createElement(s)), (fjs = d.getElementsByTagName(s)[0]);
        js.id = o;
        js.src = f;
        js.async = 1;
        fjs.parentNode.insertBefore(js, fjs);
      })(window, document, "script", "Botsonic", "https://widget.writesonic.com/CDN/botsonic.min.js");
      Botsonic("init", {
        serviceBaseUrl: "https://api.botsonic.ai",
        token: "' . esc_js(9de7ea72-3687-4103-980e-3778531a52a7) . '",
      });
    </script>';
}
add_action( 'wp_footer', 'botsonic_insert_script' );
