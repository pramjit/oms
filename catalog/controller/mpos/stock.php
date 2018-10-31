<?php
//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class Controllermposstock extends Controller{


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

	public function history(){

		$log=new Log("stockhis.log");
		$log->write($this->request->post);
		$log->write($this->request->get);
		$mcrypt=new MCrypt();
		$this->adminmodel('stock/purchase_order');
		$search=0;
		
		if ( isset($this->request->post['q']) ) 
		{
			$page = 1;
			$search=$mcrypt->decrypt($this->request->post['q']);
		}
		elseif (isset($this->request->get['page'])) {
			$page = $mcrypt->decrypt($this->request->get['page']);
		}
		 else {
			$page = 1;
		}		
		$log->write("st=".$search);
		$start = ($page-1)*20;
		$limit = 20;
		$store_id=$mcrypt->decrypt($this->request->post['sid']);
		if(isset($this->request->post['rsid'])){
		$store_id_to=$mcrypt->decrypt($this->request->post['rsid']);		
		$results = $this->model_stock_purchase_order->getListRecSearch($start,$limit,$store_id,$search,$store_id_to);
		}
		else{
			$results =$this->model_stock_purchase_order->getListRecSup($start,$limit,$store_id,$search);
			}
		$log->write($results);
		/*getting the list of the orders*/		
						
		
		foreach ($results as $result) {
			$data['products'][] = array(
				'order_id'      =>$mcrypt->encrypt( $result['id']),
				'customer'      =>$mcrypt->encrypt( $result['user_id']),
				'status'        =>$mcrypt->encrypt( $result['receivetype']),
				'total'		=>$mcrypt->encrypt( ($result['tax']+$result['subtotal'])),
				'date_added'    =>$mcrypt->encrypt( date($this->language->get('date_format_short'), strtotime($result['order_date']))),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['order_sup_send'])),
				'telephone'	=> $mcrypt->encrypt($result['recipient_number'])
							
			);
		}
	$log->write($data);
	$this->response->setOutput(json_encode($data));	

}
	public function orderlist()
	{
								
						
					/*getting the list of the orders*/
			$log=new Log("stockhis.log");

		$mcrypt=new MCrypt();
		$this->adminmodel('stock/purchase_order');
		
		if (isset($this->request->get['page'])) {
			$page = $mcrypt->decrypt($this->request->get['page']);
		} else {
			$page = 1;
		}		
		$start = ($page-1)*20;
		$limit = 20;
		$store_id=$mcrypt->decrypt($this->request->post['sid']);
		
		$data['order_list'] =$mcrypt->encrypt(serialize( $this->model_stock_purchase_order->getListRec($start,$limit,$store_id)));
		/*getting the list of the orders*/
		
		//getting total orders
		
		$total_orders = $this->model_stock_purchase_order->getTotalOrders();
		
		//getting pages

		
		//getting pages
		
		
		
		$data['results'] =$mcrypt->encrypt( sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin'))));
		$log->write($data['results']);
		
		$this->response->setOutput(json_encode($data));	

	}

/*----------------------------view_order_details function starts here------------*/

	public function order_details()
	{
				 $mcrypt=new MCrypt();
//$mcrypt->decrypt
		$order_id = $mcrypt->decrypt($this->request->get['order_id']);							
		$this->adminmodel('stock/purchase_order');
		$data['order_information'] =$this->model_stock_purchase_order->view_order_details($order_id);		
//print_r($data['order_information']['products']);
		$this->response->setOutput(json_encode($data['order_information']['products']));
		
	}

	
	public function order_details_search()
	{
				 $mcrypt=new MCrypt();

		$order_id = $mcrypt->decrypt($this->request->get['order_id']);							
		$this->adminmodel('stock/purchase_order');
		$data['order_information'] =$this->model_stock_purchase_order->view_order_details($order_id);	

	foreach ($data['order_information']['products'] as $result) {
			$datas['products'][] = array(
				'order_id'      =>$mcrypt->encrypt( $result['order_id']),
				'name'      =>$mcrypt->encrypt( $result['name']),
				'product_id'        =>$mcrypt->encrypt( $result['product_id']),
				'quantity'        =>$mcrypt->encrypt( $result['quantity']),
				'price'		=>$mcrypt->encrypt( str_replace("Rs.","",$result['price'])/$result['quantity']),
				'tax'		=>$mcrypt->encrypt( $result['tax']),
				'total'		=>$mcrypt->encrypt(str_replace("Rs.","",$result['price'])+($result['tax']*$result['quantity'])),
											
			);
		}
	
	//print_r($data['order_information']['products']);

	$datas['tax']=$mcrypt->encrypt($data['order_information']['order_info']['tax']);
	$datas['subtotal']=$mcrypt->encrypt($data['order_information']['order_info']['subtotal']);
	$datas['total']=$mcrypt->encrypt(($data['order_information']['order_info']['tax']+$data['order_information']['order_info']['subtotal']));
	$datas['to']=$mcrypt->encrypt($this->model_stock_purchase_order->getOrdersStore($order_id));


		$this->response->setOutput(json_encode($datas));
		
	}
	
	/*----------------------------view_order_details function ends here--------------*/

	

	/*-----------------------------insert receive order function starts here-------------------*/
	
	public function receive_order()
	{

		$mcrypt=new MCrypt();
		$log=new Log("receivestock.log");
                

		$log->write($this->request->post);
		$order_id = $mcrypt->decrypt($this->request->post['order_id']);
		$received_quantities = $this->request->post['receive_quantity'];
		$suppliers_ids = $this->request->post['supplier'];
		$received_product_ids = $this->request->post['product_id'];
		$user_id=$mcrypt->decrypt($this->request->post['username']);
                $prices =$this->request->post['price'];
		$rq = $this->request->post['remaining_quantity'];
		$log->write("receive_order ");
                $log->write("GET DATA:".$order_id."_".$received_quantities."_".$suppliers_ids."_".$received_product_ids ."_".$user_id);
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
                
                /**************************Added By Aasit Start********************/
                $received_product_prcs=array();
		$i=0;
			foreach($prices as $prs)
			{
				$received_product_prcs[$i]=$mcrypt->decrypt($prs);
				$i++;
			}

		$log->write($received_product_prcs);
                
		$received_product_prs=$received_product_prcs;
                //**************************************************************//
                $received_product_rqcs=array();
		$i=0;
			foreach($rq as $rqs)
			{
				$received_product_rqcs[$i]=$mcrypt->decrypt($rqs);
				$i++;
			}

		$log->write($received_product_rqcs);
                
		$received_product_rqs=$received_product_rqcs;
                //****************************Added By Aasit Start****************************//
		$order_receive_date = date("Y-m-d");//$this->request->post['order_receive_date'];
		
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
		$received_order_info['prices'] = $received_product_prs;
		$received_order_info['rq'] = $received_product_rqs;
                
		$url = ''; 
		if($order_id)
		{
			$url .= '&order_id=' . $order_id;
		}
		$log->write("Before Send To Model");
		if((count($received_quantities) != count(array_filter($received_quantities))) || (count($prices) != count(array_filter($prices))) || $order_receive_date == '')
		{
                    $_SESSION['empty_fields_error'] = 'Warning: Please check the form carefully for errors!';
		    $log->write("in check");		
				
                    $this->adminmodel('stock/purchase_order');
                    $data['order_information'] = $this->model_stock_purchase_order->view_order_details($order_id);
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
			$log->write("After Check");
			if(isset($this->request->post['disable_bit']))
			{
				unset($received_order_info['suppliers_ids']);
			}
			$this->adminmodel('stock/purchase_order');
			$inserted = $this->model_stock_purchase_order->insert_receive_order($received_order_info,$order_id);
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



	//===================== SMO REQUEST ORDER START =====================//
	
	public function request_order()
	{
		$mcrypt=new MCrypt();
                $log=new Log("ReqPO_SMO_".date('Ymd').".log");
		$data['products'] = $_POST['product'];
		$data['prices'] = $_POST['prices'];	
		$data['taxes'] = $_POST['taxes'];	
		$data['options'] = $_POST['options'];
		$data['option_values'] = $_POST['option_values'];
		$data['quantity'] = $_POST['quantity'];
		$data['supplier_id'] =$_POST['supplier_id'];//"--Supplier--";
		$data['stores'] = $_POST['stores'];
		$data['recipient_number']=$mcrypt->decrypt($_POST['cmt']);
		$data['transport_id']=$mcrypt->decrypt($_POST['tid']);
		$data['tax']=$mcrypt->decrypt($_POST['ta']);
		$data['subtotal']=$mcrypt->decrypt($_POST['sub']);
		$this->session->data['user_id']=$mcrypt->decrypt($_POST['username']);
		$this->load->library('user');
                $this->user = new User($this->registry);

		$log->write( $this->request->post);
		$log->write($data);

                /*to let the user add products without options*/
		for($i = 0 ; $i <count($data['options']); $i++)
		{
			if($data['options'][$i] == '')
			{
				$data['options'][$i] = '0_option';
			}
		}
		
		/*to let the user add products without option values*/
		for($i = 0 ; $i <count($data['option_values']); $i++)
		{
			if($data['option_values'][$i] == '')
			{
				$data['option_values'][$i] = '0_optionvalue';
			}
		}
		
		if((in_array("--products--",$data['products'])) || (in_array("--stores--",$data['stores'])) || (in_array("--Product Options--",$data['options'])) || (in_array("--Option Values--",$data['option_values'])) || (count($data['quantity']) != count(array_filter($data['quantity']))) || (count($data['options']) != count(array_filter($data['options']))) || (count($data['products']) != count(array_filter($data['products']))) || (count($data['option_values']) != count(array_filter($data['option_values']))))
		{
			$log->write("in if");

			$data['form_bit'] = 0;
			$_SESSION['errors'] = "Warning: Please check the form carefully for errors!";
			/*------------Working with data received starts-----*/
			
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
			//print_r($data['option_values_received']);
			$data['quantities_received'] = $data['quantity'];
			/*------working with data received ends---------*/
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
			/*$i = 0;
			foreach($data['options_received'] as $option)
			{
				$option_values[$i] = $this->model_catalog_option->getOptionValues($option[0]); 
				$i++;
			}*/
			$data['option_values'] = $option_values;
			$url = '';
								
			$this->load->model('catalog/option');
			$data['options'] = $this->model_catalog_option->getOptions();

					}
		else
		{

			$log->write("in else");
			$iq = 0;
					foreach($data['quantity'] as $qnty){

					$qntry_final[$iq]=$mcrypt->decrypt($qnty);
					$iq++;					

				}
$log->write($qntry_final);

$data['quantity']=$qntry_final;

			$i = 0;
			foreach($data['products'] as $product)
			{
				//$product=$mcrypt->decrypt($product);
			$log->write($product);
					
				$productval=explode('_',$product);
				$product=$mcrypt->decrypt($productval[0])."_".$mcrypt->decrypt($productval[1]);					
				$product_names[$i] = explode('_',$product);
				$i++;
			}


$log->write($product_names);


			$data['products'] = $product_names;



//prices
$ip = 0;
			foreach($data['prices'] as $product)
			{
				//$product=$mcrypt->decrypt($product);
			$log->write($product);
					
					$productval=explode('_',$product);
				$product=$mcrypt->decrypt($productval[0])."_".$mcrypt->decrypt($productval[1]);					
				$product_price[$ip] = explode('_',$product);
				$ip++;
			}
$log->write($product_price);


			$data['prices'] = $product_price;

$log->write("in taxs");

//taxes
$it = 0;
			foreach($data['taxes'] as $product)
			{
				//$product=$mcrypt->decrypt($product);
			$log->write($product);
					
					$productval=explode('_',$product);
				$product=$mcrypt->decrypt($productval[0])."_".($productval[1]);	
			$log->write($product);
			$log->write("pri");				
				$product_tax[$it] = explode('_',$product);
				$it++;
			}
$log->write("in tax");

$log->write($product_tax);


			$data['taxes'] = $product_tax;

			//stores
                        $i = 0;
			foreach($data['stores'] as $store)
			{
				$productval=explode('_',$store);
				$store=$mcrypt->decrypt($productval[0])."_".$mcrypt->decrypt($productval[1]);	
				$store_names[$i] = explode('_',$store);
				$i++;
			}
			$data['stores'] = $store_names;
                        $log->write($store_names);
                        
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
			
			//data

			$iqs = 0;
					foreach($data['supplier_id'] as $supplier_id){

					$supplier_id_final[$iqs]=$mcrypt->decrypt($supplier_id);
					$iqs++;					

				}
			$data['supplier_id']=$supplier_id_final;
			//	
			
                        $log->write("before");				
			$data['option_values'] = $option_values;			
			$this->adminmodel('stock/purchase_order');
			$log->write("after");	
			$order_id = $this->model_stock_purchase_order->insert_purchase_order($data);
			  $log->write("after id".$order_id);													
			if($order_id)
			{
				$_SESSION['success_order_message'] = "The Order has been added";
				$json['order_id'] = $mcrypt->encrypt($order_id);
				$json['success'] = $mcrypt->encrypt('Success: new order placed with ID: '.$order_id);
				$this->response->setOutput(json_encode($json));	


			}
		}
	}
	
	//===================== SMO REQUEST ORDER END ======================//
        public function retailer_list()
{
$log=new Log("RTLRLIST.log");
$mcrypt=new MCrypt();
$log->write($this->request->post);
$usrid = $mcrypt->decrypt($this->request->post['user_id']);
$log->write($usrid);



$this->adminmodel('stock/purchase_order');
$results = $this->model_stock_purchase_order->retailerList($usrid);
$log->write($results);
foreach ($results as $result) {
$data[] = array(
'RTLR_MOB' =>$mcrypt->encrypt( $result['mobile']),
'RTLR_NAME' =>$mcrypt->encrypt( $result['retailer_name'])
);
}
$log->write($data);
$this->response->setOutput(json_encode($data)); 
}
	
	///////////////////////////////////////////////////////////////////////////////////
	
	//*******************Material Dispatch Report**************************//
public function dispatch_list()
{
$log=new Log("DISPATCH_LIST.log");
$mcrypt=new MCrypt();
$this->adminmodel('stock/purchase_order');
$log->write( $this->request->get);
$stoid = $mcrypt->decrypt($this->request->get['store_id']); 
$fromdate = $mcrypt->decrypt($this->request->get['fdate']); 
$todate = $mcrypt->decrypt($this->request->get['tdate']); 
$results = $this->model_stock_purchase_order->dispatchList($stoid,$fromdate,$todate);
// print_r($results);
if(empty($results))
{
$data = 1;

}
else {
foreach ($results as $result) {
$data[] = array(
'prod_id' =>$mcrypt->encrypt( $result['product_id']),
'prod_name' =>$mcrypt->encrypt( $result['name']),
//'qty' => $result['quantity'],
//'totamt' => substr($result['price'], 4),
//'price' => substr($result['price'], 4)/$result['quantity'],
'prod_qty' =>$mcrypt->encrypt( $result['quantity']),
'prod_tot_price' =>$mcrypt->encrypt(substr($result['price'], 4)),
'prod_price'=>$mcrypt->encrypt(substr($result['price'], 4)/$result['quantity']),
'prod_date' =>$mcrypt->encrypt( $result['order_date'])

);
}
}
$log->write($data);
$this->response->setOutput(json_encode($data));
}
///////////////////////////////////////////////////////////////////////////////////
	
	
		
	
}

?>