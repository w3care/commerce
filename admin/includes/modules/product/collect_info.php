<?php
//
// +------------------------------------------------------------------------+
// |zen-cart Open Source E-commerce											|
// +------------------------------------------------------------------------+
// | Copyright (c) 2004 The zen-cart developers								|
// |																		|
// | http://www.zen-cart.com/index.php										|
// |																		|
// | Portions Copyright (c) 2003 osCommerce									|
// +------------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,			|
// | that is bundled with this package in the file LICENSE, and is			|
// | available through the world-wide-web at the following url:				|
// | http://www.zen-cart.com/license/2_0.txt.								|
// | If you did not receive a copy of the zen-cart license and are unable	|
// | to obtain it through the world-wide-web, please send a note to			|
// | license@zen-cart.com so we can mail you a copy immediately.			|
// +------------------------------------------------------------------------+
//  $Id$
//

		$parameters = array('products_name' => '',
							'products_description' => '',
							'products_url' => '',
							'products_id' => '',
							'products_quantity' => '',
							'products_model' => '',
							'products_manufacturers_model' => '',											 
							'products_image' => '',
							'products_price' => '',
							'products_commission' => '',
							'products_cogs' => '',											 
							'products_virtual' => DEFAULT_PRODUCT_PRODUCTS_VIRTUAL,
							'products_weight' => '',
							'products_date_added' => '',
							'products_last_modified' => '',
							'products_date_available' => '',
							'products_status' => '',
							'products_tax_class_id' => DEFAULT_PRODUCT_TAX_CLASS_ID,
							'manufacturers_id' => '',
							'suppliers_id' => '',
							'products_barcode' => '',																							
							'products_quantity_order_min' => '',
							'products_quantity_order_units' => '',
							'products_priced_by_attribute' => '',
							'product_is_free' => '',
							'product_is_call' => '',
							'products_quantity_mixed' => '',
							'product_is_always_free_ship' => DEFAULT_PRODUCT_PRODUCTS_IS_ALWAYS_FREE_SHIPPING,
							'products_qty_box_status' => PRODUCTS_QTY_BOX_STATUS,
							'products_quantity_order_max' => '0',
							'products_sort_order' => '0',
							'products_discount_type' => '0',
							'products_discount_type_from' => '0',
							'lowest_purchase_price' => '0',
							'master_categories_id' => '',
							'purchase_group_id' => '',
							'reorders_interval' => '',
							'reorders_pending' => ''
						);

		$pInfo = new objectInfo($parameters);

		if (isset($_GET['pID']) && empty($_POST)) {
			$product = $gBitDb->query( "SELECT pd.`products_name`, pd.`products_description`, pd.`products_url`, p.*, ".$gBitDb->mDb->SQLDate('Y-m-d','p.`products_date_available`')." as `products_date_available`, p.`products_status`, p.`products_tax_class_id`
										FROM " . TABLE_PRODUCTS . " p INNER JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON( p.`products_id` = pd.`products_id` )
										WHERE p.`products_id` = ? AND pd.`language_id` = ? ", array( (int)$_GET['pID'], (int)$_SESSION['languages_id']  ) );

			$pInfo->objectInfo($product->fields);
		} elseif (zen_not_null($_POST)) {
			$pInfo->objectInfo($_POST);
			$products_name = $_POST['products_name'];
			$products_description = $_POST['products_description'];
			$products_url = $_POST['products_url'];
		}

		$manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
		$manufacturers = $gBitDb->Execute("select `manufacturers_id`, `manufacturers_name` from " . TABLE_MANUFACTURERS . " order by `manufacturers_name`");
		while (!$manufacturers->EOF) {
			$manufacturers_array[] = array('id' => $manufacturers->fields['manufacturers_id'], 'text' => $manufacturers->fields['manufacturers_name']);
			$manufacturers->MoveNext();
		}

		$suppliers_array = array(array('id' => '', 'text' => TEXT_NONE));
		$suppliers = $gBitDb->Execute("select `suppliers_id`, `suppliers_name` from " . TABLE_SUPPLIERS . " order by `suppliers_name`");
		while (!$suppliers->EOF) {
			$suppliers_array[] = array('id' => $suppliers->fields['suppliers_id'], 'text' => $suppliers->fields['suppliers_name']);
			$suppliers->MoveNext();
		}

		$tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
		$tax_class = $gBitDb->Execute("select `tax_class_id`, `tax_class_title` from " . TABLE_TAX_CLASS . " order by `tax_class_title`");
		while (!$tax_class->EOF) {
			$tax_class_array[] = array('id' => $tax_class->fields['tax_class_id'], 'text' => $tax_class->fields['tax_class_title']);
			$tax_class->MoveNext();
		}

		$languages = zen_get_languages();

		if (!isset($pInfo->products_status)) $pInfo->products_status = '1';
		switch ($pInfo->products_status) {
			case '0': $in_status = false; $out_status = true; break;
			case '1':
			default: $in_status = true; $out_status = false;
				break;
		}
// set to out of stock if categories_status is off and new product or existing products_status is off
		if (zen_get_categories_status($current_category_id) == '0' and $pInfo->products_status != '1') {
			$pInfo->products_status = 0;
			$in_status = false;
			$out_status = true;
		}

// Virtual Products
		if (!isset($pInfo->products_virtual) && defined( 'PRODUCTS_VIRTUAL_DEFAULT' ) ) {
		$pInfo->products_virtual = PRODUCTS_VIRTUAL_DEFAULT;
	}
		switch ($pInfo->products_virtual) {
			case '0': $is_virtual = false; $not_virtual = true; break;
			case '1': $is_virtual = true; $not_virtual = false; break;
			default: $is_virtual = false; $not_virtual = true;
		}
// Always Free Shipping
		if( !isset($pInfo->product_is_always_free_ship) && defined( 'PRODUCTS_IS_ALWAYS_FREE_SHIPPING_DEFAULT' ) ) {
		$pInfo->product_is_always_free_ship = PRODUCTS_IS_ALWAYS_FREE_SHIPPING_DEFAULT;
	}
		switch ($pInfo->product_is_always_free_ship) {
			case '0': $is_product_is_always_free_ship = false; $not_product_is_always_free_ship = true; break;
			case '1': $is_product_is_always_free_ship = true; $not_product_is_always_free_ship = false; break;
			default: $is_product_is_always_free_ship = false; $not_product_is_always_free_ship = true;
		}
// products_qty_box_status shows
		if (!isset($pInfo->products_qty_box_status)) $pInfo->products_qty_box_status = PRODUCTS_QTY_BOX_STATUS;
		switch ($pInfo->products_qty_box_status) {
			case '0': $is_products_qty_box_status = false; $not_products_qty_box_status = true; break;
			case '1': $is_products_qty_box_status = true; $not_products_qty_box_status = false; break;
			default: $is_products_qty_box_status = true; $not_products_qty_box_status = false;
		}
// Product is Priced by Attributes
		if (!isset($pInfo->products_priced_by_attribute)) $pInfo->products_priced_by_attribute = '0';
		switch ($pInfo->products_priced_by_attribute) {
			case '0': $is_products_priced_by_attribute = false; $not_products_priced_by_attribute = true; break;
			case '1': $is_products_priced_by_attribute = true; $not_products_priced_by_attribute = false; break;
			default: $is_products_priced_by_attribute = false; $not_products_priced_by_attribute = true;
		}
// Product is Free
		if (!isset($pInfo->product_is_free)) $pInfo->product_is_free = '0';
		switch ($pInfo->product_is_free) {
			case '0': $in_product_is_free = false; $out_product_is_free = true; break;
			case '1': $in_product_is_free = true; $out_product_is_free = false; break;
			default: $in_product_is_free = false; $out_product_is_free = true;
		}
// Product is Call for price
		if (!isset($pInfo->product_is_call)) $pInfo->product_is_call = '0';
		switch ($pInfo->product_is_call) {
			case '0': $in_product_is_call = false; $out_product_is_call = true; break;
			case '1': $in_product_is_call = true; $out_product_is_call = false; break;
			default: $in_product_is_call = false; $out_product_is_call = true;
		}
// Products can be purchased with mixed attributes retail
		if (!isset($pInfo->products_quantity_mixed)) $pInfo->products_quantity_mixed = '0';
		switch ($pInfo->products_quantity_mixed) {
			case '0': $in_products_quantity_mixed = false; $out_products_quantity_mixed = true; break;
			case '1': $in_products_quantity_mixed = true; $out_products_quantity_mixed = false; break;
			default: $in_products_quantity_mixed = true; $out_products_quantity_mixed = false;
		}

// set image overwrite
	$on_overwrite = true;
	$off_overwrite = false;
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script type="text/javascript"><!--
	var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "products_date_available","btnDate1","<?php echo $pInfo->products_date_available; ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
<script type="text/javascript"><!--
var tax_rates = new Array();
<?php
		for ($i=0, $n=sizeof($tax_class_array); $i<$n; $i++) {
			if ($tax_class_array[$i]['id'] > 0) {
				echo 'tax_rates["' . $tax_class_array[$i]['id'] . '"] = ' . zen_get_tax_rate_value($tax_class_array[$i]['id']) . ';' . "\n";
			}
		}
?>
function doRound(x, places) {
	return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
}

function getTaxRate() {
	var selected_value = document.forms["new_product"].products_tax_class_id.selectedIndex;
	var parameterVal = document.forms["new_product"].products_tax_class_id[selected_value].value;

	if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
		return tax_rates[parameterVal];
	} else {
		return 0;
	}
}

