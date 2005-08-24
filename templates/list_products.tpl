<div class="listing bitcommerce">
	<div class="header">
		<h1>{tr}Products by{/tr} {$gQueryUser->getDisplayName(1)}</h1>
	</div>

{if $listProducts}
<form name="multiple_products_cart_quantity" action="{$smarty.const.BITCOMMERCE_PKG_URL}index.php?action=multiple_products_add_product" method="post" enctype="multipart/form-data">

	<div class="body">


{if $smarty.const.PRODUCT_LISTING_MULTIPLE_ADD_TO_CART and $runNormal == 'true'}
    <input type="submit" value="{$smarty.const.SUBMIT_BUTTON_ADD_PRODUCTS_TO_CART}" id="submit1" name="submit1" Class="SubmitBtn">
{/if}

{formhelp}To purchase multiple products at once, enter the quantity for each product you would like to purchase, and click "{$smarty.const.SUBMIT_BUTTON_ADD_PRODUCTS_TO_CART}"

{* if $smarty.const.PREV_NEXT_BAR_LOCATION == '1' || $smarty.constPREV_NEXT_BAR_LOCATION == '3'}
no paged display for now - spiderr
  <tr>
    <td class="pageresults"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
    <td class="pageresults" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, zen_get_all_get_params(array('page', 'info', 'x', 'y', 'main_page'))); ?></td>
  </tr>
{/if *}


		<ul class="clear data">
			{foreach from=$listProducts key=productsId item=prod}
				<li class="item {cycle values='odd,even'} {$prod.content_type_guid}">
					<div class="floaticon">
	{if $smarty.const.PRODUCT_LISTING_MULTIPLE_ADD_TO_CART && $prod.products_qty_box_status != '0' && $prod.products_quantity_order_max != '1'}
{tr}Purchase Multiple:{/tr} <input type="text" name="products_id[{$prod.products_id}]" value=0 size="4"><br/>
	{/if}
						{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='icon' serviceHash=$prod}
						<div class="date">
							<br />{tr}Created{/tr}: {$prod.created|bit_short_date}

							{if $prod.hits}
								<br />{tr}Hits{/tr}: {$prod.hits} {$prod.products_viewed}
							{/if}

							{if $prod.products_quantity}
								<br/>{tr}In Stock{/tr}: {$prod.products_quantity}
							{/if}

							{if !$prod.products_virtual && $prod.products_weight}
								<br/>{tr}Shipping Weight{/tr}: {$prod.products_weight|round:2} {tr}lbs{/tr} ( {$prod.products_weight_kg|round:2} {tr}Kg{/tr} )
							{/if}
						</div>
					</div>

					{if $prod.display_url}
						<a href="{$prod.display_url}">
							<img class="thumb" src="{$prod.products_image_url}" alt="{$prod.title}" title="{$prod.title}" />
						</a>
					{/if}
<div class="details">
					<h2><a href="{$prod.display_url}">{$prod.products_name}</a></h2>

					<div class="price">{$prod.display_price}</div>
<div>
{if $gBitProduct->hasAttributes($prod.products_id) or !$smarty.const.PRODUCT_LIST_PRICE_BUY_NOW}
	<a href="{$prod.display_url}">...{tr}more info{/tr}</a>
{else}
	<a href="{$smarty.const.BITCOMMERCE_PKG_URL}?action=buy_now&products_id={$prod.products_id}">{tr}Buy Now!{/tr}</a>
{/if}
{*
            $products_link = '<a href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), 'products_id=' . $listing->fields['products_id']) . '">' . MORE_INFO_TEXT . '</a>';
            $lc_text .= '<br />' . zen_get_buy_now_button($listing->fields['products_id'], $the_button, $products_link) . '<br />' . zen_get_products_quantity_min_units_display($listing->fields['products_id']);
*}
</div>

					{if $prod.products_model}
						{$prod.products_model}<br/>
					{/if}

					{if $prod.manufacturers_name}
						<a href="{$smarty.const.BITCOMMERCE_PKG_URL}?manufacturers_id={$prod.manufacturers_id}">{$prod.manufacturers_name}<br/>
					{/if}

					{if $smarty.const.PRODUCT_LIST_DESCRIPTION}
						<p>{$listProducts.products_description|truncate:PRODUCT_LIST_DESCRIPTION}</p>
					{/if}

