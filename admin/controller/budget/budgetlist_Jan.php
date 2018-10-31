<?php
//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerBudgetbudgetlist extends Controller{
	public function index()
	{            
             $this->load->language('report/Inventory_report');

        $this->document->setTitle('Add Tour Budget');

         if (isset($this->request->get['dist_id'])) {
			$dist_id=$data['filter_dist'] = $this->request->get['dist_id'];
                        $data['filter_dist_nm'] = $this->request->get['dist_nm'];
		} else {
			$data['filter_dist'] = '';
                        $data['filter_dist_nm'] = 'Select District';
		}
                if (isset($this->request->get['gam_or_mo'])) {
			$gam_or_mo=$data['gam_or_mo'] = $this->request->get['gam_or_mo'];
                        
		} else {
			$data['gam_or_mo'] = '';
               
		}
                if (isset($this->request->get['gmonth'])) {
			$gmonth=$data['gmonth'] = $this->request->get['gmonth'];
                        
		} else {
			$data['gmonth'] = '';
               
		}
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';
                
                if (isset($this->request->get['gam_or_mo'])) {
            $url .= '&gam_or_mo=' . $this->request->get['gam_or_mo'];
        }
                if (isset($this->request->get['gmonth'])) {
            $url .= '&gmonth=' . $this->request->get['gmonth'];
        }
                
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('report/inventory_report', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $this->load->model('setting/store');
        $this->load->model('budget/budgetlist');
        $data['orders'] = array();

        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_name_id' => $filter_name_id,
            'dist_id'          => $dist_id,
            'am_or_mo'           => $gam_or_mo,
            'month'              => $gmonth,
            'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'                  => $this->config->get('config_limit_admin')
        );

        

        $data['orders'] = array();
     
                if (!empty($this->request->get['gmonth']) )
                 {
                 //$order_total = $this->model_budget_budgetlist->getTotalInventoryProductWise($filter_data);
                 $results = $this->model_budget_budgetlist->getFilterList($filter_data);
                 $order_total=$total_orders = $this->model_budget_budgetlist->getTotalOrders($filter_data);
                }
                // $data['order_list'] = $this->model_budget_budgetlist->getFilterList();
        foreach ($results as $result) {// print_r($result);
                     $data['order_list'][] = array(
                         'CUSTOMER_ID' => $result['CUSTOMER_ID'],   
                         'customer_group_id' => $result['customer_group_id'], 
                         'customer_name' => $result['customer_name'],
                         'geoname'      => $result['geoname'],
                         'geoid'      => $result['geoid'],
                         'budget_km'  => $result['budget_km'],
                         'month_name' => $result['month_name']
                                
                
                                
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_all_status'] = $this->language->get('text_all_status');

        $data['column_date_start'] = $this->language->get('column_date_start');
        $data['column_date_end'] = $this->language->get('column_date_end');
        $data['column_title'] = $this->language->get('column_title');
        $data['column_orders'] = $this->language->get('column_orders');
        $data['column_total'] = $this->language->get('column_total');

        $data['entry_date_start'] = $this->language->get('entry_date_start');
        $data['entry_date_end'] = $this->language->get('entry_date_end');
        $data['entry_group'] = $this->language->get('entry_group');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['token'] = $this->session->data['token'];
    
	$this->load->model('supplychain/supplychainorder');
        $this->load->model('budget/budgetlist');
	$data["listDISTs"] = $this->model_budget_budgetlist->getdistrict();
        $data['sap'] = $this->model_supplychain_supplychainorder->getsap();
        $data['wsp'] = $this->model_supplychain_supplychainorder->getwspname();
       
            
            if (isset($this->request->get['sto_id'])) {    
            $data['fi_sto_id']=$this->request->get['sto_id'];  
            $data['fi_sto_id_name']=$this->request->get['sto_id_name']; 
            }
            else {
            $data['fi_sto_id']='';  
            $data['fi_sto_id_name']=''; 
            }
            if (isset($this->request->get['sap_id'])) { 
            $data['fi_sap_id']=$this->request->get['sap_id'];  
            $data['fi_sap_id_name']=$this->request->get['sap_id_name']; 
            }
            else {
            $data['fi_mao_id']='';  
            $data['fi_mao_id_name']='';   
            }
            if (isset($this->request->get['pro_id'])) { 
            $data['fi_pro_id']=$this->request->get['pro_id'];  
            $data['fi_pro_id_name']=$this->request->get['pro_id_name']; 
            }
            else {
            $data['fi_pro_id']='';  
            $data['fi_pro_id_name']='';    
            }
            //
            /*} */
            

	
        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('budget/budgetlist', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        
        $data['filter_name_id'] = $filter_name_id;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

		//$this->response->setOutput($this->load->view('report/Inventory_report_product_wise.tpl', $data));
                $this->response->setOutput($this->load->view('budget/budgetlist.tpl', $data));
	}
	
	 public function budgetsubmit()
            {
                $this->load->model('budget/budgetlist');
                $order_total = $this->model_budget_budgetlist->budgetsubmitdata($this->request->post);
                if($order_total==0){
                    $data['AddUpd']=0;
                }
                else{
                    $data['AddUpd']=1;
                }
                $this->response->redirect($this->url->link('budget/budgetlist', 'token=' . $this->session->data['token'], 'SSL'));
            }
	
}

?>