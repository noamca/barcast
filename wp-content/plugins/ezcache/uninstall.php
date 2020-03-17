<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die( 'NO direct access!' );
}

/** @noinspection PhpIncludeInspection */
include_once __DIR__ . '/plugins/ezcache/vendor/autoload.php';

if ( class_exists( '\Upress\EzCache\Updater' ) ) {
	\Upress\EzCache\Updater::uninstall();
}
