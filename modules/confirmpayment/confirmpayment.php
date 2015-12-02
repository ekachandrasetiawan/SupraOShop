<?php

if (!defined('_PS_VERSION_'))
	exit;

if (defined('_PS_ADMIN_DIR_') === false)
	define('_PS_ADMIN_DIR_', _PS_ROOT_DIR_.'/admin/');
require_once _PS_MODULE_DIR_.'confirmpayment/classes/TableConfirmPayment.php';

class ConfirmPayment extends Module
{
	public $html = '';
	protected $can_add_payment = true;
	public function __construct()
	{
		$this->name = 'confirmpayment';
		$this->tab = 'administration';
		$this->version = '1.0';
		$this->author = 'PT.SUPRABAKTI MANDIRI';
		$this->bootstrap = true;
		$this->need_instance = 0;
		$this->controllers = array('callback');
		$this->need_instance = true;
		$this->display = 'view';
		$this->required_database = true;
		$this->required_fields = array('newsletter','optin');

		parent::__construct();

		$this->displayName = $this->l('Confirm Payment PT.Suprabakti Mandiri');
		$this->description = $this->l('Confirm Payment PT.Suprabakti Mandiri');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function install()
	{
		return 	parent::install() &&
				$this->installDB() &&
				$this->installTab() &&
				$this->registerHook('header') &&
				$this->registerHook('customerAccount') && 
				$this->disableDevice(Context::DEVICE_TABLET | Context::DEVICE_MOBILE);
	}

	public function installDB()
	{
		$return = true;
		$return &= Db::getInstance()->execute('
				CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'confirmpayment` (
						`id_confirmpayment` int(10) unsigned NOT NULL auto_increment,
						`id_order` int(11) NOT NULL,  
						`nama_bank` varchar(100) NOT NULL,
						`no_rek` varchar(100) NOT NULL,
						`reg_account_bank` varchar(100) NOT NULL,
						`id_account_bank` int(11) NOT NULL,
						`payment` decimal(10,2) NOT NULL,  
						`payment_date` date NOT NULL,
						`state` varchar(100) NULL,
						`notes` TEXT NULL,  
				PRIMARY KEY (`id_confirmpayment`)) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;'
		);
		return $return;
	}

	public function installTab()
	{
		$tab = new Tab();
		$tab->active = 1;
		$tab->class_name = 'AdminConfirmPayment';
		foreach (Language::getLanguages(true) as $lang)
			$tab->name[$lang['id_lang']] = 'Konfirmasi Pembayaran';
		$tab->id_parent = (int)Tab::getIdFromClassName('AdminParentOrders');
		$tab->module = $this->name;
		return $tab->add();
	}


	public function uninstall()
	{
		return parent::uninstall() && $this->uninstallDB() && $this->uninstallTab();
	}


