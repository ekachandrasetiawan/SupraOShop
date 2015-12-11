<?php
class EmailActivationEmailActivationModuleFrontController extends ModuleFrontController
{
	public $ssl = true;

	public function __construct()
	{
		parent::__construct();
		$this->context = Context::getContext();
	}

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


	public function assign()
	{	
		$key=0;
		if(isset ($_GET['key'])){
			$q = 'UPDATE  `' . _DB_PREFIX_ . 'customer` SET `active` = "1" WHERE  `secure_key` =  "' . $_GET['key'] . '";';
	        Db::getInstance()->execute($q);	
	        $key=1;
		}else{
			$key=0;
		}

		$this->context->smarty->assign('key', $key);
		$this->setTemplate('emailactivation.tpl');
	}

}
