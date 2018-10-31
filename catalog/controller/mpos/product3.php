<?php
class ControllermposProduct extends Controller {
    
    private $debugIt = false;
   
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
    
public function sproductsinv(){
$log=new Log("prdsearch.log");
$mcrypt=new MCrypt();            
//log to system table
	$this->load->model('account/activity');
	$activity_data = array(
				'customer_id' => $mcrypt->decrypt($this->request->post['username']),
				'data'        => $this->request->post
			);
        $this->model_account_activity->addActivity('serachinventory', $activity_data);

        $this->adminmodel('pos/pos');            
	$this->load->library('user');
	$log->write("data");
	$this->session->data['user_id']=$mcrypt->decrypt( $this->request->post['username']);
        $this->user = new User($this->registry);
        $json = array('success' => true, 'products' => array());
        if (isset($this->request->get['q'])) {
                $q = $mcrypt->decrypt($this->request->get['q']);
            } else {
                $q = '';
            }
            
            if (isset($this->request->get['page'])) {
                $page = $this->request->get['page'];
            } else {
                $page = 1;
            }
            
            $limit    = 20;
            $offset   = ($page-1)*$limit;

            $log->write("products".$q);
            $products = $this->model_pos_pos->searchProductsStore($q,$limit,$offset);
            $log->write($products);
            $log->write(sizeof($products));
		foreach ($products as $product) {

			if ($product['image']) {
				$image = $product['image'];
			} else {
				$image = false;
			}

			if ((float)$product['special']) {
				$special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}

			if($product['store_price']=='0.0000')
			{
					$product['price']=$product['price'];

			}else{
				$product['price']=$product['store_price'];
				}


			$json['products'][] = array(
					'id'			=> $mcrypt->encrypt($product['product_id']),
					'name'			=> $mcrypt->encrypt($product['name']),
                                        'sku'			=> $mcrypt->encrypt($product['sku']),
					'quantity'		=> $mcrypt->encrypt($product['quantity']),
					'description'           => $mcrypt->encrypt($product['description']),
					'price'			=> $mcrypt->encrypt($this->currency->format( round($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))) +  round($this->tax->getTax($product['price'], $product['tax_class_id'])) )),
                                        'rtlr_price'		=> $mcrypt->encrypt($product['rtlr_price']),
					'href'			=> $mcrypt->encrypt($this->url->link('product/product', 'product_id=' . $product['product_id'])),
					'thumb'			=> $mcrypt->encrypt($image),
					'special'		=> $mcrypt->encrypt($special),
					'rating'		=> $mcrypt->encrypt($product['rating']),
					'tax'			=> $mcrypt->encrypt(round($this->tax->getTax($product['price'], $product['tax_class_id'])))
			);
		}
	$json['total']=$mcrypt->encrypt("0");
	$json['listcount']=$mcrypt->encrypt(sizeof($products));
	return $this->response->setOutput(json_encode($json));

}


    public function getstorecr(){	
		$json = array();
		$log=new Log("storecr.log");
		$log->write($this->request->post);
		 $mcrypt=new MCrypt();

				//log to system table
				$this->load->model('account/activity');
				$activity_data = array(
					'customer_id' => $mcrypt->decrypt($this->request->post['username']),
					'data'        => json_encode($this->request->post),
				);

				$this->model_account_activity->addActivity('getStoreCR', $activity_data);
	                    $this-> adminmodel('pos/pos');
				$this->request->post['store_id']=$mcrypt->decrypt($this->request->post['store_id']);
			$json['hold_cr'] =$mcrypt->encrypt($this->model_pos_pos->get_store_balance($this->request->post['store_id']));
		return $this->response->setOutput(json_encode($json));
}


    /*
	* Get Categories
	*/
    
    public function Categories(){
        $this->language->load('api/cart');
        $json = array();
        $log=new Log("category.log");
        $log->write($this->request->post);
	$mcrypt=new MCrypt();
				//log to system table
				$this->load->model('account/activity');
				$activity_data = array(
					'customer_id' => $mcrypt->decrypt($this->request->post['username']),
					'data'        => json_encode($this->request->post),
				);

				$this->model_account_activity->addActivity('Categories', $activity_data);
                    
                    $this-> adminmodel('pos/pos');
                    $this-> adminmodel('setting/store');
                    $this-> adminmodel('tool/image');
                    $this->request->post['store_id']=$mcrypt->decrypt($this->request->post['store_id']);
                    $log->write($this->request->post);
                    //$this->load->model('pos/pos');
                    if(isset($this->request->post['store_id'])&&isset($this->request->post['store_emp']))
                    {
                        $log->write("Start if");
                        $categories = $this->model_pos_pos->getTopStoreCategories('19');
		
                    }

                    else if(isset($this->request->post['store_id']))
                    {
			$log->write("in if");
			//get categories 
                	$categories = $this->model_pos_pos->getTopStoreCategories($this->request->post['store_id']);
                    }else{
			$log->write("in else");
			//get categories 
                	$categories = $this->model_pos_pos->getTopCategories();
                    }

                        $log->write($categories);

                        $json['categories'] = array();
		
                        foreach ($categories as $category_info) {
                        $json['categories'][] = array(
                        'category_id' => $mcrypt->encrypt($category_info['category_id']),
                        'image'       =>$mcrypt->encrypt( $category_info['image']?$this->model_tool_image->resize($category_info['image'], 70, 70):'view/image/pos/logo.png'),
                        'name'        =>$mcrypt->encrypt( $category_info['name']),
                        );
                    }
                    $this->session->data['user_id']=$mcrypt->decrypt($this->request->post['username']);
                    $this->load->library('user');
                    $this->user = new User($this->registry);
                    $balance = $this->model_pos_pos->get_user_balance($this->user->getId());
                    $json['cash'] =$mcrypt->encrypt( $this->currency->format($balance['cash']));
                    $json['card'] =$mcrypt->encrypt( $this->currency->format($balance['card']));
                    $json['hold_carts'] =$mcrypt->encrypt("");// $this->model_pos_pos->get_hold_cart_list_user("1");
                    $json['hold_cr'] =$mcrypt->encrypt($this->model_pos_pos->get_store_balance($this->request->post['store_id']));
                    $json['storename']=$mcrypt->encrypt( $this->model_setting_store->getStore(( $this->request->post['store_id']))["name"]);//$this->session->data['api_store_id'])["name"];
                    $json['storeaddress']= $mcrypt->encrypt($this->config->get('config_address'));//$this->config->get('config_address');
                    $json['geocode']=$mcrypt->encrypt($this->config->get('config_geocode'));

                    $log->write($json);
                    //load template                                                             
                    if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
                    } else {
			$this->response->setOutput(json_encode($json));
                    }        
    }



