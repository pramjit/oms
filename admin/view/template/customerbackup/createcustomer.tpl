
 <?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button id="button-submit" form="form-backup" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-default"><i class="fa fa-save"></i></button>
        <button type="submit" form="form-restore" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></button>
      </div>
      <h1>Create Employee</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo "Create Employee"; ?>"><?php echo "Create Employee"; ?></a></li>
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
        <h3 class="panel-title"><i class="fa fa-exchange"></i>Create Employee</h3>
      </div>
      <div class="panel-body">
        <form action="index.php?route=customer/createcustomer/customerinsrt&token=<?php echo $token; ?>" method="post" id="filter_form" class="form-horizontal">
         
           <div class="col-sm-4"> 
               
            <div class="form-group required">
              <label class="col-sm-4 control-label" >First Name</label>
              <div class="col-sm-8">
              <input class="form-control" type="text" id="First_Name" onchange="clear_First_Name()" onkeypress="return CheckIsCharacter(event,this);" name="first_name"  placeholder="Enter first name"/>
              <p id="First_Name_p" style="display:none;color:red;">Required First Name</p>
              </div>
            </div>
           <div class="form-group required">
              <label class="col-sm-4 control-label">Last Name</label>
              <div class="col-sm-8">
              <input type="text" id="Last_Name" onchange="clear_Last_Name()" onkeypress="return CheckIsCharacter(event,this);" name="last_name" class="form-control" placeholder="Enter last name "/>
               <p id="Last_Name_p" style="display:none;color:red;">Required Last Name</p>
              </div>
            </div>
            
            <div class="form-group required">
              <label class="col-sm-4 control-label">Mobile(user id)</label>
              <div class="col-sm-8">
              <input type="text" id="Email_ID" onchange="clear_Email_ID()" maxlength="10" onkeypress="return IsNumeric(event);" name="email" class="form-control" placeholder="Enter user id "/>
               <p id="Email_ID_p" style="display:none;color:red;">Required User Id</p> 
              </div>
            </div>
            
             <div class="form-group required">
              <label class="col-sm-4 control-label">Password</label>
              <div class="col-sm-8">
              <input type="password" id="Password" onchange="clear_Password()" name="password" class="form-control" placeholder="Enter password "/>
               <p id="Password_p" style="display:none;color:red;">Required Password</p>
               <div id="divpass"></div>
              </div>
            </div>
            
             <div class="form-group required">
              <label class="col-sm-4 control-label">Confirm Password</label>
              <div class="col-sm-8">
              <input type="password" id="ConfirmPassword" onchange="clear_ConfirmPassword()" name="confirm_password" class="form-control" placeholder="Enter Confirm Password "/>
               <p id="ConfirmPassword_p" style="display:none;color:red;">Required Confirm Password</p>
               <p id="PasswordMatch" style="display:none;color:red;">Passwords do not match!</p>
              </div>
            </div>
        
           </div>  
            <div class="col-sm-4">
                
                
            <div class="form-group required">
              <label class="col-sm-4 control-label">SAP ID</label>
              <div class="col-sm-8">
              <input type="text" id="sapid"  name="sapid" class="form-control" placeholder="Enter Sap id "/>
              </div>
            </div>    
                
             <div class="form-group required">
              <label class="col-sm-4 control-label">Role</label>
              <div class="col-sm-8">
               <select name="select_role" id="select_role" class="form-control select2" onchange="getareamanag(this.value);">
                    <option value=''>Select Role</option>
                  <?php foreach ($listROLEs as $listROLE) { ?>
                    <option value="<?php echo $listROLE['customer_group_id']; ?>"<?php if($listROLE['customer_group_id']==$filter_dist) { echo 'selected'; } ?>><?php echo $listROLE['name']; ?></option>
               
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="input-group">State</label>
                <div class="col-sm-8">
                <select name="stat_id" id="stat_id" class="form-control select2" onchange="district_state(this.value)">
                    <option value=''>Select State</option>
                  <?php foreach ($listSTATs as $listSTAT) { ?>
                    <option value="<?php echo $listSTAT['SID']; ?>"<?php if($listSTAT['SID']==$filter_dist) { echo 'selected'; } ?>><?php echo $listSTAT['NAME']; ?></option>
               
                  <?php } ?>
                </select>
                </div>
              </div>
            <div class="form-group" >
                <label class="col-sm-4 control-label" for="input-group">District</label>
                <div class="col-sm-8" id="dist_id_list">
                <select name="dist_id" id="dist_id" class="col-sm-4 form-control select2" class="form-control select2-selection select2-selection--multiple"  multiple="multiple">
                    <option value=''>Select District</option>                 
                </select>
                   
                </div>
              </div> 
             <div class="form-group">
              <label class="col-sm-4 control-label">Address</label>
              <div class="col-sm-8">
              <input type="text" id="Address" onchange="clear_Address()" name="address" class="form-control" placeholder="Enter address "/>
               <p id="Address_p" style="display:none;color:red;">Required Address</p>
              </div>
            </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group required" id="area_div" style="display:none;">              
                <div class="col-sm-8" id="am_id">
                <select name="select_am" id="amid" class="form-control select2"  >
                    <option value=''>Select Area Manager</option>
               </select>
              </div>
            </div>
            </div>
           
            <div class="col-sm-4">
                <div  id="group_hide"></div>
            </div>
        </form>
     
      </div>
    </div>
      
  </div>
    
</div>
<script type="text/javascript" src="view/javascript/bootstrap/js/select2.js"></script>
<?php echo $footer; ?>

<script type="text/javascript">
    $('.date').datetimepicker({
      pickTime: false 
   });
$('#button-submit').on('click', function() {
	
        //url = 'index.php?route=report/saletarget/saletargetsubmit&token=<?php echo $token; ?>';
     
        
	$('#filter_form').submit();    
   
});
</script> 
<script> 
$(document).ready(function(){
 $('#retailerdiv').hide();
$('#group_role').on('change',function(){    
  var query = $(this).val(); 
   $('#group_hide').show();
   $('#group_hide').html('');
   if(query != '')  
           {  
                $.ajax({  
                    url: 'index.php?route=customer/createcustomer/groupDropdown&token='+getURLVar('token'),  
                     method:"POST",  
                     data:{query:query},  
                     success:function(data)  
                     {  
                        
                         $('#group_hide').html(data['data']);
                         
                          
                     }  
                });  
           }  
    
})
});
//clear text

function clear_Password()
{
    $('#Password_p').hide();
    //var hh = this.value.match(/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])([a-zA-Z0-9]{8})$/);
    //alert(hh);
     //$('#Password_p').show();   
}
function clear_ConfirmPassword()
{
    //$('#ConfirmPassword_p').hide();
     $("#ConfirmPassword_p").hide();
    var password = $("#Password").val();
    var confirmPassword = $("#ConfirmPassword").val();
    if (password != confirmPassword)
        $("#PasswordMatch").show();
    else if (password = confirmPassword)
        $("#PasswordMatch").hide();
       
    else
       $('#ConfirmPassword_p').hide();
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
  var specialKeys = new Array();
        specialKeys.push(8); //Backspace
        function IsNumeric(e) {
            var keyCode = e.which ? e.which : e.keyCode
            var ret = ((keyCode >= 48 && keyCode <= 57 || keyCode == 46) || specialKeys.indexOf(keyCode) != -1);
            //document.getElementById("number").style.display = ret ? "none" : "inline";
            return ret;
        }
        
 function district_state(stid)
 {
     //alert(stid);
        $.ajax({ 
        type: 'post',
        url: 'index.php?route=customer/createcustomer/getdistrict&token='+getURLVar('token'),
        data: 'state_id='+stid,
        // dataType: 'json',
        cache: false,

        success: function(data) {
        //alert(data);
        $("#dist_id").html(data);
        
        }


   });
 
 }
 function getareamanag(rol)
 {
     if(rol=='4')
    {
      $("#area_div").show();  
    
     //alert(rol);
        $.ajax({ 
        type: 'post',
        url: 'index.php?route=customer/createcustomer/getam&token='+getURLVar('token'),
        data: 'role='+rol,
        // dataType: 'json',
        cache: false,

        success: function(data) {
        //alert(data);
        $("#am_id").html(data);
        
        }
    

   });
 
 }
 else{
   $("#area_div").hide();    
 }
 }

</script>
<script>
 $("#dist_id").select2({ tags: true,
      placeholder: function(){
        $(this).data('placeholder');
    }
  
});
</script>

