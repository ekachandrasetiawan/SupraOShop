<?php

class AdminAccountBankController extends ModuleAdminController
{
	public $html = '';
	public function __construct()
	{
		$this->bootstrap = true;
		$this->table = 'accountbank';
		$this->required_database = true;
		$this->className = 'TableAccountBank';
		$this->name ='accountbank';
		$this->displayName='Account Bank';
		$this->addRowAction('edit');
		$this->addRowAction('delete');
		$this->addRowAction('view');

		$this->context = Context::getContext();

		if (!Tools::getValue('realedit')) {
			$this->deleted = false;
		}
		$this->bulk_actions = array(
			'delete' => array(
				'text' => $this->l('Delete selected'),
				'confirm' => $this->l('Delete selected items?'),
				'icon' => 'icon-trash'
			)
		);
		parent::__construct();
	}


	public function initPageHeaderToolbar()
	{
		if (empty($this->display)) {
		    $this->page_header_toolbar_btn['new_accountbank'] = array(
		        'href' => self::$currentIndex.'&addaccountbank&token='.$this->token,
		        'desc' => $this->l('Add new Account Bank', null, null, false),
		        'icon' => 'process-icon-new'
		    );
		}
		parent::initPageHeaderToolbar();
	}

	public function renderList()
	{
		
		$this->fields_list = array();
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
		$this->fields_list['reg_account_name'] = array(
				'title' => $this->l('Account Name'),
				'type' => 'text',
				'search' => true,
				'orderby' => true,
			);

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = false;
		$helper->identifier = 'id_accountbank';
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] = array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&add'.$this->name.'&token='.$this->token,
			'desc' => $this->l('Add new')
		);
		$helper->title = 'DATA ACCOUNT BANK';
		$helper->table = $this->name;
		$helper->token = $this->token;
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$content = $this->getListContent();
		return $helper->generateList($content, $this->fields_list);
	}

	public function getListContent()
	{
		$sql = 'SELECT r.`id_accountbank`,r.`nama_bank`, r.`no_rek`, r.`reg_account_name`, r.`address`
			FROM `'._DB_PREFIX_.'accountbank` r';
		$content = Db::getInstance()->executeS($sql);
		return $content;
	}


	public function renderForm()
	{
		if (!($obj = $this->loadObject(true))) {
			return;
		}
		$id_accountbank = (int)Tools::getValue('id_accountbank');

		$fields_form = array(
			'tinymce' => true,
			'legend' => array(
				'title' => $this->l('Konfirmasi Pembayaran PT.Suprabakti Mandiri'),
			),
			'input' => array(
				'id_accountbank' => array(
					'type' => 'hidden',
					'name' => 'id_accountbank'
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
				'reg_account_name' => array(
					'type' => 'text',
					'label' => 'Account Name',
					'name' => 'reg_account_name',
					'required'=>true
				),
				'address' => array(
					'type' => 'text',
					'label' => 'Address',
					'name' => 'address',
					'required'=>true
				),
			),
			'submit' => array(
				'title' => $this->l('Save'),
			),
			'buttons' => array(
				array(
					'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.$this->token,
					'title' => $this->l('Back to list'),
					'icon' => 'process-icon-back'
				)
			)
		);

		$this->fields_form['submit'] = array(
			'title' => $this->l('Save'),
		);

		$helper = new HelperForm();
		$helper->module = $this;
		$helper->name_controller = 'accountbank';
		$helper->identifier = $this->identifier;
		$helper->token = $this->token;
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$helper->toolbar_scroll = true;
		$helper->title = $this->displayName;
		$helper->submit_action = 'submitAddaccountbank';

		$helper->fields_value = $this->getFormValues();

		return $helper->generateForm(array(array('form' => $fields_form)));

		return parent::renderForm();
	}


	public function getFormValues()
	{
		$fields_value = array();
		$id_accountbank = (int)Tools::getValue('id_accountbank');

		if ($id_accountbank)
		{
			$accountbank = new TableAccountBank((int)$id_accountbank);

			$fields_value['nama_bank'] = $accountbank->nama_bank;
			$fields_value['no_rek'] = $accountbank->no_rek;
			$fields_value['reg_account_name'] = $accountbank->reg_account_name;
			$fields_value['address'] = $accountbank->address;
		}
		else{
			$fields_value['nama_bank'] = Tools::getValue('nama_bank');
			$fields_value['no_rek'] = Tools::getValue('no_rek');
			$fields_value['reg_account_name'] = Tools::getValue('reg_account_name');
			$fields_value['address'] = Tools::getValue('address');
		}
		$fields_value['id_accountbank'] = $id_accountbank;

		return $fields_value;
	}

}

?>