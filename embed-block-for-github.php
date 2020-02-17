<?php
/**
 * @link              https://jeanbaptisteaudras.com
 * @since             0.1
 * @package           Embed Block for GitHub
 *
 * Plugin Name:       Embed Block for GitHub
 * Plugin URI:        https://jeanbaptisteaudras.com/embed-block-for-github-gutenberg-wordpress/
 * Description:       Easily embed GitHub repositories in Gutenberg Editor.
 * Version:           0.3
 * Author:            audrasjb
 * Author URI:        https://jeanbaptisteaudras.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       embed-block-for-github
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class embed_block_for_github {

	private $dev_mode = false;

	private function msgdebug ($msg) {
		//$this->msgdebug("PAHT:".plugin_dir_path( __FILE__ ));
		error_log("DEBUG: ".$msg, 0);
	}

	public function __construct() {
		add_action( 'init', array( $this, 'init_wp_register' ) );
	}

	public function init_wp_register() {
		wp_register_script(
			'ebg-repository-editor',
			$this->plugin_url('repository-block.js'),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ),
			$this->plugin_file_ver('repository-block.js')
		);
		wp_register_style(
			'ebg-repository-editor',
			$this->plugin_url('repository-block-editor.css'),
			array(),
			$this->plugin_file_ver('repository-block.css')
		);
		wp_register_style(
			'ebg-repository',
			$this->plugin_url('repository-block.css'),
			array(),
			$this->plugin_file_ver('repository-block.css')
		);
		register_block_type( 'embed-block-for-github/repository', array(
			'editor_script'   => 'ebg-repository-editor',
			'editor_style'    => 'ebg-repository-editor',
			'style'           => 'ebg-repository',
			'render_callback' => array( $this, 'ebg_embed_repository' ),
			'attributes'      => array(
				'github_url' => array( 'type' => 'string' ),
				'darck_mode' => array( 'type' => 'boolean' ),
			),
		) );
	}

	/* Get Path install plugin */
	private function plugin_path(){
		return plugin_dir_path( __FILE__ );
	}

	/* Get Path install plugin and file name. */
	private function plugin_file($file){
		if (strlen(trim($file)) > 0) {
			return $this::plugin_path() . $file;
		}
		return "";
	}

	/* Get version of the file using modified date. */
	private function plugin_file_ver($file) {
		return filemtime( $this::plugin_file($file) );
	}

	/* Get folder name plugin */
	private function plugin_name() {
		return basename( dirname( __FILE__ ) );
	}

	/* Get Url Plugin */
	private function plugin_url($file) {
		if (strlen(trim($file)) > 0) {
			return plugins_url( $file, __FILE__ );
		}
		return "";
	}

	/* Message according to the error received from GitHub. */
	private function check_message($message, $documentation_url) {
		if ($message == "Not Found") {
			return '<p>' . esc_html__( 'Repository not found. Please check your URL.', 'embed-block-for-github' ) . '</p>';
		}
		elseif ( strpos( $message, 'API rate limit exceeded for ' ) === 0 )
		{
			return '<p>' . esc_html__( 'Sorry, API Github rate limit exceeded for IP. Please try again later.', 'embed-block-for-github' ) . '</p>';
		}
		else 
		{
			return '<p>' . esc_html( sprintf( 'Error: %s', $message ) , 'embed-block-for-github' ) . '</p>';
		}
	}

	/* All messages shown to the user. */
	private function message($message, $arg = array()) {
		$msg_return = "";
		switch($message) {
			case "url_is_null":
				$msg_return = '<p>' . esc_html__( 'Use the Sidebar to add the URL of the GitHub Repository to embed.', 'embed-block-for-github' ) . '</p>';
			break;

			case "url_not_valid":
				$msg_return = '<p>' . esc_html__( 'The specified URL is not valid. Check the address using the sidebar to add the repository URL.', 'embed-block-for-github' ) . '</p>';				
			break;

			case "url_not_github":
				$msg_return = '<p>' . esc_html__( 'The specified URL is not from GitHub. Check the address using the sidebar to add the correct GitHub repository URL (only https allowed).', 'embed-block-for-github' ) . '</p>';
			break;

			case "info_no_available":
				$msg_return = '<p>' . esc_html__( 'No information available. Please check your URL.', 'embed-block-for-github' ) . '</p>';
			break;
		}
		return $msg_return;
	}

	private function transient_id($prefix = "", $postfix = "") {
		$plugin_data = get_plugin_data( __FILE__ );
		$plugin_version = $plugin_data['Version'];

		$id = "_ebg_repository_".$plugin_version."_";
		if (! empty($prefix)) 	{ $id = "_".$prefix.$id; }
		if (! empty($postfix)) 	{ $id = $id.$postfix."_"; }
		return $id;
	}

	/* Check if the URL is correct */
	private function check_github_url($github_url) {
		switch(True) {
			case ( '' === trim( $github_url ) ):
				return "url_is_null";
			break;

			case (! filter_var( $github_url, FILTER_VALIDATE_URL ) ):
				return "url_not_valid";
			break;
			
			case ( strpos( $github_url, 'https://github.com/' ) !== 0 ):
				return "url_not_github";
			break;
		}
		return "";
	}

	/* Detect type request (user, repo, etc...) */
	private function detect_request($github_url) {
		$slug = str_replace( 'https://github.com/', '', $github_url );
		
		$data_return = [];
		switch ( count(explode("/", $slug)) )
		{
			case 1:
				/* User */
				$data_return['request'] = wp_remote_get( 'https://api.github.com/users/' . explode("/", $slug)[0] );
				$data_return['type'] = "user";
				break;

			case 2:
				/* Repo */
				$data_return['request'] = wp_remote_get( 'https://api.github.com/repos/' . $slug );
				$data_return['type'] = "repo";
				break;

			default:
				/* ??? */
				/*
				$data_return['request'] = "";
				$data_return['type'] = "";
				*/
				$data_return = NULL;
		}
		return $data_return;

	}


	public function ebg_embed_repository( $attributes ) {
		$github_url = trim( $attributes['github_url'] );
		$darck_mode = (in_array("darck_mode", $attributes) ? $attributes['darck_mode'] : false);

		$transient_id = $this::transient_id("", sanitize_title_with_dashes( $github_url ) );
		$transi = new embed_block_for_github_transient($transient_id, true);
		
		/* We check and validate the propiedases are good. */
		$error['type'] = $this::check_github_url($github_url);
		
		/* If no errors have been detected, we obtain the data from github. */
		if (empty($error['type']))
		{
			/* DEV: CLEAN TRANSIENT */
			if ($this->dev_mode) {
				$transi->delete(true);
			}
			/* DEV: CLEAN TRANSIENT */
			
			if (! $transi->isExist() )
			{
				$data_all = $this::detect_request($github_url);

				if (! is_null( $data_all ) )
				{
					$body = wp_remote_retrieve_body( $data_all['request'] );
					$data_all['data'] = json_decode( $body );

					if (! is_wp_error( $response ) ) {
						$transi->set($data_all);
					} else {
						$error['type'] = "info_no_available";
						//$response->get_error_message()
					}
					unset($body);
				} else 
				{
					$error['type'] = "url_not_valid";
				}
			}

			if (empty($error['type'])) 
			{
				$data_all = $transi->get();

				if (isset($data_all->data)) 
				{
					/* We check if any error has been received from github. */
					if (isset( $data_all->data->message ) )
					{
						$error['type'] = "get_error_from_github";
						$error['msg_custom'] =  $this::check_message($data_all->data->message, $data_all->data->documentation_url);
					}

					/* If all went well, we loaded the template and generated the replacements. */
					$content = $this::template_generate_info($data_all, $a_remplace);
					if (is_null($content)) {
						$error['type'] = "url_not_valid";
					}
				}

				unset($data_all);
			}
		}

		/* If there is an error, we prepare the error message that has been detected. */
		if (! empty($error['type'])) {
			/* Clean Transient is error detected. */
			$transi->delete(true);

			$content = $this::template_file_require('msg-error.php');
			$a_remplace['%%_ERROR_TITLE_%%'] = "ERROR";
			if (empty($error['msg_custom'])) {
				$a_remplace['%%_ERROR_MESSAGE_%%'] = $this::message($error['type']);
			} else {
				$a_remplace['%%_ERROR_MESSAGE_%%'] = $error['msg_custom'];
			}
		}
		unset ($transi);

		/* If "$content" is not empty, we execute the replaces in the template. */
		if (! empty($content)) {
			$a_remplace['%%_WRAPPER_DARK_MODE_%%'] = "ebg-br-wrapper-dark-mode-" . ($darck_mode ? "on" : "off");
			$a_remplace['%%_URL_ICO_LINK_%%'] = $this::plugin_url("images/link.svg");

			foreach ($a_remplace as $key => $val) {
				$content = str_replace($key, $val, $content);
			}
			return $content;
		}
	}





	private function template_file_require( $template, $data = array() ) {
		ob_start();
		if ( ! locate_template( $this->plugin_name() . '/' . $template, true, false) ) {
			$filename = $this::plugin_path() . 'templates/' . $template;
			if (! file_exists( $filename ) ) {
				return NULL;
			}
			require $filename;
		}
		return ob_get_clean();
	}

	private function template_collect_values_to_replace($data, $prefix_text, &$a_remplace) {
		foreach ($data as $key => $value) {
			$new_prefix_text = $prefix_text."_".strtoupper($key);
			//echo "Debug >> Key:". $key . " - Valor Tipo:" . gettype($value) . "<br>";
			if (is_object($value)) {
				$this->{__FUNCTION__}($value, $new_prefix_text, $a_remplace);
			} else {
				$a_remplace[$new_prefix_text.'_%%'] = $value;
				$a_remplace[$new_prefix_text.'_%_CLASS_HIDE_IS_NULL_%%'] = (empty($value) ? "ebg-br-hide-is-null": "");
			}
		}
	}
	
	private function template_generate_info($data_all, &$a_remplace) {
		// https://api.github.com/users/vsc55
		// https://api.github.com/repos/vsc55/embed-block-for-github

		$name_file = 'info-'.strtolower($data_all->type).'.php';
		$content = $this::template_file_require($name_file, $data_all->data);
		if ( (! is_null($content)) && (! empty($content)) ) 
		{
			switch(strtolower($data_all->type))
			{
				case "user":
					$a_remplace['%%_CUSTOM_DATA_USER_CREATED_AT_ONLY_DATE_%%'] = date_format( date_create( $data_all->data->created_at ), 'd/m/Y');
					$a_remplace['%%_CUSTOM_DATA_USER_CREATED_AT_ONLY_DATE_%_CLASS_HIDE_IS_NULL_%%'] = (empty($data_all->data->created_at) ? "ebg-br-hide-is-null": "");
					$a_remplace['%%_CUSTOM_DATA_USER_UPDATED_AT_ONLY_DATE_%%'] = date_format( date_create( $data_all->data->updated_at ), 'd/m/Y');
					$a_remplace['%%_CUSTOM_DATA_USER_UPDATED_AT_ONLY_DATE_%_CLASS_HIDE_IS_NULL_%%'] = (empty($data_all->data->updated_at) ? "ebg-br-hide-is-null": "");
					break;
				case "repo":
					break;
			}
			$this::template_collect_values_to_replace($data_all->data, "%%_DATA_".strtoupper($data_all->type), $a_remplace);		
		}
		return $content;
	}

}

require_once( 'embed_block_for_github_transient.php' );

$embed_block_for_github = new embed_block_for_github();