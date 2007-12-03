{literal}
<script language="javascript" type="text/javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
  }
  function popupWindowPrice(url) {
    window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=600,height=400,screenX=150,screenY=150,top=150,left=150')
	}
--></script>
{/literal}

{form name='cart_quantity' target="`$smarty.const.BITCOMMERCE_PKG_URL`index.php?products_id=`smarty.get.products_id`&amp;&amp;action=add_product" method='post' enctype="multipart/form-data"'}


<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td rowspan="8" align="center" valign="top" class="smallText" rowspan="3" width="<?php echo SMALL_IMAGE_WIDTH; ?>">
<script language="javascript" type="text/javascript"><!--
document.write( <a href="javascript:popupWindow('zen_href_link(FILENAME_POPUP_IMAGE, 'products_id=' . $_GET['products_id']) . '\\\')">' . zen_image( CommerceProduct::getImageUrl( $_GET['products_id'], 'medium' ), addslashes($products_name)) . '<br />' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>');
//--></script>
<noscript>
<?php
  echo '<a href="' . zen_href_link(FILENAME_POPUP_IMAGE, 'products_id=' . $_GET['products_id']) . '" target="_blank">' . zen_image(CommerceProduct::getImageUrl( $_GET['products_id'], 'medium' ), $products_name) . '<br />' . TEXT_CLICK_TO_ENLARGE . '</a>';
?>
</noscript>
<?php
  if (zen_not_null($products_image)) {
{/if}
    </td>
    <td>
{*

<div class="header">
	<h1><?=$products_name;?></h1>
</div>


<div class="cartBox">
	<div class="row">
<?php
// base price
  if( !empty( $productSettings['show_onetime_charges_description'] ) && $productSettings['show_onetime_charges_description'] == 'true') {
    $one_time = '<span class="smallText">' . TEXT_ONETIME_CHARGE_SYMBOL . TEXT_ONETIME_CHARGE_DESCRIPTION . '</span><br />';
  } else {
    $one_time = '';
  }
  echo '<h2>' . $one_time . ((zen_has_product_attributes_values((int)$_GET['products_id']) and SHOW_PRODUCT_INFO_STARTING_AT == '1') ? TEXT_BASE_PRICE : '') . CommerceProduct::getDisplayPrice((int)$_GET['products_id']) . '</h2>';
?>
	</div>
<?php
    if( SHOW_PRODUCT_INFO_MODEL == '1' && $gBitProduct->getField( 'products_model' ) ) {
?>
	<div class="row">
      <?php echo $gBitProduct->getField( 'products_model' ); ?>
	</div>
<?php
	}
	if( SHOW_PRODUCT_INFO_WEIGHT == '1' && $gBitProduct->getField( 'products_weight' ) ) {
?>
	<div class="row">
	    <?php echo TEXT_PRODUCT_WEIGHT . $gBitProduct->getField( 'products_weight' ) . TEXT_PRODUCT_WEIGHT_UNIT; ?>
	</div>
<?php
	}


	$gBitSmarty->display( 'bitpackage:bitcommerce/product_options_inc.tpl' );


	if( SHOW_PRODUCT_INFO_QUANTITY == '1' && !$gBitProduct->getField( 'products_virtual' ) ) {
?>
	<div class="row">
    	<?php echo $products_quantity . TEXT_PRODUCT_QUANTITY; ?>
	</div>
<?php
	}
	if( SHOW_PRODUCT_INFO_MANUFACTURER == '1' and !empty($manufacturers_name) ) {
?>
	<div class="row">
    	<?php echo $manufacturers_name; ?>
	</div>
<?php
	}
?>
<?php
if (CUSTOMERS_APPROVAL == '3' and TEXT_LOGIN_FOR_PRICE_BUTTON_REPLACE_SHOWROOM == '') {
  echo '&nbsp;';
} else {
?>
	<div class="row">
      <table border="0" style="width:150px" cellspacing="2" cellpadding="2">
        <tr>
          <td>
            <?php echo ((SHOW_PRODUCT_INFO_IN_CART_QTY == '1' and $_SESSION['cart']->in_cart($_GET['products_id'])) ? PRODUCTS_ORDER_QTY_TEXT_IN_CART . $_SESSION['cart']->get_quantity($_GET['products_id']) . '<br /><br />' : '&nbsp;'); ?>
            <?php
            if ($products_qty_box_status == '0' or $products_quantity_order_max== '1') {
              // hide the quantity box and default to 1
              $the_button = '<input type="hidden" name="cart_quantity" value="1" />' . zen_draw_hidden_field('products_id', (int)$_GET['products_id']);
            } else {
              // show the quantity box
              $the_button = PRODUCTS_ORDER_QTY_TEXT . '<input type="text" name="cart_quantity" value="' . (zen_get_buy_now_qty($_GET['products_id'])) . '" maxlength="6" size="4" /><br />' . zen_get_products_quantity_min_units_display((int)$_GET['products_id']) . '<br />' . zen_draw_hidden_field('products_id', (int)$_GET['products_id']);
            }
			$title = !empty( $_REQUEST['sub'] ) ? $_REQUEST['sub'] : BUTTON_IN_CART_ALT;
			$the_button .= zen_image_submit( BUTTON_IMAGE_IN_CART, $title );
            echo zen_get_buy_now_button($_GET['products_id'], $the_button);
            ?>
          </td>
        </tr>
      </table>
	</div>
<?php } // CUSTOMERS_APPROVAL == '3' ?>


<?php
  if ($products_discount_type != 0) {
    echo '<div class="row">';
      require(DIR_FS_MODULES . zen_get_module_directory(FILENAME_PRODUCTS_DISCOUNT_PRICES));
    echo '</div>';
  }
?>

</div>



<?php if ($products_description != '') { ?>
    <?php echo stripslashes($products_description); ?>
<?php } ?>

    </td>
  </tr>
<?php require(DIR_FS_PAGES . $current_page_base . '/main_template_vars_images_additional.php'); ?>
<?php if (PRODUCT_INFO_PREVIOUS_NEXT == '2' or PRODUCT_INFO_PREVIOUS_NEXT == '3') { ?>
  <tr>
    <td colspan="2" align="center">
      <?php require($template->get_template_dir('/tpl_products_next_previous.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_products_next_previous.php'); ?>
    </td>
  </tr>
<?php } ?>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td class="main" align="left" valign="bottom">
<table>
  <tr>
    <td class="main" align="left" valign="bottom">
<?php
  if( SHOW_PRODUCT_INFO_REVIEWS == '1' ) {
    echo '<table align="left">';
    echo '  <tr>';
    echo '    <td class="main" align="center" valign="bottom">';
    echo (SHOW_PRODUCT_INFO_REVIEWS_COUNT == '1' ? TEXT_CURRENT_REVIEWS . ' ' . $gBitProduct->hasReviews() : '&nbsp;') . '<br />';
    echo (SHOW_PRODUCT_INFO_REVIEWS == '1' ? '<a href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS, zen_get_all_get_params()) . '">' . zen_image_button(BUTTON_IMAGE_REVIEWS, BUTTON_REVIEWS_ALT) . '</a>' : '&nbsp;');
    echo '    </td>';
    echo '  </tr>';
    echo '</table>';
  }
?>
    </td>
    <td class="main" align="right" valign="bottom">
<?php
  if (SHOW_PRODUCT_INFO_TELL_A_FRIEND == '1') {
    echo '<table align="right">';
    echo '  <tr>';
    echo '    <td class="main" align="center" valign="bottom">';
    echo (SHOW_PRODUCT_INFO_TELL_A_FRIEND == '1' ? '<a href="' . zen_href_link(FILENAME_TELL_A_FRIEND, 'products_id=' . $_GET['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_TELLAFRIEND, BUTTON_TELLAFRIEND_ALT) . '</a>' : '');
    echo '    </td>';
    echo '  </tr>';
    echo '</table>';
  }
?>
    </td>
  </tr>
</table>
    </td>
  </tr>
<?php
  if ($products_date_available > date('Y-m-d H:i:s')) {
    if (SHOW_PRODUCT_INFO_DATE_AVAILABLE == '1') {
?>
  <tr>
    <td colspan="2" align="center" class="smallText"><?php echo sprintf(TEXT_DATE_AVAILABLE, zen_date_long($products_date_available)); ?></td>
  </tr>
<?php
    }
  } else {
    if (SHOW_PRODUCT_INFO_DATE_ADDED == '1') {
?>
  <tr>
    <td colspan="2" align="center" class="smallText"><?php echo sprintf(TEXT_DATE_ADDED, zen_date_long($products_date_added)); ?></td>
  </tr>
<?php
    } // SHOW_PRODUCT_INFO_DATE_ADDED
  }
?>
<?php
  if (zen_not_null($products_url)) {
    if (SHOW_PRODUCT_INFO_URL == '1') {
?>
  <tr>
    <td class="main" align="center" colspan="2">
      <?php echo sprintf(TEXT_MORE_INFORMATION, zen_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($products_url), 'NONSSL', true, false)); ?>
    </td>
  </tr>
 <?php
    } // SHOW_PRODUCT_INFO_URL
  }
?>
</table>
*}

{/form}

