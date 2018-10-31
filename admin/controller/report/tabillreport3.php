<?php
require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerReportTabillreport3 extends Controller {
	public function index() {
          
            
                $this->load->model('user/user');
		$user_info = $this->model_user_user->getUser($this->user->getId());
                //print_r($user_info);    
                $data['usrid']=$user_info['user_id'];
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
			'href' => $this->url->link('report/tabillreport3', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');

		$this->load->model('setting/store');
                $this->load->model('report/tabillreport3');
                $data["listMOs"] = $this->model_report_tabillreport3->getMo();
                $data['orders'] = array();
                
                $data['token'] = $this->session->data['token'];
                $url = '';
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
                
               // $this->load->library('mpdf/mpdf');
                $mpdf = new mPDF('c','A4','','' , 5 , 5 , 25 , 10 , 5 , 7); 
                $mcrypt = new MCrypt();
                if (isset($this->request->get['month_id']) || isset($this->request->get['mo_id']) ){
			  
                        $pdfdata = $this->model_report_tabillreport3->tadata($this->request->get);
                        $empDtls = $this->model_report_tabillreport3->empDtls($this->request->get['mo_id']);
                        if (count($pdfdata)>0){
                            $data['pdfdatas']=$pdfdata;
                            $data['empDtls']=$empDtls;
                            foreach($allmonth as $key=>$value){ if($key == $month_id) { $data['my']=$my=$value.', '.date('Y'); }}
                        }else{
                            $data['Noop']="Sorry! No records found.";
                        } 
                } 
               
                //**************************Built PDF Start***********************//
                $j='<div style="text-align:center; color:#5c843d;">'; 
                $j=$j.'<h2>KHANDELWAL AGRO INDUSTRIES</h2>';
                $j=$j.'<h5>MORE KOTHI GANGAPUR BAREILLY</h5>';
                $j=$j.'<table  border="3" align="center" width="100%">';
                $j=$j.'<tbody>';
               
                
                $j=$j.'<tr>'
                        . '<td><b>NAME :&nbsp;</b>'.$empDtls['EMP_NAME'].'</td>'
                        . '</tr>';
                $j=$j.'<tr>'
                        . '<td><b>DESIGNATION :&nbsp;</b>'.$empDtls['EMP_GRP'].'</td>'
                        . '</tr>';
                $j=$j.'<tr>'
                        . '<td><b>H.Q. :&nbsp;</b>'.$empDtls['GEO_NAME'].'</td>'
                        . '</tr>';
                $j=$j.'<tr>'
                        . '<td><b>REPORT MONTH :&nbsp;</b>'.$my.'</td>'
                        . '</tr>';
                
                
                $j=$j.'</tbody>';
                $j=$j.'</table>';
                $j=$j.'<table  border="0" align="center" width="100%">';
                $j=$j.'<tbody>';
                $j=$j.'<tr><th colspan="15"><hr></th></tr>';
                $j=$j.'<tr>'
                        . '<td style="color:#ef710e;">DATE</td>'
                        . '<td style="color:#ef710e;">PLACE FROM</td>'
                        . '<td style="color:#ef710e;">PLACE VISITED</td>'
                   
                        . '<td style="color:#ef710e;">OPENING (MR)</td>'
                        . '<td style="color:#ef710e;">CLOSING (MR)</td>'
                        . '<td style="color:#ef710e;">OFFICIAL USED KM</td>'
                        . '<td style="color:#ef710e;">TOLL TAX</td>'
                        . '<td style="color:#ef710e;">PETROL PURCHASE LTR.</td>'
                        . '<td style="color:#ef710e;">FARE/TAXI</td>'
                        . '<td style="color:#ef710e;">LOCAL CONVEYENCE</td>'
                        . '<td style="color:#ef710e;">HOTEL</td>'
                        . '<td style="color:#ef710e;">DA</td>'
                        . '<td style="color:#ef710e;">PRINTING</td>'
                        . '<td style="color:#ef710e;">POSTAGE</td>'
                    
                        . '<td style="color:#ef710e;">MISC EXP</td>'
                        . '</tr>';
                
                $j=$j.'<tr><td colspan="15"><hr></td></tr>';
                $TOT_OPEN_MTR=$TOT_CLOSE_MTR=$TOT_MTR=$TOT_TAX_RS=$TOT_PETROL_LTR=$TOT_FARE_RS=$TOT_LOCAL_CONVEYANCE=$TOT_HOTEL_RS=$TOT_DAILY_DA=$TOT_PRINTING_RS=$TOT_POSTAGE_RS=$TOT_MISC_RS=0;
                foreach ($pdfdata as $pdf)
                {
                    //===================== COUNTING INDIVIDUAL SUM ========================//
                    $TOT_OPEN_MTR=$TOT_OPEN_MTR+$pdf["OPEN_MTR"];
                    $TOT_CLOSE_MTR=$TOT_CLOSE_MTR+$pdf["CLOSE_MTR"];
                    $BAL=$pdf["CLOSE_MTR"]-$pdf["OPEN_MTR"];
                    $TOT_MTR=$TOT_MTR+$BAL;
                    $TOT_TAX_RS=$TOT_TAX_RS+$pdf["TAX_RS"];
                    $TOT_PETROL_LTR=$TOT_PETROL_LTR+$pdf["PETROL_LTR"];
                    $TOT_FARE_RS=$TOT_FARE_RS+$pdf["FARE_RS"];
                    $TOT_LOCAL_CONVEYANCE=$TOT_LOCAL_CONVEYANCE+$pdf["LOCAL_CONVEYANCE"];
                    $TOT_HOTEL_RS=$TOT_HOTEL_RS+$pdf["HOTEL_RS"];
                    $TOT_DAILY_DA=$TOT_DAILY_DA+$pdf["DAILY_DA"];
                    $TOT_PRINTING_RS=$TOT_PRINTING_RS+$pdf["PRINTING_RS"];
                    $TOT_POSTAGE_RS=$TOT_POSTAGE_RS+$pdf["POSTAGE_RS"];
                    $TOT_MISC_RS=$TOT_MISC_RS+$pdf["MISC_RS"];
                    //===================== COUNTING INDIVIDUAL SUM END========================//
                    $j=$j.'<tr>'
                        . '<td>'.date("d-m-Y", strtotime($pdf["tadate"])).'</td>'
                       . '<td>'.$pdf["PLACE_FROM"].'</td>'
                        . '<td>'.$pdf["PLACE_TO"].', '.$pdf["PLACE_TO1"].', '.$pdf["PLACE_TO2"].', '.$pdf["PLACE_TO3"].', '.$pdf["PLACE_TO4"].'</td>'                
                        . '<td>'.$pdf["OPEN_MTR"].'</td>'
                        . '<td>'.$pdf["CLOSE_MTR"].'</td>'
                        . '<td>'.($pdf["CLOSE_MTR"]-$pdf["OPEN_MTR"]).'</td>'
                        . '<td>'.$pdf["TAX_RS"].'</td>'
                        . '<td>'.$pdf["PETROL_LTR"].'</td>'
                        . '<td>'.$pdf["FARE_RS"].'</td>'
                        . '<td>'.$pdf["LOCAL_CONVEYANCE"].'</td>'
                        . '<td>'.$pdf["HOTEL_RS"].'</td>'
                        . '<td>'.$pdf["DAILY_DA"].'</td>'
                        . '<td>'.$pdf["PRINTING_RS"].'</td>'
                        . '<td>'.$pdf["POSTAGE_RS"].'</td>'
                        . '<td>'.$pdf["MISC_RS"].'</td>'
                        . '</tr>'; 
                   $j=$j.'<tr><td colspan="15"><hr></td></tr>';
                }
                $j=$j.'<tr style="border:1px;">'
                        . '<td colspan=3>TOTAL</td>'
                        . '<td>'.$TOT_OPEN_MTR.'</td>'
                        . '<td>'.$TOT_CLOSE_MTR.'</td>'
                        . '<td>'.$TOT_MTR.'</td>'
                        . '<td>'.$TOT_TAX_RS.'</td>'
                        . '<td>'.$TOT_PETROL_LTR.'</td>'
                        . '<td>'.$TOT_FARE_RS.'</td>'
                        . '<td>'.$TOT_LOCAL_CONVEYANCE.'</td>'
                        . '<td>'.$TOT_HOTEL_RS.'</td>'
                        . '<td>'.$TOT_DAILY_DA.'</td>'
                        . '<td>'.$TOT_PRINTING_RS.'</td>'
                        . '<td>'.$TOT_POSTAGE_RS.'</td>'
                        . '<td>'.$TOT_MISC_RS.'</td>'
                        . '</tr>'; 
                $j=$j.'</tbody>';
                $j=$j.'</table>';
                $j=$j.'</div>';
                $mpdf->WriteHTML($j);
                $dt=date(YmdHis);
                $mpdf->Output(DIR_DOWNLOAD.'agro'.$dt.'.pdf','F');
                // echo $j;die;
                $s=DIR_DOWNLOAD_PDF.'agro'.$dt.'.pdf';
                //$this->response->addHeader('Content-Type: application/json');
                //$this->response->setOutput($s);  
                $data['pdf']=$s;
                //print_r($data);
               
                //**************************Built PDF End************************//
                
                
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/tabillreport3', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                
                $this->response->setOutput($this->load->view('report/tabillreport3.tpl', $data));
	}

 

}
