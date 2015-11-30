<?php

class AdminConfirmPaymentController extends AdminController
{
    protected $delete_mode;

    protected $_defaultOrderBy = 'date_add';
    protected $_defaultOrderWay = 'DESC';
    protected $can_add_confirmpayment = true;
    protected static $meaning_status = array();

	public function __construct()
	{
		$this->bootstrap = true;
        $this->name ='confirmpayment';
        $this->table = 'confirmpayment';
        $this->className = 'ConfirmPayment';
        $this->displayName='Konfirmasi Pembayaran';
        $this->deleted = true;
        $this->explicitSelect = true;

        parent::__construct();

	}

	public function setMedia()
	{
		return parent::setMedia();
	}


	public function initToolBarTitle()
	{
		$this->toolbar_title[] = $this->l('Administration');
		$this->toolbar_title[] = $this->l('Konfirmasi Pembayaran');
	}

	public function initPageHeaderToolbar()
	{
		parent::initPageHeaderToolbar();
		unset($this->page_header_toolbar_btn['back']);

	}

 	public function initContent()
    {
    	$id_confirmpayment = (int)Tools::getValue('id_confirmpayment');
		if (Tools::isSubmit('saveconfirmpayment'))
		{	
			if ($this->processSaveConfirmPayment())
				return $this->html . $this->renderList();
			else
				return $this->html . $this->renderForm();
		}
        if ($this->action == 'select_delete') {
            $this->context->smarty->assign(array(
                'delete_form' => true,
                'url_delete' => htmlentities($_SERVER['REQUEST_URI']),
                'boxes' => $this->boxes,
            ));
        }

        if (!$this->can_add_confirmpayment && !$this->display) {
            $this->informations[] = $this->l('testing');
        }

        parent::initContent();
    }

	public function processSaveConfirmPayment()
	{
		
		if ($id_confirmpayment = Tools::getValue('id_confirmpayment'))
			$payment = new TableConfirmPayment((int)$id_confirmpayment);
		else
		{
			$payment = new TableConfirmPayment();
		}
		$payment->id_confirmpayment = Tools::getValue('id_confirmpayment');
		$payment->id_order = Tools::getValue('id_order');
		$payment->nama_bank = Tools::getValue('nama_bank');
		$payment->no_rek = Tools::getValue('no_rek');
		$payment->reg_account_bank = Tools::getValue('reg_account_bank');
		$payment->data_rek = Tools::getValue('data_rek');
		$payment->payment = Tools::getValue('payment');
		$payment->payment_date = Tools::getValue('payment_date');
		$payment->notes = Tools::getValue('notes');
		$payment->state = Tools::getValue('state');

		$saved = $payment->save();
		// if ($saved)
		// 	$this->_clearCache('confirmpayment.tpl');
		// else
		// 	$this->html .= '<div class="alert alert-danger conf error">'.$this->l('An error occurred while attempting to save.').'</div>';

        return $saved;

	
	}
    public function initToolbar()
    {
        parent::initToolbar();
        if (!$this->can_add_confirmpayment) {
            unset($this->toolbar_btn['new']);
        } elseif (!$this->display && $this->can_import) {
            $this->toolbar_btn['import'] = array(
                'href' => $this->context->link->getAdminLink('AdminImport', true).'&import_type=confirmpayment',
                'desc' => $this->l('Import')
            );
        }
    }


