<?php
/**
 * PW Base.
 *
 * This file adds the Customizer additions to the PW Base Theme.
 *
 * @package PW Base
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://www.studiopress.com/
 */

add_action( 'customize_register', 'pw_base_customizer_register' );
/**
 * Register settings and controls with the Customizer.
 *
 * @since 2.2.3
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function pw_base_customizer_register( $wp_customize ) {

	$wp_customize->add_setting(
		'pw_base_link_color',
		array(
			'default'           => pw_base_customizer_get_default_link_color(),
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'pw_base_link_color',
			array(
				'description' => __( 'Change the color of post info links, hover color of linked titles, hover color of menu items, and more.', 'pw-base' ),
				'label'       => __( 'Link Color', 'pw-base' ),
				'section'     => 'colors',
				'settings'    => 'pw_base_link_color',
			)
		)
	);

	$wp_customize->add_setting(
		'pw_base_accent_color',
		array(
			'default'           => pw_base_customizer_get_default_accent_color(),
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'pw_base_accent_color',
			array(
				'description' => __( 'Change the default hovers color for button.', 'pw-base' ),
				'label'       => __( 'Accent Color', 'pw-base' ),
				'section'     => 'colors',
				'settings'    => 'pw_base_accent_color',
			)
		)
	);

}
