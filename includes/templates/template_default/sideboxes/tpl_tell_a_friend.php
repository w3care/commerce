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
// $Id: tpl_tell_a_friend.php,v 1.1 2005/07/05 05:59:02 bitweaver Exp $
//
  $id = tellafriend;
  $content = "";
  $content = zen_draw_form('tell_a_friend', zen_href_link(FILENAME_TELL_A_FRIEND, '', 'NONSSL', false), 'get');
  $content = zen_draw_input_field('to_email_address', '', 'size="15"') . '&nbsp;' . zen_image_submit('button_tell_a_friend.gif', BOX_HEADING_TELL_A_FRIEND) . zen_draw_hidden_field('products_id', $_GET['products_id']) . zen_hide_session_id() . '<p>' . BOX_TELL_A_FRIEND_TEXT . '</p>';

?>