<?php
namespace Upress\EzCache\BackgroundProcesses;

use Upress\EzCache\LicenseApi;
use Upress\EzCache\WebpApi;
use \WP_Background_Process;

class ConvertWebpProcess extends WP_Background_Process {
	/**
	 * @var string
	 */
	protected $action = 'ezcache_convert_images_to_webp';

	/**
	 * Task
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param mixed $item Queue item to iterate over.
	 *
	 * @return mixed
	 */
	protected function task( $item ) {
		global $wpdb;

		$row_id = $item['row_id'];
		$image_url = $item['image_url'];
		$image_path = $item['image_path'];
		$cache_file = $item['cache_file'];
		$webp_url = $item['image_webp_url'];
		$webp_path = $item['image_webp_path'];

		$image = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM `{$wpdb->prefix}ezcache_webp_images` WHERE `id` = %s LIMIT 1",
				[ $row_id ]
			)
		);

		if ( $image && 'completed' == $image->status ) {
			// we need to update the cache file
			$this->append_link_to_replace( $cache_file, $image_url, $webp_url );
			return false;
		}

		// only download the file if we don't have it locally
		if ( ! file_exists( $webp_path ) || filesize( $webp_path ) <= 2 || stripos( file_get_contents( $webp_path ), '"success":false' ) ) {
			$license   = new LicenseApi();
			$converter = new WebpApi( $license->get_license_key() );
			$response  = $converter->convert( $image_path );

			if ( is_wp_error( $response ) || stripos( $response['info']['content_type'], 'json' ) || stripos( $response['data'], '"success":false' ) ) {
				$wpdb->query(
					$wpdb->prepare(
						"UPDATE `{$wpdb->prefix}ezcache_webp_images` SET `status` = %s WHERE `id` = %d",
						[ 'failed', $row_id ]
					)
				);
				error_log( "ezCache WebP Background Processor: " . (is_wp_error( $response ) ? $response->get_error_message() : $response['data']) );

				// delete the broken file
				if ( file_exists( $webp_path ) ) {
					unlink( $webp_path );
				}

				return false;
			}

			file_put_contents( $webp_path, $response['data'] );
		}

		$original_size = filesize( $image_path );
		$webp_size = filesize( $webp_path );

		$wpdb->query(
			$wpdb->prepare(
				"UPDATE `{$wpdb->prefix}ezcache_webp_images` SET `status` = %s, `original_size` = %d, `webp_size` = %d WHERE `id` = %d",
				[ 'completed', $original_size, $webp_size, $row_id ]
			)
		);

		$this->append_link_to_replace( $cache_file, $image_url, $webp_url );

		// return false to remove the item from the queue
		return false;
	}

	/**
	 * Complete
	 *
	 * Override if applicable, but ensure that the below actions are
	 * performed, or, call parent::complete().
	 */
	protected function complete() {
		global $wpdb;
		parent::complete();

		$data = $this->get_links_to_replace();
		if ( ! $data ) {
			error_log( 'ezCache WebP Background Processor: invalid transient data received' );
			return;
		}

		foreach ( $data as $cache_file => $images ) {
			if ( ! file_exists( $cache_file ) ) {
				// File has been deleted? maybe the cache got cleared before the process finished
				continue;
			}

			$handle = @fopen( $cache_file, 'c+' );

			if ( $handle && @flock( $handle, LOCK_EX ) ) {
				$contents = gzdecode( fread( $handle, filesize( $cache_file ) ) );

				foreach ( $images as $image_data ) {
					$contents = str_replace( $image_data['image_url'], $image_data['webp_url'], $contents );
				}

				ftruncate( $handle, 0 );
				fseek( $handle, 0 );
				fwrite( $handle, gzencode( $contents, 6, FORCE_GZIP ) );
				flock( $handle, LOCK_UN );
			} else {
				error_log( "ezCache WebP Background Processor: Could not get a lock on {$cache_file}, failed updating WebP image URLs" );
			}

			if ( $handle ) {
				fclose( $handle );
			}
		}

		$wpdb->query( "OPTIMIZE TABLE `{$wpdb->prefix}ezcache_webp_images`" );
	}

	/**
	 * Save a link to replace in a cached file
	 *
	 * @param string $cache_file
	 * @param string $image_url
	 * @param string $webp_url
	 */
	protected function append_link_to_replace( $cache_file, $image_url, $webp_url ) {
		$transient = get_site_option( $this->action . '_reprocess_queue' );
		if ( ! $transient ) {
			$transient = [];
		}

		if ( ! isset( $transient[ $cache_file ] ) ) {
			$transient[ $cache_file ] = [];
		}

		$transient[ $cache_file ][] = [
			'image_url' => $image_url,
			'webp_url' => $webp_url,
		];

		update_site_option( $this->action . '_reprocess_queue', $transient );
	}

	/**
	 * Get the links we need to replace in the cached file
	 *
	 * @return bool|array
	 */
	protected function get_links_to_replace() {
		$transient = get_site_option( $this->action . '_reprocess_queue' );
		if ( ! $transient ) {
			return false;
		}

		update_site_option( $this->action . '_reprocess_queue', [] );
		return $transient;
	}
}
