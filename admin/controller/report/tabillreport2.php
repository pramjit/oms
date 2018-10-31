<?php
require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerReportTabillreport2 extends Controller {
	public function index() {
          
        //**************************Number to word Start*****************//
        function numberTowords($num)
        { 
            $ones = array( 
            1 => "one", 
            2 => "two", 
            3 => "three", 
            4 => "four", 
            5 => "five", 
            6 => "six", 
            7 => "seven", 
            8 => "eight", 
            9 => "nine", 
            10 => "ten", 
            11 => "eleven", 
            12 => "twelve", 
            13 => "thirteen", 
            14 => "fourteen", 
            15 => "fifteen", 
            16 => "sixteen", 
            17 => "seventeen", 
            18 => "eighteen", 
            19 => "nineteen" 
            ); 
            $tens = array( 
            2 => "twenty", 
            3 => "thirty", 
            4 => "forty", 
            5 => "fifty", 
            6 => "sixty", 
            7 => "seventy", 
            8 => "eighty", 
            9 => "ninety" 
            ); 
            $hundreds = array( 
            "hundred", 
            "thousand", 
            "million", 
            "billion", 
            "trillion", 
            "quadrillion" 
            ); //limit t quadrillion 
            $num = number_format($num,2,".",","); 
            $num_arr = explode(".",$num); 
            $wholenum = $num_arr[0]; 
            $decnum = $num_arr[1]; 
            $whole_arr = array_reverse(explode(",",$wholenum)); 
            krsort($whole_arr); 
            $rettxt = ""; 
            foreach($whole_arr as $key => $i){ 
            if($i < 20){ 
            $rettxt .= $ones[$i]; 
            }elseif($i < 100){ 
            $rettxt .= $tens[substr($i,0,1)]; 
            $rettxt .= " ".$ones[substr($i,1,1)]; 
            }else{ 
            $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
            $rettxt .= " ".$tens[substr($i,1,1)]; 
            $rettxt .= " ".$ones[substr($i,2,1)]; 
            } 
            if($key > 0){ 
            $rettxt .= " ".$hundreds[$key]." "; 
            } 
            } 
            if($decnum > 0){ 
            $rettxt .= " and "; 
            if($decnum < 20){ 
            $rettxt .= $ones[$decnum]; 
            }elseif($decnum < 100){ 
            $rettxt .= $tens[substr($decnum,0,1)]; 
            $rettxt .= " ".$ones[substr($decnum,1,1)]; 
            } 
            } 
            return $rettxt; 
        } 
            //**************************Number to word End*****************//
                $this->load->model('user/user');
		//$user_info = $this->model_user_user->getUser($this->user->getId());
                
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
			'href' => $this->url->link('report/tabillreport2', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	

		$this->load->model('setting/store');
                $this->load->model('report/tabillreport2');
                $data["listMOs"] = $this->model_report_tabillreport2->getMo();
                $data['orders'] = array();
		
		$data['token'] = $this->session->data['token'];
                
		$url = '';
                $data['orders'] = array();
                
               // $this->load->library('mpdf/mpdf');
                $mpdf = new mPDF('c','A4','','' , 5 , 5 , 25 , 10 , 5 , 7); 
                $mcrypt = new MCrypt();
                if($this->request->get['month_id'])	  {
                    $PDF = $this->model_report_tabillreport2->tadata($this->request->get);
                    if (count($PDF)>0){
                        $data['PDF']=$PDF;
                    }else{
                        $data['Noop']="Sorry! No records found.";
                    }     
                }
                
                $TOT_KM=$PDF["CLOSE_MTR"]-$PDF["OPEN_MTR"];
                $TOT_AMT=$TOT_KM*$PDF['EMP_VEH_ALW'];
   
                $TOT_VEH_EXP=$TOT_AMT+$PDF['TAX_RS'];
                $TOT_TRA_EXP=$PDF['FARE_RS']+$PDF['LOCAL_CONVEYANCE']+$PDF['HOTEL_RS']+$PDF['DAILY_DA'];
                $ALL_TOT=$PDF['POSTAGE_RS']+$PDF['PRINTING_RS']+$PDF['MISC_RS']+$TOT_VEH_EXP+$TOT_TRA_EXP;
                $data['ALL_TOT_WORD']=$ALL_TOT_WORD=numberTowords($ALL_TOT);  
                foreach($allmonth as $key=>$value){ if($key == $month_id) { $my=$value.', '.date('Y'); }}
                //**************************Built PDF Start***********************//
                $j='<div style="text-align:center; color:#5c843d;">'; 
                $j=$j.'<h2>KHANDELWAL AGRO INDUSTRIES</h2>';
                $j=$j.'<h5>MORE KOTHI GANGAPUR BAREILLY</h5>';
                $j=$j.'</div>';
                $j=$j.'<hr/>';
                $j=$j.'<table align="center" width="100%">';
                $j=$j.'<tr>'
                            .'<th>Employee:</th>'
                            .'<td>'.$PDF['EMP_NAME'].'</td>'
                            .'<th>Designation:</th>'
                            .'<td>'.$PDF['EMP_GRP'].'</td>'
                            .'<th>H.Q.:</th>'
                            .'<td>'.$PDF['GEO_NAME'].'</td>'
                     .'</tr>';
                $j=$j.'<tr>'
                            .'<th>Month:</th>'
                            .'<td>'.$my.'</td>'
                            .'<th>&nbsp;</th>'
                            .'<th>&nbsp;</th>'
                            .'<th>Bill No:</th>'
                            .'<td></td>'
                     .'</tr>';  
                $j=$j.'<tr><td colspan="6"><hr/></td></tr>';
                
                $j=$j.'<tr>'
                            .'<th>Sl.No</th>'
                            .'<th>Particulars</th>'
                            .'<th>Claimed Amt.</th>'
                            .'<th>Deduction Amt.</th>'
                            .'<th>Checked Amt.</th>'
                            .'<th>Passed Amt.</th>'
                     .'</tr>';
                $j=$j.'<tr><td colspan="6"><hr/></td></tr>';
                $j=$j.'<tr>'
                            .'<th>1.</th>'
                            .'<th colspan="5">Vehicle Running Expenses:-</th>'   
                     .'</tr>';
                $j=$j.'<tr><td colspan="6"><hr/></td></tr>';
                $j=$j.'<tr>'
                            .'<td rowspan="4">&nbsp;</td>'
                            .'<td>Vehicle Running Km</td>'
                            .'<td>'.$TOT_KM .'</td>'
                            .'<td></td>'
                            .'<td></td>'
                            .'<td></td>'
                      .'</tr>';

  		$j=$j.'<tr>'
			    .'<td>Rate Per Km</td>'
			    .'<td>'.$PDF['EMP_VEH_ALW'].'</td>'
			    .'<td>&nbsp;</td>'
			    .'<td>&nbsp;</td>'
			    .'<td>&nbsp;</td>'
 		.'</tr>';

  $j=$j.'<tr>'
    .'<td>Vehicle Running Amount</td>'
    .'<td>'.$TOT_AMT.'</td>'
    .'<td>&nbsp;</td>'
    .'<td>&nbsp;</td>'
    .'<td>&nbsp;</td>'
    .'</tr>';

  $j=$j.'<tr>'
    .'<td>Toll Tax</td>'
    .'<td>'.$PDF['TAX_RS'].'</td>'
    .'<td>&nbsp;</td>'
    .'<td>&nbsp;</td>'
    .'<td>&nbsp;</td>'
   .'</tr>';
  $j=$j.'<tr><td colspan="6"><hr/></td></tr>';
  $j=$j.'<tr>'
    .'<td>&nbsp;</td>'
    .'<th>Total (Rs.)</th>'
    .'<td>'.$TOT_VEH_EXP.'</td>'
    .'<td></td>'
    .'<td></td>'
    .'<td></td>'
    .'</tr>';
  $j=$j.'<tr><td colspan="6"><hr/></td></tr>';
  $j=$j.'<tr>'
    .'<th>2.</th>'
    .'<th colspan="5">Travelling Expenses:-</th>'
   .'</tr>';
  $j=$j.'<tr><td colspan="6"><hr/></td></tr>';
  $j=$j.'<tr>'
    .'<td rowspan="4">&nbsp;</td>'
    .'<td>Fare</td>'
    .'<td>'.$PDF['FARE_RS'].'</td>'
    .'<td>&nbsp;</td>'
    .'<td>&nbsp;</td>'
    .'<td></td>'
    .'</tr>';
 
  $j=$j.'<tr>'
    .'<td>Local Conveyance</td>'
    .'<td>'.$PDF['LOCAL_CONVEYANCE'].'</td>'
    .'<td>&nbsp;</td>'
    .'<td>&nbsp;</td>'
    .'<td>&nbsp;</td>'
    .'</tr>';
 
  $j=$j.'<tr>'
    .'<td>Hotel/Lodging</td>'
    .'<td>'.$PDF['HOTEL_RS'].'</td>'
   .' <td>&nbsp;</td>'
   .' <td>&nbsp;</td>'
    .'<td>&nbsp;</td>'
    .'</tr>';
  
  $j=$j.'<tr>'
    .'<td>D.A.</td>'
    .'<td>'.$PDF['DAILY_DA'].'</td>'
    .'<td>&nbsp;</td>'
    .'<td>&nbsp;</td>'
    .'<td>&nbsp;</td>'
    .'</tr>';
  $j=$j.'<tr><td colspan="6"><hr/></td></tr>';
  $j=$j.'<tr>'
    .'<td>&nbsp;</td>'
    .'<th>Total (Rs.)</th>'
    .'<td>'.$TOT_TRA_EXP.'</td>'
    .'<td></td>'
    .'<td></td>'
    .'<td></td>'
    .'</tr>';
  $j=$j.'<tr><td colspan="6"><hr/></td></tr>';
 $j=$j.'<tr>'
.' <th>3.</th>'
   .'<th>Mobile & Telephone Exps.</th>'
   .'<td>0.00</td>'
   .'<th colspan="3"></th>'
 .'</tr>';
 $j=$j.'<tr><td colspan="6"><hr/></td></tr>';
 $j=$j.'<tr>'
    .'<th>4.</th>'
    .'<th>Postage & Couriers Exps.</th>'
    .'<td>'.$PDF['POSTAGE_RS'].'</td>'
    .'</tr>';
 $j=$j.'<tr><td colspan="6"><hr/></td></tr>';
 $j=$j.'<tr>'
    .'<th>5.</th>'
    .'<th>Printing & Stationary Exps.</th>'
    .'<td>'.$PDF['PRINTING_RS'].'</td>'
    .'</tr>';
 $j=$j.'<tr><td colspan="6"><hr/></td></tr>';
 $j=$j.'<tr>'
 .'<th>6.</th>'
   .'<th>Misc. Exps.</th>'
   .'<td>'.$PDF['MISC_RS'].'</td>'
   .'</tr>';
 $j=$j.'<tr><td colspan="6"><hr/></td></tr>';
 $j=$j.'<tr>'
    .'<th colspan="2">Total Bill Amount</th>'
    .'<td>'.$ALL_TOT.'</td>'
    .'</tr>';
 $j=$j.'<tr>'
    .'<th colspan="2">Amounts In words Rs. </th>'
    .'<td colspan="4" align="left">'.ucfirst($ALL_TOT_WORD).'</td>'
    .'</tr>';
  $j=$j.'<tr><td colspan="6"><hr/></td></tr>';
 $j=$j.'<tr>'
    .'<th>Claimant:</th>'
    .'<td> </td>'    
    .'<th>Checked By:</th>'
    .'<td></td>'
    .'<th>Passed By:</th>'
    .'<td></td>'
    .'</tr>';
 $j=$j.'</table>';

                
 $mpdf->WriteHTML($j);
                $dt=date(YmdHis);
                $mpdf->Output(DIR_DOWNLOAD.'agro_'.$dt.'.pdf','F');
                // echo $j;die;
                $s=DIR_DOWNLOAD_PDF.'agro_'.$dt.'.pdf';
                //$this->response->addHeader('Content-Type: application/json');
                //$this->response->setOutput($s);  
                $data['pdf']=$s;
                //print_r($data);
               
                //**************************Built PDF End************************//
                
                
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/tabillreport2', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                
                $this->response->setOutput($this->load->view('report/tabillreport2.tpl', $data));
	}

 

}