function updateGross() {
	var taxRate = getTaxRate();
	var grossValue = document.forms["new_product"].products_price.value;

	if (taxRate > 0) {
		grossValue = grossValue * ((taxRate / 100) + 1);
	}

	document.forms["new_product"].products_price_gross.value = doRound(grossValue, 4);
			updateProfit();
			updateMargin();	 
			updateRounding();		
}

function updateNet() {
	var taxRate = getTaxRate();
	var netValue = document.forms["new_product"].products_price_gross.value;

	if (taxRate > 0) {
		netValue = netValue / ((taxRate / 100) + 1);
	}

	document.forms["new_product"].products_price.value = doRound(netValue, 4);
			updateProfit();
			updateMargin();
			updateRounding();		
}
function updateFromMargin() {
	 document.forms["new_product"].products_price.value = (document.forms["new_product"].products_cogs.value/100)*document.forms["new_product"].products_margin.value + parseFloat(document.forms["new_product"].products_cogs.value);
	var taxRate = getTaxRate();
	var grossValue = document.forms["new_product"].products_price.value;
	if (taxRate > 0) {
		grossValue = grossValue * ((taxRate / 100) + 1);
	}
	document.forms["new_product"].products_price_gross.value = doRound(grossValue, 4);
	updateProfit();
	RoundingHold();
}
function updateFromProfit() {
	document.forms["new_product"].products_price.value = parseFloat(document.forms["new_product"].products_cogs.value) + parseFloat(document.forms["new_product"].products_profit.value);
	var taxRate = getTaxRate();
	var grossValue = document.forms["new_product"].products_price.value;
	if (taxRate > 0) {
		grossValue = grossValue * ((taxRate / 100) + 1);
	}
	document.forms["new_product"].products_price_gross.value = doRound(grossValue, 4);	 
	updateMargin();
}
function updateFromCost() {	 
	 updateProfit();	 
	 updateMargin();	 
}
function updateProfit() {
	 var profit;
	 profit = (document.forms["new_product"].products_price.value)-(document.forms["new_product"].products_cogs.value);
	 document.forms["new_product"].products_profit.value = doRound(profit, 2);
}
function updateMargin() {	 
	 var margin;
	 margin = (document.forms["new_product"].products_price.value / (document.forms["new_product"].products_cogs.value / 100)) - 100;	
	 document.forms["new_product"].products_margin.value = doRound(margin, 2);
}
function updateRounding() {	
	var someStr;
	var someArray
	someStr = document.forms["new_product"].products_price_gross.value;
	someArray = someStr.split('.');
	rounding = someArray[1];
	document.forms["new_product"].products_rounding.value = doRound(rounding, 2);
}
function updateFromRounding() {	
	var grossStr;
	var grossArray
	grossStr = document.forms["new_product"].products_price_gross.value;
	grossArray = grossStr.split('.');
	grossStr = grossArray[0];
	grossStr += "." + document.forms["new_product"].products_rounding.value;
	document.forms["new_product"].products_price_gross.value = grossStr;
	updateNet();
}
function RoundingHold() {	
	var grossStr;
	var grossArray
	grossStr = document.forms["new_product"].products_price_gross.value;
	grossArray = grossStr.split('.');
	grossStr = grossArray[0];
	grossStr += "." + document.forms["new_product"].products_rounding.value;
	document.forms["new_product"].products_price_gross.value = grossStr;
}

