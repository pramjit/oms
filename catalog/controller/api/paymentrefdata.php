<?php
class ControllerApiPaymentrefdata extends Controller {
public function index() {
$log=new Log("Paymentrefdata.log");
//$this->load->language('api/jeepcampaign');
$keys = array(
'Emp_id',
'Cust_id',
'TO_DATE',
'FROM_DATE'
);
$json = array();
$mcrypt = new MCrypt();
foreach ($keys as $key) {
    //$this->request->post["Cust_id"]='';
$this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
}
$log->write($this->request->post);
//log to table
$this->load->model('account/activity');
$activity_data = $this->request->post;
$this->model_account_activity->addActivity('paymentref', $activity_data);
$this->load->model('payment/paymentref');
if (isset($this->request->post["Emp_id"])  && $this->request->post["Emp_id"]!="0"){

$data = $this->model_payment_paymentref->paymentrefdata($this->request->post);
$log->write($data);
if ($data) { 
$json=array(); 
foreach($data as $val){
$json['data'][]=array(
'Cust_id' => $mcrypt->encrypt($val['Cust_id']),
'Amnt_Date' => $mcrypt->encrypt($val['Amnt_Date']),
'Amnt_Ref' => $mcrypt->encrypt($val['Amnt_Ref']),
'Amnt_Rs' => $mcrypt->encrypt($val['Amnt_Rs']),
'firstname' => $mcrypt->encrypt($val['firstname']),
'lastname' => $mcrypt->encrypt($val['lastname'])
);
}
} 
else{
$json = 2;//$mcrypt->decrypt($this->language->get('error_login'));
}

}else {
			
   // $json['error'] = $mcrypt->decrypt($this->language->get('error_login'));
		
      $json=0;
}

$this->response->addHeader('Content-Type: application/json');
$this->response->setOutput(json_encode($json));
}
}
