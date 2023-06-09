<?php
/**
 * @package snow-monkey
 * @author inc2734
 * @license GPL-2.0+
 * @version 20.2.0
 */

namespace Framework\Controller;

class Manager {

	/**
	 * Setting page slug.
	 *
	 * @var string
	 */
	const MENU_SLUG = 'snow-monkey';

	/**
	 * Available settings name.
	 *
	 * @var string
	 */
	const SETTINGS_NAME = 'snow-monkey-settings';

	/**
	 * Default settings.
	 *
	 * @var array
	 */
	const DEFAULT_SETTINGS = array(
		'license-key'          => null,
		'xserver-register-key' => null,
	);

	/**
	 * constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, '_admin_menu' ) );
		add_action( 'admin_init', array( $this, '_init' ) );

		register_uninstall_hook(
			__FILE__,
			array( '\Framework\Controller\Manager', '_uninstall' )
		);
	}

	/**
	 * Add admin menu.
	 */
	public function _admin_menu() {
		add_options_page(
			__( 'Snow Monkey settings', 'snow-monkey' ),
			__( 'Snow Monkey', 'snow-monkey' ),
			'manage_options',
			self::MENU_SLUG,
			function() {
				?>
				<div class="wrap">
					<h1><?php esc_html_e( 'Snow Monkey settings', 'snow-monkey' ); ?></h1>
					<form method="post" action="options.php">
						<?php
						settings_fields( self::MENU_SLUG );
						do_settings_sections( self::MENU_SLUG );
						submit_button();
						?>
					</form>

					<form method="post" action="options.php">
						<input type="hidden" name="<?php echo esc_attr( self::SETTINGS_NAME ); ?>[reset]" value="1">
						<?php
						settings_fields( self::MENU_SLUG );
						submit_button(
							esc_html__( 'Reset settings', 'snow-monkey' ),
							'secondary'
						);
						?>
					</form>

					<hr>

					<form method="post" action="options.php">
						<input type="hidden" name="<?php echo esc_attr( self::SETTINGS_NAME ); ?>[clear-remote-patterns-cache]" value="1">
						<?php
						settings_fields( self::MENU_SLUG );
						submit_button(
							esc_html__( 'Retrieve patterns from the pattern library', 'snow-monkey' ),
							'primary'
						);
						?>
					</form>
				</div>
				<?php
			}
		);
	}

