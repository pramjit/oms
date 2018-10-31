
 <?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        
        <button type="submit" form="form-restore" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" form="form-backup" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-exchange"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $addgeo; ?>" method="post" id="form-addgeo" class="form-horizontal" data-toggle="validator" role="form">
         
        <ul id="geo" class="nav nav-tabs nav-justified">
            <li class="active"><a href="#tab-nation" data-toggle="tab">1. <?php echo $tab_tehsil; ?></a></li>
            <li class=""><a href="#tab-region" data-toggle="tab">2. <?php echo $tab_block; ?></a></li>
            <li class=""><a href="#tab-village" data-toggle="tab">3. <?php echo $tab_village; ?></a></li>
            <!---<li class=""><a href="#tab-state" data-toggle="tab">4. <?php echo $tab_state; ?></a></li>--->
           <!-- <li class=""><a href="#tab-area" data-toggle="tab">5. <?php echo $tab_area; ?></a></li>--->
           <!-- <li class=""><a href="#tab-territory" data-toggle="tab">6. <?php echo $tab_territory; ?></a></li>
            <li class=""><a href="#tab-district" data-toggle="tab">7. <?php echo $tab_district; ?></a></li>--->
            
        </ul>
        <!---------------------------------------tehsil----------------------->
          <div class="tab-content">   
               <div class="tab-pane active" id="tab-nation">
               <div class="form-group required">
                <label class="col-sm-2 control-label" >Select State</label>
                <div class="col-sm-4">
               <select name="select_tehsil_state" onchange="clear_state(this.value)" id="select_territory_nation" class="form-control">
                <option  value="">Select State</option>
                <?php foreach ($dpnation as $options) { ?>
                <?php if ($options['id'] == $user_group_id) { ?>
                <option value="<?php echo $options['id']; ?>" selected="selected"><?php echo $options['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $options['id']; ?>"><?php echo $options['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>  
                 <p id="select_territory_state_P" style="display:none;color:red;">Please select State</p>
               </div>
              </div>     
                   
                   
               <div class="form-group required">
                <label class="col-sm-2 control-label" >Select District</label>
                <div class="col-sm-4">
            <select name="select_district_territory" onchange="clear_district_territory()" id="select_district_territory" class="form-control">
                <option  value="">Select District</option>
               
              </select>
                    
                    
                 <p id="select_district" style="display:none;color:red;">Please select district</p>
               </div>
              </div>
                  
                     
            <div class="form-group required">
              <label class="col-sm-2 control-label" >Tehsil Name</label>
              <div class="col-sm-4">
                  
                  <input class="form-control" type="text" name="tehsil_name" onkeyup="clear_Tehsil_Name()" onkeypress="return CheckIsCharacter(event,this);" id="tehsil_name"  placeholder="Enter Tehsil name">
                  <p style="display:none;color:red;">Required Tehsil Name</p>
              </div>
            </div>
            
              <div class="buttons">
                <div class="pull-right">
                    <input type="submit" value="Create Tehsil" id="button-nation" class="btn btn-primary" />
                </div>
              </div>
    
               </div>
              
              
            <!------------------------block------------------------------->
              
               
              
              
              <div class="tab-pane" id="tab-region">
                  
               <div class="form-group required">
                <label class="col-sm-2 control-label" >Select State</label>
                <div class="col-sm-4">
               <select name="select_block_state" onchange="clear_state_block(this.value)" id="select_tehsil_state" class="form-control">
                <option  value="">Select State</option>
                <?php foreach ($dpnation as $options) { ?>
                <?php if ($options['id'] == $user_group_id) { ?>
                <option value="<?php echo $options['id']; ?>" selected="selected"><?php echo $options['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $options['id']; ?>"><?php echo $options['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>  
                 <p id="select_territory_state_P" style="display:none;color:red;">Please select State</p>
               </div>
              </div>     
                   
                   
               <div class="form-group required">
                <label class="col-sm-2 control-label" >Select District</label>
                <div class="col-sm-4">
            <select name="select_district_territory_block" onchange="clear_district_territory()" id="select_district_territory_block" class="form-control">
                <option  value="">Select District</option>
               
              </select>
                    
                    
                 <p id="select_district" style="display:none;color:red;">Please select district</p>
               </div>
              </div>  
                  
             
                     
            <div class="form-group required">
              <label class="col-sm-2 control-label" >Block Name</label>
              <div class="col-sm-4">
                  
                  <input class="form-control" type="text" name="Block_name" onkeyup="clear_Block_Name()" onkeypress="return CheckIsCharacter(event,this);" id="block_name"  placeholder="Enter Block name">
                  <p style="display:none;color:red;">Required Block Name</p>
              </div>
            </div>
              
              <div class="buttons">
                <div class="pull-right">
                  <input type="submit" value="Create Block" id="button-block" class="btn btn-primary" />
                </div>
              </div>
              </div>
              
             <!-------------------------------village--------------------------------->
              <div class="tab-pane" id="tab-village">
              
               <div class="form-group required">
                 <label class="col-sm-2 control-label" >Select State</label>
                <div class="col-sm-4">
               <select name="select_village_state" onchange="clear_state_village(this.value)" id="select_village_state" class="form-control">
                <option  value="">Select State</option>
                <?php foreach ($dpnation as $options) { ?>
                <?php if ($options['id'] == $user_group_id) { ?>
                <option value="<?php echo $options['id']; ?>" selected="selected"><?php echo $options['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $options['id']; ?>"><?php echo $options['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>  
                 <p id="select_territory_state_P" style="display:none;color:red;">Please select State</p>
               </div>
              </div>     
                   
                   
               <div class="form-group required">
                <label class="col-sm-2 control-label" >Select District</label>
                <div class="col-sm-4">
            <select name="select_district_territory_village" onchange="clear_district_tehsil(this.value)" id="select_district_territory_village" class="form-control">
                <option  value="">Select District</option>
               
              </select>
                    
                    
                 <p id="select_district" style="display:none;color:red;">Please select district</p>
               </div>
              </div> 
             <div class="form-group required">
                <label class="col-sm-2 control-label" >Select Tehsil</label>
                <div class="col-sm-4">
            <select name="select_tehsil" onchange="clear_tehsil()" id="select_tehsil" class="form-control">
                <option  value="">Select Tehsil</option>
               
              </select>
                    
                    
                 <p id="select_district" style="display:none;color:red;">Please select tehsil</p>
               </div>
              </div> 
                   <div class="form-group required">
                <label class="col-sm-2 control-label" >Select Block</label>
                <div class="col-sm-4">
            <select name="select_block" onchange="clear_block()" id="select_block" class="form-control">
                <option  value="">Select Block</option>
               
              </select>
                    
                    
                 <p id="select_block" style="display:none;color:red;">Please select Block</p>
               </div>
              </div> 
                     
            <div class="form-group required">
              <label class="col-sm-2 control-label" >Village Name</label>
              <div class="col-sm-4">
                  
                  <input class="form-control" type="text" name="village_name" onkeyup="clear_Village_Name()" onkeypress="return CheckIsCharacter(event,this);" id="village_name"  placeholder="Enter Village name">
                  <p style="display:none;color:red;">Required Village Name</p>
              </div>
            </div>  
            
              <div class="buttons">
                <div class="pull-right">
                  <input type="submit" value="Create Village" id="button-region" class="btn btn-primary" />
                </div>
              </div>
    
               </div> 
              
              
              
           
              
          </div>
        </form>
     
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>

<script type="text/javascript">
 
    
$('#button-nation').on('click', function() {
 //alert("bhdjfbh");
 if ($('input:text').val().length == 0) {
     $('p').show();
      return false;
 }
 else {
     
      document.getElementById("form-addgeo").submit();
	
 }
 
    
});

$('#button-village').on('click', function() {
 //alert("bhdjfbh");
 if ($('input:text').val().length == 0) {
     $('p').show();
      return false;
 }
 else {
     
      document.getElementById("form-addgeo").submit();
	
 }
 
    
});

$('#button-zone').on('click', function() {
   if ($('#select_village_state').val().length === 0) {
       $('#nation_state_p').show();
       return false;
 } else if ($('#state_name').val().length === 0) {
       $('p').show();
       return false;
 }  else {
    
 document.getElementById("form-addgeo").submit();
 }
});





$('#button-region').on('click', function() {
   
    
     if ($('#select_village_state').val().length === 0) {
          
       $('#select_territory_nation_p').show();
       return false;
 } else if ($('#select_territory_state').val().length === 0) {
    
       $('#select_territory_state_P').show();
       return false;
 }  else if ($('#village_name').val().length === 0) {
      
       $('#region_name_p').show();
       return false;
 }  else {
      
 document.getElementById("form-addgeo").submit();
 }
});



$('#button-district').on('click', function() {
   // var fff = $('#district_name').val();
    //alert(fff);
  if ($('#select_district_nation').val().length === 0) {
       $('#select_district_nation_p').show();
       return false;
 } else if ($('#select_district_state').val().length === 0) {
       $('#select_district_state_p').show();
       return false;
 }  else if ($('#select_district_territory').val().length === 0) {
       $('#select_district_territory_p').show();
       return false;
 }  else if ($('#district_name').val().length === 0) {
       $('#district_name_p').show();
       return false;
 } else if($('#district_name').val()!='') {
     
 document.getElementById("form-addgeo").submit();
 }
});




$('#button-area').on('click', function() {
    
 if ($('#select_hq_nation').val().length === 0) {
       $('#select_hq_nation_p').show();
       return false;
 } else if ($('#select_hq_state').val().length === 0) {
       $('#select_hq_state_p').show();
       return false;
 }  else if ($('#select_hq_territory').val().length === 0) {
       $('#select_hq_territory_p').show();
       return false;
 }  else if ($('#select_hq_district').val().length === 0) {
       $('#select_hq_district_p').show();
       return false;
 }  else if ($('#hq_name').val().length === 0) {
       $('#hq_name_p').show();
       return false;
 } else {   
 document.getElementById("form-addgeo").submit();
 }
});

$('#button-territory').on('click', function() {
 document.getElementById("form-addgeo").submit();
});



$('#button-state').on('click', function() {
if ($('#select_state_nation').val()==0) {
    $('#nation_state_p').show();
    return;
 } 
else if ($('#state_state_name').val().length == 0) {
     $('#state_state_p').show();
     return;
 }
 
else{
    
 var statenation=document.getElementById('select_state_nation').value;
 var statename=document.getElementById('state_state_name').value;
  
	$.ajax({ 
		type: 'post',
		url: 'index.php?route=geo/addgeo/addStates&token='+getURLVar('token'),
                data: {nation:statenation,sname:statename},
                dataType: 'json',
		cache: false,
		beforeSend: function() {
			$('#button-state').button('loading');
		},
		complete: function() {
			$('#button-state').button('reset');
		},		
		success: function(json) {
			           
            if (json['redirectf']) {
                
                //alert(json['redirectf']);
                location = json['redirectf'];
            } 
                else if (json['error']) {
                if (json['error']['warning']) {
alert(json['error']['warning']);                    
                }           
            } else {
      
       document.getElementById('select_state_nation').value="";
       document.getElementById('state_state_name').value="";
       
            }

		}
               
	});
       
        }
        
});



$('#button-district').on('click', function() {

if ($('#select_district_nation').val()== 0) {
    $('#select_district_nation_p').show();
    return;
 } 
else if ($('#select_district_state').val()== 0) {
    $('#select_district_state_p').show();
    return;
 } 
else if ($('#district_district_name').val().length == 0) {
     $('#select_district_name_p').show();
     return;
 }
 else{
 var nametxt1=document.getElementById('district_district_name').value;   
 var nametxt2=document.getElementById('select_district_nation').value;
 var nametxt3=document.getElementById('select_district_state').value;

	$.ajax({ 
		type: 'post',
		url: 'index.php?route=geo/addgeo/addDistrict&token='+getURLVar('token'),
                 data: {name:nametxt1,nation:nametxt2,state:nametxt3},
        dataType: 'json',
		cache: false,
		beforeSend: function() {
			$('#button-district').button('loading');
		},
		complete: function() {
			$('#button-district').button('reset');
		},		
		success: function(json) {
			            
            if (json['redirectf']) {
                location = json['redirectf'];
            } else if (json['error']) {
                if (json['error']['warning']) {
alert(json['error']['warning']);                    
                }           
            } else {
      
       document.getElementById('select_district_nation').value="";
       document.getElementById('select_district_state').value="";
       document.getElementById('district_district_name').value="";
            }

		}		
	});
        }
});

//onchange claer tex

//for territory
function clear_state(data) {
//alert(data);
 $('#select_state_P').hide();
    
      
      var stateid=data;
        $.ajax({ 
        type: 'post',
        url: 'index.php?route=geo/addgeovillage/getdistrict&token='+getURLVar('token'),
        data: 'stateid='+stateid,
        // dataType: 'json',
        cache: false,

        success: function(data) {

      //alert(data);
        $("#select_district_territory").html(data);
        
        }


   });

      
 }
 
function clear_state_block(data) {
//alert(data);
 $('#select_state_P').hide();
    
      
      var stateid=data;
        $.ajax({ 
        type: 'post',
        url: 'index.php?route=geo/addgeovillage/getdistrict&token='+getURLVar('token'),
        data: 'stateid='+stateid,
        // dataType: 'json',
        cache: false,

        success: function(data) {

      //alert(data);
        $("#select_district_territory_block").html(data);
        
        }


   });

      
 }
 
 function clear_state_village(data) {
//alert(data);
 $('#select_state_P').hide();
    
      
      var stateid=data;
        $.ajax({ 
        type: 'post',
        url: 'index.php?route=geo/addgeovillage/getdistrict&token='+getURLVar('token'),
        data: 'stateid='+stateid,
        // dataType: 'json',
        cache: false,

        success: function(data) {

      //alert(data);
        $("#select_district_territory_village").html(data);
        
        }


   });

      
 }
 
 function clear_district_tehsil(data) {
//alert(data);
 $('#select_state_P').hide();
    
      
      var districtid=data;
        $.ajax({ 
        type: 'post',
        url: 'index.php?route=geo/addgeovillage/gettehsilblock&token='+getURLVar('token'),
        data: 'districtid='+districtid,
        // dataType: 'json',
        cache: false,

        success: function(data) {
 var da=data.split("_");
      //alert(data);
        $("#select_tehsil").html(da[0]);
         $("#select_block").html(da[1]);
        
        }


   });

      
 }
  function clear_district_block(data) {
//alert(data);
 $('#select_state_P').hide();
    
      
      var districtid=data;
        $.ajax({ 
        type: 'post',
        url: 'index.php?route=geo/addgeovillage/getblock&token='+getURLVar('token'),
        data: 'districtid='+districtid,
        // dataType: 'json',
        cache: false,

        success: function(data) {

      //alert(data);
        $("#select_block").html(data);
        
        }


   });

      
 }
 

//--></script> 