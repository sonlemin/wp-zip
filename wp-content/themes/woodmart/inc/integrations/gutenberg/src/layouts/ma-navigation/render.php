<?php use XTS\Modules\Layouts\Main;

if ( ! function_exists( 'wd_gutenberg_my_account_navigation' ) ) {
	function wd_gutenberg_my_account_navigation( $block_attributes ) {
		$wrapper_classes = wd_get_gutenberg_element_classes( $block_attributes );
		$el_id           = wd_get_gutenberg_element_id( $block_attributes );
		$show_icons      = false;
		$attributes      = '';
		$layout_type     = $block_attributes['layout_type'];
		$orientation     = $block_attributes['orientation'];
		$icon_alignment  = ! empty( $block_attributes['icon_alignment'] ) ? $block_attributes['icon_alignment'] : '';

		if ( ! empty( $block_attributes['show_icons'] ) ) {
			$show_icons = $block_attributes['show_icons'];
		}

		if ( ! empty( $block_attributes['color_scheme'] ) ) {
			$wrapper_classes .= ' color-scheme-' . $block_attributes['color_scheme'];
		}

		if ( ! empty( $block_attributes['orientation'] ) && 'horizontal' === $block_attributes['orientation'] && ( ! empty( $block_attributes['align'] ) || ! empty( $block_attributes['alignTablet'] ) || ! empty( $block_attributes['alignMobile'] ) ) ) {
			$wrapper_classes .= ' wd-align';
		}

		$menu_classes = ' wd-nav-' . $block_attributes['orientation'];

		if ( 'horizontal' === $orientation && 'grid' === $layout_type ) {
			$menu_classes = ' wd-grid-g wd-style-default';
			$attributes  .= ' style="' . woodmart_get_grid_attrs(
				array(
					'columns'        => $block_attributes['navColumns'],
					'columns_tablet' => ! empty( $block_attributes['navColumnsTablet'] ) ? $block_attributes['navColumnsTablet'] : '',
					'columns_mobile' => ! empty( $block_attributes['navColumnsMobile'] ) ? $block_attributes['navColumnsMobile'] : '',
					'spacing'        => $block_attributes['navSpacing'],
					'spacing_tablet' => ! empty( $block_attributes['navSpacingTablet'] ) ? $block_attributes['navSpacingTablet'] : '',
					'spacing_mobile' => ! empty( $block_attributes['navSpacingMobile'] ) ? $block_attributes['navSpacingMobile'] : '',
				)
			) . '"';
		}

		if ( 'horizontal' === $orientation ) {
			$menu_classes .= ' wd-style-' . $block_attributes['style'];
		}

		if ( 'vertical' === $orientation ) {
			woodmart_enqueue_inline_style( 'mod-nav-vertical' );
			woodmart_enqueue_inline_style( 'mod-nav-vertical-design-' . $block_attributes['nav_design'] );

			$menu_classes .= ' wd-design-' . $block_attributes['nav_design'];
		}

		$gap_condition = ( 'vertical' === $orientation && 'simple' === $block_attributes['nav_design'] ) || ( 'horizontal' === $orientation && 'inline' === $layout_type );

		if ( $gap_condition && ! empty( $block_attributes['items_gap'] ) && 'custom' !== $block_attributes['items_gap'] ) {
			$menu_classes .= ' wd-gap-' . $block_attributes['items_gap'];
		}

		if ( $icon_alignment && $show_icons ) {
			$menu_classes .= ' wd-icon-' . $icon_alignment;
		}

		$items_bg_activated = ! empty( $block_attributes['navBgColorCode'] ) ||
			! empty( $block_attributes['navBgColorVariable'] ) ||
			! empty( $block_attributes['navBgColorHoverCode'] ) ||
			! empty( $block_attributes['navBgColorHoverVariable'] ) ||
			! empty( $block_attributes['navBgColorActiveCode'] ) ||
			! empty( $block_attributes['navBgColorActiveVariable'] );

		$items_box_shadow_active = (
				( ! empty( $block_attributes['navBoxShadowColorCode'] ) || ! empty( $block_attributes['navBoxShadowColorVariable'] ) ) &&
				( ! empty( $block_attributes['navBoxShadowHorizontal'] ) || 0 === $block_attributes['navBoxShadowHorizontal'] ) &&
				( ! empty( $block_attributes['navBoxShadowVertical'] ) || 0 === $block_attributes['navBoxShadowVertical'] ) &&
				( ! empty( $block_attributes['navBoxShadowSpread'] ) || 0 === $block_attributes['navBoxShadowSpread'] ) &&
				( ! empty( $block_attributes['navBoxShadowBlur'] ) || 0 === $block_attributes['navBoxShadowBlur'] )
			) ||
			(
				( ! empty( $block_attributes['navBoxShadowHoverColorCode'] ) || ! empty( $block_attributes['navBoxShadowHoverColorVariable'] ) ) &&
				( ! empty( $block_attributes['navBoxShadowHoverHorizontal'] ) || 0 === $block_attributes['navBoxShadowHoverHorizontal'] ) &&
				( ! empty( $block_attributes['navBoxShadowHoverVertical'] ) || 0 === $block_attributes['navBoxShadowHoverVertical'] ) &&
				( ! empty( $block_attributes['navBoxShadowHoverSpread'] ) || 0 === $block_attributes['navBoxShadowHoverSpread'] ) &&
				( ! empty( $block_attributes['navBoxShadowHoverBlur'] ) || 0 === $block_attributes['navBoxShadowHoverBlur'] )
			) ||
			(
				( ! empty( $block_attributes['navBoxShadowActiveColorCode'] ) || ! empty( $block_attributes['navBoxShadowActiveColorVariable'] ) ) &&
				( ! empty( $block_attributes['navBoxShadowActiveHorizontal'] ) || 0 === $block_attributes['navBoxShadowActiveHorizontal'] ) &&
				( ! empty( $block_attributes['navBoxShadowActiveVertical'] ) || 0 === $block_attributes['navBoxShadowActiveVertical'] ) &&
				( ! empty( $block_attributes['navBoxShadowActiveSpread'] ) || 0 === $block_attributes['navBoxShadowActiveSpread'] ) &&
				( ! empty( $block_attributes['navBoxShadowActiveBlur'] ) || 0 === $block_attributes['navBoxShadowActiveBlur'] )
			);

		$items_border_active = (
				! empty( $block_attributes['navBorderType'] ) &&
				'none' !== $block_attributes['navBorderType'] &&
				! empty( $block_attributes['navBorderWidthTop'] ) &&
				! empty( $block_attributes['navBorderWidthRight'] ) &&
				! empty( $block_attributes['navBorderWidthBottom'] ) &&
				! empty( $block_attributes['navBorderWidthLeft'] )
			) || (
				! empty( $block_attributes['navBorderRadiusTop'] ) &&
				! empty( $block_attributes['navBorderRadiusRight'] ) &&
				! empty( $block_attributes['navBorderRadiusBottom'] ) &&
				! empty( $block_attributes['navBorderRadiusLeft'] )
			) || (
				! empty( $block_attributes['navBorderHoverType'] ) &&
				'none' !== $block_attributes['navBorderHoverType'] &&
				! empty( $block_attributes['navBorderHoverWidthTop'] ) &&
				! empty( $block_attributes['navBorderHoverWidthRight'] ) &&
				! empty( $block_attributes['navBorderHoverWidthBottom'] ) &&
				! empty( $block_attributes['navBorderHoverWidthLeft'] )
			) || (
				! empty( $block_attributes['navBorderActiveType'] ) &&
				'none' !== $block_attributes['navBorderActiveType'] &&
				! empty( $block_attributes['navBorderActiveWidthTop'] ) &&
				! empty( $block_attributes['navBorderActiveWidthRight'] ) &&
				! empty( $block_attributes['navBorderActiveWidthBottom'] ) &&
				! empty( $block_attributes['navBorderActiveWidthLeft'] )
			);

		if ( $items_bg_activated || $items_box_shadow_active || $items_border_active ) {
			$menu_classes .= ' wd-add-pd';
		}

		Main::setup_preview();

		ob_start();
		?>
		<div id="<?php echo esc_attr( $el_id ); ?>" class="wd-el-my-acc-nav<?php echo esc_attr( $wrapper_classes ); ?>">
			<?php
			/**
			 * Display WooCommerce account navigation.
			 */
			woodmart_account_navigation( $menu_classes, $show_icons, $attributes );
			?>
		</div>
		<?php

		Main::restore_preview();
		return ob_get_clean();
	}
}
