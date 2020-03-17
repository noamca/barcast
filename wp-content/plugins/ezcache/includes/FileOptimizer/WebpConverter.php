<?php
namespace Upress\EzCache\FileOptimizer;

use Upress\EzCache\BackgroundProcesses\ConvertWebpProcess;
use wpdb;

class WebpConverter extends BaseFileOptimizer {
	/** @var string $cache_dir */
	protected $cache_dir;
	/** @var string $cache_file */
	protected $cache_file;
	/** @var ConvertWebpProcess $webp_processor */
	protected $webp_processor;
	/** @var wpdb $wpdb */
	protected $wpdb;

	function __construct( $cache_dir, $cache_file, $webp_processor, $wpdb ) {
		$this->cache_dir = $cache_dir;
		$this->cache_file  = $cache_file;
		$this->webp_processor  = $webp_processor;
		$this->wpdb  = $wpdb;
	}

	/**
	 * Minifies CSS files
	 *
	 * @param string $html HTML content.
	 *
	 * @return string
	 */
	public function optimize( $html ) {
		/*
		 * we need to make sure to handle multiple formats
		 * <img src="elva-fairy-800w.jpg" alt="Elva dressed as a fairy">
		 * <img srcset="elva-fairy-320w.jpg 320w, elva-fairy-480w.jpg 480w, elva-fairy-800w.jpg 800w" sizes="(max-width: 320px) 280px, (max-width: 480px) 440px, 800px" src="elva-fairy-800w.jpg" alt="Elva dressed as a fairy">
		 * <picture><source media="(max-width: 799px)" srcset="elva-480w-close-portrait.jpg"><img srv="elva-480w-close-portrait.jpg" alt="Elva dressed as a fairy"></picture>
		 * <div style="background: url(elva-480w-close-portrait.jpg);">
		 * <div style="background-image: url(elva-480w-close-portrait.jpg);">
		 */
		$images = $this->find( '<img\s+([^>]+[\s"\'])?src\s*=\s*[\'"]\s*?([^\'"]+(?:\?[^\'"]*)?)\s*?[\'"]([^>]+)?\/?>', $html );

		if ( ! $images ) {
			return $html;
		}

		$need_to_process = false;
		foreach ( $images as $image ) {
			$image_url = $image[2];

			// we don't process external files or files that don't exist

			if($this->is_external_file( $image_url )) {
				continue;
			}

			$image_path = $this->get_file_path( $image[2] );
			if( ! file_exists( $image_path ) ) {
				continue;
			}

			$ext = pathinfo( $image_path, PATHINFO_EXTENSION );
			$mime = mime_content_type( $image_path );

			$image_webp_url = preg_replace( '/^(.+)\.' . preg_quote( $ext, '/' ) . '$/u', '$1.webp', $image_url );
			$image_webp_path = preg_replace( '/^(.+)\.' . preg_quote( $ext, '/' ) . '$/u', '$1.webp', $image_path );

			// and skip non-images or images already in webp format
			if ( 'webp' == $ext || ! preg_match( '/image\/.+/i', $mime ) || preg_match( '/image\/(svg.*|ico|gif|webp)/i', $mime ) ) {
				continue;
			}

			// check if the image has a webp version and if it has then just replace it in the html
			$image = $this->wpdb->get_row(
				$this->wpdb->prepare(
					"SELECT * FROM `{$this->wpdb->prefix}ezcache_webp_images` WHERE `uid` = %s LIMIT 1",
					[ sha1( $image_path ) ]
				)
			);

			if ( $image && 'completed' == $image->status ) {
				$html = preg_replace( '/\b' . preg_quote( $image_url, '/' ) . '\b/u', $image->webp_url, $html );
				continue;
			}

			if ( $image && 'pending' == $image->status ) {
				// image is being processed in background
				continue;
			}

			if ( $image && 'failed' == $image->status ) {
				// if the image has failed we need to requeue it
				$this->wpdb->query(
					$this->wpdb->prepare(
						"DELETE FROM `{$this->wpdb->prefix}ezcache_webp_images` WHERE `id` = %d",
						[ $image->id ]
					)
				);
			}

			// otherwise send the image to process
			// this should parse the images and set a callback to update the cache file
			$this->wpdb->query(
				$this->wpdb->prepare(
					"INSERT INTO `{$this->wpdb->prefix}ezcache_webp_images` (`uid`, `url`, `webp_url`, `path`, `webp_path`, `status`, `created_at`, `updated_at`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
					[ sha1( $image_path ), $image_url, $image_webp_url, $image_path, $image_webp_path, 'pending', date( 'Y-m-d H:i:s' ), date( 'Y-m-d H:i:s' ) ]
				)
			);

			if ( empty( $this->wpdb->last_error ) ) {
				$need_to_process = true;
				$this->webp_processor->push_to_queue( [
					'row_id'          => $this->wpdb->insert_id,
					'image_url'       => $image_url,
					'image_path'      => $image_path,
					'image_webp_url'  => $image_webp_url,
					'image_webp_path' => $image_webp_path,
					'cache_file'      => $this->cache_file,
				] );
			} else {
				error_log( 'ezCache WebP Converter Error: ' . $this->wpdb->last_error );
			}
		}

		if ( $need_to_process ) {
			add_filter( 'http_headers_useragent', function () {
				return 'webp background processor';
			}, 9999 );
			$this->webp_processor->save();
		}

		return $html;
	}
}
