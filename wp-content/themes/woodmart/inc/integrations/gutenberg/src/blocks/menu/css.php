<?php
use XTS\Gutenberg\Block_CSS;

$block_css = new Block_CSS( $attrs );

$block_css->add_css_rules(
	$block_selector,
	array(
		array(
			'attr_name' => 'align',
			'template'  => '--wd-align: var(--wd-{{value}});',
		),
	)
);

$block_css->add_css_rules(
	$block_selector . ' > .wd-nav',
	array(
		// itemsColor.
		array(
			'attr_name' => 'itemsColorCode',
			'template'  => '--nav-color: {{value}};',
		),
		array(
			'attr_name' => 'itemsColorVariable',
			'template'  => '--nav-color: var({{value}});',
		),
		array(
			'attr_name' => 'itemsHoverColorCode',
			'template'  => '--nav-color-hover: {{value}};',
		),
		array(
			'attr_name' => 'itemsHoverColorVariable',
			'template'  => '--nav-color-hover: var({{value}});',
		),
		// itemsBgColorCode.
		array(
			'attr_name' => 'itemsBgColorCode',
			'template'  => '--nav-bg: {{value}};',
		),
		array(
			'attr_name' => 'itemsBgColorVariable',
			'template'  => '--nav-bg: var({{value}});',
		),
		array(
			'attr_name' => 'itemsBgHoverColorCode',
			'template'  => '--nav-bg-hover: {{value}};',
		),
		array(
			'attr_name' => 'itemsBgHoverColorVariable',
			'template'  => '--nav-bg-hover: var({{value}});',
		),
	)
);

if ( ! isset( $attrs['disable_active_style'] ) || ! $attrs['disable_active_style'] ) {
	$block_css->add_css_rules(
		$block_selector . ' > .wd-nav',
		array(
			array(
				'attr_name' => 'itemsActiveColorCode',
				'template'  => '--nav-color-active: {{value}};',
			),
			array(
				'attr_name' => 'itemsActiveColorVariable',
				'template'  => '--nav-color-active: var({{value}});',
			),
			array(
				'attr_name' => 'itemsBgActiveColorCode',
				'template'  => '--nav-bg-active: {{value}};',
			),
			array(
				'attr_name' => 'itemsBgActiveColorVariable',
				'template'  => '--nav-bg-active: var({{value}});',
			),
		)
	);
}

if ( 'vertical' === $attrs['design'] && 'simple' === $attrs['dropdown_design'] && 'custom' === $attrs['vertical_items_gap'] ) {
	$block_css->add_css_rules(
		$block_selector . ' > .wd-nav',
		array(
			// Gap.
			array(
				'attr_name' => 'customVerticalItemsGap',
				'template'  => '--nav-gap: {{value}}px;',
			),
		)
	);

	$block_css->add_css_rules(
		$block_selector . ' > .wd-nav',
		array(
			// Gap.
			array(
				'attr_name' => 'customVerticalItemsGapTablet',
				'template'  => '--nav-gap: {{value}}px;',
			),
		),
		'tablet'
	);

	$block_css->add_css_rules(
		$block_selector . ' > .wd-nav',
		array(
			// Gap.
			array(
				'attr_name' => 'customVerticalItemsGapMobile',
				'template'  => '--nav-gap: {{value}}px;',
			),
		),
		'mobile'
	);
} else if ( 'horizontal' === $attrs['design'] && 'custom' === $attrs['items_gap'] ) {
	$block_css->add_css_rules(
		$block_selector . ' > .wd-nav',
		array(
			// Gap.
			array(
				'attr_name' => 'customItemsGap',
				'template'  => '--nav-gap: {{value}}px;',
			),
		)
	);
	
	$block_css->add_css_rules(
		$block_selector . ' > .wd-nav',
		array(
			// Gap.
			array(
				'attr_name' => 'customItemsGapTablet',
				'template'  => '--nav-gap: {{value}}px;',
			),
		),
		'tablet'
	);

	$block_css->add_css_rules(
		$block_selector . ' > .wd-nav',
		array(
			// Gap.
			array(
				'attr_name' => 'customItemsGapMobile',
				'template'  => '--nav-gap: {{value}}px;',
			),
		),
		'mobile'
	);
}

$block_css->add_css_rules(
	$block_selector . ' > .wd-nav > li > a .wd-nav-img',
	array(
		array(
			'attr_name' => 'iconWidth',
			'template'  => '--nav-img-width: {{value}}px;',
		),
		array(
			'attr_name' => 'iconHeight',
			'template'  => '--nav-img-height: {{value}}px;',
		),
	)
);

$block_css->add_css_rules(
	$block_selector,
	array(
		array(
			'attr_name' => 'alignTablet',
			'template'  => '--wd-align: var(--wd-{{value}});',
		),
	),
	'tablet'
);

$block_css->add_css_rules(
	$block_selector . ' > .wd-nav > li > a .wd-nav-img',
	array(
		array(
			'attr_name' => 'iconWidthTablet',
			'template'  => '--nav-img-width: {{value}}px;',
		),
		array(
			'attr_name' => 'iconHeightTablet',
			'template'  => '--nav-img-height: {{value}}px;',
		),
	),
	'tablet'
);

$block_css->add_css_rules(
	$block_selector,
	array(
		array(
			'attr_name' => 'alignMobile',
			'template'  => '--wd-align: var(--wd-{{value}});',
		),
	),
	'mobile'
);

$block_css->add_css_rules(
	$block_selector . ' > .wd-nav > li > a .wd-nav-img',
	array(
		array(
			'attr_name' => 'iconWidthMobile',
			'template'  => '--nav-img-width: {{value}}px;',
		),
		array(
			'attr_name' => 'iconHeightMobile',
			'template'  => '--nav-img-height: {{value}}px;',
		),
	),
	'mobile'
);

$block_css->merge_with( wd_get_block_typography_css( $block_selector . ' > .wd-nav > li > a', $attrs, 'itemTp' ) );

$block_css->merge_with(
	wd_get_block_advanced_css(
		array(
			'selector'       => $block_selector,
			'selector_hover' => $block_selector_hover,
		),
		$attrs
	)
);

$block_css->merge_with( wd_get_block_box_shadow_css( $block_selector . ' > .wd-nav', $attrs, 'itemsBoxShadow', '--nav-shadow' ) );
$block_css->merge_with( wd_get_block_box_shadow_css( $block_selector . ' > .wd-nav', $attrs, 'itemsBoxShadowHover', '--nav-shadow-hover' ) );

$block_css->merge_with( wd_get_block_border_css( $block_selector . ' > .wd-nav', $attrs, 'itemsBorder', '--nav-border', '--nav-radius' ) );
$block_css->merge_with( wd_get_block_border_css( $block_selector . ' > .wd-nav', $attrs, 'itemsBorderHover', '--nav-border-hover', '--nav-radius-hover' ) );

$block_css->merge_with( wd_get_block_padding_css( $block_selector . ' > .wd-nav', $attrs, 'itemsPadding', '--nav-pd', true ) );

if ( ! isset( $attrs['disable_active_style'] ) || ! $attrs['disable_active_style'] ) {
	$block_css->merge_with( wd_get_block_box_shadow_css( $block_selector . ' > .wd-nav', $attrs, 'itemsBoxShadowActive', '--nav-shadow-active' ) );

	$block_css->merge_with( wd_get_block_border_css( $block_selector . ' > .wd-nav', $attrs, 'itemsBorderActive', '--nav-border-active', '--nav-radius-active' ) );
}

return $block_css->get_css_for_devices();
