<?php
class ModelSettingStore extends Model {
	public function addStore($data) {
		$this->event->trigger('pre.admin.store.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "store SET name = '" . $this->db->escape($data['config_name']) . "', `url` = '" . $this->db->escape($data['config_url']) . "', `ssl` = '" . $this->db->escape($data['config_ssl']) . "'");

		$store_id = $this->db->getLastId();

		// Layout Route
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE store_id = '0'");

		foreach ($query->rows as $layout_route) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "layout_route SET layout_id = '" . (int)$layout_route['layout_id'] . "', route = '" . $this->db->escape($layout_route['route']) . "', store_id = '" . (int)$store_id . "'");
		}

		$this->cache->delete('store');

		$this->event->trigger('post.admin.store.add', $store_id);

		return $store_id;
	}

	public function editStore($store_id, $data) {
		$this->event->trigger('pre.admin.store.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "store SET name = '" . $this->db->escape($data['config_name']) . "', `url` = '" . $this->db->escape($data['config_url']) . "', `ssl` = '" . $this->db->escape($data['config_ssl']) . "' WHERE store_id = '" . (int)$store_id . "'");

		$this->cache->delete('store');

		$this->event->trigger('post.admin.store.edit', $store_id);
	}

	public function deleteStore($store_id) {
		$this->event->trigger('pre.admin.store.delete', $store_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "store WHERE store_id = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "layout_route WHERE store_id = '" . (int)$store_id . "'");

		$this->cache->delete('store');

		$this->event->trigger('post.admin.store.delete', $store_id);
	}

	public function getStore($store_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "store WHERE store_id = '" . (int)$store_id . "'");

		return $query->row;
	}


public function getStoreInv($store_id) {
$store_data = array(array());
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "store WHERE store_id = '" . (int)$store_id . "'");


foreach ($query->rows as $storedb) {
                         $store_datan =array(
			'store_id' => $storedb['store_id'],
			'name'     => $storedb['name'],
			'url'      => $storedb['url']
			
		);
			//array_push($store_data,  $store_datan); 
$store_data=array($store_datan);
                                         							
                        }


		return $store_data;
	}




	public function getStores($data = array()) {
		//$store_data = $this->cache->get('store');
$mcrypt = new MCrypt();
$storeid =$mcrypt->decrypt($this->request->post['store_id']);
$district =$mcrypt->decrypt($this->request->post['district']);
		if (!$store_data) {
                     $store_data = array(array());
                                       $store_data = array(array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG
			
		));
		$query = $this->db->query("SELECT * FROM oc_store 
WHERE store_id IN(select ocu.store_id from ak_customer_emp_map akm
LEFT JOIN oc_user ocu ON ocu.customer_id = akm.CUSTOMER_ID
WHERE akm.GEO_LEVEL_ID = 4 AND akm.GEO_ID = ".$district." AND ocu.user_group_id = 10)
ORDER BY url");
if(empty($query->rows)){
$query=$this->db->query("select store_id,`name`,url from oc_store limit 1");
}

                        foreach ($query->rows as $storedb) {
                         $store_datan =array(
			'store_id' => $storedb['store_id'],
			'name'     => $storedb['name'],
			'url'      => $storedb['url']
			
		);
			array_push($store_data,  $store_datan);                                          							
                        }
			//$this->cache->set('store', $store_data);
		}

		return $store_data;
	}

	public function getTotalStores() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store");

		return $query->row['total'];
	}

	public function getTotalStoresByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_layout_id' AND `value` = '" . (int)$layout_id . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByLanguage($language) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_language' AND `value` = '" . $this->db->escape($language) . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByCurrency($currency) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_currency' AND `value` = '" . $this->db->escape($currency) . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByCountryId($country_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_country_id' AND `value` = '" . (int)$country_id . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByZoneId($zone_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_zone_id' AND `value` = '" . (int)$zone_id . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByCustomerGroupId($customer_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_customer_group_id' AND `value` = '" . (int)$customer_group_id . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByInformationId($information_id) {
		$account_query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_account_id' AND `value` = '" . (int)$information_id . "' AND store_id != '0'");

		$checkout_query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_checkout_id' AND `value` = '" . (int)$information_id . "' AND store_id != '0'");

		return ($account_query->row['total'] + $checkout_query->row['total']);
	}

	public function getTotalStoresByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_order_status_id' AND `value` = '" . (int)$order_status_id . "' AND store_id != '0'");

		return $query->row['total'];
	}

	//transport store
	public function getTransport($data = array()) {

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "transport ORDER BY name");
                                                          							                        
				

		return $query->rows;
	}	
}