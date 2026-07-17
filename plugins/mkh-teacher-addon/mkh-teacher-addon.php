<?php
/**
 * Plugin Name: MKH Teacher Addon
 * Plugin URI: https://muslimkidstime.com
 * Description: Extends MasterStudy LMS with comprehensive Teacher Profile functionality using ACF PRO. Stores all teacher data as user meta for seamless integration with instructor accounts.
 * Version: 1.0.0
 * Author: Muslim Kids Time
 * Author URI: https://muslimkidstime.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: mkh-teacher-addon
 * Domain Path: /languages
 * Requires PHP: 8.0
 * Requires at least: 6.0
 *
 * @package MKH_Teacher_Addon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main MKH Teacher Addon Plugin Class
 *
 * @package MKH_Teacher_Addon
 */
class MKH_Teacher_Addon {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	const VERSION = '1.0.0';

	/**
	 * Plugin file path.
	 *
	 * @var string
	 */
	protected $plugin_file;

	/**
	 * Plugin directory path.
	 *
	 * @var string
	 */
	protected $plugin_dir;

	/**
	 * Single instance of the class.
	 *
	 * @var MKH_Teacher_Addon
	 */
	protected static $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return MKH_Teacher_Addon
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	protected function __construct() {
		$this->plugin_file = __FILE__;
		$this->plugin_dir  = plugin_dir_path( $this->plugin_file );

		$this->load_dependencies();
		$this->init_hooks();
	}

	/**
	 * Load plugin dependencies.
	 */
	protected function load_dependencies() {
		// Load ACF field registration
		require_once $this->plugin_dir . 'inc/acf/teacher-profile-fields.php';
		
		// Load dashboard menu integration
		require_once $this->plugin_dir . 'includes/class-dashboard-menu.php';
		
		// Load template loader
		require_once $this->plugin_dir . 'includes/class-template-loader.php';
		
		// Initialize template loader
		new MKH_Teacher_Addon_Template_Loader( $this->plugin_dir );
	}

	/**
	 * Initialize WordPress hooks.
	 */
	protected function init_hooks() {
		// Plugin activation/deactivation
		register_activation_hook( $this->plugin_file, array( $this, 'activate' ) );
		register_deactivation_hook( $this->plugin_file, array( $this, 'deactivate' ) );

		// Initialize plugin
		add_action( 'plugins_loaded', array( $this, 'init' ) );

		// Load text domain
		add_action( 'init', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Plugin activation.
	 */
	public function activate() {
		// Set default options if needed
		// Flush rewrite rules to register custom routes
		flush_rewrite_rules();
		
		// Reset MasterStudy page config to include our custom route
		delete_transient( 'stm_lms_routes_pages_transient' );
		delete_transient( 'stm_lms_routes_pages_config_transient' );
		delete_transient( 'stm_lms_routes_pages_routes_transient' );
	}

	/**
	 * Plugin deactivation.
	 */
	public function deactivate() {
		// Clean up if needed
		flush_rewrite_rules();
	}

	/**
	 * Initialize plugin.
	 */
	public function init() {
		// Check if ACF PRO is active
		if ( ! class_exists( 'ACF' ) ) {
			add_action( 'admin_notices', array( $this, 'acf_missing_notice' ) );
			return;
		}

		// Plugin is ready
		do_action( 'mkh_teacher_addon_init' );
	}

	/**
	 * Load plugin text domain.
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'mkh-teacher-addon',
			false,
			dirname( plugin_basename( $this->plugin_file ) ) . '/languages'
		);
	}

	/**
	 * Display admin notice if ACF PRO is missing.
	 */
	public function acf_missing_notice() {
		if ( current_user_can( 'activate_plugins' ) ) {
			?>
			<div class="notice notice-error">
				<p>
					<?php
					printf(
						/* translators: %s: Plugin name */
						esc_html__( '%s requires Advanced Custom Fields PRO to be installed and activated.', 'mkh-teacher-addon' ),
						'<strong>MKH Teacher Addon</strong>'
					);
					?>
				</p>
			</div>
			<?php
		}
	}

	/**
	 * Get plugin version.
	 *
	 * @return string
	 */
	public function get_version() {
		return self::VERSION;
	}

	/**
	 * Get plugin file path.
	 *
	 * @return string
	 */
	public function get_plugin_file() {
		return $this->plugin_file;
	}

	/**
	 * Get plugin directory path.
	 *
	 * @return string
	 */
	public function get_plugin_dir() {
		return $this->plugin_dir;
	}
}

/**
 * Initialize the plugin.
 */
function mkh_teacher_addon() {
	return MKH_Teacher_Addon::get_instance();
}

// Start the plugin
mkh_teacher_addon();
