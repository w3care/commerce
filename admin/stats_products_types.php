<?php
// +--------------------------------------------------------------------+
// | Copyright (c) 2007 bitcommerce.org									|
// | http://www.bitcommerce.org											|
// +--------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license		|
// +--------------------------------------------------------------------+
/**
 * @version	$Header: /cvsroot/bitweaver/_bit_commerce/admin/stats_products_types.php,v 1.1 2010/02/15 05:53:55 spiderr Exp $
 *
 * Product class for handling all production manipulation
 *
 * @package	bitcommerce
 * @author	 spider <spider@steelsun.com>
 */


define('HEADING_TITLE', 'Order'.( (!empty( $_REQUEST['oID'] )) ? ' #'.$_REQUEST['oID'] : 's'));

require('includes/application_top.php');
require_once( BITCOMMERCE_PKG_PATH.'classes/CommerceStatistics.php' );

$stats = new CommerceStatistics();

$gBitSmarty->assign_by_ref( 'typesStats', $stats->getRevenueByType( $_REQUEST ) );
$gBitSmarty->assign_by_ref( 'optionsStats', $stats->getRevenueByOption( $_REQUEST ) );

print $gBitSmarty->fetch( 'bitpackage:bitcommerce/admin_stats_products_types.tpl' );

require(DIR_FS_ADMIN_INCLUDES . 'footer.php'); 

?>

<!-- footer_eof //-->
<br />
</body>
</html>
<?php require(DIR_FS_ADMIN_INCLUDES . 'application_bottom.php'); ?>
