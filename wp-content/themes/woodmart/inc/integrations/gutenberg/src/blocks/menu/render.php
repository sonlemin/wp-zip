<?php
if ( ! function_exists( 'wd_gutenberg_menu' ) ) {
	function wd_gutenberg_menu( $block_attributes ) {
		$block_attributes['el_class'] = wd_get_gutenberg_element_classes( $block_attributes );
		$block_attributes['el_id']    = wd_get_gutenberg_element_id( $block_attributes );
		$block_attributes['is_wpb']   = false;

		if ( ! empty( $block_attributes['color_scheme'] ) ) {
			$block_attributes['el_class'] .= ' color-scheme-' . $block_attributes['color_scheme'];
		}

		if ( ! empty( $block_attributes['align'] ) || ! empty( $block_attributes['alignTablet'] ) || ! empty( $block_attributes['alignMobile'] ) ) {
			$block_attributes['el_class'] .= ' wd-align';
		}

		$items_bg_activated = ! empty( $block_attributes['itemsBgColorCode'] ) ||
			! empty( $block_attributes['itemsBgColorVariable'] ) ||
			! empty( $block_attributes['itemsBgHoverColorCode'] ) ||
			! empty( $block_attributes['itemsBgHoverColorVariable'] ) ||
			! empty( $block_attributes['itemsBgActiveColorCode'] ) ||
			! empty( $block_attributes['itemsBgActiveColorVariable'] );

		$items_box_shadow_active = (
			( ! empty( $block_attributes['itemsBoxShadowColorCode'] ) || ! empty( $block_attributes['itemsBoxShadowColorVariable'] ) ) &&
			( ! empty( $block_attributes['itemsBoxShadowHorizontal'] ) || 0 === $block_attributes['itemsBoxShadowHorizontal'] ) &&
			( ! empty( $block_attributes['itemsBoxShadowVertical'] ) || 0 === $block_attributes['itemsBoxShadowVertical'] ) &&
			( ! empty( $block_attributes['itemsBoxShadowSpread'] ) || 0 === $block_attributes['itemsBoxShadowSpread'] ) &&
			( ! empty( $block_attributes['itemsBoxShadowBlur'] ) || 0 === $block_attributes['itemsBoxShadowBlur'] )
		) ||
		(
			( ! empty( $block_attributes['itemsBoxShadowHoverColorCode'] ) || ! empty( $block_attributes['itemsBoxShadowHoverColorVariable'] ) ) &&
			( ! empty( $block_attributes['itemsBoxShadowHoverHorizontal'] ) || 0 === $block_attributes['itemsBoxShadowHoverHorizontal'] ) &&
			( ! empty( $block_attributes['itemsBoxShadowHoverVertical'] ) || 0 === $block_attributes['itemsBoxShadowHoverVertical'] ) &&
			( ! empty( $block_attributes['itemsBoxShadowHoverSpread'] ) || 0 === $block_attributes['itemsBoxShadowHoverSpread'] ) &&
			( ! empty( $block_attributes['itemsBoxShadowHoverBlur'] ) || 0 === $block_attributes['itemsBoxShadowHoverBlur'] )
		) ||
		(
			( ! empty( $block_attributes['itemsBoxShadowActiveColorCode'] ) || ! empty( $block_attributes['itemsBoxShadowActiveColorVariable'] ) ) &&
			( ! empty( $block_attributes['itemsBoxShadowActiveHorizontal'] ) || 0 === $block_attributes['itemsBoxShadowActiveHorizontal'] ) &&
			( ! empty( $block_attributes['itemsBoxShadowActiveVertical'] ) || 0 === $block_attributes['itemsBoxShadowActiveVertical'] ) &&
			( ! empty( $block_attributes['itemsBoxShadowActiveSpread'] ) || 0 === $block_attributes['itemsBoxShadowActiveSpread'] ) &&
			( ! empty( $block_attributes['itemsBoxShadowActiveBlur'] ) || 0 === $block_attributes['itemsBoxShadowActiveBlur'] )
		);

		$items_border_active = (
			! empty( $block_attributes['itemsBorderType'] ) &&
			'none' !== $block_attributes['itemsBorderType'] &&
			! empty( $block_attributes['itemsBorderWidthTop'] ) &&
			! empty( $block_attributes['itemsBorderWidthRight'] ) &&
			! empty( $block_attributes['itemsBorderWidthBottom'] ) &&
			! empty( $block_attributes['itemsBorderWidthLeft'] )
		) || (
			! empty( $block_attributes['itemsBorderRadiusTop'] ) &&
			! empty( $block_attributes['itemsBorderRadiusRight'] ) &&
			! empty( $block_attributes['itemsBorderRadiusBottom'] ) &&
			! empty( $block_attributes['itemsBorderRadiusLeft'] )
		) || (
			! empty( $block_attributes['itemsBorderHoverType'] ) &&
			'none' !== $block_attributes['itemsBorderHoverType'] &&
			! empty( $block_attributes['itemsBorderHoverWidthTop'] ) &&
			! empty( $block_attributes['itemsBorderHoverWidthRight'] ) &&
			! empty( $block_attributes['itemsBorderHoverWidthBottom'] ) &&
			! empty( $block_attributes['itemsBorderHoverWidthLeft'] )
		) || (
			! empty( $block_attributes['itemsBorderActiveType'] ) &&
			'none' !== $block_attributes['itemsBorderActiveType'] &&
			! empty( $block_attributes['itemsBorderActiveWidthTop'] ) &&
			! empty( $block_attributes['itemsBorderActiveWidthRight'] ) &&
			! empty( $block_attributes['itemsBorderActiveWidthBottom'] ) &&
			! empty( $block_attributes['itemsBorderActiveWidthLeft'] )
		);

		if ( $items_bg_activated || $items_box_shadow_active || $items_border_active ) {
			$block_attributes['menu_classes'] = ' wd-add-pd';
		}

		if ( ! empty( $block_attributes['disable_active_style'] ) ) {
			$block_attributes['menu_classes'] .= ' wd-dis-act';
		}

		return woodmart_shortcode_mega_menu( $block_attributes, '' );
	}
}
