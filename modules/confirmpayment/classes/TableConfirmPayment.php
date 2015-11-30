<?php

class TableConfirmPayment extends ObjectModel
{
	public $id;
	
	public $id_order;

	public $nama_bank;
	
	public $no_rek;

	public $reg_account_bank;

	public $data_rek;

	public $payment;

	public $payment_date;

	public $notes;

	public static $definition = array(
		'table' => 'confirmpayment',
		'primary' => 'id_confirmpayment',
		'fields' => array(
			'id_order' 			=>  array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'nama_bank' 		=> 	array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 50),
			'no_rek' 			=>  array('type' => self::TYPE_INT, 'size' => 16, 'required' => false),
			'reg_account_bank' 	=>  array('type' => self::TYPE_STRING, 'validate' => 'isGenericName','required' => true,),
			'data_rek' 			=>  array('type' => self::TYPE_STRING, 'validate' => 'isGenericName','required' => true,),
			'payment' 			=>	array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
			'payment_date' 		=>  array('type' => self::TYPE_DATE),
			'state' 			=>  array('type' => self::TYPE_STRING),
			'notes' 			=>  array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
		)	
	);

}

?>