//--></script>
		<?php
//	echo $type_admin_handler;
echo zen_draw_form_admin('new_product', $type_admin_handler , 'cPath=' . $cPath . (isset($_GET['product_type']) ? '&product_type=' . $_GET['product_type'] : '') . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . '&action=new_product_preview' . (isset($_GET['page']) ? '&page=' . $_GET['page'] : ''), 'post', 'enctype="multipart/form-data"'); ?>

	<div class="form-group">
		<label for="product-category">Product in Category</label>
		<?php echo zen_output_generated_category_path($current_category_id); ?>
	</div>
		<table border="0" cellspacing="0" cellpadding="2">
			<tr>
				<td class="main" align="right"><?php echo zen_draw_hidden_field('products_date_added', (zen_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . zen_image_submit('button_preview.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . zen_href_link_admin(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '')) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
			</tr>
			<tr>
				<td><table border="0" cellspacing="0" cellpadding="2">
<?php
// show when product is linked
if( !empty( $_GET['pID'] ) && zen_get_product_is_linked($_GET['pID']) == 'true') {
?>
					<tr>
						<td class="main"><?php echo TEXT_MASTER_CATEGORIES_ID; ?></td>
						<td class="main">
							<?php
								// echo zen_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id);
								echo zen_image(DIR_WS_IMAGES . 'icon_yellow_on.gif', IMAGE_ICON_LINKED) . '&nbsp;&nbsp;';
								echo zen_draw_pull_down_menu('master_categories_id', zen_get_master_categories_pulldown($_GET['pID']), $pInfo->master_categories_id); ?>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="main"><?php echo TEXT_INFO_MASTER_CATEGORIES_ID; ?></td>
					</tr>
					<tr>
						<td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '100%', '2'); ?></td>
					</tr>

<?php } ?>
<?php
// hidden fields not changeable on products page
echo zen_draw_hidden_field('master_categories_id', (!empty($pInfo->master_categories_id) ? $pInfo->master_categories_id : $current_category_id ) );
echo zen_draw_hidden_field('products_discount_type', $pInfo->products_discount_type);
echo zen_draw_hidden_field('products_discount_type_from', $pInfo->products_discount_type_from);
echo zen_draw_hidden_field('lowest_purchase_price', $pInfo->lowest_purchase_price);
?>
					<tr>
						<td colspan="2" class="main" align="center"><?php echo (zen_get_categories_status($current_category_id) == '0' ? TEXT_CATEGORIES_STATUS_INFO_OFF : '') . ($out_status == true ? ' ' . TEXT_PRODUCTS_STATUS_INFO_OFF : ''); ?></td>
					<tr>
<?php
		for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
					<tr>
						<td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_NAME; ?></td>
						<td class="main"><?php echo zen_draw_input_field('products_name[' . $languages[$i]['id'] . ']', (isset($products_name[$languages[$i]['id']]) ? stripslashes($products_name[$languages[$i]['id']]) : zen_get_products_name($pInfo->products_id, $languages[$i]['id'])), zen_set_field_length(TABLE_PRODUCTS_DESCRIPTION, 'products_name')) . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
					</tr>
<?php
		}
?>
					<tr>
						<td class="main"><?php echo TEXT_PRODUCTS_STATUS; ?></td>
						<td class="main"><?php echo zen_draw_radio_field('products_status', '1', $in_status) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE . '&nbsp;' . zen_draw_radio_field('products_status', '0', $out_status) . '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE; ?></td>
					</tr>
					<tr>
						<td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>
					<tr>
						<td class="main"><?php echo TEXT_PRODUCTS_DATE_AVAILABLE; ?><br /><small>(YYYY-MM-DD)</small></td>
						<td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; ?><script type="text/javascript">dateAvailable.writeControl(); dateAvailable.dateFormat="yyyy-MM-dd";</script></td>
					</tr>
					<tr>
						<td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>
					<tr>
						<td class="main"><?php echo tra( 'Subscription Frequency' ); ?>:</td>
						<td class="main">
				<select class="form-control" name="reorders_interval_number">
					<option value="">None</option>
<?php
for( $i=1; $i<=12; $i++ ) {
	print "<option value=\"$i\" ".($pInfo->reorders_interval == $i ? 'selected="selected"' : '' ).">$i</option>\n";
}
?>
				</select>

				<select class="form-control" name="reorders_interval">
					<option value="Years">Years</option>
					<option value="Months">Months</option>
					<option value="Weeks">Weeks</option>
					<option value="Days">Days</option>
				</select>
			</td>
					</tr>
					<tr>
						<td class="main"><?php echo tra( 'Subscription Repeats' ); ?>:</td>
						<td class="main"><input type="text" name="reorders_pending" value="<?php echo $pInfo->reorders_pending ? $pInfo->reorders_pending : 999; ?>" /></td>
					</tr>
					<tr>
						<td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>
					<tr>
						<td class="main"><?php echo TEXT_PRODUCTS_MANUFACTURER; ?></td>
						<td class="main"><?php echo zen_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id); ?></td>
					</tr>					
					<tr>
						<td class="main"><?php echo TEXT_PRODUCTS_MANUFACTURERS_MODEL; ?></td>
						<td class="main"><?php echo zen_draw_input_field('products_manufacturers_model', $pInfo->products_manufacturers_model, zen_set_field_length(TABLE_PRODUCTS, 'products_manufacturers_model')); ?></td>
					</tr>					 
					<tr>
						<td class="main"><?php echo TEXT_PRODUCTS_SUPPLIER; ?></td>
						<td class="main"><?php echo zen_draw_pull_down_menu('suppliers_id', $suppliers_array, $pInfo->suppliers_id); ?></td>
					</tr>					
					
					<tr>
						<td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>
