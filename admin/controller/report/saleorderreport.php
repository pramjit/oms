<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerReportSaleorderreport extends Controller {
	public function index() {
		$this->load->language('report/sale_report');
		$this->load->language('report/sale_order');
		$this->document->setTitle('Ta Summary');
		
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

		//echo $this->request->get['mo_id'];exit;
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
			'href' => $this->url->link('report/saleorderreport', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');

		$this->load->model('setting/store');
                $this->load->model('report/saleorderreport');
                $data["listMOs"] = $this->model_report_saleorderreport->getMo();
                $data["listWSs"] = $this->model_report_saleorderreport->getWs();
                $data["listDISTs"] = $this->model_report_saleorderreport->getdistrict();
                $data["listTEHs"] = $this->model_report_saleorderreport->gettehsil();
                
              
                
                
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
                if (!empty($this->request->get['mo_id']) || !empty($this->request->get['ws_id']) || !empty($this->request->get['filter_date_start']) || !empty($this->request->get['filter_date_end'])) {
                $order_total = $this->model_report_saleorderreport->getTotalSale($filter_data);
		$results = $this->model_report_saleorderreport->getdailysummary($filter_data);
                }
               // print_r($results);
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
                                'firstname'    => $result['firstname'],
                                'telephone'    => $result['telephone'],
				'TA_DATE'      => $result['TA_DATE'],
				'PLACE_FROM'   => $result['PLACE_FROM'],
                                'PLACE_TO'=> $result['PLACE_TO'],
				'PLACE_TO1'    => $result['PLACE_TO1'],
				'PLACE_TO2'    => $result['PLACE_TO2'],
                                'PLACE_TO3'    => $result['PLACE_TO3'],
                                'PLACE_TO4'    => $result['PLACE_TO4'],
				'TA_EMP_ID'    => $result['TA_EMP_ID'],
				'TOTAL'        => $result['TOTAL'],
				'OPEN_MTR'     => $result['OPEN_MTR'],
				'CLOSE_MTR'    => $result['CLOSE_MTR'],
                                'TAX_RS'       => $result['TAX_RS'],
				'FARE_RS'      => $result['FARE_RS'],
				'PETROL_LTR'   => $result['PETROL_LTR'],
				'LOCAL_CONVEYANCE'=> $result['LOCAL_CONVEYANCE'],
				'HOTEL_RS'        => $result['HOTEL_RS'],
                                'SID'             => $result['SID'],
                                'REMARKS'         => $result['REMARKS'],
				'PRINTING_RS'     => $result['PRINTING_RS'],
                                'MISC_RS'         => $result['MISC_RS'],
                                'POSTAGE_RS'      => $result['POSTAGE_RS']
                             
				
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
		$pagination->url = $this->url->link('report/saleorderreport', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;


		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/saleorderreport.tpl', $data));
	}

 public function gettaupload(){
     
    $this->load->model('report/saleorderreport');    
    $taupl = $this->request->post['taupl'];
    //print_r($taupl);die;
    $tadata= $this->model_report_saleorderreport->gettaupload($taupl);
    ?>
    <table width="365" height="100" border="1">
        
        <tr>
        <td >UPLOAD -I</td>
        <td id="up1"><a href="<?php echo UPLOAD_IMAGE.$tadata['UPLOAD_1'];?>" download="download"><?php echo $tadata['UPLOAD_1'];?></a></td>
        </tr>
        <tr>
        <td >UPLOAD -II</td>
        <td id="up1"><a href="<?php echo UPLOAD_IMAGE.$tadata['UPLOAD_2'];?>" download="download"><?php echo $tadata['UPLOAD_2'];?></a></td>
        </tr>
        <tr>
        <td >UPLOAD -III</td>
        <td id="up1"><a href="<?php echo UPLOAD_IMAGE.$tadata['UPLOAD_3'];?>" download="download"><?php echo $tadata['UPLOAD_3'];?></a></td>
        </tr>
        <tr>
        <td >UPLOAD -IV</td>
        <td id="up1"><a href="<?php echo UPLOAD_IMAGE.$tadata['UPLOAD_4'];?>" download="download"><?php echo $tadata['UPLOAD_4'];?></a></td>
        </tr>
       
    </table>
    <?php
   
   // echo json_encode($tadata);
    }
        
public function download_excel() {

	if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}


		if (isset($this->request->get['mo_id'])) {
			$data['filter_mo'] = $this->request->get['mo_id'];
                        $data['filter_mo_nm'] = $this->request->get['mo_nm'];
		} else {
			$data['filter_mo']= '';
                        $data['filter_mo_nm']='Select MO';
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

        $this->load->model('report/indentreport');
        $order_total = $this->model_report_saleorderreport->getTotalSale($filter_data);

		$data['orders'] = array();

		$results = $this->model_report_saleorderreport->getdailysummary($filter_data);
                
		                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        
        'Employee Name',
        'Employee Mobile',
        'Place From',
        'Place To',
        'Place From 1',
        'Place From 2',
        'Place From 3',
        'Place From 4',        
        'Open Kmr',
        'Close Kms',
        'Total Kms',
        'Toll Tax (Rs)',
        'Fare/Taxi (Rs)',
        'Petrol Purchase(Rs)',
        'Local Conveyance(Rs)',
        'Hotel/Lodging(Rs)',
        'Postage (rs)',
        'Printing Stationary(Rs)',
        'Misc (rs)',
        'Date',       
        'Remarks'
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
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['firstname']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['telephone']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['PLACE_FROM']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['PLACE_TO']);        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['PLACE_TO1']);        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['PLACE_TO2']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['PLACE_TO3']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['PLACE_TO4']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['OPEN_MTR']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $data['CLOSE_MTR']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $data['TOTAL']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $data['TAX_RS']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $data['FARE_RS']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $data['PETROL_LTR']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $data['LOCAL_CONVEYANCE']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $data['HOTEL_RS']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, $data['POSTAGE_RS']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, $data['PRINTING_RS']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $row, $data['MISC_RS']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $row, $data['TA_DATE']);        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $row, $data['REMARKS']);
             
   
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Ta_Summary_Report_'.date('dMy').'.xls"');
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
