
 <?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        
        <button type="submit" form="form-restore" data-toggle="tooltip" title="Add Geo" class="btn btn-default"><i class="fa fa-reply"></i></button>
      </div>
      <h1>Add Geo</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="Add Geo"><?php echo "Add Geo" ?></a></li>
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
        <h3 class="panel-title"><i class="fa fa-exchange"></i> Add Geo</h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $addgeo; ?>" method="post" id="form-addgeo" class="form-horizontal" data-toggle="validator" role="form">
         
        <ul id="geo" class="nav nav-tabs nav-justified">
            <li class="active"><a href="#tab-nation" data-toggle="tab">1.Country</a></li>
            <li class=""><a href="#tab-zone" data-toggle="tab">2. State</a></li>
            <li class=""><a href="#tab-region" data-toggle="tab">3.Territory</a></li>
            <li class=""><a href="#tab-state" data-toggle="tab">4. District</a></li>
            
        </ul>
          <div class="tab-content">   
               <div class="tab-pane active" id="tab-nation">
                         
            <div class="form-group required">
              <label class="col-sm-2 control-label" >Country Name</label>
              <div class="col-sm-4">
                  
                  <input class="form-control" type="text" name="nation_name" onkeyup="clear_Nation_Name()" onkeypress="return CheckIsCharacter(event,this);" id="nation_name"  placeholder="Enter Nation name">
                  <p style="display:none;color:red;">Required Country Name</p>
              </div>
            </div>
            
              <div class="buttons">
                <div class="pull-right">
                    <input type="submit" value="Create Country" id="button-nation" class="btn btn-primary" />
                </div>
              </div>
    
               </div>
              
              
              
              
               <div class="tab-pane" id="tab-zone">
              
              <div class="form-group required">
                <label class="col-sm-2 control-label" >Select Country</label>
                <div class="col-sm-4">
              <select name="select_state_nation" onchange="clear_State_Nation()" id="select_state_nation" class="form-control">
                <option  value="">Select Country</option>
                <?php foreach ($dpnation as $options) { ?>
                <?php if ($options['id'] == $user_group_id) { ?>
                <option value="<?php echo $options['id']; ?>" selected="selected"><?php echo $options['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $options['id']; ?>"><?php echo $options['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select> 
                 <p id="nation_state_p" style="display:none;color:red;">Required select Country</p>
               </div>
              </div>  
                   
            <div class="form-group required">
              <label class="col-sm-2 control-label" >State</label>
              <div class="col-sm-4">
                  <input class="form-control" type="text" name="state_name" onkeyup="clear_state_Name();" onkeypress="return CheckIsCharacter(event,this);" id="state_name"  placeholder="Enter State name" >
                  <p style="display:none;color:red;">Required State name</p>
              </div>
              
            </div>
                   
                
            
              <div class="buttons">
                <div class="pull-right">
                  <input type="submit" value="Create State" id="button-zone" class="btn btn-primary" />
                </div>
              </div>
    
               </div>
              
              
              
              
              
              
              
              <div class="tab-pane" id="tab-region">
                  
              <div class="form-group required">
                <label class="col-sm-2 control-label" >Select Country</label>
                <div class="col-sm-4">
              <select name="select_territory_nation" onchange="clear_territory_nation(this.value)" id="select_territory_nation" class="form-control">
                <option  value="">Select Country</option>
                <?php foreach ($dpnation as $options) { ?>
                <?php if ($options['id'] == $user_group_id) { ?>
                <option value="<?php echo $options['id']; ?>" selected="selected"><?php echo $options['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $options['id']; ?>"><?php echo $options['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
                    
                    
                 <p id="select_territory_nation_p" style="display:none;color:red;">Please select nation</p>
               </div>
              </div>
                  
            <div class="form-group required">
                <label class="col-sm-2 control-label" >Select State</label>
                <div class="col-sm-4">
              <select name="select_territory_state" onchange="clear_territory_state(this.val)" id="select_territory_state" class="form-control">
                <option  value="">Select State</option>
              </select>   
                 <p id="select_territory_state_P" style="display:none;color:red;">Please select State</p>
               </div>
              </div>
                  
               <div class="form-group required">
                <label class="col-sm-2 control-label" >Territory Name</label>
                <div class="col-sm-4">
                   <input class="form-control" type="text" id="territory_name" onkeyup="clear_territory_name()"  name="territory_name"  placeholder="Enter Territory name"/>
                    <p  id="region_name_p" style="display:none;color:red;">Please Fill Territory name </p>
               </div>
              </div>
              
              <div class="buttons">
                <div class="pull-right">
                  <input type="submit" value="Create Territory" id="button-region" class="btn btn-primary" />
                </div>
              </div>
              </div>
              
              
              <div class="tab-pane" id="tab-state">
                  
              <div class="form-group required">
                <label class="col-sm-2 control-label" >Select Country</label>
                <div class="col-sm-4">
              <select name="select_district_nation" onchange="clear_district_nation(this.value)" id="select_district_nation" class="form-control">
                <option  value="">Select Country</option>
                <?php foreach ($dpnation as $options) { ?>
                <?php if ($options['id'] == $user_group_id) { ?>
                <option value="<?php echo $options['id']; ?>" selected="selected"><?php echo $options['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $options['id']; ?>"><?php echo $options['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
                    
                    
                 <p id="select_district_nation_p" style="display:none;color:red;">Required select nation</p>
               </div>
              </div>
                  
            <div class="form-group required">
                <label class="col-sm-2 control-label" >Select State</label>
                <div class="col-sm-4">
              <select name="select_district_state" onchange="clear_district_state(this.value)" id="select_district_state" class="form-control">
                <option  value="">Select State</option>
              </select>
                     
                 <p id="select_district_state_p" style="display:none;color:red;">Required select zone</p>
               </div>
              </div>
                  
                  
              <div class="form-group required">
                <label class="col-sm-2 control-label" >Select Territory</label>
                <div class="col-sm-4">
              <select name="select_district_territory" onchange="clear_district_territory()" id="select_district_territory" class="form-control">
                <option  value="">Select Territory</option>
               
              </select>
                    
                    
                 <p id="select_district_territory_p" style="display:none;color:red;">Required select region</p>
               </div>
              </div>
                
             <div class="form-group required">
                <label class="col-sm-2 control-label" >District Name</label>
                <div class="col-sm-4">
                  <input  id="district_name" onkeyup="clear_district_Name()" onkeypress="return CheckIsCharacter(event,this);" class="form-control" type="text" name="district_name"  placeholder="Enter District name"/>
                   <p id="district_name_p" style="display:none;color:red;">Required District name</p>
                </div>
              </div>
               
              
              <div class="buttons">
                <div class="pull-right">
                  <input type="submit" value="Create District" id="button-district" class="btn btn-primary" />
                </div>
              </div>
              </div>
              
              
              
              
              
              
              
              
              
             <!------------ 
              <div class="tab-pane" id="tab-area">
                  
              <div class="form-group required">
                <label class="col-sm-2 control-label" >Select Country</label>
                <div class="col-sm-4">
              <select name="select_hq_nation" onchange="clear_hq_nation(this.value)" id="select_hq_nation" class="form-control">
                <option  value="">Select Country</option>
                <?php foreach ($dpnation as $options) { ?>
                <?php if ($options['id'] == $user_group_id) { ?>
                <option value="<?php echo $options['id']; ?>" selected="selected"><?php echo $options['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $options['id']; ?>"><?php echo $options['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
                    
                    
                 <p id="select_hq_nation_p" style="display:none;color:red;">Required select nation</p>
               </div>
              </div>
                  
            <div class="form-group required">
                <label class="col-sm-2 control-label" >Select State</label>
                <div class="col-sm-4">
              <select name="select_hq_state" onchange="clear_hq_state(this.value)" id="select_hq_state" class="form-control">
                <option  value="">Select State</option>
               
              </select>
                 
                 <p id="select_hq_state_p" style="display:none;color:red;">Required select State</p>
               </div>
              </div>
                  
                  
              <div class="form-group required">
                <label class="col-sm-2 control-label" >Select Territory</label>
                <div class="col-sm-4">
              <select name="select_hq_territory" onchange="clear_hq_territory(this.value)" id="select_hq_territory" class="form-control">
                <option  value="">Select Territory</option>
                
              </select>
                    
                    
                 <p id="select_hq_territory_p" style="display:none;color:red;">Required select region</p>
               </div>
              </div>
                  
                  
              <div class="form-group required">
                <label class="col-sm-2 control-label" >Select District</label>
                <div class="col-sm-4">
              <select name="select_hq_district" onchange="clear_hq_district()" id="select_hq_district" class="form-control">
                <option  value="">Select District</option>
                
              </select>
                      
                 <p id="select_hq_district_p" style="display:none;color:red;">Required select state</p>
               </div>
              </div>
                
           <div class="form-group required">
                <label class="col-sm-2 control-label" >Village</label>
                <div class="col-sm-4">
                  <input  id="hq_name" onkeyup="clear_hq_name()" onkeypress="return CheckIsCharacter(event,this);" class="form-control" type="text" name="hq_name"  placeholder="Enter Village name"/>
                   <p id="hq_name_p" style="display:none;color:red;">Required area name</p>
                </div>
              </div>
               
              
              <div class="buttons">
                <div class="pull-right">
                  <input type="submit" value="Create Village" id="button-area" class="btn btn-primary" />
                </div>
              </div>
              </div>
              --------------->
           
             
              
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



$('#button-zone').on('click', function() {
   if ($('#select_state_nation').val().length === 0) {
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
    
    
     if ($('#select_territory_nation').val().length === 0) {
       $('#select_territory_nation_p').show();
       return false;
 } else if ($('#select_territory_state').val().length === 0) {
       $('#select_territory_state_P').show();
       return false;
 }  else if ($('#territory_name').val().length === 0) {
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
function clear_territory_nation(data) {
//alert(data);
      $('#select_territory_nation_p').hide();
     
      
      var nation=data;
        $.ajax({ 
        type: 'post',
        url: 'index.php?route=geo/addgeo/getterritorystate&token='+getURLVar('token'),
        data: 'nation='+nation,
        // dataType: 'json',
        cache: false,

        success: function(data) {

        // alert(data);
        $("#select_territory_state").html(data);
        
        }


   });

      
 }
 
 function clear_territory_state()  {
      $('#select_territory_state_P').hide();
 }
 
 function clear_territory_name() {
      $('#region_name_p').hide();
 }
 



// for District
function clear_district_nation(data) {
      $('#select_district_nation_p').hide();
       $("#select_district_territory").html('<option value="">Select Territory</option>');
      
      
      var nation=data;
        $.ajax({ 
        type: 'post',
        url: 'index.php?route=geo/addgeo/getterritorystate&token='+getURLVar('token'),
        data: 'nation='+nation,
        // dataType: 'json',
        cache: false,

        success: function(data) {

        // alert(data);
        $("#select_district_state").html(data);
        
        }


   });
 }
 
 function clear_district_state(data)  {
     //alert(data);
      $('#select_district_state_p').hide();
      
      
      var state_id=data;
        $.ajax({ 
        type: 'post',
        url: 'index.php?route=geo/addgeo/getdistrict_territory&token='+getURLVar('token'),
        data: 'state_id='+state_id,
        // dataType: 'json',
        cache: false,

        success: function(data) {

         //alert(data);
        $("#select_district_territory").html(data);
        
        }


   });
 }
 
 function clear_district_territory() {
      $('#select_district_territory_p').hide();
 }
 function clear_district_Name() {
    $('#district_name_p').hide();
 }
 
 

//for HQ
function clear_hq_nation(data) {
   // alert(data);
      $('#select_hq_nation_p').hide();
      $("#select_hq_territory").html('<option value="">Select Territory</option>');
      $("#select_hq_district").html('<option value="">Select District</option>');
      
      var nation=data;
        $.ajax({ 
        type: 'post',
        url: 'index.php?route=geo/addgeo/getterritorystate&token='+getURLVar('token'),
        data: 'nation='+nation,
        // dataType: 'json',
        cache: false,

        success: function(data) {

         //alert(data);
        $("#select_hq_state").html(data);
        
        }


   });
 }
 
 function clear_hq_state(data)  {
      $('#select_hq_state_p').hide();
       $("#select_hq_district").html('<option value="">Select District</option>');
       var state_id=data;
        $.ajax({ 
        type: 'post',
        url: 'index.php?route=geo/addgeo/getdistrict_territory&token='+getURLVar('token'),
        data: 'state_id='+state_id,
        // dataType: 'json',
        cache: false,

        success: function(data) {

         //alert(data);
        $("#select_hq_territory").html(data);
        
        }


   });
      
      
 }
 
 function clear_hq_territory(data)  {
    // alert(data);
      $('#select_hq_territory_p').hide();
      
      var territory_id=data;
        $.ajax({ 
        type: 'post',
        url: 'index.php?route=geo/addgeo/getTerritory_District&token='+getURLVar('token'),
        data: 'territory_id='+territory_id,
        // dataType: 'json',
        cache: false,

        success: function(data) {

      //   alert(data);
        $("#select_hq_district").html(data);
        
        }


   });
      
 }
 function clear_hq_district() {
    $('#select_hq_district_p').hide();
    
 }
 
 function clear_hq_name() {
    $('#hq_name_p').hide();
 }
 
 
 
 
function CheckIsCharacter(e,t){
         try {
                if (window.event) {
                    var charCode = window.event.keyCode;
                }
                else if (e) {
                    var charCode = e.which;
                }
                else { return true; }
                if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123))
                    return true;
                else
                   
                    return false;
            }
            catch (err) {
                alert(err.Description);
            }
       }
//--></script> 