<?php
	global $gBitUser;
	$listHash = array();
	$groups = $gBitUser->getAllGroups( $listHash );

?>

					<tr>
						<td class="main"><?=tra("Related Group ID")?></td>
						<td class="main">
				<select class="form-control" name="purchase_group_id">
					<option value=""></value>
<?php
	foreach( $groups as $group ) {
		print '<option value="'.$group['group_id'].'" '.($pInfo->purchase_group_id == $group['group_id'] ? 'selected="selected"': '') .' >'.$group['group_name']."</option>\n";
	}
?>
				</select>
			 User will be added to this group upon successful purchase</td>
					</tr>

					<tr>
						<td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>

					<tr>
						<td class="main"><?php echo TEXT_PRODUCT_IS_FREE; ?></td>
						<td class="main"><?php echo zen_draw_radio_field('product_is_free', '1', ($in_product_is_free==1)) . '&nbsp;' . 'Yes' . '&nbsp;&nbsp;' . zen_draw_radio_field('product_is_free', '0', ($in_product_is_free==0)) . '&nbsp;' . 'No' . ' ' . ($pInfo->product_is_free == 1 ? '<span class="errorText">' . TEXT_PRODUCTS_IS_FREE_EDIT . '</span>' : ''); ?></td>
					</tr>
					<tr>
						<td class="main"><?php echo TEXT_PRODUCT_IS_CALL; ?></td>
						<td class="main"><?php echo zen_draw_radio_field('product_is_call', '1', ($in_product_is_call==1)) . '&nbsp;' . 'Yes' . '&nbsp;&nbsp;' . zen_draw_radio_field('product_is_call', '0', ($in_product_is_call==0)) . '&nbsp;' . 'No' . ' ' . ($pInfo->product_is_call == 1 ? '<span class="errorText">' . TEXT_PRODUCTS_IS_CALL_EDIT . '</span>' : ''); ?></td>
					</tr>

					<tr>
						<td class="main"><?php echo TEXT_PRODUCTS_PRICED_BY_ATTRIBUTES; ?></td>
						<td class="main"><?php echo zen_draw_radio_field('products_priced_by_attribute', '1', $is_products_priced_by_attribute) . '&nbsp;' . TEXT_PRODUCT_IS_PRICED_BY_ATTRIBUTE . '&nbsp;&nbsp;' . zen_draw_radio_field('products_priced_by_attribute', '0', $not_products_priced_by_attribute) . '&nbsp;' . TEXT_PRODUCT_NOT_PRICED_BY_ATTRIBUTE . ' ' . ($pInfo->products_priced_by_attribute == 1 ? '<span class="errorText">' . TEXT_PRODUCTS_PRICED_BY_ATTRIBUTES_EDIT . '</span>' : ''); ?></td>
					</tr>

					<tr>
						<td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>
					<tr bgcolor="#ebebff">
						<td class="main"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></td>
						<td class="main"><?php echo zen_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id, 'onchange="updateGross()"'); ?></td>
					</tr>
					<tr bgcolor="#ebebff">
						<td class="main"><?php echo TEXT_PRODUCTS_COGS_NET; ?></td>
						<td class="main"><?php echo zen_draw_input_field('products_cogs', $pInfo->products_cogs , 'onKeyUp="updateFromCost()"'); ?></td>
					</tr>
					<tr bgcolor="#ebebff">
						<td class="main"><?php echo TEXT_PRODUCTS_MARGIN; ?></td>
			            <td class="main"><?php echo zen_draw_input_field('products_margin', isset($products_margin) ? $products_margin : 0 , 'onKeyUp="updateFromMargin()"'); ?></td>
					</tr> 
					<tr bgcolor="#ebebff">
						<td class="main"><?php echo TEXT_PRODUCTS_PROFIT; ?></td>
			            <td class="main"><?php echo zen_draw_input_field('products_profit', isset($products_profit) ? $products_profit : 0 , 'onKeyUp="updateFromProfit()"'); ?></td>
					</tr>
					<tr bgcolor="#ebebff">
						<td class="main"><?php echo TEXT_PRODUCTS_ROUNDING; ?></td>
			            <td class="main"><?php echo zen_draw_input_field('products_rounding', isset($products_rounding) ? $products_rounding : 0 , 'onKeyUp="updateFromRounding()"'); ?></td>
					</tr>																													
					<tr bgcolor="#ebebff">
						<td class="main"><?php echo TEXT_PRODUCTS_PRICE_NET; ?></td>
						<td class="main"><?php echo zen_draw_input_field('products_price', $pInfo->products_price, 'onKeyUp="updateGross()"'); ?></td>
					</tr>
					<tr bgcolor="#ebebff">
						<td class="main"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
						<td class="main"><?php echo zen_draw_input_field('products_price_gross', $pInfo->products_price, 'OnKeyUp="updateNet()"'); ?></td>
					</tr>
					
		 
					
					<tr>
						<td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>
					<tr>
						<td ><?php echo tra( 'Products Commission' ); ?></td>
						<td ><?php echo zen_draw_input_field( 'products_commission', $pInfo->products_commission ); ?></td>
					</tr>

					<tr>
						<td class="main"><?php echo TEXT_PRODUCTS_VIRTUAL; ?></td>
						<td class="main"><?php echo zen_draw_radio_field('products_virtual', '1', $is_virtual) . '&nbsp;' . TEXT_PRODUCT_IS_VIRTUAL . '&nbsp;' . zen_draw_radio_field('products_virtual', '0', $not_virtual) . '&nbsp;' . TEXT_PRODUCT_NOT_VIRTUAL . ' ' . ($pInfo->products_virtual == 1 ? '<br /><span class="errorText">' . TEXT_VIRTUAL_EDIT . '</span>' : ''); ?></td>
					</tr>

					<tr>
						<td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>
					<tr>
						<td class="main"><?php echo TEXT_PRODUCTS_IS_ALWAYS_FREE_SHIPPING; ?></td>
						<td class="main"><?php echo zen_draw_radio_field('product_is_always_free_ship', '1', $is_product_is_always_free_ship) . '&nbsp;' . TEXT_PRODUCT_IS_ALWAYS_FREE_SHIPPING . '&nbsp;' . zen_draw_radio_field('product_is_always_free_ship', '0', $not_product_is_always_free_ship) . '&nbsp;' . TEXT_PRODUCT_NOT_ALWAYS_FREE_SHIPPING . ' ' . ($pInfo->product_is_always_free_ship == 1 ? '<br /><span class="errorText">' . TEXT_FREE_SHIPPING_EDIT . '</span>' : ''); ?></td>
					</tr>

					<tr>
						<td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>
					<tr>
						<td class="main"><?php echo TEXT_PRODUCTS_QTY_BOX_STATUS; ?></td>
						<td class="main"><?php echo zen_draw_radio_field('products_qty_box_status', '1', $is_products_qty_box_status) . '&nbsp;' . TEXT_PRODUCTS_QTY_BOX_STATUS_ON . '&nbsp;' . zen_draw_radio_field('products_qty_box_status', '0', $not_products_qty_box_status) . '&nbsp;' . TEXT_PRODUCTS_QTY_BOX_STATUS_OFF . ' ' . ($pInfo->products_qty_box_status == 0 ? '<br /><span class="errorText">' . TEXT_PRODUCTS_QTY_BOX_STATUS_EDIT . '</span>' : ''); ?></td>
					</tr>

					<tr>
						<td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>

					<tr>
						<td class="main"><?php echo TEXT_PRODUCTS_QUANTITY_MIN_RETAIL; ?></td>
						<td class="main"><?php echo zen_draw_input_field('products_quantity_order_min', ($pInfo->products_quantity_order_min == 0 ? 1 : $pInfo->products_quantity_order_min)); ?></td>
					</tr>

					<tr>
						<td class="main"><?php echo TEXT_PRODUCTS_QUANTITY_MAX_RETAIL; ?></td>
						<td class="main"><?php echo zen_draw_input_field('products_quantity_order_max', $pInfo->products_quantity_order_max); ?>&nbsp;&nbsp;<?php echo TEXT_PRODUCTS_QUANTITY_MAX_RETAIL_EDIT; ?></td>
					</tr>

					<tr>
						<td class="main"><?php echo TEXT_PRODUCTS_QUANTITY_UNITS_RETAIL; ?></td>
						<td class="main"><?php echo zen_draw_input_field('products_quantity_order_units', ($pInfo->products_quantity_order_units == 0 ? 1 : $pInfo->products_quantity_order_units)); ?></td>
					</tr>

					<tr>
						<td class="main"><?php echo TEXT_PRODUCTS_MIXED; ?></td>
						<td class="main"><?php echo zen_draw_radio_field('products_quantity_mixed', '1', $in_products_quantity_mixed) . '&nbsp;' . 'Yes' . '&nbsp;&nbsp;' . zen_draw_radio_field('products_quantity_mixed', '0', $out_products_quantity_mixed) . '&nbsp;' . 'No'; ?></td>
					</tr>

					<tr>
						<td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>

