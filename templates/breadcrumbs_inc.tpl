{if $breadcrumbs}
<ul class="breadcrumbs">
	<li class="crumb0"><a href="{$smarty.const.BITCOMMERCE_PKG_URL}">{tr}Shopping{/tr}</a></li>
	{section name="crumb" loop=$breadcrumbs}
		<li class="crumb{$smarty.section.crumb.iteration}" >
		{if $breadcrumbs[crumb].link} <a href="{$breadcrumbs[crumb].link}"> {/if}
		{$breadcrumbs[crumb].title|escape}			
		{if $breadcrumbs[crumb].link} </a> {/if}
		</li>
	{/section}
</ul>
{/if}
