<?php
use XTS\Gutenberg\Block_CSS;

$block_css = new Block_CSS( $attrs );

if ( 'horizontal' === $attrs['orientation'] ) {
	$block_css->add_css_rules(
		$block_selector,
		array(
			array(
				'attr_name' => 'align',
				'template'  => '--wd-align: var(--wd-{{value}});',
			),
		)
	);
}

$block_css->add_css_rules(
	$block_selector . ' .wd-nav-my-acc',
	array(
		array(
			'attr_name' => 'navColorCode',
			'template'  => '--nav-color: {{value}};',
		),
		array(
			'attr_name' => 'navColorVariable',
			'template'  => '--nav-color: var({{value}});',
		),
		array(
			'attr_name' => 'navHoverColorCode',
			'template'  => '--nav-color-hover: {{value}};',
		),
		array(
			'attr_name' => 'navHoverColorVariable',
			'template'  => '--nav-color-hover: var({{value}});',
		),
		array(
			'attr_name' => 'navActiveColorCode',
			'template'  => '--nav-color-active: {{value}};',
		),
		array(
			'attr_name' => 'navActiveColorVariable',
			'template'  => '--nav-color-active: var({{value}});',
		),

		array(
			'attr_name' => 'navBgColorCode',
			'template'  => '--nav-bg: {{value}};',
		),
		array(
			'attr_name' => 'navBgColorVariable',
			'template'  => '--nav-bg: var({{value}});',
		),
		array(
			'attr_name' => 'navBgColorHoverCode',
			'template'  => '--nav-bg-hover: {{value}};',
		),
		array(
			'attr_name' => 'navBgColorHoverVariable',
			'template'  => '--nav-bg-hover: var({{value}});',
		),
		array(
			'attr_name' => 'navBgColorActiveCode',
			'template'  => '--nav-bg-active: {{value}};',
		),
		array(
			'attr_name' => 'navBgColorActiveVariable',
			'template'  => '--nav-bg-active: var({{value}});',
		),
		array(
			'attr_name' => 'iconSize',
			'template'  => '--nav-icon-size: {{value}}px;',
		),
	)
);

$block_css->add_css_rules(
	$block_selector . ' .wd-nav-my-acc > li > a .wd-nav-icon',
	array(
		array(
			'attr_name' => 'iconColorCode',
			'template'  => 'color: {{value}};',
		),
		array(
			'attr_name' => 'iconColorVariable',
			'template'  => 'color: var({{value}});',
		),
	)
);

$block_css->add_css_rules(
	$block_selector . ' .wd-nav-my-acc > li:hover > a .wd-nav-icon',
	array(
		array(
			'attr_name' => 'iconColorHoverCode',
			'template'  => 'color: {{value}};',
		),
		array(
			'attr_name' => 'iconColorHoverVariable',
			'template'  => 'color: var({{value}});',
		),
	)
);

$block_css->add_css_rules(
	$block_selector . ' .wd-nav-my-acc > li.wd-active > a .wd-nav-icon',
	array(
		array(
			'attr_name' => 'iconColorActiveCode',
			'template'  => 'color: {{value}};',
		),
		array(
			'attr_name' => 'iconColorActiveVariable',
			'template'  => 'color: var({{value}});',
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
	$block_selector . ' .wd-nav-my-acc',
	array(
		array(
			'attr_name' => 'iconSizeTablet',
			'template'  => '--nav-icon-size: {{value}}px;',
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
	$block_selector . ' .wd-nav-my-acc',
	array(
		array(
			'attr_name' => 'iconSizeMobile',
			'template'  => '--nav-icon-size: {{value}}px;',
		),
	),
	'mobile'
);

$show_gap_control        = ( 'vertical' === $attrs['orientation'] && 'simple' === $attrs['nav_design'] ) || ( 'horizontal' === $attrs['orientation'] && 'inline' === $attrs['layout_type'] );
$show_custom_gap_control = 'custom' === $attrs['items_gap'] && $show_gap_control;

if ( $show_custom_gap_control ) {
	$block_css->add_css_rules(
		$block_selector . ' .wd-nav',
		array(
			array(
				'attr_name' => 'customItemsGap',
				'template'  => '--nav-gap: {{value}}px;',
			),
		)
	);

	$block_css->add_css_rules(
		$block_selector . ' .wd-nav',
		array(
			array(
				'attr_name' => 'customItemsGapTablet',
				'template'  => '--nav-gap: {{value}}px;',
			),
		),
		'tablet'
	);

	$block_css->add_css_rules(
		$block_selector . ' .wd-nav',
		array(
			array(
				'attr_name' => 'customItemsGapMobile',
				'template'  => '--nav-gap: {{value}}px;',
			),
		),
		'mobile'
	);
}

$block_css->merge_with( wd_get_block_box_shadow_css( $block_selector . ' .wd-nav-my-acc', $attrs, 'navBoxShadow', '--nav-shadow' ) );
$block_css->merge_with( wd_get_block_box_shadow_css( $block_selector . ' .wd-nav-my-acc', $attrs, 'navBoxShadowHover', '--nav-shadow-hover' ) );
$block_css->merge_with( wd_get_block_box_shadow_css( $block_selector . ' .wd-nav-my-acc', $attrs, 'navBoxShadowActive', '--nav-shadow-active' ) );

$block_css->merge_with( wd_get_block_border_css( $block_selector . ' .wd-nav-my-acc', $attrs, 'navBorder', '--nav-border', '--nav-radius' ) );
$block_css->merge_with( wd_get_block_border_css( $block_selector . ' .wd-nav-my-acc', $attrs, 'navBorderHover', '--nav-border-hover', '--nav-radius-hover' ) );
$block_css->merge_with( wd_get_block_border_css( $block_selector . ' .wd-nav-my-acc', $attrs, 'navBorderActive', '--nav-border-active', '--nav-radius-active' ) );

$block_css->merge_with( wd_get_block_padding_css( $block_selector . ' .wd-nav-my-acc', $attrs, 'itemsPadding', '--nav-pd', true ) );

$block_css->merge_with( wd_get_block_typography_css( $block_selector . ' .wd-nav-my-acc > li > a', $attrs, 'itemTp' ) );

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
