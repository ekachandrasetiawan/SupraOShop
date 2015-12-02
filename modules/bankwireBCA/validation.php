<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/bankwireBCA.php');

$currency = new Currency(intval(isset($_POST['currency_payement']) ? $_POST['currency_payement'] : $cookie->id_currency));
$total = floatval(number_format($cart->getOrderTotal(true, 3), 2, '.', ''));
$mailVars = array(
	'{bankwire_owner}' => Configuration::get('BCA_OWNER'),
	'{bankwire_details}' => nl2br(Configuration::get('BCA_DETAILS')),
	'{bankwire_address}' => nl2br(Configuration::get('BCA_ADDRESS'))
);

$bankwire = new BankWireBCA();
$bankwire->validateOrder($cart->id, _PS_OS_BANKWIRE_, $total, $bankwire->displayName, NULL, $mailVars, $currency->id);
$order = new Order($bankwire->currentOrder);
Tools::redirectLink(__PS_BASE_URI__.'order-confirmation.php?id_cart='.$cart->id.'&id_module='.$bankwire->id.'&id_order='.$bankwire->currentOrder.'&key='.$order->secure_key);
?>