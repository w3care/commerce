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
//  $Id: localization_dhtml.php,v 1.1 2005/07/05 06:00:04 bitweaver Exp $
//
  $za_contents = array();
  $za_heading = array();
  $za_heading = array('text' => BOX_HEADING_LOCALIZATION, 'link' => zen_href_link(FILENAME_ALT_NAV, '', 'NONSSL'));
  $za_contents[] = array('text' => BOX_LOCALIZATION_CURRENCIES, 'link' => zen_href_link(FILENAME_CURRENCIES, '', 'NONSSL'));
  $za_contents[] = array('text' => BOX_LOCALIZATION_LANGUAGES, 'link' => zen_href_link(FILENAME_LANGUAGES, '', 'NONSSL'));
  $za_contents[] = array('text' => BOX_LOCALIZATION_ORDERS_STATUS, 'link' => zen_href_link(FILENAME_ORDERS_STATUS, '', 'NONSSL'));
if ($za_dir = @dir(DIR_WS_BOXES . 'extra_boxes')) {
  while ($zv_file = $za_dir->read()) {
    if (preg_match('/localization_dhtml.php$/', $zv_file)) {
      require(DIR_WS_BOXES . 'extra_boxes/' . $zv_file);
    }
  }
}
?>
<!-- localization //-->
<?php
echo zen_draw_admin_box($za_heading, $za_contents);
?>
<!-- localization_eof //-->
