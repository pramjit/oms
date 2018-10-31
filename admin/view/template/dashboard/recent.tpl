<div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>Order Summary</h3>      
      </div>
      <div class="panel-body">
        <div class="well">
        <div class="row">
          <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-date-start">District</label>
                <select name="dist_id" id="dist_id" class="form-control select2-selection select2-selection--multiple"  multiple="multiple" >
                    <option value=''>Select District</option> 
                    <option value=''>Select ALL</option>
                   <?php foreach ($listDISTs as $listDIST) { ?>
                    <option value="<?php echo $listDIST['SID']; ?>"<?php if($listDIST['SID']==$filter_dist) { echo 'selected'; } ?>><?php echo $listDIST['NAME']; ?></option>
               
                  <?php } ?>
                 
                </select>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                 <label class="control-label" for="input-date-start">Party Name</label>
                <select name="store_id" id="store_id" class="form-control select2-selection select2-selection--multiple"  multiple="multiple"  >
                    <option value=''>Select Party Name</option> 
                    <option value=''>Select ALL</option>
                  <?php foreach ($listWSs as $listWS) { ?>
                   
                  <option value="<?php echo $listWS['store_id']; ?>" <?php if($listWS['store_id']==$filter_store ) { echo 'selected'; } ?>><?php echo $listWS['name']; ?></option>
                    
                  <?php } ?>
                </select>
              </div>
            </div>            
            <div class="col-sm-2"> 
                <label class="control-label" for="input-group">&nbsp;</label>
                <button type="button" id="button-filter" class="btn btn-warning pull-right" style="margin-top: 22px;"><i class="fa fa-search"></i> &nbsp; Search</button>
            </div>
          </div>
        </div>
        <div class="table-responsive">

<table width="100%" border="1">
<tr>
<td width="100" class="text-center" style="font-weight:bold"><?php  echo date('F-Y', strtotime('-3 month')); ?></td>
<td width="100" class="text-center" style="font-weight:bold"><?php  echo date('F-Y', strtotime('-2 month')); ?></td>
<td width="100" class="text-center" style="font-weight:bold"><?php  echo date('F-Y', strtotime('-1 month')); ?></td>
<td width="100" class="text-center" style="font-weight:bold"><?php echo date('F-Y'); ?></td>
</tr>
<tr>
<td><table width="100%" border="1">
<tr>
<td width="100" class="text-center">Order</td>
<td width="100" class="text-center">Dilevered</td>
<td width="100" class="text-center">Pending</td>
</tr>
</table></td>
<td><table width="100%" border="1">
<tr>
<td width="100" class="text-center">Order</td>
<td width="100" class="text-center">Dilevered</td>
<td width="100" class="text-center">Pending</td>
</tr>
</table></td>
<td><table width="100%" border="1">
<tr>
<td width="100" class="text-center">Order</td>
<td width="100" class="text-center">Dilevered</td>
<td width="100" class="text-center">Pending</td>
</tr>
</table></td>
<td><table width="100%" border="1">
<tr>
<td width="100" class="text-center">Order</td>
<td width="100" class="text-center">Dilevered</td>
<td width="100" class="text-center">Pending</td>
</tr>
</table></td>
</tr>

<tr>
<td><table width="100%" border="1">
<tr>
<td width="100" height="19px" class="text-center"><?php echo $monf['ordered']; ?></td>
<td width="100" height="19px"  class="text-center"><?php echo $monf['dispatch']; ?></td>
<td width="100" height="19px" class="text-center"><?php echo $monf['Pending']; ?></td>
</tr>
</table></td>
<td><table width="100%" border="1">
<tr>
<td width="100" height="19px"  class="text-center"><?php echo $mons['ordered']; ?></td>
<td width="100" height="19px"  class="text-center"><?php echo $mons['dispatch']; ?></td>
<td width="100" height="19px"  class="text-center"><?php echo $mons['Pending']; ?></td>
</tr>
</table></td>
<td><table width="100%" border="1">
<tr>
<td width="100" height="19px"  class="text-center"><?php echo $mont['ordered']; ?></td>
<td width="100" height="19px"  class="text-center"><?php echo $mont['dispatch']; ?></td>
<td width="100" height="19px"  class="text-center"><?php echo $mont['Pending']; ?></td>
</tr>
</table></td>
<td><table width="100%" border="1">
<tr>
<td width="100" height="19px"  class="text-center"><?php echo $monfr['ordered']; ?></td>
<td width="100" height="19px"  class="text-center"><?php echo $monfr['dispatch']; ?></td>
<td width="100" height="19px"  class="text-center"><?php echo $monfr['Pending']; ?></td>
</tr>
</table></td>
</tr>
</table>

  </div>

      </div>
    </div>
  </div>

