<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerReportPossummaryreport extends Controller {
	public function index() {
		$this->load->language('report/sale_report');
		$this->load->language('report/sale_order');
		$this->document->setTitle('Retailer Summary');
		
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

		if (isset($this->request->get['mo_id'])) {
			$data['filter_mo'] = $this->request->get['mo_id'];
                        $data['filter_mo_nm'] = $this->request->get['mo_nm'];
		} else {
			$data['filter_mo']= '';
                        $data['filter_mo_nm']='Select MO';
		}
                if (isset($this->request->get['dist_id'])) {
			$data['filter_dist'] = $this->request->get['dist_id'];
                        $data['filter_dist_nm'] = $this->request->get['dist_nm'];
		} else {
			$data['filter_dist'] = '';
                        $data['filter_dist_nm'] = 'Select District';
		}
                if (isset($this->request->get['teh_id'])) {
			$data['filter_teh'] = $this->request->get['teh_id'];
                        $data['filter_teh_nm'] = $this->request->get['teh_nm'];
		} else {
			$data['filter_teh'] = '';
                        $data['filter_teh_nm'] = 'Select Tehsil';
		}


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Sale Summary',
			'href' => $this->url->link('report/possummaryreport', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');

		$this->load->model('setting/store');
                $this->load->model('report/possummaryreport');
                $data["listMOs"] = $this->model_report_possummaryreport->getMo();
                $data["listDISTs"] = $this->model_report_possummaryreport->getdistrict();
                $data["listTEHs"] = $this->model_report_possummaryreport->gettehsil();
                
              
                
                
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'mo_id'           => $filter_mo,
                        'dist_id'         => $filter_dist,
                        'teh_id'          => $filter_teh,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

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


		

		$data['orders'] = array();
                
                   
                $order_total = $this->model_report_possummaryreport->getTotalSale($filter_data);
		$results = $this->model_report_possummaryreport->getdailysummary($filter_data);
                
               //print_r($results);
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
                                       'RETAILER_NAME'    => $result['RETAILER_NAME'],
                                'CONTACT_PERSON'    => $result['CONTACT_PERSON'],
                                'MOBILE'    => $result['MOBILE'],
                                'DISTRICT_NAME'    => $result['DISTRICT_NAME'],
				'MO_NAME'      => $result['MO_NAME'],
				'CR_DATE'     => $result['CR_DATE'],
				'TEHSIL_NAME'          => $result['TEHSIL_NAME'],
				'BLOCK_NAME'       => $result['BLOCK_NAME'],
                                        ' PINCODE'       => $result['PINCODE'],
                                         'TIN_GST_NO'       => $result['TIN_GST_NO'],
                                        'PAN_NO'       => $result['PAN_NO'],
                                        'ADDHAR_NO'       => $result['ADDHAR_NO'],
                                        'FRC_NO'       => $result['FRC_NO'],
                                        'FRC_VALID_UPTO'       => $result['FRC_VALID_UPTO'],
                                        'SEED_LICENCE'       => $result['SEED_LICENCE'],
                                        'SEED_LICENCE_UPTO'       => $result['SEED_LICENCE_UPTO'],
                                        'MFMS_ID'       => $result['MFMS_ID']
				
			);
		}

		$data['token'] = $this->session->data['token'];
                
		$url = '';
				
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}	
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/possummaryreport', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;


		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/possummaryreport.tpl', $data));
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

		if (isset($this->request->get['mo_id'])) {
			$data['filter_mo'] = $this->request->get['mo_id'];
                        $data['filter_mo_nm'] = $this->request->get['mo_nm'];
		} else {
			$data['filter_mo']= '';
                        $data['filter_mo_nm']='Select MO';
		}
                if (isset($this->request->get['dist_id'])) {
			$data['filter_dist'] = $this->request->get['dist_id'];
                        $data['filter_dist_nm'] = $this->request->get['dist_nm'];
		} else {
			$data['filter_dist'] = '';
                        $data['filter_dist_nm'] = 'Select District';
		}
                if (isset($this->request->get['teh_id'])) {
			$data['filter_teh'] = $this->request->get['teh_id'];
                        $data['filter_teh_nm'] = $this->request->get['teh_nm'];
		} else {
			$data['filter_teh'] = '';
                        $data['filter_teh_nm'] = 'Select Tehsil';
		}


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_group'           => $filter_group
			
		);

        $this->load->model('report/possummaryreport');
        $order_total = $this->model_report_possummaryreport->getTotalSale($filter_data);

		$data['orders'] = array();

		$results = $this->model_report_possummaryreport->getdailysummary($filter_data);
                
		                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Firm Name',
        'Contact Person',
        'Mobile',
        'DISTRICT NAME',
        'TEHSIL NAME',
        'BLOCK NAME',
        'PINCODE',
        'TIN_GST_NO',
        'PAN_NO',
        'ADDHAR_NO',
        'FRC_NO',
        'FRC_VALID_UPTO',
        'SEED_LICENCE',
        'SEED_LICENCE_UPTO',
        'MFMS_ID',
        'CR_DATE',
        'MO_NAME',
        'No of Visit'
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
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['RETAILER_NAME']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['CONTACT_PERSON']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['MOBILE']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['DISTRICT_NAME']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['TEHSIL_NAME']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['BLOCK_NAME']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['PINCODE']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['TIN_GST_NO']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['PAN_NO']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $data['ADDHAR_NO']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $data['FRC_NO']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $data['FRC_VALID_UPTO']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $data['SEED_LICENCE']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $data['SEED_LICENCE_UPTO']);   
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $data['MFMS_ID']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $data['CR_DATE']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, $data['noofvisit']);
             
   
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Retailer_Summary_Report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
        public function email_excel() {
           $this->load->model('report/dailysummaryreport');
        $order_total = $this->model_report_dailysummaryreport->getTotalSale($filter_data);

		$data['orders'] = array();

		$results = $this->model_report_dailysummaryreport->getSale_summary($filter_data);
                
		                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        
        'Store Name',
	'Cash',
	'Tagged',
        'Total'
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
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']); 
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['Cash']); 
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['Tagged']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, ($data['Cash']+$data['Tagged']));
             
   
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $filename='dailysummaryreport_'.date('ymdhis').'.xls';
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

                $mail->Subject    = "Sale Summary";

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
