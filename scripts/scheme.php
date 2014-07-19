<?php
/**
 * Adding the query var that will be used to trigger
 * the custom scheme CSS output. We do this to avoid
 * having to use wp-load.php here.
 *
 * @link http://ottopress.com/2010/dont-include-wp-load-please/
 *
 * @return $vars
 * @filter query_vars
 */
function churchthemes_scheme_add_trigger( $vars ) {
	$vars[] = 'ct_scheme_css';
	return $vars;
}
add_filter( 'query_vars', 'churchthemes_scheme_add_trigger' );


/**
 * If the query var exists in a URL then we will
 * print out the CSS.
 *
 * @see churchthemes_register() in lib/functions/functions.php
 *
 * @action template_redirect
 */
function churchthemes_scheme_trigger_output() {
	if ( get_query_var( 'ct_scheme_css' ) ) :

		$theme_options = get_option('ct_theme_options');

		$ct_main_color = ( $theme_options['main_color'] && ( $theme_options['main_color'] !== '#' ) ) ? $theme_options['main_color'] : CHURCHTHEMES_OPTIONS_MAIN_COLOR;
		$ct_slider_controls_active = ( defined( 'CHURCHTHEMES_SLIDER_CONTROLS_ACTIVE' ) ) ? CHURCHTHEMES_SLIDER_CONTROLS_ACTIVE : $ct_main_color;
		$ct_logo = ( $theme_options['logo'] ) ? $theme_options['logo'] : null;
		$ct_logo_width = ( $theme_options['logo_width'] ) ? $theme_options['logo_width'] : CHURCHTHEMES_OPTIONS_LOGO_WIDTH;
		$ct_logo_height = ( $theme_options['logo_height'] ) ? $theme_options['logo_height'] : CHURCHTHEMES_OPTIONS_LOGO_HEIGHT;
		$ct_logo_top_margin = ( $theme_options['logo_top_margin'] ) ? $theme_options['logo_top_margin'] : CHURCHTHEMES_OPTIONS_LOGO_TOP_MARGIN;

		if ( !ob_start( 'ob_gzhandler' ) ) {
			ob_start();
		}
		header( 'Content-type: text/css; charset: UTF-8' );
		header( 'Cache-control: must-revalidate' );
?>
#header .logo {
	margin-top:<?php echo intval( $ct_logo_top_margin ) ?>px;
}

#header .logo a {
<?php if ( $ct_logo ) : ?>
	background:url(<?php echo esc_url( $ct_logo ) ?>) no-repeat;
<?php endif; ?>
	width:<?php echo intval( $ct_logo_width ) ?>px;
	max-width:<?php echo intval( $ct_logo_width ) ?>px;
	height:<?php echo intval( $ct_logo_height ) ?>px;
}

a,
a:visited {
	color:<?php echo esc_html( $ct_main_color ) ?>;
}

h1 a:hover,
h2 a:hover,
h3 a:hover,
h4 a:hover,
h5 a:hover,
h6 a:hover,
h1 a:visited:hover,
h2 a:visited:hover,
h3 a:visited:hover,
h4 a:visited:hover,
h5 a:visited:hover,
h6 a:visited:hover {
	color:<?php echo esc_html( $ct_main_color ) ?>;
}

::selection,
::-moz-selection {
	background:<?php echo esc_html( $ct_main_color ) ?>;
}

blockquote {
	border-left:3px solid <?php echo esc_html( $ct_main_color ) ?>;
}

.navbar ul li a:hover {
	color:<?php echo esc_html( $ct_main_color ) ?>;
}

.mask .slide_content h3.subtitle {
	color:<?php echo esc_html( $ct_main_color ) ?>;
}

.pag_box ol a:hover,
.pag_box ol a.flex-active {
	background:<?php echo $ct_slider_controls_active; ?>;
}

.list_locations li .link:hover {
	color:<?php echo esc_html( $ct_main_color ) ?>;
}

.list_widget li a:hover {
	color:<?php echo esc_html( $ct_main_color ) ?>;
}

.pagination li:hover,
.pagination li.active {
	background:<?php echo esc_html( $ct_main_color ) ?>;
}

.selectbox-wrapper ul li.selected,
.selectbox-wrapper ul li.current {
	background:<?php echo esc_html( $ct_main_color ) ?>;
}

.search-excerpt {
	color:<?php echo esc_html( $ct_main_color ) ?>;
}

.single-location small a {
	color:<?php echo esc_html( $ct_main_color ) ?> !important;
}


/* Events Manager Styles */
table.em-calendar td.eventful a,
table.em-calendar td.eventful-today a {
	color:<?php echo esc_html( $ct_main_color ) ?> !important;
}
.ui-state-hover {
	color:<?php echo esc_html( $ct_main_color ) ?> !important;
}
.ui-datepicker-today .ui-state-highlight {
	background:<?php echo esc_html( $ct_main_color ) ?> !important;
}


/* Audio Player Plugin Styles */
.mejs-container .mejs-controls .mejs-time-rail .mejs-time-loaded {
	background: <?php echo esc_html( $ct_main_color ) ?>;
	background: rgba(<?php echo implode( ',', churchthemes_hex_to_rgb( $ct_main_color ) ) ?>,0.8);
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(rgba(<?php echo implode( ',', churchthemes_hex_to_rgb( $ct_main_color ) ) ?>,0.5)), to(rgba(<?php echo implode( ',', churchthemes_hex_to_rgb( $ct_main_color ) ) ?>,1.0)));
	background: -webkit-linear-gradient(top, rgba(<?php echo implode( ',', churchthemes_hex_to_rgb( $ct_main_color ) ) ?>,0.5), rgba(<?php echo implode( ',', churchthemes_hex_to_rgb( $ct_main_color ) ) ?>,1.0));
	background: -moz-linear-gradient(top, rgba(<?php echo implode( ',', churchthemes_hex_to_rgb( $ct_main_color ) ) ?>,0.5), rgba(<?php echo implode( ',', churchthemes_hex_to_rgb( $ct_main_color ) ) ?>,1.0));
	background: -o-linear-gradient(top, rgba(<?php echo implode( ',', churchthemes_hex_to_rgb( $ct_main_color ) ) ?>,0.5), rgba(<?php echo implode( ',', churchthemes_hex_to_rgb( $ct_main_color ) ) ?>,1.0));
	background: -ms-linear-gradient(top, rgba(<?php echo implode( ',', churchthemes_hex_to_rgb( $ct_main_color ) ) ?>,0.5), rgba(<?php echo implode( ',', churchthemes_hex_to_rgb( $ct_main_color ) ) ?>,1.0));
	background: linear-gradient(rgba(<?php echo implode( ',', churchthemes_hex_to_rgb( $ct_main_color ) ) ?>,0.5), rgba(<?php echo implode( ',', churchthemes_hex_to_rgb( $ct_main_color ) ) ?>,1.0));
}


/* Reftagger Plugin Styles */
.lbsTooltipFooter a:hover {
	color:<?php echo esc_html( $ct_main_color ) ?>;
}
<?php
		exit;
	endif;
}
add_action( 'template_redirect', 'churchthemes_scheme_trigger_output' );
