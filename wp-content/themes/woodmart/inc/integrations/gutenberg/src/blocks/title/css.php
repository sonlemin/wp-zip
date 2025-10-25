<?php
use XTS\Gutenberg\Block_CSS;

$mark_selector = $block_selector . ' .wd-highlight';
$block_css     = new Block_CSS( $attrs );

$block_css->add_css_rules(
	$block_selector,
	array(
		array(
			'attr_name' => 'colorCode',
			'template'  => 'color: {{value}};',
		),
		array(
			'attr_name' => 'colorVariable',
			'template'  => 'color: var({{value}});',
		),

		array(
			'attr_name' => 'activeBorderColorCode',
			'template'  => '--wd-title-brd-color-act: {{value}};',
		),
		array(
			'attr_name' => 'activeBorderColorVariable',
			'template'  => '--wd-title-brd-color-act: var({{value}});',
		),
		array(
			'attr_name' => 'textAlign',
			'template'  => '--wd-align: var(--wd-{{value}});',
		),
	)
);

$block_css->add_css_rules(
	$block_selector_hover,
	array(
		array(
			'attr_name' => 'colorHoverCode',
			'template'  => 'color: {{value}};',
		),
		array(
			'attr_name' => 'colorHoverVariable',
			'template'  => 'color: var({{value}});',
		),
	)
);

$block_css->add_css_rules(
	'.wd-hover-parent:hover ' . $block_selector,
	array(
		array(
			'attr_name' => 'colorParentHoverCode',
			'template'  => 'color: {{value}};',
		),
		array(
			'attr_name' => 'colorParentHoverVariable',
			'template'  => 'color: var({{value}});',
		),
	)
);

$block_css->add_css_rules(
	$block_selector . '> img, ' . $block_selector . '> picture img, ' . $block_selector . ' > span img',
	array(
		array(
			'attr_name' => 'inlineImageWidth',
			'template'  => 'width: {{value}}px !important;',
		),
	)
);

$block_css->add_css_rules(
	$block_selector,
	array(
		array(
			'attr_name' => 'textAlignTablet',
			'template'  => '--wd-align: var(--wd-{{value}});',
		),
	),
	'tablet'
);

$block_css->add_css_rules(
	$block_selector . '> img, ' . $block_selector . '> picture img, ' . $block_selector . ' > span img',
	array(
		array(
			'attr_name' => 'inlineImageWidthTablet',
			'template'  => 'width: {{value}}px !important;',
		),
	),
	'tablet'
);

$block_css->add_css_rules(
	$block_selector,
	array(
		array(
			'attr_name' => 'textAlignMobile',
			'template'  => '--wd-align: var(--wd-{{value}});',
		),
	),
	'mobile'
);

$block_css->add_css_rules(
	$block_selector . '> img, ' . $block_selector . '> picture img, ' . $block_selector . ' > span img',
	array(
		array(
			'attr_name' => 'inlineImageWidthMobile',
			'template'  => 'width: {{value}}px !important;',
		),
	),
	'mobile'
);

$block_css->add_css_rules(
	$mark_selector,
	array(
		array(
			'attr_name' => 'markColorCode',
			'template'  => 'color: {{value}};',
		),
		array(
			'attr_name' => 'markColorVariable',
			'template'  => 'color: var({{value}});',
		),
	)
);

if ( ! empty( $attrs['gradientEnable'] ) ) {
	$gradient_position = ! empty( $attrs['gradientPosition'] ) ? $attrs['gradientPosition'] : 'center center';

	if ( ! empty( $attrs['gradient'] ) && false !== strpos( $attrs['gradient'], 'radial-gradient' ) ) {
		$gradient = str_replace( 'radial-gradient(', 'radial-gradient(at ' . $gradient_position . ',', $attrs['gradient'] );

		$block_css->add_to_selector(
			$mark_selector,
			'background-image: ' . $gradient . ';',
		);
	} else {
		$block_css->add_css_rules(
			$mark_selector,
			array(
				array(
					'attr_name' => 'gradient',
					'template'  => 'background-image: {{value}};',
				),
			)
		);
	}

	if ( ! empty( $attrs['gradientTablet'] ) && false !== strpos( $attrs['gradientTablet'], 'radial-gradient' ) ) {
		$gradient_position = ! empty( $attrs['gradientPositionTablet'] ) ? $attrs['gradientPositionTablet'] : $gradient_position;

		$gradient = str_replace( 'radial-gradient(', 'radial-gradient(at ' . $gradient_position . ',', $attrs['gradientTablet'] );

		$block_css->add_to_selector(
			$mark_selector,
			'background-image: ' . $gradient . ';',
			'tablet'
		);
	} else {
		$block_css->add_css_rules(
			$mark_selector,
			array(
				array(
					'attr_name' => 'gradientTablet',
					'template'  => 'background-image: {{value}};',
				),
			),
			'tablet'
		);
	}

	if ( ! empty( $attrs['gradientMobile'] ) && false !== strpos( $attrs['gradientMobile'], 'radial-gradient' ) ) {
		$gradient_position = ! empty( $attrs['gradientPositionMobile'] ) ? $attrs['gradientPositionMobile'] : $gradient_position;

		$gradient = str_replace( 'radial-gradient(', 'radial-gradient(at ' . $gradient_position . ',', $attrs['gradientMobile'] );

		$block_css->add_to_selector(
			$mark_selector,
			'background-image: ' . $gradient . ';',
			'mobile'
		);
	} else {
		$block_css->add_css_rules(
			$mark_selector,
			array(
				array(
					'attr_name' => 'gradientMobile',
					'template'  => 'background-image: {{value}};',
				),
			),
			'mobile'
		);
	}

	if ( ! empty( $attrs['gradient'] ) ) {
		$block_css->add_to_selector(
			$mark_selector,
			'-webkit-background-clip: text;',
		);

		$block_css->add_to_selector(
			$mark_selector,
			'background-clip: text;',
		);

		$block_css->add_to_selector(
			$mark_selector,
			'-webkit-text-fill-color: transparent;',
		);
	}

	if ( ! empty( $attrs['gradientTablet'] ) ) {
		$block_css->add_to_selector(
			$mark_selector,
			'-webkit-background-clip: text;',
			'tablet'
		);

		$block_css->add_to_selector(
			$mark_selector,
			'background-clip: text;',
			'tablet'
		);

		$block_css->add_to_selector(
			$mark_selector,
			'-webkit-text-fill-color: transparent;',
			'tablet'
		);
	}

	if ( ! empty( $attrs['gradientMobile'] ) ) {
		$block_css->add_to_selector(
			$mark_selector,
			'-webkit-background-clip: text;',
			'mobile'
		);

		$block_css->add_to_selector(
			$mark_selector,
			'background-clip: text;',
			'mobile'
		);

		$block_css->add_to_selector(
			$mark_selector,
			'-webkit-text-fill-color: transparent;',
			'mobile'
		);
	}
}

$block_css->merge_with( wd_get_block_typography_css( $block_selector, $attrs, 'tp' ) );
$block_css->merge_with( wd_get_block_typography_css( $mark_selector, $attrs, 'mark' ) );
$block_css->merge_with(
	wd_get_block_advanced_css(
		array(
			'selector'       => $block_selector,
			'selector_hover' => $block_selector_hover,
		),
		$attrs
	)
);

return $block_css->get_css_for_devices();
