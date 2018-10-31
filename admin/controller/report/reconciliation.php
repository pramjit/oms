<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerReportReconciliation extends Controller {
	public function index() {
		$this->load->language('report/reconciliation');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
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

		if (isset($this->request->get['filter_store'])) {
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
			'href' => $this->url->link('report/reconciliation', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/reconciliation');
                 $this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_report_reconciliation->getTotalOrders($filter_data);

		$results = $this->model_report_reconciliation->getOrders($filter_data);

		foreach ($results as $result) {
                        
                        $grower_info = $this->model_report_reconciliation->getGrowerDetails($result['order_id']);
                        $farmer_info=explode('-', $grower_info);
      
                $grower_id=@$farmer_info[0];
                $farmer_name=ucwords(strtolower(@$farmer_info[1]));
                $father_name=ucwords(strtolower(@$farmer_info[2]));
                
			$data['orders'][] = array(
				
				'date'       => date($this->language->get('date_format_short'), strtotime($result['date'])),
				'order_id'   => $result['order_id'],
                                'store_name' => $result['store_name'],
                                'store_id'   => $result['store_id'],
                                'total'      => $result['total'],
                                'tagged'     => $result['tagged'],
                                'grower_id'  => $grower_id,
                                'farmer_name'=> $farmer_name,
                                'unit'       => $result['company']
				
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all_status'] = $this->language->get('text_all_status');

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_orders'] = $this->language->get('column_orders');
		$data['column_products'] = $this->language->get('column_products');
		$data['column_tax'] = $this->language->get('column_tax');
		$data['column_total'] = $this->language->get('column_total');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');
                $data['stores'] = $this->model_setting_store->getStores();
		
		$url = '';
                if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
                if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}

		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/reconciliation', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
                $data['filter_store'] = $filter_store;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/reconciliation.tpl', $data));
	}
        public function download_pdf() {
            $this->load->language('report/reconciliation');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}

		$this->load->model('report/reconciliation');
                 $this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end
		);

		$order_total = $this->model_report_reconciliation->getTotalOrders($filter_data);

		$results = $this->model_report_reconciliation->getOrders($filter_data);

		foreach ($results as $result) {
                        
                        $grower_info = $this->model_report_reconciliation->getGrowerDetails($result['order_id']);
                        $farmer_info=explode('-', $grower_info);
      
                $grower_id=@$farmer_info[0];
                $farmer_name=ucwords(strtolower(@$farmer_info[1]));
                $father_name=ucwords(strtolower(@$farmer_info[2]));
                
			$data['orders'][] = array(
				
				'date'       => date($this->language->get('date_format_short'), strtotime($result['date'])),
				'order_id'   => $result['order_id'],
                                'store_name' => $result['store_name'],
                                'store_id'   => $result['store_id'],
                                'total'      => $result['total'],
                                'tagged'     => $result['tagged'],
                                'grower_id'  => $grower_id,
                                'farmer_name'=> $farmer_name,
                                'unit'       => $result['company']
				
			);
		}

		
		
                require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
                
                $html = $this->load->view('report/reconciliation_pdf.tpl',$data);
                //$this->response->setOutput($this->load->view('tag/order_invoice.tpl', $data));
               
                $base_url = HTTP_CATALOG;
                
                $mpdf = new mPDF('c','A4','','' , 5 , 5 , 25 , 10 , 5 , 7);
                
                $header = '<div class="header"><div class="logo">
<img src="../image/letterhead_text.png" style="height: 40px; width: 121px;" />
<img src="../image/letterhead_log.png" style="height: 55px; width: 121px;float: right;" />
                         </div
<div class="company">
<img src="../image/letterhead_topline.png" style="height: 10px; width: 100%;" />
</div></div><hr />';
    //$header='';
                $mpdf->SetHTMLHeader($header, 'O', false);
                    
                $footer = '<div class="footer"><div class="address"><b>Akshamaala Solutions Pvt. Ltd. </b></div></div>';

                //$footer = '<div class="footer"><div class="address"><b>Akshamaala Solutions Pvt. Ltd. : </b>'.$data['company_address'].'</div><div class="pageno">{PAGENO}</div></div>';
                $mpdf->SetHTMLFooter($footer);
                    
                $mpdf->SetDisplayMode('fullpage');
    
                $mpdf->list_indent_first_level = 0;
    
                $mpdf->WriteHTML($html);
                
                $filename='Reconciliation Report'.$order_id.'.pdf';
                //$mpdf->Output(DIR_UPLOAD.$filename,'F');
                $mpdf->Output($filename,'D');
                
                
		$this->response->setOutput($this->load->view('report/reconciliation_pdf.tpl', $data));
        }
        public function download_excel() {
            
               if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}

		

		$this->load->model('report/reconciliation');
                

		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end
		);

		$order_total = $this->model_report_reconciliation->getTotalOrders($filter_data);

		$results = $this->model_report_reconciliation->getOrders($filter_data);

    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Unit',
        'Store Name',
        'Store ID',
        'Order ID',
        'Grower ID',
        'Name',
        'Date',
        'Amount',
        'Tagged'
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
        
         $grower_info = $this->model_report_reconciliation->getGrowerDetails($data['order_id']);
         $farmer_info=explode('-', $grower_info);
         $grower_id=@$farmer_info[0];
         $farmer_name=ucwords(strtolower(@$farmer_info[1]));
         $father_name=ucwords(strtolower(@$farmer_info[2]));
                
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, @$data['company'] );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['store_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['order_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $grower_id);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $farmer_name);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, date('Y-m-d',strtotime($data['date'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['total']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['tagged']);
           
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="reconciliation_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
    
    }

     public function email_excel() {
        
        

				
		$this->load->model('report/sale');
                
		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => '',
			'filter_date_start'	     => date('Y-m-d'),
			'filter_date_end'	     => date('Y-m-d'),
			
			'filter_order_status_id' => '5'
		);

		

		$results = $this->model_report_sale->getOrders($filter_data);

		

        
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Date Start',
        'Date End',
        'No. Orders',
        'Store',
        'Total'
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
    { 
        $col = 0;
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, date('Y-m-d',strtotime($data['date_start'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date('Y-m-d',strtotime($data['date_end'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['orders']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['total']);            
        

        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    //header('Content-Type: application/vnd.ms-excel');
    //header('Content-Disposition: attachment;filename="tagged_report_'.date('dMy').'.xls"');
    //header('Cache-Control: max-age=0');

    //$objWriter->save('php://output');
    $filename='sale_order_report_'.date('ymdhis').'.xls';
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
		//$mail->From = "mail.akshamaala.in";
		//$mail->FromName = "Support Team";
                $mail->SetFrom('mail.akshamaala.in', 'Akshamaala');

                $mail->AddReplyTo('mail.akshamaala.in','Akshamaala');

                $mail->Subject    = "Sale orders Report";

                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

                $mail->MsgHTML($body);
                
                //to get the email of supplier
                
                $mail->AddAddress('chetan.singh@akshamaala.com', "Chetan Singh");
                //$mail->AddCC('ashok.prasad@akshamaala.com', "Ashok Prasad");
                

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