</div>
					<div class="clear"></div>
				</li>

{*



            if (isset($_GET['manufacturers_id'])) {
              $lc_text = '<a href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), 'manufacturers_id=' . $_GET['manufacturers_id'] . '&products_id=' . $listing->fields['products_id']) . '">' . $listing->fields['products_name'] . '</a>';
            } else {
              $lc_text = '<a href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing->fields['products_id']) . '">' . $listing->fields['products_name'] . '</a>';
            }
			// add description

            break;

// more info in place of buy now
            $lc_button = '';
            if (zen_has_product_attributes($listing->fields['products_id']) or PRODUCT_LIST_PRICE_BUY_NOW == '0') {
              $lc_button = '<a href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), 'products_id=' . $listing->fields['products_id']) . '">' . MORE_INFO_TEXT . '</a>';
            } else {
              if (PRODUCT_LISTING_MULTIPLE_ADD_TO_CART != 0) {
                $how_many++;
                $lc_button = TEXT_PRODUCT_LISTING_MULTIPLE_ADD_TO_CART . "<input type=\"text\" name=\"products_id[" . $listing->fields['products_id'] . "]\" value=0 size=\"4\">";
              } else {
                $lc_button = '<a href="' . zen_href_link($_GET['main_page'], zen_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing->fields['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_BUY_NOW, BUTTON_BUY_NOW_ALT) . '</a>&nbsp;';
              }
            }
            $the_button = $lc_button;
            $products_link = '<a href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), 'products_id=' . $listing->fields['products_id']) . '">' . MORE_INFO_TEXT . '</a>';
            $lc_text .= '<br />' . zen_get_buy_now_button($listing->fields['products_id'], $the_button, $products_link) . '<br />' . zen_get_products_quantity_min_units_display($listing->fields['products_id']);

            break;
          case 'PRODUCT_LIST_IMAGE':
            $lc_align = 'center';
            if (isset($_GET['manufacturers_id'])) {
              $lc_text = '<a href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), 'manufacturers_id=' . $_GET['manufacturers_id'] . '&products_id=' . $listing->fields['products_id']) . '">' . zen_image(  CommerceProduct::getImageUrl( $listing->fields['products_id'], 'avatar' ), $listing->fields['products_name'] ) . '</a>';
            } else {
              $lc_text = '&nbsp;<a href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing->fields['products_id']) . '">' . zen_image( CommerceProduct::getImageUrl( $listing->fields['products_id'], 'avatar' ), $listing->fields['products_name'] ) . '</a>&nbsp;';
            }
            break;
        }

*}
	{foreachelse}
		<li class="item norecords">
			{tr}No products found.{/tr}
		</li>
	{/foreach}
</ul>

		<div class="clear"></div>
	</div>	<!-- end .body -->

{* if $smarty.const.PREV_NEXT_BAR_LOCATION == '2' || $smarty.const.PREV_NEXT_BAR_LOCATION == '3'}
	<tr>
		<td class="pageresults"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
		<td class="pageresults" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, zen_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
	</tr>
{/if *}

{if $runNormal == 'true' && $smarty.const.PRODUCT_LISTING_MULTIPLE_ADD_TO_CART and $smarty.const.PRODUCT_LISTING_MULTIPLE_ADD_TO_CART >= 2 }
    <input type="submit" align="absmiddle" value="{$smarty.const.SUBMIT_BUTTON_ADD_PRODUCTS_TO_CART}" id="submit1" name="submit1" Class="SubmitBtn"></form>
{/if}

</form>

{/if}

</div>	<!-- end .bitcommerce -->