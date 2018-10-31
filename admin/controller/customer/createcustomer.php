<?php
error_reporting(0);
class ControllerCustomerCreatecustomer extends Controller {
	public function index() {
		
            $this->document->setTitle('Add Employee');
            $this->load->model('setting/store');
            $this->load->model('customer/createcustomer');

            $data['token'] = $this->session->data['token'];
            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');


            $data["listSTATs"] = $this->model_customer_createcustomer->getState();
            $data["listROLEs"] = $this->model_customer_createcustomer->getRole();
            $this->response->setOutput($this->load->view('customer/createcustomer.tpl', $data));
            $id=$this->request->get['user_id'];

        }
        public function vldUserId(){
            $this->load->model('customer/createcustomer');
            $avl = $this->model_customer_createcustomer->vldUserId();
            if(!empty($avl)){
                echo 1;
            }else{
                echo 0;
            }
            
        }


        public function customerinsrt(){
            $this->load->model('customer/createcustomer');
            $Success = $this->model_customer_createcustomer->RegisterEmp($this->request->post);
            if($Success){
                $this->response->redirect($this->url->link('customer/updatecustomer', 'token=' . $this->session->data['token'], 'SSL'));
            }else{
                $this->response->redirect($this->url->link('customer/createcustomer', 'token=' . $this->session->data['token'], 'SSL'));
            }
        }
       
        public function getdistrict(){
            $this->load->model('customer/createcustomer');
            $tot_dists = $this->model_customer_createcustomer->getdistrict();
            foreach ($tot_dists as $tot_dist) { 
                echo '<option value="'.$tot_dist["SID"].'">'.$tot_dist["NAME"].'</option>';
            }
        }
        
        public function getam(){
            $this->load->model('customer/createcustomer');
            $tot_ams = $this->model_customer_createcustomer->getAreaManager();

            echo '<option value="0">Select Area Manager</option>';
            foreach ($tot_ams as $tot_am) { 
                echo '<option value="'.$tot_am["customer_id"].'">'.$tot_am["firstname"].'</option>';
            }
        }
        
            
}