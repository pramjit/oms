<?php
class Controllermpossale extends Controller {


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


public function mysale()
{

		$log=new Log("mysale.log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
			$uid=$mcrypt->decrypt($this->request->post['username']);
			$sid=$mcrypt->decrypt($this->request->post['store_id']);
		if(isset($this->request->get['sdate'])){
			$sdate=$mcrypt->decrypt($this->request->get['sdate']);
	}
	else{
	}
             		$this->load->model('account/customer');
	$jsons = $this->model_account_customer->getsale($uid,$sdate);
		$log->write($jsons);

	$log->write("Total check");

	//$total=$this->model_account_customer->getUserSale($uid);

	//$log->write($total);
	if(empty($sdate))
	{
		$sdate=date('Y-m-d');
	}

	$json['total']=$mcrypt->encrypt(round($this->model_account_customer->getUserSale($uid,$sdate)["total"],2,PHP_ROUND_HALF_UP));			  
	foreach ($jsons as $ids) {		
	$json['products'][] = array(
                        'id'       =>$mcrypt->encrypt($ids['product_id']),
                        'name'       =>$mcrypt->encrypt($ids['name']),
			'pirce' =>$mcrypt->encrypt( $ids['price']+$ids['tax']),
			'quantity' => $mcrypt->encrypt($ids['quantity']),
						
                                            );
	}


		$this->response->setOutput(json_encode($json));

}

//tagged sales ---getsaleTagged

public function mysaletag()
{

		$log=new Log("mysale-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
			$uid=$mcrypt->decrypt($this->request->post['username']);
			$sid=$mcrypt->decrypt($this->request->post['store_id']);
		if(isset($this->request->get['sdate']))
		{
			$sdate=$mcrypt->decrypt($this->request->get['sdate']);
		}
	else{
	}
             		$this->load->model('account/customer');
	$jsons = $this->model_account_customer->getsaleTagged($uid,$sdate);
		$log->write($jsons);

	$log->write("Total check");

	if(empty($sdate))
	{
		$sdate=date('Y-m-d');
	}
	
	
	$json['total']=$mcrypt->encrypt(round($this->model_account_customer->getUserSaleTagged($uid,$sdate)["total"],2,PHP_ROUND_HALF_UP));			  
		$log->write(round($this->model_account_customer->getUserSaleTagged($uid,$sdate)["total"],2,PHP_ROUND_HALF_UP));
	foreach ($jsons as $ids) {		
	$json['products'][] = array(
                        'id'       =>$mcrypt->encrypt($ids['product_id']),
                        'name'       =>$mcrypt->encrypt($ids['name']),
			'pirce' =>$mcrypt->encrypt( $ids['price']+$ids['tax']),
			'quantity' => $mcrypt->encrypt($ids['quantity']),
						
                                            );
	}


		$this->response->setOutput(json_encode($json));

}


function customersale()
{

		$log=new Log("customersale.log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
			$uid=$mcrypt->decrypt($this->request->post['username']);
			$sid=$mcrypt->decrypt($this->request->post['store_id']);

		if (isset($this->request->get['sdate'])) {
			$filter_date_start = $this->request->get['sdate'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['edate'])) {
			$filter_date_end = $this->request->get['edate'];
		} else {
			$filter_date_end = '';
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 5;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}


		$this->adminmodel('report/customer');

		$data['products'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_order_status_id' => $filter_order_status_id,
			'filter_store'		=> $sid,	
			'start'                  => $mcrypt->decrypt($this->request->post['start']),
			'limit'                  => $this->config->get('config_limit_admin')
		);


		$results = $this->model_report_customer->getOrders($filter_data);

		foreach ($results as $result) {
			$data['products'][] = array(
				'id'       => $mcrypt->encrypt($result['customer']),
				'name'          => $mcrypt->encrypt($result['email']),				
				'pirce'         => $mcrypt->encrypt($result['orders']),
				'quantity'       => $mcrypt->encrypt($result['products']),
				'total'          =>$mcrypt->encrypt( $this->currency->format($result['total'], $this->config->get('config_currency')))				
			);
		}
		$this->response->setOutput(json_encode($data));

}



public function mysalesub()
{

		$log=new Log("mysale.log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
			$uid=$mcrypt->decrypt($this->request->post['username']);
			$sid=$mcrypt->decrypt($this->request->post['store_id']);
		if(isset($this->request->get['sdate'])){
			$sdate=$mcrypt->decrypt($this->request->get['sdate']);
	}
	else{
	}
             		$this->load->model('account/customer');
	$jsons = $this->model_account_customer->getsaleSub($uid,$sdate);
		$log->write($jsons);

	$log->write("Total check");

	if(empty($sdate))
	{
		$sdate=date('Y-m-d');
	}

	$json['total']=$mcrypt->encrypt(round($this->model_account_customer->getUserSaleSub($uid,$sdate)["total"],2,PHP_ROUND_HALF_UP));			  
	foreach ($jsons as $ids) {		
	$json['products'][] = array(
                        'id'       =>$mcrypt->encrypt($ids['product_id']),
                        'name'       =>$mcrypt->encrypt($ids['name']),
			'pirce' =>$mcrypt->encrypt( $ids['price']+$ids['tax']),
			'quantity' => $mcrypt->encrypt($ids['quantity']),
						
                                            );
	}


		$this->response->setOutput(json_encode($json));

}


public function mysalechq()
{

		$log=new Log("mysale.log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
			$uid=$mcrypt->decrypt($this->request->post['username']);
			$sid=$mcrypt->decrypt($this->request->post['store_id']);
		if(isset($this->request->get['sdate'])){
			$sdate=$mcrypt->decrypt($this->request->get['sdate']);
	}
	else{
	}
             		$this->load->model('account/customer');
	$jsons = $this->model_account_customer->getsaleChq($uid,$sdate);
		$log->write($jsons);

	$log->write("Total check");

	if(empty($sdate))
	{
		$sdate=date('Y-m-d');
	}

	$json['total']=$mcrypt->encrypt(round($this->model_account_customer->getUserSaleChq($uid,$sdate)["total"],2,PHP_ROUND_HALF_UP));			  
	foreach ($jsons as $ids) {		
	$json['products'][] = array(
                        'id'       =>$mcrypt->encrypt($ids['product_id']),
                        'name'       =>$mcrypt->encrypt($ids['name']),
			'pirce' =>$mcrypt->encrypt( $ids['price']+$ids['tax']),
			'quantity' => $mcrypt->encrypt($ids['quantity']),
						
                                            );
	}


		$this->response->setOutput(json_encode($json));

}





}