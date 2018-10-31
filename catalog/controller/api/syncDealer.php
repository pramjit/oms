<?php
class Fileid{
    public $id="";
  } 
class ControllerApisyncDealer extends Controller {
	public function index() {            
            $log=new Log("syncDealer.log");
             $log->write($this->request->post);
            //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('syncDealer', $activity_data);
			
       
        //
	$this->load->model('sync/syncDealer');
        $this->model_sync_syncDealer->SyncFunction($this->request->post["emp_id"]);
        
        $json=new Fileid();
        $json->id=$this->model_sync_syncDealer->getId();                
        $this->response->addHeader('Content-Type: application/json');
	$this->response->setOutput(json_encode($json));
	}
        
        
        public function download(){
            
            $log=new Log("syncDealer.log");
             $log->write($this->request->post);
             $log->write($this->request->get);


            //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->get;

				$this->model_account_activity->addActivity('downloadDealer', $activity_data);
			
       
        //
            $file=DIR_DOWNLOAD.$this->request->get["id"].".zip";
            $mask=  basename($this->request->get["id"]);
            if (!headers_sent()) {
                if (is_file($file)) {
                    header('Content-Type: application/octet-stream');
                    header('Content-Description: File Transfer');
                    header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));

                    readfile($file, 'rb');
                    exit;
                } else {
                    exit('Error: Could not find file ' . $file . '!');
                }
        }
            
        }
}