/*farmer  data */
    public function CategoriesFarmer(){
            $this->language->load('api/cart');
                $json = array();

                    
                    $this-> adminmodel('pos/pos');
                    $this-> adminmodel('setting/store');
                    $this-> adminmodel('tool/image');

                    //$this->load->model('pos/pos');
                    //get categories 
                $categories = $this->model_pos_pos->getTopCategories();
		 $mcrypt=new MCrypt();
		$json['navigation'] = array();
		
		foreach ($categories as $category_info) {
                    $json['navigation'][] = array(
                        'id' => ($category_info['category_id']),
                        'name'        =>( $category_info['name']),
			'original_id' =>($category_info['category_id'])	,
			'children'   =>array( array('id'=>($category_info['category_id']),
						 'type'=>'category',
						'name'=>'ALL',
						'original_id' =>($category_info['category_id'])	

							))
                    );
		}                
                                                            
                    if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} 
		else {
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}        
    }

/*
	* Get products
*/
public function products() {
    $this->load->language('api/cart');
    $mcrypt=new MCrypt();
    //log to system table
    $this->load->model('account/activity');
    $activity_data = array(
		'customer_id' => $mcrypt->decrypt($this->request->post['username']),
		'data'        => $this->request->post
		);
	$this->model_account_activity->addActivity('products', $activity_data);
        $json = array();
        /*if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}
            else*/
                {
		$log =new Log("Product: ".date('Y-m-d').".log");
		$log->write($this->request->get);
		$log->write($this->request->post);
                   
                $this->load->model('catalog/product');
		$this->load->library('user');
		$log->write("data");
		$this->session->data['user_id']=$mcrypt->decrypt( $this->request->post['username']);
                $this->user = new User($this->registry);
		$log->write("data re");
		$log->write("data");

                if(isset($this->request->post['stype'])&&isset($this->request->post['store_emp']))
                {
                    $this->config->set('config_store_id','19');
                }

                else if(isset($this->request->post['stype']))
                {
                    //get indent
                    $this->config->set('config_store_id',$mcrypt->decrypt($this->request->post['stype']));
                    //$this->request->post['stype']));
                    $log->write("in stype");
                }
                else{

                //get store id
                $this->config->set('config_store_id',$mcrypt->decrypt( $this->request->post['store_id']));

                }
		$json = array('success' => true, 'products' => array());

		//***************** CATEGORY WISE PRODUCT ********************//
		if (isset($this->request->get['category'])) {
			$category_id = $mcrypt->decrypt( $this->request->get['category']);
		} else {
			$category_id = 0;
		}
                //***************** STATE WISE PRICE *************************//
               
		if (isset($this->request->post['state'])) {
			$state_id = $mcrypt->decrypt( $this->request->post['state']);
		} else {
			$state_id = 0;
		}
                
		$log->write('Cat_ID:'.$category_id.', State_ID:'.$state_id);
		$products = $this->model_catalog_product->getProducts(array(
			'filter_category_id' => $category_id,
                        'filter_state_id' => $state_id
		));

		foreach ($products as $product) {

		$log->write($product);

			if ($product['image']) {
				$image = $product['image'];
			} else {
				$image = false;
			}

			if ((float)$product['special']) {
				$special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}
$log->write($product['price']);
if(empty($product['price'])||$product['price']==0.0000)
{
		$product['price']=$product['sprice'];
}

////$mcrypt->encrypt($this->currency->format(round($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))) )),
//$product['description']
			$json['products'][] = array(
					'id'			=> $mcrypt->encrypt($product['product_id']),
					'name'			=> $mcrypt->encrypt($product['name']),
                                        'sku'			=> $mcrypt->encrypt($product['sku']),
                                        'quantity'		=> $mcrypt->encrypt($product['squantity']),
                                        //'squantity'		=> $mcrypt->encrypt($product['squantity']),
					'description'           => $mcrypt->encrypt("0"),
					//'price'		=> $mcrypt->encrypt($this->currency->format($product['price'])),
                                        //'rtlr_price'		=> $mcrypt->encrypt($product['rtlr_price']),
                                        'price'                 => $mcrypt->encrypt($this->currency->format($product['st_ws_price'])),
                                        'rtlr_price'		=> $mcrypt->encrypt($product['st_rt_price']),
                                        'st_ws_price'           => $mcrypt->encrypt($product['st_ws_price']),
                                        'st_rt_price'           => $mcrypt->encrypt($product['st_rt_price']),
					'href'			=> $mcrypt->encrypt($this->url->link('product/product', 'product_id=' . $product['product_id'])),
					'thumb'			=> $mcrypt->encrypt($image),
					'special'		=> $mcrypt->encrypt($special),
					'rating'		=> $mcrypt->encrypt($product['rating']),
					'tax'			=> $mcrypt->encrypt(($this->tax->getTax($product['price'], $product['tax_class_id']))),
					'category'		=> $this->request->get['category'],
					'subsidy'		=> $mcrypt->encrypt( empty($product['subsidy'])? 0:$product['subsidy'])

			);
		}
        }
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
                        $log->write($json);
			$this->response->setOutput(json_encode($json));
		}
	}
        //******************************* INDENT SAP DETAILS REPORT START **************************//
        public function sapreports() {
            $this->load->language('api/cart');
                $json = array();
                $mcrypt=new MCrypt();                    
		$log =new Log("indreports.log");

		$log->write($this->request->get);
		$log->write($this->request->post);

                //log to system table
		$this->load->model('account/activity');
		$activity_data = array(
			'customer_id' => $mcrypt->decrypt($this->request->post['username']),
			'data'        => $this->request->post
			);
                    $this->model_account_activity->addActivity('inventory products', $activity_data);
                    $this->load->model('catalog/product');
                    $this->load->library('user');
                
                $log->write("data");
		$this->session->data['user_id']=$mcrypt->decrypt( $this->request->post['username']);
                $this->user = new User($this->registry);
                $log->write("data re");
        
                //get store id
                $this->config->set('config_store_id',$mcrypt->decrypt( $this->request->post['store_id']));
		$products = $this->model_catalog_product->getSapRecords($mcrypt->decrypt( $this->request->get['indid']),
		$mcrypt->decrypt($this->request->post['limit'])

		);
                 $log->write($products);
                foreach ($products as $product) {
                $json['products'][] = array(
                        'PRO_ID'        => $mcrypt->encrypt($product['PRO_ID']),
			'PRO_NAME'      => $mcrypt->encrypt($product['PRO_NAME']),
                        'IND_QTY'       => $mcrypt->encrypt($product['IND_QTY']),
                        'SAP_QTY'       => $mcrypt->encrypt($product['SAP_QTY'])
                        
			);
		}
                $json['remarks']=$mcrypt->encrypt($products[0]['REMARKS']);
                $json['STO_NAME']=$mcrypt->encrypt($products[0]['STO_NAME']);
                        $log->write($json);
			$this->response->setOutput(json_encode($json));
		
        }
        //*************************** INDENT SAP DETAIL REPORT END**********************//
        //******************************* INDENT REPORT START **************************//
        public function indreports() {
            $this->load->language('api/cart');
                $json = array();
                $mcrypt=new MCrypt();                    
		$log =new Log("indreports.log");

		$log->write($this->request->get);
		$log->write($this->request->post);

                //log to system table
		$this->load->model('account/activity');
		$activity_data = array(
			'customer_id' => $mcrypt->decrypt($this->request->post['username']),
			'data'        => $this->request->post
			);
                    $this->model_account_activity->addActivity('inventory products', $activity_data);
                    $this->load->model('catalog/product');
                    $this->load->library('user');
                
                $log->write("data");
		$this->session->data['user_id']=$mcrypt->decrypt( $this->request->post['username']);
                $this->user = new User($this->registry);
                $log->write("data re");
        
                //get store id
                $this->config->set('config_store_id',$mcrypt->decrypt( $this->request->post['store_id']));
		$products = $this->model_catalog_product->getIndRecords($mcrypt->decrypt( $this->request->post['start']),
			$mcrypt->decrypt($this->request->post['limit'])

		);
                 $log->write($products);
                foreach ($products as $product) {
                 $json['products'][] = array(
                        'IND_ID'            => $mcrypt->encrypt($product['IND_ID']),
			'IND_DATE'          => $mcrypt->encrypt($product['IND_DATE']),
			'SAP_REF'      => $mcrypt->encrypt($product['SAP_REF']),
			'IND_STATUS'	=> $mcrypt->encrypt($product['IND_STATUS']),
			
			);
		}
                         $log->write($json);
			$this->response->setOutput(json_encode($json));
		
        }
        //******************************* INDENT REPORT END**************************//
        //******************************* INDENT REPORT BY STORE START **************************//
        public function strindreports() {
            $this->load->language('api/cart');
                $json = array();
                $mcrypt=new MCrypt();                    
		$log =new Log("indreports.log");

		$log->write($this->request->get);
		$log->write($this->request->post);

                //log to system table
		$this->load->model('account/activity');
		$activity_data = array(
			'customer_id' => $mcrypt->decrypt($this->request->post['username']),
			'data'        => $this->request->post
			);
                    $this->model_account_activity->addActivity('inventory products', $activity_data);
                    $this->load->model('catalog/product');
                    $this->load->library('user');
                
                $log->write("data");
		$this->session->data['user_id']=$mcrypt->decrypt( $this->request->post['username']);
                $this->user = new User($this->registry);
                $log->write("data re");
        
                //get store id
                $mcrypt->decrypt( $this->request->post['store_id']);
                $sto = $mcrypt->decrypt( $this->request->get['q']);
                $type = $mcrypt->decrypt( $this->request->get['type']);
               //$this->request->post['start']='0';
              // $this->request->post['limit']='20';
              // $this->request->post['q']='12';
 //$this->request->post['type']='5';
 //$this->request->post['user_id']='166';

		$products = $this->model_catalog_product->getIndRecordsStr(
			$mcrypt->decrypt( $this->request->post['start']),
			$mcrypt->decrypt($this->request->post['limit']),
                        $mcrypt->decrypt( $this->request->get['q']),
                        $mcrypt->decrypt( $this->request->get['type']),
                        $mcrypt->decrypt( $this->request->post['user_id'])

		);
               // print_r($products);die;
                 $log->write($products);
                foreach ($products as $product) {
                 $json['products'][] = array(
                        'IND_ID'      => $mcrypt->encrypt($product['IND_ID']),
			'IND_DATE'    => $mcrypt->encrypt(date("d-m-Y",strtotime($product['IND_DATE']))),
			'IND_STATUS'  => $mcrypt->encrypt($product['IND_STATUS'])
		);
		}
                           $log->write($json);
			$this->response->setOutput(json_encode($json));
		
        }
        //******************************* INDENT REPORT END**************************//
        //************** Aasit Product Inventory ***********//
	public function invproducts() {
            $this->load->language('api/cart');
                $json = array();
                $mcrypt=new MCrypt();                    
		$log =new Log("prdinv.log");

		$log->write($this->request->get);
		$log->write($this->request->post);

                //log to system table
		$this->load->model('account/activity');
		$activity_data = array(
			'customer_id' => $mcrypt->decrypt($this->request->post['username']),
			'data'        => $this->request->post
			);
                    $this->model_account_activity->addActivity('inventory products', $activity_data);
                    $this->load->model('catalog/product');
                    $this->load->library('user');
                
                $log->write("data");
		$this->session->data['user_id']=$mcrypt->decrypt( $this->request->post['username']);
                $this->user = new User($this->registry);
                $log->write("data re");
        
                //get store id
                $this->config->set('config_store_id',$mcrypt->decrypt( $this->request->post['store_id']));
		$json = array('success' => true, 'products' => array());
		$json['listcount']=$mcrypt->encrypt($this->model_catalog_product->getTotalQntyProducts(array()));
		$products = $this->model_catalog_product->getProducts(array(
			'start'=> $mcrypt->decrypt( $this->request->post['start']),
			'limit'=> $mcrypt->decrypt($this->request->post['limit'])

		));

		$json['total']=$mcrypt->encrypt(round($this->model_catalog_product->getTotalInventoryAmount($mcrypt->decrypt( $this->request->post['store_id'])) ));
		foreach ($products as $product) {
                    $log->write($product);
        
                    if ($product['image']) {
			$image = $product['image'];
                    } else {
			$image = false;
                    }

                    if ((float)$product['special']) {
                        $special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
                    } else {
			$special = false;
                    }
                    $log->write($product['price']);
                    if(empty($product['price'])||$product['price']==0.0000)
                    {
                        $product['price']=$product['sprice'];
                    }
                    $json['products'][] = array(
                        'id'            => $mcrypt->encrypt($product['product_id']),
			'name'          => $mcrypt->encrypt($product['name']),
                        'sku'           => $mcrypt->encrypt($product['sku']),
			'quantity'      => $mcrypt->encrypt($product['quantity']),
			'description'	=> $mcrypt->encrypt($product['description']),
			'price'		=> $mcrypt->encrypt( $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))+($this->tax->getTax($product['price'], $product['tax_class_id'])))),
                        'rtlr_price'	=> $mcrypt->encrypt($product['rtlr_price']),
			'href'		=> $mcrypt->encrypt($this->url->link('product/product', 'product_id=' . $product['product_id'])),
			'thumb'		=> $mcrypt->encrypt($image),
			'special'	=> $mcrypt->encrypt($special),
			'rating'	=> $mcrypt->encrypt($product['rating']),
			'tax'		=> $mcrypt->encrypt(($this->tax->getTax($product['price'], $product['tax_class_id'])))
			);
		}
        
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

