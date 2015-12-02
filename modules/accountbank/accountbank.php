<?php

if (!defined('_PS_VERSION_'))
	exit;

if (defined('_PS_ADMIN_DIR_') === false)
	define('_PS_ADMIN_DIR_', _PS_ROOT_DIR_.'/admin/');
require_once _PS_MODULE_DIR_.'accountbank/classes/TableAccountBank.php';

class AccountBank extends Module
{
	public $html = '';
	protected $can_add_account_bank = true;
	public function __construct()
	{
		$this->name = 'accountbank';
		$this->tab = 'administration';
		$this->version = '1.0';
		$this->author = 'PT.SUPRABAKTI MANDIRI';
		$this->bootstrap = true;
		$this->need_instance = 0;
		$this->need_instance = true;

		parent::__construct();

		$this->displayName = $this->l('Account Bank PT.Suprabakti Mandiri');
		$this->description = $this->l('Account Bank PT.Suprabakti Mandiri');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function install()
	{
		return 	parent::install() &&
				$this->installDB() &&
				$this->installTab() &&
				$this->registerHook('header') &&
				$this->disableDevice(Context::DEVICE_TABLET | Context::DEVICE_MOBILE);
	}

	public function installDB()
	{
		$return = true;
		$return &= Db::getInstance()->execute('
				CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'accountbank` (
						`id_accountbank` int(10) unsigned NOT NULL auto_increment,
						`nama_bank` varchar(100) NOT NULL,
						`no_rek` varchar(20) NOT NULL,
						`reg_account_name` varchar(100) NOT NULL,
						`address` varchar(150) NOT NULL,
				PRIMARY KEY (`id_accountbank`)) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;'
		);
		return $return;
	}

	public function installTab()
	{
		$tab = new Tab();
		$tab->active = 1;
		$tab->class_name = 'AdminAccountBank';
		foreach (Language::getLanguages(true) as $lang)
			$tab->name[$lang['id_lang']] = 'Account Bank';
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
			$ret &=  Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'accountbank`');
		return $ret;
	}

	public function uninstallTab()
	{
		$id_tab = (int)Tab::getIdFromClassName('AdminAccountBank');
		if ($id_tab)
		{
			$tab = new Tab($id_tab);
			return $tab->delete();
		}
		else
			return false;
	}
}

?>