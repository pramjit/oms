<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerReportSaletarget extends Controller {
	public function index() {
		$this->load->language('report/sale_report');
		$this->load->language('report/sale_order');
		$this->document->setTitle('Add Employee');
		$this->load->model('setting/store');
                $this->load->model('report/saletarget');
         
                $data['token'] = $this->session->data['token'];
                $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
	       
                $data['prodname']= $this->model_report_saletarget->prodname($this->request->post); 
                //$data["listROLEs"] = $this->model_customer_createcustomer->getRole();
               // $data["listDISTs"] = $this->model_customer_createcustomer->getdistrict();
                $this->response->setOutput($this->load->view('report/saletarget.tpl', $data));
                //echo $id=$this->request->get['user_id'];
                //print_r($data["listDISTs"]);
        }


        public function saletargetsubmit()
            {
                $this->load->model('report/saletarget');
                $order_total = $this->model_report_saletarget->saletargetsubmitdata($this->request->post);
                $this->response->redirect($this->url->link('report/saletarget', 'token=' . $this->session->data['token'], 'SSL'));
            }
       
       
        }
        
            
