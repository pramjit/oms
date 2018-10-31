<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerReportInventoryReport extends Controller {
	public function index() {
		$this->load->language('report/Inventory_report');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = '';
		}
                
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
                /*
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
                */
                if ($this->request->get['filter_store']!="") {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
                
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/inventory_report', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('setting/store');
                $this->load->model('report/Inventory');
		$data['orders'] = array();

		$filter_data = array(
			
			'filter_store' => $filter_store,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		

		$data['orders'] = array();
 if ($this->request->get['filter_store']!="") {
     $order_total = $this->model_report_Inventory->getTotalInventory($filter_data);
 $results = $this->model_report_Inventory->getInventory_report($filter_data);
 
 }
		
                //$taxc=new Tax();                

		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
                                'product_id' => $result['product_id'],
				'product_name' => $result['Product_name'],
                                'store_id'      => $result['store_id'],
                                'store_name'      => $result['store_name'],
				'qnty'   => $result['Qnty'],
				'Amount'      => $result['Amount'],
				'price'     => $result['price'],
                                //'tax'	    => $mcrypt->encrypt(round($taxc->getTax($result['price'], $result['tax_class_id'])))
                                
				
                                
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all_status'] = $this->language->get('text_all_status');

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_title'] = $this->language->get('column_title');
		$data['column_orders'] = $this->language->get('column_orders');
		$data['column_total'] = $this->language->get('column_total');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');
                //$data['stores'] = $this->model_setting_store->getStores();
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['groups'] = array();

		$data['groups'][] = array(
			'text'  => $this->language->get('text_year'),
			'value' => 'year',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_month'),
			'value' => 'month',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_week'),
			'value' => 'week',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_day'),
			'value' => 'day',
		);

		$url = '';

		
                /*
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
                */
                if ($this->request->get['filter_store']!="") {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/inventory_report', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;
		$data['filter_store'] = $filter_store;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/Inventory_report.tpl', $data));
	}



public function product_wise()
        {
            $this->load->language('report/Inventory_report');

        $this->document->setTitle('Inventory Report (Product Wise)');

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else
                {
            //$filter_name = '';
        }
                if (isset($this->request->get['filter_name_id'])) {
            $filter_name_id = $this->request->get['filter_name_id'];
        }
        
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';
                
                if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }
                if (isset($this->request->get['filter_name_id'])) {
            $url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
        }
                
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('report/inventory_report', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $this->load->model('setting/store');
                $this->load->model('report/Inventory');
        $data['orders'] = array();

        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_name_id' => $filter_name_id,
            'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'                  => $this->config->get('config_limit_admin')
        );

        

        $data['orders'] = array();
                if ($this->request->get['filter_name']!="")
                {
                 //$order_total = $this->model_report_Inventory->getTotalInventoryProductWise($filter_data);
                 //$results = $this->model_report_Inventory->getInventory_reportProductWise($filter_data);
 
                }
        
                 $order_total = $this->model_report_Inventory->getTotalInventoryProductWise($filter_data);
                 $results = $this->model_report_Inventory->getInventory_reportProductWise($filter_data);
        foreach ($results as $result) { //print_r($result);
                     $data['orders'][] = array(
                                'product_id' => $result['product_id'],
                'product_name' => $result['Product_name'],
                                'store_id'      => $result['store_id'],
                                'store_name'      => $result['store_name'],
                'qnty'   => $result['Qnty'],
                'Amount'      => $result['Amount'],
                'price'     => $result['price'],
                                
                
                                
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_all_status'] = $this->language->get('text_all_status');

        $data['column_date_start'] = $this->language->get('column_date_start');
        $data['column_date_end'] = $this->language->get('column_date_end');
        $data['column_title'] = $this->language->get('column_title');
        $data['column_orders'] = $this->language->get('column_orders');
        $data['column_total'] = $this->language->get('column_total');

        $data['entry_date_start'] = $this->language->get('entry_date_start');
        $data['entry_date_end'] = $this->language->get('entry_date_end');
        $data['entry_group'] = $this->language->get('entry_group');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_filter'] = $this->language->get('button_filter');

        $data['token'] = $this->session->data['token'];

        $this->load->model('localisation/order_status');
        //$data['stores'] = $this->model_setting_store->getStores();
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        

        $url = '';

                if ($this->request->get['filter_name']!="") {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }
                if ($this->request->get['filter_name_id']!="") {
            $url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
        }
        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/inventory_report/product_wise', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        
        $data['filter_name_id'] = $filter_name_id;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/Inventory_report_product_wise.tpl', $data));
        }


      public function download_excel(){
        if (isset($this->request->get['filter_store'])) {
            $filter_store = $this->request->get['filter_store'];
        } else {
            $filter_store = '';
        }
                
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';
                /*
        if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store=' . $this->request->get['filter_store'];
        }
                */
                if ($this->request->get['filter_store']!="") {
            $url .= '&filter_store=' . $this->request->get['filter_store'];
        }
                
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('report/inventory_report', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $this->load->model('setting/store');
                $this->load->model('report/Inventory');
        $data['orders'] = array();

        $filter_data = array(
            
            'filter_store' => $filter_store,
            'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'                  => $this->config->get('config_limit_admin')
        );

        

        $data['orders'] = array();
 if ($this->request->get['filter_store']!="") 
{

     //$order_total = $this->model_report_Inventory->getTotalInventory($filter_data);
 $results = $this->model_report_Inventory->getInventory_report_excel($filter_data);
 
 }
        
            // Starting the PHPExcel library
    //print_r($this->load->library('PHPExcel'));
    //$this->load->library('PHPExcel/IOFactory');
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
       
        'Store Name',
        'Product ID',
        'Product Name',
        'Qnty',
        'Price',
        'Amount'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    // Fetching the table data
    //$this->load->model('report/searchattendance');
    //$results = $this->model_report_searchattendance->getmdoattendance($filter_data);
    
    $row = 2;
    
    foreach($results as $data)
    {         $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['product_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['Product_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['Qnty']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['price']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['Amount']);
        
            
        

        $row++;
    }

    

    
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Inventory_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
  public function download_excel_product_wise(){
         if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else
                {
            //$filter_name = '';
        }
                if (isset($this->request->get['filter_name_id'])) {
            $filter_name_id = $this->request->get['filter_name_id'];
        }
        

        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_name_id' => $filter_name_id
           
        );

        
        $this->load->model('setting/store');
        $this->load->model('report/Inventory');

        $data['orders'] = array();
                if ($this->request->get['filter_name']!="")
                {
                 $order_total = $this->model_report_Inventory->getTotalInventoryProductWise($filter_data);
                 $results = $this->model_report_Inventory->getInventory_reportProductWise($filter_data);
 
                }
        $order_total = $this->model_report_Inventory->getTotalInventoryProductWise($filter_data);
        $results = $this->model_report_Inventory->getInventory_reportProductWise($filter_data);

            // Starting the PHPExcel library
    //print_r($this->load->library('PHPExcel'));
    //$this->load->library('PHPExcel/IOFactory');
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Store Name',
        'Product Name',
        'Qnty',
        'Price (With out Tax)',
        'Amount (With out Tax) '
       
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    // Fetching the table data
    //$this->load->model('report/searchattendance');
    //$results = $this->model_report_searchattendance->getmdoattendance($filter_data);
    
    $row = 2;
    
/*
foreach ($results as $result) { //print_r($result);
                     $data['orders'][] = array(
                                'product_id' => $result['product_id'],
                'product_name' => $result['Product_name'],
                                'store_id'      => $result['store_id'],
                                'store_name'      => $result['store_name'],
                'qnty'   => $result['Qnty'],
                'Amount'      => $result['Amount'],
                'price'     => $result['price'],
                                
                
                                
            );
        }

*/
    foreach($results as $data)
    {         $col = 0;
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['Product_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['Qnty']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['price']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, ($data['price']*$data['Qnty']));
      
        $row++;
    }

    

    
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Inventory_report_product_wise_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
      public function email_excel(){
        
$this->load->model('report/Inventory');
$data['orders'] = array();

 $results = $this->model_report_Inventory->getInventory_report($filter_data);
             
        
    // Starting the PHPExcel library
    //print_r($this->load->library('PHPExcel'));
    //$this->load->library('PHPExcel/IOFactory');
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        'Product ID',
        'Product Name',
        'Store name',
        'Qnty',
        'Price',
        'Amount'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
    
    $row = 2;
    
    foreach($results as $data)
    {
        $col = 0;
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['product_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['Product_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['Qnty']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['price']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['Amount']);
        
        $row++;
    }

    

    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    //header('Content-Type: application/vnd.ms-excel');
    //header('Content-Disposition: attachment;filename="Inventory_report_'.date('dMy').'.xls"');
    //header('Cache-Control: max-age=0');

    //$objWriter->save('php://output');
    $filename='inventory_report_'.date('ymdhis').'.xls';
    $objWriter->save(DIR_UPLOAD.$filename );
    //
    $mail             = new PHPMailer();

                $body = "<p>Akshamaala Solution Pvt. Ltd.</p>";
                
                $mail->IsSMTP();
                $mail->Host       = "mail.akshamaala.in";
                                                           
                $mail->SMTPAuth   = false;                 
                $mail->SMTPSecure = "";                 
                $mail->Host       = "mail.akshamaala.in";      
                $mail->Port       = 25;                  
                $mail->Username   = "mis@akshamaala.in";  
                $mail->Password   = "mismis";            

                $mail->SetFrom('mail.akshamaala.in', 'Akshamaala');

                $mail->AddReplyTo('mail.akshamaala.in','Akshamaala');

                $mail->Subject    = "Inventory Report";

                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

                $mail->MsgHTML($body);
                
                //to get the email of supplier
                
                $mail->AddAddress('chetan.singh@akshamaala.com', "Chetan Singh");
                
                

                $mail->AddAttachment(DIR_UPLOAD.$filename);
                
                if(!$mail->Send())
                {
                  echo "Mailer Error: " . $mail->ErrorInfo;
                }
                else
                {
                  if(!unlink(DIR_UPLOAD.$filename))
                  {
                      echo ("Error deleting ");
                  }
                  else
                  {
                     echo ("Deleted ");
                  }
                                  
                }
        
    }  
    
}