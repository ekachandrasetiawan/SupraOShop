<?php

/* SSL Management */
$useSSL = true;

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/bankwirePERMATA.php');

if (!$cookie->isLogged())
    Tools::redirect('authentication.php?back=order.php');
$bankwire = new BankWirePERMATA();
echo $bankwire->execPayment($cart);

include_once(dirname(__FILE__).'/../../footer.php');

?>