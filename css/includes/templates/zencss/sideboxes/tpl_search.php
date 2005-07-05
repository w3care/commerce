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
// $Id: tpl_search.php,v 1.1 2005/07/05 05:59:27 bitweaver Exp $
//
  $id = search;
  $content = "";
  $content .= zen_draw_form('quick_find', zen_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get');
  $content .= zen_draw_hidden_field('search_in_description','1'); 
  $content .= zen_draw_input_field('keyword', '', 'size="10" maxlength="30" style="width: ' . (BOX_WIDTH-30) . 'px"') . '<br /><input type="submit" value="' . BOX_HEADING_SEARCH . '" style="width: ' . (BOX_WIDTH-30) . 'px" /><br /><br /><a href="' . zen_href_link(FILENAME_ADVANCED_SEARCH) . '"><strong>' . BOX_SEARCH_ADVANCED_SEARCH . '</strong></a>';
  $content .= "</form>";
?>