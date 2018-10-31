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
class Controllergeoaddgeo extends Controller {
    public function  index(){
        
        $this->load->language('geo/addgeo');

	$this->document->setTitle($this->language->get('heading_title'));

	$this->load->model('geo/addgeo');
        
      
        //drop down
            $data['user_group_id']="-1";
            $data['dpnation']= $this->model_geo_addgeo->getNations();
           
            
            $data['dpzone']= $this->model_geo_addgeo->getZone();
            $data['dpregion']= $this->model_geo_addgeo->getRegion();
            $data['dpstate']= $this->model_geo_addgeo->getState();
            $data['dparea']= $this->model_geo_addgeo->getArea();
            $data['dpterritory']= $this->model_geo_addgeo->getTerritory();
            $data['dp_district_state']= $this->model_geo_addgeo->get_District_State();
        //end drop down
                
                
       $data['heading_title'] = $this->language->get('heading_title');
       $data['tab_nation']=$this->language->get('text_nation');
       $data['tab_zone']=$this->language->get('text_zone');
       $data['tab_region']=$this->language->get('text_region');
       $data['tab_area']=$this->language->get('text_area');
       $data['tab_territory']=$this->language->get('text_territory');
       //$data['tab_state']=$this->language->get('text_state');
       $data['tab_state']=$this->language->get('text_state');
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
                
                $data['addgeo'] = $this->url->link('geo/addgeo/addNation', 'token=' . $this->session->data['token'], 'SSL');
                
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
                $this->response->setOutput($this->load->view('geo/addgeo.tpl', $data));
    }
    
      public function  addNation(){
            $this->load->language('geo/addgeo');
          $json = array();
          $this->load->model('geo/addgeo');
           
                        if (isset($this->request->post['nation_name'])&&($this->request->post['nation_name']!="")) {
                         $retval= $this->model_geo_addgeo->addNation($this->request->post); 
                        
                         if($retval>1)
                         {
                         
                         $this->session->data['success'] = $this->language->get('text_success');
                         }else{
                             $this->session->data['error']=$this->language->get('text_check_nation');
                         }
                         
                        }
                        if (isset($this->request->post['zone_name'])&&($this->request->post['zone_name']!=""))
                        {
                           $retval= $this->model_geo_addgeo->addZone($this->request->post); 
                           if($retval>1)
                         {
                         
                         $this->session->data['success'] = $this->language->get('text_success');
                         }else{
                             $this->session->data['error']=$this->language->get('text_check_state');
                         }
                         
                        }
                        
                        if (isset($this->request->post['region_name'])&&($this->request->post['region_name']!=""))
                        {
                             $retval= $this->model_geo_addgeo->addRegion($this->request->post); 
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
                             $retval= $this->model_geo_addgeo->addState($this->request->post); 
                            if($retval>1)
                         {
                         
                         $this->session->data['success'] = $this->language->get('text_success');
                         }else{
                             $this->session->data['error']=$this->language->get('text_check_state');
                         }
                        
                        } 
                        
                        
                         if (isset($this->request->post['hq_name'])&&($this->request->post['hq_name']!=""))
                        {
                             $retval= $this->model_geo_addgeo->addHQ($this->request->post); 
                            if($retval>1)
                         {
                         
                         $this->session->data['success'] = $this->language->get('text_success');
                         }else{
                             $this->session->data['error']=$this->language->get('text_check_hq');
                         }
                        
                        }
                        
                        
                          if (isset($this->request->post['territory_name'])&&($this->request->post['territory_name']!=""))
                        {
                             $retval= $this->model_geo_addgeo->addTerritory($this->request->post); 
                            if($retval>1)
                         {
                         
                         $this->session->data['success'] = $this->language->get('text_success');
                         }else{
                             $this->session->data['error']=$this->language->get('text_check_territory');
                         }
                        
                        }
                        
                       if (isset($this->request->post['district_name'])&&($this->request->post['district_name']!=""))
                        {
                             $retval= $this->model_geo_addgeo->addDistrict($this->request->post); 
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
                        $this->response->redirect($this->url->link('geo/addgeo', 'token=' . $this->session->data['token'], 'SSL'));
                        
                      
      }
      
      public function checknation(){
           $this->load->model('geo/addgeo');
         $nation=$this->request->post["nation"];
         echo $nation;
      }

      

      public function getterritorystate(){


        $this->load->model('geo/addgeo');


        if (isset($this->request->post['nation'])) {

        $data['dp_zone']= $this->model_geo_addgeo->getterritorystate($this->request->post); 

        $dpzone= count($data['dp_zone']);
        echo ' <option value=""> Select State</option> ';
        for($n=0;$n<$dpzone;$n++)
        {
        echo '<option value="'.$data['dp_zone'][$n]['SID'].'">'.$data['dp_zone'][$n]['NAME'].'</option>';
        }

       } 
    }
    
public function getdistrict_territory(){
    
    
     $this->load->model('geo/addgeo');


        if (isset($this->request->post['state_id'])) {

        $data['dp_zone']= $this->model_geo_addgeo->getdistrict_territory($this->request->post); 

        $dpzone= count($data['dp_zone']);
        echo ' <option value=""> Select Territory</option> ';
        for($n=0;$n<$dpzone;$n++)
        {
        echo '<option value="'.$data['dp_zone'][$n]['SID'].'">'.$data['dp_zone'][$n]['NAME'].'</option>';
        }

       } 
    
}

public function getTerritory_District() {
    
    $this->load->model('geo/addgeo');


        if (isset($this->request->post['territory_id'])) {

        $data['dp_zone']= $this->model_geo_addgeo->getTerritory_District($this->request->post); 

        $dpzone= count($data['dp_zone']);
        echo ' <option value=""> Select District</option> ';
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
