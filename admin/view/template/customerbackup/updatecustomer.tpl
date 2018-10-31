<?php echo $header; ?><?php echo $column_left;//print_r($results_n); ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Update Employee</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">Update Employee</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
    
    <div class="container-fluid">
    <div class="panel panel-default">    
    <div class="panel-body">
    <div class="well">
    <div class="row">
       
        
        <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>
    
          <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-date-start"></label>
                <div class="input-group date">
                  <input type="text" name="filter_dat" id="filter_dat" value="" placeholder="Search"  class="form-control" />                  
                </div>
              </div>
          </div> 
          <div class="col-sm-2"> 
                <label class="control-label" for="input-group">&nbsp;</label>
                <button type="button" id="button-filter" class="btn btn-warning pull-right" style="margin-top: 22px;"><i class="fa fa-search"></i> &nbsp; Search</button>
          </div>
    </div></div></div></div></div>
    
    
  <div class="container-fluid">
    <div class="panel panel-default">
    
      <div class="panel-body">
       <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left" width="10%">S. No.</td>
                <td class="text-left">User Id</td>
                <td class="text-left">Name</td>                
                <td class="text-left">Role</td>
                <td class="text-left">State</td>
                <td class="text-left">District</td>
                <td class="text-left"></td>
             </tr>
            </thead>
            <tbody>
              <?php $total=0; if ($results) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($listEMPDs as $emp) {//print_r($order); ?>
            <tr>
                <td class="text-left"><?php echo $aa; ?></td>
                <td class="text-left"><?php echo $emp['User_id']; ?></td>
                <td class="text-left"><?php echo $emp['firstname']; ?></td>
                <td class="text-left"><?php if($emp['customer_group_id']=='1')
                                               echo "Director";
                                            elseif($emp['customer_group_id']=='2') 
                                            {
                                               echo "Sales Head"; 
                                            }
                                             elseif($emp['customer_group_id']=='3') 
                                            {
                                               echo "Area Manager"; 
                                            }
                                             elseif($emp['customer_group_id']=='4') 
                                            {
                                               echo "Marketing Officer"; 
                                            }
                                             elseif($emp['customer_group_id']=='5') 
                                            {
                                               echo "Asst Area Incharge"; 
                                            }
                                            elseif($emp['customer_group_id']=='6') 
                                            {
                                               echo "Supply Chain"; 
                                            }
                                            elseif($emp['customer_group_id']=='7') 
                                            {
                                               echo "Sales Executive"; 
                                            }
                                            elseif($emp['customer_group_id']=='8') 
                                            {
                                               echo "Administrator"; 
                                            }
                                            elseif($emp['customer_group_id']=='9') 
                                            {
                                               echo "MIS"; 
                                            }
                                            elseif($emp['customer_group_id']=='10') 
                                            {
                                               echo "Whole Sales Person"; 
                                            }
                                      ?>
                </td>
                <td class="text-left"><?php echo $emp['State']; ?></td>
                <td class="text-left"><?php echo $emp['district']; ?></td>
                <td class="text-left"><button type="button" id="button-filter" onclick="empdetl(<?php echo $emp['User_id']; ?>);" class="btn btn-primary pull-right" >&nbsp; Edit</button></td>
            </tr>
              <?php $total=$total+$order['total']; $aa++; } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right">
           <!--<span style="font-weight: bold;">Total Amount : <?php echo $total; ?></span> <br/>-->

         <?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
 </div>
<script>
    $( document ).ready(function() {
    console.log( "ready!" );
    $('#mo_id').select2();
    $('#dist_id').select2();
    $('#teh_id').select2();
});
</script>]


<script type="text/javascript">
    $('.date').datetimepicker({
      pickTime: false 
   });
function empdetl(id) {
	//alert(id);
        url = 'index.php?route=customer/updatecustomerdtl&user_id='+id+'&token=<?php echo $token; ?>';
        
	location = url;     
   
}
</script> 
<script type="text/javascript">
$('#button-download').on('click', function() { 
	url = 'index.php?route=customer/updatecustomer/download_excel&token=<?php echo $token; ?>';
        var gfilter_data = $('#filter_dat').val();
             
        url += '&filter_data=' + encodeURIComponent(gfilter_data);
        
        
        location = url;    
});
</script>
<script type="text/javascript">
   
$('#button-filter').on('click', function() {
	
        url = 'index.php?route=customer/updatecustomer&token=<?php echo $token; ?>';
        var gfilter_data = $('#filter_dat').val();
             
        url += '&filter_dat=' + encodeURIComponent(gfilter_data);
        
        
        location = url;  
	
});
</script> 

<?php echo $footer; ?>