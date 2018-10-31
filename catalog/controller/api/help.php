<?php

class ControllerApiHelp extends Controller {
	public function index() {
            $log=new Log("help.log");
            $log->write($this->request->post);
            $keys = array(
                        'empid',
                        'name',
                        'mobile_no',
                        'call',
                        'message');
            $json = array();
            $mcrypt = new MCrypt();
            foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
            }
            $log->write($this->request->post);
            //log to table
            $this->load->model('account/activity');
            $activity_data = $this->request->post;
            $this->model_account_activity->addActivity('help', $activity_data);
            $this->load->model('help/save');
            $api_info = $this->model_help_save->savehelp($this->request->post);
            if($api_info==1)
            {
                $json = 1;
            }
            else {               
                    $json = 2;
            }
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
    }
    public function getdata()
    {
        
        $log=new Log("helpdata.log");
        $mcrypt=new MCrypt();
        $log->write($this->request->post);
        $this->load->model('account/activity');
        $activity_data = $this->request->post;
        $this->model_account_activity->addActivity('help', $activity_data);
        $this->load->model('help/save');
       $empid=$mcrypt->decrypt($this->request->post['empid']);
        $results= $this->model_help_save->getdata($empid);
        $log->write($results);
if(!empty($results))
{
	foreach ($results as $result) {
	$data[] = array(
		'EMP_ID'      =>$mcrypt->encrypt( $result['EMP_ID']),
		'EMP_NAME'    =>$mcrypt->encrypt( $result['EMP_NAME']),
		'MOB_NO'      =>$mcrypt->encrypt( $result['MOB_NO']),
		'CALL_STS'    =>$mcrypt->encrypt( $result['CALL_STS']),
		'MSG'         =>$mcrypt->encrypt( $result['MSG']),
		'QRY_STS'     =>$mcrypt->encrypt( $result['QRY_STS']),
                'CR_DATE'     =>$mcrypt->encrypt( $result['CR_DATE']),
		'UP_DATE'     =>$mcrypt->encrypt( $result['UP_DATE'])
		
							
            );
        }
}
else
{
	$data=2;
}
	$log->write($data);
	$this->response->setOutput(json_encode($data));	
    }
}