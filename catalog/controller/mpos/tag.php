<?php

class Controllermpostag extends Controller{

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

function tag(){



		$log=new Log("tag-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();

		
		$log->write($this->request->post);
		$log->write($this->request->get);
		$uid=$mcrypt->decrypt($this->request->post['username']);
		$sid=$mcrypt->decrypt($this->request->post['store_id']);
		$this->adminmodel('tag/order');

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_order_status'])) {
			$filter_order_status = $this->request->get['filter_order_status'];
		} else {
			$filter_order_status = null;
		}

		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}

		if (isset($this->request->get['sdate'])) {
			$filter_date_added = $mcrypt->decrypt($this->request->get['sdate']);
					$log->write($filter_date_added);
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['pdate'])) {
			$filter_date_potential = $mcrypt->decrypt($this->request->get['pdate']);
			$log->write($filter_date_potential);

		} else {
			$filter_date_potential = null;
		}


		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['products'] = array();

		$filter_data = array(
			'filter_order_id'      => $filter_order_id,
			'filter_customer'	   => $filter_customer,
			'filter_order_status'  => '1',
			'filter_total'         => $filter_total,
			'filter_date_added'    => $filter_date_added,
			'filter_date_potential' => $filter_date_potential,
			'filter_store_id'	=>$sid,
			'sort'			=>$sort,
			'order'                => $order,
			'start'                => $mcrypt->decrypt($this->request->post['start']),
			'limit'                => $this->config->get('config_limit_admin')
		);


		$results = $this->model_tag_order->getOrders($filter_data);

		foreach ($results as $result) { 
			$data['products'][] = array(
				'id'      => $mcrypt->encrypt($result['order_id']),
				'name'      => $mcrypt->encrypt($result['customer']),
				'pirce'        => $mcrypt->encrypt($result['status']),
                                'quantity'     => $mcrypt->encrypt($result['telephone']),
                                'store_name'    => $mcrypt->encrypt($result['store_name']),
				'total'         => $mcrypt->encrypt($this->currency->format($result['total'], $result['currency_code'], $result['currency_value'])),
				'date_added'    => $mcrypt->encrypt(date($this->language->get('date_format_short'), strtotime($result['date_added']))),
				'date' => $mcrypt->encrypt(date($this->language->get('date_format_short'), strtotime($result['date_potential'])))
				);
		}

				
				$keys = array(
			'store_id',
			'limit',
			'start',
			'username'		
		);

foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
		$log->write($this->request->post);
		$this->response->setOutput(json_encode($data));

}


}

?>