<?php
function fallacy_detector_register_api_routes() {
  register_rest_route(
    'fallacy-detector/v1',
    '/detect',
    array(
      'methods' => WP_REST_Server::CREATABLE,  
      'callback' => 'fallacy_detector_api_endpoint',
      'permission_callback' => 'fallacy_detector_jwt_validate', 
    )
  );
}
add_action('rest_api_init', 'fallacy_detector_register_api_routes');

function fallacy_detector_jwt_validate(WP_REST_Request $request) {
  
}

function fallacy_detector_api_endpoint(WP_REST_Request $request) {
  $params = $request->get_json_params();
  $user_prompt = sanitize_text_field($params['user_input']);

  //  $userInput = $_POST['userInput'] ?? '';
  $backend_user_prompt = get_option('fallacy_detector_user_prompt');

  $gpt_version = get_option('fallacy_detector_gpt_version');

  // Retrieve saved settings from database
  $api_key = get_option('fallacy_detector_api_key');
  $system_prompt = get_option('fallacy_detector_system_prompt');

  // Add the chat assistant prompt as a system message at the beginning of the conversation
  $messages = [['role' => 'system', 'content' => $system_prompt]];
  $messages[] = ['role' => 'user', 'content' => $backend_user_prompt . ' ' . $user_prompt];

  // Prepare OpenAI API URL
  $api_url = 'https://api.openai.com/v1/chat/completions';

  // Prepare data payload for OpenAI API
  $data_payload = [
    'model' => $gpt_version,
    'messages' => $messages,
    'max_tokens' => 2000,  
    'temperature' => 0.7,  
  ];

  // Call OpenAI API with user input and saved settings
  $response = wp_remote_post(
    $api_url,
    array(
      'headers' => array(
        'Authorization' => 'Bearer ' . $api_key,
        'Content-Type' => 'application/json',
      ),
      'body' => json_encode($data_payload),
    )
  );

  // Process the OpenAI API response and return it
  if (is_wp_error($response)) {
    return new WP_Error('openai_error', 'Error contacting OpenAI API', array('status' => 500));
  }

  $body = wp_remote_retrieve_body($response);
  $data = json_decode($body);
  return new WP_REST_Response($data, 200);
}
