<?php

class TableAccountBank extends ObjectModel
{
	public $id;

	public $nama_bank;
	
	public $no_rek;

	public $reg_account_name;

	public $address;

	public static $definition = array(
		'table' => 'accountbank',
		'primary' => 'id_accountbank',
		'fields' => array(
			'nama_bank' 		=> 	array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 50),
			'no_rek' 			=>  array('type' => self::TYPE_INT, 'size' => 20, 'required' => false),
			'reg_account_name' 	=>  array('type' => self::TYPE_STRING, 'validate' => 'isGenericName','required' => false),
			'address' 			=>  array('type' => self::TYPE_STRING, 'validate' => 'isGenericName','required' => false),
		)	
	);
}

?>