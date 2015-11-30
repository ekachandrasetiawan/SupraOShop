<form action="" method="post" style="max-width:600px">
    <fieldset>
        <legend>
                {l s='Automation Settings' mod='supramulticurrency'}
        </legend>
            <table border="0" class="table" cellpadding="0" cellspacing="0" id="form">
                <tr>
                    <td style="height: 35px;">
                        {l s='Update Product Prices' mod='supramulticurrency'}
                    </td>
                    <td>
                        <select name="supra_update_prices">
                            <option value="yes">
                                {l s='Yes, Update Product Prices' mod='supramulticurrency'}
                            </option> 
                            <option value="no" {if $update_prices neq 'yes'} Selected="selected" {/if}>
                                {l s='Do Not Update' mod='supramulticurrency'}
                            </option> 
                        </select>
                    </td>
                    <tr>
                        <td width="130" style="vertical-align: top;">
                            {l s='Update Currency Rates' mod='supramulticurrency'}
                        </td>
                        <td>
                            <select name="supra_update_rates">
                                <option value="yes">
                                    {l s='Yes, Update Currency Rates' mod='supramulticurrency'}
                                </option> 
                                <option value="no" {if $update_rates neq 'yes'} Selected="selected" {/if}>
                                    {l s='Do not Update Currencies (You may update manually)' mod='supramulticurrency'}
                                </option> 
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="130" style="vertical-align: top;">
                            {l s='Update Source (CronJob or Auto)' mod='supramulticurrency'}
                        </td>
                        <td>
                            <select name="supra_update_source">
                                <option value="auto">
                                    {l s='Yes, Auto (when FO visited)' mod='supramulticurrency'}
                                </option>
                                <option value="cron" {if $update_source neq 'auto'} Selected="selected" {/if}>
                                    {l s='No, Manually or CronJob (recomended)' mod='supramulticurrency'}
                                </option> 
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="130" style="vertical-align: top;">
                            {l s='Price Round Mode' mod='supramulticurrency'}
                        </td>
                        <td style="padding-bottom:15px;">
                            <select name="supra_round">
                                <option value="ceil"  {if $round eq 'ceil'} Selected="selected" {/if}>
                                    {l s='Round Up (1.99 to 2.00)' mod='supramulticurrency'}
                                </option> 
                                <option value="floor" {if $round eq 'floor'} Selected="selected" {/if}>
                                    {l s='Round Down (1.99 to 1)' mod='supramulticurrency'}
                                </option>
                                    <option value="round" {if $round eq 'round'} Selected="selected" {/if}>
                                        {l s='Round Nearest (1.51 to 2)' mod='supramulticurrency'}
                                    </option> 
                                    <option value="none"  {if $round eq 'none'} Selected="selected" {/if}>
                                        {l s='Don\'t Round' mod='supramulticurrency'}
                                    </option> 
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="130" style="vertical-align: top;">
                            {l s='Show Price At Product Page' mod='supramulticurrency'}
                        </td>
                        <td style="padding-bottom:15px;">
                            <select name="supra_show_price">
                                <option value="on">
                                    {l s='On' mod='supramulticurrency'}
                                </option>
                                <option value="off" {if $show_price neq 'on'} Selected="selected" {/if}>
                                    {l s='Off' mod='supramulticurrency'}
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="130" style="vertical-align: top;">
                            {l s='Price/Rates Update Period (If update source is auto)' mod='supramulticurrency'}
                        </td>
                        <td style="padding-bottom:15px;">
                            <select name="mlc_task_time">
                                <option value="1" {if $task_time eq '1'} Selected="selected" {/if}>
                                    {l s='Each' mod='supramulticurrency'} 1 {l s='hour' mod='supramulticurrency'}
                                </option>
                                <option value="2" {if $task_time eq '2'} Selected="selected" {/if}>
                                    {l s='Each' mod='supramulticurrency'} 2 {l s='hours' mod='supramulticurrency'}
                                </option>
                                <option value="3" {if $task_time eq '3'} Selected="selected" {/if}>
                                    {l s='Each' mod='supramulticurrency'} 3 {l s='hours' mod='supramulticurrency'}
                                </option>
                                <option value="4" {if $task_time eq '4'} Selected="selected" {/if}>
                                    {l s='Each' mod='supramulticurrency'} 4 {l s='hours' mod='supramulticurrency'}
                                </option>
                                <option value="6" {if $task_time eq '6'} Selected="selected" {/if}>
                                    {l s='Each' mod='supramulticurrency'} 6 {l s='hours' mod='supramulticurrency'}
                                </option>
                                <option value="8" {if $task_time eq '8'} Selected="selected" {/if}>
                                    {l s='Each' mod='supramulticurrency'} 8 {l s='hours' mod='supramulticurrency'}
                                </option>
                                <option value="12" {if $task_time eq '12'} Selected="selected" {/if}>
                                    {l s='Each' mod='supramulticurrency'} 12 {l s='hours' mod='supramulticurrency'}
                                </option>
                                <option value="24" {if $task_time eq '24'} Selected="selected" {/if}>
                                    {l s='Each' mod='supramulticurrency'} 24 {l s='hours' mod='supramulticurrency'}
                                </option>
                                <option value="48" {if $task_time eq '48'} Selected="selected" {/if}>
                                    {l s='Each' mod='supramulticurrency'} 48 {l s='hours' mod='supramulticurrency'}
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <input class="button" name="btnSubmit" value="{l s='Update settings' mod='supramulticurrency'}" type="submit" />
                        </td>
                    </tr>
            </table>
            <hr/>
            <br/>
                    <h3> {l s='Last Update Process' mod='supramulticurrency'} {$last_update} </h3>
                    <hr/>
                    <h3>{l s='Cron Job / Cron Task Command' mod='supramulticurrency'}</h3>
                    <p>
                    {l s='Create a cron Job (may called "scheduled task") on your server. Recommended period to use this command is 4 times in a day (1 update per 6 hours)' mod='supramulticurrency'} 
                    </p>
                    <div style="display:block; padding:5px; border:1px solid #a00; font-size:0.8em; background:#fff; color:#a00; padding:5px">{$cron}</div>

    </fieldset>
</form>