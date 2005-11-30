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
//// $Id: gv_faq.php,v 1.2 2005/11/30 04:17:49 spiderr Exp $
//

	if( $gBitUser->isRegistered() ) {
		$gv_query = "select `amount`
					from " . TABLE_COUPON_GV_CUSTOMER . "
					where `customer_id` = ?";
		if( $gvBalance = $gBitDb->getOne($gv_query, array( $gBitUser->mUserId ) ) ) {
			$gvBalance = $currencies->format( $gvBalance );
		}
		$gBitSmarty->assign( 'gvBalance', $gvBalance );
	}
	if( !empty( $_SESSION['gv_id'] ) ) {
		$gv_query = "select `coupon_amount`
					from " . TABLE_COUPONS . "
					where `coupon_id` = ?";
		if( $couponAmount = $gBitDb->getOne($gv_query, array( $_SESSION['gv_id'] ) ) ) {
			$couponAmount = $currencies->format( $couponAmount );
		}
		$gBitSmarty->assign( 'couponAmount', $couponAmount );
	}
  	$breadcrumb->add(NAVBAR_TITLE);

	print $gBitSmarty->fetch( 'bitpackage:bitcommerce/gv_faq.tpl' );
?>