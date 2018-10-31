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
            }else{
                    $data['filter_dist'] = '';
                    $data['filter_dist_nm'] = 'Select District';
            }
            
            if(isset($this->request->get['gam_or_mo'])) {
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

        //$this->load->model('setting/store');
        $this->load->model('budget/budgetlist');
        $data['orders'] = array();
        $filter_data = array(
            'filter_name'       => $filter_name,
            'filter_name_id'    => $filter_name_id,
            'dist_id'           => $dist_id,
            'am_or_mo'          => $gam_or_mo,
            'month'             => $gmonth,
            'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'             => $this->config->get('config_limit_admin')
        );

        

        $data['orders'] = array();
     
            if (!empty($this->request->get['gmonth']) )
            {
                $results = $this->model_budget_budgetlist->getFilterList($filter_data);
                $order_total=$total_orders = $this->model_budget_budgetlist->getTotalOrders($filter_data);
            }
            foreach ($results as $result) {// print_r($result);
                    $avlkm = $this->model_budget_budgetlist->avlBudgetKm($result['CUST_ID'],$gmonth);
                    if($avlkm){$avlbugkm=$avlkm['AVL_KM'];}else{$avlbugkm=0.00;}
                    if (empty($result['BDGT_MONTH'])){$BDGT_MONTH=$gmonth;}else{$BDGT_MONTH=$result['BDGT_MONTH'];}
                    $data['order_list'][] = array(
                        'CUSTOMER_ID'          => $result['CUST_ID'],   
                        'customer_group_id'    => $result['CUST_GROUP_NAME'], 
                        'customer_name'        => $result['CUST_NAME'],
                        'geoname'              => $result['CUST_DIST_NAME'],
                        'geoid'                => $result['CUST_DIST'],
                        'budget_km'            => $result['ALL_BDGT_KM'],
                        'add_budget_km'        => $result['ADD_BDGT_KM'],
                        'month_name'           => $BDGT_MONTH,
                        'avl_km'               => $avlbugkm 
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
    

        $this->load->model('budget/budgetlist');
	$data["listDISTs"] = $this->model_budget_budgetlist->getdistrict();
       

	
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

		
                $this->response->setOutput($this->load->view('budget/budgetlist.tpl', $data));
	}
	
	 public function budgetsubmit()
            {
                $this->load->model('budget/budgetlist');
                $order_total = $this->model_budget_budgetlist->budgetsubmitdata($this->request->post);
                $this->response->redirect($this->url->link('budget/budgetlist', 'token=' . $this->session->data['token'], 'SSL'));
            }
	
}

?>