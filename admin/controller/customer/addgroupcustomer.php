<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of createdealer
 *
 * @author agent
 */
class Controllercustomeraddgroupcustomer  extends Controller{
    
       public function index() {
           $this->load->language('customer/addgroupcustomer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/addgroupcustomer');
                $data['getdata']= $this->model_customer_addgroupcustomer->getUserLevel();
              /*  
                if (($this->request->server['REQUEST_METHOD'] == 'POST') )
                                {
                  
			//CALL MODEL FUNCTION TO SAVE DATA
                    $this->model_customer_addgroupcustomer->addGroup($this->request->post);
                   
                  
                    $this->session->data['success'] = $this->language->get('text_success');

		    $this->response->redirect($this->url->link('customer/addgroupcustomer', 'token=' . $this->session->data['token'], 'SSL'));
			 
		}
                */
                //fatch Data
                  $data['groupnameshow']= $this->model_customer_addgroupcustomer->getGroupNameShow();
                
               //end fatch Data
                
                $data['heading_title'] = $this->language->get('heading_title');
                if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
                
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
                
                $data['addgroupcustomer'] = $this->url->link('customer/addgroupcustomer', 'token=' . $this->session->data['token'], 'SSL');
                
                $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('customer/addgroupcustomer', 'token=' . $this->session->data['token'], 'SSL')
		);
                $data['button_save'] = $this->language->get('button_save');
		$data['button_back'] = $this->language->get('button_back');
                $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                $this->response->setOutput($this->load->view('customer/addgroupcustomer.tpl', $data));
       }
       
       public function addGroup(){
           $this->load->language('customer/addgroupcustomer');
            $json = array();
          $this->load->model('customer/addgroupcustomer');
          
         
          
                     if (isset($this->request->post['name'])&&isset($this->request->post['addlevel'])) {
                            
                       $this->model_customer_addgroupcustomer->addGroup($this->request->post); 
                       $this->session->data['success'] = $this->language->get('text_success');
                       $json['redirectf'] = str_replace('&amp;', '&', $this->url->link('customer/addgroupcustomer', 'token=' . $this->session->data['token'], 'SSL'));  
               
                        }
                        else{
                        $json['error']['warning'] ="Error";
                            
                        }
                        $this->response->addHeader('Content-Type: application/json');
                        $this->response->setOutput(json_encode($json));
       }
       
       
}
