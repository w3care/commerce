{strip}
{form name="quick_find" action="`$smarty.const.BITCOMMERCE_PKG_URL`index.php?main_page=quick_find" method="get"}
	<input name="main_page" value="advanced_search_result"  type="hidden" />
	<input name="search_in_description" value="1"  type="hidden" />
	<div class="row">
		<input name="keyword" size="18" maxlength="100" style="width: 120px;"  type="text" />
		<input value="Search" style="width: 50px;"  type="submit" />
		<br />
		<a href="{$smarty.const.BITCOMMERCE_PKG_URL}index.php?main_page=advanced_search">Detailed Search</a>
	</div>
{/form}
{/strip}
