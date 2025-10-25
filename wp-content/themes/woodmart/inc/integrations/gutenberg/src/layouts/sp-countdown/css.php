<?php
use XTS\Gutenberg\Block_CSS;

$block_css = new Block_CSS( $attrs );

$block_css->add_css_rules(
	$block_selector,
	array(
		array(
			'attr_name' => 'textAlign',
			'template'  => '--wd-align: var(--wd-{{value}});',
		),
	)
);

$block_css->add_css_rules(
	$block_selector . ' .wd-timer',
	array(
		array(
			'attr_name' => 'bgTimerColorCode',
			'template'  => '--wd-timer-bg: {{value}};',
		),
		array(
			'attr_name' => 'bgTimerColorVariable',
			'template'  => '--wd-timer-bg: var({{value}});',
		),
		array(
			'attr_name' => 'countdownGap',
			'template'  => 'gap: {{value}}px;',
		),
	)
);

$block_css->add_css_rules(
	$block_selector . ' .wd-timer-value',
	array(
		array(
			'attr_name' => 'numberColorCode',
			'template'  => 'color: {{value}};',
		),
		array(
			'attr_name' => 'numberColorVariable',
			'template'  => 'color: var({{value}});',
		),
	)
);

$block_css->add_css_rules(
	$block_selector . ' .wd-timer-text',
	array(
		array(
			'attr_name' => 'labelsColorCode',
			'template'  => 'color: {{value}};',
		),
		array(
			'attr_name' => 'labelsColorVariable',
			'template'  => 'color: var({{value}});',
		),
	)
);

$block_css->add_css_rules(
	$block_selector . ' .wd-sep',
	array(
		array(
			'attr_name' => 'separatorColorCode',
			'template'  => 'color: {{value}};',
		),
		array(
			'attr_name' => 'separatorColorVariable',
			'template'  => 'color: var({{value}});',
		),
	)
);

$block_css->add_css_rules(
	$block_selector . ' .wd-item',
	array(
		array(
			'attr_name' => 'countdownMinHeight',
			'template'  => 'min-height: {{value}}px;',
		),
		array(
			'attr_name' => 'countdownMinWidth',
			'template'  => 'min-width: {{value}}px;',
		),
	)
);

$block_css->add_css_rules(
	$block_selector . ' .wd-sep',
	array(
		array(
			'attr_name' => 'separatorFontSize',
			'template'  => 'font-size: {{value}}px;',
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
	$block_selector . ' .wd-item',
	array(
		array(
			'attr_name' => 'countdownMinHeightTablet',
			'template'  => 'min-height: {{value}}px;',
		),
		array(
			'attr_name' => 'countdownMinWidthTablet',
			'template'  => 'min-width: {{value}}px;',
		),
	),
	'tablet'
);

$block_css->add_css_rules(
	$block_selector . ' .wd-sep',
	array(
		array(
			'attr_name' => 'separatorFontSizeTablet',
			'template'  => 'font-size: {{value}}px;',
		),
	),
	'tablet'
);

$block_css->add_css_rules(
	$block_selector . ' .wd-timer',
	array(
		array(
			'attr_name' => 'countdownGapTablet',
			'template'  => 'gap: {{value}}px;',
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
	$block_selector . ' .wd-sep',
	array(
		array(
			'attr_name' => 'separatorFontSizeMobile',
			'template'  => 'font-size: {{value}}px;',
		),
	),
	'mobile'
);

$block_css->add_css_rules(
	$block_selector . ' .wd-timer',
	array(
		array(
			'attr_name' => 'countdownGapMobile',
			'template'  => 'gap: {{value}}px;',
		),
	),
	'mobile'
);

$block_css->add_css_rules(
	$block_selector . ' .wd-item',
	array(
		array(
			'attr_name' => 'countdownMinHeightMobile',
			'template'  => 'min-height: {{value}}px;',
		),
		array(
			'attr_name' => 'countdownMinWidthMobile',
			'template'  => 'min-width: {{value}}px;',
		),
	),
	'mobile'
);

$block_css->merge_with( wd_get_block_box_shadow_css( $block_selector . ' .wd-timer', $attrs, 'timerBoxShadow', '--wd-timer-shadow' ) );
$block_css->merge_with( wd_get_block_border_css( $block_selector . ' .wd-item', $attrs, 'itemsBorder' ) );
$block_css->merge_with( wd_get_block_typography_css( $block_selector . ' .wd-timer-value', $attrs, 'numberTp' ) );
$block_css->merge_with( wd_get_block_typography_css( $block_selector . ' .wd-timer-text', $attrs, 'labelsTp' ) );
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
