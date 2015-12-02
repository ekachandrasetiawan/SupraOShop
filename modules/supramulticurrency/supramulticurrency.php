<?php
	if (!defined('_PS_VERSION_'))
		exit;

	class SupraMultiCurrency extends module{
		private $_html = '';

		public function __construct(){
			$this->name = 'supramulticurrency';
			$this->tab = 'front_office_features';
			$this->version = 1.0;
			$this->author = 'PT.SUPRABAKTI MANDIRI';
			parent::__construct();
			$this->_errors = array();
			$this->page= basename(__FILE__, '.php');
			$this->displayName = $this->l('Supra Multi Currency Product');
    		$this->description = $this->l('Supra Multi Currency Product');
    		$this->confirmUninstall = $this->l('Apakah Anda Akan Mendelete Module ini ?');
    		$config = Configuration::getMultiple(array(
		    				'MLC_TASK_TIME', 
		    				'MLC_UPDATE_PRICES', 
		    				'MLC_UPDATE_RATES', 
		    				'MLC_LAST_UPDATE', 
		    				'MLC_ROUND_MODE', 
		    				'MLC_UPDATE_SOURCE', 
		    				'MLC_SHOW_PRODUCT_PAGE', 
		    				'MLC_SHOW_PRICE'));
	        $this->task_time = (int)$config['MLC_TASK_TIME'] * 3600;
	        $this->update_source = $config['MLC_UPDATE_SOURCE'];
	        $this->update_prices = $config['MLC_UPDATE_PRICES'];
	        $this->update_rates = $config['MLC_UPDATE_RATES'];
	        $this->round = $config['MLC_ROUND_MODE'];
	        $this->last_update = $config['MLC_LAST_UPDATE'];
	        $this->show_price = $config['MLC_SHOW_PRICE'];
		}

		public function install() {
			if (!parent::install() OR 
				!$this->registerHook('actionProductAdd') OR 
				!$this->registerHook('actionProductDelete') OR 
				!$this->registerHook('actionProductSave') OR 
				!$this->registerHook('actionProductUpdate') OR 
				!$this->registerHook('displayRightColumnProduct') OR 
				!$this->registerHook('displayFooter') OR 
				!$this->registerHook('displayBackOfficeHeader')) return false;
			if (!Db::getInstance()->Execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'supramulticurrency_product` (  
												`id_product` int(11) unsigned NOT NULL,  
												`id_currency` int(11) NOT NULL,  
												`product_price` decimal(10,2) NOT NULL,  
												`product_sale_price` decimal(10,2) NOT NULL,  
												`product_detail_prices` TEXT NULL,  
												PRIMARY KEY (`id_product`)) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8')) return false;
			return true;
			return true;
		}

		public function uninstall() {
			return parent::uninstall();
		}

		public function hookActionProductUpdate($params){
			if(isset(
				$_GET['id_product']) AND 
				$_GET['id_product'] != '')
				$params['id_product'] = (int)$_GET['id_product'];
			if(
			  !isset($_POST['sbm_id_currency'])OR 
			  !isset($_POST['sbm_product_price'])OR 
			  !isset($_POST['sbm_product_sale_price'])OR 
			  (int)$_POST['sbm_product_price'] == 0) return false;
			$sbm_product = array( 
						'id_product' => $params['id_product'], 
						'id_currency' => $_POST['sbm_id_currency'], 
						'product_price'=> $_POST['sbm_price'], 
						'product_sale_price' => $_POST['sbm_product_sale_price'], 
						'product_detail_prices' => array(),);
			$this->SaveMultiCurrencyProduct($sbm_product);
			$this->UpdateProductPrice($params['id_product']);
	        unset($_POST['sbm_id_currency']);
	        return true;
		}

		public function hookDisplayFooter() {
			if ($this->update_source != 'auto' OR (int)$this->last_update + $this->task_time > time()) return;
	        if ($this->update_rates == 'yes') Currency::refreshCurrencies();
	        if ($this->update_prices == 'yes') $this->UpdateAll();
		}

		public function hookDisplayRightColumnProduct($params) { 
			if ($this->show_price == 'off') return;
	        global $cookie;
	        $site_currency = New Currency($cookie->id_currency);
	        if (!$sbm_currency = $this->GetMultiCurrency($_GET['id_product'])) return;
	        if ($site_currency->id == $sbm_currency['id_currency']) return;
	        $this->smarty->assign('sbm_price', Tools::displayPrice($sbm_currency['product_price'], New Currency($sbm_currency['id_currency'])));
	        return $this->display(__FILE__, 'multicurrency_product_display.tpl');
		}

		public function hookActionProductSave($params){
			if (!isset($_GET['id_product']) OR 
				!$product = new Product($_GET['id_product']) OR 
				!isset($_POST['sbm_id_currency']) OR 
				!$selected_currency = New Currency($_POST['sbm_id_currency'])) return;
	        	$default_curency = CurrencyCore::getDefaultCurrency();
	        if ($selected_currency->id === $default_curency->id) 
	        	return $this->deleteDataCurrency($product->id);
	        $sbm_product = array(
	        				'id_product' => $product->id, 
	        				'id_currency' => $_POST['sbm_id_currency'], 
	        				'product_price' => $_POST['price'], 
	        				'product_sale_price' => $_POST['wholesale_price'], 
	        				'product_detail_prices' => array(),);
	        $this->SaveMultiCurrencyProduct($sbm_product);
	        $this->UpdateProductPrice($params['id_product']);
	        unset($_POST['sbm_id_currency']);
	        return true;
		}

		public function hookActionProductAdd($params){
			return true; 
		}

		public function hookdisplayBackOfficeHeader($params){
			if (Tools::getValue('controller') != 'AdminProducts') return;
	        if (
	        	!isset($_GET['updateproduct']) AND 
	        	!isset($_GET['addproduct'])) 
	        return;

	        $externaljs = '$(document).on("change","select#sbm_id_currency", 
        							function(){
        								var selected = $("option:selected", this).attr("sign");
        								$("div#product-prices").find(".input-group-addon").text(selected);
        								$("div#product-prices").find("#selected_currency_sign").text(selected);
        							});
								$( document ).ready(
									function() {
										$("#link-Prices").click(
											function(){
												if($("select#mc_id_currency").length){;}
												else {
													if($("div#product-prices").length){
														$("div#product-prices").prepend(form_data);
														setStartUpValues();
														return;
													}else { 
														console.log("Hal itu disebabkan oleh halaman lambat loading . memungkinkan memberikan perubahan untuk memuatnya");
														callPricesTabAgain();
													}   
												}
											}
										);
									}
								);
							function callPricesTabAgain(){
								setTimeout(function(){  
									$("#link-Prices").trigger("click");
								},300);
							}
							function setStartUpValues() {
								if(typeof currency_sign === "undefined")return false;
									$("div#product-prices").find(".input-group-addon").text(currency_sign);
									$("div#product-prices").find("#selected_currency_sign").text(currency_sign);
									$("input#wholesale_price").val(product_sale_price);
									$("input#priceTEReal").val(product_price);
									$("input#priceTE").val(product_price);
									$("input#unit_price").val(product_price);
									$("input#priceTE").trigger("keyup");
								return;
							}';
	        $currencies = CurrencyCore::getCurrencies();
	        $product_multicurrency = $this->GetMultiCurrency(Tools::getValue('id_product'));
	        $product_currency = new Currency($product_multicurrency['id_currency']);
	        $data = '<script type="text/javascript">' . 'var form_data = \'' . $this->getSelectBox() . "';";
	        if ($product_multicurrency):
	            $data.= 'var currency_sign = "' . $product_currency->sign . '";' . "";
	            $data.= 'var product_sale_price = "' . $product_multicurrency['product_sale_price'] . '";' . "";
	            $data.= 'var product_price = "' . $product_multicurrency['product_price'] . '";' . "";
	        endif;
	        $data.= $externaljs . '</script>';
	        return $data;
		}

		public function getSelectBox() {
	        $currencies = CurrencyCore::getCurrencies();
	        $default_currency = CurrencyCore::getDefaultCurrency();
	        $product_multicurrency = $this->GetMultiCurrency(Tools::getValue('id_product'));
	        $product_currency = new Currency($product_multicurrency['id_currency']);
	        $data = '<div style="display: block; margin: 0;padding: 15px; border: 3px dashed #eee;"><table width="99%">' . '<tr valign="bottom" align="left"><td>' . '<h4>PLEASE SELECT CURRENCY</h4>';
	        if (!isset($_GET['id_product'])) return $data . 
	        	$this->l("Anda harus menyimpan produk sebelum menggunakan Product Multi- Currency") . '</td></tr></table></div>' . '<div class="clear clearfix"></div><hr/>';
	        $data.= '<select class="" name="sbm_id_currency" style="width:300px; font-size:1.1em; color:#a00" id="sbm_id_currency">';
	        if ($product_multicurrency) {
	            $data.= '<option value="' . $product_currency->id . '" ' . 'sign="' . $product_currency->sign . '" Selected>' . $product_currency->name . ' (' . $this->l('Selected') . ')</option>';
	        }
	        $data.= '<option value="' . $default_currency->id . '" ' . 'sign="' . $default_currency->sign . '" >' . $default_currency->name . ' ' . '(' . $this->l('Default') . ')</option>';
	        foreach ($currencies as $currency):
	            if ($default_currency->id == $currency['id_currency']) continue;
	            $data.= '<option value="' . $currency['id_currency'] . '"' . 'sign="' . $currency['sign'] . '">' . $currency['name'] . '</option>';
	        endforeach;
	        $data .= '</select></td>'. '<td><div id="selected_currency_sign" style="font-size:13em; position:absolute;  margin-left: -178px; margin-top: 0.8em; font-weight:bolder; float:left; color:#ccc">Rp</div></td>'. '<td></td></tr></table></div><div class="clear clearfix"></div><hr/>';
	        return $data;
    	}

		public function hookAdminProductsExtra($params) {
	        return;
	    }
		public function getMultiCurrency($id_product) {
        	return Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'supramulticurrency_product` WHERE `id_product` = \'' . pSQL($id_product) . '\'', false);
    	}

		public function saveMultiCurrencyProduct($sbm_product){
			if ($this->GetMultiCurrency($sbm_product["id_product"])) {
	            if ((int)$sbm_product['id_currency'] == (int)Configuration::get('PS_CURRENCY_DEFAULT')) return $this->deleteDataCurrency((int)$sbm_product['id_currency']);
	            return $this->UpdateMultiCurrencyProduct($sbm_product);
	        }
	        return $this->AddMultiCurrencyProduct($sbm_product);
		}

		public function deleteDataCurrency($id_product) {
	        $delete = 'DELETE FROM  `' . _DB_PREFIX_ . 'supramulticurrency_product` WHERE `id_product` = ' . $id_product . ' LIMIT 1';
	        return Db::getInstance()->execute($delete);
	    }

	    public function addMultiCurrencyProduct($sbm_product) {
	        if ((int)$sbm_product['id_currency'] == (int)Configuration::get('PS_CURRENCY_DEFAULT')) return false;
	        return Db::getInstance()->execute('INSERT INTO  `' . _DB_PREFIX_ . 'supramulticurrency_product` (
	        																	`id_product` ,
	        																	`id_currency` ,
	        																	`product_price` ,
	        																	`product_sale_price`) 
	        																	VALUES (
	        																	' . pSQL($sbm_product["id_product"]) . ',
	        																	' . pSQL($sbm_product["id_currency"]) . ',
	        																	' . pSQL((float)$sbm_product["product_price"]) . ',
	        																	' . pSQL((float)$sbm_product["product_sale_price"]) . '
	        																	);');
	    }
	    public function updateMultiCurrencyProduct($sbm_product) {
	        $q = 'UPDATE  `' . _DB_PREFIX_ . 'supramulticurrency_product` SET 
	        								`id_currency` =  ' . pSQL($sbm_product["id_currency"]) . ',
	        								`product_price`   =  ' . pSQL($sbm_product["product_price"]) . ',
	        								`product_sale_price` =  ' . pSQL($sbm_product["product_sale_price"]) . 
	        								'WHERE  `id_product` =  ' . pSQL($sbm_product["id_product"]) . ' LIMIT 1 ;';
	        Db::getInstance()->execute($q);
	        return;
	    }

	    public function priceRound($value) {
	        if ($this->round == 'none') return round($value, 8);
	        if ($this->round == 'ceil') return ceil($value);
	        if ($this->round == 'floor') return floor($value);
	        if ($this->round == 'round') return round($value);
    	}

    	public function updateProductPrice($id_product) {
	        $product_sbm = $this->GetMultiCurrency($id_product);
	        $def_currency = CurrencyCore::getDefaultCurrency();
	        $sbm_currency = New Currency((int)$product_sbm['id_currency']);
	        $new_product_price = (float)$product_sbm['product_price'] * ($def_currency->conversion_rate / $sbm_currency->conversion_rate);
	        $new_product_sale_price = (float)$product_sbm['product_sale_price'] * ($def_currency->conversion_rate / $sbm_currency->conversion_rate);
	        // $new_product_price = (float)$this->priceRound($new_product_price);
	        // $new_product_sale_price = (float)$this->priceRound($new_product_sale_price);
	        $sql1 = "UPDATE `" . _DB_PREFIX_ . "product` SET 
	        								`price` = " . $new_product_price . ", 
	        								`wholesale_price` = " . $new_product_sale_price . " 
	        								WHERE `id_product` = " . $id_product . " LIMIT 1 ";
	        $sql2 = "UPDATE `" . _DB_PREFIX_ . "product_shop` SET 
	        								`price` = " . $new_product_price . ",	
	        								`wholesale_price` = " . $new_product_sale_price . " 
	        								WHERE `id_product` = " . $id_product . " ";
	        if (
	        	Db::getInstance()->Execute($sql1, false) AND 
	        	Db::getInstance()->Execute($sql2, false)) return true;
	        return false;
	    }

	    function updateAll() {
	        $defaultcurrency = Configuration::get('PS_CURRENCY_DEFAULT');
	        $products = Db::getInstance()->ExecuteS("SELECT * FROM `" . _DB_PREFIX_ . "supramulticurrency_product` WHERE id_currency != '$defaultcurrency' ");
	        foreach ($products as $product) 
	        	$this->UpdateProductPrice($product['id_product']);
	        return true;
	    }

	    public static function getHttpHost($http = false, $entities = false) {
	        $host = (isset(
	        				$_SERVER['HTTP_X_FORWARDED_HOST']) ? 
	        				$_SERVER['HTTP_X_FORWARDED_HOST'] : 
	        				$_SERVER['HTTP_HOST']);

	        if ($entities) $host = htmlspecialchars($host, ENT_COMPAT, 'UTF-8');
	        if ($http) $host = (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://') . $host;
	        return $host;
	    }

	    public static function getShopDomain($http = false, $entities = false) {
	        if (!($domain = Configuration::get('PS_SHOP_DOMAIN'))) $domain = $this->getHttpHost();
	        if ($entities) $domain = htmlspecialchars($domain, ENT_COMPAT, 'UTF-8');
	        if ($http) $domain = 'https://' . $domain;
	        return $domain;
	    }
	}
?>