	/**
	 * Initialize available blocks settings.
	 */
	public function _init() {
		if ( ! $this->_is_option_page() && ! $this->_is_options_page() ) {
			return;
		}

		if ( ! get_option( self::SETTINGS_NAME ) ) {
			update_option( self::SETTINGS_NAME, self::DEFAULT_SETTINGS );
		}

		/**
		 * Post proccess.
		 */
		register_setting(
			self::MENU_SLUG,
			self::SETTINGS_NAME,
			function( $option ) {
				delete_transient( 'snow-monkey-remote-patterns' );
				delete_transient( 'snow-monkey-remote-patterns' );

				if ( isset( $option['clear-remote-patterns-cache'] ) && '1' === $option['clear-remote-patterns-cache'] ) {
					return get_option( self::SETTINGS_NAME );
				}

				if ( isset( $option['reset'] ) && '1' === $option['reset'] ) {
					return array();
				}

				if ( isset( $option['license-key'] ) && static::get_option( 'license-key' ) !== $option['license-key'] ) {
					delete_transient( 'snow-monkey-license-status-' . sha1( $option['license-key'] ) );
				}

				// ライセンスキーが入力されている場合は XSERVER 登録キーは認証しない
				if ( isset( $option['license-key'] ) && isset( $option['xserver-register-key'] ) ) {
					delete_transient( 'snow-monkey-xserver-register-status-' . sha1( $option['license-key'] ) );

					if ( ! empty( $option['license-key'] ) ) {
						$option['xserver-register-key'] = '';
					}
				}

				return $option;
			}
		);

		add_settings_section(
			self::SETTINGS_NAME,
			__( 'Settings', 'snow-monkey' ),
			function() {
			},
			self::MENU_SLUG
		);

		add_settings_field(
			'license-key',
			'<label for="license-key">' . esc_html__( 'License key', 'snow-monkey' ) . '</label>',
			function( $args ) {
				$transient = static::get_license_status( static::get_option( 'license-key' ) );

				$button = 'true' === $transient
					? array(
						'label'   => __( 'Enable', 'snow-monkey' ),
						'color'   => '#fff',
						'bgcolor' => '#51cf66',
					)
					: array(
						'label'   => __( 'Disable', 'snow-monkey' ),
						'color'   => '#fff',
						'bgcolor' => '#e03131',
					);
				?>
				<div style="display: flex; align-items: center; gap: .5rem">
					<div style="flex: 1 1 auto">
						<input
							type="text"
							id="license-key"
							class="widefat"
							name="<?php echo esc_attr( self::SETTINGS_NAME ); ?>[license-key]"
							value="<?php echo esc_attr( static::get_option( 'license-key' ) ); ?>"
						>
					</div>
					<div style="flex: 0 0 auto">
						<span style="background-color: <?php echo esc_attr( $button['bgcolor'] ); ?>; color: <?php echo esc_attr( $button['color'] ); ?>; padding: .25em .5em; display: inline-block; font-size: 14px"><?php echo esc_html( $button['label'] ); ?></span>
					</div>
				</div>
				<p class="description">
					<?php esc_html_e( 'If the license key entered is valid, premium block patterns are available.', 'snow-monkey' ); ?><br>
					<?php esc_html_e( 'If the license key is entered, the Xserver register key setting is not saved.', 'snow-monkey' ); ?>
				</p>
				<?php
			},
			self::MENU_SLUG,
			self::SETTINGS_NAME
		);

		add_settings_field(
			'xserver-register-key',
			'<label for="xservser-regiser-key">' . esc_html__( 'Xserver register key', 'snow-monkey' ) . '</label>',
			function( $args ) {
				$transient = static::get_xserver_register_status( static::get_option( 'xserver-register-key' ) );

				$button = 'true' === $transient
					? array(
						'label'   => __( 'Enable', 'snow-monkey' ),
						'color'   => '#fff',
						'bgcolor' => '#51cf66',
					)
					: array(
						'label'   => __( 'Disable', 'snow-monkey' ),
						'color'   => '#fff',
						'bgcolor' => '#e03131',
					);
				?>
				<div style="display: flex; align-items: center; gap: .5rem">
					<div style="flex: 1 1 auto">
						<input
							type="text"
							id="xserver-register-key"
							class="widefat"
							name="<?php echo esc_attr( self::SETTINGS_NAME ); ?>[xserver-register-key]"
							value="<?php echo esc_attr( static::get_option( 'xserver-register-key' ) ); ?>"
						>
					</div>
					<div style="flex: 0 0 auto">
						<span style="background-color: <?php echo esc_attr( $button['bgcolor'] ); ?>; color: <?php echo esc_attr( $button['color'] ); ?>; padding: .25em .5em; display: inline-block; font-size: 14px"><?php echo esc_html( $button['label'] ); ?></span>
					</div>
				</div>
				<p class="description">
					<?php esc_html_e( 'If the Xserver register key entered is valid, premium block patterns are available.', 'snow-monkey' ); ?><br>
					<?php esc_html_e( 'If you are using Xserver integration, enter the Xserver register key in the Xserver register key entry field without entering anything in the license key entry field.', 'snow-monkey' ); ?>
				</p>
				<?php
			},
			self::MENU_SLUG,
			self::SETTINGS_NAME
		);
	}

	/**
	 * Get license status.
	 *
	 * @param string $license_key The license key.
	 * @return boolean
	 */
	public static function get_license_status( $license_key ) {
		$transient = get_transient( 'snow-monkey-license-status-' . sha1( $license_key ) );
		if ( false !== $transient ) {
			return $transient;
		}

		$status = static::_request_license_validate( $license_key );
		set_transient( 'snow-monkey-license-status-' . sha1( $license_key ), $status ? $status : 'false', 60 * 10 );
		return $status;
	}

