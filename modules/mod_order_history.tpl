{strip}
{if $sideboxCustomerOrders}
	{bitmodule title=$moduleTitle name="bc_orderhistory"}
		<ul class="list-unstyled">
			{foreach from=$sideboxCustomerOrders key=orderId item=order}
				<li><a class="floaticon" href="{$smarty.const.HTTP_SERVER}{$smarty.const.BITCOMMERCE_PKG_URL}index.php?main_page=index.php&amp;action=cust_order&pid={$order.id}">{biticon ipackage="bitcommerce" iname="small_cart" iexplain="Cart"}</a><a href="{$smarty.const.HTTP_SERVER}{$smarty.const.BITCOMMERCE_PKG_URL}index.php?main_page=product_info&products_id={$order.id}">{$order.name}</a></li>
			{/foreach}
		</ul>
	{/bitmodule}
{/if}
{/strip}
