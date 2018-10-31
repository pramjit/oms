<?php

class ModelPosPos extends Model {
	
	/*
	 * POS database table definition
	 * 
	 */
	
	// This function is how POS module creates it's tables to store order payment entries. You would call this function in your controller in a
	// function called install(). The install() function is called automatically by OC versions 1.4.9.x, and maybe 1.4.8.x when a module is
	// installed in admin.
	public function createModuleTables() {
           
            $query  = "ALTER TABLE `" . DB_PREFIX . "order` ADD `card_no` TINYINT( 4 ) NULL AFTER `payment_code`;";
            $query .= "ALTER TABLE `" . DB_PREFIX . "order` ADD `user_id` INT( 100 ) NULL AFTER `customer_id`;";
            $query .= "ALTER TABLE `" . DB_PREFIX . "user` ADD `cash` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0' AFTER `ip` ,
            ADD `card` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0' AFTER `cash` ;";

            $query .= "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "pos_withdraw` (
              `pos_withdraw_id` int(100) NOT NULL AUTO_INCREMENT,
              `user_id` int(100) NOT NULL,
              `amount` decimal(10,2) NOT NULL,
              `date` datetime NOT NULL,
              PRIMARY KEY (`pos_withdraw_id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

            /*
            $query .= "INSERT INTO '" . DB_PREFIX . "user_group' values('','point of sale','a:2:{s:6:\"access\";a:2:{i:0;s:10:\"module/pos\";i:1;s:10:\"sale/order\";}s:6:\"modify\";a:2:{i:0;s:10:\"module/pos\";i:1;s:10:\"sale/order\";}}')";

            $this->db->query($query);

            $user_group_id = $this->db->getLastId();

            //add setting data
            $this->db->query("DELETE '" . DB_PREFIX . "setting' WHERE key= 'pos_user_group_id'");
            $this->db->query("INSERT INTO '" . DB_PREFIX . "setting' values('','0','POS',pos_user_group_id','".$user_group_id."',0)");
            */
	}

	public function deleteModuleTables() {
		// $query = $this->db->query("DROP TABLE " . DB_PREFIX . "order_payment");
	}

	public function addPayment($data) {            
            $sql = "update " . DB_PREFIX . "user set cash = cash + ".$data['cash'].", card= card + ".$data['card']." where user_id = ".$data['user_id'];
            $this->db->query($sql);
	}
	
        public function editPayment($order_id,$data){
            $query = $this->db->query("select user_id, total, payment_method from " . DB_PREFIX . "order where order_id = '".$order_id."'");
            $row = $query->row;
            
            $cash = $card = 0;
            
            if($row['total'] > $data['total'] || $data['payment_method'] != 'Card'){
                $cash = $data['total'] - $row['total'];
            }elseif($data['payment_method'] == 'Card'){
                $card = $data['total'] - $row['total'];
            }
            
            $sql = "update " . DB_PREFIX . "user set cash = cash + ".$cash.", card= card + ".$card." where user_id = ".$data['user_id'];
            $this->db->query($sql);
        }

	public function UpdateOrderStatusTemp($orderid,$id) 
	 {            
				$log=new Log("paycode.log");
	            $sql = "update " . DB_PREFIX . "order_temp set order_status_id = '".$id."' where order_id = '".$orderid."'";
				$log->write($sql);
		$log->write($this->db->query($sql));
	}

	public function UpdateOrderStatusLeads($orderid,$id) 
	 {            
				$log=new Log("dsclpaycode.log");
	            $sql = "update " . DB_PREFIX . "order_leads set order_status_id = '".$id."' where order_id = '".$orderid."'";
				$log->write($sql);
		$log->write($this->db->query($sql));
	}
	public function RequisitionToBill($orderid,$billid) 
	 {            
				$log=new Log("dsclpaycode.log");
	            $sql = "insert into " . DB_PREFIX . "requisition_to_bill set requisition_id = '".$orderid."',bill_id='".$billid."'";
				$log->write($sql);
		$log->write($this->db->query($sql));
	}

    public function adminmodel($model) {
      
      $admin_dir = DIR_SYSTEM;
      $admin_dir = str_replace('system/','admin/',$admin_dir);
      $file = $admin_dir . 'model/' . $model . '.php';      
      //$file  = DIR_APPLICATION . 'model/' . $model . '.php';
      $class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
      
      if (file_exists($file)) {
         include_once($file);
         
         $this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
      } else {
         trigger_error('Error: Could not load model ' . $model . '!');
         exit();               
      }
   }


        
	public function addOrder($data) {


		$log=new Log("quantity.log");
		$log->write($data);
		$this->adminmodel('setting/store');
		
		$store_info = $this->model_setting_store->getStore($data['store_id']);
		
		if ($store_info) {
			$store_name = $store_info['name'];
			$store_url = $store_info['url'];
		} else {
			$store_name = $this->config->get('config_name');
			$store_url = HTTP_CATALOG;
		}
		
		$this->load->model('setting/setting');
		
		$setting_info = $this->model_setting_setting->getSetting('setting', $data['store_id']);
			
		if (isset($setting_info['invoice_prefix'])) {
			$invoice_prefix = $setting_info['invoice_prefix'];
		} else {
			$invoice_prefix = $this->config->get('config_invoice_prefix');
		}
		
		$this->load->model('localisation/country');
		
		$this->load->model('localisation/zone');
		
		$country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);
		
		if ($country_info) {
			$shipping_country = $country_info['name'];
			$shipping_address_format = $country_info['address_format'];
		} else {
			$shipping_country = '';	
			$shipping_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}	
		