<script type="text/javascript"><!--
updateGross();
//--></script>
<?php
		for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
					<tr>
						<td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_DESCRIPTION; ?></td>
						<td colspan="2"><table border="0" cellspacing="0" cellpadding="0">
				<table>
				<tr>
							 		<td class="main" width="100%">
				<?php if (is_null($_SESSION['html_editor_preference_status'])) echo TEXT_HTML_EDITOR_NOT_DEFINED; ?>
				<?php if ($_SESSION['html_editor_preference_status']=="FCKEDITOR") {
//					if ($_SESSION['html_editor_preference_status']=="FCKEDITOR") require(DIR_FS_ADMIN_INCLUDES.'fckeditor.php');
					$oFCKeditor = new FCKeditor ;
					$oFCKeditor->Value = (isset($products_description[$languages[$i]['id']])) ? stripslashes($products_description[$languages[$i]['id']]) : zen_get_products_description($pInfo->products_id, $languages[$i]['id']) ;
					$oFCKeditor->CreateFCKeditor( 'products_description[' . $languages[$i]['id'] . ']', '99%', '230' ) ;	//instanceName, width, height (px or %)
				} else { // using HTMLAREA or just raw "source"
					echo zen_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', 'soft', '100%', '20', (isset($products_description[$languages[$i]['id']])) ? stripslashes($products_description[$languages[$i]['id']]) : zen_get_products_description($pInfo->products_id, $languages[$i]['id'])); //,'id="'.'products_description' . $languages[$i]['id'] . '"');
							} ?>
					</td>
					<td class="main" width="25" valign="top"><?php echo zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
							</tr>
				</table>

				</td>
					</tr>