public function productdetail()
{


		 $mcrypt=new MCrypt();
//log to system table
				$this->load->model('account/activity');
				$activity_data = array(
					'customer_id' => $mcrypt->decrypt($this->request->post['username']),
					'data'        => $this->request->post
				);

				$this->model_account_activity->addActivity('product detail', $activity_data);

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);


//check


		if ($product_info) {
			$url = '';
			$this->load->model('catalog/review');
			//$data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);
			$data['description']=$product_info['description'];
			
if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$data['price'] = ($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$data['price'] = false;
			}

			$data['price_formatted']=	($this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'))));
			$data['id'] = (int)$this->request->get['product_id'];
			$data['remote_id'] = (int)$this->request->get['product_id'];
			$data['brand'] = $product_info['manufacturer'];
			$data['category'] =	$this->request->get['category_id'];
			$data['discount_price']="0";
			$data['discount_price_formated'] = '0';
			$data['currency'] ='INR';
			$data['code']='1';	
			$data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
			$data['name'] = $product_info['model'];
			$data['reward'] = $product_info['reward'];
			$data['points'] = $product_info['points'];

			if ($product_info['quantity'] <= 0) 
			{
				$data['stock'] = $product_info['stock_status'];

			} elseif ($this->config->get('config_stock_display')) {
				$data['stock'] = $product_info['quantity'];
			} else {
				$data['stock'] = $this->language->get('text_instock');
			}

			$this->load->model('tool/image');

			if ($product_info['image']) {
				$data['url'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			} else {
				$data['url'] = '';
			}

			if ($product_info['image']) {
				$data['main_image'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
			} else {
				$data['main_image'] = '';
			}


					$data['main_image_high_res']	= $data['main_image'];
					$data['images'] = array();



			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

			foreach ($results as $result) {
				$data['images'][] = array(
					 $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
					 $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
				);
			}

			



					$datasize=array('id'=>"1","remote_id"=>"1",value=>"1");
					array_push($datasize,array('id'=>"2","remote_id"=>"2",value=>"2"));
					$data['variants'][] = array(
							'id'=>"1",
							'color'=>array('id'=>"1","remote_id"=>"1",value=>"1",code=>"1",img=>"1") ,
							'size'=> $datasize,
							'images'=>$data['images'],
							'code'=>"1",
							'related'=>array()
							);

					

}

//end check


	$this->response->setOutput(json_encode($data));



}



	public function productsfarmer() {

            $this->load->language('api/cart');
                $json = array();
/*		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}
                else*/
                    {
$log =new Log("prd.log");
$log->write($this->request->get);
$log->write($this->request->post);
		 $mcrypt=new MCrypt();
		$this->load->model('catalog/product');
//		$this->load->library('user');
$log->write("data");
//		$this->session->data['user_id']=( $this->request->post['username']);
  //              $this->user = new User($this->registry);
$log->write("data re");
$log->write("data");
	
//get store id
$this->config->set('config_store_id','0');//( $this->request->post['store_id']));
		$json = array( 'metadata' => array());



		/*check category id parameter*/
		if (isset($this->request->get['category'])) {
			$category_id =		 ( $this->request->get['category']);
		} else {
			$category_id = 0;
		}

		$products = $this->model_catalog_product->getProducts(array(
			'filter_category_id'        => $category_id
		));



		foreach ($products as $product) {

			if ($product['image']) {
				$image = $product['image'];
			} else {
				$image = false;
			}

			if ((float)$product['special']) {
				$special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}
			$json['metadata']['links']=array('first'=>'1','last'=>'1','next'=>'1','prev'=>'1','self'=>'1');
			$json['metadata']['sorting']="";
			$json['metadata']['records_count']="3";
//records ['metadata'] ($this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))))
			$json['records'][] = array(
					'id'			=> ($product['product_id']),
					'remote_id'		=>($product['product_id']),
					'name'			=> ($product['name']),
					'description'	=> ($product['description']),
					'price'			=> $product['price'] ,
					'price_formatted'=>	($this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')))),
					'category'=>	$category_id,
					'brand' => 'Unnati',	
					'discount_price' => '11',
					'discount_price_formated' => '11',
					'currency' =>'INR',
					'code'=>'1',	
					'url'			=> ($this->url->link('product/product', 'product_id=' . $product['product_id'])),
					'main_image'			=> "https://unnati.world/shop/image/". ($image),
					'main_image_high_res'	=> "https://unnati.world/shop/image/".($image),
					'images' => array(),
					'variants'=>array(),	
					'special'		=> ($special),
					'rating'		=> ($product['rating']),
					'tax'			=> (round($this->tax->getTax($product['price'], $product['tax_class_id']),2, PHP_ROUND_HALF_UP))
			);
		}
        }
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}



}
