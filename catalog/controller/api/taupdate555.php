<?php
class ControllerApiTaupdate extends Controller {
public function index() {
$log=new Log("taupdate.log");
$keys = array( 
'SID',
'TA_DATE', 
'TA_EMP_ID',
'PLACE_FROM',
'PLACE_TO',
'OPEN_MTR',
'CLOSE_MTR',
'TAX_RS',
'FARE_RS',
'PETROL_LTR',
'LOCAL_CONVEYANCE',
'HOTEL_RS',
'POSTAGE_RS',
'PRINTING_RS',
'MISC_RS',
'UPLOAD_1',
'UPLOAD_2',
'UPLOAD_3',
'UPLOAD_4', 
'REMARKS',
'STATUS',
'PLACE_TO1',
'PLACE_TO2',
'PLACE_TO3',
'PLACE_TO4',
'DAILY_DA'
);

$json = array();
$mcrypt = new MCrypt();
foreach ($keys as $key) {
$this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
}
$log->write($this->request->post);
$this->load->model('account/activity');
$activity_data = $this->request->post;
$this->model_account_activity->addActivity('ta', $activity_data);
$this->load->model('ta/ta');
//if ((!empty($this->request->files['UPLOAD_1']['name'])) || (!empty($this->request->files['UPLOAD_2']['name'])) || (!empty($this->request->files['UPLOAD_3']['name'])) || (!empty($this->request->files['UPLOAD_4']['name']) )){
if(isset($this->request->post["SID"]) !="0"){
$api_info = $this->model_ta_ta->taupdate($this->request->post);
$log->write($api_info);
if ($api_info>0){ 
$this->load->model('account/customer');
$json = 1;
}
else{
$json = 0;//$mcrypt->decrypt($this->language->get('error_login'));
}
}else{
$json = 0;
}
$this->response->addHeader('Content-Type: application/json');
$this->response->setOutput(json_encode($json));
}
}
