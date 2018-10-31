<?php
class ControllerDashboardRecent extends Controller {
	public function index() {
		$this->load->language('dashboard/recent');

		$data['heading_title'] ='Order Summary';
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		
		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_view'] = $this->language->get('button_view');

		$data['token'] = $this->session->data['token'];

		// Last 5 Orders
		$data['orders'] = array();

		$filter_data = array(
			'sort'  => 'o.date_added',
                        'dist_id' => $filter_dist,
                    'store_id' => $filter_store,
			'order' => 'DESC',
			'start' => 0,
			'limit' => 5
		);

		if($this->user->getGroupId()!="1")
                    {
		$filter_data = array(
                    'filter_user_id' => $this->user->getId(),
			'sort'  => 'o.date_added',
                    'dist_id' => $filter_dist,
                    'store_id' => $filter_store,
			'order' => 'DESC',
			'start' => 0,
			'limit' => 5
		);
                }
		$data["listDISTs"] = $this->model_sale_order->getdistrict();
                $data["listWSs"] = $this->model_sale_order->getWs();
                
                $data["monf"] = $this->model_sale_order->getOrder1month($filter_data);
                $data["mons"] = $this->model_sale_order->getOrder2month($filter_data);
                $data["mont"] = $this->model_sale_order->getOrder3month($filter_data);
                $data["monfr"] = $this->model_sale_order->getOrdercurmonth($filter_data);
                
		$results = $this->model_sale_order->getOrders($filter_data);               

		foreach ($results as $result) {
			$data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'customer'   => $result['customer'],
				'status'     => $result['status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'view'       => $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], 'SSL'),
			);
		}

		return $this->load->view('dashboard/recent.tpl', $data);
	}
}