		$zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);
		
		if ($zone_info) {
			$shipping_zone = $zone_info['name'];
		} else {
			$shipping_zone = '';			
		}	
					
		$country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);
		
		if ($country_info) {
			$payment_country = $country_info['name'];
			$payment_address_format = $country_info['address_format'];			
		} else {
			$payment_country = '';	
			$payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';					
		}
	
		$zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);
		
		if ($zone_info) {
			$payment_zone = $zone_info['name'];
		} else {
			$payment_zone = '';			
		}	

		$this->load->model('localisation/currency');

		$currency_info = $this->model_localisation_currency->getCurrencyByCode($this->config->get('config_currency'));
		
		if ($currency_info) {
			$currency_id = $currency_info['currency_id'];
			$currency_code = $currency_info['code'];
			$currency_value = $currency_info['value'];
		} else {
			$currency_id = 0;
			$currency_code = $this->config->get('config_currency');
			$currency_value = 1.00000;			
		}
      	
                $this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET card_no ='" . $this->db->escape($data['card_no']) . "',  invoice_prefix = '" . $this->db->escape($invoice_prefix) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($store_name) . "',store_url = '" . $this->db->escape($store_url) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($payment_country) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($payment_zone) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($payment_address_format) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($shipping_country) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($shipping_zone) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($shipping_address_format) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', order_status_id = '" . (int)$data['order_status_id'] . "', affiliate_id  = '" . (int)$data['affiliate_id'] . "', language_id = '" . (int)$this->config->get('config_language_id') . "', currency_id = '" . (int)$currency_id . "', currency_code = '" . $this->db->escape($currency_code) . "', currency_value = '" . (float)$currency_value . "', date_added = NOW(), date_modified = NOW()");
      	
                $order_id = $this->db->getLastId();

                if (isset($data['order_product'])) {		
      		foreach ($data['order_product'] as $order_product) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$order_product['product_id'] . "', name = '" . $this->db->escape($order_product['name']) . "', model = '" . $this->db->escape($order_product['model']) . "', quantity = '" . (int)$order_product['quantity'] . "', price = '" . (float)$order_product['price'] . "', total = '" . (float)$order_product['total'] . "', tax = '" . (float)$order_product['tax'] . "', reward = '" . (int)$order_product['reward'] . "'");
			
				$order_product_id = $this->db->getLastId();
				//quantity  to update in store
			$log->write("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");
				$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");
			$log->write("UPDATE " . DB_PREFIX . "product_to_store SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND store_id = '".(int)$data['store_id']."'");

				$this->db->query("UPDATE " . DB_PREFIX . "product_to_store SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND store_id = '".(int)$data['store_id']."'");

				if (isset($order_product['order_option'])) {
					foreach ($order_product['order_option'] as $order_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$order_option['product_option_id'] . "', product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "', name = '" . $this->db->escape($order_option['name']) . "', `value` = '" . $this->db->escape($order_option['value']) . "', `type` = '" . $this->db->escape($order_option['type']) . "'");
						
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}				
			}
		}
		
		if (isset($data['order_voucher'])) {	
			foreach ($data['order_voucher'] as $order_voucher) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . (int)$order_id . "', voucher_id = '" . (int)$order_voucher['voucher_id'] . "', description = '" . $this->db->escape($order_voucher['description']) . "', code = '" . $this->db->escape($order_voucher['code']) . "', from_name = '" . $this->db->escape($order_voucher['from_name']) . "', from_email = '" . $this->db->escape($order_voucher['from_email']) . "', to_name = '" . $this->db->escape($order_voucher['to_name']) . "', to_email = '" . $this->db->escape($order_voucher['to_email']) . "', voucher_theme_id = '" . (int)$order_voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($order_voucher['message']) . "', amount = '" . (float)$order_voucher['amount'] . "'");
			
      			$this->db->query("UPDATE " . DB_PREFIX . "voucher SET order_id = '" . (int)$order_id . "' WHERE voucher_id = '" . (int)$order_voucher['voucher_id'] . "'");
			}
		}

		// Get the total
		$total = 0;
		$log->write($data['order_total']);
		if (isset($data['order_total'])) {		
      		foreach ($data['order_total'] as $order_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($order_total['code']) . "', title = '" . $this->db->escape($order_total['title']) . "',  `value` = '" . (float)$order_total['value'] . "', sort_order = '" . (int)$order_total['sort_order'] . "'");
			}
			
			$total += $order_total['value'];
		}

		// Affiliate
		$affiliate_id = 0;
		$commission = 0;

		if (!empty($this->request->post['affiliate_id'])) {
			$affiliate_id = (int)$this->request->post['affiliate_id'];
		}
		
		if ($affiliate_id > 0 ) {
			$this->load->model('sale/affiliate');
			
			$affiliate_info = $this->model_sale_affiliate->getAffiliate($affiliate_id);
			
			if ($affiliate_info) {
				$commission = ($total / 100) * $affiliate_info['commission']; 
			}
		}
		if($data['payment_method']=='Cheque')
		{
		 	$sqlbank = "insert into " . DB_PREFIX . "order_by_cheque set order_id = '".(int)$order_id."',cheque_num='".$data['chenum']."',cheque_micr='".$data['chemic']."',cheque_bank='".$data['chebnk']."',	cheque_account_name='".$data['cheacc']."',cheque_account_num='".$data['cheaccno']."'";
			$this->db->query($sqlbank);
			$log->write($sqlbank);
		}
		
		$sqlappend="";
		//check as per type of pos
		if($data['payment_method']=='Cheque')
		{
			$sqlappend=",cheque='".(float)$total."'";	
		}
		if($data['payment_method']=='Cash')
		{
			$sqlappend=",cash='".(float)$total."'";			
		}
		if($data['payment_method']=='Tagged Cash' && isset($data['amtcash']) && (!empty($data['amtcash'])))
		{
			$sqlappend=",tagged='".((float)$total-(float)$data['amtcash'])."',cash='".(float)$data['amtcash']."'";			
		}
		else if($data['payment_method']=='Tagged')
		{
			$sqlappend=",tagged='".(float)$total."'";			
		}
		
		if($data['payment_method']=='Subsidy')
		{
			$sqlappend=",cash='".(float)$data['subsidy']."',subsidy='".((float)$data['sub'])."' ";			
		}	
		$log->write($sqlappend);

		// Update order total			 
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET user_id = '".$this->user->getId()."', total = '" . (float)$total . "', affiliate_id = '" . (int)$affiliate_id . "', commission = '" . (float)$commission . "' ".$sqlappend." WHERE order_id = '" . (int)$order_id . "'"); 	
		
		return $order_id;
	}
        
	public function editOrder($order_id, $data) {
		$this->load->model('localisation/country');

		$this->load->model('localisation/zone');

		$country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);

		if ($country_info) {
			$shipping_country = $country_info['name'];
			$shipping_address_format = $country_info['address_format'];
		} else {
			$shipping_country = '';	
			$shipping_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}	

		$zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);

		if ($zone_info) {
			$shipping_zone = $zone_info['name'];
		} else {
			$shipping_zone = '';			
		}	

		$country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);

		if ($country_info) {
			$payment_country = $country_info['name'];
			$payment_address_format = $country_info['address_format'];			
		} else {
			$payment_country = '';	
			$payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';					
		}

		$zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);

		if ($zone_info) {
			$payment_zone = $zone_info['name'];
		} else {
			$payment_zone = '';			
		}			

		// Restock products before subtracting the stock later on
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

			foreach($product_query->rows as $product) {
				$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "' AND subtract = '1'");

				$option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

				foreach ($option_query->rows as $option) {
					$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
				}
			}
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($payment_country) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($payment_zone) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($payment_address_format) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "',  shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($shipping_country) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($shipping_zone) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($shipping_address_format) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', order_status_id = '" . (int)$data['order_status_id'] . "', affiliate_id  = '" . (int)$data['affiliate_id'] . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'"); 
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");

		if (isset($data['order_product'])) {
			foreach ($data['order_product'] as $order_product) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_product_id = '', order_id = '" . (int)$order_id . "', product_id = '" . (int)$order_product['product_id'] . "', name = '" . $this->db->escape($order_product['name']) . "', model = '" . $this->db->escape($order_product['model']) . "', quantity = '" . (int)$order_product['quantity'] . "', price = '" . (float)$order_product['price'] . "', total = '" . (float)$order_product['total'] . "', tax = '" . (float)$order_product['tax'] . "', reward = '" . (int)$order_product['reward'] . "'");

				$order_product_id = $this->db->getLastId();

				$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");

				if (isset($order_product['order_option'])) {
					foreach ($order_product['order_option'] as $order_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_option_id = '', order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$order_option['product_option_id'] . "', product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "', name = '" . $this->db->escape($order_option['name']) . "', `value` = '" . $this->db->escape($order_option['value']) . "', `type` = '" . $this->db->escape($order_option['type']) . "'");


						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'"); 

		if (isset($data['order_voucher'])) {	
			foreach ($data['order_voucher'] as $order_voucher) {	
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_voucher_id = '', order_id = '" . (int)$order_id . "', voucher_id = '" . (int)$order_voucher['voucher_id'] . "', description = '" . $this->db->escape($order_voucher['description']) . "', code = '" . $this->db->escape($order_voucher['code']) . "', from_name = '" . $this->db->escape($order_voucher['from_name']) . "', from_email = '" . $this->db->escape($order_voucher['from_email']) . "', to_name = '" . $this->db->escape($order_voucher['to_name']) . "', to_email = '" . $this->db->escape($order_voucher['to_email']) . "', voucher_theme_id = '" . (int)$order_voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($order_voucher['message']) . "', amount = '" . (float)$order_voucher['amount'] . "'");

				$this->db->query("UPDATE " . DB_PREFIX . "voucher SET order_id = '" . (int)$order_id . "' WHERE voucher_id = '" . (int)$order_voucher['voucher_id'] . "'");
			}
		}

		// Get the total
                $total = 0;
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'"); 
                
		if (isset($data['order_total'])) {		
      		foreach ($data['order_total'] as $order_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($order_total['code']) . "', title = '" . $this->db->escape($order_total['title']) . "', `value` = '" . (float)$order_total['value'] . "', sort_order = '" . (int)$order_total['sort_order'] . "'");
			}
			
			$total += $order_total['value'];
		}

		// Affiliate
		$affiliate_id = 0;
		$commission = 0;

		if (!empty($this->request->post['affiliate_id'])) {
			$affiliate_id = (int)$this->request->post['affiliate_id'];
		}
		
		if ($affiliate_id > 0 ) {
			$this->load->model('sale/affiliate');
			
			$affiliate_info = $this->model_sale_affiliate->getAffiliate($affiliate_id);
			
			if ($affiliate_info) {
				$commission = ($total / 100) * $affiliate_info['commission']; 
			}
		}
		
		// Update order total			 
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET user_id = '".$this->user->getId()."', total = '" . (float)$total . "', affiliate_id = '" . (int)$affiliate_id . "', commission = '" . (float)$commission . "' WHERE order_id = '" . (int)$order_id . "'"); 	
		
		return $order_id;
	}

	// add for Browse begin
	public function getTopStoreCategories($sid) {
		// get all categories
		$log=new Log("category.log");
		//$log->write("SELECT c.image, c.category_id, c.parent_id, cd.name FROM `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "category_description` cd ON c.category_id = cd.category_id  LEFT JOIN `" . DB_PREFIX . "category_to_store` c2s ON c.category_id = c2s.category_id   WHERE c.parent_id = 0 and c2s.store_id='".$sid."' and cd.language_id = '". (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, LCASE(cd.name)");
		//$query = $this->db->query("SELECT c.image, c.category_id, c.parent_id, cd.name FROM `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "category_description` cd ON c.category_id = cd.category_id  LEFT JOIN `" . DB_PREFIX . "category_to_store` c2s ON c.category_id = c2s.category_id   WHERE c.parent_id = 0 and c2s.store_id='".$sid."' and cd.language_id = '". (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, LCASE(cd.name)");
                $log->write("SELECT c.image, c.category_id, c.parent_id, cd.name FROM `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "category_description` cd ON c.category_id = cd.category_id  LEFT JOIN `" . DB_PREFIX . "category_to_store` c2s ON c.category_id = c2s.category_id   WHERE c.parent_id = 0 and c2s.store_id='".$sid."' and cd.language_id = '". (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, LCASE(cd.name)");
		$query = $this->db->query("SELECT c.image, c.category_id, c.parent_id, cd.name FROM `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "category_description` cd ON c.category_id = cd.category_id  LEFT JOIN `" . DB_PREFIX . "category_to_store` c2s ON c.category_id = c2s.category_id   WHERE c.parent_id = 0 and cd.language_id = '". (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, LCASE(cd.name)");
		return $query->rows;
	}


	public function getTopCategories() {
		// get all categories
		$query = $this->db->query("SELECT c.image, c.category_id, c.parent_id, cd.name FROM `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "category_description` cd ON c.category_id = cd.category_id WHERE c.parent_id = 0 and cd.language_id = '". (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, LCASE(cd.name)");
		return $query->rows;
	}
        
        public function getCategories() {
		// get all categories
		$query = $this->db->query("SELECT c.image, c.category_id, c.parent_id, cd.name FROM `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "category_description` cd ON c.category_id = cd.category_id WHERE cd.language_id = '". (int)$this->config->get('config_language_id') . "'");
		return $query->rows;
	}
        
	public function getSubCategories($category_id) {
		// get all sub categories under the given category
		$query = $this->db->query("SELECT c.category_id, c.image, cd.name FROM `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "category_description` cd ON c.category_id = cd.category_id WHERE cd.language_id = '". (int)$this->config->get('config_language_id') . "' AND c.parent_id = '" . $category_id . "'");
		return $query->rows;
	}
        
        public function getProductByBarcode($barcode) {
		// get all products in the given category
		$query = $this->db->query("SELECT p.product_id, GROUP_CONCAT(po.option_id) as options from `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_option` po ON p.product_id = po.product_id WHERE p.isbn = '" . $barcode . "'");
		return $query->row;
	}
        
	public function total_products($category_id) {
            // get all products in the given category
            $query = $this->db->query("SELECT count(p.product_id) as total FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "product` p ON pc.product_id = p.product_id LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id WHERE pd.language_id = '". (int)$this->config->get('config_language_id') . "' AND pc.category_id = '" . $category_id . "'  AND p.status = '1' AND p.quantity > 0");
            $result = $query->row;
            return $result['total'];
	}
        
        public function getProducts($category_id, $limit = 20, $offset = 0) {
            // get all products in the given category
//"SELECT p.product_id, p.price, p.quantity, p.image, pd.name, GROUP_CONCAT(po.option_id) as options FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "product` p ON pc.product_id = p.product_id LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id LEFT JOIN `" . DB_PREFIX . "product_option` po ON p.product_id = po.product_id WHERE pd.language_id = '". (int)$this->config->get('config_language_id') . "' AND pc.category_id = '" . $category_id . "'  AND p.status = '1' AND p.quantity > 0 GROUP BY p.product_id LIMIT ".$offset.", ".$limit
   //         $query = $this->db->query("SELECT p.product_id, p.price, p.quantity, p.image, pd.name, GROUP_CONCAT(po.option_id) as options FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "product` p ON pc.product_id = p.product_id LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id LEFT JOIN `" . DB_PREFIX . "product_option` po ON p.product_id = po.product_id WHERE pd.language_id = '". '0' . "' AND pc.category_id = '" . $category_id . "'  AND p.status = '1' AND p.quantity > 0 GROUP BY p.product_id LIMIT ".$offset.", ".$limit);

	$query = $this->db->query("SELECT p.product_id, p.price, p.quantity, p.image, pd.name, GROUP_CONCAT(po.option_id) as options,ps.store_price FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "product` p ON pc.product_id = p.product_id LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id LEFT JOIN `" . DB_PREFIX . "product_option` po ON p.product_id = po.product_id LEFT JOIN `" . DB_PREFIX . "product_to_store` ps on p.product_id=ps.product_id WHERE pd.language_id = '". (int)$this->config->get('config_language_id') . "' AND pc.category_id = '" . $category_id . "'  AND p.status = '1' AND ps.store_id='".$this->user->getStoreId()."' AND p.quantity > 0 AND ps.quantity > 0 GROUP BY p.product_id LIMIT ".$offset.", ".$limit);

            return $query->rows;
	}
        
        public function total_search_products($q){
            // get all products in the given category
            $query = $this->db->query("SELECT count( p.product_id ) AS total from `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id WHERE pd.language_id = '". (int)$this->config->get('config_language_id') . "'  AND pd.name like '%".$q."%' AND p.status = '1' AND p.quantity > 0");
	    $result= $query->row;
            return $result['total'];
        }
        
        public function searchProducts($q, $limit = 20, $offset = 0){
            // get all products in the given category
            $query = $this->db->query("SELECT p.product_id, p.price, p.quantity, p.image, pd.name, GROUP_CONCAT(po.option_id) as options,ps.store_price FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "product` p ON pc.product_id = p.product_id LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id LEFT JOIN `" . DB_PREFIX . "product_option` po ON p.product_id = po.product_id LEFT JOIN `" . DB_PREFIX . "product_to_store` ps on p.product_id=ps.product_id WHERE pd.language_id = '". (int)$this->config->get('config_language_id') . "'  AND pd.name like '%".$q."%' AND p.status = '1' AND ps.store_id='".$this->user->getStoreId()."' AND p.quantity > 0 GROUP BY p.product_id LIMIT ".$offset.", ".$limit);
	    return $query->rows;
        }


        public function searchProductsStore($q, $limit = 20, $offset = 0){
            $log=new Log("prdsearchQry.log");
            $log->write("SELECT p.product_id, p.price,p.sku, ps.quantity, p.image, pd.name, ps.store_price,p.tax_class_id FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "product` p ON pc.product_id = p.product_id LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id  LEFT JOIN `" . DB_PREFIX . "product_to_store` ps on p.product_id=ps.product_id WHERE pd.language_id = '". (int)$this->config->get('config_language_id') . "'  AND pd.name like '%".$q."%' AND p.status = '1'  AND p.quantity > 0 AND ps.quantity > 0 GROUP BY p.product_id LIMIT ".$offset.", ".$limit);
            // get all products in the given category
            $query = $this->db->query("SELECT p.product_id, p.price,p.sku, ps.quantity, p.image, pd.name,ps.store_price,p.tax_class_id FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "product` p ON pc.product_id = p.product_id LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id LEFT JOIN `" . DB_PREFIX . "product_to_store` ps on p.product_id=ps.product_id WHERE pd.language_id = '". (int)$this->config->get('config_language_id') . "'  AND pd.name like '%".$q."%' AND p.status = '1'  AND p.quantity > 0 AND ps.quantity > 0 GROUP BY p.product_id LIMIT ".$offset.", ".$limit);
	    return $query->rows;
        }



public function getCustomers($sid,$uid){
            //search customer by name 
            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer`  WHERE store_id = '".$sid."'");
	    return $query->rows;
        }

        
        public function getCustomer($customer_id){
            //search customer by name 
            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer`  WHERE customer_id = '".$customer_id."'");
	    return $query->row;
        }
        public function getCustomerByPhone($customer_id){
            //search customer by name 
            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer`  WHERE telephone = '".$customer_id."'");
	    return $query->row;
        }
        public function searchAffiliate($q){
            //search affiliate by name             
            $query = $this->db->query("SELECT c.firstname, c.lastname, c.affiliate_id FROM `" . DB_PREFIX . "affiliate` c WHERE c.firstname like '%".$q."%' or c.lastname like '%".$q."%' or c.telephone like '%".$q."%'");                                    
	    return $query->rows;
        }
        
        public function searchCustomer($q){
            //search customer by name                         
            $query = $this->db->query("SELECT c.firstname, c.lastname, c.customer_id FROM `" . DB_PREFIX . "customer` c WHERE c.firstname like '%".$q."%' or c.lastname like '%".$q."%' or c.telephone like '%".$q."%'");                    
	    return $query->rows;
        }
        
        public function getStatistics(){
            $query = $this->db->query("select user_id, username, firstname, lastname, cash, card from " . DB_PREFIX . "user");
            return $query->rows;
        }
        
        public function withdraw($data){
            //user_id, amount 
            //1) insert into oc_pos_withdraw 
            //2) cash = cash - amount on user 
            $this->db->query("insert into `" . DB_PREFIX . "pos_withdraw` set pos_withdraw_id = '', user_id ='".$data['user_id']."', amount= '".$data['amount']."', date = NOW()");
            $this->db->query("update `" . DB_PREFIX . "user` set cash = cash - ".$data['amount']." where user_id = '".$data['user_id']."'");
        }
        
        public function total_history($user_id){
            $query = $this->db->query("select count(*) as total from `" . DB_PREFIX . "pos_withdraw` pw left join `" . DB_PREFIX . "user` u on pw.user_id = u.user_id where pw.user_id='".$user_id."'");
            $row = $query->row;
            return $row['total'];
        }
        
        public function history($user_id, $limit = 10, $offset = 0){            
            $query = $this->db->query("select u.username, u.firstname, u.lastname, pw.* from `" . DB_PREFIX . "pos_withdraw` pw left join `" . DB_PREFIX . "user` u on pw.user_id = u.user_id where pw.user_id='".$user_id."' ORDER BY pw.date DESC LIMIT ".$offset.", ".$limit);
            return $query->rows;
        }
        
        public function hold_cart($data){
            $this->db->query("insert into `" . DB_PREFIX . "cart_holder` set cart_holder_id = '', user_id ='".$data['user_id']."', name= '".$data['name']."', cart = '".serialize($data['cart'])."', date_created = NOW()"); 
            return $this->db->getLastId();
        }
        
        public function get_hold_cart_list(){
            $query = $this->db->query('select cart_holder_id, name, date_created from `' . DB_PREFIX . 'cart_holder` where user_id = "'.$this->user->getId().'"'); 
            return $query->rows;
        }
        
        public function hold_cart_select($id){
            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'cart_holder` WHERE cart_holder_id="'.$id.'"');
            return $query->row;
        }
        
        public function hold_cart_delete($id){
            $this->db->query('DELETE FROM `' . DB_PREFIX . 'cart_holder` WHERE cart_holder_id="'.$id.'"');
        }
        
        public function get_today_card($user_id){
            $query = $this->db->query('SELECT sum(total) as total FROM `' . DB_PREFIX . 'order` WHERE user_id="'.$user_id.'" AND payment_method="Card" AND DATE(date_added) = DATE(NOW())');
            $row = $query->row;
            return $row['total'];
        }
        
        public function get_today_cash($user_id){
            $query = $this->db->query('SELECT sum(total) as total FROM `' . DB_PREFIX . 'order` WHERE user_id="'.$user_id.'" AND payment_method="Cash" AND DATE(date_added) = DATE(NOW())');
            $row = $query->row;
            return $row['total'];
        }
        
        public function get_user_balance($user_id){
            $query = $this->db->query('SELECT cash, card FROM `' . DB_PREFIX . 'user` WHERE user_id="'.$user_id.'"');
            return $query->row;   
        }
	
	public function get_store_balance($store_id){
            $query = $this->db->query('SELECT currentcredit FROM `' . DB_PREFIX . 'store` WHERE store_id="'.$store_id.'"');
            return $query->row['currentcredit'];   
        }

//send order to sugar
public function sendsugar($data) 
	{

	$log=new Log("sugar.log");
	$log->write("save data sugar");		
	$log->write($data);
	$log->write("g=".$data['gcustomer_id']);
        //Data, connection, auth
        $dataFromTheForm =        '<date_potential>'.$data['date_potential'].'</date_potential>
        <date_modified>'.$data['date_potential'].'</date_modified>
        <date_added>'.$data['date_potential'].'</date_added>
        <order_status_id>'.$data['order_status_id'].'</order_status_id>
        <total>'.$data['total'].'</total>
        <payment_code>'.$data['payment_code'].'</payment_code>
        <payment_method>'.$data['payment_method'].'</payment_method>
        <telephone>'.$data['telephone'].'</telephone>
        <email>'.$data['email'].'</email>
        <lastname>'.$data['fname'].'</lastname>
        <firstname>'.$data['farmername'].'</firstname>
        <user_id>'.$data['user_id'].'</user_id>
        <card_no>'.$data['card_no'].'</card_no>
	<uid>'.$data['uid'].'</uid>
	<vid>'.$data['village_id'].'</vid>
	<village_name>'.$data['village_name'].'</village_name>
	<circle_code>'.$data['circle_code'].'</circle_code>
	<transid>'.$data['transid'].'</transid>
	<ename>'.$data['ename'].'</ename>
        <customer_id>'.$data['gcustomer_id'].'</customer_id>
        <customer_group_id>'.$data['customer_group_id'].'</customer_group_id>
        <store_url>'.$data['store_id'].'</store_url>
        <order_id>'.$data['oid'].'</order_id>
        <store_name>'.$data['store_name'].'</store_name>
        <store_id>'.$data['store_id'].'</store_id>'
; // request data from the form
$orddtl='<orddtl>';

                        foreach ($data['order_product'] as $result) {

$orddtl.='
        <orderdetail>
          <ORD_DATE>'.$data['date_potential'].'</ORD_DATE>
          <reward>0</reward>
          <order_product_id>0</order_product_id>
          <order_id>'.$data['oid'].'</order_id>
          <product_id>'.$result['product_id'].'</product_id>
          <name>'.$result['name'].'</name>
          <model>'.$result['model'].'</model>
          <quantity>'.$result['quantity'].'</quantity>
          <price>'.$result['price'].'</price>
          <total>'.$result['total'].'</total>
          <tax>'.$result['tax'].'</tax>
        </orderdetail>';
}
$orddtl.='</orddtl>';

        $soapUrl = "http://dsclsugar.com/akshamob/service.asmx?op=Requisition"; // asmx URL of WSDL
        $soapUser = "username";  //  username
        $soapPassword = "password"; // password

        // xml post structure

        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                              <soap:Body>
                                <Requisition xmlns="http://aksha/app/"> 
                                  <oid>'.$dataFromTheForm.'</oid>'.$orddtl.' 
                                </Requisition>
                              </soap:Body>
                            </soap:Envelope>';   // data from the form, e.g. some ID number

           $headers = array(
                        "Content-type: text/xml;charset=\"utf-8\"",
                        "Accept: text/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: http://aksha/app/Requisition", 
                        "Content-length: ".strlen($xml_post_string),
                    ); //SOAPAction: your op URL

            $url = $soapUrl;

            // PHP cURL  for https connection with auth
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
            //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // converting
            $response = curl_exec($ch); 
	    $log->write($response);	
            curl_close($ch);
            // converting
            $response1 = str_replace("<soap:Body>","",$response);
            $response2 = str_replace("</soap:Body>","",$response1);
		$log->write($response1);	
		$log->write($response2);	

            // convertingc to XML
            $parser = simplexml_load_string($response2);
            // user $parser to get your data out of XML response and to display it.


	}

//end data





//end

//emp
 public function addOrder_leads($data) {


        $log=new Log("dsclquantity.log");
        $log->write($data);
        $this->adminmodel('setting/store');
        $log->write("adata");        
        $store_info = $this->model_setting_store->getStore($data['store_id']);
        
        if ($store_info) {
            $store_name = $store_info['name'];
            $store_url = $store_info['url'];
        } else {
            $store_name = $this->config->get('config_name');
            $store_url = HTTP_CATALOG;
        }
        
        $this->load->model('setting/setting');
        
        $setting_info = $this->model_setting_setting->getSetting('setting', $data['store_id']);
            
        if (isset($setting_info['invoice_prefix'])) {
            $invoice_prefix = $setting_info['invoice_prefix'];
        } else {
            $invoice_prefix = $this->config->get('config_invoice_prefix');
        }
        
        $this->load->model('localisation/country');
        
        $this->load->model('localisation/zone');
        
        $country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);
        
        if ($country_info) {
            $shipping_country = $country_info['name'];
            $shipping_address_format = $country_info['address_format'];
        } else {
            $shipping_country = '';    
            $shipping_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
        }    
        
        $zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);
        
        if ($zone_info) {
            $shipping_zone = $zone_info['name'];
        } else {
            $shipping_zone = '';            
        }    
                    
        $country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);
        
        if ($country_info) {
            $payment_country = $country_info['name'];
            $payment_address_format = $country_info['address_format'];            
        } else {
            $payment_country = '';    
            $payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';                    
        }
    
        $zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);
        
        if ($zone_info) {
            $payment_zone = $zone_info['name'];
        } else {
            $payment_zone = '';            
        }    

        $this->load->model('localisation/currency');

        $currency_info = $this->model_localisation_currency->getCurrencyByCode($this->config->get('config_currency'));
        
        if ($currency_info) {
            $currency_id = $currency_info['currency_id'];
            $currency_code = $currency_info['code'];
            $currency_value = $currency_info['value'];
        } else {
            $currency_id = 0;
            $currency_code = $this->config->get('config_currency');
            $currency_value = 1.00000;            
        }
                  $insert_q="INSERT INTO `" . DB_PREFIX . "order_leads` SET card_no ='" . $this->db->escape($data['card_no']) . "',  invoice_prefix = '" . $this->db->escape($invoice_prefix) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($store_name) . "',store_url = '" . $this->db->escape($store_url) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($payment_country) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($payment_zone) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($payment_address_format) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($shipping_country) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($shipping_zone) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($shipping_address_format) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', order_status_id = '" . (int)$data['order_status_id'] . "', affiliate_id  = '" . (int)$data['affiliate_id'] . "', language_id = '" . (int)$this->config->get('config_language_id') . "', currency_id = '" . (int)$currency_id . "', currency_code = '" . $this->db->escape($currency_code) . "', currency_value = '" . (float)$currency_value . "', date_added = NOW(), date_modified = NOW(),date_potential='".$data['date_potential']."'";
                $this->db->query($insert_q);
          
                $order_id = $this->db->getLastId();

                if (isset($data['order_product'])) {        
              foreach ($data['order_product'] as $order_product) {    
                  $this->db->query("INSERT INTO " . DB_PREFIX . "order_product_leads SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$order_product['product_id'] . "', name = '" . $this->db->escape($order_product['name']) . "', model = '" . $this->db->escape($order_product['model']) . "', quantity = '" . (int)$order_product['quantity'] . "', price = '" . (float)$order_product['price'] . "', total = '" . (float)$order_product['total'] . "', tax = '" . (float)$order_product['tax'] . "', reward = '" . (int)$order_product['reward'] . "'");
            
                $order_product_id = $this->db->getLastId();
                //quantity  to update in store
                      
                              
            }
        }
        


        // Get the total
        $total = 0;
        $log->write($data['order_total']);
        if (isset($data['order_total'])) {        
              foreach ($data['order_total'] as $order_total) {    
                  $this->db->query("INSERT INTO " . DB_PREFIX . "order_total_leads SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($order_total['code']) . "', title = '" . $this->db->escape($order_total['title']) . "',  `value` = '" . (float)$order_total['value'] . "', sort_order = '" . (int)$order_total['sort_order'] . "'");
            }
            
            $total += $order_total['value'];
        }

        // Affiliate
        $affiliate_id = 0;
        $commission = 0;

                        $log->write("comm");
	$log->write("UPDATE `" . DB_PREFIX . "order_leads` SET user_id = '".$data['user_id']."', total = '" . (float)$total . "', affiliate_id = '" . (int)$affiliate_id . "', commission = '" . (float)$commission . "' WHERE order_id = '" . (int)$order_id . "'");
                        $log->write("dcomm");
        // Update order total            
        $this->db->query("UPDATE `" . DB_PREFIX . "order_leads` SET user_id = '".$data['user_id']."', total = '" . (float)$total . "', affiliate_id = '" . (int)$affiliate_id . "', commission = '" . (float)$commission . "' WHERE order_id = '" . (int)$order_id . "'");     
        
        return $order_id;
    }



public function addOrder_temp($data) {


		$log=new Log("quantity.log");
		$log->write($data);
		$this->adminmodel('setting/store');
		
		$store_info = $this->model_setting_store->getStore($data['store_id']);
		
		if ($store_info) {
			$store_name = $store_info['name'];
			$store_url = $store_info['url'];
		} else {
			$store_name = $this->config->get('config_name');
			$store_url = HTTP_CATALOG;
		}
		
		$this->load->model('setting/setting');
		
		$setting_info = $this->model_setting_setting->getSetting('setting', $data['store_id']);
			
		if (isset($setting_info['invoice_prefix'])) {
			$invoice_prefix = $setting_info['invoice_prefix'];
		} else {
			$invoice_prefix = $this->config->get('config_invoice_prefix');
		}
		
		$this->load->model('localisation/country');
		
		$this->load->model('localisation/zone');
		
		$country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);
		
		if ($country_info) {
			$shipping_country = $country_info['name'];
			$shipping_address_format = $country_info['address_format'];
		} else {
			$shipping_country = '';	
			$shipping_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}	
		
		$zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);
		
		if ($zone_info) {
			$shipping_zone = $zone_info['name'];
		} else {
			$shipping_zone = '';			
		}	
					
		$country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);
		
		if ($country_info) {
			$payment_country = $country_info['name'];
			$payment_address_format = $country_info['address_format'];			
		} else {
			$payment_country = '';	
			$payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';					
		}
	
		$zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);
		
		if ($zone_info) {
			$payment_zone = $zone_info['name'];
		} else {
			$payment_zone = '';			
		}	

		$this->load->model('localisation/currency');

		$currency_info = $this->model_localisation_currency->getCurrencyByCode($this->config->get('config_currency'));
		
		if ($currency_info) {
			$currency_id = $currency_info['currency_id'];
			$currency_code = $currency_info['code'];
			$currency_value = $currency_info['value'];
		} else {
			$currency_id = 0;
			$currency_code = $this->config->get('config_currency');
			$currency_value = 1.00000;			
		}
      	        $insert_q="INSERT INTO `" . DB_PREFIX . "order_temp` SET card_no ='" . $this->db->escape($data['card_no']) . "',  invoice_prefix = '" . $this->db->escape($invoice_prefix) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($store_name) . "',store_url = '" . $this->db->escape($store_url) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($payment_country) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($payment_zone) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($payment_address_format) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($shipping_country) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($shipping_zone) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($shipping_address_format) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', order_status_id = '" . (int)$data['order_status_id'] . "', affiliate_id  = '" . (int)$data['affiliate_id'] . "', language_id = '" . (int)$this->config->get('config_language_id') . "', currency_id = '" . (int)$currency_id . "', currency_code = '" . $this->db->escape($currency_code) . "', currency_value = '" . (float)$currency_value . "', date_added = NOW(), date_modified = NOW()";
                $this->db->query($insert_q);
      	
                $order_id = $this->db->getLastId();

                if (isset($data['order_product'])) {		
      		foreach ($data['order_product'] as $order_product) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_product_temp SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$order_product['product_id'] . "', name = '" . $this->db->escape($order_product['name']) . "', model = '" . $this->db->escape($order_product['model']) . "', quantity = '" . (int)$order_product['quantity'] . "', price = '" . (float)$order_product['price'] . "', total = '" . (float)$order_product['total'] . "', tax = '" . (float)$order_product['tax'] . "', reward = '" . (int)$order_product['reward'] . "'");
			
				$order_product_id = $this->db->getLastId();				
				
			}
		}
		
		

		// Get the total
		$total = 0;
		$log->write($data['order_total']);
		if (isset($data['order_total'])) {		
      		foreach ($data['order_total'] as $order_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total_temp SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($order_total['code']) . "', title = '" . $this->db->escape($order_total['title']) . "',  `value` = '" . (float)$order_total['value'] . "', sort_order = '" . (int)$order_total['sort_order'] . "'");
			}
			
			$total += $order_total['value'];
		}
