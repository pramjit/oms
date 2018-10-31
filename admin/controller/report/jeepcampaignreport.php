<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerReportJeepcampaignreport extends Controller {
	public function index() {
		$this->load->language('report/sale_report');
		$this->load->language('report/sale_order');
		$this->document->setTitle('Jeep Campaign Report');
		
                $this->load->model('user/user');
		$user_info = $this->model_user_user->getUser($this->user->getId());
                $userid=$user_info['customer_id'];
                $usergroupid=$user_info['user_group_id'];
		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = NULL;//date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
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
		
		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Jeep Campaign Report',
			'href' => $this->url->link('report/jeepcampaignreport', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');

		$this->load->model('setting/store');
                $this->load->model('report/jeepcampaignreport');
                $data["listMOs"] = $this->model_report_jeepcampaignreport->getMo($usergroupid,$userid);
              
                
              
                
                
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
               
                
                $order_total = $this->model_report_jeepcampaignreport->getTotalSale($filter_data);
		if($userid=='56' || $userid=='285')
                {
		$results = $this->model_report_jeepcampaignreport->getadmindtl($filter_data);
                }
                 elseif($usergroupid=="3"){
                    $results = $this->model_report_jeepcampaignreport->getdailyam($filter_data);
                }
                else{
                    $results = $this->model_report_jeepcampaignreport->getdailysummary($filter_data);
                }
		foreach ($results as $result) { //print_r($result);
                                 $data['WHOLE_SELLER'] = array();
                                 $rmc= explode(",",$result["WHOLE_SELLER"]);
                                 $tot=count($rmc); 
                                 for($i=0;$i<$tot;$i++)
                                 {
                                   
                                   $res = $this->model_report_jeepcampaignreport->getwholesalename($rmc[$i]);
                                   $data['WHOLE_SELLER'][]=array('name'=>$res);
                                  
                                 }
                                 // print_r($data['WHOLE_SELLER']);die;
			         $data['orders'][] = array(
                                'MO_Name'    => $result['MO_Name'],
                                'RETAILER_NAME'    => $result['RETAILER_NAME'],
				'WHOLE_SELLER'      => $data['WHOLE_SELLER'],
				'cr_date'     => $result['cr_date'],
				'START_VILLAGE'          => $result['START_VILLAGE'],
				'OPEN_KM'       => $result['OPEN_KM'],
                                'village_covered'          =>$result['village_covered'],
                                     'JEEP_ID' =>$result['JEEP_ID']
				
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
		$pagination->url = $this->url->link('report/jeepcampaignreport', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
                $filter_date_start=$this->request->get['filter_date_start'];
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;


		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/jeepcampaignreport.tpl', $data));
	}

   public function getfarmerdtl(){
     
    $this->load->model('report/jeepcampaignreport');    
    $jeep = $this->request->get['jeepid'];
    $orders= $this->model_report_jeepcampaignreport->getjeepfarmerdtl($jeep);
    ?><table width="400" border="0">
     
            <thead>
            <tr style="font-weight:bold">
                <td >Name</td>
                <td >Mobile</td>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) { //print_r($order); ?>
                <tr>
                    <td><?php echo $order['FARMER_NAME']; ?></td>
                    <td><?php echo $order['FAR_MOBILE']; ?></td>               
                </tr>
                <?php }  ?>
            </tbody>
        </table>
    <?php
    }
    
       public function getOtherdtl(){
     
    $this->load->model('report/jeepcampaignreport');    
    $jeep = $this->request->get['jeepid'];
    $orders= $this->model_report_jeepcampaignreport->getjeepOthersdtl($jeep);
    ?><table width="400" border="0">
      
            <thead>
            <tr style="font-weight:bold">
                 <td >Jeep Vendor</td>
                 <td >Driver Name</td>
                 <td >Driver Mobile</td>
                 <td >Vehicle No</td>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) { //print_r($order); ?>
                <tr>
                    <td><?php echo $order['VENDOR_NAME']; ?></td>
                    <td><?php echo $order['DRIVER_NAME']; ?></td>
                    <td><?php echo $order['DRIVER_MOBILE']; ?></td>
                    <td><?php echo $order['VEHICLE_NO']; ?></td>
                </tr>
                <?php }  ?>
            </tbody>
        </table>
    <?php
    }
        
public function download_excel() {

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = NULL;//date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
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
			
			'filter_group'           => $filter_group
			
		);

        $this->load->model('report/jeepcampaignreport');
        $order_total = $this->model_report_jeepcampaignreport->getTotalSale($filter_data);

		$data['orders'] = array();

		$results = $this->model_report_jeepcampaignreport->getdailysummary($filter_data);
                
		                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        
        'Marketing Officer',
        'Retailer Name',
        'Wholeseller Name',
        'Date',        
        'Start Village',
        'Open Km',
        'Village Covered'
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
        
         $wholeseller = array();
        $wholeseller=explode(",",$data['WHOLE_SELLER']);
        $list=array();
        for($k=0;$k<count($wholeseller);$k++)
        {
            $res = $this->model_report_jeepcampaignreport->getwholesalename($wholeseller[$k]);
            if(!empty($res))
            {
                array_push($list,$res);
              
            }
        }
        $listwhole=implode(",",$list);
        $col = 0;
         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['MO_Name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['RETAILER_NAME']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $listwhole);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['cr_date']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['START_VILLAGE']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['OPEN_KM']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['village_covered']);
        
             
   
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Jeepcampaignreport_'.date('dMy').'.xls"');
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
