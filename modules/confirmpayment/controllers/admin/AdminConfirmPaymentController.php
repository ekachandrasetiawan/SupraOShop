<?php

class AdminConfirmPaymentController extends ModuleAdminController
{
	public $html = '';
    public function __construct()
    {
    	$this->bootstrap = true;
    	$this->table = 'confirmpayment';
    	$this->className = 'TableConfirmPayment';
    	$this->name ='confirmpayment';
        $this->table = 'confirmpayment';
        $this->displayName='Konfirmasi Pembayaran';
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
            $this->page_header_toolbar_btn['new_confirmpayment'] = array(
                'href' => self::$currentIndex.'&addconfirmpayment&token='.$this->token,
                'desc' => $this->l('Add new ConfirmPayment', null, null, false),
                'icon' => 'process-icon-new'
            );
        }
        else if($this->display=='view') {

        	$id_confirmpayment = (int)Tools::getValue('id_confirmpayment');
        	$payment = new TableConfirmPayment((int)$id_confirmpayment);
        	
        	$this->page_header_toolbar_btn['new_confirmpayment'] = array(
                'href' => 'index.php?controller=AdminOrders&id_order='.$payment->id_order.'&vieworder&token='.Tools::getAdminTokenLite('AdminOrders'),
                'desc' => $this->l('View Order', null, null, false),
                'icon' => 'process-icon-preview'
            );
        }
        parent::initPageHeaderToolbar();
    }
 	public function renderView()
    {	
    	if (!($customer = $this->loadObject())) {
            return;
        }
    	$id_confirmpayment = (int)Tools::getValue('id_confirmpayment');
    	$payment = new TableConfirmPayment((int)$id_confirmpayment);
    	$this->base_tpl_view = 'view.tpl';
    	$this->tpl_view_vars = array(
    			'id_order'=>$payment->id_order
    		);
    	
    	 return parent::renderView();
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


	public function renderForm()
    {

    	if (!($obj = $this->loadObject(true))) {
            return;
        }

        $id_confirmpayment = (int)Tools::getValue('id_confirmpayment');

		if ($id_confirmpayment)
		{
			$payment = new TableConfirmPayment((int)$id_confirmpayment);

			if($payment->state=='WAITING'){
				$status = ['WAITING','PAID','POSTPONE','CANCEL'];	
			}else if($payment->state=='PAID'){
				$status = ['PAID','WAITING','POSTPONE','CANCEL'];	
			}else if($payment->state=='POSTPONE'){
				$status = ['POSTPONE','PAID','WAITING','CANCEL'];
			}else if($payment->state=='CANCEL'){
				$status = ['CANCEL','POSTPONE','PAID','WAITING'];
			}

			if($payment->data_rek=='BANK PERMATA'){
				$databank = ['BANK PERMATA','BANK BCA'];	
			}else{
				$databank = ['BANK BCA','BANK PERMATA'];	
			}

			$order = new OrderCore((int)$payment->id_order);

			
			$list = array();
			$list[] = ['id_order'=>$payment->id_order,'reference'=>$order->reference];
		}
		else
		{
			$databank = ['BANK PERMATA','BANK BCA'];
			$status = ['WAITING','PAID','POSTPONE','CANCEL'];

			$sql = 'SELECT r.`id_order`,r.`reference`
			FROM `'._DB_PREFIX_.'orders` r WHERE current_state=10';
			$data = Db::getInstance()->executeS($sql);

			$list = array();
			foreach ($data as $value) {
				$list[] = ['id_order'=>$value['id_order'],'reference'=>$value['reference']];
			}
		}

		$state = array();
		foreach ($status as $state_data) {
			$state[] = ['id_state'=>$state_data,'name'=>$state_data];
		}

		$bank = array();
		foreach ($databank as $bank_data) {
			$bank[] = ['id_option'=>$bank_data,'name'=>$bank_data];
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
					    'query' => $bank,                           
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
		$helper->submit_action = 'submitAddconfirmpayment';

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

}

?>