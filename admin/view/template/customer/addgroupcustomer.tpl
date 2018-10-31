
 <?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button   id="button_save" form="form-backup" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-default"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $addgroupcustomer; ?>" method="post" id="form-addgroupcustomer" class="form-horizontal">
            
            <div class="form-group required">
              <label class="col-sm-2 control-label" >Customer Group Name</label>
              <div class="col-sm-4">
              <input class="form-control" type="text" id="Group_Name" onchange="clear_Group_Name()" name="group_name"  placeholder="Enter Customer Group Name"/>
              <p id="Group_Name_p" style="display:none;color:red;">Required customer group Name</p>
              </div>
            </div>
            
            <div class="form-group required">
              <label class="col-sm-2 control-label" >Add Level</label>
              <div class="col-sm-4">
              <select id="add_level" onchange="clear_add_level()" name="add_level" class="form-control">
                 <option  value=""> Select level </option>
                 <?php foreach ($getdata as $options) { ?>
                   <?php if ($options['id'] == $user_group_id) { ?>
                   <option value="<?php echo $options['_id']; ?>" selected="selected"><?php echo $options['name']; ?></option>
                   <?php } else { ?>
                   <option value="<?php echo $options['id']; ?>"><?php echo $options['name']; ?></option>
                   <?php } ?>
                   <?php } ?>
               </select>
               <p id="add_level_p" style="display:none;color:red;">Required add level</p>
              </div>
            </div>
     
        </form>
     
      </div>
    </div>
    
    
   
    <div class="panel panel-default">
        <div class="panel-body">
           
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center">SN</td>
                  <td style="width: 1px;"class="text-center">Group Name</td>
                  <td style="width: 1px;"class="text-center">Level</td>
                  <td style="width: 1px;"class="text-center">Edit</td>
           
                </tr>
              </thead>
              <tbody>
                <?php if ($groupnameshow) { ?>
                <?php foreach ($groupnameshow as $product) { ?>
               
                <tr>
                  
                  
                  <td class="text-left"><?php echo $product['id']; ?></td>
                  <td class="text-left"><?php echo $product['name']; ?></td>
                  <td class="text-left"><?php echo $product['level']; ?></td>
                  <td class="text-right"><a href="<?php echo $product['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        
        </div>
       </div>
    
    
  </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">   
 $('#button_save').on('click', function() {
   
 if ($('#Group_Name').val().length ===0) {
     $('#Group_Name_p').show();
 }
 
  else if ($('#add_level').val().length ===0) {
     $('#add_level_p').show();
 }
 
 
 else {
     var name=document.getElementById('Group_Name').value;
     var level=document.getElementById('add_level').value;
     
	$.ajax({ 
		type: 'post',
		url: 'index.php?route=customer/addgroupcustomer/addGroup&token='+getURLVar('token'),
                data: {name:name,addlevel:level},
        dataType: 'json',
		cache: false,
		beforeSend: function() {
			$('#button-nation').button('loading');
		},
		complete: function() {
			$('#button-nation').button('reset');
		},		
		success: function(json) {
			           
            if (json['redirectf']) {
                location = json['redirectf'];
            } else if (json['error']) {
                if (json['error']['warning']) {
                 alert(json['error']['warning']);                    
                }           
            }
		}
               
	});
 }
 });
 function clear_Group_Name()
{
 $('#Group_Name_p').hide();
}

function clear_add_level()
{
 $('#add_level_p').hide();
}
</script>