<?php
		}
?>
					<tr>
						<td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>
					<tr>
						<td class="main"><?php echo TEXT_PRODUCTS_QUANTITY; ?></td>
						<td class="main"><?php echo zen_draw_input_field('products_quantity', $pInfo->products_quantity); ?></td>
					</tr>
					<tr>
						<td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>
					<tr>
						<td class="main"><?php echo TEXT_PRODUCTS_MODEL; ?></td>
						<td class="main"><?php echo zen_draw_input_field('products_model', $pInfo->products_model, zen_set_field_length(TABLE_PRODUCTS, 'products_model')); ?></td>
					</tr>
					<tr>
						<td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>
<?php
	$dir = dir(DIR_FS_CATALOG_IMAGES);
	$dir_info[] = array('id' => '', 'text' => "Main Directory");
	while ($file = $dir->read()) {
		if (is_dir(DIR_FS_CATALOG_IMAGES . $file) && strtoupper($file) != 'CVS' && $file != "." && $file != "..") {
			$dir_info[] = array('id' => $file . '/', 'text' => $file);
		}
	}

	$default_directory = substr( $pInfo->products_image, 0,strpos( $pInfo->products_image, '/')+1);
?>
					<tr>
						<td class="main"><?php echo TEXT_PRODUCTS_IMAGE; ?></td>
						<td class="main"><?php echo zen_draw_file_field('products_image') . '<br />' . $pInfo->products_image . zen_draw_hidden_field('products_previous_image', $pInfo->products_image); ?></td>
					</tr>
					<tr>
						<td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>
