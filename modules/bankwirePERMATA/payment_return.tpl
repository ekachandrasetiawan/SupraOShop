{if $status == 'ok'}
	<p>{l s='Your order on' mod='bankwirePERMATA'} <span class="bold">{$shop_name}</span> {l s='is complete.' mod='bankwirePERMATA'}
		<br /><br />
		{l s='Please send us a bank wire with:' mod='bankwirePERMATA'}
		<br /><br />- {l s='an amout of' mod='bankwirePERMATA'} <span class="price">{$total_to_pay}</span>
		<br /><br />- {l s='to the account owner of' mod='bankwirePERMATA'} <span class="bold">{if $bankwireOwner}{$bankwireOwner}{else}___________{/if}</span>
		<br /><br />- {l s='with theses details' mod='bankwirePERMATA'} <span class="bold">{if $bankwireDetails}{$bankwireDetails}{else}___________{/if}</span>
		<br /><br />- {l s='to this bank' mod='bankwirePERMATA'} <span class="bold">{if $bankwireAddress}{$bankwireAddress}{else}___________{/if}</span>
		<br /><br />- {l s='Do not forget to insert your order #' mod='bankwirePERMATA'} <span class="bold">{$id_order}</span> {l s='in the subjet of your bank wire' mod='bankwirePERMATA'}
		<br /><br />{l s='An e-mail has been sent to you with this information.' mod='bankwirePERMATA'}
		<br /><br /><span class="bold">{l s='Your order will be sent as soon as we receive your settlement.' mod='bankwirePERMATA'}</span>
		<br /><br />{l s='For any questions or for further information, please contact our' mod='bankwirePERMATA'} <a href="{$base_dir_ssl}contact-form.php">{l s='customer support' mod='bankwirePERMATA'}</a>.
	</p>
{else}
	<p class="warning">
		{l s='We noticed a problem with your order. If you think this is an error, you can contact our' mod='bankwirePERMATA'} 
		<a href="{$base_dir_ssl}contact-form.php">{l s='customer support' mod='bankwirePERMATA'}</a>.
	</p>
{/if}
