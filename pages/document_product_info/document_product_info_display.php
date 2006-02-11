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
// $Id: document_product_info_display.php,v 1.4 2006/02/11 05:06:37 spiderr Exp $
//
// Variables available on this page
//
// $products_name
// $products_model
// $products_price
// $specials_price
// $products_image @@TODO Consider using a array generated by a class for multiple images
// $products_url
// $products_date_available
// $products_date_added
// $products_description
// $products_manufacturer
// $products_weight
// $products_quantity
// $options_name - Array
// $options_menu - Array
//   $module_show_categories
?>
<?php echo zen_draw_form('cart_quantity', zen_href_link(zen_get_info_page($_GET['products_id']), zen_get_all_get_params(array('action')) . 'action=add_product'), 'post', 'enctype="multipart/form-data"'); ?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
<?php if (PRODUCT_INFO_PREVIOUS_NEXT == '1' or PRODUCT_INFO_PREVIOUS_NEXT == '3') { ?>
  <tr>
    <td colspan="2" align="center">
      <?php require($template->get_template_dir('/tpl_products_next_previous.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_products_next_previous.php'); ?>
    </td>
  </tr>
<?php } ?>
  <tr>
    <td colspan="2" class="pageHeading" valign="top"><h1><?php echo $products_name; ?></h1></td>
  </tr>
  <tr>
    <td align="center" valign="top" class="smallText" rowspan="3" width="<?php echo SMALL_IMAGE_WIDTH; ?>">
<?php
  if (zen_not_null($products_image)) {
    require(DIR_FS_PAGES . $current_page_base . '/main_template_vars_images.php');
  } else {
    echo '&nbsp;';
  }
?>
    </td>
    <td align="center" class="pageHeading">
<?php
// base price
  if ($show_onetime_charges_description == 'true') {
    $one_time = '<span class="smallText">' . TEXT_ONETIME_CHARGE_SYMBOL . TEXT_ONETIME_CHARGE_DESCRIPTION . '</span><br />';
  } else {
    $one_time = '';
  }

  echo $one_time . ((zen_has_product_attributes_values((int)$_GET['products_id']) and SHOW_DOCUMENT_PRODUCT_INFO_STARTING_AT == '1') ? TEXT_BASE_PRICE : '') . CommerceProduct::getDisplayPrice((int)$_GET['products_id']);
?>
    </td>
  </tr>

  <tr>
    <td class="main" align="center" valign="top">
      <?php echo ((SHOW_DOCUMENT_PRODUCT_INFO_MODEL == '1' and $products_model !='') ? TEXT_PRODUCT_MODEL . $products_model : '&nbsp;'); ?>
    </td>
  </tr>
  <tr>
    <td class="main" align="center"><?php echo ((SHOW_DOCUMENT_PRODUCT_INFO_WEIGHT == '1' and $products_weight !=0) ? TEXT_PRODUCT_WEIGHT .  $products_weight . TEXT_PRODUCT_WEIGHT_UNIT : '&nbsp;'); ?></td>
  </tr>
  <tr>
    <td colspan="2" class="main" align="center">
<?php
  if ($pr_attr->fields['total'] > 0) {
?>
      <table border="0" width="90%" cellspacing="0" cellpadding="2">
<?php if ($zv_display_select_option > 0) { ?>
        <tr>
          <td colspan="2" class="main" align="left"><?php echo TEXT_PRODUCT_OPTIONS; ?></td>
        </tr>
<?php } // show please select unless all are readonly ?>
<?php
    for($i=0;$i<sizeof($options_name);$i++) {
?>
<?php
  if ($options_comment[$i] != '' and $options_comment_position[$i] == '0') {
?>

        <tr>
          <td><?php echo zen_draw_separator(DIR_WS_TEMPLATE_IMAGES . OTHER_IMAGE_TRANPARENT, '1', '5'); ?></td>
        </tr>
        <tr>
          <td colspan="2" class="ProductInfoComments" align="left" valign="bottom"><?php echo $options_comment[$i]; ?></td>
        </tr>
<?php
  }
?>
        <tr>
          <td class="main" align="left" valign="top"><?php echo $options_name[$i] . ':'; ?></td>
          <td class="main" align="left" valign="top" width="75%"><?php echo $options_menu[$i]; ?></td>
        </tr>
<?php if ($options_comment[$i] != '' and $options_comment_position[$i] == '1') { ?>
        <tr>
          <td colspan="2" class="ProductInfoComments" align="left" valign="top"><?php echo $options_comment[$i]; ?></td>
        </tr>
<?php } ?>

<?php
if ($options_attributes_image[$i] != '') {
?>
        <tr><td colspan="2"><table class="products-attributes-images"><tr>
          <?php echo $options_attributes_image[$i]; ?>
        </tr></table></td></tr>
<?php
}
?>
<?php
    }
?>
<?php
  if ($show_onetime_charges_description == 'true') {
?>
        <tr>
          <td colspan="2" class="main" align="left"><?php echo TEXT_ONETIME_CHARGE_SYMBOL . TEXT_ONETIME_CHARGE_DESCRIPTION; ?></td>
        </tr>
<?php } ?>

<?php
  if ($show_attributes_qty_prices_description == 'true') {
?>
        <tr>
          <td colspan="2" class="main" align="left"><?php echo zen_image(DIR_WS_TEMPLATE_ICONS . 'icon_status_green.gif', TEXT_ATTRIBUTES_QTY_PRICE_HELP_LINK, 10, 10) . '&nbsp;' . '<a href="javascript:popupWindowPrice(\'' . zen_href_link(FILENAME_POPUP_ATTRIBUTES_QTY_PRICES, 'products_id=' . $_GET['products_id'] . '&products_tax_class_id=' . $products_tax_class_id) . '\')">' . TEXT_ATTRIBUTES_QTY_PRICE_HELP_LINK . '</a>'; ?></td>
        </tr>
<?php } ?>

      </table>
<?php
  }
?>
    </td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td class="main" align="center"><?php echo ((SHOW_DOCUMENT_PRODUCT_INFO_QUANTITY == '1') ? $products_quantity . TEXT_PRODUCT_QUANTITY : '&nbsp;'); ?></td>
  </tr>
  <tr>
    <td class="main" align="center"><?php echo ((SHOW_DOCUMENT_PRODUCT_INFO_MANUFACTURER == '1' and !empty($manufacturers_name)) ? TEXT_PRODUCT_MANUFACTURER . $manufacturers_name : '&nbsp;'); ?></td>
    <td align="center">
<?php
if (CUSTOMERS_APPROVAL == '3' and TEXT_LOGIN_FOR_PRICE_BUTTON_REPLACE_SHOWROOM == '') {
  echo '&nbsp;';
} else {
?>
      <table border="0" width="150px" cellspacing="2" cellpadding="2">
        <tr>
          <td align="center" class="cartBox">
            <?php echo ((SHOW_DOCUMENT_PRODUCT_INFO_IN_CART_QTY == '1' and $_SESSION['cart']->in_cart($_GET['products_id'])) ? PRODUCTS_ORDER_QTY_TEXT_IN_CART . $_SESSION['cart']->get_quantity($_GET['products_id']) . '<br /><br />' : '&nbsp;'); ?>
            <?php
            if ($products_qty_box_status == '0' or $products_quantity_order_max== '1') {
              // hide the quantity box and default to 1
              $the_button = '<input type="hidden" name="cart_quantity" value="1" />' . zen_draw_hidden_field('products_id', (int)$_GET['products_id']) . zen_image_submit(BUTTON_IMAGE_IN_CART, BUTTON_IN_CART_ALT);
            } else {
              // show the quantity box
              $the_button = PRODUCTS_ORDER_QTY_TEXT . '<input type="text" name="cart_quantity" value="' . (zen_get_buy_now_qty($_GET['products_id'])) . '" maxlength="6" size="4" /><br />' . zen_get_products_quantity_min_units_display((int)$_GET['products_id']) . '<br />' . zen_draw_hidden_field('products_id', (int)$_GET['products_id']) . zen_image_submit(BUTTON_IMAGE_IN_CART, BUTTON_IN_CART_ALT);
            }
            echo zen_get_buy_now_button($_GET['products_id'], $the_button);
            ?>
          </td>
        </tr>
      </table>
<?php } // CUSTOMERS_APPROVAL == '3' ?>
    </td>
  </tr>

<?php
  if ($products_discount_type != 0) {
    echo '<tr><td colspan="2">';
      require(DIR_FS_MODULES . zen_get_module_directory(FILENAME_PRODUCTS_DISCOUNT_PRICES));
    echo '</td></tr>';
  }
?>

<?php if ($products_description != '') { ?>
  <tr>
    <td colspan="2" class="plainbox-description"><?php echo stripslashes($products_description); ?></td>
  </tr>
<?php } ?>

<?php require(DIR_FS_PAGES . $current_page_base . '/main_template_vars_images_additional.php'); ?>
<?php if (PRODUCT_INFO_PREVIOUS_NEXT == '2' or PRODUCT_INFO_PREVIOUS_NEXT == '3') { ?>
  <tr>
    <td colspan="2" align="center">
      <?php require($template->get_template_dir('/tpl_products_next_previous.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_products_next_previous.php'); ?>
    </td>
  </tr>
<?php } ?>
  <tr>
    <td align="center" colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td class="main" align="left" valign="bottom">
<?php
  if ($reviews->fields['count'] > 0 or SHOW_DOCUMENT_PRODUCT_INFO_REVIEWS == '1') {
    echo '<table align="left">';
    echo '  <tr>';
    echo '    <td class="main" align="center" valign="bottom">';
    echo (SHOW_DOCUMENT_PRODUCT_INFO_REVIEWS_COUNT == '1' ? TEXT_CURRENT_REVIEWS . ' ' . $reviews->fields['count'] : '&nbsp;') . '<br />';
    echo (SHOW_DOCUMENT_PRODUCT_INFO_REVIEWS == '1' ? '<a href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS, zen_get_all_get_params()) . '">' . zen_image_button(BUTTON_IMAGE_REVIEWS, BUTTON_REVIEWS_ALT) . '</a>' : '&nbsp;');
    echo '    </td>';
    echo '  </tr>';
    echo '</table>';
  }
?>
    </td>
    <td class="main" align="right" valign="bottom">
<?php
  if (SHOW_DOCUMENT_PRODUCT_INFO_TELL_A_FRIEND == '1') {
    echo '<table align="right">';
    echo '  <tr>';
    echo '    <td class="main" align="center" valign="bottom">';
    echo (SHOW_DOCUMENT_PRODUCT_INFO_TELL_A_FRIEND == '1' ? '<a href="' . zen_href_link(FILENAME_TELL_A_FRIEND, 'products_id=' . $_GET['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_TELLAFRIEND, BUTTON_TELLAFRIEND_ALT) . '</a>' : '');
    echo '    </td>';
    echo '  </tr>';
    echo '</table>';
  }
?>
    </td>
  </tr>
<?php
  if ($products_date_available > date('Y-m-d H:i:s')) {
    if (SHOW_DOCUMENT_PRODUCT_INFO_DATE_AVAILABLE == '1') {
?>
  <tr>
    <td colspan="2" align="center" class="smallText"><?php echo sprintf(TEXT_DATE_AVAILABLE, zen_date_long($products_date_available)); ?></td>
  </tr>
<?php
    }
  } else {
    if (SHOW_DOCUMENT_PRODUCT_INFO_DATE_ADDED == '1') {
?>
  <tr>
    <td colspan="2" align="center" class="smallText"><?php echo sprintf(TEXT_DATE_ADDED, zen_date_long($products_date_added)); ?></td>
  </tr>
<?php
    } // SHOW_DOCUMENT_PRODUCT_INFO_DATE_ADDED
  }
?>
<?php
  if (zen_not_null($products_url)) {
    if (SHOW_DOCUMENT_PRODUCT_INFO_URL == '1') {
?>
  <tr>
    <td class="main" align="center" colspan="2">
      <?php echo sprintf(TEXT_MORE_INFORMATION, zen_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($products_url), 'NONSSL', true, false)); ?>
    </td>
  </tr>
 <?php
    } // SHOW_DOCUMENT_PRODUCT_INFO_URL
  }
?>
<tr>
  <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
</tr>
</table></form>
