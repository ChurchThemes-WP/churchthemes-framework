<?php
/**
 * Return an ID of an attachment by searching the database with the file URL.
 *
 * First checks to see if the $url is pointing to a file that exists in
 * the wp-content directory. If so, then we search the database for a
 * partial match consisting of the remaining path AFTER the wp-content
 * directory. Finally, if a match is found the attachment ID will be
 * returned.
 *
 * @return {int} $attachment
 */
function churchthemes_get_attachment_id_by_url( $url ) {

	$parse_url  = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );

	$this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
	$file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );

	if ( !isset( $parse_url[1] ) || empty( $parse_url[1] ) || ( $this_host != $file_host ) ) {
		return;
	}

	global $wpdb;

	$prefix     = $wpdb->prefix;
	$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM " . $prefix . "posts WHERE guid RLIKE %s;", $parse_url[1] ) );

	return $attachment[0];
}


/**
 * Adding the query var that will be used to trigger
 * the force download script. We do this to avoid having to
 * resort to using wp-load.php in a separate download script.
 *
 * @link http://ottopress.com/2010/dont-include-wp-load-please/
 *
 * @return $vars
 * @filter query_vars
 */
function churchthemes_force_download_trigger( $vars ) {
	$vars[] = 'ct_download';
	return $vars;
}
add_filter( 'query_vars', 'churchthemes_force_download_trigger' );


/**
 * If the query var exists in a URL then we will trigger the
 * script to force download a file by URL.
 *
 * @see churchthemes_force_download_trigger()
 *
 * @action template_redirect
 */
function churchthemes_force_download() {

	if ( !get_query_var( 'ct_download' ) ) {
		return;
	}

	// Get the file URL
	$file = ( $_REQUEST['ct_download'] ) ? esc_url_raw( $_REQUEST['ct_download'] ) : null;

	// Get the file extension
	$ext = pathinfo( $file, PATHINFO_EXTENSION );

	// Kill the script if there is no file or file extension
	if ( empty( $file ) || empty( $ext ) ) {
		return;
	}

	// Make sure a file really exists, otherwise kill the script
	$response = wp_remote_get( $file );
	if ( is_wp_error( $response ) ) {
		$error_message = $response->get_error_message();
		die ( "<h1>Script Error</h1><p>Something went wrong: $error_message. Please ensure the file at this path exists.</p>" );
	}

	// Convert URL to a path
	$path = get_attached_file( churchthemes_get_attachment_id_by_url( $file ) );

	// Fallback to URL if no local attachment is found
	$path = empty( $path ) ? $file : $path;

	// Required to access URL objects
	if ( !ini_get( 'allow_url_fopen' ) ) {
		@ini_set( 'allow_url_fopen', 'On' );
	}

	// Required for IE, otherwise Content-Disposition is ignored
	if ( ini_get( 'zlib.output_compression' ) ) {
		@ini_set( 'zlib.output_compression', 'Off' );
	}

	// Get the file extension to serve the correct Content-Type
	switch ( $ext ) {
		case "pdf"  : $type = 'application/pdf';               break;
		case "exe"  : $type = 'application/octet-stream';      break;
		case "dmg"  : $type = 'application/octet-stream';      break;
		case "zip"  : $type = 'application/zip';               break;
		case "doc"  : $type = 'application/msword';            break;
		case "xls"  : $type = 'application/vnd.ms-excel';      break;
		case "ppt"  : $type = 'application/vnd.ms-powerpoint'; break;
		case "ogg"  : $type = 'application/ogg';               break;
		case "swf"  : $type = 'application/x-shockwave-flash'; break;
		case "xml"  : $type = 'application/xml';               break;
		case "xhtml": $type = 'application/xhtml+xml';         break;
		case "txt"  : $type = 'text/plain';                    break;
		case "rtf"  : $type = 'text/rtf';                      break;
		case "htm"  : $type = 'text/html';                     break;
		case "html" : $type = 'text/html';                     break;
		case "bmp"  : $type = 'image/bmp';                     break;
		case "gif"  : $type = 'image/gif';                     break;
		case "png"  : $type = 'image/png';                     break;
		case "jpeg" : $type = 'image/jpg';                     break;
		case "jpg"  : $type = 'image/jpg';                     break;
		case "tif"  : $type = 'image/tiff';                    break;
		case "tiff" : $type = 'image/tiff';                    break;
		case 'aif'  : $type = 'audio/x-aiff';                  break;
		case 'mp3'  : $type = 'audio/mpeg';                    break;
		case 'm4a'  : $type = 'audio/mp4a-latm';               break;
		case 'midi' : $type = 'audio/midi';                    break;
		case 'wav'  : $type = 'audio/x-wav';                   break;
		case 'm4v'  : $type = 'video/x-m4v';                   break;
		case 'mp4'  : $type = 'video/mp4';                     break;
		case 'mpeg' : $type = 'video/mpeg';                    break;
		case 'mpg'  : $type = 'video/mpeg';                    break;
		case 'mov'  : $type = 'video/quicktime';               break;
		case 'avi'  : $type = 'video/x-msvideo';               break;
		default     : $type = 'application/octet-stream';
	}
	header( 'Content-Type: ' . $type );

	// Set the remaining required headers
	header( 'Content-Description: File Transfer' );
	header( 'Content-Disposition: attachment; filename="' . basename( $path ) . '";' );
	header( 'Content-Transfer-Encoding: binary' );
	header( 'Expires: 0' );
	header( 'Pragma: public' );
	header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
	header( 'Cache-Control: private', false ); // Required for certain browsers
	header( 'Robots: none' );
	header( 'Connection: close' );

	// Attempt to fetch the filesize
	if ( $filesize = @filesize( $path ) ) {
		header( 'Content-Length: ' . $filesize );
	}

	// Fetch the file for download
	ob_clean();
	flush();
	@readfile( $path );
	exit;
}
add_action( 'template_redirect', 'churchthemes_force_download' );