// Affiliate
        $affiliate_id = 0;
        $commission = 0;

		
		// Update order total			 
		$this->db->query("UPDATE `" . DB_PREFIX . "order_temp` SET user_id = '".$this->user->getId()."', total = '" . (float)$total . "', affiliate_id = '" . (int)$affiliate_id . "', commission = '" . (float)$commission . "' WHERE order_id = '" . (int)$order_id . "'"); 	
		
		return $order_id;
	}

//end emp


//paytm
//paytm function

	public function SendPayTM($data) 
	{


	$log=new Log('paytm.log');
	$log->write("paytm data");
	//start 
	//header("Pragma: no-cache");
	//header("Cache-Control: no-cache");
	//header("Expires: 0");
	$log->write($data);	
	// following files need to be included

	$checkSum = "";
	$paramList = array();
	$paramList = array();
	$paramList['request'] = array('merchantGuid' => 'a6876e0e-dea4-49b2-a631-503b65deb554',
       	'merchantOrderId' => $data['orderid'],     
        'totalAmount'=>$data['amount'],
        'posId'=>$data['store_id'],
        'industryType'=>'Retail',
        'comment'=>$data['username'],
        'currencyCode'=>'INR');
			         			
	$paramList['version'] = $data['version'];
	$paramList['channel'] = 'POS';
	$paramList['ipAddress'] = '120.138.8.16';
	$paramList['operationType'] = 'WITHDRAW_MONEY';
	$paramList['platformName'] = 'PayTM';

	$log->write($paramList);

	$data_string = json_encode($paramList); 
	//Here checksum string will return by getChecksumFromArray() function.
	$checkSum = $this->getChecksumFromString($data_string,"xBBm6NVzYTYCPWIh");
	$log->write($data_string);	
	$ch = curl_init();                    // initiate curl
	$url = "https://trust-uat.paytm.in/wallet-web/v7/withdraw"; // where you want to post data
	$headers = array('Content-Type:application/json','mid:a6876e0e-dea4-49b2-a631-503b65deb554','checksumhash:'.$checkSum,'phone:'.$data['customer_mob'],'otp:'.$data['totp']);
	$log->write($headers);	
	$ch = curl_init();  // initiate curl
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_POST, 1);  // tell curl you want to post something
	curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string); // define what you want to post
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the output in string format
	 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);     
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$output = curl_exec ($ch); // execute
	$info = curl_getinfo($ch);
	$log->write($info);
	$log->write("Output");
	$log->write($output);
	$data= json_decode($output, true); 	
	$log->write($data);
