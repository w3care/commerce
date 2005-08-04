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
// $Id: tpl_logoff_default.php,v 1.1 2005/07/05 05:59:28 bitweaver Exp $
//
?>
<h1><?php echo HEADING_TITLE; ?></h1>
<p class="plainbox"><?php echo TEXT_MAIN; ?></p>
<?php echo '<a href="' . zen_href_link(FILENAME_DEFAULT) . '">' . zen_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?>
