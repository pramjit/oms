<?php
class ControllerApiupload extends Controller {


 public function adminmodel($model) {
     
      $admin_dir = DIR_SYSTEM;
      $admin_dir = str_replace('system/','admin/',$admin_dir);
      $file = $admin_dir . 'model/' . $model . '.php';     
      //$file  = DIR_APPLICATION . 'model/' . $model . '.php';
      $class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
     
      if (file_exists($file)) {
         include_once($file);
        
         $this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
      } else {
         trigger_error('Error: Could not load model ' . $model . '!');
         exit();              
      }
   }

public function upload()
{

//upload file
            $log=new Log("upload.log");
             $log->write($this->request->post);
             //log to table
       
  
                $this->load->model('account/activity');

                $activity_data = $this->request->post;

                $this->model_account_activity->addActivity('upload', $activity_data);
           
      
        //
           
        $this->load->language('api/upload');

        $json = array();



        if (!$json) {
            if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
                // Sanitize the filename
                $filename = html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8');

                if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
                    $json['error'] = $this->language->get('error_filename');
                }

                // Allowed file extension types
                $allowed = array();

                $extension_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_ext_allowed'));

                $filetypes = explode("\n", $extension_allowed);

                foreach ($filetypes as $filetype) {
                    $allowed[] = trim($filetype);
                }

                if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                // Allowed file mime types
                $allowed = array();

                $mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));

                $filetypes = explode("\n", $mime_allowed);

                foreach ($filetypes as $filetype) {
                    $allowed[] = trim($filetype);
                }

                if (!in_array($this->request->files['file']['type'], $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                // Check to see if any PHP files are trying to be uploaded
                $content = file_get_contents($this->request->files['file']['tmp_name']);

                if (preg_match('/\<\?php/i', $content)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                // Return any upload error
                if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
                    $json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
                }
            } else {
                $json['error'] = $this->language->get('error_upload');
            }
        }

        if (!$json) {
            $file = $filename . '.' . md5(mt_rand());

            move_uploaded_file($this->request->files['file']['tmp_name'], DIR_UPLOAD . $file);

            // Hide the uploaded file name so people can not link to it directly.
                      
            $json['success'] = $this->language->get('text_upload');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
   
       
//

}

public function setgeo()
{
        //name,address,geocode,telephone,fax,image,open,comment
        $log=new Log("geo.log");
        $mcrypt=new MCrypt();
        $keys = array(
            'username',
            'geocode',
            'store_id',
            'name',
            'address'
        );
        $log->write("in geo");
        $log->write($this->request->post);
        foreach ($keys as $key) {           
                    $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;           
            }
        $this->request->post['fax']=$this->request->post['store_id'];
        $this->load->language('localisation/location');
        $this->adminmodel('localisation/location');
        $this->model_localisation_location->addLocation($this->request->post);         
        $this->adminmodel('setting/setting');
        $this->model_setting_setting->editSettingValue('config','config_geocode',$this->request->post['geocode'],$this->request->post['store_id']);
        //also update geocode in setting table as per store
        $json['success'] = $this->language->get('text_success');
        $this->response->setOutput(json_encode($json));

}


public function setank()
{

$log=new Log("bnk.log");
        $mcrypt=new MCrypt();
        $log->write($this->request->post);

                     $this->load->model('account/customer');      
                        $data=array();
            $data['bank_id']=$mcrypt->decrypt($this->request->post['bid']);
            $data['bank_name']=$mcrypt->decrypt($this->request->post['bname']);
            $data['amount']=$mcrypt->decrypt($this->request->post['bamt']);
            $data['user_id']=$mcrypt->decrypt($this->request->post['username']);
            $data['store_id']=$mcrypt->decrypt($this->request->post['store_id']);
            if(!empty($data['amount']) && !empty($data['store_id'])){
            $jsons = $this->model_account_customer->addbankTrans($data);
            $log->write($jsons);
            $json['success'] = 'Success: Transaction added.';
            }
            else{
                if(empty($data['amount'])){
                $json['success'] = 'Error: Amount can not be zero.';
                }
                if(empty($data['store_id'])){
                $json['success'] = 'Error: You are not authorized.';
                }

                }
        $this->response->setOutput(json_encode($json));

}

