<?php
$assets = array(
	'styles'    => array( 'el-menu' ),
	'scripts'   => array(),
	'libraries' => array(),
);

if ( ( ! empty( $this->attrs['design'] ) && 'vertical' !== $this->attrs['design'] ) || ( ! empty( $this->attrs['style'] )  && 'bg' === $this->attrs['style'] ) ) {
	$assets['styles'][] = 'bg-navigation';
}

return $assets;
