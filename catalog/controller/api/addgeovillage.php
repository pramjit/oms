<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of createVillage
 *
 * @author agent
 */
class Controllergeoaddgeovillage extends Controller {
    public function  index(){
        
        $this->load->language('geo/addgeo');

	$this->document->setTitle($this->language->get('heading_title'));

	$this->load->model('geo/addgeovillage');
        
      
        //drop down
            $data['user_group_id']="-1";
            $data['dpnation']= $this->model_geo_addgeovillage->getState();
           
            
            $data['dpzone']= $this->model_geo_addgeovillage->getZone();
            $data['dpregion']= $this->model_geo_addgeovillage->getRegion();
            $data['dpstate']= $this->model_geo_addgeovillage->getState();
            $data['dparea']= $this->model_geo_addgeovillage->getArea();
            $data['dpterritory']= $this->model_geo_addgeovillage->getTerritory();
            $data['dp_district_state']= $this->model_geo_addgeovillage->get_District_State();
        //end drop down
                
                
       $data['heading_title'] = $this->language->get('heading_title');
       $data['tab_nation']=$this->language->get('text_nation');
       $data['tab_zone']=$this->language->get('text_zone');
       $data['tab_region']=$this->language->get('text_region');
       $data['tab_village']=$this->language->get('text_village');
       $data['tab_tehsil']=$this->language->get('text_tehsil');
       //$data['tab_state']=$this->language->get('text_state');
       $data['tab_block']=$this->language->get('text_block');
       $data['tab_district']=$this->language->get('text_district');
                
                
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
                
                $data['addgeo'] = $this->url->link('geo/addgeovillage/addNation', 'token=' . $this->session->data['token'], 'SSL');
                
                $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('geo/addgeo', 'token=' . $this->session->data['token'], 'SSL')
		);
                $data['button_save'] = $this->language->get('button_save');
		$data['button_back'] = $this->language->get('button_back');
                $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                $this->response->setOutput($this->load->view('geo/addgeovillage.tpl', $data));
    }
    
      public function  addNation(){
            $this->load->language('geo/addgeo');
          $json = array();
          $this->load->model('geo/addgeovillage');
           
                        if (isset($this->request->post['select_tehsil_state'])&&($this->request->post['select_district_territory'])&&($this->request->post['tehsil_name']!="")) {
                         $retval= $this->model_geo_addgeovillage->addtehsil($this->request->post); 
                        
                         if($retval>1)
                         {
                         
                         $this->session->data['success'] = $this->language->get('text_success');
                         }else{
                             $this->session->data['error']=$this->language->get('text_check_nation');
                         }
                         
                        }
                        if (isset($this->request->post['select_block_state'])&&($this->request->post['select_district_territory_block'])&&($this->request->post['Block_name']!=""))
                        {
                           $retval= $this->model_geo_addgeovillage->addblock($this->request->post); 
                           if($retval>1)
                         {
                         
                         $this->session->data['success'] = $this->language->get('text_success');
                         }else{
                             $this->session->data['error']=$this->language->get('text_check_state');
                         }
                         
                        }
                        
                        if (isset($this->request->post['select_village_state'])&&($this->request->post['select_district_territory_village'])&&($this->request->post['select_tehsil'])&&($this->request->post['village_name']!=""))
                        {
                             $retval= $this->model_geo_addgeovillage->addvillage($this->request->post); 
                            if($retval>1)
                         {
                         
                         $this->session->data['success'] = $this->language->get('text_success');
                         }else{
                             $this->session->data['error']=$this->language->get('text_error');
                         }
                        
                        }
                        
                        //print_r($this->request->post['state_name']);
                        if (isset($this->request->post['state_name'])&&($this->request->post['state_name']!=""))
                        {
                             $retval= $this->model_geo_addgeovillage->addState($this->request->post); 
                            if($retval>1)
                         {
                         
                         $this->session->data['success'] = $this->language->get('text_success');
                         }else{
                             $this->session->data['error']=$this->language->get('text_check_state');
                         }
                        
                        } 
                        
                        
                         if (isset($this->request->post['hq_name'])&&($this->request->post['hq_name']!=""))
                        {
                             $retval= $this->model_geo_addgeovillage->addHQ($this->request->post); 
                            if($retval>1)
                         {
                         
                         $this->session->data['success'] = $this->language->get('text_success');
                         }else{
                             $this->session->data['error']=$this->language->get('text_check_hq');
                         }
                        
                        }
                        
                        
                          if (isset($this->request->post['territory_name'])&&($this->request->post['territory_name']!=""))
                        {
                             $retval= $this->model_geo_addgeovillage->addTerritory($this->request->post); 
                            if($retval>1)
                         {
                         
                         $this->session->data['success'] = $this->language->get('text_success');
                         }else{
                             $this->session->data['error']=$this->language->get('text_check_territory');
                         }
                        
                        }
                        
                       if (isset($this->request->post['district_name'])&&($this->request->post['district_name']!=""))
                        {
                             $retval= $this->model_geo_addgeovillage->addDistrict($this->request->post); 
                            if($retval>1)
                         {
                         
                         $this->session->data['success'] = $this->language->get('text_success');
                         }else{
                             $this->session->data['error']=$this->language->get('text_check_district');
                         }
                        
                        }
                        
                        
                        else{
                        $json['error']['warning'] ="Error";
                            
                        }
                        $this->response->redirect($this->url->link('geo/addgeovillage', 'token=' . $this->session->data['token'], 'SSL'));
                        
                      
      }
      
      public function checknation(){
           $this->load->model('geo/addgeo');
         $nation=$this->request->post["nation"];
         echo $nation;
      }

      

      public function getterritorystate(){


        $this->load->model('geo/addgeovillage');


        if (isset($this->request->post['nation'])) {

        $data['dp_zone']= $this->model_geo_addgeovillage->getterritorystate($this->request->post); 

        $dpzone= count($data['dp_zone']);
        echo ' <option value=""> Select State</option> ';
        for($n=0;$n<$dpzone;$n++)
        {
        echo '<option value="'.$data['dp_zone'][$n]['SID'].'">'.$data['dp_zone'][$n]['NAME'].'</option>';
        }

       } 
    }
    
