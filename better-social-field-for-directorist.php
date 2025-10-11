<?php
/**
 * Plugin Name: Better Social Field for Directorist
 * Plugin URI: https://github.com/obiplabon/better-social-field-for-directorist
 * Description: Add a better social field for Directorist
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Tested up to: 6.2
 * Author: Md Obidullah (obiPlabon)
 * Author URI: https://obiplabon.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: better-social-field-for-directorist
 * Requires Plugins: directorist
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class Better_social_field_for_directorist {
	
	public static function get_instance() {
		static $instance = null;
		if ( $instance === null ) {
			$instance = new self();
		}
		return $instance;
	}

	public function init() {
		add_filter( 'directorist_template_file_path', [$this, 'override_templates'], 10, 2 );

		add_filter( 'atbdp_extension_settings_submenu', [ $this, 'add_settings_submenu' ] );
		add_filter( 'atbdp_listing_type_settings_field_list', array( $this, 'register_setting_fields' ) );
	}

	public function override_templates( $file_path, $template_name ) {
		if ( 'listing-form/fields/social_info' === $template_name ) {
			$file_path = dirname( __FILE__ ) . '/templates/listing-form/fields/social_info.php';
		}

		if ( 'single/fields/social_info' === $template_name ) {
			$file_path = dirname( __FILE__ ) . '/templates/single/fields/social_info.php';
		}
		
		return $file_path;
	}

	public function get_default_social_media(): array {
		$default = [
			'facebook|Facebook|fa fa-facebook|Facebook URL',
			'twitter|X|fa fa-twitter|Twitter URL',
			'linkedin|LinkedIn|fa fa-linkedin|LinkedIn URL',
			'pinterest|Pinterest|fa fa-pinterest|Pinterest URL',
			'instagram|Instagram|fa fa-instagram|Instagram URL',
			'tumblr|Tumblr|fa fa-tumblr|Tumblr URL',
			'flickr|Flickr|fa fa-flickr|Flickr URL',
			'snapchat|Snapchat|fa fa-snapchat|Snapchat URL',
			'reddit|Reddit|fa fa-reddit|Reddit URL',
			'youtube|Youtube|fa fa-youtube|Youtube URL',
			'vimeo|Vimeo|fa fa-vimeo|Vimeo URL',
			'vine|Vine|fa fa-vine|Vine URL',
			'github|Github|fa fa-github|Github URL',
			'dribbble|Dribbble|fa fa-dribbble|Dribbble URL',
			'behance|Behance|fa fa-behance|Behance URL',
			'soundcloud|SoundCloud|fa fa-soundcloud|SoundCloud URL',
			'stack-overflow|StackOverFLow|fa fa-stack-overflow|StackOverFLow URL',
		];

		return apply_filters( 'bsfd_default_social_media', $default );
	}

	public function get_supported_social_media(): array {
		$social_media = wp_cache_get( 'bsfd_supported_social_media' );
		
		if ( false !== $social_media ) {
			return $social_media;
		}

		$social_media = get_directorist_option( 'bsfd_supported_social_media', '' );
		
		if ( empty( $social_media ) ) {
			$social_media = implode( "\n", $this->get_default_social_media() );
		}

		$social_media = array_map( 'trim', explode( "\n", $social_media ) );
		$social_media = array_filter( $social_media );
		$seen_ids = [];
		$social_media = array_filter( array_map( function( $item ) use ( &$seen_ids ) {
			$parts = explode( '|', $item );
			$id = $parts[0] ?? '';
			if ( ! $id || isset( $seen_ids[ $id ] ) ) {
				return null;
			}
			$seen_ids[ $id ] = true;
			return [
				'id'         => $id,
				'label'      => $parts[1] ?? ucwords( $id ),
				'icon'       => $parts[2] ?? 'fa fa-' . $id,
				/* translators: %s: social media ID */
				'placeholder'=> $parts[3] ?? sprintf( __( 'Enter your %s profile URL', 'better-social-field-for-directorist' ), $id ),
			];
		}, $social_media ) );

		$social_media = (array) apply_filters( 'bsfd_supported_social_media', $social_media );

		wp_cache_set( 'bsfd_supported_social_media', $social_media );

		return $social_media;
	}

	public function add_settings_submenu( $submenu ) {
		$submenu['better_social_field_submenu'] = [
			'label'    => __('Better Social Field', 'better-social-field-for-directorist'),
			'icon'     => '<i class="fa fa-connectdevelop"></i>',
			'sections' => [
				'general_section' => [
					'title'       => __('Better Social Field', 'better-social-field-for-directorist'),
					'description' => __('Format: <code>id|Label|icon-class|placeholder</code><br>e.g: <code>facebook|Facebook|fab fa-facebook|Enter your Facebook profile URL</code>', 'better-social-field-for-directorist'),
					'fields'      => [ 'bsfd_supported_social_media'],
				],
			]
		];

		return $submenu;
	}

	public function register_setting_fields( $fields ) {
		$fields['bsfd_supported_social_media'] = [
			'label'       => __( 'Supported Social Media', 'better-social-field-for-directorist' ),
			'type'        => 'textarea',
			'description' => __( 'Enter one social network per line. When empty, the default set will be used.', 'better-social-field-for-directorist' ),
			'value'       => implode( "\n", $this->get_default_social_media() ),
			'placeholder' => 'facebook|Facebook|fab fa-facebook|Enter your Facebook profile URL',
		];

		return $fields;
	}
}

function bsfd() {
	return Better_social_field_for_directorist::get_instance();
}

bsfd()->init();