    public function getList($id_lang, $orderBy = null, $orderWay = null, $start = 0, $limit = null, $id_lang_shop = null)
    {
        parent::getList($id_lang, $orderBy, $orderWay, $start, $limit, $id_lang_shop);

        if ($this->_list) {
            foreach ($this->_list as &$row) {
                $row['badge_success'] = $row['total_spent'] > 0;
            }
        }
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
				'reg_account_bank' => array(
					'type' => 'text',
					'label' => 'Nama Pengirim',
					'name' => 'reg_account_bank',
					'required'=>true
				),
				'data_rek' =>array(
				  'type' => 'select',                              
				  'label' => $this->l('Rekening Tujuan'),         
				  'desc' => $this->l('Choose Rekening Tujuan'),  
				  'name' => 'data_rek',                     
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
		$helper->name_controller = 'confirmpayment';
		$helper->identifier = $this->identifier;
		$helper->token = $this->token;
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$helper->toolbar_scroll = true;
		$helper->title = $this->displayName;
		$helper->submit_action = 'saveconfirmpayment';

		$helper->fields_value = $this->getFormValues();

		return $helper->generateForm(array(array('form' => $fields_form)));

        return parent::renderForm();
    }


	public function getFormValues()
	{
		$fields_value = array();
		$id_confirmpayment = (int)Tools::getValue('id_confirmpayment');

		if ($id_confirmpayment)
		{
			$payment = new TableConfirmPayment((int)$id_confirmpayment);
			echo '<pre>';
			var_dump($payment);
			echo '</pre>';
			die();
			$fields_value['id_order'] = $payment->id_order;
			$fields_value['nama_bank'] = $payment->nama_bank;
			$fields_value['no_rek'] = $payment->no_rek;
			$fields_value['reg_account_bank'] = $payment->reg_account_bank;
			$fields_value['data_rek'] = $payment->data_rek;
			$fields_value['payment'] = $payment->payment;
			$fields_value['payment_date'] = $payment->payment_date;
			$fields_value['notes'] = $payment->notes;
			$fields_value['state'] = $payment->state;

		}
		else{
			$fields_value['id_order'] = Tools::getValue('id_order');
			$fields_value['nama_bank'] = Tools::getValue('nama_bank');
			$fields_value['no_rek'] = Tools::getValue('no_rek');
			$fields_value['reg_account_bank'] = Tools::getValue('reg_account_bank');
			$fields_value['data_rek'] = Tools::getValue('data_rek');
			$fields_value['payment'] = Tools::getValue('payment');
			$fields_value['payment_date'] = Tools::getValue('payment_date');
			$fields_value['notes'] = Tools::getValue('notes');
			$fields_value['state'] = Tools::getValue('state');
		}
		$fields_value['id_confirmpayment'] = $id_confirmpayment;

		return $fields_value;
	}



    public function processDelete()
    {
        $this->_setDeletedMode();
        parent::processDelete();
    }


    protected function _setDeletedMode()
    {
        if ($this->delete_mode == 'real') {
            $this->deleted = false;
        } elseif ($this->delete_mode == 'deleted') {
            $this->deleted = true;
        } else {
            $this->errors[] = Tools::displayError('Unknown delete mode:').' '.$this->deleted;
            return;
        }
    }


    protected function processBulkDelete()
    {
        $this->_setDeletedMode();
        parent::processBulkDelete();
    }

    public function processAdd()
    {
        if (Tools::getValue('submitFormAjax')) {
            $this->redirect_after = false;
        }
        $confirmpayment = new TableConfirmPayment();
        if ($confirmpayment->id) {
            $this->errors[] = Tools::displayError('An account already exists for this email address:').' '.$customer_email;
            $this->display = 'edit';
            return $confirmpayment;
        } elseif ($confirmpayment = parent::processAdd()) {
            $this->context->smarty->assign('new_confirmpayment', $confirmpayment);
            return $confirmpayment;
        }
        return false;
    }


	public function processUpdate()
    {
    	if (Validate::isLoadedObject($this->object)) {
            return parent::processUpdate();
        } else {
            $this->errors[] = Tools::displayError('An error occurred while loading the object.').'
				<b>'.$this->table.'</b> '.Tools::displayError('(cannot load object)');
        }
    }

    public function processSave()
    {
        $confirmpayment = new TableConfirmPayment();
        $this->errors = array_merge($this->errors, $confirmpayment->validateFieldsRequiredDatabase());

        return parent::processSave();
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
		$this->fields_list['data_rek'] = array(
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
		$helper->actions = array('edit', 'delete','view');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] = array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&add'.$this->name.'&token='.$this->token,
			'desc' => $this->l('Add new')
		);
		$helper->title = $this->displayName;
		$helper->table = $this->name;
		$helper->token = $this->token;
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

		$content = $this->getListContent();

		return $helper->generateList($content, $this->fields_list);
	}

	public function getListContent()
	{
		$sql = 'SELECT r.`id_confirmpayment`,r.`id_order`, r.`nama_bank`, r.`no_rek`, r.`data_rek`, r.`payment_date`,r.`state`
			FROM `'._DB_PREFIX_.'confirmpayment` r';

		$content = Db::getInstance()->executeS($sql);
		return $content;
	}
}

?>