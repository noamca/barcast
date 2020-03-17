<?php
/*
	Plugin Name: ezCache
	Description: EzCache is an easy and innovative cache plugin that will help you significantly improve your site speed.
	Plugin URI: https://ezcache.app
	Version: 1.3.8
	Author: uPress
	Author URI: https://www.upress.io
	Text Domain: ezcache
	Domain Path: /languages/
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

namespace {
	if ( ! defined( 'ABSPATH' ) ) {
		die( 'NO direct access!' );
	}

	define( 'EZCACHE_DIR', __DIR__ );
	define( 'EZCACHE_FILE', __FILE__ );
	define( 'EZCACHE_URL', plugin_dir_url( __FILE__ ) );
	define( 'EZCACHE_BASEBANE', basename( __FILE__ ) );
	define( 'EZCACHE_VERSION', '1.3.8' );
	define( 'EZCACHE_SETTINGS_KEY', 'ezcache' );
}

namespace Upress\EzCache {

	use WP_Post;

	class Plugin {
		private static $instance;
		public $plugin_dir;
		public $plugin_file;
		public $plugin_url;
		public $plugin_basename;
		public $plugin_version;
		public $plugin_settings_key;
		/** @var Cache $ezcache */
		public $ezcache;

		/**
		 * @return Plugin
		 */
		public static function initialize() {
			if ( ! self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}


		private function __construct() {
			$this->plugin_dir = EZCACHE_DIR;
			$this->plugin_file = EZCACHE_FILE;
			$this->plugin_url = EZCACHE_URL;
			$this->plugin_basename = EZCACHE_BASEBANE;
			$this->plugin_version = EZCACHE_VERSION;
			$this->plugin_settings_key = EZCACHE_SETTINGS_KEY;

			$this->load_dependencies();
			$this->initialize_plugin();
		}

		/**
		 * Import required files
		 */
		protected function load_dependencies() {
            require_once EZCACHE_DIR . '/vendor/autoload.php';
		}

		/**
		 * Init all the plugin parts
		 */
		protected function initialize_plugin() {
			$this->ezcache = Cache::instance();

			register_activation_hook( EZCACHE_FILE, [ $this, 'activation_hook' ] );
			register_deactivation_hook( EZCACHE_FILE, [ $this, 'deactivation_hook' ] );

			add_action( 'init', [ $this, 'load_translation' ] );
			add_action( 'edit_post', [ $this, 'maybe_clear_cache_on_post_update' ], 10, 2 );
			add_filter( 'cron_schedules', [ $this, 'add_cron_schedules' ] );
			add_action( 'plugins_loaded', [ $this, 'update_db_check' ] );

			new RestApi($this);
			new Admin($this);

			add_action( 'ezcache_clear_expired_cache', [ $this->ezcache, 'clear_expired_cache' ] );
		}

		function load_translation() {
			load_textdomain( 'ezcache', sprintf( '%1$s/%2$s/%2$s-%3$s.mo', WP_LANG_DIR, 'ezcache', get_locale() ) );
			load_plugin_textdomain( 'ezcache', false, basename( dirname( EZCACHE_FILE ) ) . '/languages' );
		}

		function get_wp_config_path() {
			if ( file_exists( ABSPATH . 'wp-config.php') ) {
				return ABSPATH . 'wp-config.php';
			}

			return dirname(ABSPATH) . '/wp-config.php';
		}

		function update_db_check() {
			if ( EZCACHE_VERSION != get_option( 'ezcache_version' ) ) {
				$this->activation_hook();
			}
		}

		function activation_hook() {
			$path = $this->get_wp_config_path();
			$contents = file_get_contents( $path );

			// update wp-config define
			if ( preg_match( '/define\s*?\(\s*?[\']WP_CACHE[\'"]/', $contents) ) {
				$contents = preg_replace( '/define\s*?\(\s*?[\'"]WP_CACHE[\'"],\s*?(true|false)\s*?\)/', "define( 'WP_CACHE', true )", $contents );
			} else {
				$contents = preg_replace( '/(<\?php)/', "$1\ndefine( 'WP_CACHE', true );", $contents );
			}

			file_put_contents( $path, $contents );

			// if the user currently has an advanced-cache file rename it as a backup
			if ( file_exists( WP_CONTENT_DIR . '/advanced-cache.php' ) && false === strpos( file_get_contents( WP_CONTENT_DIR . '/advanced-cache.php' ), 'ezCache Advanced Cache' ) ) {
				rename( WP_CONTENT_DIR . '/advanced-cache.php', WP_CONTENT_DIR . '/advanced-cache.php.ezcache-backup' );
			}
			// copy advanced-cache
			copy( EZCACHE_DIR . '/advanced-cache.php', WP_CONTENT_DIR . '/advanced-cache.php' );

			Settings::maybe_update_cronjobs( Settings::get_settings() );
			Updater::upgrade();

			$this->ezcache->preload_homepage();
		}

		function deactivation_hook() {
			$path = $this->get_wp_config_path();
			$contents = file_get_contents( $path );

			// update wp-config define
			// we don't need to do anything if the constant is not defined
			if ( preg_match( '/define\s*?\(\s*?[\']WP_CACHE[\'"]/', $contents) ) {
				$contents = preg_replace( '/define\s*?\(\s*?[\'"]WP_CACHE[\'"],\s*?(true|false)\s*?\)/', "define( 'WP_CACHE', false )", $contents );
			}

			file_put_contents( $path, $contents );

			// delete advanced-cache
			if ( file_exists( WP_CONTENT_DIR . '/advanced-cache.php' ) ) {
				unlink( WP_CONTENT_DIR . '/advanced-cache.php' );
			}
			// if we have a backup of an older advanced-cache file rename it back
			if ( file_exists( WP_CONTENT_DIR . '/advanced-cache.php.ezcache-backup' ) ) {
				rename( WP_CONTENT_DIR . '/advanced-cache.php.ezcache-backup', WP_CONTENT_DIR . '/advanced-cache.php' );
			}

			if ( wp_next_scheduled ( 'ezcache_clear_expired_cache' ) ) {
				wp_clear_scheduled_hook( 'ezcache_clear_expired_cache' );
			}

			$this->ezcache->clear_cache();
		}

		/**
		 * Runs when a post is created, updated, or a comment is left on it
		 * @param int $post_id
		 * @param WP_Post $post
		 */
		function maybe_clear_cache_on_post_update( $post_id, $post ) {
			$settings = Settings::get_settings();

			if ( ! $settings->cache_clear_on_post_edit ) {
				return;
			}

			$this->ezcache->clear_cache_single( $post_id );
		}

		function add_cron_schedules( $schedules ) {
			if ( ! isset( $schedules['every_10_minutes'] ) ) {
				$schedules['every_10_minutes'] = array(
					'interval' => 600,
					'display' => __( 'Every 10 Minutes', 'ezcache' ),
				);
			}
			if ( ! isset( $schedules['hourly'] ) ) {
				$schedules['hourly'] = array(
					'interval' => 3600,
					'display' => __( 'Hourly', 'ezcache' ),
				);
			}
			if ( ! isset( $schedules['every_3_hours'] ) ) {
				$schedules['every_3_hours'] = array(
					'interval' => 10800,
					'display' => __( 'Every 3 Hours', 'ezcache' ),
				);
			}
			if ( ! isset( $schedules['every_6_hours'] ) ) {
				$schedules['every_6_hours'] = array(
					'interval' => 21600,
					'display'  => __( 'Every 6 Hours', 'ezcache' ),
				);
			}
			if ( ! isset( $schedules['every_12_hours'] ) ) {
				$schedules['every_12_hours'] = array(
					'interval' => 43200,
					'display' => __( 'Every 12 Hours', 'ezcache' ),
				);
			}
			if ( ! isset( $schedules['daily'] ) ) {
				$schedules['daily'] = array(
					'interval' => 86400,
					'display' => __( 'Daily', 'ezcache' ),
				);
			}

			return $schedules;
		}
	}

	Plugin::initialize();
}
