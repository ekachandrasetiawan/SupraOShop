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
    	// $this->addRowAction('edit');
     //    $this->addRowAction('delete');
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
        // if (empty($this->display)) {
        //     $this->page_header_toolbar_btn['new_confirmpayment'] = array(
        //         'href' => self::$currentIndex.'&addconfirmpayment&token='.$this->token,
        //         'desc' => $this->l('Add new ConfirmPayment', null, null, false),
        //         'icon' => 'process-icon-new'
        //     );
        // }
        // else if($this->display=='view') {
        // 	$id_confirmpayment = (int)Tools::getValue('id_confirmpayment');
        // 	$payment = new TableConfirmPayment((int)$id_confirmpayment);
        	
        // 	$this->page_header_toolbar_btn['new_confirmpayment'] = array(
        //         'href' => 'index.php?controller=AdminOrders&id_order='.$payment->id_order.'&vieworder&token='.Tools::getAdminTokenLite('AdminOrders'),
        //         'desc' => $this->l('View Order', null, null, false),
        //         'icon' => 'process-icon-preview'
        //     );
        // }
        // else 
        if($this->display=='view') {

        	$id_confirmpayment = (int)Tools::getValue('id_confirmpayment');
        	$payment = new TableConfirmPayment((int)$id_confirmpayment);
        	if($payment->state=='WAITING'){
        		$this->page_header_toolbar_btn['paid'] = array(
	                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&id_confirmpayment='.$id_confirmpayment.'&paid'.$this->name.'&token='.$this->token,
	                'desc' => $this->l('PAYMENT DONE', null, null, false),
	                'icon' => 'process-icon-payment'
            	);

            	$this->page_header_toolbar_btn['cancel'] = array(
	                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&id_confirmpayment='.$id_confirmpayment.'&cancel'.$this->name.'&token='.$this->token,
	                'desc' => $this->l('PAYMENT CANCEL', null, null, false),
	                'icon' => 'process-icon-cancel'
            	);

            	$this->page_header_toolbar_btn['postpone'] = array(
	                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&id_confirmpayment='.$id_confirmpayment.'&postpone'.$this->name.'&token='.$this->token,
	                'desc' => $this->l('PAYMENT POSTPONE', null, null, false),
	                'icon' => 'process-icon-dropdown'
            	);
        	}else if($payment->state=='PAID'){
        		$this->page_header_toolbar_btn['waiting'] = array(
	                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&id_confirmpayment='.$id_confirmpayment.'&waiting'.$this->name.'&token='.$this->token,
	                'desc' => $this->l('PAYMENT WAITING', null, null, false),
	                'icon' => 'process-icon-reset'
            	);

        		$this->page_header_toolbar_btn['cancel'] = array(
	                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&id_confirmpayment='.$id_confirmpayment.'&cancel'.$this->name.'&token='.$this->token,
	                'desc' => $this->l('PAYMENT CANCEL', null, null, false),
	                'icon' => 'process-icon-cancel'
            	);

            	$this->page_header_toolbar_btn['postpone'] = array(
	                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&id_confirmpayment='.$id_confirmpayment.'&postpone'.$this->name.'&token='.$this->token,
	                'desc' => $this->l('PAYMENT POSTPONE', null, null, false),
	                'icon' => 'process-icon-dropdown'
            	);
            	$this->page_header_toolbar_btn['new_confirmpayment'] = array(
	                'href' => 'index.php?controller=AdminOrders&id_order='.$payment->id_order.'&vieworder&token='.Tools::getAdminTokenLite('AdminOrders'),
	                'desc' => $this->l('View Order', null, null, false),
	                'icon' => 'process-icon-preview'
            	);
        	}
        	else if($payment->state=='POSTPONE'){
      			$this->page_header_toolbar_btn['paid'] = array(
	                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&id_confirmpayment='.$id_confirmpayment.'&paid'.$this->name.'&token='.$this->token,
	                'desc' => $this->l('PAYMENT DONE', null, null, false),
	                'icon' => 'process-icon-payment'
            	);

        		$this->page_header_toolbar_btn['cancel'] = array(
	                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&id_confirmpayment='.$id_confirmpayment.'&cancel'.$this->name.'&token='.$this->token,
	                'desc' => $this->l('PAYMENT CANCEL', null, null, false),
	                'icon' => 'process-icon-cancel'
            	);
        	}
        }
        parent::initPageHeaderToolbar();
    }

	
	public function initContent()
	{
		$id_confirmpayment = (int)Tools::getValue('id_confirmpayment');
        $payment = new TableConfirmPayment((int)$id_confirmpayment);
		if (Tools::isSubmit('paidconfirmpayment'))
		{	
			// Update State Kondirmasi Pembayaran
			Db::getInstance()->update('confirmpayment', array('state' => 'PAID'), 'id_confirmpayment = '.(int)$id_confirmpayment);
			// Update Status Order
			Db::getInstance()->update('orders', array('current_state' => '2'), 'id_order = '.(int)$payment->id_order);

			// Add Order History State
			Db::getInstance()->execute('INSERT INTO  `' . _DB_PREFIX_ . 'order_history` (
	        																	`id_employee` ,
	        																	`id_order` ,
	        																	`id_order_state` ,
	        																	`date_add`) 
	        																	VALUES (
	        																	'.(int)Tools::getValue('id_employee').',
	        																	'.$payment->id_order.',
	        																	2,
	        																	"'.Date("Y-m-d h:i:s").'"
	        																	);');
			// Redirect Link Admin 
			$link = $this->redirect_after = AdminController::$currentIndex.'&configure='.$this->name.'&id_confirmpayment='.$id_confirmpayment.'&view'.$this->name.'&token='.$this->token;
			Tools::redirectAdmin($link);

		}else if(Tools::isSubmit('cancelconfirmpayment'))
		{
			Db::getInstance()->update('confirmpayment', array('state' => 'CANCEL'), 'id_confirmpayment = '.(int)$id_confirmpayment);
			
			// Redirect Link Admin 
			$link = $this->redirect_after = AdminController::$currentIndex.'&configure='.$this->name.'&id_confirmpayment='.$id_confirmpayment.'&view'.$this->name.'&token='.$this->token;
			Tools::redirectAdmin($link);
		}
		else if(Tools::isSubmit('postponeconfirmpayment'))
		{
			Db::getInstance()->update('confirmpayment', array('state' => 'POSTPONE'), 'id_confirmpayment = '.(int)$id_confirmpayment);
			
			// Redirect Link Admin 
			$link = $this->redirect_after = AdminController::$currentIndex.'&configure='.$this->name.'&id_confirmpayment='.$id_confirmpayment.'&view'.$this->name.'&token='.$this->token;
			Tools::redirectAdmin($link);
		}
		else if(Tools::isSubmit('waitingconfirmpayment'))
		{
			// Update Status Konfirmasi Pembayaran
			Db::getInstance()->update('confirmpayment', array('state' => 'WAITING'), 'id_confirmpayment = '.(int)$id_confirmpayment);
			// Update Status Order
			Db::getInstance()->update('orders', array('current_state' => '1'), 'id_order = '.(int)$payment->id_order);

			// Add Order History State
			Db::getInstance()->execute('INSERT INTO  `' . _DB_PREFIX_ . 'order_history` (
	        																	`id_employee` ,
	        																	`id_order` ,
	        																	`id_order_state` ,
	        																	`date_add`) 
	        																	VALUES (
	        																	'.(int)Tools::getValue('id_employee').',
	        																	'.$payment->id_order.',
	        																	1,
	        																	"'.Date("Y-m-d h:i:s").'"
	        																	);');
			// Redirect Link Admin 
			$link = $this->redirect_after = AdminController::$currentIndex.'&configure='.$this->name.'&id_confirmpayment='.$id_confirmpayment.'&view'.$this->name.'&token='.$this->token;
			Tools::redirectAdmin($link);
		}
		parent::initContent();
	}
	public function renderView()
	{	
		$id_confirmpayment = (int)Tools::getValue('id_confirmpayment');
		$payment = new TableConfirmPayment((int)$id_confirmpayment);

		$order = new OrderCore((int)$payment->id_order);
		$customer = new Customer((int)$order->id_customer);
		$accountbank = new TableAccountBank((int)$payment->id_account_bank);

		if($customer->id_gender=='1'){
			$gender ='Tuan';
		}else{
			$gender ='Ny';
		}

		// Data Konfirmasi Pembayaran
		$this->tpl_vars = array(
			'gender' => $gender,
			'firstname' => $customer->firstname,
			'lastname' => $customer->lastname,
			'email' => $customer->email,
            'order' => $order->reference,
            'nama_bank' => $payment->nama_bank,
            'pemilik_rek' => $payment->reg_account_bank,
            'rekening_tujuan'=>$accountbank->nama_bank.' ['.$accountbank->no_rek.' ]',
            'payment'=>Tools::displayPrice($payment->payment, $this->context->currency->id),
            'payment_date'=>$payment->payment_date,
            'state'=>$payment->state,
            'notes'=>$payment->notes,
        );

		$tpl = $this->context->smarty->createTemplate(dirname(__FILE__).'/../../views/templates/admin/confirmpayment/helpers/view/view_bt.tpl');
		$tpl->assign($this->tpl_vars);
		return $tpl->fetch();
	}

	public function renderList()
	{
		
		$this->fields_list = array();
		$this->fields_list['nameorder'] = array(
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
		$this->fields_list['namabank'] = array(
				'title' => $this->l('Rekening Tujuan'),
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
		$helper->actions = array('view');
		// $helper->actions = array('edit', 'delete','view');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		// $helper->toolbar_btn['new'] = array(
		// 	'href' => AdminController::$currentIndex.'&configure='.$this->name.'&add'.$this->name.'&token='.$this->token,
		// 	'desc' => $this->l('Add new')
		// );
		$helper->title = 'DATA KONFIRMASI PEMBAYARAN';
		$helper->table = $this->name;
		$helper->token = $this->token;
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$content = $this->getListContent();
		return $helper->generateList($content, $this->fields_list);
	}

	public function getListContent()
	{
		$sql = 'SELECT 
					r.`id_confirmpayment`,
					o.`reference` nameorder, 
					r.`nama_bank`, 
					r.`no_rek`, 
					ab.`nama_bank` namabank, 
					ab.`no_rek` no_rek, 
					r.`payment_date`,
					r.`state`
			FROM `'._DB_PREFIX_.'confirmpayment` r
			LEFT JOIN `'._DB_PREFIX_.'orders` o
				ON (o.id_order = r.id_order)
			LEFT JOIN `'._DB_PREFIX_.'accountbank` ab
				ON (ab.id_accountbank = r.id_account_bank)';
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

			$accountbank = new TableAccountBank((int)$payment->id_accountbank);

			$order = new OrderCore((int)$payment->id_order);

			
			$bank = array();
			$bank[] = ['id_option'=>$payment->id_accountbank,'name'=>$accountbank->nama_bank.' ['.$accountbank->no_rek.' ]'];


			$list = array();
			$list[] = ['id_order'=>$payment->id_order,'reference'=>$order->reference];
		}
		else
		{
			$payment = new TableConfirmPayment((int)$id_confirmpayment);
			$status = ['WAITING','PAID','POSTPONE','CANCEL'];

			$sql = 'SELECT r.`id_order`,r.`reference`
			FROM `'._DB_PREFIX_.'orders` r WHERE current_state=10';
			$data = Db::getInstance()->executeS($sql);


			$list = array();
			foreach ($data as $value) {
				$list[] = ['id_order'=>$value['id_order'],'reference'=>$value['reference']];
			}

			$sqlbank = 'SELECT a.`id_accountbank`,a.`nama_bank`,a.`no_rek`
			FROM `'._DB_PREFIX_.'accountbank` a';
			$databank = Db::getInstance()->executeS($sqlbank);

			
			$bank = array();
			foreach ($databank as $val) {
				$bank[] = ['id_option'=>$val['id_accountbank'],'name'=>$val['nama_bank'].' ['.$val['no_rek'].' ]'];
			}
		}

		$state = array();
		foreach ($status as $state_data) {
			$state[] = ['id_state'=>$state_data,'name'=>$state_data];
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
					'required'=>true,
					// 'readonly'=>'readonly'
				),
				'reg_account_bank' => array(
					'type' => 'text',
					'label' => 'Nama Pengirim',
					'name' => 'reg_account_bank',
					'required'=>true,
					// 'readonly'=>'readonly'
				),
				'id_account_bank' =>array(
				  'type' => 'select',                              
				  'label' => $this->l('Rekening Tujuan'),         
				  'desc' => $this->l('Choose Rekening Tujuan'),  
				  'name' => 'id_account_bank',                     
				  'required' => true,
				  'readonly'=>'readonly',                             
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
					'required'=>true,
					// 'readonly'=>'readonly'
				),
				'payment_date' => array(
					'type' => 'date',
					'label' => 'Payment Date',
					'name' => 'payment_date',
					'required'=>true,
					// 'readonly'=>'readonly'
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
				  // 'readonly'=>'readonly',                        
				  'options' => array(
					    'query' => $state,                           
					    'id' => 'id_state',                           
					    'name' => 'name',
					    'readonly'=>'readonly'

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
			$fields_value['id_account_bank'] = $payment->id_account_bank;
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
			$fields_value['id_account_bank'] = Tools::getValue('id_account_bank');
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