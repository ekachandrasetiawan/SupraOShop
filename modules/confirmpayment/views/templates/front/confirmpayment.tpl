<div id="confirmpayment">
    {capture name=path}
        <a href="{$link->getPageLink('my-account', true)|escape:'html'}">{l s='My account' mod='confirmpayment'}</a>
        <span class="navigation-pipe">{$navigationPipe}</span>
        Konfirmasi Pembayaran
    {/capture}

<div id="mywishlist">
		
	<h1 class="page-heading">Konfirmasi Pembayaran</h1>
	
		{if isset($confirmation)}
			<p class="alert alert-success">{l s='Konfirmasi Pemesanan Berhasil Dikirim.'}</p>
		{elseif isset($alreadySent)}
			<!-- <p class="alert alert-warning">{l s='lakukan Konfimrasi Pemesanan'}</p> -->
			<p class="alert alert-success">{l s='Konfirmasi Pemesanan Berhasil Dikirim.'}</p>
		{else}
		{/if}
			{include file="$tpl_dir./errors.tpl"}
			<form id="form_wishlist" class="std box" method="post"  action="{$request_uri}">
			<fieldset>
				<h3 class="page-subheading">Konfirmasi Pembayaran</h3>
				<div class="form-group">
					<input type="hidden" value="" name="token">
					<label for="name" class="align_right">
						<div class="required">*</div> Order No
					</label>
					<select id="id_order" class="form-control inputTxt" name="id_order">
							<option value="">Select Order No</option>
							{foreach from=$orders item=order}
								<option value="{$order.id_order|intval}"{if isset($smarty.request.id_order) && $smarty.request.id_order == $order.id_order} selected="selected"{/if}>{$order.name|escape:'html':'UTF-8'}</option>
							{/foreach}
					</select>

					<label for="name" class="align_right">
						<div class="required">*</div>Nama Bank
					</label>
					<input type="text" value="" class="inputTxt form-control" name="nama_bank" id="nama_bank">

					<label for="name" class="align_right">
						<div class="required">*</div> Atas Nama
					</label>
					<input type="text" value="" class="inputTxt form-control" name="reg_account_bank" id="reg_account_bank">

					<label for="name" class="align_right">
						<div class="required">*</div> Rekening Tujuan
					</label>
					<select name="id_account_bank" class="inputTxt form-control">
						<option value="">Select Rekening Tujuan</option>
						{foreach from=$bank item=val}
						<option value="{$val.id_account_bank|intval}"{if isset($smarty.request.id_account_bank) && $smarty.request.id_account_bank == $val.id_account_bank} selected="selected"{/if}>{$val.name|escape:'html':'UTF-8'}</option>
						{/foreach}
					</select>

					<label for="name" class="align_right">
						<div class="required">*</div>  Jumlah Pembayaran
					</label>
					<input type="text" value="" class="inputTxt form-control" name="payment" id="payment">

					<label for="name" class="align_right">
						<div class="required">*</div>  Tanggal Pembayaran
					</label>
					<input type="text" value="{$date}" class="inputTxt form-control" name="payment_date" id="datepicker">
				</div>
				<p class="submit">
                    <button name="submitconfirmpayment" type="submit" class="btn btn-default button button-medium" id="submitconfirmpayment">
                    	<span>Confirm Payment<i class="icon-chevron-right right"></i></span>
                    </button>
				</p>
			</fieldset>
		</form>
		
</div>

</div>