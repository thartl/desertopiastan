<?php

/**
 * Cache-busting: Attaches a time stamp as version to style.css.
 *
 * Checks for minified version of style.css. Uses style.min.css if the correct version is available and is at least 20%
 * of original size, otherwise falls back to style.css. Also sends style sheet out for minification and saves for next
 * run.
 *
 * @return string Hex color code for primary color.
 */


/**
 * Remove child theme style sheet
 * @uses genesis_meta  <genesis/lib/css/load-styles.php>
 */
remove_action( 'genesis_meta', 'genesis_load_stylesheet' );

/**
 * Enqueue Genesis child theme style sheet at higher priority
 * @uses wp_enqueue_scripts <http://codex.wordpress.org/Function_Reference/wp_enqueue_style>
 */
add_action( 'wp_enqueue_scripts', 'genesis_cache_bust_load_stylesheet', 15 );
/**
 * Get the (date &) time the theme's CSS was last modified
 * and use it to bust the cache by appending
 * it to the CSS output.
 * // th-- added year, month, date
 *
 * @author FAT Media / th--
 * @link   http://youneedfat.com
 */
function genesis_cache_bust_load_stylesheet() {
	// Get the stylesheet info.
	$stylesheet_uri      = get_stylesheet_directory_uri() . '/style.css';
	$stylesheet_location = get_stylesheet_directory() . '/style.css';
	$last_modified       = date( "Y-m-d_h.i.s", filemtime( $stylesheet_location ) );

	// Get location of minified version and use that if it exists
	$min_stylesheet_location = get_stylesheet_directory() . '/css/main-min/' . $last_modified . '/style.min.css';


	/** MINIFICATION - turn off for development, if TH_WHICH_ENVIRONMENT (set by Which Environment) is not being defined */
	if ( ! defined( 'TH_WHICH_ENVIRONMENT' ) || TH_WHICH_ENVIRONMENT == 'live' ) {

		if ( file_exists( $min_stylesheet_location ) ) {

			$original_file_size          = filesize( $stylesheet_location );
			$min_file_size               = filesize( $min_stylesheet_location );
			$fraction_of_original        = $min_file_size / $original_file_size;
			$min_at_least_twenty_percent = $fraction_of_original > 0.2;

			if ( $min_at_least_twenty_percent ) {
				$stylesheet_uri = get_stylesheet_directory_uri() . '/css/main-min/' . $last_modified . '/style.min.css';
			}

		} else {

			// If minified version does not exist, create it.
			add_action( 'shutdown', 'th_minify_main_style' );

		}

	}

	// Enqueue the stylesheet.
	wp_enqueue_style( 'th-main-style-versioned', $stylesheet_uri, array(), $last_modified );
}

/** Create a directory (directories), call th_get_minified_version() to get minified version, and delete previous directories */
/** Add to .gitignore:
 *    wp-content/themes/parkdalewire/css/main-min/
 *    wp-content/themes/parkdalewire/css/main-min/**
 **/
function th_minify_main_style() {

	$stylesheet_uri      = get_stylesheet_directory_uri() . '/style.css';
	$stylesheet_location = get_stylesheet_directory() . '/style.css';
	$last_modified       = date( "Y-m-d_h.i.s", filemtime( $stylesheet_location ) );

	if ( ! is_dir( get_stylesheet_directory() . '/css' ) ) {
		mkdir( get_stylesheet_directory() . '/css', 0775 );
	}

	if ( ! is_dir( get_stylesheet_directory() . '/css/main-min' ) ) {
		mkdir( get_stylesheet_directory() . '/css/main-min', 0775 );
	}

	mkdir( get_stylesheet_directory() . '/css/main-min/' . $last_modified, 0775 );

	$new_min_folder = get_stylesheet_directory() . '/css/main-min/' . $last_modified;

	if ( is_dir( $new_min_folder ) ) {

		// Get minified version and write the file
		$min_css_content = th_get_minified_version();
		file_put_contents( $new_min_folder . '/style.min.css', $min_css_content );

		// Delete previous minified versions and their folders
		th_delete_unused_css_min_folders( $last_modified );

	}
}

/** Get minified version.  Called by th_minify_main_style */
/** https://cssminifier.com/php */
function th_get_minified_version() {

	// setup the URL and read the CSS from a file
	$url = 'https://cssminifier.com/raw';
	$css = file_get_contents( get_stylesheet_directory() . '/style.css' );

	// init the request, set various options, and send it
	$ch = curl_init();

	curl_setopt_array( $ch, [
		CURLOPT_URL            => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POST           => true,
		CURLOPT_HTTPHEADER     => [ "Content-Type: application/x-www-form-urlencoded" ],
		CURLOPT_POSTFIELDS     => http_build_query( [ "input" => $css ] )
	] );

	$minified = curl_exec( $ch );

	// finally, close the request
	curl_close( $ch );

	// output the $minified css
	return $minified;
}

/** Delete unused versions of style.min.css and their folders */
function th_delete_unused_css_min_folders( $last_modified ) {

	$css_directory = get_stylesheet_directory() . '/css/main-min';

	$directories = scandir( $css_directory );

	if ( file_exists( $css_directory . '/min-css-log.txt' ) ) {
		$log = file_get_contents( $css_directory . '/min-css-log.txt' );
	} else {
		$log = "Versions: ";
	}

	$stylesheet_location     = get_stylesheet_directory() . '/style.css';
	$min_stylesheet_location = get_stylesheet_directory() . '/css/main-min/' . $last_modified . '/style.min.css';

	if ( file_exists( $min_stylesheet_location ) ) {

		$original_file_size = filesize( $stylesheet_location );
		$min_file_size      = filesize( $min_stylesheet_location );

		$fraction_of_original = $min_file_size / $original_file_size;
		$fraction_in_percent  = round( $fraction_of_original, 3 ) * 100;

		$min_at_least_twenty_percent = $fraction_of_original > 0.2;
		$min_at_least_twenty_percent = ( $min_at_least_twenty_percent ) ? 'true' : 'false';

	} else {

		$min_file_size               = 'unknown';
		$min_at_least_twenty_percent = 'unknown';
		$fraction_in_percent         = 'unknown';

	}

	file_put_contents( $css_directory . '/min-css-log.txt', $log . '
' . $last_modified . ' -- ' . $min_file_size . ' bytes' . ' -- Minimum 20% size: ' . $min_at_least_twenty_percent . ' (' . $fraction_in_percent . '%)' );

	foreach ( $directories as $directory ) {

		if ( strpos( $directory, $last_modified ) === false ) {

			if ( preg_match( '/\d{4}-\d{2}-\d{2}_\d{2}\.\d{2}\.\d{2}/', $directory ) ) {

				$full_path = $css_directory . '/' . $directory;

				$files = glob( $full_path . '/*' ); //get all file names
				foreach ( $files as $file ) {
					if ( is_file( $file ) ) {
						unlink( $file );
					} //delete file
				}

				rmdir( $full_path );

			}

		}

	}

}
