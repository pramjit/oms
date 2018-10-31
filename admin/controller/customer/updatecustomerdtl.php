<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerCustomerUpdatecustomerdtl extends Controller {
	public function index() {
          

		$this->load->model('setting/store');
                $this->load->model('customer/updatecustomer');
         
                $data['token'] = $this->session->data['token'];
                $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                
                $data['USR'] = $this->model_customer_updatecustomer->getOneEmpdetl();
                
                
                
                
                $data["Roles"]  = $this->model_customer_updatecustomer->getRole();
		$data["States"] = $this->model_customer_updatecustomer->getState();
                
                $StId =$data['USR']['state_id'];
                $empId=$data['USR']['customer_id'];
                $empGrpId=$data['USR']['customer_group_id'];
                $data['Dists']  = $this->model_customer_updatecustomer->getDistList($StId);
                $AcDists = $this->model_customer_updatecustomer->getAcDist($empId);
               
                $AcDist=array();
                foreach($AcDists as $AD){
                    array_push($AcDist, $AD['GEO_ID']);
                }
                $data['AcDist'] =$AcDist;
                
                $data['AreaMngr']=$this->model_customer_updatecustomer->getAreaMngr();
                if($empGrpId==4){
                    $MoAm=$this->model_customer_updatecustomer->ParAm($empId);
                    if(empty($MoAm)){ $data['ParAM']=0; }
                    $data['ParAM']=$MoAm;
                }else{
                    $data['ParAM']=0;
                }

                $this->response->setOutput($this->load->view('customer/updatecustomerdtl.tpl', $data));
        }
         public function custupdatedtl()        {
            
            $this->load->model('customer/updatecustomer');
            $order_total = $this->model_customer_updatecustomer->custupdatedtl($this->request->post);
             $this->response->redirect($this->url->link('customer/updatecustomer', 'token=' . $this->session->data['token'], 'SSL'));
            } 

        public function getam()        {
            $this->load->model('customer/updatecustomer');
            $tot_ams = $this->model_customer_updatecustomer->getamanager();
            echo '<select name="am_id" id="am_id" class="form-control select2" >';
            foreach ($tot_ams as $tot_am) { 
                echo '<option value="'.$tot_am["customer_id"].'" >'.$tot_am["firstname"].'</option>';
            }
            echo '</select>';
                
         
        }      
                
       
         public function getdistrict()        {
            $this->load->model('customer/updatecustomer');
            $tot_dists = $this->model_customer_updatecustomer->getdistrict();
            echo '<select name="dist_id" id="dist_id" class="form-control select2-selection select2-selection--multiple"  multiple="multiple" >';
             echo ' <option value=""> Select District</option> ';
            foreach ($tot_dists as $tot_dist) { 
                echo '<option value="'.$tot_dist["SID"].'">'.$tot_dist["NAME"].'</option>';
            }
            echo '</select>';
              
        }
        public function customerupdate(){
            $this->load->model('customer/updatecustomer');
            $Success = $this->model_customer_updatecustomer->UPDATEEmpData($this->request->post);
            if($Success){
                $this->response->redirect($this->url->link('customer/updatecustomer', 'token=' . $this->session->data['token'], 'SSL'));
            }else{
                $this->response->redirect($this->url->link('customer/createcustomer', 'token=' . $this->session->data['token'], 'SSL'));
            }
        }
        
            
}