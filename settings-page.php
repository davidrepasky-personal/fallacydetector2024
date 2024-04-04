<?php
// Add a menu item for the settings page under the "Settings" menu for admins
function fallacy_detector_add_admin_menu() {
    add_options_page(
        'Chatbot Settings',
        'Chatbot Settings',
        'manage_options',
        'fallacy_detector',
        'fallacy_detector_settings_page'
    );
}

// Initialize the settings section, fields, and settings
function fallacy_detector_settings_init() {
    register_setting('fallacy_detector', 'fallacy_detector_api_key');
    register_setting('fallacy_detector', 'fallacy_detector_gpt_version');
    register_setting('fallacy_detector', 'fallacy_detector_system_prompt');
    register_setting('fallacy_detector', 'fallacy_detector_user_prompt');

    add_settings_section(
        'fallacy_detector_fallacy_detector_section',
        __('Choose the settings for the OpenAI API interface.', 'fallacy_detector'),
        'fallacy_detector_settings_section_callback',
        'fallacy_detector'
    );

    add_settings_field(
        'fallacy_detector_api_key',
        __('API Key', 'fallacy_detector'),
        'fallacy_detector_api_key_render',
        'fallacy_detector',
        'fallacy_detector_fallacy_detector_section'
    );

    add_settings_field(
        'fallacy_detector_gpt_version',
        __('GPT Version', 'fallacy_detector'),
        'fallacy_detector_gpt_version_render',
        'fallacy_detector',
        'fallacy_detector_fallacy_detector_section'
    );

    add_settings_field(
        'fallacy_detector_system_prompt',
        __('System Prompt', 'fallacy_detector'),
        'fallacy_detector_system_prompt_render',
        'fallacy_detector',
        'fallacy_detector_fallacy_detector_section'
    );

    add_settings_field(
        'fallacy_detector_user_prompt',
        __('User Prompt', 'fallacy_detector'),
        'fallacy_detector_user_prompt_render',
        'fallacy_detector',
        'fallacy_detector_fallacy_detector_section'
    );
}

// Render the text field for API Key input
function fallacy_detector_api_key_render() {
    $api_key = get_option('fallacy_detector_api_key');
    ?>
    <input type='text' name='fallacy_detector_api_key' value='<?php echo esc_attr($api_key); ?>'>
    <?php
}

// Render the select field for GPT Version
function fallacy_detector_gpt_version_render() {
    $gpt_version = get_option('fallacy_detector_gpt_version');
    ?>
    <select name='fallacy_detector_gpt_version'>
        <option value='gpt-3.5-turbo' <?php selected($gpt_version, 'gpt-3.5-turbo'); ?>>GPT-3.5-Turbo</option>
        <option value='gpt-4' <?php selected($gpt_version, 'gpt-4'); ?>>GPT-4</option>
        <option value='gpt-4-turbo' <?php selected($gpt_version, 'gpt-4-turbo'); ?>>GPT-4-Turbo</option>
    </select>
    <?php
}

// Render the textarea for System Prompt
function fallacy_detector_system_prompt_render() {
    $system_prompt = get_option('fallacy_detector_system_prompt');
    ?>
    <textarea cols='40' rows='5' name='fallacy_detector_system_prompt'><?php echo esc_attr($system_prompt); ?></textarea>
    <?php
}

// Render the textarea for User Prompt
function fallacy_detector_user_prompt_render() {
    $user_prompt = get_option('fallacy_detector_user_prompt');
    ?>
    <textarea cols='40' rows='5' name='fallacy_detector_user_prompt'><?php echo esc_attr($user_prompt); ?></textarea>
    <?php
}

// Settings section callback
function fallacy_detector_settings_section_callback() {
    echo __('Set the API key and choose the default GPT version for your chatbot.', 'fallacy_detector');
}

// Settings page content
function fallacy_detector_settings_page() {
    ?>
    <form action='options.php' method='post'>
        
        <h2>Fallacy Detector API</h2>
        
        <?php
        settings_fields('fallacy_detector');
        do_settings_sections('fallacy_detector');
        submit_button();
        ?>
        
    </form>
    <?php
}

// Hooks and actions to initialize the settings page
add_action('admin_menu', 'fallacy_detector_add_admin_menu');
add_action('admin_init', 'fallacy_detector_settings_init');
