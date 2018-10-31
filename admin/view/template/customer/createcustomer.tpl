<?php 
echo $header; 
echo $column_left; 
?>
<div id="content">
    <div class="page-header">
    <div class="container-fluid">
        <div class="pull-right">
            <button id="button-submit" form="form-backup" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-default"><i class="fa fa-save"></i></button>
            <button type="submit" form="form-restore" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></button>
        </div>
        <h1>Employee Registration :</h1>
        <ul class="breadcrumb">
            <?php 
            foreach($breadcrumbs as $breadcrumb){ 
                echo '<li><a href="">Employee Details</a></li>';
            }
            ?>
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
            <form id="EmpRegd" action="index.php?route=customer/createcustomer/customerinsrt&token=<?php echo $token; ?>" method="post" class="form-horizontal">
            <div class="col-sm-4"> 
                
                <div class="form-group required">
                <label class="col-sm-4 control-label">UserId (Mob)</label>
                <div class="col-sm-8">
                    <input type="text"  name="UserId" id="UserId" onchange="vldUserId(this.value);" maxlength="10" onkeypress="return IsNumeric(event);" class="form-control" placeholder="Enter 10 Digit Mobile No As User Id"/>
                    <p id="UserId_p" style="display:none;color:red;">Required UserId</p>
                    <p id="UserId_ex" style="display:none;color:red;">UserId/Mobile Number Exists</p> 
                </div>
                </div>
                <div class="form-group required">
                <label class="col-sm-4 control-label" >First Name</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" name="fname" id="fname" onchange="clear_fname()" onkeypress="return CheckIsCharacter(event,this);" placeholder="Enter first name" required="required"/>
                    <p id="fname_p" style="display:none;color:red;">Required First Name</p>
                </div>
                </div>
                <div class="form-group required">
                <label class="col-sm-4 control-label" >Last Name</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" name="lname" id="lname" onchange="clear_lname()" onkeypress="return CheckIsCharacter(event,this);" placeholder="Enter last name" required="required"/>
                    <p id="lname_p" style="display:none;color:red;">Required First Name</p>
                </div>
                </div>
                <div class="form-group required">
                <label class="col-sm-4 control-label">Password</label>
                <div class="col-sm-8">
                    <input type="password" id="passwd" name="passwd"  onchange="clear_passwd()" class="form-control" placeholder="Enter password "/>
                    <p id="passwd_p" style="display:none;color:red;">Required Password</p>
                </div>
                </div>
                <div class="form-group required">
                <label class="col-sm-4 control-label">Confirm Password</label>
                <div class="col-sm-8">
                    <input type="password" id="cpasswd" name="cpasswd" onchange="clear_cpasswd()" class="form-control" placeholder="Enter Confirm Password "/>
                    <p id="cpasswd_p" style="display:none;color:red;">Required Confirm Password</p>
                </div>
                </div>
            </div>  
            
            
            
            
            <div class="col-sm-4">
                <div class="form-group required">
                <label class="col-sm-4 control-label">SAP ID</label>
                <div class="col-sm-8">
                    <input type="text" id="sapid"  name="sapid" class="form-control" placeholder="Enter SAP Id "/>
                </div>
                </div>    
                <div class="form-group required">
                <label class="col-sm-4 control-label">Role</label>
                <div class="col-sm-8">
                    <select name="erole" id="erole" class="form-control select2" onchange="getXtraFields(this.value);">
                        <option value='0'>Select Role</option>
                        <?php foreach ($listROLEs as $listROLE) { ?>
                        <option value="<?php echo $listROLE['customer_group_id']; ?>"><?php echo $listROLE['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                </div>
                <div class="form-group required">
                <label class="col-sm-4 control-label" for="input-group">State</label>
                <div class="col-sm-8">
                    <select name="estate" id="estate" class="form-control select2" onchange="findDistrict(this.value);">
                        <option value='0'>Select State</option>
                        <?php foreach ($listSTATs as $listSTAT) { ?>
                        <option value="<?php echo $listSTAT['SID']; ?>"><?php echo $listSTAT['NAME']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                </div>
                <div class="form-group required">
                <label class="col-sm-4 control-label" for="input-group">District</label>
                <div class="col-sm-8" id="dist_id_list">
                    <select name="edist[]" id="edist" class="col-sm-4 form-control select2 select2-selection--multiple"  multiple="multiple">
                        <option value='0'> Select District</option>                 
                    </select>
                </div>
                </div> 
                <div class="form-group">
                <label class="col-sm-4 control-label">Address</label>
                <div class="col-sm-8">
                    <input type="text" id="addrs" name="addrs" onchange="clear_Address();" class="form-control" placeholder="Address"/>
                    <p id="addrs_p" style="display:none;color:red;">Required Address</p>
                </div>
                </div>
            </div>
            
            
            
            <div class="col-sm-4">
                <div id="XtraDiv" style="display: none;"> 
                    <div id="MoAmDiv" style="display: none;"> 
                        <div class="form-group required">
                        <label class="col-sm-4 control-label">Area Manager</label>
                        <div class="col-sm-8">
                            <select name="MoAmId" id="MoAmId" class="form-control">
                                <option value='0'>Select Area Manager</option>
                            </select>
                            <p id="MoAmId_p" style="display:none;color:red;">Select AM</p>
                        </div>
                        </div>
                    </div>

                    <div class="form-group required">
                    <label class="col-sm-4 control-label">Hotel Conveyance</label>
                    <div class="col-sm-8">
                        <input type="text" name="HotCon" id="HotCon" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.keyCode==8 || event.keyCode==46' placeholder="Enter Amount"/>
                        <p id="HotCon_p" style="display:none;color:red;">Required Hotel Conveyance</p>
                    </div>
                    </div>
                    <div class="form-group required">
                    <label class="col-sm-4 control-label">Local Conveyance</label>
                    <div class="col-sm-8">
                        <input type="text" name="LocCon" id="LocCon" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.keyCode==8 || event.keyCode==46' placeholder="Enter Amount"/>
                        <p id="LocCon_p" style="display:none;color:red;">Required Local Conveyance</p>
                    </div>
                    </div>
                    <div class="form-group required">
                    <label class="col-sm-4 control-label">Outstation Conveyance</label>
                    <div class="col-sm-8">
                        <input type="text" name="OutCon" id="OutCon" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.keyCode==8 || event.keyCode==46' placeholder="Enter Amount"/>
                        <p id="OutCon_p" style="display:none;color:red;">Required Hotel Conveyance</p>
                    </div>
                    </div>
                    <div class="form-group required">
                    <label class="col-sm-4 control-label">Vehicle Conveyance</label>
                    <div class="col-sm-8">
                        <input type="text" name="MotCon" id="MotCon" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.keyCode==8 || event.keyCode==46' placeholder="Enter Amount for Car / Bike"/>
                        <p id="MotCon_p" style="display:none;color:red;">Required Motor Conveyance</p>
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
    $(document).ready(function(){
        $("#edist").select2({ 
            tags: true,
            placeholder: '   Select District(s)',
            width: '100%'
        });
    });

    
    function vldUserId(UsrId){
        $.ajax({ 
            type: 'post',
            url: 'index.php?route=customer/createcustomer/vldUserId&token='+getURLVar('token'),
            data: 'UsrId='+UsrId,
            cache: false,
            success: function(resp) {
               if(resp==1){
                   $('#UserId_ex').show();
                   return false;
               }
               else{
                   $('#UserId_ex').hide();
               }
            }
        });
    }
    
    
    
    
    
    //================================== Form Validation & Submit Start===========================//
    $('#button-submit').on('click', function() {
	
        var UserId=$('#UserId').val();
        var fname=$('#fname').val();
        var lname=$('#lname').val();
        var passwd=$('#passwd').val();
        var cpasswd=$('#cpasswd').val();

        var sapid=$('#sapid').val();
        var erole=$('#erole').val();
        var estate=$('#estate').val();
        var edist=$('#edist').val();
        var addrs=$('#addrs').val();

        var MoAmId=$('#MoAmId').val();
        var HotCon=$('#HotCon').val();
        var LocCon=$('#LocCon').val();
        var OutCon=$('#OutCon').val();
        var MotCon=$('#MotCon').val();
        
        if(!UserId || UserId.length<10){
            alert('UserId Required');
            return false;
        }
        if(!fname || fname.length<2){
            alert('Firstname Required');
            return false;
        }
        if(!lname || lname.length<2){
            alert('Lastname Required');
            return false;
        }
        if(!passwd || passwd.length<6){
            alert('Password length min. 6  or more ');
            return false;
        }
        if(passwd && passwd!=cpasswd){
            alert("Password & Confirm Password does not match");
            return false;
        }
        if(!sapid || sapid.length<5){
            alert('SAP ID Required');
            return false;
        }  
        if(!erole || erole==0){
            alert('Select Role');
            return false;
        } 
        if(!estate || estate==0){
            alert('Select State');
            return false;
        } 
        if(!edist || edist==0){
            alert('Select Dist');
            return false;
        } 
        if(!addrs || addrs.length<5){
            alert('Address Required');
            return false;
        } 
        
        if(erole==4){
            
            if(!MoAmId || MoAmId==0){
                alert('Select Area Manager');
                return false;
            } 
        }
        if(erole==4 || erole==3){
            
            if(!HotCon || HotCon==''){
                alert('Hotel Conveyance Required');
                return false;
            } 
            if(!LocCon || LocCon==''){
                alert('Local Conveyance Required');
                return false;
            } 
            if(!OutCon || OutCon==''){
                alert('Outstation Conveyance Required');
                return false;
            } 
            if(!MotCon || MotCon==''){
                alert('Motor Conveyance Required');
                return false;
            } 
            
        }
           
            $('#EmpRegd').submit(); // On Successful validation   
        
    });
</script> 

<script> 
function CheckIsCharacter(e,t){
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || (charCode==08) || (charCode==32))
            return true;
        else

            return false;
    }catch (err) {
        alert(err.Description);
    }
}

    var specialKeys = new Array();
    specialKeys.push(8); //Backspace
function IsNumeric(e) {
    var keyCode = e.which ? e.which : e.keyCode;
    var ret = ((keyCode >= 48 && keyCode <= 57 ) || specialKeys.indexOf(keyCode) != -1);
    //document.getElementById("number").style.display = ret ? "none" : "inline";
    return ret;
}
        
function findDistrict(stid){
    $.ajax({ 
        type: 'post',
        url: 'index.php?route=customer/createcustomer/getdistrict&token='+getURLVar('token'),
        data: 'state_id='+stid,
        cache: false,
        success: function(data) {
            $("#edist").html(data);
        }
    });
}
function getXtraFields(RoleId){
     $('#XtraDiv').hide();
     $('#MoAmDiv').hide();
    if(RoleId==3 || RoleId==4){
        $('#XtraDiv').show();
        if(RoleId==4){
            
            $.ajax({ 
                type: 'post',
                url: 'index.php?route=customer/createcustomer/getam&token='+getURLVar('token'),
                data: 'role='+RoleId,
                // dataType: 'json',
                cache: false,
                success: function(data) {
                    
                    $('#MoAmDiv').show();
                    $("#MoAmId").html(data);
                }
            });
        }
        
    } 
} 

    
</script>