return $output;
//end
		}

function encrypt_e($input, $ky) {
	$log=new Log("payen.log");
	$log->write("in");
	$key = $ky;
	$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
		$log->write("in1");
	$input = $this->pkcs5_pad_e($input, $size);
	$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
	$iv = "@@@@&&&&####$$$$";
	mcrypt_generic_init($td, $key, $iv);
	$data = mcrypt_generic($td, $input);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
	$data = base64_encode($data);
	return $data;
}

function decrypt_e($crypt, $ky) {

	$crypt = base64_decode($crypt);
	$key = $ky;
	$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
	$iv = "@@@@&&&&####$$$$";
	mcrypt_generic_init($td, $key, $iv);
	$decrypted_data = mdecrypt_generic($td, $crypt);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
	$decrypted_data = pkcs5_unpad_e($decrypted_data);
	$decrypted_data = rtrim($decrypted_data);
	return $decrypted_data;
}

function pkcs5_pad_e($text, $blocksize) {
	$pad = $blocksize - (strlen($text) % $blocksize);
	return $text . str_repeat(chr($pad), $pad);
}

function pkcs5_unpad_e($text) {
	$pad = ord($text{strlen($text) - 1});
	if ($pad > strlen($text))
		return false;
	return substr($text, 0, -1 * $pad);
}

