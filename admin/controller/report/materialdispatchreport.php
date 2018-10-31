<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerReportMaterialdispatchreport extends Controller {
	public function index() {
		$this->load->language('report/sale_report');
		$this->load->language('report/sale_order');
		$this->document->setTitle('Material Dispatch Report');
                
		$this->load->model('user/user');
		$user_info = $this->model_user_user->getUser($this->user->getId());
                $userid=$user_info['customer_id'];
               $usergroupid=$user_info['user_group_id'];
                //print_r($user_info);
                //print_r($data);	
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
		
		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Stock Tranfer report',
			'href' => $this->url->link('report/stocktransfer', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');

		$this->load->model('setting/store');
                $this->load->model('report/materialdispatchreport');
                $data["listMOs"] = $this->model_report_materialdispatchreport->getMo($usergroupid,$userid);
                $data["listWSs"] = $this->model_report_materialdispatchreport->getWs();
                $data["listAMs"] = $this->model_report_materialdispatchreport->getAm();
                $data["listDISTs"] = $this->model_report_materialdispatchreport->getdistrict();
                //$data["listTEHs"] = $this->model_report_indentreport->gettehsil();
                
              
                
                
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
                
                $order_total = $this->model_report_materialdispatchreport->getTotalSale($filter_data);
                
                $results = $this->model_report_materialdispatchreport->getdailysummary($filter_data);
                                
               // print_r($results);
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
                                'DISP_DATE'    => $result['DISP_DATE'],
                                'DISP_TRANS'   => $result['DISP_TRANS'],
				'DISP_VEHI'    => $result['DISP_VEHI'],
				'DISP_GRR'     => $result['DISP_GRR'],
                                'DISP_MOB'     => $result['DISP_MOB'],
				'DISP_INV'     => $result['DISP_INV'],
                                'STO_ID'       => $result['STO_ID'],
                                'STO_NAME'     => $result['STO_NAME'],
                                'IND_NO'       => $result['IND_NO'],
                                'IND_QTY'      => $result['IND_QTY'],    
				'SO_QTY'       => $result['SO_QTY'],
                                'DISP_QTY'     => $result['DISP_QTY'],
'ORD_NO'     => $result['ORD_NO'],


                            );
		}

		$data['token'] = $this->session->data['token'];
                
		$url = '';
				
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
                if (isset($this->request->get['ws_id'])) {
			$url .= '&ws_id=' . $this->request->get['ws_id'];
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
		$pagination->url = $this->url->link('report/materialdispatchreport', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;


		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/materialdispatchreport.tpl', $data));
	}

 public function getorderdata(){
     
    $this->load->model('report/stocktransfer');    
    $orddata = $this->request->post['orddata'];
    $ordno = $this->request->post['ordno'];
    //print_r($orddata);die;
    $orderdata= $this->model_report_stocktransfer->getorder($orddata);
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
			'filter_group'           => $filter_group
			
		);

        $this->load->model('report/materialdispatchreport');
        $order_total = $this->model_report_materialdispatchreport->getTotalSale($filter_data);

		$data['orders'] = array();

		$results = $this->model_report_materialdispatchreport->getdownloadexcel($filter_data);
                
		                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);
    ob_clean();
    // Field names in the first row
    $fields = array(
        
        
        'DISP. DATE',
        'PARTY NAME',
        'ORDER NO',
        'ORDER QTY',
        'DISP. QTY',
        'INVOICE NO',
        
        'GRR. DETAILS',
        'TRANSPORT NAME',
        'VEHICLE NO'
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
        if($data['order_status']=='0'){ $rk= "Not Approved"; } else{ $rk= "Approved";}
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['DISP_DATE']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['STO_NAME']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['IND_NO']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['IND_QTY']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['DISP_QTY']);        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['IND_NO']);        
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['DISP_GRR']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['DISP_TRANS'] );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['DISP_VEHI']);
  
             
   
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Material_Dispatch_Report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
        public function email_excel() {
           $this->load->model('report/materialdispatchreport');
        $order_total = $this->model_report_materialdispatchreport->getTotalSale($filter_data);

		$data['orders'] = array();

		$results = $this->model_report_materialdispatchreport->getSale_summary($filter_data);
                
		                
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