	/**
	 * Validate checker.
	 *
	 * @param string $license_key The license key.
	 * @return boolean
	 */
	protected static function _request_license_validate( $license_key ) {
		global $wp_version;

		$response = wp_remote_get(
			sprintf(
				'https://snow-monkey.2inc.org/wp-json/snow-monkey-license-manager/v1/validate/%1$s?repository=snow-monkey',
				$license_key
			),
			array(
				'user-agent' => 'WordPress/' . $wp_version,
				'timeout'    => 30,
				'headers'    => array(
					'Accept-Encoding' => '',
				),
			)
		);

		$status = false;
		if ( $response && ! is_wp_error( $response ) ) {
			$response_code = wp_remote_retrieve_response_code( $response );
			if ( 200 === $response_code ) {
				$status = wp_remote_retrieve_body( $response );
			}
		}

		return $status;
	}

	/**
	 * Get Xserver register status.
	 *
	 * @param string $xserver_register_key The license key.
	 * @return boolean
	 */
	public static function get_xserver_register_status( $xserver_register_key ) {
		$transient = get_transient( 'snow-monkey-xserver-register-status-' . sha1( $xserver_register_key ) );
		if ( false !== $transient ) {
			return $transient;
		}

		$status = static::_request_license_validate_xserver( $xserver_register_key );
		set_transient( 'snow-monkey-xserver-register-status-' . sha1( $license_key ), $status ? $status : 'false', 60 * 10 );
		return $status;
	}

	/**
	 * Validate checker.
	 *
	 * @param string $license_key The license key.
	 * @return boolean
	 */
	protected static function _request_license_validate_xserver( $xserver_register_key ) {
		global $wp_version;

		$response = wp_remote_get(
			sprintf(
				'https://snow-monkey.2inc.org/wp-json/snow-monkey-license-manager/v1/validate-xserver/%1$s?repository=snow-monkey',
				$xserver_register_key
			),
			array(
				'user-agent' => 'WordPress/' . $wp_version,
				'timeout'    => 30,
				'headers'    => array(
					'Accept-Encoding' => '',
				),
			)
		);

		$status = false;
		if ( $response && ! is_wp_error( $response ) ) {
			$response_code = wp_remote_retrieve_response_code( $response );
			if ( 200 === $response_code ) {
				$status = wp_remote_retrieve_body( $response );
			}
		}

		return $status;
	}

	/**
	 * Uninstall
	 */
	public static function _uninstall() {
		delete_option( self::SETTINGS_NAME );
	}

	/**
	 * Return option.
	 *
	 * @param string $key The option key name.
	 * @return mixed
	 */
	public static function get_option( $key ) {
		$option = get_option( self::SETTINGS_NAME );
		if ( ! $option ) {
			return false;
		}

		return isset( $option[ $key ] ) ? $option[ $key ] : false;
	}

	/**
	 * Return true is option page.
	 *
	 * @return boolean
	 */
	protected function _is_option_page() {
		$current_url = admin_url( '/options-general.php?page=' . static::MENU_SLUG );
		$current_url = preg_replace( '|^(.+)?(/wp-admin/.*?)$|', '$2', $current_url );
		$request_uri = $_SERVER['REQUEST_URI'];
		$request_uri = preg_replace( '|^(.+)?(/wp-admin/.*?)$|', '$2', $request_uri );
		return false !== strpos( $request_uri, $current_url );
	}

	/**
	 * Return true is options page.
	 *
	 * @return boolean
	 */
	protected function _is_options_page() {
		$current_url = admin_url( '/options.php' );
		$current_url = preg_replace( '|^(.+)?(/wp-admin/.*?)$|', '$2', $current_url );
		$request_uri = $_SERVER['REQUEST_URI'];
		$request_uri = preg_replace( '|^(.+)?(/wp-admin/.*?)$|', '$2', $request_uri );
		return false !== strpos( $request_uri, $current_url );
	}
}