function generateSalt_e($length) {
	$random = "";
	srand((double) microtime() * 1000000);

	$data = "AbcDE123IJKLMN67QRSTUVWXYZ";
	$data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
	$data .= "0FGH45OP89";

	for ($i = 0; $i < $length; $i++) {
		$random .= substr($data, (rand() % (strlen($data))), 1);
	}

	return $random;
}

function checkString_e($value) {
	$myvalue = ltrim($value);
	$myvalue = rtrim($myvalue);
	if ($myvalue == 'null')
		$myvalue = '';
	return $myvalue;
}

function getChecksumFromArray($arrayList, $key, $sort=1) {
	if ($sort != 0) {
		ksort($arrayList);
	}
	$str = $this->getArray2Str($arrayList);
	$salt = $this->generateSalt_e(4);
	$finalString = $str . "|" . $salt;
	$hash = hash("sha256", $finalString);
	$hashString = $hash . $salt;
	$checksum = $this->encrypt_e($hashString, $key);
	return $checksum;
}
function getChecksumFromString($str, $key) {
	$log=new Log("payhash.log");
	$log->write("in");	
	$salt = $this->generateSalt_e(4);
	$log->write("in1");	
	$finalString = $str . "|" . $salt;
	$hash = hash("sha256", $finalString);
		$log->write("in2");
	$hashString = $hash . $salt;
	$checksum = $this->encrypt_e($hashString, $key);
		$log->write("out");
		$log->write($checksum);
	return $checksum;
}

