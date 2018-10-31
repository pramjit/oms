<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerCustomerCreatecustomer extends Controller {
	public function index() {
		$this->load->language('report/sale_report');
		$this->load->language('report/sale_order');
		$this->document->setTitle('Add Employee');
		$this->load->model('setting/store');
                $this->load->model('customer/createcustomer');
         
                $data['token'] = $this->session->data['token'];
                $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
	       
                $data["listSTATs"] = $this->model_customer_createcustomer->getState();
                $data["listROLEs"] = $this->model_customer_createcustomer->getRole();
               // $data["listDISTs"] = $this->model_customer_createcustomer->getdistrict();
                $this->response->setOutput($this->load->view('customer/createcustomer.tpl', $data));
                echo $id=$this->request->get['user_id'];
                //print_r($data["listDISTs"]);
        }
        
        public function customerinsrt()        {
                $this->load->model('customer/createcustomer');
                    $Success = $this->model_customer_createcustomer->customerinsrt($this->request->post);
                    if($Success){
                        $this->response->redirect($this->url->link('customer/updatecustomer', 'token=' . $this->session->data['token'], 'SSL'));
                    }else{
                        $this->response->redirect($this->url->link('customer/createcustomer', 'token=' . $this->session->data['token'], 'SSL'));
                    }
                }
       
         public function getdistrict()        {
                $this->load->model('customer/createcustomer');
                $tot_dists = $this->model_customer_createcustomer->getdistrict();
               // echo '<select name="dist_id" id="dist_id" class="form-control select2" class="form-control select2-selection select2-selection--multiple"  multiple="multiple">';
                foreach ($tot_dists as $tot_dist) { 
                    echo '<option value="'.$tot_dist["SID"].'">'.$tot_dist["NAME"].'</option>';
                }
               // echo '</select>';
                
         
        }
        public function getam()        {
                $this->load->model('customer/createcustomer');
                $tot_ams = $this->model_customer_createcustomer->getamanager();
                echo '<select name="am_id" id="am_id" class="form-control select2" style="width: 94% !important">';
               
                foreach ($tot_ams as $tot_am) { 
                    echo '<option value="'.$tot_am["customer_id"].'">'.$tot_am["firstname"].'</option>';
                }
                echo '</select>';
                
         
        }
        
            
}