<?php
/*
Plugin Name: PluginBuddy S3 URLs
Plugin URI: http://pluginbuddy.com/free-wordpress-plugins/s3/
Description: Allows you to insert an S3 Link into a post or page
Author: Ronald Huereca
Version: 0.1.0
Requires at least: 3.0
Author URI: http://www.ronalfy.com/
*/ 

if (!class_exists('pluginbuddy_s3')) {
    class pluginbuddy_s3	{	
		//private
		private $admin_options = array();
		private $plugin_url = '';
		private $plugin_dir = '';
		private $plugin_info = array();
		
		/**
		* __construct()
		* 
		* Class constructor
		*
		*/
		function __construct(){
			
			//Initialize plugin information
			$this->plugin_info = array(
				'slug' => 'pluginbuddy_s3',
				'version' => '0.1.0',
				'name' => 'PluginBuddy S3',
				'url' => 'http://pluginbuddy.com/free-wordpress-plugins/s3/',
				'locale' => 'LION',
				'path' => plugin_basename( __FILE__ )
			);
			
			$this->admin_options = $this->get_admin_options();
			$this->plugin_url = rtrim( plugin_dir_url(__FILE__), '/' );
			$this->plugin_dir = rtrim( plugin_dir_path(__FILE__), '/' );
			
			add_action( 'init', array( &$this, 'init' ) );
			
		} //end constructor
		
		
		/**
		* add_media_button()
		* 
		* Displays a thumbsup icon in the media bar when editing a post or page
		*
		* @param		string    $context	Media bar string
		* @return		string    Updated context variable with shortcode button added
		*/
		public function add_media_button( $context ) {
			$image_btn = $this->get_plugin_url( 'images/shortcode_icon.png' );
			$out = '<a href="#TB_inline?width=450&height=600&inlineId=pb_s3_shortcode" class="thickbox" title="' . __("Add S3 Shortcode", 'LION' ) . '"><img src="'.$image_btn.'" alt="' . __("Add S3 Shortcode", 'LION' ) . '" /></a>';
			return $context . $out;
		} //end add_media_button
		
		/**
		* add_settings_page()
		*
		* Adds an options page to the admin panel area 
		*
		**/
		public function add_settings_page() {
			add_options_page( 'PluginBuddy S3 Settings', 'PluginBuddy S3', 'manage_options', 'pb_s3', array( &$this, 'output_settings' ) );
		} //end add_settings_page
		
				
		/**
		* add_shortcode_editor()
		* 
		* Action target that displays the popup to insert the thumbsup shortcode on a post or page
		*
		*/
		public function add_shortcode_editor(){
			?>
			<script type="text/javascript">
				function pb_InsertGroup(){					
					//Get Attribute Values
					var attributes = '';
					var access_key = jQuery( "#pb_accesskey" ).val();
					var secret_key = jQuery( "#pb_secretkey" ).val();
					var bucket = jQuery( "#pb_bucket" ).val();
					var expires = jQuery( "#pb_expires" ).val();
					var filename = jQuery( "#pb_file" ).val();
					var link_text = jQuery( "#pb_text" ).val();
					
					//Write Attributes String
					var attributes = "access_key='" + access_key + "' secret_key='" + secret_key + "' bucket='" + bucket + "' expires='" + expires + "' file='" + filename + "'";					
							
					var win = window.dialogArguments || opener || parent || top;
					win.send_to_editor( "[s3 " + attributes + "]" + link_text + "[/s3]" );
				} //end pb_insertgroup
			</script>
		
			<div id="pb_s3_shortcode" style="display:none;">
				<div class="wrap">
					<div>
						<div style="padding:15px 15px 0 15px;">
							<h3 style="color:#5A5A5A!important; font-family:Georgia,Times New Roman,Times,serif!important; font-size:1.8em!important; font-weight:normal!important;"><?php _e('Insert S3 Link', 'LION' ); ?></h3>
						</div>
						

 						<!--Global Options-->
                        <div id='pb_s3_options'>
                        	<table class="form-table">
                                <tbody>
                                    <tr valign="top">
                                        <th scope="row"><label for='pb_accesskey'><?php _e( 'Access Key', 'LION' ); ?></label></th>
                                        <td>
                                        <input type='text' size='30' name='access_key' id='pb_accesskey' value='<?php echo esc_attr( $this->get_admin_option( 'access_key' ) ); ?>' />                                       
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><label for='pb_secretkey'><?php _e( 'Secret Key', 'LION' ); ?></label></th>
                                        <td>
                                        <input type='text' size='30' name='secret_key' id='pb_secretkey' value='<?php echo esc_attr( $this->get_admin_option( 'secret_key' ) ); ?>' />                                       
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><label for='pb_bucket'><?php _e( 'Bucket Name', 'LION' ); ?></label></th>
                                        <td>
                                        <input type='text' size='30' name='bucket' id='pb_bucket' value='<?php echo esc_attr( $this->get_admin_option( 'bucket' ) ); ?>' />                                       
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><label for='pb_expires'><?php _e( 'Expires (Seconds)', 'LION' ); ?></label></th>
                                        <td>
                                        <input type='text' size='30' name='expires' id='pb_expires' value='<?php echo esc_attr( $this->get_admin_option( 'expires' ) ); ?>' />                                       
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><label for='pb_text'><?php _e( 'Text', 'LION' ); ?></label></th>
                                        <td>
                                        <input type='text' size='30' name='pb_text' id='pb_text' value='' />                                       
                                        </td>
                                    </tr>
                                     <tr valign="top">
                                        <th scope="row"><label for='pb_file'><?php _e( 'File Name', 'LION' ); ?></label></th>
                                        <td>
                                        <input type='text' size='30' name='pb_file' id='pb_file' value='' />                                       
                                        </td>
                                    </tr>
                        
                                </tbody>
                            </table>
                        </div><!--/pb_s3_options-->
                        
					<div style="padding:15px;">
						<input type="button" class="button-primary" value="<?php _e( 'Insert', 'LION' ); ?>" onclick="pb_InsertGroup();"/>&nbsp;&nbsp;&nbsp;
					<a class="button" href="#" onclick="tb_remove(); return false;"><?php _e("Cancel", 'LION' ); ?></a>
					</div>
				</div><!--/.wrap-->
			</div><!--/#pb_s3_shortcode-->
			<?php
		} //end add_shortcode_editor
		
		/**
		* get_admin_option()
		* 
		* Returns a localized admin option
		*
		* @param   string    $key    Admin Option Key to Retrieve
		* @return   mixed                The results of the admin key.  False if not present.
		*/
		public function get_admin_option( $key = '' ) {			
			$admin_options = $this->get_admin_options();
			if ( array_key_exists( $key, $admin_options ) ) {
				return $admin_options[ $key ];
			}
			return false;
		}
		
		/**
		* get_admin_options()
		* 
		* Initialize and return an array of all admin options
		*
		* @return   array					All Admin Options
		*/
		public function get_admin_options( ) {
			
			if (empty($this->admin_options)) {
				$admin_options = $this->get_plugin_defaults();
				
				$options = get_option( $this->get_plugin_info( 'slug' ) );
				if (!empty($options)) {
					foreach ($options as $key => $option) {
						if (array_key_exists($key, $admin_options)) {
							$admin_options[$key] = $option;
						}
					}
				}
				
				//Save the options
				$this->admin_options = $admin_options;
				$this->save_admin_options();								
			}
			return $this->admin_options;
		} //end get_admin_options
		
		/**
		* get_all_admin_options()
		* 
		* Returns an array of all admin options
		*
		* @return   array					All Admin Options
		*/
		public function get_all_admin_options() {
			return $this->admin_options;
		}
		
		/**
		* get_plugin_defaults()
		* 
		* Returns an array of default plugin options (to be stored in the options table)
		*
		* @return		array               Default plugin keys
		*/
		public function get_plugin_defaults() {
			if ( isset( $this->defaults ) ) {
				return $this->defaults;
			} else {
				$this->defaults = array(
					'access_key' => false,
					'secret_key' => false,
					'bucket' => false,
					'expires' => false
				);
				return $this->defaults;
			}
		} //end get_plugin_defaults
		
		/**
		* get_plugin_dir()
		* 
		* Returns an absolute path to a plugin item
		*
		* @param		string    $path	Relative path to make absolute (e.g., /css/image.png)
		* @return		string               An absolute path (e.g., /htdocs/ithemes/wp-content/.../css/image.png)
		*/
		public function get_plugin_dir( $path = '' ) {
			$dir = $this->plugin_dir;
			if ( !empty( $path ) && is_string( $path) )
				$dir .= '/' . ltrim( $path, '/' );
			return $dir;		
		} //end get_plugin_dir
			
		/**
		* get_plugin_info()
		* 
		* Returns a localized plugin key
		*
		* @param   string    $key    Plugin Key to Retrieve
		* @return   mixed                The results of the plugin key.  False if not present.
		*/
		public function get_plugin_info( $key = '' ) {	
			if ( array_key_exists( $key, $this->plugin_info ) ) {
				return $this->plugin_info[ $key ];
			}
			return false;
		} //end get_plugin_info
		
		
		/**
		* get_plugin_url()
		* 
		* Returns an absolute url to a plugin item
		*
		* @param		string    $path	Relative path to plugin (e.g., /css/image.png)
		* @return		string               An absolute url (e.g., http://www.domain.com/plugin_url/.../css/image.png)
		*/
		public function get_plugin_url( $path = '' ) {
			$dir = $this->plugin_url;
			if ( !empty( $path ) && is_string( $path) )
				$dir .= '/' . ltrim( $path, '/' );
			return $dir;	
		} //get_plugin_url
		
		
		/**
		* init()
		* 
		* Initializes plugin localization, post types, updaters, plugin info, and adds actions/filters
		*
		*/
		function init() {		
			
			//* Localization Code */
			load_plugin_textdomain( 'LION', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
			
			//Add plugin info
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$this->plugin_info = wp_parse_args( array_change_key_case( get_plugin_data( __FILE__, false, false ), CASE_LOWER ), $this->plugin_info );
			
			//Media bar
			add_action('media_buttons_context', array( &$this, 'add_media_button') );
			add_action( 'admin_footer-post.php', array( &$this, 'add_shortcode_editor' ) );
			add_action( 'admin_footer-post-new.php', array( &$this, 'add_shortcode_editor' ) );
			
			//Admin menu
			add_action( 'admin_menu', array( &$this, 'add_settings_page' ) );
			
			//shortcode
	   		add_shortcode( 's3', array( &$this, 'output_shortcode' ) );
			
		}//end function init
		
		public function output_settings() {
			if ( isset( $_POST[ 'update' ] ) ) {
				check_admin_referer( 'pb-s3_save-settings' );
				$options[ 'access_key' ] = sanitize_text_field( $_POST[ 'access_key' ] );
				$options[ 'secret_key' ] = sanitize_text_field( $_POST[ 'secret_key' ] );
				$options[ 'bucket' ] = sanitize_text_field( $_POST[ 'bucket' ] );
				$options[ 'expires' ] = absint( $_POST[ 'expires' ] );
				$this->save_admin_options( $options );
				?>
				<div class="updated"><p><?php _e( 'Settings Saved', 'LION' ); ?></p></div>
				<?php
				
			} //end if $_POST['update']
			?>
			<div class="wrap">
				<h3><?php _e( "PluginBuddy S3 Settings", 'LION' ); ?></h3>
                	 <form method="post" action="<?php echo esc_attr( $_SERVER["REQUEST_URI"] ); ?>">
					<?php wp_nonce_field( 'pb-s3_save-settings' ) ?>
                	<table class="form-table">
                        <tbody>
                            <tr valign='top'>
                                <th scope="row"><label for='access_key'><?php _e( 'Access Key', 'LION' ); ?></label></th>
                                <td  valign='middle'>
                                	<input type='text' size='30' id='access_key' name='access_key' value='<?php echo esc_attr( $this->get_admin_option( 'access_key' ) ); ?>' />
                                </td>
                            </tr>
                            <tr valign='top'>
                                <th scope="row"><label for='secret_key'><?php _e( 'Secret Key', 'LION' ); ?></label></th>
                                <td  valign='middle'>
                                	<input type='text' size='30' id='secret_key' name='secret_key' value='<?php echo esc_attr( $this->get_admin_option( 'secret_key' ) ); ?>' />
                                </td>
                            </tr>
                            <tr valign='top'>
                                <th scope="row"><label for='bucket'><?php _e( 'Bucket Name', 'LION' ); ?></label></th>
                                <td  valign='middle'>
                                	<input type='text' size='30' id='bucket' name='bucket' value='<?php echo esc_attr( $this->get_admin_option( 'bucket' ) ); ?>' />
                                </td>
                            </tr>
                            <tr valign='top'>
                                <th scope="row"><label for='expires'><?php _e( 'Expires (seconds)', 'LION' ); ?></label></th>
                                <td  valign='middle'>
                                	<input type='text' size='30' id='expires' name='expires' value='<?php echo esc_attr( $this->get_admin_option( 'expires' ) ); ?>' />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="submit">
                      <input class='button-primary' type="submit" name="update" value="<?php esc_attr_e('Save Settings', 'LION' ) ?>" />
                    </div><!--/.submit-->
                  </form>
			</div><!--/.wrap-->
			<?php
		} //end output_settings
		
		/**
		* output_shortcode()
		* 
		* Outputs a shortcode - uses pb_thumbsup_display
		*
		* @param		array    $atts		Shortcode attributes (see pb_s3_display for params)
		* @param		array    $content	Content to display within the shortcode (see pb_s3_display for params)
		* @return		string    Shortcode output (or empty string on failure)
		*/
		public function output_shortcode( $atts, $content = false ) {
			$atts[ 'text' ] = $content;
			$display = pb_s3_display( $atts );
			if ( !is_wp_error( $display ) ) {
				return $display;
			}
			return '';
		} //end output_shortcode
		
		/**
		* save_admin_option()
		* 
		* Saves an individual option to the options array
		* @param		string    	$key		Option key to save
		* @param		mixed		$value	Value to save in the option	
		*/
		public function save_admin_option( $key = '', $value = '' ) {
			$this->admin_options[ $key ] = $value;
			$this->save_admin_options();
			return $value;
		} //end save_admin_option
		
		/**
		* save_admin_options()
		* 
		* Saves a group of admin options to the options table
		* @param		array    	$admin_options		Optional array of options to save (are merged with existing options)
		*/
		public function save_admin_options( $admin_options = false ){
			if (!empty($this->admin_options)) {
				if ( is_array( $admin_options ) ) {
					$this->admin_options = wp_parse_args( $admin_options, $this->admin_options );
				}
				update_option( $this->get_plugin_info( 'slug' ), $this->admin_options);
			}
		} //end save_admin_options
		
    } //end class
}
//instantiate the class
global $pb_s3;
if (class_exists('pluginbuddy_s3')) {
	if (get_bloginfo('version') >= "3.0") {
		add_action( 'plugins_loaded', 'pb_s3_instantiate' );
	}
}
function pb_s3_instantiate() {
	global $pb_s3;
	$pb_thumbsup = new pluginbuddy_s3();
}
/**
* pb_thumbsup_display()
* Insert a voting group
*
*
* The defaults for the parameter $args are:
*		access_key - Required - String - Access key for the Amazon S3 account (default false)
*		secret_key - Required - String - Secret key for the Amazon S3 account (default false)
*		bucket   - Required - String - The bucket to access (default false)
*		expires     - Required - INT - Expiration time in seconds for the link (default false)
*		file   - Required - String - File or object to access (default false)
*		text - Required - String - Link or IMG text to go inside the anchors (default false)
*		echo - Optional - Bool - Whether to echo or return the content (default false)
* @param array $args Elements that make up a shortcode request
* @return int|WP_Error WP_Error on failure.  HTML string on success.
*/
function pb_s3_display( $args ) {
	global $post, $pb_s3;
	$defaults = array( 
		'access_key' => false,
		'secret_key' => false,
		'bucket' => false,
		'expires' => false,
		'file' => false,
		'text' => false,
		'echo' => false
	);
	extract( shortcode_atts( $defaults, $args ) );
	if ( !$file || !$text || !$access_key || !$secret_key || !$bucket ) return new WP_Error( 'pb_s3_missinginfo', __( 'There is information missing to create the S3 Link', 'LION' ) );
	
	$query_args = array( 
		'AWSAccessKeyId' => $access_key,
	); 
	//The expiration parameter is required
	if ( $expires ) {
		$expires = absint( $expires );
		if ( $expires != 0 ) {
			$expires = time() + absint( $expires );
			$query_args[ 'Expires' ] = $expires;
		} else {
			return new WP_Error( 'pb_s3_expire', __( 'Expiration parameter is invalid.', 'LION' ) );
		}
	} else {
		return new WP_Error( 'pb_s3_expire', __( 'Expiration parameter must be set.', 'LION' ) );	
	} // end if $expires
	
	$amazon_text = "GET\n\n\n{$expires}\n/{$bucket}/{$file}";
	$query_args[ 'Signature' ] = urlencode( base64_encode( ( hash_hmac( "sha1", utf8_encode( $amazon_text ), $secret_key, TRUE ) ) ) );
	
	$url = add_query_arg( $query_args, "https://{$bucket}.s3.amazonaws.com/{$file}" );
	$link = sprintf( '<a href="%s">%s</a>', esc_url( $url ), esc_html( $text ) );
	if ( $echo ) echo $link;
	else return $link;
} //end function pb_s3_display
?>