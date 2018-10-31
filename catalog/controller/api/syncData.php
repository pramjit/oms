<?php
class Fileid{
    public $id="";
  } 
class ControllerApisyncData extends Controller {
	public function index() {            
        $log=new Log("SyncData".date('Y_m_d').".log");
        $log->write($this->request->post);
        
        //log to table
        $this->load->model('account/activity');
        $activity_data = $this->request->post;
        $this->model_account_activity->addActivity('syncData', $activity_data);
        
	//$usr_id='6';
        $usr_id=$this->request->post["emp_id"]=1;
        $this->load->model('sync/syncData');
        $this->model_sync_syncData->SyncFunction($usr_id);
        $json=new Fileid();
        
        $json->id=$this->model_sync_syncData->getId();    
        $log->write("SyncResponseGet: ".json_encode($json));
        $this->response->addHeader('Content-Type: application/json');
	$this->response->setOutput(json_encode($json));
        $log->write("SyncResponseSend: ".json_encode($json));
	}
        
        
        public function download(){
            
            $log=new Log("SyncData".date('Y_m_d').".log");
            $log->write($this->request->post);
            $log->write($this->request->get);
            
            $this->load->model('account/activity');
            $activity_data = $this->request->get;
            $this->model_account_activity->addActivity('download', $activity_data);
			
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
