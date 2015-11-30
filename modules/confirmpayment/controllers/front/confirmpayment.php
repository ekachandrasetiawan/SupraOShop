<?php
class ConfirmPaymentConfirmPaymentModuleFrontController extends ModuleFrontController
{
	public $ssl = true;

	public function __construct()
	{
		parent::__construct();
		$this->context = Context::getContext();
		// include_once($this->module->getLocalPath().'WishList.php');
	}

 	public function setMedia()
    {
        parent::setMedia();
        $this->addJqueryUi('ui.datepicker');
    }

    public function postProcess()
    {
    	if (Tools::isSubmit('submitconfirmpayment')) {
    		$confirmpayment = new TableConfirmPayment();

    		$confirmpayment->id_order = Tools::getValue('id_order');
    		$confirmpayment->nama_bank = Tools::getValue('nama_bank');
    		$confirmpayment->reg_account_bank = Tools::getValue('reg_account_bank');
    		$confirmpayment->data_rek = Tools::getValue('data_rek');
    		$confirmpayment->payment = Tools::getValue('payment');
    		$confirmpayment->payment_date = Tools::getValue('payment_date');


    		if(!$confirmpayment->id_order){
    			$this->errors[] = Tools::displayError('Pilih Order yang Telah Dibayar');
    		}elseif (!$confirmpayment->nama_bank) {
                $this->errors[] = Tools::displayError('Nama Bank tidak boleh kosong.');
            }elseif (!$confirmpayment->reg_account_bank) {
            	$this->errors[] = Tools::displayError('Atas Nama tidak boleh kosong.');
            }elseif (!$confirmpayment->data_rek) {
            	$this->errors[] = Tools::displayError('Pilih Rekening Tujuan');
            }elseif (!$confirmpayment->payment) {
            	$this->errors[] = Tools::displayError('Jumlah Pembayaran tidak boleh kosong.');
            }elseif(!Validate::isPrice($confirmpayment->payment)){
            	$this->errors[] = Tools::displayError('Jumlah Pembayaran Harus Angka.');
            }elseif (!$confirmpayment->payment_date) {
            	$this->errors[] = Tools::displayError('Tanggal Pembayaran tidak boleh kosong.');
            }else {
            	$this->context->smarty->assign('confirmation', 1);
            	$this->context->smarty->assign('alreadySent', 1);

            	$order_id = new OrderCore((int)$confirmpayment->id_order);

            	$q = 'UPDATE  `' . _DB_PREFIX_ . 'orders` SET `current_state` = "1" WHERE  `id_order` =  ' . $confirmpayment->id_order . ';';
	        	Db::getInstance()->execute($q);

            	$confirmpayment->save();
            }
    	}
    }


	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{

		parent::initContent();
		$this->context->smarty->assign(array(
            'errors' => $this->errors
        ));

		$action = Tools::getValue('action');
		if (!Tools::isSubmit('myajax'))
			$this->assign();
		elseif (!empty($action) && method_exists($this, 'ajaxProcess'.Tools::toCamelCase($action)))
			$this->{'ajaxProcess'.Tools::toCamelCase($action)}();
		else
			die(Tools::jsonEncode(array('error' => 'method doesn\'t exist')));
	}

	/**
	 * Assign wishlist template
	 */
	public function assign()
	{

		
		$id = (int)$this->context->cookie->id_customer;
		$sql = 'SELECT `id_order`,`reference`
			FROM `'._DB_PREFIX_.'orders` WHERE `id_customer`=\'' . $id . '\' AND `current_state`=10';

		$data = Db::getInstance()->executeS($sql);

		$list = array();
		foreach ($data as $value) {
			$list[] = ['id_order'=>$value['id_order'],'name'=>$value['reference']];
		}
		$this->context->smarty->assign('orders', $list);
		$this->context->smarty->assign('date', date('Y-m-d'));
		$this->setTemplate('confirmpayment.tpl');
	}

	public function ajaxProcessDeleteList()
	{
		if (!$this->context->customer->isLogged())
			die(Tools::jsonEncode(array('success' => false,
				'error' => $this->module->l('You aren\'t logged in', 'mywishlist'))));

		$default = Tools::getIsset('default');
		$default = (empty($default) === false ? 1 : 0);
		$id_wishlist = Tools::getValue('id_wishlist');

		$wishlist = new WishList((int)$id_wishlist);
		if (Validate::isLoadedObject($wishlist) && $wishlist->id_customer == $this->context->customer->id)
		{
			$default_change = $wishlist->default ? true : false;
			$id_customer = $wishlist->id_customer;
			$wishlist->delete();
		}
		else
			die(Tools::jsonEncode(array('success' => false,
				'error' => $this->module->l('Cannot delete this wishlist', 'mywishlist'))));

		if ($default_change)
		{
			$array = WishList::getDefault($id_customer);

			if (count($array))
				die(Tools::jsonEncode(array(
					'success' => true,
					'id_default' => $array[0]['id_wishlist']
					)));
		}

		die(Tools::jsonEncode(array('success' => true)));
	}

	public function ajaxProcessSetDefault()
	{
		if (!$this->context->customer->isLogged())
			die(Tools::jsonEncode(array('success' => false,
				'error' => $this->module->l('You aren\'t logged in', 'mywishlist'))));

		$default = Tools::getIsset('default');
		$default = (empty($default) === false ? 1 : 0);
		$id_wishlist = Tools::getValue('id_wishlist');

		if ($default)
		{
			$wishlist = new WishList((int)$id_wishlist);
			if (Validate::isLoadedObject($wishlist) && $wishlist->id_customer == $this->context->customer->id && $wishlist->setDefault())
				die(Tools::jsonEncode(array('success' => true)));
		}

		die(Tools::jsonEncode(array('error' => true)));
	}

}
