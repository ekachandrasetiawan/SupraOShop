  {capture name=path}
        <a href="{$link->getPageLink('my-account', true)|escape:'html'}">{l s='Login' mod='emailactivation'}</a>
        <span class="navigation-pipe">{$navigationPipe}</span>
        Aktivasi Email
  {/capture}

 <p class="alert alert-success">
 		{if ($key==1)}
			{l s='Registration successful, Account Anda Telah Active..'}
		{else}
  			{l s='Registration successful, please activate email..'}
  		{/if}
 </p>