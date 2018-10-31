<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerCustomerUpdatecustomer extends Controller {
	public function index() {
		$this->load->language('report/sale_report');
		$this->load->language('report/sale_order');
		$this->document->setTitle('Add Employee');
		$this->load->model('setting/store');
                $this->load->model('customer/updatecustomer');
                
                $data['token'] = $this->session->data['token'];
                $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
	       
                //$data["listSTATs"] = $this->model_customer_updatecustomer->getState($filter_data);
                //$data["listROLEs"] = $this->model_customer_updatecustomer->getRole($filter_data);
                $data["eList"] = $this->model_customer_updatecustomer->getEmpDtls();
                $order_total = $this->model_customer_updatecustomer->getEmptotal($filter_data);
                
                
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('customer/updatecustomer', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

                
                //print_r($data["EMPTOTALs"]);
               // $data["listDISTs"] = $this->model_customer_createcustomer->getdistrict();
                $this->response->setOutput($this->load->view('customer/updatecustomer.tpl', $data));
                //print_r($data["listDISTs"]);
        }


        public function customerinsrt()        {
                $this->load->model('customer/updatecustomer');
                $order_total = $this->model_customer_updatecustomer->customerinsrt($this->request->post);
                 $this->response->redirect($this->url->link('customer/updatecustomer', 'token=' . $this->session->data['token'], 'SSL'));
                }
       
         public function getdistrict()        {
                $this->load->model('customer/updatecustomer');
                $tot_dists = $this->model_customer_updatecustomer->getdistrict();
                echo '<select name="dist_id" id="dist_id" class="form-control select2">';
                foreach ($tot_dists as $tot_dist) { 
                    echo '<option value="'.$tot_dist["SID"].'">'.$tot_dist["NAME"].'</option>';
                }
                echo '</select>';
                
               /* 
                $tot_dists= count($data['tot_dists']);
                echo ' <option value=""> Select District</option> ';
                for($n=0;$n<$dpzone;$n++)
                {
                echo '<option value="'.$data['dp_zone'][$n]['SID'].'">'.$data['dp_zone'][$n]['NAME'].'</option>';
                }
                * 
                */
        }
        public function download_excel() {

	if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		

		$filter_data = array(
			'filter_dat'           => $filter_d
			
		);
        $this->load->model('customer/updatecustomer');
        $order_total = $this->model_customer_updatecustomer->getEmpdetl($filter_data);

		$data['orders'] = array();

		$results = $this->model_customer_updatecustomer->getEmpdetl($filter_data);
                
		                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        
        'User Id',
        'Name',
        'Role',
        'State',        
        'District'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     	
    $row = 2;
    
    foreach($results as $data)
    {
                               if($data['customer_group_id']=='1')
                                               $cust_role=  "Director";
                                            elseif($data['customer_group_id']=='2') 
                                            {
                                               $cust_role= "Sales Head"; 
                                            }
                                             elseif($data['customer_group_id']=='3') 
                                            {
                                               $cust_role=  "Area Manager"; 
                                            }
                                             elseif($data['customer_group_id']=='4') 
                                            {
                                               $cust_role=  "Marketing Officer"; 
                                            }
                                             elseif($data['customer_group_id']=='5') 
                                            {
                                               $cust_role=  "Asst Area Incharge"; 
                                            }
                                            elseif($data['customer_group_id']=='6') 
                                            {
                                               $cust_role=  "Supply Chain"; 
                                            }
                                            elseif($data['customer_group_id']=='7') 
                                            {
                                               $cust_role=  "Sales Executive"; 
                                            }
                                            elseif($data['customer_group_id']=='8') 
                                            {
                                               $cust_role=  "Administrator"; 
                                            }
                                            elseif($data['customer_group_id']=='9') 
                                            {
                                               $cust_role=  "MIS"; 
                                            }
                                            elseif($data['customer_group_id']=='10') 
                                            {
                                               $cust_role=  "Whole Sales Person"; 
                                            }
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['User_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['firstname']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $cust_role);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['State']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['district']);
      
   
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Employee_Detail_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
        	
        
            
}