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
// $Id: tpl_main_page.php,v 1.1 2005/07/05 05:59:02 bitweaver Exp $
//
?>
<body onload="resize();">
<p class="main"><b><?php echo HEADING_TITLE; ?></b><br><?php echo zen_draw_separator(); ?></p>
<p class="main"><b><i><?php echo SUB_HEADING_TITLE_1; ?></i></b><br><?php echo SUB_HEADING_TEXT_1; ?></p>
<p class="main"><b><i><?php echo SUB_HEADING_TITLE_2; ?></i></b><br><?php echo SUB_HEADING_TEXT_2; ?></p>
<p class="main"><b><i><?php echo SUB_HEADING_TITLE_3; ?></i></b><br><?php echo SUB_HEADING_TEXT_3; ?></p>
<p align="right" class="main"><a href="javascript:window.close();"><?php echo TEXT_CLOSE_WINDOW; ?></a></p>
</body>