function verifychecksum_e($arrayList, $key, $checksumvalue) {
	$arrayList =$this->removeCheckSumParam($arrayList);
	ksort($arrayList);
	$str = $this->getArray2Str($arrayList);
	$paytm_hash = $this->decrypt_e($checksumvalue, $key);
	$salt = substr($paytm_hash, -4);

	$finalString = $str . "|" . $salt;

	$website_hash = hash("sha256", $finalString);
	$website_hash .= $salt;

	$validFlag = "FALSE";
	if ($website_hash == $paytm_hash) {
		$validFlag = "TRUE";
	} else {
		$validFlag = "FALSE";
	}
	return $validFlag;
}

function verifychecksumFromstr_e($str, $key, $checksumvalue) {
	$paytm_hash = $this->decrypt_e($checksumvalue, $key);
	$salt = substr($paytm_hash, -4);

	$finalString = $str . "|" . $salt;

	$website_hash = hash("sha256", $finalString);
	$website_hash .= $salt;

	$validFlag = "FALSE";
	if ($website_hash == $paytm_hash) {
		$validFlag = "TRUE";
	} else {
		$validFlag = "FALSE";
	}
	return $validFlag;
}

function getArray2Str($arrayList) {
	$paramStr = "";
	$flag = 1;
	foreach ($arrayList as $key => $value) {
		if ($flag) {
			$paramStr .=$this->checkString_e($value);
			$flag = 0;
		} else {
			$paramStr .= "|" . $this->checkString_e($value);
		}
	}
	return $paramStr;
}

