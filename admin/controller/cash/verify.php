<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerCashVerify extends Controller {
	public function index() {
		$this->load->language('report/cash_report');

		$this->document->setTitle('Cash List');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}
                
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
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
			'href' => $this->url->link('report/cash_report', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		
                $this->load->model('cash/verify');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_cash_verify->getTotalCash_transation($filter_data);

		$data['orders'] = array();

		$results = $this->model_cash_verify->getCash_report($filter_data);
                
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
				'SIID' => $result['transid'],
				'amount'   => $result['amount'],
				'store_id'      => $result['store_id'],
				'name'     => $result['name'],
                                'status'     => $result['status'],
                                'date_added'=>  date($this->language->get('date_format_short'), strtotime($result['date_added'])),   
				'bank_name'      => $result['bank_name']
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('cash/verify', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('cash/cash_report.tpl', $data));
	}
        public function get_store_data()
        {
           $this->load->language('report/cash_report');
            //$_REQUEST["store_id"];
            $this->load->model('cash/verify');
            $getstoresdata = $this->model_cash_verify->getstoresdata($_REQUEST["store_id"]);
            echo $getstoresdata["currentcredit"];

        }
         public function verify() {
             
             $this->document->setTitle('Verify Cash');
             
             
             $this->load->model('cash/verify');
             
             if ($this->request->server['REQUEST_METHOD'] == 'POST')
             { 
                 $this->model_cash_verify->verify_cash($this->request->post);
                 $this->model_cash_verify->insert_into_store_trans($this->request->post);
                 $this->session->data['success'] = 'Transaction Verified Successfully';
                 $this->response->redirect($this->url->link('cash/verify/verify', 'token=' . $this->session->data['token'] . $url, 'SSL'));
             }
		

		
                
             $this->getForm();
         }
         protected function getForm() {
             $SID = $this->request->get["SID"];
	     $filter_data = array(
			'transid'	     => $SID
		);	
                $results = $this->model_cash_verify->getCash_record($filter_data);
                $getTransactionTypesresults = $this->model_cash_verify->getTransactionTypes();
                
                
                $this->load->model('user/user');
                $this->load->model('setting/store');
                $user_data = $this->model_user_user->getUser($results["user_id"]);
                
                //print_r($results);
                $logged_user_data = $this->user->getId();
                
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['deposit_date'])) {
			$data['error_deposit_date'] = $this->error['deposit_date'];
		} else {
			$data['error_deposit_date'] = '';
		}

		

		$url = '';

		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Verify Cash',
			'href' => $this->url->link('cash/verify', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		

		$data['cancel'] = $this->url->link('cash/verify', 'token=' . $this->session->data['token'] . $url, 'SSL');
                $data['submit_by'] =$user_data["firstname"]." ".$user_data["lastname"];
		$data['bank_name'] = $results["bank_name"];
                $data['amount'] = $results["amount"];
                $data['store_name'] = $results["name"];
                $data['store_id'] = $results["store_id"];
                $data['creditlimit'] = $results["creditlimit"];
                $data['currentcredit'] = $results["currentcredit"];
                $data['transid'] = $this->request->get["SID"];
                $data['date_added'] = $results["date_added"];
                $data['verify_date'] = $results["deposit_date"];
                $data['transaction_number'] = $results["transaction_number"];
                $data['branch_code'] = $results["branch_code"];
                $data['branch_location'] = $results["branch_location"];
                $data['remarks'] = $results["remarks"];
                $data['stores'] = $this->model_setting_store->getStores();
                $data['TransactionTypes'] =  $getTransactionTypesresults;
                
                $data['logged_user'] = $logged_user_data;
                if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
                $data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                $data['action'] = $this->url->link('cash/verify/verify', 'token=' . $this->session->data['token'] . $url.'&SID='.$this->request->get["SID"], 'SSL');
		$this->response->setOutput($this->load->view('cash/verify_form.tpl', $data));
	}
        public function download_excel() {
		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}
                
                $this->load->model('report/cash');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end
			
		);

		$data['orders'] = array();

		$results = $this->model_report_cash->getCash_report($filter_data);
                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        'Store Name ',
        'Bank',
        'Date',
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
    
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['bank_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date('Y-m-d',strtotime($data['date_added'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['amount']);
        
            
        

        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Cash_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
		
	}
        public function email_excel() {
           
                $this->load->model('report/cash');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => date('Y-m-d'),
			'filter_date_end'	     => date('Y-m-d')
			
		);

		$data['orders'] = array();

		$results = $this->model_report_cash->getCash_report($filter_data);
                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        'Store Name ',
        'Bank',
        'Date',
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
    
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['bank_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date('Y-m-d',strtotime($data['date_added'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['amount']);
        
            
        

        $row++;
    }
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $filename='cash_report_'.date('ymdhis').'.xls';
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

				$mail->Subject    = "Tagged orders Report";

				$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

				$mail->MsgHTML($body);
				
				//to get the email of supplier
				
				$mail->AddAddress('vipin.kumar@aspltech.com', "vipin Chahal");
				
				

				$mail->AddAttachment(DIR_UPLOAD.$filename);
				
				if(!$mail->Send()) 
				{
				  echo "Mailer Error: " . $mail->ErrorInfo;
				} 
				else
				{ 
				  if(!unlink($file_to_attach))
				  {
					  echo ("Error deleting $file_to_attach");
				  }
				  else
				  {
					 echo ("Deleted $file_to_attach");
				  }
                                  
				}
        }
}