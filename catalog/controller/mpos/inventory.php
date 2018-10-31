<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class Controllermposinventory extends Controller{


    public function adminmodel($model) {
      
        $admin_dir = DIR_SYSTEM;
        $admin_dir = str_replace('system/','admin/',$admin_dir);
        $file = $admin_dir . 'model/' . $model . '.php';
        $class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
      
        if (file_exists($file)) {
            include_once($file);
            $this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
        } else {
            trigger_error('Error: Could not load model ' . $model . '!');
            exit();               
        }
    }

    public function orderlist()
    {
	$mcrypt=new MCrypt();
	$this->adminmodel('inventory/purchase_order');
	
        if (isset($this->request->get['page'])) {
            $page = $mcrypt->decrypt($this->request->get['page']);
	} else {
            $page = 1;
	}
            $log=new Log("inv.log");
            $start = ($page-1)*20;
            $limit = 20;
            $uid=$mcrypt->decrypt($this->request->post['username']);
            $log->write($this->model_inventory_purchase_order->getListRecStore($uid,$start,$limit));
            $data['order_list'] =$mcrypt->encrypt(serialize( $this->model_inventory_purchase_order->getListRecStore($uid,$start,$limit)));
		/*getting the list of the orders*/
            $total_orders = $this->model_inventory_purchase_order->getTotalOrders();
            $log->write($total_orders);
		//getting pages
            $data['results'] =$mcrypt->encrypt( sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin'))));
            $this->response->setOutput(json_encode($data));	
    }

    //************************** AM RCV ORDER LIST *********************************//
    public function orderlist_mo()
    {
	$mcrypt=new MCrypt();
	$this->adminmodel('inventory/purchase_order');
	
        if (isset($this->request->get['page'])) {
		$page = $mcrypt->decrypt($this->request->get['page']);
	} else {
		$page = 1;
	}
	$log=new Log("MO_RCV_ORDER.log");
	$start = ($page-1)*20;
	$limit = 20;
	$uid=$mcrypt->decrypt($this->request->post['username']);
	$log->write($this->model_inventory_purchase_order->getListRecStoreMo($uid,$start,$limit));
	$data['order_list'] =$mcrypt->encrypt(serialize( $this->model_inventory_purchase_order->getListRecStoreMo($uid,$start,$limit)));
		/*getting the list of the orders*/
            $total_orders = $this->model_inventory_purchase_order->getTotalOrdersMo();
            $log->write($total_orders);
		//getting pages
            $data['results'] =$mcrypt->encrypt( sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin'))));
            $this->response->setOutput(json_encode($data));	

	}
 //************************** AM RCV ORDER LIST END *****************************//       
 //************************** WS Order List Start *******************************//
    public function orderlist_ws()
    {
        /*getting the list of the orders*/
        $mcrypt=new MCrypt();
        $this->adminmodel('inventory/purchase_order');
        if (isset($this->request->get['page'])) {
                $page = $mcrypt->decrypt($this->request->get['page']);
        } else {
                $page = 1;
        }
        $log=new Log("ws_orderlist.log");
        $sid = $mcrypt->decrypt($this->request->post['sid']);
        $uid=$mcrypt->decrypt($this->request->post['username']);


        $start = ($page-1)*20;
        $limit = 20;

        $log->write("Store Id:".$sid);
        $log->write($this->model_inventory_purchase_order->getListRecStoreWs($sid,$start,$limit));
        $data['order_list'] =$mcrypt->encrypt(serialize( $this->model_inventory_purchase_order->getListRecStoreWs($sid,$start,$limit)));
        /*getting the list of the orders*/

        //getting total orders

        $total_orders = $this->model_inventory_purchase_order->getTotalOrdersWs();
        $log->write($total_orders);
	$data['results'] =$mcrypt->encrypt( sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin'))));
        $this->response->setOutput(json_encode($data));	
    }
//*************************** AM Order List Start********************************//
        public function orderlist_am()
	{ 
            /*getting the list of the orders*/
            $mcrypt=new MCrypt();
            $this->adminmodel('inventory/purchase_order');
            if (isset($this->request->get['page'])) {
            $page = $mcrypt->decrypt($this->request->get['page']);
            } else {
                    $page = 1;
            }
            $log=new Log("AM_PENDING_IND.log");
            $start = ($page-1)*20;
            $limit = 20;
            $uid=$mcrypt->decrypt($this->request->post['username']);
            //$uid=405;
            $log->write($this->model_inventory_purchase_order->getListRecStoreAm($uid,$start,$limit));
            //Added New
            $newdata=$this->model_inventory_purchase_order->getListRecStoreAm($uid,$start,$limit);
            if($newdata)
            {
                foreach($newdata as $ind){
                if(empty($ind['status_date'])){$stsdt='0000-00-00';}else{$stsdt=$ind['status_date'];}
                $data['order_list'][] = array(
                'id' => $mcrypt->encrypt($ind['id']),
                'order_date' => $mcrypt->encrypt($ind['order_date']),
                'order_sup_send' => $mcrypt->encrypt($ind['order_sup_send']),
                'delete_bit' => $mcrypt->encrypt($ind['delete_bit']),
                'user_id' => $mcrypt->encrypt($ind['user_id']),
                'receive_date' => $mcrypt->encrypt($ind['receive_date']),
                'receive_bit' => $mcrypt->encrypt($ind['receive_bit']),
                'pending_bit' => $mcrypt->encrypt($ind['pending_bit']),
                'pre_supplier_bit' => $mcrypt->encrypt($ind['pre_supplier_bit']),
                'order_status' => $mcrypt->encrypt($ind['order_status']),
                'status_date' => $mcrypt->encrypt($stsdt),
                'store_id' => $mcrypt->encrypt($ind['store_id']),
                'remarks' => $mcrypt->encrypt($ind['remarks']),
                'receivetype' => $mcrypt->encrypt($ind['receivetype'])
                );
                }
            }
            else {$data['order_list']="NA";}
                
            $total_orders = $this->model_inventory_purchase_order->getTotalOrdersAm();
            $log->write($total_orders);
            $data['results'] =$mcrypt->encrypt( sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin'))));
            $this->response->setOutput(json_encode($data));	

	}
    //*************************** AM Order List End *********************************//
    //*************************** AM Update Order Status********************************//
        public function orderstatus_am()
	{
								
            /*getting the list of the orders*/
            $mcrypt=new MCrypt();
            $log=new Log("AM_UP_IND_STS".date('Y-m-d').".log");
            $this->adminmodel('inventory/purchase_order');
            if (isset($this->request->get['order_id'])) {
                $order_id = $mcrypt->decrypt($this->request->get['order_id']);
                $order_sts = $mcrypt->decrypt($this->request->get['order_sts']);
                $order_rmk = $mcrypt->decrypt($this->request->get['remarks']);
            }
                $uid=$mcrypt->decrypt($this->request->post['username']);
                
                
		$upIndSts = $this->model_inventory_purchase_order->updateOrderStatusAm($uid,$order_id,$order_sts,$order_rmk);
		$log->write($upIndSts);
                if($upIndSts){
                    
                    if($upIndSts==1 && $order_sts==2){ // Message for Indent Approved
                        
                        $msgDtls=$this->model_inventory_purchase_order->msgDtls($order_id);
                        $log->write($msgDtls);
                        $mInd=$msgDtls['IND_ID'];
                        $mQty=$msgDtls['IND_QTY'];
                        $StoId=$msgDtls['IND_DEL'];
                        $AmId=$uid;
                        $mMoMob='91'.$msgDtls['MO_MOB'];
                        $mAmName=$msgDtls['AM_NAME'];
                        $mDlMob='91'.$msgDtls['DEL_MOB'];
                        $mDlName=$msgDtls['DEL_NAME'];
                        $mDlDist=$msgDtls['DEL_DIST'];
                        
                        $InMob_I='918392913500'; // for fixed
                        $InMob_II='917830800900'; // for fixed
                        //$InMob_III='919971813600'; // for test    
                        //$InMob_IV='919821497662'; // for test  
                        $numbers = array($mMoMob, $mDlMob, $InMob_I, $InMob_II); 
                        //$numbers = array($InMob_III,$InMob_IV); 
                        //============================ Approve Message Start==========================//
                                            
                            //$textmsg='Your Order of TotalKgs KGS has been Approved. Your Order ID is OrderId .';
                            $textmsg='Order of M/s PartyName , DistName of OrdQty KGS has been Approved by AmName. Your Order ID is OrdId.';
                            $textmsg=str_replace('PartyName',$mDlName,$textmsg);
                            $textmsg=str_replace('DistName',$mDlDist,$textmsg);
                            $textmsg=str_replace('OrdQty',$mQty,$textmsg);
                            $textmsg=str_replace('AmName',$mAmName,$textmsg);
                            $textmsg=str_replace('OrdId',$mInd,$textmsg);
                            
                            $log->write("Message Sent:".$textmsg);
                            $message = rawurlencode($textmsg);
                            $numbers = implode(',', $numbers);
                            //$numbers2 = implode(',', $numbers2);
                            //Authorization
                            $apiKey = urlencode('bDDRD5CawD8-vZaDs0E2AFnEOi1BBmyE90KujZbjTc');
                            $sender = urlencode('KAIBLY');
                        
                            $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
                        
                            // Send the POST request with cURL
                            $ch = curl_init('https://api.textlocal.in/send/');
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            $responseBody = curl_exec($ch);
                            if($responseBody === false) {
                                $responseBody=curl_error($ch);
                            }
                            curl_close($ch);
                            $log->write("Message Sent:".$responseBody);	
                            
                            $type='AM_APV_PO';
                            $msgMaster = $this->model_inventory_purchase_order->msgMaster($mInd,$type,$AmId,$StoId,$textmsg,$numbers,$responseBody);
                            $log->write("Message2DB:".$msgMaster);
                        //============================ Approve Message End ===========================//
                        $return='1';

                    }else if($upIndSts==1 && $order_sts==7){    

                        $return= '2';

                    }else if($upIndSts==1 && $order_sts==5){

                        $return= '5';

                    }else{

                        $return= '3';

                    }
                }
                $this->response->addHeader('Content-Type:application/json');
		$this->response->setOutput($return);	

	}
//*************************** AM Order List End *********************************//
//*************************** AM Order List Receive Start********************************//
        public function rcvorderlist_am()
	{
				/*getting the list of the orders*/
                $mcrypt=new MCrypt();
		$this->adminmodel('inventory/purchase_order');
		
		if (isset($this->request->get['page'])) {
			$page = $mcrypt->decrypt($this->request->get['page']);
		} else {
			$page = 1;
		}
		$log=new Log("AM_RCV_CON.log");
		$start = ($page-1)*20;
		$limit = 20;
		$uid=$mcrypt->decrypt($this->request->post['username']);
		$log->write($this->model_inventory_purchase_order->getListRecStoreAmRcv($uid,$start,$limit));
		$data['order_list'] =$mcrypt->encrypt(serialize( $this->model_inventory_purchase_order->getListRecStoreAmRcv($uid,$start,$limit)));
		/*getting the list of the orders*/
				//getting total orders
		
		$total_orders = $this->model_inventory_purchase_order->getTotalOrdersAmRcv();
		$log->write($total_orders);
		//getting pages

		
		//getting pages
		
		
		
		$data['results'] =$mcrypt->encrypt( sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin'))));

		$this->response->setOutput(json_encode($data));	

	}
//*************************** AM Order List Receive End *********************************//
/*----------------------------view_order_details function starts here------------*/
	
	public function order_details()
	{
				 $mcrypt=new MCrypt();
//$mcrypt->decrypt
		$order_id = $mcrypt->decrypt($this->request->get['order_id']);							
		$this->adminmodel('inventory/purchase_order');
		$data['order_information'] =$this->model_inventory_purchase_order->view_order_details($order_id);		
//print_r($data['order_information']['products']);
		$this->response->setOutput(json_encode($data['order_information']['products']));
		
	}
	public function order_details_mo()
	{
				 $mcrypt=new MCrypt();
//$mcrypt->decrypt
		$order_id = $mcrypt->decrypt($this->request->get['order_id']);							
		$this->adminmodel('inventory/purchase_order');
		$data['order_information'] =$this->model_inventory_purchase_order->view_order_details_mo($order_id);		
//print_r($data['order_information']['products']);
		$this->response->setOutput(json_encode($data['order_information']['products']));
		
	}
	/*----------------------------view_order_details function ends here--------------*/

	//******************************* RECEIVE ORDER MO *********************************//
        public function receive_order_mo()
	{

		$mcrypt=new MCrypt();
		$log=new Log("receive_mo.log");

		$log->write($this->request->post);
		$order_id = $mcrypt->decrypt($this->request->post['order_id']);
		$received_quantities = $this->request->post['receive_quantity'];
		$suppliers_ids = $this->request->post['supplier'];
		$received_product_ids = $this->request->post['product_id'];
		$user_id=$mcrypt->decrypt($this->request->post['username']);
		$log->write($user_id);
		$this->session->data['user_id']=$user_id;
		$this->load->library('user');
                $this->user = new User($this->registry);

			$i=0;
			$received_quantities_de =array();
			foreach($received_quantities as $qnty)
			{
				$log->write($qnty);
				if($i!=0)
				{
					
					$received_quantities_de[$i]="next product";
					$i++;
					
				}
				$received_quantities_de[$i]=$mcrypt->decrypt($qnty);
						
				$i++;
			}
		$log->write($received_quantities_de);
		$received_quantities=$received_quantities_de;
		$received_product_idss=array();
		$i=0;
			foreach($received_product_ids as $pid)
			{
				$received_product_idss[$i]=$mcrypt->decrypt($pid);
				$i++;
			}

		$log->write($received_product_idss);
		$received_product_ids=$received_product_idss;


		$order_receive_date = date("Y-m-d");//$this->request->post['order_receive_date'];
		$prices = $this->request->post['price'];
		$rq = $this->request->post['remaining_quantity'];
		if(isset($this->request->post['disable_bit']))
		{
			$data['disable_bit'] = 1;
		}
		/*print_r($received_quantities);
		print_r($suppliers_ids);
		print_r($received_product_ids);
		print_r($order_receive_date);
		print_r($prices);
		exit;*/
		$received_order_info['received_quantities'] = $received_quantities;
		$received_order_info['received_product_ids'] = $received_product_ids;
		$received_order_info['suppliers_ids'] = $suppliers_ids;
		$received_order_info['order_receive_date'] = $order_receive_date;
		$received_order_info['prices'] = $prices;
		
		$received_order_info['rq'] = $rq;
		$url = ''; 
		if($order_id)
		{
			$url .= '&order_id=' . $order_id;
		}
		$log->write("before check");
		if((count($received_quantities) != count(array_filter($received_quantities))) || (count($prices) != count(array_filter($prices))) || $order_receive_date == '')
		{
			$_SESSION['empty_fields_error'] = 'Warning: Please check the form carefully for errors!';
			
				$log->write("in check");		
			
			
			$this->adminmodel('inventory/purchase_order');
			$data['order_information'] = $this->model_inventory_purchase_order->view_order_details_mo($order_id);
			
			for($i =0; $i<count($data['order_information']['products']); $i++)
			{
				unset($data['order_information']['products'][$i]['quantities']);
				unset($data['order_information']['products'][$i]['prices']);
			}
			
			$start_loop = 0;
			$data['validation_bit'] = 1;
			
			for($i = 0; $i<count($received_product_ids); $i++)
			{
				for($j = $start_loop; $j<count($prices); $j++)
				{
					if($prices[$j] == 'next product')
					{
						$start_loop = $j + 1;
						break;
					}
					else
					{
						$data['order_information']['products'][$i]['quantities'][$j] = $received_quantities[$j];
						$data['order_information']['products'][$i]['suppliers'][$j] = $suppliers_ids[$j];
						$data['order_information']['products'][$i]['prices'][$j] = $prices[$j];
					}
				}
				
				$data['order_information']['products'][$i]['quantities'] = array_values($data['order_information']['products'][$i]['quantities']);
				$data['order_information']['products'][$i]['suppliers'] = array_values($data['order_information']['products'][$i]['suppliers']);
				$data['order_information']['products'][$i]['prices'] = array_values($data['order_information']['products'][$i]['prices']);
				$data['order_information']['products'][$i]['rq'] = $received_order_info['rq'][$i];
			}
			
			$data['order_id'] = $order_id;
			if($order_receive_date)
			{
				$data['order_information']['order_info']['receive_date'] =  $order_receive_date;
			}
			else
			{
				$data['order_information']['order_info']['receive_date'] =  '0000-00-00';
			}
			$datas['receive_message'] = $mcrypt->encrypt('Warning: Please check the form carefully for errors!');

			$this->response->setOutput(json_encode($datas));
		}
		else
		{
			$log->write("after check");
			if(isset($this->request->post['disable_bit']))
			{
				unset($received_order_info['suppliers_ids']);
			}
			$this->adminmodel('inventory/purchase_order');
			$inserted = $this->model_inventory_purchase_order->insert_receive_order_mo($received_order_info,$order_id);
			if($inserted)
			{
				$data['receive_message'] = $mcrypt->encrypt('Order received Successfully!!');
				$this->response->setOutput(json_encode($data));
			}
			else
			{
				$data['receive_message'] = $mcrypt->encrypt('Sorry!! something went wrong, try again');
				$this->response->setOutput(json_encode($data));	
			}
				
		}
	}
        //******************************* RECEIVE ORDER MO ENDS ****************************//

	/*-----------------------------insert receive order function starts here-------------------*/
	
	public function receive_order()
	{

		$mcrypt=new MCrypt();
		$log=new Log("receive.log");

		$log->write($this->request->post);
		$order_id = $mcrypt->decrypt($this->request->post['order_id']);
		$received_quantities = $this->request->post['receive_quantity'];
		$suppliers_ids = $this->request->post['supplier'];
		$received_product_ids = $this->request->post['product_id'];
		$user_id=$mcrypt->decrypt($this->request->post['username']);
		$log->write($user_id);
		$this->session->data['user_id']=$user_id;
		$this->load->library('user');
                $this->user = new User($this->registry);

			$i=0;
			$received_quantities_de =array();
			foreach($received_quantities as $qnty)
			{
				$log->write($qnty);
				if($i!=0)
				{
					
					$received_quantities_de[$i]="next product";
					$i++;
					
				}
				$received_quantities_de[$i]=$mcrypt->decrypt($qnty);
						
				$i++;
			}
		$log->write($received_quantities_de);
		$received_quantities=$received_quantities_de;
		$received_product_idss=array();
		$i=0;
			foreach($received_product_ids as $pid)
			{
				$received_product_idss[$i]=$mcrypt->decrypt($pid);
				$i++;
			}

		$log->write($received_product_idss);
		$received_product_ids=$received_product_idss;


		$order_receive_date = date("Y-m-d");//$this->request->post['order_receive_date'];
		$prices = $this->request->post['price'];
		$rq = $this->request->post['remaining_quantity'];
		if(isset($this->request->post['disable_bit']))
		{
			$data['disable_bit'] = 1;
		}
		/*print_r($received_quantities);
		print_r($suppliers_ids);
		print_r($received_product_ids);
		print_r($order_receive_date);
		print_r($prices);
		exit;*/
		$received_order_info['received_quantities'] = $received_quantities;
		$received_order_info['received_product_ids'] = $received_product_ids;
		$received_order_info['suppliers_ids'] = $suppliers_ids;
		$received_order_info['order_receive_date'] = $order_receive_date;
		$received_order_info['prices'] = $prices;
		
		$received_order_info['rq'] = $rq;
		$url = ''; 
		if($order_id)
		{
			$url .= '&order_id=' . $order_id;
		}
		$log->write("before check");
		if((count($received_quantities) != count(array_filter($received_quantities))) || (count($prices) != count(array_filter($prices))) || $order_receive_date == '')
		{
			$_SESSION['empty_fields_error'] = 'Warning: Please check the form carefully for errors!';
			
				$log->write("in check");		
			
			
			$this->adminmodel('inventory/purchase_order');
			$data['order_information'] = $this->model_inventory_purchase_order->view_order_details($order_id);
			
			for($i =0; $i<count($data['order_information']['products']); $i++)
			{
				unset($data['order_information']['products'][$i]['quantities']);
				unset($data['order_information']['products'][$i]['prices']);
			}
			
			$start_loop = 0;
			$data['validation_bit'] = 1;
			
			for($i = 0; $i<count($received_product_ids); $i++)
			{
				for($j = $start_loop; $j<count($prices); $j++)
				{
					if($prices[$j] == 'next product')
					{
						$start_loop = $j + 1;
						break;
					}
					else
					{
						$data['order_information']['products'][$i]['quantities'][$j] = $received_quantities[$j];
						$data['order_information']['products'][$i]['suppliers'][$j] = $suppliers_ids[$j];
						$data['order_information']['products'][$i]['prices'][$j] = $prices[$j];
					}
				}
				
				$data['order_information']['products'][$i]['quantities'] = array_values($data['order_information']['products'][$i]['quantities']);
				$data['order_information']['products'][$i]['suppliers'] = array_values($data['order_information']['products'][$i]['suppliers']);
				$data['order_information']['products'][$i]['prices'] = array_values($data['order_information']['products'][$i]['prices']);
				$data['order_information']['products'][$i]['rq'] = $received_order_info['rq'][$i];
			}
			
			$data['order_id'] = $order_id;
			if($order_receive_date)
			{
				$data['order_information']['order_info']['receive_date'] =  $order_receive_date;
			}
			else
			{
				$data['order_information']['order_info']['receive_date'] =  '0000-00-00';
			}
			$datas['receive_message'] = $mcrypt->encrypt('Warning: Please check the form carefully for errors!');

			$this->response->setOutput(json_encode($datas));
		}
		else
		{
			$log->write("after check");
			if(isset($this->request->post['disable_bit']))
			{
				unset($received_order_info['suppliers_ids']);
			}
			$this->adminmodel('inventory/purchase_order');
			$inserted = $this->model_inventory_purchase_order->insert_receive_order($received_order_info,$order_id);
			if($inserted)
			{
				$data['receive_message'] = $mcrypt->encrypt('Order received Successfully!!');
				$this->response->setOutput(json_encode($data));
			}
			else
			{
				$data['receive_message'] = $mcrypt->encrypt('Sorry!! something went wrong, try again');
				$this->response->setOutput(json_encode($data));	
			}
				
		}
	}
	
	/*-----------------------------insert receive order function ends here-----------------*/
        //******************************* REQUEST ORDER BY MO START****************************//
        /*public function request_order()
	{
		$mcrypt=new MCrypt();
		$json=array();
		$json['success'] = $mcrypt->encrypt('Service temporarily not available');
		$this->response->setOutput(json_encode($json));	
	}*/
	
	public function request_order()
	{
		$mcrypt=new MCrypt();
                $log=new Log("Req_Ind_Mo_".date('Y_m_d').".log");
                
		$data['products'] = $_POST['product'];
		$data['options'] = $_POST['options'];
		$data['option_values'] = $_POST['option_values'];
		$data['quantity'] = $_POST['quantity'];
		$data['supplier_id'] ="--Supplier--"; 
		$data['stores'] = $_POST['stores'];
                $data['ws_price'] = $_POST['ws_price'];
               
		$this->load->library('user');
                $this->user = new User($this->registry);
                $log->write( $this->request->post);
                                
                $this->session->data['user_id']=$mcrypt->decrypt($_POST['username']);
		$log->write('MoId: '.$this->session->data['user_id']);
                
                
		for($i = 0 ; $i <count($data['options']); $i++)
		{
			if($data['options'][$i] == '')
			{
				$data['options'][$i] = '0_option';
			}
		}
		
		
		for($i = 0 ; $i <count($data['option_values']); $i++)
		{
			if($data['option_values'][$i] == '')
			{
				$data['option_values'][$i] = '0_optionvalue';
			}
		}
		
		if((in_array("--products--",$data['products'])) || (in_array("--stores--",$data['stores'])) || (in_array("--Product Options--",$data['options'])) || (in_array("--Option Values--",$data['option_values'])) || (count($data['quantity']) != count(array_filter($data['quantity']))) || (count($data['options']) != count(array_filter($data['options']))) || (count($data['products']) != count(array_filter($data['products']))) || (count($data['option_values']) != count(array_filter($data['option_values']))))
		{
			$log->write("Inside If");

			$data['form_bit'] = 0;
			$_SESSION['errors'] = "Warning: Please check the form carefully for errors!";
			
			
			$i = 0;
			foreach($data['products'] as $product)
			{
				if(strrchr($product,"_"))
				{
				$product_names[$i] = explode('_',$product);
				}
				else
				{
					$product_names[$i] = $product;
				}
				$i++;
			}
			$data['product_received'] = $product_names;
			$i = 0;
			foreach($data['options'] as $option)
			{
				if(strrchr($option,"_"))
				{
					$options[$i] = explode('_',$option);
				}
				else
				{
					$options[$i] = $option;
				}
				$i++;
			}
			$data['options_received'] = $options;
			$i = 0;
			foreach($data['option_values'] as $option_value)
			{
				if(strrchr($option_value,"_"))
				{
					$option_values[$i] = explode('_',$option_value);
				}
				else
				{
					$option_values[$i] = $option_value;
				}
				$i++;
			}
			$data['option_values_received'] = $option_values;
			
			$data['quantities_received'] = $data['quantity'];
			
			$this->load->model('catalog/product');
			$products = $this->model_catalog_product->getProducts();
			$i = 0;
			foreach($products as $product)
			{
				$products[$i] = $product['name'];
				$product_ids[$i] = $product['product_id'];
				$i++;
			}
			$data['products'] = $products;
			$data['product_ids'] = $product_ids;
			$this->load->model('catalog/option');
			$data['options'] = $this->model_catalog_option->getOptions();
			
			$data['option_values'] = $option_values;
			$url = '';
								
			$this->load->model('catalog/option');
			$data['options'] = $this->model_catalog_option->getOptions();

		}
		else
		{

			$log->write("Inside Else");
			$iq = 0;
			foreach($data['quantity'] as $qnty){

                        $qntry_final[$iq]=$mcrypt->decrypt($qnty);
			$iq++;					
                        }
                        
                        $log->write('Total_Qty: ');
                        $log->write($qntry_final);
                        $data['quantity']=$qntry_final;

			$i = 0;
			foreach($data['products'] as $product)
			{
				
                                $productval=explode('_',$product);
				$product=$mcrypt->decrypt($productval[0])."_".$mcrypt->decrypt($productval[1]);					
				$product_names[$i] = explode('_',$product);
				$i++;
			}
                        $log->write('Product Name:');
                        $log->write($product_names);
                        $data['products'] = $product_names;
			
                        $i = 0;
			foreach($data['stores'] as $store)
			{
				$productval=explode('_',$store);
				$store=$mcrypt->decrypt($productval[0])."_".$mcrypt->decrypt($productval[1]);	
				$store_names[$i] = explode('_',$store);
				$i++;
			}
                        
                        $log->write('Store Name:');
                        $log->write($store_names);
			$data['stores'] = $store_names;
                        
                        foreach($data['ws_price'] as $price)
			{
                            $data_price[]=$mcrypt->decrypt($price);	
			}
			$data['data_price'] = $data_price;
                        $i = 0;
			foreach($data['options'] as $option)
			{
				if(strrchr($option,"_"))
				{
					$options[$i] = explode('_',$option);
				}
				else
				{
					$options[$i] = $option;
				}
				$i++;
			}
			$data['options'] = $options;
			$i = 0;
			foreach($data['option_values'] as $option_value)
			{
				if(strrchr($option_value,"_"))
				{
					$option_values[$i] = explode('_',$option_value);
				}
				else
				{
					$option_values[$i] = $option_value;
				}
				$i++;
			}
			$data['option_values'] = $option_values;
                        
                        $log->write("Load Model");
			$this->adminmodel('inventory/purchase_order');
			$OrderId = $this->model_inventory_purchase_order->insert_purchase_order($data);
                        if($OrderId) // Order Placed Successfully
			{
                            $log->write("Order Placed with OrderId: ".$OrderId);
                            $totQty=0;
                            for($i = 0; $i<count($data['products']); $i++)
                            {    
                                $totQty=$totQty+$data['quantity'][$i];
                            }    
                           

                            $StoKey=$data['stores'][0][0];
                            $StoId = $this->model_inventory_purchase_order->StoKey($StoKey);
                            if(empty($StoId)){ 
                                $StoId=0;
                            }
                            
                            
                            $DD = $this->model_inventory_purchase_order->DlMob($StoId);
                            $log->write("Del Data:");
                            $log->write($DD);
                            
                            $DlMob='91'.$DD['DIS_MOB'];
                            $DlName=$DD['DIS_NAME'];
                            $DlDist=$DD['DIS_DIST_NAME'];
                            
                            
                            $MoId=$this->session->data['user_id'];
                            $AM = $this->model_inventory_purchase_order->AmMob($MoId);
                            $AmMob='91'.$AM['AM_MOB'];
                            $MoName=$AM['MO_NAME'];
                            $log->write("Am Mob:".$AmMob);
                       
                            //=============== Inbound Mobile ===============//    
                            $InMob_I='918392913500'; // for fixed
                            $InMob_II='917830800900'; // for fixed
                            //$InMob_III='919971813600'; // for test    
                            //$InMob_IV='919205540431'; // for test  
                            $numbers = array($DlMob, $AmMob, $InMob_I, $InMob_II);
                            //$numbers = array($InMob_III,$InMob_IV);
                            
                        
                            //$textmsg='Your Order of TotalKgs KGS has been Booked. Your Order ID is OrderId .';
                            $textmsg='Order of M/s PartyName , DistName of OrdQty KGS has been booked by MoName . Your Order ID is OrdId.';
                            $textmsg=str_replace('PartyName',$DlName,$textmsg);
                            $textmsg=str_replace('DistName' ,$DlDist,$textmsg);
                            $textmsg=str_replace('OrdQty',$totQty,$textmsg);
                            $textmsg=str_replace('MoName',$MoName,$textmsg);
                            $textmsg=str_replace('OrdId',$OrderId,$textmsg);
                            
                            $log->write("Message Sent:".$textmsg);
                            $message = rawurlencode($textmsg);
                            $numbers = implode(',', $numbers);
                            //$numbers2 = implode(',', $numbers2);
                            //Authorization
                            $apiKey = urlencode('bDDRD5CawD8-vZaDs0E2AFnEOi1BBmyE90KujZbjTc');
                            $sender = urlencode('KAIBLY');
                        
                            $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
                        
                            // Send the POST request with cURL
                            $ch = curl_init('https://api.textlocal.in/send/');
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            $responseBody = curl_exec($ch);
                            if($responseBody === false) {
                                $responseBody=curl_error($ch);
                            }
                            curl_close($ch);
                            $log->write("Message Sent:".$responseBody);	
                            $type='MO_REQ_PO';
                            $msgMaster = $this->model_inventory_purchase_order->msgMaster($OrderId,$type,$MoId,$StoId,$textmsg,$numbers,$responseBody);
                            $log->write("Message2DB:".$msgMaster);	
                            
                           //************************ Confirm Message To AM & DEALER End**************************//
                            $_SESSION['success_order_message'] = "The Order has been added";
                            $json['order_id'] = $mcrypt->encrypt( $OrderId);
                            $json['success'] = $mcrypt->encrypt('Success: new Indent placed with ID: '.$OrderId);
                            $this->response->setOutput(json_encode($json));	
                        }
		}
	}
	
	//******************************* REQUEST ORDER BY MO END****************************//
        
        //********************** Search Unapproved Indent Based by Store ID ******************//
	public function unApproveIndent()
        {
            $mcrypt=new MCrypt();
            $log=new Log("unApproveIndentStore.log");
            $log->write($this->request->post);
            $log->write($this->request->get);
            $store_id = $mcrypt->decrypt( $this->request->get['store_id']);
            $indent_dt = $mcrypt->decrypt( $this->request->get['indent_date']);
            //$store_id = $this->request->get['store_id'];
            //$indent_dt = $this->request->get['indent_date'];
if(empty($store_id)||empty($indent_dt))
{
	if(empty($store_id)){
		$json['msg']=$mcrypt->encrypt('Please Select Wholesaler');
	}
	if(empty($indent_dt)){
		$json['msg']=$mcrypt->encrypt('Please Select Indent Date');
	}
}
else
{
            $this->adminmodel('inventory/purchase_order');
            $indlist = $this->model_inventory_purchase_order->chkApproveIndent($store_id,$indent_dt);
            if($indlist)
            {
                for($z=0;$z<count($indlist);$z++)
                {
                $json['indent_id'][]=$mcrypt->encrypt($indlist[$z]['id']);
                $json['indent_dt'][]=$mcrypt->encrypt($indlist[$z]['order_date']);
                }
                /*
                foreach($indlist as $ind){
                $json['indents'][] = array(
                    'indent_id' =>$mcrypt->encrypt($ind['id']),
                    'indent_dt' =>$mcrypt->encrypt($ind['order_date'])
                );
                }
                 
                 */
            }
            else {
                $json['msg']=$mcrypt->encrypt('No Indent Found');
            }
}
            $this->response->setOutput(json_encode($json));
        }
        public function unApproveIndentList()
        {
            $mcrypt=new MCrypt();
            $log=new Log("unApproveIndentList.log");
            $log->write($this->request->post);
            $log->write($this->request->get);
            $store_id = $mcrypt->decrypt( $this->request->get['store_id']);
            $indent_id = $mcrypt->decrypt( $this->request->get['indent_id']);
            $indent_dt = $mcrypt->decrypt( $this->request->get['indent_date']);
            //$store_id = $this->request->get['store_id'];
            //$indent_id = $this->request->get['indent_id'];
            //$indent_dt = $this->request->get['indent_date'];
            $this->adminmodel('inventory/purchase_order');
            $indlist = $this->model_inventory_purchase_order->chkApproveIndentList($store_id,$indent_id,$indent_dt);
            if($indlist)
            {
               /* for($z=0;$z<count($indlist);$z++)
                {
                $json['indent_id'][]=$mcrypt->encrypt($indlist[$z]['order_id']);
                $json['store_id'][]=$mcrypt->encrypt($indlist[$z]['store_id']);
                $json['product_id'][]=$mcrypt->encrypt($indlist[$z]['product_id']);
                $json['product_name'][]=$mcrypt->encrypt($indlist[$z]['name']);
                $json['product_qty'][]=$mcrypt->encrypt($indlist[$z]['quantity']);
                }
                * 
                */
                foreach($indlist as $ind){
                $json['indents'][] = array(
                    'indent_id'     =>$mcrypt->encrypt($ind['order_id']),
                    'store_id'      =>$mcrypt->encrypt($ind['store_id']),
                    'product_id'    =>$mcrypt->encrypt($ind['product_id']),
                    'product_name'  =>$mcrypt->encrypt($ind['name']),
                    'product_qty'   =>$mcrypt->encrypt($ind['quantity'])
                );
                }
            }
            else {
                $json['msg']=$mcrypt->encrypt('No Indent Found');
            }
            $this->response->setOutput(json_encode($json));
        }
        public function unApproveIndentUpdate()
        {
            $mcrypt=new MCrypt();
            $log=new Log("unApproveIndentUpdate.log");
            $log->write($this->request->post);
            $log->write($this->request->get);
            //$indent_id  =$mcrypt->decrypt($this->request->get['indent_id']);
            //$product_id =$this->request->get['product_id'];
            //$product_qty=$this->request->get['product_qty'];
            $data['indent_id'] = $_POST['indent_id'];
            $data['product_id'] = $_POST['product_id'];
            $data['product_qty'] = $_POST['product_qty'];
      	if(empty($data['indent_id'])||empty($data['product_id'])||empty($data['product_qty']))
	{
		
			$json['msg']=$mcrypt->encrypt('Please Select Indent Id');
			
	}
	else
	{
 
            foreach($data['indent_id'] as $inds)
            {
		$data_ind[]=$mcrypt->decrypt($inds);	
            }
            $data['data_ind'] = $data_ind;
            foreach($data['product_id'] as $pros)
            {
		$data_pro[]=$mcrypt->decrypt($pros);	
            }
            $data['data_pro'] = $data_pro;
            foreach($data['product_qty'] as $qtys)
            {
		$data_qty[]=$mcrypt->decrypt($qtys);	
            }
            $data['data_qty'] = $data_qty;
            
            //$indent_id = $this->request->get['indent_id'];
            //$product_ids[] = $this->request->get['product_id'];
            //$product_qtys[] = $this->request->get['quantity'];
            $this->adminmodel('inventory/purchase_order');
            $upd = $this->model_inventory_purchase_order->updateIndentList($data_ind,$data_pro,$data_qty);
            if($upd == 1)
            {
                 $json['success']=$mcrypt->encrypt('Record Updated Successfully');
            }
            else {
                    $json['error']=$mcrypt->encrypt('Sorry! Try again ');
            }
}
             $this->response->setOutput(json_encode($json));
        }
        public function unApproveIndentDelete()
        {
            $mcrypt=new MCrypt();
            $log=new Log("unApproveIndentDelete.log");
            $log->write($this->request->post);
            $log->write($this->request->get);
            $indent_id = $mcrypt->decrypt($this->request->get['indent_id']);
            $product_id= $mcrypt->decrypt($this->request->get['product_id']);
           
            //$indent_id = $this->request->get['indent_id'];
            //$product_id= $this->request->get['product_id'];
            $this->adminmodel('inventory/purchase_order');
            $del = $this->model_inventory_purchase_order->deleteIndentList($indent_id,$product_id);
            if($del == 1)
            {
                    $json['success']=$mcrypt->encrypt('true');
                    $json['msg']=$mcrypt->encrypt('Record Deleted Successfully');
            }
            else {
                    $json['success']=$mcrypt->encrypt('false');
                    $json['msg']=$mcrypt->encrypt('Sorry! Try again ');
            }
            $this->response->setOutput(json_encode($json));
        }
}

?>