<!-------------------------
<div class="col-sm-6">
    
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> <?php echo $heading_title; ?></h3>
     <button type="button" id="button-filter" class="btn btn-warning pull-right" ><i class="fa fa-search"></i> &nbsp; Search</button>
    <br/><br/> 
  
     <select name="dist_id" id="dist_id" class="form-control" style="width:50%">
                    <option value=''>Select District</option> 
                    <option value=''>Select ALL</option>
                   <?php foreach ($listDISTs as $listDIST) { ?>
                    <option value="<?php echo $listDIST['SID']; ?>"<?php if($listDIST['SID']==$filter_dist) { echo 'selected'; } ?>><?php echo $listDIST['NAME']; ?></option>
               
                  <?php } ?>
                 
     </select><br/>
    <select name="store_id" id="store_id" class="form-control" style="width:50%"  >
                    <option value=''>Select Party Name</option> 
                    <option value=''>Select ALL</option>
                  <?php foreach ($listWSs as $listWS) { ?>
                   
                  <option value="<?php echo $listWS['store_id']; ?>" <?php if($listWS['store_id']==$filter_store ) { echo 'selected'; } ?>><?php echo $listWS['name']; ?></option>
                    
                  <?php } ?>
     </select>
 

  </div>
<div class="table-responsive">

<table style="width:100%" border="1">
<tr>
<th class="text-center" style="padding-right:2%"><?php  echo date('F-Y', strtotime('-3 month')); ?></th>
<th class="text-center" style="padding-right:2%"><?php  echo date('F-Y', strtotime('-2 month')); ?></th> 

</tr>
<tr>
<td >
  <table >
        <tr>
            <td width="10%"> Order</td>
            <td width="10%"> Dilevered</td>
            <td width="10%"> Pending</td>
        </tr>
        <tr>
            <td width="10%"><?php echo $monf['ordered']?></td>
            <td width="10%"><?php echo $monf['dispatch']?></td>
            <td width="10%"><?php echo $monf['Pending']?></td>
        </tr>
    </table>        
</td>
<td>
    <table>
        <tr>
            <td width="10%"> Order</td>
            <td width="10%"> Dilevered</td>
            <td width="10%"> Pending</td>
        </tr>
        <tr>
            <td width="10%"><?php echo $mons['ordered']?></td>
            <td width="10%"><?php echo $mons['dispatch']?></td>
            <td width="10%"><?php echo $mons['Pending']?></td>
        </tr>
    </table>        
</td>

</tr>


</table>

      
 <br/><br/>     
 <table style="width:100%" border="1">
<tr>
<th class="text-center" style="padding-right:2%"><?php  echo date('F-Y', strtotime('-1 month')); ?></th>
<th class="text-center" style="padding-right:2%"><?php echo date('F-Y'); ?></th>
</tr>
<tr>

<td>
    <table >
        <tr >
            <td width="10%"> Order</td>
            <td width="10%"> Dilevered</td>
            <td width="10%"> Pending</td>
        </tr>
        <tr>
            <td width="10%"><?php echo $mont['ordered']?></td>
            <td width="10%"><?php echo $mont['dispatch']?></td>
            <td width="10%"><?php echo $mont['Pending']?></td>
        </tr>
    </table>        
</td>
<td>
    <table>
        <tr>
            <td width="10%" > Order</td>
            <td width="10%"> Dilevered</td>
            <td width="10%"> Pending</td>
        </tr>
        <tr>
            <td width="10%"><?php echo $monfr['ordered']?></td>
            <td width="10%"><?php echo $monfr['dispatch']?></td>
            <td width="10%"><?php echo $monfr['Pending']?></td>
        </tr>
    </table>        
</td>
</tr>


</table>     

  </div>
