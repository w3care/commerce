{strip}
{if $gBitProduct->isValid() && $gBitUser->isRegistered()}
	{bitmodule title=$moduleTitle name="bc_notifications"}
		{if $notificationExists}
			<a href="{$smarty.const.HTTP_SERVER}{$smarty.const.BITCOMMERCE_PKG_URL}index.php?main_page={$smarty.request.main_page}&amp;action=notify_remove&amp;products_id={$gBitProduct->mProductsId}">{booticon iname="icon-envelope"  ipackage="icons"  iexplain="Send Mail"}{tr}Do not notify me of updates to{/tr} {$gBitProduct->getTitle()}</a>
		{else}
			<a href="{$smarty.const.HTTP_SERVER}{$smarty.const.BITCOMMERCE_PKG_URL}index.php?main_page={$smarty.request.main_page}&amp;action=notify&amp;products_id={$gBitProduct->mProductsId}">{booticon iname="icon-envelope"  ipackage="icons"  iexplain="Send Mail"}{tr}Notify me of updates to{/tr} {$gBitProduct->getTitle()}</a>
		{/if}
	{/bitmodule}
{/if}
{/strip}