<?php
		for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
					<tr>
						<td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_URL . '<br /><small>' . TEXT_PRODUCTS_URL_WITHOUT_HTTP . '</small>'; ?></td>
						<td class="main"><?php echo zen_draw_input_field('products_url[' . $languages[$i]['id'] . ']', (isset($products_url[$languages[$i]['id']]) ? $products_url[$languages[$i]['id']] : zen_get_products_url($pInfo->products_id, $languages[$i]['id'])), zen_set_field_length(TABLE_PRODUCTS_DESCRIPTION, 'products_url')) . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) ; ?></td>
					</tr>
<?php
		}
?>
					<tr>
						<td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>
					<tr>
						<td class="main"><?php echo TEXT_PRODUCTS_WEIGHT; ?></td>
						<td class="main"><?php echo zen_draw_input_field('products_weight', $pInfo->products_weight); ?></td>
					</tr>
					<tr>
						<td class="main"><?php echo TEXT_PRODUCTS_BARCODE; ?></td>
						<td class="main"><?php echo zen_draw_input_field('products_barcode', $pInfo->products_barcode); ?></td>
					</tr>					
					<tr>
						<td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>
					<tr>
						<td class="main"><?php echo TEXT_PRODUCTS_SORT_ORDER; ?></td>
						<td class="main"><?php echo zen_draw_input_field('products_sort_order', $pInfo->products_sort_order); ?></td>
					</tr>
				</table></td>
			</tr>
			<tr>
				<td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			</tr>
			<tr>
				<td class="main" align="right"><?php echo zen_draw_hidden_field('products_date_added', (zen_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . zen_image_submit('button_preview.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . zen_href_link_admin(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '')) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
			</tr>
		</table></form>