public function getank()
{

        $mcrypt=new MCrypt();

             $this->load->model('account/customer');      
                   
            $jsons = $this->model_account_customer->getank();

foreach ($jsons as $ids) {       
$json['crops'][] = array(
                        'id' => $mcrypt->encrypt($ids['bank_id']),
                        'name'       =>$mcrypt->encrypt($ids['bank']),
            'aname' =>$mcrypt->encrypt($ids['bank_account_name']),
            'anum' => $mcrypt->encrypt($ids['bank_account_number']),
            'atype' => $mcrypt->encrypt($ids['bank_account_type']),
            'acode' => $mcrypt->encrypt($ids['bank_ifsc_code']),
            'abranch' => $mcrypt->encrypt($ids['bank_branch'])

                                            );
}

$this->response->setOutput(json_encode($json));



}

public function getanktrans()
{

        $mcrypt=new MCrypt();
$log=new Log("bnk.log");
$log->write($this->request->post);
             $this->load->model('account/customer');      
                   
            $uid=$mcrypt->decrypt($this->request->post['username']);
            $sid=$mcrypt->decrypt($this->request->post['store_id']);
            $jsons = $this->model_account_customer->getanktrans($uid,$sid);
$log->write($jsons);

foreach ($jsons as $ids) {       
$json['crops'][] = array(
                        'name'       =>$mcrypt->encrypt($ids['name']),
                        'aname'       =>$mcrypt->encrypt($ids['bank_name']),
            'pirce' =>$mcrypt->encrypt($ids['amount']),
            'date_added' => $mcrypt->encrypt($ids['date_added']),
                       
                                            );
}

$this->response->setOutput(json_encode($json));



}




public function addaff()
{

$log=new Log("addaff.log");
        $mcrypt=new MCrypt();
$log->write($this->request->post);

        $keys = array(
            'username',
            'firstname',
    'lastname',
    'payment',
    'telephone',
    'city',
    'postcode',
    'code',
    'bank_name',
'bank_branch_number',
'bank_swift_code',
'bank_account_name',
'bank_account_number',
'rabi_crop_1',
'rabi_crop_2',
'kharif_crop_1',
'kharif_crop_2',
'kharif_crop_1_acre',
'kharif_crop_2_acre',
'rabi_crop_1_acre',
'rabi_crop_2_acre',
'Address',
'email',
'fax',
'store_id'

        );
foreach ($keys as $key) {
           

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
           
        }
$log->write($this->request->post);


        $this->load->language('marketing/affiliate');
              $json = array();
            
        $this->request->post['password']="ufc@unnati";
        $this->request->post['confirm']="ufc@unnati";                
            
$json['error']="";

//
if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32))
{
               $json['error']  = $this->language->get('error_firstname');
        }

        if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
             $json['error']  = $this->language->get('error_lastname');
        }

        /*if ((utf8_strlen($this->request->post['email']) > 96) || (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email']))) {
             $json['error']  = $this->language->get('error_email');
        }*/

        if ($this->request->post['payment'] == 'cheque') {
            if ($this->request->post['cheque'] == '') {
                 $json['error']  = $this->language->get('error_cheque');
            }
        } elseif ($this->request->post['payment'] == 'paypal') {
            if ((utf8_strlen($this->request->post['paypal']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['paypal'])) {
                $this->error['paypal'] = $this->language->get('error_paypal');
            }
        } elseif ($this->request->post['payment'] == 'bank') {
            if ($this->request->post['bank_account_name'] == '') {
                 $json['error']  = $this->language->get('error_bank_account_name');
            }

            if ($this->request->post['bank_account_number'] == '') {
                 $json['error']  = $this->language->get('error_bank_account_number');
            }
        }
$this->request->post['address_1']=$this->request->post['Address'];
    $this->adminmodel('marketing/affiliate');

$log->write("out");
        $affiliate_info = $this->model_marketing_affiliate->getAffiliateByTelephone($this->request->post['telephone']);
$log->write("out1");
        if (!isset($this->request->get['affiliate_id'])) {
            if ($affiliate_info) {
                 $json['error']  = $this->language->get('error_exists');
            }
        } else {
            if ($affiliate_info && ($this->request->get['affiliate_id'] != $affiliate_info['affiliate_id'])) {
                 $json['error']  = $this->language->get('error_exists');
            }
        }

        if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
             $json['error']  = $this->language->get('error_telephone');
        }

        if ($this->request->post['password'] || (!isset($this->request->get['affiliate_id']))) {
            if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
                 $json['error']  = $this->language->get('error_password');
            }

            if ($this->request->post['password'] != $this->request->post['confirm']) {
                 $json['error']  = $this->language->get('error_confirm');
            }
        }

        if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
             $json['error']  = $this->language->get('error_address_1');
        }

        if ((utf8_strlen(trim($this->request->post['city'])) < 2) || (utf8_strlen(trim($this->request->post['city'])) > 128)) {
             $json['error']  = $this->language->get('error_city');
        }
