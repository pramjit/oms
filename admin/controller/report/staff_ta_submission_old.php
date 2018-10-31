<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerReportStaffTaSubmission extends Controller {
	public function index() {
		$this->load->language('report/sale_report');
		$this->load->language('report/sale_order');
		$this->document->setTitle('Staff Ta Submission');
                
		$this->load->model('user/user');
		$user_info = $this->model_user_user->getUser($this->user->getId());
                $userid=$user_info['customer_id'];
               $usergroupid=$user_info['user_group_id'];
               
              


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
			'href' => $this->url->link('report/indentreport', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');

		$this->load->model('setting/store');
                $this->load->model('report/indentreport');
                 $this->load->model('report/staff_ta_submission');
     
                
              
                
                
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'mo_id'           => $filter_mo,
                        'dist_id'         => $filter_dist,
                        'teh_id'          => $filter_teh,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin'),
                        'usrid'           => $user_info['customer_id']
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
                
                $order_total = $this->model_report_staff_ta_submission->getTotalSale($filter_data);
               $results = $this->model_report_staff_ta_submission->getdailysummary($filter_data);
                               
               //print_r($results);
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
                                'Emp_Name'    => $result['Emp_Name'],
                                'Mobile'    => $result['Mobile'],
                                '01' => $result['01'],
                                '02' => $result['02'],
                                     '03' => $result['03'],
                                     '04' => $result['04'],
                                     '05' => $result['05'],
                                     '06' => $result['06'],
                                     '07' => $result['07'],
                                     '08' => $result['08'],
                                     '09' => $result['09'],
                                     '10' => $result['10'],
                                     '11' => $result['11'],
                                     '12' => $result['12'],
                                     '13' => $result['13'],
                                     '14' => $result['14'],
                                     '15' => $result['15'],
                                     '16' => $result['16'],
                                     '17' => $result['17'],
                                     '18' => $result['18'],
                                     '19' => $result['19'],
                                     '20' => $result['20'],
                                     '21' => $result['21'],
                                     '22' => $result['22'],
                                     '23' => $result['23'],
                                     '24' => $result['24'],
                                     '25' => $result['25'],
                                     '26' => $result['26'],
                                     '27' => $result['27'],
                                     '28' => $result['28'],
                                     '29' => $result['29'],
                                     '30' => $result['30'],
                                     '31' => $result['31']
                                     
				
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
		$pagination->url = $this->url->link('report/indentreport', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;


		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/staff_ta_submission.tpl', $data));
	}

 public function getorderdata(){
     
    $this->load->model('report/indentreport');    
    $orddata = $this->request->post['orddata'];
    $ordno = $this->request->post['ordno'];
    //print_r($orddata);die;
    $orderdata= $this->model_report_indentreport->getorder($orddata);
    //print_r($orderdata);
    ?>
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
       <!---------- <button type="button" class="close" data-dismiss="modal">&times;</button>----->
        <div class="modal-body" >
 <h4 class="modal-title">Order detail :-&nbsp; &nbsp;<?php echo $orddata; ?></h4>
 </div>
  <table width="560" border="1">
<thead>
<tr>
<td class="text-center" style="font-weight: bold;">Product Name</td>
<td class="text-center" style="font-weight: bold;">Order Quantity</td>
<td class="text-center" style="font-weight: bold;">Sap Code</td>

</tr>
</thead>
<?php foreach ($orderdata as $order) { //print_r($order); ?>
<tr>
<td><?php echo $order['product_name']; ?></td>
<td><?php echo $order['quantity']; ?></td>
<td><?php echo $order['sap_code']; ?></td>

</tr>

<?php } ?>

</table>
 </div>
   

  </div>
</div>

    <?php
   
   // echo json_encode($tadata);
    }
        
public function download_excel() {

        $this->load->model('report/staff_ta_submission');
        $order_total = $this->model_report_staff_ta_submission->getTotalSale($filter_data);

		$data['orders'] = array();

		$results = $this->model_report_staff_ta_submission->getdownloadexcel($filter_data);
                
		                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);
    ob_clean();
    // Field names in the first row
    $fields = array(
        
         'Name',
                                     'Mobile No',
                                     '01',
                                     '02',
                                     '03' ,
                                     '04',
                                     '05',
                                     '06',
                                     '07',
                                     '08',
                                     '09',
                                     '10',
                                     '11',
                                     '12',
                                     '13' ,
                                     '14',
                                     '15' ,
                                     '16' ,
                                     '17' ,
                                     '18' ,
                                     '19',
                                     '20',
                                     '21',
                                     '22' ,
                                     '23' ,
                                     '24',
                                     '25' ,
                                     '26',
                                     '27' ,
                                     '28' ,
                                     '29',
                                     '30',
                                     '31'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     	
    $row = 2;
   // print_r($results); die;
    foreach($results as $data)
    {
      
        $col = 0;
             $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['Emp_Name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['Mobile']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['01']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['02']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['03']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['04']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['05']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['06']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['07']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $data['08']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $data['09']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $data['10']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $data['11']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $data['12']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $data['13']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $data['14']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, $data['15']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, $data['16']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $row, $data['17']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $row, $data['18']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $row, $data['19']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $row, $data['20']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22, $row, $data['21']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(23, $row, $data['22']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(24, $row, $data['23']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(25, $row, $data['24']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26, $row, $data['25']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(27, $row, $data['26']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(28, $row, $data['27']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(29, $row, $data['28']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(30, $row, $data['29']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(31, $row, $data['30']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(32, $row, $data['31']);
        
  
             
   
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Staff_ta_submission_report'.date('dMy').'.xls"');
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
