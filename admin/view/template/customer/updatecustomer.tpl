<?php 
echo $header; 
echo $column_left;
?>

<div id="content">
    <div class="page-header">
      <div class="container-fluid">
        <h1>Employee / User List:</h1>
        <ul class="breadcrumb">
          <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>">Update Employee</a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
    
    <div class="container-fluid">
    
        <div class="well" style="padding: 30px 10px 10px 10px !important;">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    
                    <input type="text" name="emp_name" id="emp_name" value="" placeholder="Search By Name"  class="form-control" />                  
                </div>
            </div> 
            <div class="col-sm-2"> 
                
                <button type="button" id="button-filter" class="btn btn-warning form-control"><i class="fa fa-search"></i> &nbsp; Search</button>
            </div>
            <div class="col-sm-2"> 
                
                <!--<button type="button" id="button-download" class="btn btn-primary form-control"> <i class="fa fa-download"></i> &nbsp; Download</button>-->
          </div>
        </div>
    </div>

        <div class="panel panel-default">
        <div class="panel-body">
            <table class="table table-bordered table-responsive elist">
                <thead>
                    <tr style="background: #1e91cf; color: #ffffff;">
                      <th class="text-left">#SNO.</th>
                      <th class="text-left">USER_ID/MOBILE</th>
                      <th class="text-left">USER_NAME</th>                
                      <th class="text-left">USER_ROLE</th>
                      <th class="text-left">USER_STATE</th>
                      <th class="text-left">USER_DISTRICT(S)</th>
                      <th class="text-left">ACTION</th>
                 </tr>
                </thead>
                <tbody>
                    
                    <?php $xno=1;
                    foreach($eList as $EL){ 
                    ?>
                    <tr>
                        <td><?php echo $xno; ?></td>
                        <td><?php echo $EL['EMP_UID']; ?></td>
                        <td><?php echo $EL['EMP_NAME']; ?></td>
                        <td><?php echo $EL['EMP_GROUP']; ?></td>
                        <td><?php echo $EL['EMP_STATE']; ?></td>
                        <td><?php echo $EL['EMP_DIST']; ?></td>
                        <?php
                        echo '<td onclick="empDtls('.$EL['EMP_ID'].');"> <span class="form-control btn btn-warning"><i class="fa fa-edit"></i> &nbsp; EDIT</span></td>';
                        ?>
                    </tr>
                    <?php $xno++;}?>
                </tbody>
            </table>
        </div>
    </div>
  </div>
 </div>
<script src="view/javascript/common.js" type="text/javascript"></script>


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>
<script>
$(document).ready(function() {
    $('.elist').DataTable();
} );
</script>]


<script type="text/javascript">
function empDtls(id) {
	//alert(id);
        url = 'index.php?route=customer/updatecustomerdtl&UniId='+id+'&token=<?php echo $token; ?>';
        
	location = url;     
   
}
</script> 


<script type="text/javascript">
$('#button-download').on('click', function() { 
	url = 'index.php?route=customer/updatecustomer/download_excel&token=<?php echo $token; ?>';
        var gfilter_data = $('#emp_name').val();
             
        url += '&filter_data=' + encodeURIComponent(gfilter_data);
        
        
        location = url;    
});
</script>
<script type="text/javascript">
   
$('#button-filter').on('click', function() {
	
        url = 'index.php?route=customer/updatecustomer&token=<?php echo $token; ?>';
        var gfilter_data = $('#emp_name').val();
             
        url += '&search=' + encodeURIComponent(gfilter_data);
        
        
        location = url;  
	
});
</script> 

<?php echo $footer; ?>