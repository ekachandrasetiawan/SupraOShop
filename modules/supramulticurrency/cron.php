<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
if (!$_GET['q'] == _COOKIE_KEY_ )
    exit;
include(dirname(__FILE__).'/supramulticurrency.php');
Currency::refreshCurrencies();
$cur = new SupraMultiCurrency();
if ($cur->UpdateAll('Cron Task'))
	echo 'Success';
?>