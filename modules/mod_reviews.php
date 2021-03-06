<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
// $Id$
//
global $gBitDb, $gBitProduct;
require_once( BITCOMMERCE_PKG_PATH.'includes/bitcommerce_start_inc.php' );

$listHash['reviews'] = TRUE;
if( $gBitProduct->isValid() ) {
	$listHash['products_id'] = $gBitProduct->mProductsId;
}
$listHash['max_records'] = MAX_RANDOM_SELECT_REVIEWS;
if( $sideboxReview = $gBitProduct->getList( $listHash ) ) {
	$_template->tpl_vars['sideboxReview'] = new Smarty_variable( current( $sideboxReview ) );
} elseif ( $gBitProduct->isValid() ) {
	$_template->tpl_vars['reviewProductsId'] = new Smarty_variable( $gBitProduct->getField( 'products_id' ) );
	$_template->tpl_vars['writeReview'] = new Smarty_variable( TRUE );
}
if( empty( $moduleTitle ) ) {
	$_template->tpl_vars['moduleTitle'] = new Smarty_variable( '<img src="'.BITCOMMERCE_PKG_URL.'icons/star.png" alt="*" />'.tra( 'Reviews' ) );
}
?>
