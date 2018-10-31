<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerReportPaymentreport extends Controller {
	public function index() {
		$this->load->language('report/sale_report');
		$this->load->language('report/sale_order');
		$this->document->setTitle('Payment Report');
		
        $this->load->model('user/user');
		$user_info = $this->model_user_user->getUser($this->user->getId());
        $userid=$user_info['customer_id'];
        $usergroupid=$user_info['user_group_id'];
                
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
	   if (isset($this->request->get['ws_id'])) {
			$data['filter_ws'] = $this->request->get['ws_id'];
                        $data['filter_ws_nm'] = $this->request->get['ws_id'];
		} else {
			$data['filter_ws'] = '';
                        $data['filter_ws_nm'] = 'Select Wholesale';
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
			'href' => $this->url->link('report/tasummaryreport', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');

		$this->load->model('setting/store');
                $this->load->model('report/paymentreport');
          
                $data["listWSs"] = $this->model_report_paymentreport->getWs();
              
                
                
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'mo_id'           => $filter_mo,
                        'dist_id'         => $filter_dist,
                        'teh_id'          => $filter_teh,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin'),
                     'usrid'           => $user_info['customer_id'],
                     'usrgrpid'           => $user_info['user_group_id']
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
        	if (isset($this->request->get['filter_date_start'])) {       
                $order_total = $this->model_report_paymentreport->getTotalSale($filter_data);
		
                $results = $this->model_report_paymentreport->getdailysummary($filter_data);
                
			}   
                //print_r($results);
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
                                'party_name'    => $result['party_name'],
                                'Par_Addrs'    => $result['Par_Addrs'],
                                'Amnt_Bank'    => $result['Amnt_Bank'],
				'Amnt_Ref'      => $result['Amnt_Ref'],
				'Amnt_Date'   => $result['Amnt_Date'],
                                'Amnt_Type'     => $result['Amnt_Type'],
				'Amnt_Rs'    => $result['Amnt_Rs'],
				'Sid'    => $result['Sid'],
				'PAYMENT_STATUS'    => $result['PAYMENT_STATUS'],
				'PAYMENT_DATE'    => $result['PAYMENT_DATE']
                             
				
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
		$pagination->url = $this->url->link('report/paymentreport', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;


		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/paymentreport.tpl', $data));
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
 if (isset($this->request->get['ws_id'])) {
			$data['filter_ws'] = $this->request->get['ws_id'];
                        $data['filter_ws_nm'] = $this->request->get['ws_id'];
		} else {
			$data['filter_ws'] = '';
                        $data['filter_ws_nm'] = 'Select Wholesale';
		}


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_ws'           => $filter_ws
			
		);

        $this->load->model('report/paymentreport');
       // $order_total = $this->model_report_paymentreport->getTotalSale($filter_data);

		$data['orders'] = array();

		$results = $this->model_report_paymentreport->getdailysummary($filter_data);
                
		                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
                   
                    'PARTY NAME',
                    'ADDRESS',
                    'PAYMENT DATE',
                    'BANK',                  
                    'PAYMENT TYPE',
                    'AMOUNT REF',
                    'AMOUNT'	
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

        if($data['Amnt_Type']=='1'){ $rk= "Cheque Number"; } if($data['Amnt_Type']=='2'){ $rk= "DD Number";}
        if($data['Amnt_Type']=='3'){ $rk= "UTR Number";}if($data['Amnt_Type']=='4'){ $rk= "Cash";}
       // print_r($results);
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['party_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['Par_Addrs']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['Amnt_Date']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['Amnt_Bank']);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $rk);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['Amnt_Ref']);           
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['Amnt_Rs']);        
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Payment_Report_'.date('dMy').'.xls"');
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
    public function sendStatus(){
        $log=new Log("ADM_RCV_PAY_".date('Ymd').".log");
		$this->load->model('report/paymentreport');
		$suc = $this->model_report_paymentreport->updateStatus($this->request->post);
        if($suc){
        $this->load->model('user/user');
        $user_info = $this->model_user_user->getUser($this->user->getId());
        $admId=$user_info['user_id'];   
        
        $payId=$this->request->post['sid'];
        $MsgDtls = $this->model_report_paymentreport->MsgDtls($payId);
        $DealerName=$MsgDtls['DL_NAME'];
        $DealerDist=$MsgDtls['DL_DIST'];
        $TotalRs=$MsgDtls['TOT_RS'];
        $SubDate=date('dM, Y');
        $OrderStsID=$this->request->post['status_name'];
        if($OrderStsID==1){
            $RcvSts='Pending';
        }elseif ($OrderStsID==2) {
                $RcvSts='Received';
            }elseif ($OrderStsID==3) {
                    $RcvSts='Cheque Bounced';
                } else {
                    $RcvSts='NA';
                }
        
            $StoId=$MsgDtls['STO_ID'];
        
            $DL_MOB=$MsgDtls['DL_MOB'];
            $AM_MOB=$MsgDtls['AM_MOB'];
            $MO_MOB=$MsgDtls['MO_MOB'];
            //$InMob_I='918392913500'; // for fixed
            $InMob_II='917830800900'; // for fixed
            //$InMob_III='919971813600'; // for test    
            //$InMob_IV='919205540431'; // for test  
            $numbers = array($DL_MOB, $AM_MOB, $MO_MOB,$InMob_II);
        
        
        
            //$textmsg='Payment of M/s DealerName of Rs. TotalRs /- on RcvDate is OrderSts by Company.';
            $textmsg='Payment of M/s PartyName , DistName of Rs. RcvAmount/- on RcvDate is RcvStatus by Company.';
            $textmsg=str_replace('PartyName',$DealerName,$textmsg);
            $textmsg=str_replace('DistName',$DealerDist,$textmsg);
            $textmsg=str_replace('RcvAmount',$TotalRs,$textmsg);
            $textmsg=str_replace('RcvDate',$SubDate,$textmsg);
            $textmsg=str_replace('RcvStatus',$RcvSts,$textmsg);
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
            $type='MO_SUB_MT';
            $OrderId=101;// Payment Received
            $msgMaster = $this->model_report_paymentreport->msgMaster($OrderId,$type,$admId,$StoId,$textmsg,$numbers,$responseBody);
            $log->write("Message2DB:".$msgMaster);        
                
        }
        echo $suc;
    }

}
