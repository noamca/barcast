<?php

namespace Upress\EzCache;

use Exception;
use wpdb;

class Updater {
	/** @var string $current_version */
	protected static $current_version;
	/** @var string $collate */
	protected static $collate;

	protected static function wpdb() {
		global $wpdb;
	}

	public static function uninstall() {
		global $wpdb;

		$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}ezcache_webp_images`");
		delete_option( 'ezcache_version' );
	}

	/**
	 * Run any necessary db updates, file upgrades etc.
	 */
	public static function upgrade() {
		global $wpdb;

		self::$current_version = get_option( 'ezcache_version' );
		self::$collate = $wpdb->get_charset_collate();

		/** @noinspection PhpIncludeInspection */
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		try {
			self::update_0_1_20190811();
			self::update_0_1_20190812();

			// make sure we update the version in the database so we can run upgrades at later times
			update_option( 'ezcache_version', EZCACHE_VERSION );
		} catch ( Exception $ex ) {
			error_log( 'ezCache Updater Error: ' . $ex );
			wp_die( $ex->getMessage() );
		}
	}

	/**
	 * Update to the 1.0 version
	 * create the 404 database
	 * @throws Exception
	 */
	protected static function update_0_1_20190811() {
		global $wpdb;

		$ver = '0.1-20190811';
		if ( self::$current_version && version_compare( self::$current_version, $ver, '>=' ) ) {
			return;
		}

		$wpdb->query( "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ezcache_webp_images` (
			`id` bigint(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			`uid` varchar(191) NOT NULL DEFAULT '',
			`url` text(0) NOT NULL DEFAULT '',
			`webp_url` text(0) NOT NULL DEFAULT '',
			`status` enum('pending', 'completed', 'failed') NOT NULL DEFAULT 'pending',
			`created_at` datetime(0) NOT NULL DEFAULT '0000-00-00 00:00:00',
			`updated_at` datetime(0) NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY (`id`),
			UNIQUE INDEX (`uid`) USING BTREE
		) ". ( self::$collate ) );

		if ( ! empty( $wpdb->last_error ) ) {
			throw new Exception( $wpdb->last_error );
		}

		self::$current_version = $ver;
		update_option( 'ezcache_version', self::$current_version );
	}

	/**
	 * Update to the 1.0 version
	 * create the 404 database
	 * @throws Exception
	 */
	protected static function update_0_1_20190812() {
		global $wpdb;

		$ver = '0.1-20190812';
		if ( self::$current_version && version_compare( self::$current_version, $ver, '>=' ) ) {
			return;
		}

		$cols = $wpdb->get_col( "SHOW COLUMNS FROM `{$wpdb->prefix}ezcache_webp_images`", 0 );
		if( ! in_array( 'path', $cols ) ) {
			$wpdb->query( "ALTER TABLE `{$wpdb->prefix}ezcache_webp_images` 
				ADD COLUMN `path` text(0) NOT NULL DEFAULT '' AFTER `webp_url`,
				ADD COLUMN `webp_path` text(0) NOT NULL DEFAULT '' AFTER `path`,
				ADD COLUMN `original_size` int(10) UNSIGNED NOT NULL DEFAULT 0 AFTER `webp_path`,
				ADD COLUMN `webp_size` int(10) UNSIGNED NOT NULL DEFAULT 0 AFTER `original_size`
			" );
		}

		if ( ! empty( $wpdb->last_error ) ) {
			throw new Exception( $wpdb->last_error );
		}

		self::$current_version = $ver;
		update_option( 'ezcache_version', self::$current_version );
	}

}
