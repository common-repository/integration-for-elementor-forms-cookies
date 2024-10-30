<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

class Cookies_Integration_Action_After_Submit extends \ElementorPro\Modules\Forms\Classes\Action_Base {

	/**
	 * Get Name
	 *
	 * Return the action name
	 *
	 * @access public
	 * @return string
	 */
	public function get_name() {
		return 'cookies integration';
	}

	/**
	 * Get Label
	 *
	 * Returns the action label
	 *
	 * @access public
	 * @return string
	 */
	public function get_label() {
		return __( 'Cookies', 'cookies-elementor-integration' );
	}

	/**
	 * Register Settings Section
	 *
	 * Registers the Action controls
	 *
	 * @access public
	 * @param \Elementor\Widget_Base $widget
	 */
	public function register_settings_section( $widget ) {
		$widget->start_controls_section(
			'section_cookies-elementor-integration',
			[
				'label' => __( 'Cookies', 'cookies-elementor-integration' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

		$widget->add_control(
			'cookies_set_form_form_name',
			[
				'label' => __( 'Use form data for cookie name', 'cookies-elementor-integration' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'separator' => 'before',
				'description' => __( 'When enabled you can enter the form field id in the cookie name field below', 'cookies-elementor-integration' ),
			]
		);

		$widget->add_control(
			'cookie_name',
			[
				'label' => __( 'Cookie name', 'cookies-elementor-integration' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'cookiename',
				'label_block' => true,
				'separator' => 'before',
				'description' => __( 'Enter your cookie name', 'cookies-elementor-integration' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			'cookies_set_form_form_value',
			[
				'label' => __( 'Use form data for cookie value', 'cookies-elementor-integration' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'separator' => 'before',
				'description' => __( 'When enabled you can enter the form field id in the cookie value field below', 'cookies-elementor-integration' ),
			]
		);

		$widget->add_control(
			'cookie_value',
			[
				'label' => __( 'Cookie value', 'cookies-elementor-integration' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'cookievalue',
				'label_block' => true,
				'separator' => 'before',
				'description' => __( 'Enter your cookie value', 'cookies-elementor-integration' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			'cookie_time',
			[
				'label' => __( 'Cookie time', 'cookies-elementor-integration' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'placeholder' => '3600',
				'label_block' => true,
				'separator' => 'before',
				'description' => __( 'Enter your cookie time in seconds', 'cookies-elementor-integration' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->end_controls_section();

	}

	/**
	 * On Export
	 *
	 * Clears form settings on export
	 * @access Public
	 * @param array $element
	 */
	public function on_export( $element ) {
		unset(
			$element['cookie_name'],
			$element['cookies_set_form_form_name'],
			$element['cookie_value'],
			$element['cookies_set_form_form_value'],
			$element['cookie_time']
		);

		return $element;
	}

	/**
	 * Run
	 *
	 * Runs the action after submit
	 *
	 * @access public
	 * @param \ElementorPro\Modules\Forms\Classes\Form_Record $record
	 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
	 */
	public function run( $record, $ajax_handler ) {
		$settings = $record->get( 'form_settings' );

		//  Make sure that there is an cookie name set
		if ( empty( $settings['cookie_name'] ) ) {
			return;
		}

		//  Make sure that there is a cookie value set
		if ( empty( $settings['cookie_value'] ) ) {
			return;
		}

		//  if cookietime is empty set to 3600
		if ( empty( $settings['cookie_time'] ) ) {
			$cookietime = 3600;
		} else {
			$cookietime = $settings['cookie_time'];
		}

		// Get submitted Form data
		$raw_fields = $record->get( 'fields' );

		// Normalize the Form Data
		$fields = [];
		foreach ( $raw_fields as $id => $field ) {
			$fields[ $id ] = $field['value'];
		}

		//Check if switch is set to use form data - cookie name
		$useformdataforname = $settings['cookies_set_form_form_name'];
		if ($useformdataforname == "yes") {
			$cookiename = $fields[$settings['cookie_name']];
		}
		else {
			$cookiename = $settings['cookie_name'];
		}

		//Check if switch is set to use form data - cookie value
		$useformdataforvalue = $settings['cookies_set_form_form_value'];
		if ($useformdataforvalue == "yes") {
			$cookievalue = $fields[$settings['cookie_value']];
		}
		else {
			$cookievalue = $settings['cookie_value'];
		}

		//Set cookie here
		setcookie($cookiename, $cookievalue, time()+$cookietime, "/");
	}
}