	public function uninstallDB($drop_table = true)
	{
		$ret = true;
		if($drop_table)
			$ret &=  Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'confirmpayment`');
		return $ret;
	}

	public function uninstallTab()
	{
		$id_tab = (int)Tab::getIdFromClassName('AdminConfirmPayment');
		if ($id_tab)
		{
			$tab = new Tab($id_tab);
			return $tab->delete();
		}
		else
			return false;
	}

	
	public function getContent()
	{
		$id_confirmpayment = (int)Tools::getValue('id_confirmpayment');
		if (Tools::isSubmit('saveconfirmpayment'))
		{	
			if ($this->processSaveConfirmPayment())
				return $this->html . $this->renderList();
			else
				return $this->html . $this->renderForm();
		}
		elseif (Tools::isSubmit('updateconfirmpayment') || Tools::isSubmit('addconfirmpayment'))
		{
			$this->html .= $this->renderForm();
			return $this->html;
		}
		else if (Tools::isSubmit('deleteconfirmpayment'))
		{
			$chandra = new TableConfirmPayment((int)$id_confirmpayment);
			$chandra->delete();
			$this->_clearCache('confirmpayment.tpl');
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		else
		{
			$this->html .= $this->renderList();
			return $this->html;
		}

	}

	public function hookHeader($params)
	{
		$this->context->controller->addCSS(($this->_path).'confirmpayment.css', 'all');
		$this->context->controller->addJS(($this->_path).'views/js/confirmpayment.js');
		$this->smarty->assign(array('confirmpayment_link' => $this->context->link->getModuleLink('confirmpayment', 'confirmpayment')));
	}

	public function hookCustomerAccount($params)
	{
		return $this->display(__FILE__, 'my-account.tpl');
	}



	public function processSaveConfirmPayment()
	{
		
		if ($id_confirmpayment = Tools::getValue('id_confirmpayment'))
			$chandra = new TableConfirmPayment((int)$id_confirmpayment);
		else
		{
			$chandra = new TableConfirmPayment();

		}

		$chandra->id_confirmpayment = Tools::getValue('id_confirmpayment');
		$chandra->id_order = Tools::getValue('id_order');
		$chandra->nama_bank = Tools::getValue('nama_bank');
		$chandra->no_rek = Tools::getValue('no_rek');
		$chandra->reg_account_bank = Tools::getValue('reg_account_bank');
		$chandra->id_account_bank = Tools::getValue('id_account_bank');
		$chandra->payment = Tools::getValue('payment');
		$chandra->payment_date = Tools::getValue('payment_date');
		$chandra->notes = Tools::getValue('notes');
		$chandra->state = Tools::getValue('state');

		$saved = $chandra->save();
		if ($saved)
			$this->_clearCache('confirmpayment.tpl');
		else
			$this->html .= '<div class="alert alert-danger conf error">'.$this->l('An error occurred while attempting to save.').'</div>';

        return $saved;

	
	}

	public function renderForm()
	{
		$options = array(
		  array(
		    'id_option' => 'BANK PERMATA',
		    'name' => 'BANK PERMATA'
		  ),
		  array(
		    'id_option' => 'BANK BCA',
		    'name' => 'BANK BCA'
		  ),
		);
		

		$state = array(
		  array(
		    'id_state' => 'WAITING',
		    'name' => 'WAITING'
		  ),
		  array(
		    'id_state' => 'PAID',
		    'name' => 'PAID'
		  ),
		  array(
		    'id_state' => 'POSTPONE',
		    'name' => 'POSTPONE'
		  ),
		  array(
		    'id_state' => 'CANCEL',
		    'name' => 'CANCEL'
		  ),
		);

		$sql = 'SELECT r.`id_order`,r.`reference`
			FROM `'._DB_PREFIX_.'orders` r';

		$data = Db::getInstance()->executeS($sql);

		$list = array();
		foreach ($data as $value) {
			$list[] = ['id_order'=>$value['id_order'],'reference'=>$value['reference']];
		}


		$fields_form = array(
			'tinymce' => true,
			'legend' => array(
				'title' => $this->l('Konfirmasi Pembayaran PT.Suprabakti Mandiri'),
			),
			'input' => array(
				'id_confirmpayment' => array(
					'type' => 'hidden',
					'name' => 'id_confirmpayment'
				),
				
				'id_order' =>array(
				  'type' => 'select',                              
				  'label' => $this->l('Order'),         
				  'desc' => $this->l('Choose Order'),  
				  'name' => 'id_order',                     
				  'required' => true,                              
				  'options' => array(
					    'query' => $list,                           
					    'id' => 'id_order',                           
					    'name' => 'reference'                               
				  )
				),

				'nama_bank' => array(
					'type' => 'text',
					'label' => 'Nama Bank',
					'name' => 'nama_bank',
					'required'=>true
				),
				'no_rek' => array(
					'type' => 'text',
					'label' => 'No Rekening',
					'name' => 'no_rek',
					'required'=>true
				),
				'reg_account_bank' => array(
					'type' => 'text',
					'label' => 'Nama Pengirim',
					'name' => 'reg_account_bank',
					'required'=>true
				),
				'id_account_bank' =>array(
				  'type' => 'select',                              
				  'label' => $this->l('Rekening Tujuan'),         
				  'desc' => $this->l('Choose Rekening Tujuan'),  
				  'name' => 'id_account_bank',                     
				  'required' => true,                              
				  'options' => array(
					    'query' => $options,                           
					    'id' => 'id_option',                           
					    'name' => 'name'                               
				  )
				),
				'payment' => array(
					'type' => 'text',
					'label' => 'Total Payment',
					'name' => 'payment',
					'required'=>true
				),
				'payment_date' => array(
					'type' => 'date',
					'label' => 'Payment Date',
					'name' => 'payment_date',
					'required'=>true
				),

				'content' => array(
					'type' => 'textarea',
					'label' => $this->l('Text'),
					'name' => 'notes',
					'cols' => 40,
					'rows' => 10,
					'class' => 'rte',
					'autoload_rte' => true,
				),
				'state' =>array(
				  'type' => 'select',                              
				  'label' => 'WAITING',         
				  'desc' => $this->l('Choose State Konfirmasi Pembayaran'),  
				  'name' => 'state',                     
				  'required' => true,                              
				  'options' => array(
					    'query' => $state,                           
					    'id' => 'id_state',                           
					    'name' => 'name'                               
				  )
				),
			),
			'submit' => array(
				'title' => $this->l('Save'),
			),
			'buttons' => array(
				array(
					'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
					'title' => $this->l('Back to list'),
					'icon' => 'process-icon-back'
				)
			)
		);

		$helper = new HelperForm();
		$helper->module = $this;
		$helper->name_controller = 'confirmpayment';
		$helper->identifier = $this->identifier;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$helper->toolbar_scroll = true;
		$helper->title = $this->displayName;
		$helper->submit_action = 'saveconfirmpayment';

		$helper->fields_value = $this->getFormValues();

		return $helper->generateForm(array(array('form' => $fields_form)));
	}

	public function getFormValues()
	{
		$fields_value = array();
		$id_confirmpayment = (int)Tools::getValue('id_confirmpayment');

		if ($id_confirmpayment)
		{
			$chandra = new TableConfirmPayment((int)$id_confirmpayment);
			
			$fields_value['id_order'] = $chandra->id_order;
			$fields_value['nama_bank'] = $chandra->nama_bank;
			$fields_value['no_rek'] = $chandra->no_rek;
			$fields_value['reg_account_bank'] = $chandra->reg_account_bank;
			$fields_value['id_account_bank'] = $chandra->id_account_bank;
			$fields_value['payment'] = $chandra->payment;
			$fields_value['payment_date'] = $chandra->payment_date;
			$fields_value['notes'] = $chandra->notes;
			$fields_value['state'] = $chandra->state;

		}
		else{
			$fields_value['id_order'] = Tools::getValue('id_order');
			$fields_value['nama_bank'] = Tools::getValue('nama_bank');
			$fields_value['no_rek'] = Tools::getValue('no_rek');
			$fields_value['reg_account_bank'] = Tools::getValue('reg_account_bank');
			$fields_value['id_account_bank'] = Tools::getValue('id_account_bank');
			$fields_value['payment'] = Tools::getValue('payment');
			$fields_value['payment_date'] = Tools::getValue('payment_date');
			$fields_value['notes'] = Tools::getValue('notes');
			$fields_value['state'] = Tools::getValue('state');
		}
				
		$fields_value['id_confirmpayment'] = $id_confirmpayment;

		return $fields_value;
	}

	public function renderList()
	{
		$this->fields_list = array();
		$this->fields_list['id_order'] = array(
				'title' => $this->l('order'),
				'type' => 'text',
				'search' => true,
				'orderby' => true,
			);
		$this->fields_list['nama_bank'] = array(
				'title' => $this->l('Nama Bank'),
				'type' => 'text',
				'search' => true,
				'orderby' => true,
			);
		$this->fields_list['no_rek'] = array(
				'title' => $this->l('No Rekening'),
				'type' => 'text',
				'search' => true,
				'orderby' => true,
			);
		$this->fields_list['id_account_bank'] = array(
				'title' => $this->l('Rekening Tujuan'),
				'type' => 'text',
				'search' => true,
				'orderby' => true,
			);
		$this->fields_list['payment_date'] = array(
				'title' => $this->l('Payment Date'),
				'type' => 'text',
				'search' => true,
				'orderby' => true,
			);
		$this->fields_list['state'] = array(
				'title' => $this->l('State'),
				'type' => 'text',
				'search' => true,
				'orderby' => true,
			);

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = false;
		$helper->identifier = 'id_confirmpayment';
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] = array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&add'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add new')
		);

		$helper->title = $this->displayName;
		$helper->table = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

		$content = $this->getListContent();

		return $helper->generateList($content, $this->fields_list);
	}

	public function getListContent()
	{
		$sql = 'SELECT r.`id_confirmpayment`,r.`id_order`, r.`nama_bank`, r.`no_rek`, r.`id_account_bank`, r.`payment_date`,r.`state`
			FROM `'._DB_PREFIX_.'confirmpayment` r';

		$content = Db::getInstance()->executeS($sql);
		return $content;
	}

}

?>