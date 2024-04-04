<?php
/**
 * Plugin Name: Fallacy Detector API
 * Description: Connects WordPress to OpenAI to detect fallacies in user input.
 * Version: 1.0
 * Author: David Repasky
 * Text Domain: fallacy-detector-api
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Include the settings page and REST API endpoint logic.
require_once plugin_dir_path(__FILE__) . 'settings-page.php';
require_once plugin_dir_path(__FILE__) . 'api-endpoint.php';

// Code to run during plugin activation (e.g., create options with default values).
function fallacy_detector_activate() {
    add_option('fallacy_detector_api_key', '');
    add_option('fallacy_detector_gpt_version', 'gpt-3.5-turbo');
    add_option('fallacy_detector_system_prompt', '');
    add_option('fallacy_detector_user_prompt', '');
}
register_activation_hook(__FILE__, 'fallacy_detector_activate');

// Code to run during plugin deactivation (e.g., clean up options).
function fallacy_detector_deactivate() {
    delete_option('fallacy_detector_api_key');
    delete_option('fallacy_detector_gpt_version');
    delete_option('fallacy_detector_system_prompt');
    delete_option('fallacy_detector_user_prompt');
}
register_deactivation_hook(__FILE__, 'fallacy_detector_deactivate');
