<?php
/**
 * Review reminder class.
 *
 * @package woodmart
 */

namespace XTS\Modules\Review_Reminder;

use XTS\Admin\Modules\Options;

/**
 * Review reminder class.
 */
class Main {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'add_options' ) );

		woodmart_include_files(
			__DIR__,
			array(
				'./class-admin',
				'./class-frontend',
				'./class-emails',
			)
		);
	}

	/**
	 * Add options in theme settings.
	 *
	 * @return void
	 */
	public function add_options() {
		Options::add_field(
			array(
				'id'          => 'review_reminder_enabled',
				'name'        => esc_html__( 'Review reminder', 'woodmart' ),
				'description' => esc_html__( 'Enable this option to automatically send review reminder emails to customers after they make a purchase.', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'single_product_comments_section',
				'group'       => esc_html__( 'Review reminder', 'woodmart' ),
				'default'     => false,
				'priority'    => 320,
			)
		);

		Options::add_field(
			array(
				'id'           => 'review_reminder_sending_delay',
				'name'         => esc_html__( 'Sending delay', 'woodmart' ),
				'description'  => esc_html__( 'Set the delay after purchase before sending the review reminder email.', 'woodmart' ),
				'type'         => 'group',
				'section'      => 'single_product_comments_section',
				'group'        => esc_html__( 'Review reminder', 'woodmart' ),
				'inner_fields' => array(
					array(
						'id'         => 'review_reminder_sending_timeframe',
						'type'       => 'text_input',
						'attributes' => array(
							'type' => 'number',
							'min'  => 1,
						),
						'priority'   => 10,
						'default'    => 7,
					),
					array(
						'id'       => 'review_reminder_sending_timeframe_period',
						'type'     => 'select',
						'options'  => array(
							strval( MINUTE_IN_SECONDS ) => array(
								'name'  => esc_html__( 'Minutes', 'woodmart' ),
								'value' => strval( MINUTE_IN_SECONDS ),
							),
							strval( HOUR_IN_SECONDS )   => array(
								'name'  => esc_html__( 'Hours', 'woodmart' ),
								'value' => strval( HOUR_IN_SECONDS ),
							),
							strval( DAY_IN_SECONDS )    => array(
								'name'  => esc_html__( 'Days', 'woodmart' ),
								'value' => strval( DAY_IN_SECONDS ),
							),
						),
						'default'  => strval( DAY_IN_SECONDS ),
						'priority' => 20,
					),
				),
				'requires'    => array(
					array(
						'key'     => 'review_reminder_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
				),
				'priority'     => 330,
			)
		);
	}
}

new Main();
