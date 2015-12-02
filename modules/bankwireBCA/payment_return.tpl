{if $status == 'ok'}
	<p>{l s='Your order on' mod='bankwireBCA'} <span class="bold">{$shop_name}</span> {l s='is complete.' mod='bankwireBCA'}
		<br /><br />
		{l s='Please send us a bank wire with:' mod='bankwireBCA'}
		<br /><br />- {l s='an amout of' mod='bankwireBCA'} <span class="price">{$total_to_pay}</span>
		<br /><br />- {l s='to the account owner of' mod='bankwireBCA'} <span class="bold">{if $bankwireOwner}{$bankwireOwner}{else}___________{/if}</span>
		<br /><br />- {l s='with theses details' mod='bankwireBCA'} <span class="bold">{if $bankwireDetails}{$bankwireDetails}{else}___________{/if}</span>
		<br /><br />- {l s='to this bank' mod='bankwireBCA'} <span class="bold">{if $bankwireAddress}{$bankwireAddress}{else}___________{/if}</span>
		<br /><br />- {l s='Do not forget to insert your order #' mod='bankwireBCA'} <span class="bold">{$id_order}</span> {l s='in the subjet of your bank wire' mod='bankwireBCA'}
		<br /><br />{l s='An e-mail has been sent to you with this information.' mod='bankwireBCA'}
		<br /><br /><span class="bold">{l s='Your order will be sent as soon as we receive your settlement.' mod='bankwireBCA'}</span>
		<br /><br />{l s='For any questions or for further information, please contact our' mod='bankwireBCA'} <a href="{$base_dir_ssl}contact-form.php">{l s='customer support' mod='bankwireBCA'}</a>.
	</p>
{else}
	<p class="warning">
		{l s='We noticed a problem with your order. If you think this is an error, you can contact our' mod='bankwireBCA'} 
		<a href="{$base_dir_ssl}contact-form.php">{l s='customer support' mod='bankwireBCA'}</a>.
	</p>
{/if}
