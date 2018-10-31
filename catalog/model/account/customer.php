<?php
class ModelAccountCustomer extends Model {
	public function addCustomer($data) {
		$this->event->trigger('pre.customer.add', $data);

		if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $data['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()");

		$customer_id = $this->db->getLastId();

		$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['address']) ? serialize($data['custom_field']['address']) : '') . "'");

		$address_id = $this->db->getLastId();

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");

		$this->load->language('mail/customer');

		$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

		$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";

		if (!$customer_group_info['approval']) {
			$message .= $this->language->get('text_login') . "\n";
		} else {
			$message .= $this->language->get('text_approval') . "\n";
		}

		$message .= $this->url->link('account/login', '', 'SSL') . "\n\n";
		$message .= $this->language->get('text_services') . "\n\n";
		$message .= $this->language->get('text_thanks') . "\n";
		$message .= $this->config->get('config_name');

		$mail = new Mail($this->config->get('config_mail'));
		$mail->setTo($data['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject($subject);
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();

		// Send to main admin email if new account email is enabled
		if ($this->config->get('config_account_mail')) {
			$message  = $this->language->get('text_signup') . "\n\n";
			$message .= $this->language->get('text_website') . ' ' . $this->config->get('config_name') . "\n";
			$message .= $this->language->get('text_firstname') . ' ' . $data['firstname'] . "\n";
			$message .= $this->language->get('text_lastname') . ' ' . $data['lastname'] . "\n";
			$message .= $this->language->get('text_customer_group') . ' ' . $customer_group_info['name'] . "\n";
			$message .= $this->language->get('text_email') . ' '  .  $data['email'] . "\n";
			$message .= $this->language->get('text_telephone') . ' ' . $data['telephone'] . "\n";

			$mail->setTo($this->config->get('config_email'));
			$mail->setSubject(html_entity_decode($this->language->get('text_new_customer'), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();

			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', $this->config->get('config_mail_alert'));

			foreach ($emails as $email) {
				if (utf8_strlen($email) > 0 && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}

		$this->event->trigger('post.customer.add', $customer_id);

		return $customer_id;
	}

	public function editCustomer($data) {
		$this->event->trigger('pre.customer.edit', $data);

		$customer_id = $this->customer->getId();

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "' WHERE customer_id = '" . (int)$customer_id . "'");

		$this->event->trigger('post.customer.edit', $customer_id);
	}

	public function editPassword($email, $password) {
		$this->event->trigger('pre.customer.edit.password');

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		$this->event->trigger('post.customer.edit.password');
	}

	public function editNewsletter($newsletter) {
		$this->event->trigger('pre.customer.edit.newsletter');

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");

		$this->event->trigger('post.customer.edit.newsletter');
	}

	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}


	public function getsale($uid,$sdate)
		{

		$log=new Log("mysale".date('Y-m-d').".log");
		$log->write("select product_id,name,sum(quantity) as quantity,(price+tax) as price  from  oc_order_product  where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".date('Y-m-d')."' AND order_status_id='5') group by product_id");
		if(empty($sdate))
		{
			$query = $this->db->query("select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".date('Y-m-d')."' AND payment_method='Cash' AND order_status_id='5') group by product_id");
		}else{
			$query = $this->db->query("select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".$sdate."' AND payment_method='Cash' AND order_status_id='5') group by product_id");

			}

					return $query->rows;
		
		}


	public function getsaleTagged($uid,$sdate)
		{

		$log=new Log("mysale".date('Y-m-d').".log");
		$log->write("select product_id,name,sum(quantity) as quantity,(price+tax) as price  from  oc_order_product  where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".date('Y-m-d')."' AND (payment_method ='Tagged' or payment_method ='Tagged Cash' ) AND order_status_id='5') group by product_id");
		if(empty($sdate))
		{
			$query = $this->db->query("select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".date('Y-m-d')."' AND (payment_method ='Tagged' or payment_method ='Tagged Cash' ) AND order_status_id='5') group by product_id");
		}else{
			$query = $this->db->query("select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".$sdate."' AND (payment_method ='Tagged' or payment_method ='Tagged Cash' ) AND order_status_id='5') group by product_id");

			}

					return $query->rows;
		
		}


	public function getsaleSub($uid,$sdate)
		{

		$log=new Log("mysale".date('Y-m-d').".log");
		$log->write("select product_id,name,sum(quantity) as quantity,(price+tax) as price  from  oc_order_product  where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".date('Y-m-d')."' AND payment_method='Subsidy' AND order_status_id='5') group by product_id");
		if(empty($sdate))
		{
			$query = $this->db->query("select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".date('Y-m-d')."' AND payment_method='Subsidy' AND order_status_id='5') group by product_id");
		}else{
			$query = $this->db->query("select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".$sdate."' AND payment_method='Subsidy' AND order_status_id='5') group by product_id");

			}

					return $query->rows;
		
		}

	public function getsaleChq($uid,$sdate)
		{

		$log=new Log("mysale".date('Y-m-d').".log");
		$log->write("select product_id,name,sum(quantity) as quantity,(price+tax) as price  from  oc_order_product  where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".date('Y-m-d')."' AND payment_method='Cheque' AND order_status_id='5') group by product_id");
		if(empty($sdate))
		{
			$query = $this->db->query("select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".date('Y-m-d')."' AND payment_method='Cheque' AND order_status_id='5') group by product_id");
		}else{
			$query = $this->db->query("select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".$sdate."' AND payment_method='Cheque' AND order_status_id='5') group by product_id");

			}

					return $query->rows;
		
		}

	public function getCrops() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "crop ");

		return $query->rows;
	}

	public function getCustomerByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getCustomerByToken($token) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = ''");

		return $query->row;
	}

	public function getTotalCustomersByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}

	public function getIps($customer_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->rows;
	}



	public function addbankTrans($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "bank_transaction SET bank_id = '" . $data['bank_id'] . "', bank_name = '" . $data['bank_name'] . "', amount= '" . $this->db->escape($data['amount']) . "',user_id= '" . $this->db->escape($data['user_id']) . "',store_id= '" . $this->db->escape($data['store_id']) . "', date_added = NOW()");
		$tid = $this->db->getLastId();
		return $tid;
	}



	public function getanktrans($uid,$sid) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "bank_transaction` as bt LEFT JOIN `" . DB_PREFIX . "store` as st on st.store_id=bt.store_id  WHERE user_id='".$uid."' order by date_added desc limit 15");
		return $query->rows;
	}


	public function getank() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "bank` WHERE IsActive='1'");

		return $query->rows;
	}
		public function gethelp() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "help` WHERE IsActive='1'");

		return $query->rows;
	}		

	public function isBanIp($ip) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ban_ip` WHERE ip = '" . $this->db->escape($ip) . "'");

		return $query->num_rows;
	}
	
	public function addLoginAttempt($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_login WHERE email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");
		
		if (!$query->num_rows) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_login SET email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', total = 1, date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "customer_login SET total = (total + 1), date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE customer_login_id = '" . (int)$query->row['customer_login_id'] . "'");
		}			
	}	
	
	public function getLoginAttempts($email) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		return $query->row;
	}

	public function getLastOrderDate($cid) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE customer_id = '" . $this->db->escape(utf8_strtolower($cid)) . "' order by date_added desc limit 1");
		return $query->row;
	}



	public function getUserSale($uid,$sdate) {
		$log=new Log("mysale.log");
		$log->write("SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('sub_total') AND o.order_status_id = '5' AND DATE(o.date_added)='".$sdate."' o.user_id='".$uid."'");	
				$query = $this->db->query("SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('sub_total','tax') AND o.order_status_id = '5' AND DATE(o.date_added)='".$sdate."' AND o.payment_method='Cash' AND o.user_id='".$uid."'");
		return $query->row;
	}
	public function getUserSaleTagged($uid,$sdate) {
		$log=new Log("mysale-".date('Y-m-d').".log");
		$log->write("SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('sub_total') AND o.order_status_id = '5' AND DATE(o.date_added)='".$sdate."' AND (o.payment_method ='Tagged' or o.payment_method ='Tagged Cash' ) AND  o.user_id='".$uid."'");	
				$query = $this->db->query("SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('sub_total','tax') AND o.order_status_id = '5' AND DATE(o.date_added)='".$sdate."' AND (o.payment_method='Tagged' or o.payment_method ='Tagged Cash') AND o.user_id='".$uid."'");
		return $query->row;
	}

		public function getUserSaleSub($uid,$sdate) {
		$log=new Log("mysale.log");
		$log->write("SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('sub_total') AND o.order_status_id = '5' AND DATE(o.date_added)='".$sdate."' AND o.payment_method='Subsidy' AND  o.user_id='".$uid."'");	
				$query = $this->db->query("SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('sub_total','tax') AND o.order_status_id = '5' AND DATE(o.date_added)='".$sdate."' AND o.payment_method='Subsidy' AND o.user_id='".$uid."'");
		return $query->row;
	}


	public function getUserSaleChq($uid,$sdate) {
		$log=new Log("mysale.log");
		$log->write("SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('sub_total') AND o.order_status_id = '5' AND DATE(o.date_added)='".$sdate."' AND o.payment_method='Cheque' AND  o.user_id='".$uid."'");	
				$query = $this->db->query("SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('sub_total','tax') AND o.order_status_id = '5' AND DATE(o.date_added)='".$sdate."' AND o.payment_method='Cheque' AND o.user_id='".$uid."'");
		return $query->row;
	}

	public function deleteLoginAttempts($email) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}


	
}