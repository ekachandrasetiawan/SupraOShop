<?php

if (!defined('_PS_VERSION_'))
	exit;

if (defined('_PS_ADMIN_DIR_') === false)
	define('_PS_ADMIN_DIR_', _PS_ROOT_DIR_.'/admin/');

class EmailActivation extends Module
{
	public $html = '';
	protected $can_add_account_bank = true;
	public function __construct()
	{
		$this->name = 'emailactivation';
		$this->tab = 'administration';
		$this->version = '1.0';
		$this->author = 'PT.SUPRABAKTI MANDIRI';
		$this->bootstrap = true;
		$this->need_instance = 0;
		$this->need_instance = true;

		parent::__construct();

		$this->displayName = $this->l('Email Activation PT.Suprabakti Mandiri');
		$this->description = $this->l('Email Activation PT.Suprabakti Mandiri');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function install()
	{
		return 	parent::install();
	}

	public function uninstall()
	{
		return parent::uninstall();
	}


}

?>