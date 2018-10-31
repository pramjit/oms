<?php
require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerReportTabillreport extends Controller {
	public function index() {
          
            
                $this->load->model('user/user');
		//$user_info = $this->model_user_user->getUser($this->user->getId());
              
                if (isset($this->request->get['month_id'])) {
                        
			$month_id=$data['month_id'] = $this->request->get['month_id'];
		} else {
			$data['month_id'] ='';
		}
                if (isset($this->request->get['mo_id'])) {
			$data['filter_mo'] = $this->request->get['mo_id'];
                        $data['filter_mo_nm'] = $this->request->get['mo_nm'];
		} else {
			$data['filter_mo']= '';
                        $data['filter_mo_nm']='Select MO';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'TA BILL REPORT',
			'href' => $this->url->link('report/tabillreport', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');

		$this->load->model('setting/store');
                $this->load->model('report/tabillreport');
                $data["listMOs"] = $this->model_report_tabillreport->getMo();
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

		$data['token'] = $this->session->data['token'];
                $url = '';
                $data['orders'] = array();
                
               // $this->load->library('mpdf/mpdf');
                $mpdf = new mPDF('c','A4','','' , 5 , 5 , 25 , 10 , 5 , 7); 
                $mcrypt = new MCrypt();
                if (!empty($this->request->get['month_id']) || !empty($this->request->get['mo_id']) ){
                   foreach($allmonth as $key=>$value){
                       if( $key==$this->request->get['month_id']){
                           $Y=date('Y');
                           $getMonth=$value.$Y;
                           $getEmpId=$this->request->get['mo_id'];
                       }
                   }
                   $PDF = $this->model_report_tabillreport->tadata($getEmpId,$getMonth);
                    if (count($PDF)>0){
                        $data['PDF']=$PDF;
                    }else{
                        $data['Noop']="Sorry! No records found.";
                    }     
                }
                
               
               
               
                if($filter_month_id=='01'){$mid='JAN, '.date('Y');}
                if($filter_month_id=='02'){$mid='FEB, '.date('Y');}
                if($filter_month_id=='03'){$mid='MAR, '.date('Y');}
                if($filter_month_id=='04'){$mid='APR, '.date('Y');}
                if($filter_month_id=='05'){$mid='MAY, '.date('Y');}
                if($filter_month_id=='06'){$mid='JUN, '.date('Y');}
                if($filter_month_id=='07'){$mid='JUL, '.date('Y');}
                if($filter_month_id=='08'){$mid='AUG, '.date('Y');}
                if($filter_month_id=='09'){$mid='SEP, '.date('Y');}
                if($filter_month_id=='10'){$mid='OCT, '.date('Y');}
                if($filter_month_id=='11'){$mid='NOV, '.date('Y');}
                if($filter_month_id=='12'){$mid='DEC, '.date('Y');}
                $data['emp_rep_date']=$mid;
                //**************************Built PDF Start***********************//
                $j='<div style="text-align:center; color:#5c843d;">'; 
                $j=$j.'<h2>KHANDELWAL AGRO INDUSTRIES</h2>';
                $j=$j.'<h5>MORE KOTHI GANGAPUR BAREILLY</h5>';
                $j=$j.'<table  border="3" align="center" width="100%">';
                $j=$j.'<tbody>';
               $j=$j.'<tr>'
.'<td><b>NAME :&nbsp;</b>'.$PDF['EMP_NAME'].'</td>'
.'<td></td>'
.'</tr>';
$j=$j.'<tr>'
.'<td><b>ROLE :&nbsp;</b>'.$PDF['EMP_GRP'].'</td>'
.'<td></td>'
.'</tr>';
$j=$j.'<tr>'
.'<td><b>AREA :&nbsp;</b>'.$PDF['GEO_NAME'].'</td>'
.'<td></td>'
.'</tr>';
$j=$j.'<tr>'	
.'<td><b>BUDGET MONTH :&nbsp;</b>'.$PDF['EMP_MONTH'].'</td>'
.'<td></td>'
.'</tr>';
$j=$j.'<tr>'
.'<td><b>REPORT DATE :&nbsp;</b>'.DATE('d-m-Y').'</td>'
.'<td>&nbsp;</td>'
.'</tr>';
$j=$j.'</table>';
$j=$j.'<div class="main3" id="main3" style="height:230px;" >';
$j=$j.'<table width="100%" border="1">';
$j=$j.'<tr>'
.'<td width="625">PARTICULARS</td>'
.'<td width="122">KM</td>'
.'<td width="148">RATE</td>'
.'<td width="228">APPROVED BUDGET</td>'
.'</tr>';
$j=$j.'<tr>'
.'<td>DA</td>'
.'<td>&nbsp;</td>'
.'<td>&nbsp;</td>'
.'<td>&nbsp;</td>'
.'</tr>';
$j=$j.'<tr>'
.'<td>Vehicle Running Exp</td>'
.'<td>'.$PDF['EMP_ALW_KM'].'</td>'
.'<td>'.$PDF['EMP_VEH_ALW'].'</td>'
.'<td>'.$PDF['EMP_ALW_KM']*$PDF['EMP_VEH_ALW'].'</td>'
.'</tr>';
$j=$j.'<tr>'
.'<td>Telephone & Mobile</td>'
.'<td>&nbsp;</td>'
.'<td>&nbsp;</td>'
.'<td>&nbsp;</td>'
.'</tr>';
$j=$j.'<tr>'
.'<td>Postage & Telegram</td>'
.'<td>&nbsp;</td>'
.'<td>&nbsp;</td>'
.'<td>&nbsp;</td>'
.'</tr>';
$j=$j.'<tr>'
.'<td>Printing & Stationary</td>'
.'<td>&nbsp;</td>'
.'<td>&nbsp;</td>'
.'<td>&nbsp;</td>'
.'</tr>';
$j=$j.'<tr>'
.'<td>Loading Per Day</td>'
.'<td>&nbsp;</td>'
.'<td>&nbsp;</td>'
.'<td>&nbsp;</td>'
.'</tr>';
$j=$j.'<tr>'
.'<td>Travelling By Bus/Train For Meeting Only</td>'
.'<td>&nbsp;</td>'
.'<td>&nbsp;</td>'
.'<td>&nbsp;</td>'
.'</tr>';
$j=$j.'<tr>'
.'<td>Total</td>'
.'<td>'.$PDF['EMP_ALW_KM'].'</td>'
.'<td>'.$PDF['EMP_VEH_ALW'].'</td>'
.'<td>'.$PDF['EMP_ALW_KM']*$PDF['EMP_VEH_ALW'].'</td>'
.'</tr>';
$j=$j.'</table>';
$j=$j.'</div>';
$j=$j.'<div class="main4" id="main4" style="height:200px; " >';
$j=$j.'<tr>'
.'<td >NOTE:-</td>'
.'</tr>';
$j=$j.'<tr>'
.'<td> Please send following reports:-<br/>'
.'1. ATP 1-15 upto 3rd of Month.<br/>'
.'2. ATP 16-31 upto 16th of Month.<br/>'
.'3. TA Bill 3rd of month will suppoting documents.<br/>'
.'4. Budget Attached with TA Bill.<br/>'
.'5. Date of month 30 & 31 complete TA bill, ATP and Courier to Head Office Bill.<br/>'
.'6. No claim of any tour on date of month end (30 & 31).<br/>'
.'7. Millage will be Rs '.$PDF['EMP_VEH_ALW'].'per/km'
.'</td>';
$j=$j.'</tr><br/><br/>';
$j=$j.'<tr>'
	.'<td>APPROVED BY.</td>'
.'</tr>';
               
                $mpdf->WriteHTML($j);
                $dt=date(YmdHis);
                $mpdf->Output(DIR_DOWNLOAD.'Approved_Budget'.$dt.'.pdf','F');
                // echo $j;die;
                $s=DIR_DOWNLOAD_PDF.'Approved_Budget'.$dt.'.pdf';
                //$this->response->addHeader('Content-Type: application/json');
                //$this->response->setOutput($s);  
                $data['pdf']=$s;
                //print_r($data);
               
                //**************************Built PDF End************************//
                
                
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/tabillreport', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                
                $this->response->setOutput($this->load->view('report/tabillreport.tpl', $data));
	}

 

}
