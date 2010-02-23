{include file="bitpackage:bitcommerce/admin_header_inc.tpl"}
{include_php file="`$smarty.const.BITCOMMERCE_PKG_PATH`admin/includes/header_navigation.php"}

<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javascript/kruse/CalendarPopup.js"></script>
<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javascript/kruse/PopupWindow.js"></script>
<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javascript/kruse/AnchorPosition.js"></script>
<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javascript/kruse/date.js"></script>
<div id="caldiv" style="width:200px;position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>

<div class="admin bitcommerce">
	<div class="header">
		<h1 class="header">{tr}Revenue Breakdown{/tr} {$smarty.request.timeframe}</h1>
	</div>
	<div class="body">


<div class="span-12">
{include file="bitpackage:bitcommerce/admin_stats_sales_by_type_inc.tpl"}
{include file="bitpackage:bitcommerce/admin_stats_sales_by_option_inc.tpl"}
</div>
<div class="span-12 last">
<table class="data stats">
	<caption>{tr}Customer Created Products Stats{/tr}</caption>
	<thead>
	<tr>
		<th colspan="2">{tr}New Customers{/tr}</th>
		<th>Conversion Rate</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td>New Registrations</td><td class="item">{$statsCustomers.new_registrations}</td><td></td>
	</tr>
	<tr>
		<td class="item">{tr}New Customers That Created Products{/tr}</td>
		<td class="item">{$statsCustomers.new_customers_that_created_products}</td>
		<td class="item selected">{math equation="round(x/y*100)" y=$statsCustomers.new_registrations x=$statsCustomers.new_customers_that_created_products}%</td>
	</tr>
	<tr>
		<td class="item">{tr}New Customers That Purchased New Products{/tr}</td>
		<td class="item">{$statsCustomers.new_customers_that_purchased_new_products}</td>
		<td class="item selected">{math equation="round(x/y*100)" x=$statsCustomers.new_customers_that_purchased_new_products y=$statsCustomers.new_customers_that_created_products}%</td>
	</tr>
	<tr>
		<td class="item">{tr}New Products Created By New Customers{/tr}</td>
		<td class="item">{$statsCustomers.new_products_created_by_new_customers}</td>
	</tr>
	<tr>
		<td class="item">{tr}New Products Purchased By New Customers{/tr}</td>
		<td class="item">{$statsCustomers.new_products_purchased_by_new_customers}</td>
		<td class="item selected">{math equation="round(x/y*100)" x=$statsCustomers.new_products_purchased_by_new_customers y=$statsCustomers.new_products_created_by_new_customers}%</td>
	</tr>
	<tr><th colspan="3">{tr}Existing Customers{/tr}</th></tr>
	<tr>
		<td class="item">{tr}Existing Customers That Created Products{/tr}</td>
		<td class="item">{$statsCustomers.all_customers_that_created_products-$statsCustomers.new_customers_that_created_products}</td>
		<td class="item selected"></td>
	</tr>
	<tr>
		<td class="item">{tr}Existing Customers That Purchased New Products{/tr}</td>
		<td class="item">{$statsCustomers.all_customers_that_purchased_new_products-$statsCustomers.new_customers_that_purchased_new_products}</td>
		<td class="item selected">{math equation="round((w-x)/(y-z)*100)" w=$statsCustomers.all_customers_that_purchased_new_products x=$statsCustomers.new_customers_that_purchased_new_products y=$statsCustomers.all_customers_that_created_products z=$statsCustomers.new_customers_that_created_products}%</td>
	</tr>
	<tr>
		<td class="item">{tr}New Products Created By Exsting Customers{/tr}</td>
		<td class="item">{$statsCustomers.new_products_created_by_all_customers-$statsCustomers.new_products_created_by_new_customers}</td>
	</tr>
	<tr>
		<td class="item">{tr}New Products Purchased By Exsting Customers{/tr}</td>
		<td class="item">{$statsCustomers.new_products_purchased_by_all_customers-$statsCustomers.new_products_purchased_by_new_customers}</td>
		<td class="item selected">{math equation="round((w-x)/(y-z)*100)" w=$statsCustomers.new_products_purchased_by_all_customers x=$statsCustomers.new_products_purchased_by_new_customers y=$statsCustomers.new_products_created_by_all_customers z=$statsCustomers.new_products_created_by_new_customers}%</td>
	</tr>
	<tr><th colspan="3">{tr}Totals{/tr}</th></tr>
	<tr>
		<td class="item">Total Unique Products Purchased</td>
		<td class="item">{$statsCustomers.unique_products_ordered}</td>
	</tr>
	<tr>
		<td class="item">Total Orders</td><td class="item">{$statsCustomers.total_orders}</td>
	</tr>
	<tr>
		<td class="item"><a href="{$smarty.const.BITCOMMERCE_PKG_PATH}admin/revenue.php?period={$smarty.request.period}&">{tr}All Time Periods{/tr}</a></td>
	</tr>
	</tbody>
</table>

</div>

<div class="span-12 last">
<table class="data stats">
	<caption>{tr}Revenue By Interest{/tr}</caption>
	<thead>
	<tr>
		<th>{tr}Interest{/tr}</th>
		<th>{tr}Orders{/tr}</th>
		<th>{tr}Amount{/tr}</th>
		<th>{tr}Avg. Size{/tr}</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$valuableInterests item=interest key=interestsId}
	<tr>
		<td class="item">{$interest.interests_name}</td>
		<td class="item">{$interest.total_orders}</td>
		<td class="item currency">${$interest.total_revenue|round:2}</td>
		<td class="item currency">${math equation="round(x/y,2)" x=$interest.total_revenue y=$interest.total_orders}</td>
	</tr>
	{/foreach}
	</tbody>
</table>
</div>


<div class="span-12 last">
<table class="data stats">
	<caption>{tr}Most Valuable Customers{/tr}</caption>
	<thead>
	<tr>
		<th>{tr}Customer{/tr}</th>
		<th>{tr}Orders{/tr}</th>
		<th>{tr}Amount{/tr}</th>
		<th>{tr}Avg. Size{/tr}</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$valuableCustomers item=cust key=custId}
	<tr>
		<td class="item">{displayname user_id=$custId}<div class="floaticon"><a href="{$smarty.const.BITCOMMERCE_PKG_URL}admin/list_orders.php?user_id={$custId}">Orders</div></td>
		<td class="item">{$cust.total_orders}</td>
		<td class="item currency">${$cust.total_revenue|round:2}</td>
		<td class="item currency">${math equation="round(x/y,2)" x=$cust.total_revenue y=$cust.total_orders}</td>
	</tr>
	{/foreach}
	</tbody>
</table>
</div>


	</div><!-- end .body -->
</div><!-- end .bitcommerce -->