public function getdistrict(){
    
    
     $this->load->model('geo/addgeovillage');


        if (isset($this->request->post['stateid'])) {

        $data['dp_zone']= $this->model_geo_addgeovillage->getdistrict($this->request->post); 
        
       

        $dpzone= count($data['dp_zone']);
        echo ' <option value=""> Select District</option> ';
        for($n=0;$n<$dpzone;$n++)
        {
        echo '<option value="'.$data['dp_zone'][$n]['SID'].'">'.$data['dp_zone'][$n]['NAME'].'</option>';
        }

       } 
    
}

public function getdistrictteri() {
    
    $this->load->model('geo/addgeovillage');


        if (isset($this->request->request['territory_id'])) {

        $data['dp_zone']= $this->model_geo_addgeovillage->getdistrictteri($this->request->request); 

        $dpzone= count($data['dp_zone']);
        echo ' <option value=""> Select District</option> ';
        for($n=0;$n<$dpzone;$n++)
        {
        echo '<option value="'.$data['dp_zone'][$n]['SID'].'">'.$data['dp_zone'][$n]['NAME'].'</option>';
        }

       } 
    
}


public function gettehsil() {
    
    $this->load->model('geo/addgeovillage');


        if (isset($this->request->post['districtid'])) {

        $data['dp_zone']= $this->model_geo_addgeovillage->gettehsil($this->request->post); 

        $dpzone= count($data['dp_zone']);
        echo ' <option value=""> Select Tehsil</option> ';
        for($n=0;$n<$dpzone;$n++)
        {
        echo '<option value="'.$data['dp_zone'][$n]['SID'].'">'.$data['dp_zone'][$n]['NAME'].'</option>';
        }

       } 
    
}

  public function gettehsilblock(){
          $this->load->model('geo/addgeovillage');
          $tehsil=$this->model_geo_addgeovillage->gettehsil($this->request->post);
           $block=$this->model_geo_addgeovillage->getblock($this->request->post);
          $a= '<option value="">Select Tehsil</option>';
          foreach($tehsil as $value) {
              $a.= '<option value="'.$value["SID"].'">'.$value["NAME"].'</option>';
          }
          $b= '<option value="">Select Block</option>';
          foreach($block as $value) {
              $b.= '<option value="'.$value["SID"].'">'.$value["NAME"].'</option>';
          }
          $c=$a.'_'.$b;
          echo $c;
          
      }

public function getblock() {
    
    $this->load->model('geo/addgeovillage');


        if (isset($this->request->post['districtid'])) {

        $data['dp_zone']= $this->model_geo_addgeovillage->getblock($this->request->post); 

        $dpzone= count($data['dp_zone']);
        echo ' <option value=""> Select Block</option> ';
        for($n=0;$n<$dpzone;$n++)
        {
        echo '<option value="'.$data['dp_zone'][$n]['SID'].'">'.$data['dp_zone'][$n]['NAME'].'</option>';
        }

       } 
    
}

public function gethq() {
    
    $this->load->model('geo/addgeo');


        if (isset($this->request->post['district_id'])) {

        $data['dp_zone']= $this->model_geo_addgeo->gethq_details($this->request->post); 
        $data['dp_retailer']= $this->model_geo_addgeo->getretailer_details($this->request->post); 
        //print_r($data['dp_zone']);   
         //print_r($data['dp_retailer']);   
        $dpzone= count($data['dp_zone']);
        $dp_zon_ar=' <option value=""> Select HQ</option> ';
        for($n=0;$n<$dpzone;$n++)
        {
        $dp_zon_ar.= '<option value="'.$data['dp_zone'][$n]['SID'].'">'.$data['dp_zone'][$n]['GEO_NAME'].'</option>';
        }
        
        $dpretailer= count($data['dp_retailer']);
        $dpretailer_ar=' <option value=""> Select Retailer</option> ';
        for($n=0;$n<$dpretailer;$n++)
        {
        $dpretailer_ar.= '<option value="'.$data['dp_retailer'][$n]['SID'].'">'.$data['dp_retailer'][$n]['OUTLET_NAME'].'</option>';
        }
echo $dp_zon_ar."|".$dpretailer_ar;
       } 
    
}
      
      
     
    
}