$log->write("out3");
        $this->adminmodel('localisation/country');
$log->write("out4");
$this->request->post['country_id']="99";
        $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

        if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
             $json['error']  = $this->language->get('error_postcode');
        }

        if ($this->request->post['country_id'] == '') {
             $json['error']  = $this->language->get('error_country');
        }
        $this->request->post['zone_id']="1";
        if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
             $json['error']  = $this->language->get('error_zone');
        }

        if (!$this->request->post['code']) {
             $json['error']  = $this->language->get('error_code');
        }

    $this->request->post['user_store_id']=$this->request->post['store_id'];
//

$log->write("out");
$log->write($json['error']);

    if( $json['error']=="")   
{

$log->write("in");


            $this->model_marketing_affiliate->addAffiliate($this->request->post);
        $json['success']="Success: UFC added.";
}
             $this->response->setOutput(json_encode($json));


}


public function getaff()
{
$log=new Log("viewaff.log");
        $mcrypt=new MCrypt();
$log->write($this->request->post);

        $this->load->language('marketing/affiliate');
$log->write("after language");
$this->adminmodel('marketing/affiliate');
$data['products'] = array();




        $filter_data = array(
            'filter_store'   => $mcrypt->decrypt($this->request->post["store_id"]),
            'filter_user_id' => $mcrypt->decrypt($this->request->post["username"]),
            'filter_name'       => $filter_name,
            'filter_email'      => $filter_email,
            'filter_status'     => $filter_status,
            'filter_approved'   => $filter_approved,
            'filter_date_added' => $filter_date_added,
            'sort'              => $sort,
            'order'             => $order,
            'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'             => $this->config->get('config_limit_admin')
        );

        $results = $this->model_marketing_affiliate->getAffiliates($filter_data);

        foreach ($results as $result) {
                                           
            $data['products'][] = array(
                'id' => $mcrypt->encrypt($result['affiliate_id']),
                'name'         => $mcrypt->encrypt($result['firstname']),
                'acode'        =>$mcrypt->encrypt($result['code']),
                'telephone'        => $mcrypt->encrypt($result['telephone']),
                'abranch'        => $mcrypt->encrypt($result['city']),
                'balance'      => $mcrypt->encrypt($this->currency->format($result['balance'], $this->config->get('config_currency'))),
                'status'       =>$mcrypt->encrypt( ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'))),
                'date_added'   =>$mcrypt->encrypt( date($this->language->get('date_format_short'), strtotime($result['date_added']))),
                            );
        }
             $this->response->setOutput(json_encode($data));



}




public function getCrops(){

        $mcrypt=new MCrypt();

             $this->load->model('account/customer');      
                   
            $jsons = $this->model_account_customer->getCrops();

foreach ($jsons as $ids) {       
$json['crops'][] = array(
                        'id' => $mcrypt->encrypt($ids['id']),
                        'name'       =>$mcrypt->encrypt($ids['name']),
                                            );
}
             $this->response->setOutput(json_encode($json));
}

public function addcustomer(){
            
        $mcrypt=new MCrypt();
$log=new Log("addcust.log");

$log->write($this->request->post);
         


             //
              $json = array();
            
             if($this->request->post['firstname']==''){
                 $json['error'] = 'Error: Please firstname name.';

             }
             /*if($this->request->post['lastname']==''){
                 $json['error'] = 'Error: Please enter lastname name.';
$this->response->setOutput( json_encode($json));
                 die();
             }*/
             if($this->request->post['telephone']==''){
                 $json['error'] = 'Error: Please enter telephone name.';

             }

             if($this->request->post['card']=='')
                 {

                     $json['error'] = 'Error: Please enter card number.';

             }
             if($this->request->post['village']=='')
                 {
                 $json['error'] = 'Error: Please enter village name.';

             }
        if($this->request->post['pincode']=='')
                 {
                 $json['error'] = 'Error: Please enter pincode name.';

             }
             $log->write("check");
             //check mobilenummber exits

$keys = array(
            'username',
            'store_id',
            'telephone',
            'village',
            'pincode',
            'firstname',
            'card',
            'crop1',
            'crop2',
            'acre1',
            'acre2'   
        );


$log->write($this->request->post);
foreach ($keys as $key) {
           

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
           
        }




             $this->adminmodel('sale/customer');      
                 if (isset($this->request->post['telephone']) )
                    {
            $customer_info = $this->model_sale_customer->getCustomerByEmail($this->request->post['telephone']);
        }
$log->write($customer_info);
            
             if (empty($customer_info)){
             $log->write("check if");
                 unset($this->session->data['cid']);
             $this->request->post['email']=($this->request->post['telephone']);
             $this->request->post['fax']=($this->request->post['telephone']);
             $this->request->post['password']=($this->request->post['telephone']);
         $this->request->post['customer_group_id']="1";
             $this->request->post['newsletter']='0';       
             $this->request->post['approved']='1';
             $this->request->post['status']='1';
             $this->request->post['safe']='1';
              $this->request->post['address_1']= ($this->request->post['village']);
                 $this->request->post['address_2']= ($this->request->post['village']);
                 $this->request->post['city']= ($this->request->post['village']);
                 $this->request->post['company']='Unnati';
             $this->request->post['country_id']='0';
             $this->request->post['zone_id']='0';
             $this->request->post['postcode']='0';
             $this->request->post['store_id']=$this->request->post['store_id'];            
             $this->request->post['address']=array($this->request->post);
             $this->model_sale_customer->addCustomer($this->request->post);
            
             if(isset($this->session->data['cid']))
             {
                 $json['id']=$this->session->data['cid'];
             }
             }else{
                 $json['error'] = 'Error: customer already exists with this telephone.';
             }
             //html update             
            
            
             $json['success'] = 'Success: customer added.';
            
             $this->response->setOutput(json_encode($json));
             //                                      
        }



        public function Customer(){
$log=new Log("cust.log");

$log->write($this->request->get);
        $mcrypt=new MCrypt();
            $this->adminmodel('pos/pos');
            $q =$mcrypt->decrypt($this->request->get['q']);
            $json = $this->model_pos_pos->searchCustomer($q);
        $njson['api_ids'] = array();
foreach ($json as $ids) {

        $jsons = $this->model_pos_pos->getCustomer($ids['customer_id']);
                    $njson['api_ids'][] = array(
                        'api_id' => $mcrypt->encrypt($jsons['customer_id']),
                        'api_name'       =>$mcrypt->encrypt($jsons['firstname']." ".$jsons['lastname']),
                        'api_cash'        =>$mcrypt->encrypt($jsons['telephone']),
                    );
        }

       


       
            return $this->response->setOutput(json_encode($njson));
        }



        public function getcustomer(){
$log=new Log("cust.log");

$log->write($this->request->post);
        $mcrypt=new MCrypt();
            $this->adminmodel('pos/pos');
            $this->load->model('account/customer');
            $sid =$mcrypt->decrypt($this->request->post['store_id']);
            $uid =$mcrypt->decrypt($this->request->post['username']);



        $njson['products'] = array();

if(isset($this->request->get['q'])){


            $q =$mcrypt->decrypt($this->request->get['q']);
            $json = $this->model_pos_pos->searchCustomer($q);
        $njson['api_ids'] = array();
foreach ($json as $ids) {

        $jsons = $this->model_pos_pos->getCustomer($ids['customer_id']);
                    $njson['products'][] = array(
                        'id' => $mcrypt->encrypt($jsons['customer_id']),
                        'name'       =>$mcrypt->encrypt($jsons['firstname']." ".$jsons['lastname']),
                        'telephone'        =>$mcrypt->encrypt($jsons['telephone']),
            'date_added' =>$mcrypt->encrypt($this->model_account_customer->getLastOrderDate($jsons['customer_id'])["date_added"])
                    );
        }



}
else{

        $jsons = $this->model_pos_pos->getCustomers($sid,$uid);
foreach ($jsons as $ids) {
                    $njson['products'][] = array(
                        'id' => $mcrypt->encrypt($ids['customer_id']),
                        'name'       =>$mcrypt->encrypt($ids['firstname']." ".$jsons['lastname']),
                        'telephone'        =>$mcrypt->encrypt($ids['telephone']),
            'date_added' =>$mcrypt->encrypt($this->model_account_customer->getLastOrderDate($jsons['customer_id'])["date_added"])
           
                    );
        }

    }   


       
            return $this->response->setOutput(json_encode($njson));
        }






    public function index() {




        $this->load->language('api/customer');

        // Delete past customer in case there is an error
        unset($this->session->data['customer']);

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error']['warning'] = $this->language->get('error_permission');
        } else {
            // Add keys for missing post vars
            $keys = array(
                'customer_id',
                'customer_group_id',
                'firstname',
                'lastname',
                'email',
                'telephone',
                'fax'
            );

            foreach ($keys as $key) {
                if (!isset($this->request->post[$key])) {
                    $this->request->post[$key] = '';
                }
            }

            // Customer
            if ($this->request->post['customer_id']) {
                $this->load->model('account/customer');

                $customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);

                if (!$customer_info || !$this->customer->login($customer_info['email'], '', true)) {
                    $json['error']['warning'] = $this->language->get('error_customer');
                }
            }

            if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
                $json['error']['firstname'] = $this->language->get('error_firstname');
            }

            if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
                $json['error']['lastname'] = $this->language->get('error_lastname');
            }

            if ((utf8_strlen($this->request->post['email']) > 96) || (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email']))) {
                $json['error']['email'] = $this->language->get('error_email');
            }

            if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
                $json['error']['telephone'] = $this->language->get('error_telephone');
            }

            // Customer Group
            if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
                $customer_group_id = $this->request->post['customer_group_id'];
            } else {
                $customer_group_id = $this->config->get('config_customer_group_id');
            }

            // Custom field validation
            $this->load->model('account/custom_field');

            $custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

            foreach ($custom_fields as $custom_field) {
                if (($custom_field['location'] == 'account') && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
                    $json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
                }
            }

            if (!$json) {
                $this->session->data['customer'] = array(
                    'customer_id'       => $this->request->post['customer_id'],
                    'customer_group_id' => $customer_group_id,
                    'firstname'         => $this->request->post['firstname'],
                    'lastname'          => $this->request->post['lastname'],
                    'email'             => $this->request->post['email'],
                    'telephone'         => $this->request->post['telephone'],
                    'fax'               => $this->request->post['fax'],
                    'custom_field'      => isset($this->request->post['custom_field']) ? $this->request->post['custom_field'] : array()
                );

                $json['success'] = $this->language->get('text_success');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}