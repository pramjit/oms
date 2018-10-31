<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerReportStaffTaSubmission extends Controller {
	public function index() {
		$this->load->language('report/sale_report');
		$this->load->language('report/sale_order');
		$this->document->setTitle('Staff Ta Submission');
                $data['filter_month']=$this->request->get['gmonth_id'];
		$this->load->model('user/user');
		$user_info = $this->model_user_user->getUser($this->user->getId());
                $userid=$user_info['customer_id'];
                $usergroupid=$user_info['user_group_id'];
                $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Sale Summary',
			'href' => $this->url->link('report/indentreport', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	

		$this->load->model('setting/store');
                $this->load->model('report/indentreport');
                $this->load->model('report/staff_ta_submission');
                $data['orders'] = array();

		$allmonth=array(
                    '01'=>'January',
                    '03'=>'March',
                    '04'=>'April',
                    '05'=>'May',
                    '06'=>'June',
                    '07'=>'July',
                    '08'=>'August',
                    '09'=>'September',
                    '10'=>'October',
                    '11'=>'November',
                    '12'=>'December'
                );
                $data['allmonth']=$allmonth;
                $Y=date('Y');
                if (isset($this->request->get['gmonth_id'])) {
                        
			$month_id=$data['month_id'] = $this->request->get['gmonth_id'];
                        $fromDate=$Y.'-'.$month_id.'-01';
                        $toDate=$Y.'-'.$month_id.'-31';
		} else {
			$month_id=$data['month_id'] ='';
                        $fromDate='';
                        $toDate='';
		}
                //================================== Data Available for the Month or NOT================================//
                $data['ChkTaAvl']=$ChkTaAvl = $this->model_report_staff_ta_submission->ChkTaAvl($fromDate,$toDate);
                if(count($ChkTaAvl)>0){
                    $empList = $this->model_report_staff_ta_submission->empList();
                    $masData=array();
                    $data['dtlist']=$dtlist= $this->model_report_staff_ta_submission->daList($fromDate,$toDate);
                    foreach($empList as $emp){


                        $oneData=array($emp['EMP_NAME'],$emp['EMP_MOB'] );
                        $empTaData = $this->model_report_staff_ta_submission->empTaData($emp['EMP_ID'],$fromDate,$toDate);
                        if(count($empTaData)>0){
                            foreach($empTaData as $ed){
                                array_push($oneData,$ed['STS']);
                            }
                        }
                        array_push($masData,$oneData);


                    }
                    $data['masData']=$masData;
                }
                else{
                        $data['Noop']="Sorry! No records found.";
                } 
                //=============================== Data Available for the Month or NOT End================================//               
                

		$data['token'] = $this->session->data['token'];
                $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/staff_ta_submission.tpl', $data));
	}

       
public function download_excel() {
    
    $this->load->model('report/staff_ta_submission');
    $month_id= $this->request->get['gmonth_id'];
    $Y=date('Y');
    $fromDate=$Y.'-'.$month_id.'-01';
    $toDate=$Y.'-'.$month_id.'-31';
    
    //============================= Data Set ============================//
    $empList = $this->model_report_staff_ta_submission->empList();
    $masData=array();
    $dtlist= $this->model_report_staff_ta_submission->daList($fromDate,$toDate);
    foreach($empList as $emp){


        $oneData=array($emp['EMP_NAME'],$emp['EMP_MOB'] );
        $empTaData = $this->model_report_staff_ta_submission->empTaData($emp['EMP_ID'],$fromDate,$toDate);
        
        if(count($empTaData)>0){
            foreach($empTaData as $ed){
                array_push($oneData,$ed['STS']);
            }
        }
        array_push($masData,$oneData);


    }
    
    
    //=========================== Data Set End ============================//                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);
    ob_clean();
    // Field names in the first row
    $fields = array( '#SNo','Employee Name','Mobile Number');
    foreach($dtlist as $cdt){
        array_push($fields,$cdt['XD']);
    }

    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     	
    $row = 2;
    
    foreach($masData as $key=>$ma){
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $key+1);
        $so=1;
         foreach($ma as $xd){
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($so, $row, $xd);
            $so++;
         }
         
        $row++;
       
    }


    //PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="TA_SUBMIT_REPORT'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');
    $objWriter->save('php://output');
    }

}