function redirect2PG($paramList, $key) {
	$hashString = $this->getchecksumFromArray($paramList);
	$checksum = $this->encrypt_e($hashString, $key);
}

function removeCheckSumParam($arrayList) {
	if (isset($arrayList["CHECKSUMHASH"])) {
		unset($arrayList["CHECKSUMHASH"]);
	}
	return $arrayList;
}

function getTxnStatus($requestParamList) {
	return $this->callAPI(PAYTM_STATUS_QUERY_URL, $requestParamList);
}

function initiateTxnRefund($requestParamList) {
	$CHECKSUM = $this->getChecksumFromArray($requestParamList,PAYTM_MERCHANT_KEY,0);
	$requestParamList["CHECKSUM"] = $CHECKSUM;
	return $this->callAPI(PAYTM_REFUND_URL, $requestParamList);
}

function callAPI($apiURL, $requestParamList) {
	$jsonResponse = "";
	$responseParamList = array();
	$JsonData =json_encode($requestParamList);
	$postData = 'JsonData='.urlencode($JsonData);
	$ch = curl_init($apiURL);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                         
	'Content-Type: application/json', 
	'Content-Length: ' . strlen($postData))                                                                       
	);  
	$jsonResponse = curl_exec($ch);   
	$responseParamList = json_decode($jsonResponse,true);
	return $responseParamList;
}


//end paytm






}
?>