</div>
</div>
  <!------------------------
<div class="col-sm-6"> 
<div class="panel panel-default">
  <div class="panel-heading">
      
    <h3 class="panel-title"><i class="fa fa-shopping-cart"></i>Party Summary</h3>
    
     <br/><br/><select name="mo_id" id="mo_id" class="form-control"  >
                    <option value=''>Select Party Name</option> 
                    <option value=''>Select ALL</option>
                  <?php foreach ($listWSs as $listWS) { ?>
                   
                  <option value="<?php echo $listWS['store_id']; ?>" <?php if($listWS['store_id']==$filter_ws) { echo 'selected'; } ?>><?php echo $listWS['name']; ?></option>
                    
                  <?php } ?>
     </select>
  </div>
  <div class="table-responsive">
<table style="width:100%" border="1">
<tr>
<th class="text-center" style="padding-right:2%"><?php  echo date('F-Y', strtotime('-3 month')); ?></th>
<th class="text-center" style="padding-right:2%"><?php  echo date('F-Y', strtotime('-2 month')); ?></th> 

</tr>
<tr>
<td>
  <table >
        <tr>
            <td width="10%"> Order</td>
            <td width="10%"> Dilevered</td>
            <td width="10%"> Pending</td>
        </tr>
        <tr>
            <td width="10%"> <?php echo $monf['ordered']?></td>
            <td width="10%"><?php echo $monf['dispatch']?></td>
            <td width="10%"><?php echo $monf['Pending']?></td>
        </tr>
    </table>        
</td>
<td>
    <table>
        <tr>
            <td width="10%"> Order</td>
            <td width="10%"> Dilevered </td>
            <td width="10%"> Pending</td>
        </tr>
        <tr>
            <td width="10%"><?php echo $mons['ordered']?></td>
            <td width="10%"><?php echo $mons['dispatch']?></td>
            <td width="10%"><?php echo $mons['Pending']?></td>
        </tr>
    </table>        
</td>

</tr>


</table>

    <br/><br/>  
      
 <table style="width:100%" border="1">
<tr>
<th class="text-center" style="padding-right:2%"><?php  echo date('F-Y', strtotime('-1 month')); ?></th>
<th class="text-center" style="padding-right:2%"><?php echo date('F-Y'); ?></th>
</tr>
<tr>

<td>
    <table >
        <tr>
            <td width="10%"> Order </td>
            <td width="10%"> Dilevered</td>
            <td width="10%"> Pending</td>
        </tr>
        <tr>
            <td width="10%"><?php echo $mont['ordered']?></td>
            <td width="10%"><?php echo $mont['dispatch']?></td>
            <td width="10%"><?php echo $mont['Pending']?></td>
        </tr>
    </table>        
</td>
<td>
    <table >
        <tr>
            <td width="10%"> Order </td>
            <td width="10%"> Dilevered </td>
            <td width="10%"> Pending</td>
        </tr>
        <tr>
            <td width="10%"><?php echo $monfr['ordered']?></td>
            <td width="10%"><?php echo $monfr['dispatch']?></td>
            <td width="10%"><?php echo $monfr['Pending']?></td>
        </tr>
    </table>        
</td>
</tr>


</table>     

  </div>
</div>
</div>-------------->
<script>
    $( document ).ready(function() {
    console.log( "ready!" );
    $('#store_id').select2();
    $('#dist_id').select2();
    
});
</script>
<script type="text/javascript">
    
$('#button-filter').on('click', function() {
    
        url = 'index.php?route=common/dashboard&token=<?php echo $token; ?>';
        var gdist_id = $('#dist_id').val();
        var gdist_nm = $("#dist_id option[value='"+gdist_id+"']").text();
       if(gdist_id!=null)
       {
           url += '&dist_id=' + encodeURIComponent(gdist_id);
           //url += '&mo_nm=' + encodeURIComponent(gmo_nm);
       } 
        var gstore_id = $('#store_id').val();
        var gstore_nm = $("#store_id  option[value='"+gstore_id +"']").text();
       if(gstore_id!=null)
       {
           url += '&store_id=' + encodeURIComponent(gstore_id);
           //url += '&mo_nm=' + encodeURIComponent(gmo_nm);
       } 
	location = url;     
   
});
</script> 