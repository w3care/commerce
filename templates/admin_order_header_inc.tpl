{literal}
<script type="text/javascript">/* <![CDATA[ */
function editAddress( pAddress ) {
	jQuery.ajax({
		data: 'address_type='+pAddress+'&oID='+{/literal}{$smarty.request.oID}{literal},
		url: "{/literal}{$smarty.const.BITCOMMERCE_PKG_URL}admin/orders.php{literal}",
		timeout: 60000,
		success: function(r) { 
			$('#'+pAddress+'address').html(r);
		}
	})
}
/* ]]> */</script>
{/literal}


<div class="row">
	<div class="span4">
		<h4>{tr}Customer{/tr}</h4>
		{$order->info.date_purchased|bit_long_datetime}<br/>
		{displayname hash=$order->customer} (ID: {$order->customer.user_id})
		<a href="product_history.php?user_id={$order->customer.user_id}">{booticon iname="icon-time" iexplain="Customer Sales History"}</a>
		{smartlink ipackage=users ifile="admin/index.php" assume_user=$order->customer.user_id ititle="Assume User Identity" ibiticon="users/assume_user" iforce=icon} 
		<br/>
{if $order->customer.telephone}
	{$order->customer.telephone}<br/>
{/if}
		<a href="mailto:{$order->customer.email_address}">{$order->customer.email_address}</a><br/>
		{if $order->customer.referer_url}{$order->customer.referer_url|stats_referer_display_short}<br/>{/if}
		{if $customerStats.orders_count == 1}<em>First Order</em>
		{else}
		<strong>Tier {$customerStats.tier|round}</strong>: <a href="list_orders.php?user_id={$order->customer.user_id}&amp;orders_status_id=all&amp;list_filter=all">{$customerStats.orders_count} {tr}orders{/tr} {tr}total{/tr} ${$customerStats.customers_total|round:2}</a> {tr}over{/tr} {$customerStats.customers_age} 
			{if $customerStats.gifts_redeemed || $customerStats.gifts_balance}<br/>
				Gift: ${$customerStats.gifts_redeemed} redeemed {if $customerStats.gifts_balance|round:2}, ${$customerStats.gifts_balance|round:2} {tr}remaining{/tr}{/if}{if $customerStats.commissions}, ${$customerStats.commissions|round:2} {tr}Commissions{/tr}{/if}
			{/if}
		{/if}
	</div>
	<div class="span4">
		<h4>{tr}Payment Info{/tr}</h4>
		{if $order->info.cc_type || $order->info.cc_owner || $order->info.cc_number}
		<div class="clear">
			<div class="floatleft">{$order->info.cc_type}: </div>
			<div class="floatright">{$order->info.cc_owner}</div>
		</div>
		<div class="clear">
			<div class="floatleft">{tr}Number{/tr}: </div>
			<div class="floatright">{$order->info.cc_number}</div>
		</div>
		<div class="clear">
			<div class="floatleft">{tr}Expires{/tr}: </div>
			<div class="floatright">{$order->info.cc_expires} CVV: {$order->getField('cc_cvv')}</div>
		</div>
		<div class="clear">
			<div class="floatleft">{tr}Trans ID{/tr}: </div>
			<div class="floatright">{$order->info.cc_ref_id}</div>
		</div>
		{/if}
		<div class="clear">
			<div class="floatleft">{tr}IP{/tr}:</div>
			<div class="floatright"> {$order->info.ip_address}</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="span4">
		<h4>{tr}Shipping Address{/tr}<a class="icon" onclick="editAddress('delivery');return false;"><i class="icon-edit"></i></a></h4>
		<div id="deliveryaddress">
		{php}
		global $order;
		echo zen_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br />');
		{/php}
		</div>
	</div>
	<div class="span4">
		<h4>{tr}Billing Address{/tr} <a class="icon" onclick="editAddress('billing');return false;"><i class="icon-edit"></i></a></h4>
		<div id="billingaddress">
		{php}
		global $order;
		echo zen_address_format($order->billing['format_id'], $order->billing, 1, '', '<br />');
		{/php}
		</div>
	</div>
</div>

{$notificationBlock}
