<?php
class ControllerCommonMenu extends Controller {
	public function index() {
		$this->load->language('common/menu');
		
		
		$this->load->model('user/user');
	
			$this->load->model('tool/image');
	

			$user_info = $this->model_user_user->getUser($this->user->getId());
	
			if ($user_info) {
				$data['firstname'] = $user_info['firstname'];
				$data['lastname'] = $user_info['lastname'];
	
				$data['user_group'] = $user_info['user_group'];
	
				if (is_file(DIR_IMAGE . $user_info['image'])) {
					$data['image'] = $this->model_tool_image->resize($user_info['image'], 45, 45);
				} else {
					$data['image'] = '';
				}
			} else {
				$data['firstname'] = '';
				$data['lastname'] = '';
				$data['user_group'] = '';
				$data['image'] = '';
			}			

			// Create a 3 level menu array
			// Level 2 can not have children
			
			$data['token'] = $this->session->data['token'];
			// Menu

				if($data['user_group']=='Administrator'){

			$data['menus'][] = array(
				'id'       => 'menu-dashboard',
				'icon'	   => 'fa-dashboard',
				'name'	   => $this->language->get('text_dashboard'),
				'href'     => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
				'children' => array()
			);

}else{

			if ($this->user->hasPermission('access', 'common/dashboard')) {
			$data['menus'][] = array(
				'id'       => 'menu-dashboard',
				'icon'	   => 'fa-dashboard',
				'name'	   => $this->language->get('text_dashboard'),
				'href'     => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
				'children' => array()
			);
			}
}
			// Catalog
			$catalog = array();
			
			if ($this->user->hasPermission('access', 'catalog/category')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_category'),
					'href'     => $this->url->link('catalog/category', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/product')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_product'),
					'href'     => $this->url->link('catalog/product', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/recurring')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_recurring'),
					'href'     => $this->url->link('catalog/recurring', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/filter')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_filter'),
					'href'     => $this->url->link('catalog/filter', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			// Attributes
			$attribute = array();
			
			if ($this->user->hasPermission('access', 'catalog/attribute')) {
				$attribute[] = array(
					'name'     => $this->language->get('text_attribute'),
					'href'     => $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/attribute_group')) {
				$attribute[] = array(
					'name'	   => $this->language->get('text_attribute_group'),
					'href'     => $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($attribute) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_attribute'),
					'href'     => '',
					'children' => $attribute
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/option')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_option'),
					'href'     => $this->url->link('catalog/option', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/manufacturer')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_manufacturer'),
					'href'     => $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/download')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_download'),
					'href'     => $this->url->link('catalog/download', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/review')) {		
				$catalog[] = array(
					'name'	   => $this->language->get('text_review'),
					'href'     => $this->url->link('catalog/review', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);		
			}
			
			if ($this->user->hasPermission('access', 'catalog/information')) {		
				$catalog[] = array(
					'name'	   => $this->language->get('text_information'),
					'href'     => $this->url->link('catalog/information', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);					
			}
			
			if ($catalog) {
				$data['menus'][] = array(
					'id'       => 'menu-catalog',
					'icon'	   => 'fa-tags', 
					'name'	   => $this->language->get('text_catalog'),
					'href'     => '',
					'children' => $catalog
				);		
			}
			
	
			// Extension
			$extension = array();
			/*
			if ($this->user->hasPermission('access', 'extension/store')) {		
				$extension[] = array(
					'name'	   => $this->language->get('text_store'),
					'href'     => $this->url->link('extension/store', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);					
			}
			*/
			if ($this->user->hasPermission('access', 'extension/installer')) {		
				$extension[] = array(
					'name'	   => $this->language->get('text_installer'),
					'href'     => $this->url->link('extension/installer', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);					
			}	
			
			
					
			if ($this->user->hasPermission('access', 'extension/modification')) {
				$extension[] = array(
					'name'	   => $this->language->get('text_modification'),
					'href'     => $this->url->link('extension/modification', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
if ($this->user->hasPermission('access', 'extension/module')) {		
				$extension[] = array(
					'name'	   => $this->language->get('text_module'),
					'href'     => $this->url->link('extension/module', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'extension/shipping')) {
				$extension[] = array(
					'name'	   => $this->language->get('text_shipping'),
					'href'     => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
if ($this->user->hasPermission('access', 'extension/payment')) {
				$extension[] = array(
					'name'	   => $this->language->get('text_payment'),
					'href'     => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
if ($this->user->hasPermission('access', 'extension/total')) {
				$extension[] = array(
					'name'	   => $this->language->get('text_total'),
					'href'     => $this->url->link('extension/total', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}

if ($this->user->hasPermission('access', 'extension/feed')) {
				$extension[] = array(
					'name'	   => $this->language->get('text_feed'),
					'href'     => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
					
			if ($extension) {					
				$data['menus'][] = array(
					'id'       => 'menu-extension',
					'icon'	   => 'fa-puzzle-piece', 
					'name'	   => $this->language->get('text_extension'),
					'href'     => '',
					'children' => $extension
				);		
			}
			

/*Sale Order*/
			$saleinventory=array();
			
			if ($this->user->hasPermission('access', 'saleordergenerate/sordergenerate')) {
				$saleinventory[] = array(
					'name'	   => 'Generate Sale Order',
					'href'     => $this->url->link('saleordergenerate/sordergenerate', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}

                        if ($saleinventory) {
				$data['menus'][] = array(
					'id'       => 'saleinventory',
					'icon'	   => 'fa fa-pencil-square-o  fa-fw', 
					'name'	   => 'Sale Order',
					'href'     => '',
					'children' => $saleinventory
				);
			}
/*Sale Order End*/
/*Supply Chain*/
			$supplyinventory=array();
			
			if ($this->user->hasPermission('access', 'supplychain/supplychainorder')) {
				$supplyinventory[] = array(
					'name'	   => 'Dispatch Order Generate',
					'href'     => $this->url->link('supplychain/supplychainorder', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}

                        if ($supplyinventory) {
				$data['menus'][] = array(
					'id'       => 'supplyinventory',
					'icon'	   => 'fa fa-pencil-square-o  fa-fw', 
					'name'	   => 'Supply Chain',
					'href'     => '',
					'children' => $supplyinventory
				);
			}
/*Supply Chain End*/
                        
/*Geo Start*/
			$addgeo=array();
			
			if ($this->user->hasPermission('access', 'geo/addgeo')) {
				$addgeo[] = array(
					'name'	   => 'Geo  (Upper)',
					'href'     => $this->url->link('geo/addgeo', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                        if ($this->user->hasPermission('access', 'geo/addgeovillage')) {
				$addgeo[] = array(
					'name'	   => 'Geo  (Lower)',
					'href'     => $this->url->link('geo/addgeovillage', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                        if ($addgeo) {
				$data['menus'][] = array(
					'id'       => '$addgeo',
					'icon'	   => 'fa fa-pencil-square-o  fa-fw', 
					'name'	   => 'Geography',
					'href'     => '',
					'children' => $addgeo
				);
			}
/*Geo End*/
/*Customer Start*/
			$addemp=array();
			
			if ($this->user->hasPermission('access', 'customer/createcustomer')) {
				$addemp[] = array(
					'name'	   => 'Add Employee',
					'href'     => $this->url->link('customer/createcustomer', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                        if ($this->user->hasPermission('access', 'customer/updatecustomer')) {
				$addemp[] = array(
					'name'	   => 'Update Employee',
					'href'     => $this->url->link('customer/updatecustomer', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                        
                      
                        if ($addemp) {
				$data['menus'][] = array(
					'id'       => '$addemp',
					'icon'	   => 'fa fa-pencil-square-o  fa-fw', 
					'name'	   => 'Add Employee',
					'href'     => '',
					'children' => $addemp
				);
			}
/*Customer End*/

/*MO Report Start*/
			$kaireport=array();
			
			if ($this->user->hasPermission('access', 'report/dailysummaryreport')) {
				$kaireport[] = array(
					'name'	   => 'Daily Summary Report',
					'href'     => $this->url->link('report/dailysummaryreport', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                        if ($this->user->hasPermission('access', 'report/monthlysummaryreport')) {
				$kaireport[] = array(
					'name'	   => 'Monthly Summary Report',
					'href'     => $this->url->link('report/monthlysummaryreport', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                        if ($this->user->hasPermission('access', 'report/farmersummaryreport')) {
				$kaireport[] = array(
					'name'	   => 'Farmer Summary Report',
					'href'     => $this->url->link('report/farmersummaryreport', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                        if ($this->user->hasPermission('access', 'report/possummaryreport')) {
				$kaireport[] = array(
					'name'	   => 'Retailer Summary Report',
					'href'     => $this->url->link('report/possummaryreport', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                        if ($this->user->hasPermission('access', 'report/jeepreport')) {
				$kaireport[] = array(
					'name'	   => 'Jeep Campaign Report',
					'href'     => $this->url->link('report/jeepreport', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                        if ($this->user->hasPermission('access', 'report/farmdemoreport')) {
				$kaireport[] = array(
					'name'	   => 'Farm Demo',
					'href'     => $this->url->link('report/farmdemoreport', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                        if ($this->user->hasPermission('access', 'report/tasummaryreport')) {
				$kaireport[] = array(
					'name'	   => 'Ta Summary Report',
					'href'     => $this->url->link('report/tasummaryreport', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                        if ($this->user->hasPermission('access', 'report/indentreport')) {
				$kaireport[] = array(
					'name'	   => 'Indent Report',
					'href'     => $this->url->link('report/indentreport', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                          /* if ($this->user->hasPermission('access', 'report/saleorderreport')) {
				$kaireport[] = array(
					'name'	   => 'Sale Order Report',
					'href'     => $this->url->link('report/saleorderreport', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}*/
                         if ($this->user->hasPermission('access', 'report/jeepcampaignreport')) {
				$kaireport[] = array(
					'name'	   => 'Jeep Campaign Report',
					'href'     => $this->url->link('report/jeepcampaignreport', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                         if ($this->user->hasPermission('access', 'report/planreport')) {
				$kaireport[] = array(
					'name'	   => 'Plan Report',
					'href'     => $this->url->link('report/planreport', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                    /*    if ($this->user->hasPermission('access', 'report/saletargetreport')) {
				$kaireport[] = array(
					'name'	   => 'Sale Target Report',
					'href'     => $this->url->link('report/saletargetreport', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}*/
// New Report Sales & Supply
if ($this->user->hasPermission('access', 'report/stocktransfer')) {
$kaireport[] = array(
'name' => 'Stock Transfer Report',
'href' => $this->url->link('report/stocktransfer', 'token=' . $this->session->data['token'], true),
'children' => array() 
); 
}
if ($this->user->hasPermission('access', 'report/materialreceive')) {
$kaireport[] = array(
'name' => 'Material Receive Report',
'href' => $this->url->link('report/materialreceive', 'token=' . $this->session->data['token'], true),
'children' => array() 
); 
}
if ($this->user->hasPermission('access', 'report/inventorystatus')) {
$kaireport[] = array(
'name' => 'Inventory Status Report',
'href' => $this->url->link('report/inventorystatus', 'token=' . $this->session->data['token'], true),
'children' => array() 
); 
}
if ($this->user->hasPermission('access', 'report/saleordersummary')) {
$kaireport[] = array(
'name' => 'Sale Order Summary',
'href' => $this->url->link('report/saleordersummary', 'token=' . $this->session->data['token'], true),
'children' => array() 
); 
}
if ($this->user->hasPermission('access', 'report/materialdispatchreport')) {
$kaireport[] = array(
'name' => 'Material Dispatch Report',
'href' => $this->url->link('report/materialdispatchreport', 'token=' . $this->session->data['token'], true),
'children' => array() 
); 
}
if ($this->user->hasPermission('access', 'report/materialdispatchsummary')) {
$kaireport[] = array(
'name' => 'Material Dispatch Summary',
'href' => $this->url->link('report/materialdispatchsummary', 'token=' . $this->session->data['token'], true),
'children' => array() 
); 
}
                        $tareport=array();
			
			if ($this->user->hasPermission('access', 'report/tabillreport')) {
				$tareport[] = array(
					'name'	   => 'Ta Report 1',
					'href'     => $this->url->link('report/tabillreport', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                        if ($this->user->hasPermission('access', 'report/tabillreport2')) {
				$tareport[] = array(
					'name'	   => 'Ta Report 2',
					'href'     => $this->url->link('report/tabillreport2', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                        if ($this->user->hasPermission('access', 'report/tabillreport3')) {
				$tareport[] = array(
					'name'	   => 'Ta Report 3',
					'href'     => $this->url->link('report/tabillreport3', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}

                        if ($tareport) {
				$data['menus'][] = array(
					'id'       => 'tareport',
					'icon'	   => 'fa fa-pencil-square-o  fa-fw', 
					'name'	   => 'Ta Report ',
					'href'     => '',
					'children' => $tareport
				);
			}

                        if ($kaireport) {
				$data['menus'][] = array(
					'id'       => 'kaireport',
					'icon'	   => 'fa fa-file  fa-fw', 
					'name'	   => 'Report',
					'href'     => '',
					'children' => $kaireport
				);
                                
			}
/*Mo Report End*/  
                        
                        
 /*Sale Order*/
			$saletarget=array();
			
			if ($this->user->hasPermission('access', 'report/saletarget')) {
				$saletarget[] = array(
					'name'	   => 'Sale Target Generate',
					'href'     => $this->url->link('report/saletarget', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}

                        if ($saleinventory) {
				$data['menus'][] = array(
					'id'       => '$saletarget',
					'icon'	   => 'fa fa-pencil-square-o  fa-fw', 
					'name'	   => 'Sale Target',
					'href'     => '',
					'children' => $saletarget
				);
			}
/*Sale Order End*/                       
 //****************************** UNNATI TAB START ********************************//
                        
                        
/*store inventory request*/			
/*
			$storeinventory=array();
			
			if ($this->user->hasPermission('access', 'inventory/purchase_order')) {
				$storeinventory[] = array(
					'name'	   => $this->language->get('text_purchase_order_inv'),
					'href'     => $this->url->link('inventory/purchase_order', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}

if ($storeinventory) {
				$data['menus'][] = array(
					'id'       => 'storeinventory',
					'icon'	   => 'fa fa-pencil-square-o  fa-fw', 
					'name'	   => 'Store Inventory',
					'href'     => '',
					'children' => $storeinventory
				);
			}
*/
/*stock*/
/*		
                       $stockinventory=array();
			
			if ($this->user->hasPermission('access', 'stock/purchase_order')) {
				$stockinventory[] = array(
					'name'	   => 'Stock',
					'href'     => $this->url->link('stock/purchase_order', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}

	if ($stockinventory) {
				$data['menus'][] = array(
					'id'       => 'stockinventory',
					'icon'	   => 'fa fa-pencil-square-o  fa-fw', 
					'name'	   => 'Stock Transfer',
					'href'     => '',
					'children' => $stockinventory
				);
			}
 
 */
/*stock end*/
/*Inventory*/
  /*                      
		
			$inventory=array();
			
			if ($this->user->hasPermission('access', 'purchase/purchase_order')) {
				$inventory[] = array(
					'name'	   => $this->language->get('text_purchase_order'),
					'href'     => $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'purchase/return_orders')) {
				$inventory[] = array(
					'name'	   => $this->language->get('text_purchase_return'),
					'href'     => $this->url->link('purchase/return_orders', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			
			if ($this->user->hasPermission('access', 'purchase/sale_offer')) {
				$inventory[] = array(
					'name'	   => $this->language->get('sale_offer_text'),
					'href'     => $this->url->link('purchase/sale_offer', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			$supplier=array();
			
			if ($this->user->hasPermission('access', 'purchase/supplier')) {
				$supplier[] = array(
					'name'	   => 'Supplier',
					'href'     => $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			
			if ($this->user->hasPermission('access', 'purchase/supplier_group')) {
				$supplier[] = array(
					'name'	   => 'Supplier Group',
					'href'     => $this->url->link('purchase/supplier_group', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if($supplier)
			{
			$inventory[] = array(
					'name'	   =>'Supplier' ,
					'href'     => '',
					'children' => $supplier
				);	
			}
			$sreport=array();
			
			if ($this->user->hasPermission('access', 'purchase/received_orders')) {
				$sreport[] = array(
					'name'	   => $this->language->get('received_orders'),
					'href'     => $this->url->link('purchase/received_orders', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'purchase/pending_orders')) {
				$sreport[] = array(
					'name'	   => $this->language->get('pending_orders'),
					'href'     => $this->url->link('purchase/pending_orders', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			
if ($this->user->hasPermission('access', 'purchase/dead_chart')) {
				$sreport[] = array(
					'name'	   => $this->language->get('dead_chart_text'),
					'href'     => $this->url->link('purchase/dead_chart', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}							
			if ($this->user->hasPermission('access', 'purchase/stock_report')) {
				$sreport[] = array(
					'name'	   => $this->language->get('stock_report_text'),
					'href'     => $this->url->link('purchase/stock_report', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
		
			if ($this->user->hasPermission('access', 'purchase/stock_report')) {
				$sreport[] = array(
					'name'	   => $this->language->get('stock_inout_text'),
					'href'     => $this->url->link('purchase/stock_report/stock_inout', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'purchase/stock_report/dead_products')) {
				$sreport[] = array(
					'name'	   => $this->language->get('dead_products_text'),
					'href'     => $this->url->link('purchase/stock_report/dead_products', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
		if ($this->user->hasPermission('access', 'purchase/stock_report/best_products')) {
				$sreport[] = array(
					'name'	   => $this->language->get('best_products_text'),
					'href'     => $this->url->link('purchase/stock_report/best_products', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if($sreport)
			{
			$inventory[] = array(
					'name'	   =>'Report' ,
					'href'     => '',
					'children' => $sreport
				);	
			}
			
			
			//								
$chart=array();
			
			if ($this->user->hasPermission('access', 'purchase/chart')) {
				$chart[] = array(
					'name'	   => $this->language->get('purchase_chart_text'),
					'href'     => $this->url->link('purchase/chart/purchase_chart', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'purchase/chart')) {
				$chart[] = array(
					'name'	   => $this->language->get('sale_chart_text'),
					'href'     => $this->url->link('purchase/chart', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if($chart)
			{
			$inventory[] = array(
					'name'	   =>'Chart' ,
					'href'     => '',
					'children' => $chart
				);	
			}
		
		
		
			
			
			if ($inventory) {
				$data['menus'][] = array(
					'id'       => 'inventory',
					'icon'	   => 'fa fa-pencil-square-o  fa-fw', 
					'name'	   => 'Inventory',
					'href'     => '',
					'children' => $inventory
				);
			}
			
			//pos
			$pos=array();
			if ($this->user->hasPermission('access', 'pos/pos')) {
				$pos[] = array(
					'name'	   => $this->language->get('text_pos'),
					'href'     => $this->url->link('pos/pos', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
if ($this->user->hasPermission('access', 'pos/dashboard')) {	
				$pos[] = array(
					'name'	   => $this->language->get('text_pos_dash'),
					'href'     => $this->url->link('pos/dashboard', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
		

if ($pos) {
				$data['menus'][] = array(
					'id'       => 'pos',
					'icon'	   => 'fa-shopping-cart', 
					'name'	   => $this->language->get('text_pos_main'),
					'href'     => '',
					'children' => $pos
				);
			}


			
			// Sales
			$sale = array();
			
			if ($this->user->hasPermission('access', 'sale/order')) {
				$sale[] = array(
					'name'	   => $this->language->get('text_order'),
					'href'     => $this->url->link('sale/order', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'lead/orderleads')) {
				$sale[] = array(
					'name'	   => $this->language->get('text_order')." Leads",
					'href'     => $this->url->link('lead/orderleads', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}

			
			if ($this->user->hasPermission('access', 'sale/recurring')) {	
				$sale[] = array(
					'name'	   => $this->language->get('text_recurring'),
					'href'     => $this->url->link('sale/recurring', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'sale/return')) {
				$sale[] = array(
					'name'	   => $this->language->get('text_return'),
					'href'     => $this->url->link('sale/return', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			// Voucher
			$voucher = array();
			
			if ($this->user->hasPermission('access', 'sale/voucher')) {
				$voucher[] = array(
					'name'	   => $this->language->get('text_voucher'),
					'href'     => $this->url->link('sale/voucher', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'sale/voucher_theme')) {
				$voucher[] = array(
					'name'	   => $this->language->get('text_voucher_theme'),
					'href'     => $this->url->link('sale/voucher_theme', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($voucher) {
				$sale[] = array(
					'name'	   => $this->language->get('text_voucher'),
					'href'     => '',
					'children' => $voucher		
				);		
			}
			
			
			
			// Customer
			$customer = array();
			
			if ($this->user->hasPermission('access', 'sale/customer')) {
				$customer[] = array(
					'name'	   => $this->language->get('text_customer'),
					'href'     => $this->url->link('sale/customer', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'sale/customer_group')) {
				$customer[] = array(
					'name'	   => $this->language->get('text_customer_group'),
					'href'     => $this->url->link('sale/customer_group', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'sale/custom_field')) {		
				$customer[] = array(
					'name'	   => $this->language->get('text_custom_field'),
					'href'     => $this->url->link('sale/custom_field', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
if ($this->user->hasPermission('access', 'sale/customer_ban_ip')) {		
				$customer[] = array(
					'name'	   => $this->language->get('text_customer_ban_ip'),
					'href'     => $this->url->link('sale/customer_ban_ip', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			
			if ($customer) {
	$sale[] = array(
					'name'	   => $this->language->get('text_customer'),
					'href'     => '',
					'children' => $customer		
				);		
}


if ($sale) {
				$data['menus'][] = array(
					'id'       => 'menu-sale',
					'icon'	   => 'fa-shopping-cart', 
					'name'	   => $this->language->get('text_sale'),
					'href'     => '',
					'children' => $sale
				);
			}
			
			// Marketing
			$marketing = array();
			
			if ($this->user->hasPermission('access', 'marketing/marketing')) {
				$marketing[] = array(
					'name'	   => $this->language->get('text_marketing'),
					'href'     => $this->url->link('marketing/marketing', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'marketing/affiliate')) {
				$marketing[] = array(
					'name'	   => $this->language->get('text_affiliate'),
					'href'     => $this->url->link('marketing/affiliate', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'marketing/coupon')) {	
				$marketing[] = array(
					'name'	   => $this->language->get('text_coupon'),
					'href'     => $this->url->link('marketing/coupon', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'marketing/contact')) {
				$marketing[] = array(
					'name'	   => $this->language->get('text_contact'),
					'href'     => $this->url->link('marketing/contact', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($marketing) {
				$data['menus'][] = array(
					'id'       => 'menu-marketing',
					'icon'	   => 'fa-share-alt', 
					'name'	   => $this->language->get('text_marketing'),
					'href'     => '',
					'children' => $marketing
				);	
			}
*/			
//******************************** UNNATI TAB ENDS HERE **************************************//
			// System
			$system = array();
			
			if ($this->user->hasPermission('access', 'setting/setting')) {
				$system[] = array(
					'name'	   => $this->language->get('text_setting'),
					'href'     => $this->url->link('setting/store', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
		

			// Users
			$user = array();
			
			if ($this->user->hasPermission('access', 'user/user')) {
				$user[] = array(
					'name'	   => $this->language->get('text_users'),
					'href'     => $this->url->link('user/user', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'user/user_permission')) {	
				$user[] = array(
					'name'	   => $this->language->get('text_user_group'),
					'href'     => $this->url->link('user/user_permission', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'user/api')) {		
				$user[] = array(
					'name'	   => $this->language->get('text_api'),
					'href'     => $this->url->link('user/api', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($user) {
				$system[] = array(
					'name'	   => $this->language->get('text_users'),
					'href'     => '',
					'children' => $user		
				);
			}
// Design
			$design = array();
			
			if ($this->user->hasPermission('access', 'design/layout')) {
				$design[] = array(
					'name'	   => $this->language->get('text_layout'),
					'href'     => $this->url->link('design/layout', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			/*
			if ($this->user->hasPermission('access', 'design/menu')) {
				$design[] = array(
					'name'	   => $this->language->get('text_menu'),
					'href'     => $this->url->link('design/menu', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			*/	
			/*	
			if ($this->user->hasPermission('access', 'design/theme')) {	
				$design[] = array(
					'name'	   => $this->language->get('text_theme'),
					'href'     => $this->url->link('design/theme', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'design/language')) {
				$design[] = array(
					'name'	   => $this->language->get('text_translation'),
					'href'     => $this->url->link('design/language', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			*/	
			if ($this->user->hasPermission('access', 'design/banner')) {
				$design[] = array(
					'name'	   => $this->language->get('text_banner'),
					'href'     => $this->url->link('design/banner', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			

						if ($design) {																
				$system[] = array(
					'name'	   => $this->language->get('text_design'),
					'href'     => '',
					'children' => $design
				);
			}

			// Localisation
			$localisation = array();
			
			if ($this->user->hasPermission('access', 'localisation/location')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_location'),
					'href'     => $this->url->link('localisation/location', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'localisation/language')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_language'),
					'href'     => $this->url->link('localisation/language', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/currency')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_currency'),
					'href'     => $this->url->link('localisation/currency', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/stock_status')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_stock_status'),
					'href'     => $this->url->link('localisation/stock_status', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/order_status')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_order_status'),
					'href'     => $this->url->link('localisation/order_status', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			// Returns
			$return = array();
			
			if ($this->user->hasPermission('access', 'localisation/return_status')) {
				$return[] = array(
					'name'	   => $this->language->get('text_return_status'),
					'href'     => $this->url->link('localisation/return_status', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/return_action')) {
				$return[] = array(
					'name'	   => $this->language->get('text_return_action'),
					'href'     => $this->url->link('localisation/return_action', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);		
			}
			
			if ($this->user->hasPermission('access', 'localisation/return_reason')) {
				$return[] = array(
					'name'	   => $this->language->get('text_return_reason'),
					'href'     => $this->url->link('localisation/return_reason', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($return) {	
				$localisation[] = array(
					'name'	   => $this->language->get('text_return'),
					'href'     => '',
					'children' => $return		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/country')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_country'),
					'href'     => $this->url->link('localisation/country', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/zone')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_zone'),
					'href'     => $this->url->link('localisation/zone', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/geo_zone')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_geo_zone'),
					'href'     => $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			// Tax		
			$tax = array();
			
			if ($this->user->hasPermission('access', 'localisation/tax_class')) {
				$tax[] = array(
					'name'	   => $this->language->get('text_tax_class'),
					'href'     => $this->url->link('localisation/tax_class', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/tax_rate')) {
				$tax[] = array(
					'name'	   => $this->language->get('text_tax_rate'),
					'href'     => $this->url->link('localisation/tax_rate', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($tax) {	
				$localisation[] = array(
					'name'	   => $this->language->get('text_tax'),
					'href'     => '',
					'children' => $tax		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/length_class')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_length_class'),
					'href'     => $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/weight_class')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_weight_class'),
					'href'     => $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($localisation) {																
				$system[] = array(
					'name'	   => $this->language->get('text_localisation'),
					'href'     => '',
					'children' => $localisation	
				);
			}
			
			// Tools	
			$tool = array();
			
			if ($this->user->hasPermission('access', 'tool/upload')) {
				$tool[] = array(
					'name'	   => $this->language->get('text_upload'),
					'href'     => $this->url->link('tool/upload', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'tool/backup')) {
				$tool[] = array(
					'name'	   => $this->language->get('text_backup'),
					'href'     => $this->url->link('tool/backup', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'tool/error_log')) {
				$tool[] = array(
					'name'	   => $this->language->get('text_error_log'),
					'href'     => $this->url->link('tool/error_log', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($tool) {
				$system[] = array(
					'name'	   => $this->language->get('text_tools'),
					'href'     => '',
					'children' => $tool	
				);
			}
			
			if ($system) {
				$data['menus'][] = array(
					'id'       => 'menu-system',
					'icon'	   => 'fa-cog', 
					'name'	   => $this->language->get('text_system'),
					'href'     => '',
					'children' => $system
				);
			}
			
		// Report
			$report = array();
			
			// Report Sales
			$report_sale = array();	

	               if ($this->user->hasPermission('access', 'report/sale_summary')) {
				$report_sale[] = array(
					'name'	   => 'Sale Summary',
					'href'     => $this->url->link('report/sale_summary', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
               if ($this->user->hasPermission('access', 'tag/sale_summary')) {
				$report_sale[] = array(
					'name'	   => 'Tagged Summary ',
					'href'     => $this->url->link('tag/sale_summary', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
					
			
			if ($this->user->hasPermission('access', 'report/sale_order')) {
				$report_sale[] = array(
					'name'	   => $this->language->get('text_report_sale_order'),
					'href'     => $this->url->link('report/sale_order', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}

			
			if ($this->user->hasPermission('access', 'tag/order')) {
				$report_sale[] = array(
					'name'	   => 'Tagged Report',
					'href'     => $this->url->link('tag/order', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
                        if ($this->user->hasPermission('access', 'report/reconciliation')) {
				$report_sale[] = array(
					'name'	   => 'Reconciliation Report',
					'href'     => $this->url->link('report/reconciliation', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'report/sale_tax')) {
				$report_sale[] = array(
					'name'	   => $this->language->get('text_report_sale_tax'),
					'href'     => $this->url->link('report/sale_tax', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			
			if ($this->user->hasPermission('access', 'report/sale_shipping')) {
				$report_sale[] = array(
					'name'	   => $this->language->get('text_report_sale_shipping'),
					'href'     => $this->url->link('report/sale_shipping', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			
			if ($this->user->hasPermission('access', 'report/sale_return')) {	
				$report_sale[] = array(
					'name'	   => $this->language->get('text_report_sale_return'),
					'href'     => $this->url->link('report/sale_return', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);	
			}
			
			if ($this->user->hasPermission('access', 'report/sale_coupon')) {		
				$report_sale[] = array(
					'name'	   => $this->language->get('text_report_sale_coupon'),
					'href'     => $this->url->link('report/sale_coupon', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($report_sale) {
				$report[] = array(
					'name'	   => $this->language->get('text_sale'),
					'href'     => '',
					'children' => $report_sale
				);			
			}
			
			// Report Products			
			$report_product = array();	
			
			if ($this->user->hasPermission('access', 'report/product_viewed')) {
				$report_product[] = array(
					'name'	   => $this->language->get('text_report_product_viewed'),
					'href'     => $this->url->link('report/product_viewed', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			
			if ($this->user->hasPermission('access', 'report/product_purchased')) {
				$report_product[] = array(
					'name'	   => $this->language->get('text_report_product_purchased'),
					'href'     => $this->url->link('report/product_purchased', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}

			if ($this->user->hasPermission('access', 'report/product_sales')) {
				$report_product[] = array(
					'name'	   => 'Product Wise Sales',
					'href'     => $this->url->link('report/product_sales', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
                        if ($this->user->hasPermission('access', 'report/product_storewisesales')) {
				$report_product[] = array(
					'name'	   => 'Product  Sales (Store Wise)',
					'href'     => $this->url->link('report/product_storewisesales', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}

			
			if ($report_product) {	
				$report[] = array(
					'name'	   => $this->language->get('text_product'),
					'href'     => '',
					'children' => $report_product	
				);		
			}
			
			// Report Customers				
			$report_customer = array();
			
			if ($this->user->hasPermission('access', 'report/customer_online')) {	
				$report_customer[] = array(
					'name'	   => $this->language->get('text_report_customer_online'),
					'href'     => $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'report/customer_activity')) {
				$report_customer[] = array(
					'name'	   => $this->language->get('text_report_customer_activity'),
					'href'     => $this->url->link('report/customer_activity', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'report/customer_search')) {
				$report_customer[] = array(
					'name'	   => $this->language->get('text_report_customer_search'),
					'href'     => $this->url->link('report/customer_search', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'report/customer_order')) {	
				$report_customer[] = array(
					'name'	   => $this->language->get('text_report_customer_order'),
					'href'     => $this->url->link('report/customer_order', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'report/customer_order_report')) {	
				$report_customer[] = array(
					'name'	   => 'Customer Orders',
					'href'     => $this->url->link('report/customer_order_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}


			
			if ($this->user->hasPermission('access', 'report/customer_reward')) {
				$report_customer[] = array(
					'name'	   => $this->language->get('text_report_customer_reward'),
					'href'     => $this->url->link('report/customer_reward', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'report/customer_credit')) {
				$report_customer[] = array(
					'name'	   => $this->language->get('text_report_customer_credit'),
					'href'     => $this->url->link('report/customer_credit', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($report_customer) {	
				$report[] = array(
					'name'	   => $this->language->get('text_customer'),
					'href'     => '',
					'children' => $report_customer	
				);
			}
			
			// Report Marketing			
			$report_marketing = array();			
			
			if ($this->user->hasPermission('access', 'report/marketing')) {
				$report_marketing[] = array(
					'name'	   => $this->language->get('text_marketing'),
					'href'     => $this->url->link('report/marketing', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'report/affiliate')) {
				$report_marketing[] = array(
					'name'	   => $this->language->get('text_report_affiliate'),
					'href'     => $this->url->link('report/affiliate', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);		
			}
			
			if ($this->user->hasPermission('access', 'report/affiliate_activity')) {
				$report_marketing[] = array(
					'name'	   => $this->language->get('text_report_affiliate_activity'),
					'href'     => $this->url->link('report/affiliate_activity', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);		
			}
			
			if ($report_marketing) {	
				$report[] = array(
					'name'	   => $this->language->get('text_marketing'),
					'href'     => '',
					'children' => $report_marketing	
				);		
			}
			
						

		//cash report
					$cash_report = array();
			if ($this->user->hasPermission('access', 'report/cash_report')) {
				$cash_report[] = array(
					'name'	   => 'Cash Report',
					'href'     => $this->url->link('report/cash_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'cash/verify')) {
				$cash_report[] = array(
					'name'	   => 'Cash Verify',
					'href'     => $this->url->link('cash/verify/verify', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($cash_report) {	
				$report[] = array(
					'name'	   => 'Cash',
					'href'     => '',
					'children' => $cash_report
				);		
			}
			
		//inventory report
					$inv_report = array();
			if ($this->user->hasPermission('access', 'report/inventory_report')) {
				$inv_report[] = array(
					'name'	   => 'Inventory Report',
					'href'     => $this->url->link('report/inventory_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
                        if ($this->user->hasPermission('access', 'report/inventory_report')) {
				$inv_report[] = array(
					'name'	   => 'Inventory Report (Product Wise)',
					'href'     => $this->url->link('report/inventory_report/product_wise', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($inv_report) {	
				$report[] = array(
					'name'	   => 'Inventory',
					'href'     => '',
					'children' => $inv_report
				);		
			}			


			if ($report) {	
				$data['menus'][] = array(
					'id'       => 'menu-report',
					'icon'	   => 'fa-bar-chart-o', 
					'name'	   => $this->language->get('text_reports'),
					'href'     => '',
					'children' => $report
				);	
			}	

			// Stats
			$data['text_complete_status'] = $this->language->get('text_complete_status');
			$data['text_processing_status'] = $this->language->get('text_processing_status');
			$data['text_other_status'] = $this->language->get('text_other_status');
	
			$this->load->model('sale/order');
	
			$order_total = $this->model_sale_order->getTotalOrders();
	
			$complete_total = $this->model_sale_order->getTotalOrders(array('filter_order_status' => implode(',', $this->config->get('config_complete_status'))));
			
			if ($complete_total) {
				$data['complete_status'] = round(($complete_total / $order_total) * 100);
			} else {
				$data['complete_status'] = 0;
			}
	
			$processing_total = $this->model_sale_order->getTotalOrders(array('filter_order_status' => implode(',', $this->config->get('config_processing_status'))));
	
			if ($processing_total) {
				$data['processing_status'] = round(($processing_total / $order_total) * 100);
			} else {
				$data['processing_status'] = 0;
			}
	
			$this->load->model('localisation/order_status');
	
			$order_status_data = array();
	
			$results = $this->model_localisation_order_status->getOrderStatuses();
	
			foreach ($results as $result) {
				if (!in_array($result['order_status_id'], array_merge($this->config->get('config_complete_status'), $this->config->get('config_processing_status')))) {
					$order_status_data[] = $result['order_status_id'];
				}
			}
	
			$other_total = $this->model_sale_order->getTotalOrders(array('filter_order_status' => implode(',', $order_status_data)));
	
			if ($other_total) {
				$data['other_status'] = round(($other_total / $order_total) * 100);
			} else {
				$data['other_status'] = 0;
			}
			
		return $this->load->view('common/menu.tpl', $data);
	}
}
