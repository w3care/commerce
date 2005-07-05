<?php
global $gBitSystem;

$gBitSystem->registerPackage( 'bitcommerce', dirname( __FILE__ ).'/' );
if( $gBitSystem->isPackageActive( 'bitcommerce' ) ) {
	$gBitSystem->registerAppMenu( 'bitcommerce', 'Shopping', BITCOMMERCE_PKG_URL.'index.php', 'bitpackage:bitcommerce/menu_bitcommerce.tpl' );
}

if( !defined( 'BITCOMMERCE_DB_PREFIX' ) ) {
	$lastQuote = strrpos( BIT_DB_PREFIX, '`' );
	if( $lastQuote != FALSE ) {
		$lastQuote++;
	}
	$prefix = substr( BIT_DB_PREFIX,  $lastQuote );
	define( 'BITCOMMERCE_DB_PREFIX', $prefix.'bit_' );
}

?>
