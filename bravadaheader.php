<?php
/**
 * Plugin Name:     Bravada Header
 * Description:     Anpassung Header des Bravada-Themes
 * Author:          Bego Mario Garde
 * Author URI:      https://pixolin.de
 * Text Domain:     bravadaheader
 * Version:         0.1.0
 *
 * @package         Bravadaheader
 */

// If called without WordPress, exit.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$theme = wp_get_theme(); // gets the current theme
if ( ! 'Bravada' == $theme->name && ! 'Bravada' == $theme->parent_theme ) {
	return;
}


add_action( 'init', 'remove_functions', 15 );

/**
 * Entferne Header-Title-Funktion des Themes
 *
 * @return void
 */
function remove_functions() {
	remove_action( 'cryout_headerimage_hook', 'bravada_header_title', 100 );
};

add_action( 'cryout_headerimage_hook', 'pix_header_title' );

/**
 * Fügt eine angepasste Version der Header-Title-Funktion
 * des Themes hinzu.
 *
 * @return void
 */
function pix_header_title() {
	if ( cryout_on_landingpage() && cryout_get_option( 'theme_lpslider' ) != 3 ) {
		return; // if on landing page and static slider not set to header image, exit.
	}
	if ( bravada_header_title_check() ) :
		?>
	<div id="header-page-title">
		<div id="header-page-title-inside">
			<?php if ( is_author() ) { ?>
				<div id="author-avatar" <?php cryout_schema_microdata( 'image' ); ?>>
					<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'bravada_author_bio_avatar_size', 80 ), '', '', array( 'extra_attr' => cryout_schema_microdata( 'url', 0 ) ) ); ?>
				</div><!-- #author-avatar -->
			<?php } ?>
			<div class="entry-meta pretitle-meta">
				<?php cryout_headertitle_topmeta_hook(); ?>
			</div><!-- .entry-meta -->
			<?php
			if ( is_front_page() ) {
				// TODO : Am Ende der Zeile Text anpassen.
				echo '<h2 class="entry-title" ' . cryout_schema_microdata( 'entry-title', 0 ) . '>' . esc_html( get_bloginfo( 'name', 'display' ) ) . '</h2><p class="byline"><a href="https://wordpress.org/">Bildhauerei</a> &middot; <a href="https://wordpress.org/">Malerei</a> &middot; <a href="https://wordpress.org/">Druckgrafik</a></p>';
			} elseif ( is_singular() ) {
				the_title( '<div class="entry-title">', '</div>' );
			} else {
				echo '<div class="entry-title">';
				if ( is_home() ) {
					single_post_title();
				}
				if ( function_exists( 'is_shop' ) && is_shop() ) {
					echo wp_kses( get_the_title( wc_get_page_id( 'shop' ) ), array() );
				} elseif ( is_archive() ) {
					echo wp_kses( get_the_archive_title(), array() );
				}
				if ( is_search() ) {
					printf( __( 'Search Results for: %s', 'bravada' ), '' . get_search_query() . '' );
				}
				if ( is_404() ) {
					_e( 'Not Found', 'bravada' );
				}
				echo '</div>';
			}
			?>
			<div class="entry-meta aftertitle-meta">
			<?php cryout_headertitle_bottommeta_hook(); ?>
				<?php cryout_breadcrumbs_hook(); ?>
			</div><!-- .entry-meta -->
			<div class="byline">
				<?php
				if ( is_singular( array( 'post', 'page' ) ) && has_excerpt() && cryout_get_option( 'theme_meta_single_byline' ) ) {
					echo wp_kses_post( get_the_excerpt() );
				}
				if ( ( ( is_archive() && ! function_exists( 'is_shop' ) ) || ( is_archive() && ! is_shop() ) ) && cryout_get_option( 'theme_meta_blog_byline' ) ) {
					echo wp_kses_post( get_the_archive_description() );
				}
				if ( is_search() ) {
					echo get_search_form();
				}
				?>
			</div>
		</div>
	</div>
			<?php
	endif;

}
