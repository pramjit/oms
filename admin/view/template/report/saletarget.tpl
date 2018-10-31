<?php echo $header; ?><?php echo $column_left;//print_r($results_n); ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Sale Target</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">Sale Target</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <form action = "index.php?route=report/saletarget/saletargetsubmit&token=<?php echo $token; ?>" method="post" enctype="multipart/form-data" id= "filter_form">
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>Sale Target</h3>
        
      </div>
      <div class="panel-body">
        <div class="well">
        <div class="row">
            
            <div class="col-sm-4" style="margin-top:18px;">
                
              <div class="form-group">
                <label class="control-label" for="input-group">Target Description</label>
                
                 <input class="form-control" type="text" name="tdesp"   id="tdesp"  placeholder="Enter Target Description">
              </div>
            </div><br/>
             <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-date-start">To Date</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
                </div>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-date-end">End Date</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-group">Target On</label>
                <select name="tar_id" id="teh_id" class="form-control" onchange="starget(this.value);">
                    <option value=''>Select Target On</option>
                    <option value='0'>Quantity</option>
                    <option value='1'>Amount</option>
                  
                </select>
              </div>
            </div>
            <div class="col-sm-2 pull-right" style="margin-top:20px;">
                <div class="form-group">
                <label class="control-label" for="input-group">&nbsp;</label>
                <button type="button" id="button-submit" class="btn btn-primary " >Submit</button>
                </div>
             </div>
           </div>
              
        </div>


        <div class="table-responsive">
          <table class="table" id="table_add_product">
            <thead>
              <tr >
                <td class="text-left">Product Name</td>
                <td id="pqtyh" class="text-left">Target Quantity</td>
                <td id="prodh" class="text-left">Target Amount</td>
                <td class="text-right"></td>

              </tr>
            </thead>
            <tbody>
                <tr id="PNO0">
                    <td class="col-sm-6 text-left">
                        <div id="prod0" >
                            <select name="prod_name[]"  id="prod_name0" class="form-control">
                                    <option  value="">Select Product</option>
                                    <?php foreach ($prodname as $options) { ?>              
                                    <option value="<?php echo $options['product_id']; ?>"><?php echo $options['name']; ?> ( <?php echo $options['sku']; ?>)</option>
                                    <?php } ?>
                            </select> 
                            <!--------<input type="text" placeholder="Product Name" name="prod_name[]" id="prod_name0" value="" class="form-control m-b" >------>
                        </div>
                    </td>
                    <td class="text-right"><div id="pqty0"><input type="text" placeholder="Quantity" name="qty[]" id="qty0" value="" class="form-control m-b"  
                                                                  onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.keyCode==8 || event.keyCode==46' disabled="disabled"></div></td>
                    <td class="text-right"><div id="pamt0"><input type="text" placeholder="Amount" name="amt[]" id="amt0" value="" class="form-control m-b" 
 onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.keyCode==8 || event.keyCode==46' disabled="disabled"></div></td>
                    <td class="text-right"><button class="btn" type="button" id="bt_add_new0" onclick="add_new_row(0)"><img src="view/image/add.png" ></button></td>
                </tr>
            </tbody>
             <div id="table_add_product"></div>
          </table>
        </div>
      
      </div>
    </div>
  </div>
  </form>
 </div>
<script>
    $( document ).ready(function() {
    console.log( "ready!" );
    $('#mo_id').select2();
    $('#dist_id').select2();
    $('#teh_id').select2();
});
</script>

<script type="text/javascript">
    $('.date').datetimepicker({
      pickTime: false 
   });
$('#button-submit').on('click', function() {
	
        //url = 'index.php?route=report/saletarget/saletargetsubmit&token=<?php echo $token; ?>';
     
        
	$('#filter_form').submit();    
   
});
</script> 
<script type="text/javascript">
$('#button-download').on('click', function() { 
	url = 'index.php?route=report/dailysummaryreport/download_excel&token=<?php echo $token; ?>';
        var gmo_id = $('#mo_id').val();
        var gmo_nm = $("#mo_id option[value='"+gmo_id+"']").text();
       
        url += '&mo_id=' + encodeURIComponent(gmo_id);
        url += '&mo_nm=' + encodeURIComponent(gmo_nm);
        var gdist_id = $('#dist_id').val();
        var gdist_nm = $("#dist_id option[value='"+gdist_id+"']").text();
        if (gdist_id) 
        {
            url += '&dist_id=' + encodeURIComponent(gdist_id);
            url += '&dist_nm=' + encodeURIComponent(gdist_nm);
        }
        var gteh_id = $('#teh_id').val();
        var gteh_nm = $("#teh_id option[value='"+gteh_id+"']").text();
        if (gteh_id) 
        {
            url += '&teh_id=' + encodeURIComponent(gteh_id);
            url += '&teh_nm=' + encodeURIComponent(gteh_nm);
        }
        location = url;    
});
</script>
<script type="text/javascript">

	var PNO = 0; 
	function add_new_row(rn){ 
           
            
               var gsel = $("#teh_id").val();
               if(!gsel)
               {
                   alert('Select Target Type');
                   return false;
               }
               $("#bt_add_new"+PNO).prop("disabled",true);
               $("#bt_remove"+PNO).prop("disabled",true);
        
        PNO++;
        var add_new = '<tr id="PNO'+PNO+'" >';        	
	    add_new +='<td><div id="prod'+PNO+'">';
            add_new +='<select name="prod_name[]"  id="prod_name'+PNO+'" class="form-control">';
            add_new +='<option  value="">Select Product</option><?php foreach ($prodname as $options) { ?>';
            add_new +='<option value="<?php echo $options['product_id']; ?>"><?php echo $options['name']."(".$options['sku'].")"; ?></option>';
            
            add_new +='<?php } ?></select> ';
            add_new +=  '</div></td>';
                        
                        if(gsel=== '0')
                        {
   			add_new +=  '<td><div id="pqty'+PNO+'">';
   			add_new +=  '<input type="text" placeholder="Quantity" name="qty[]" id="qty'+PNO+'" value="" class="form-control m-b"  onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.keyCode==8 || event.keyCode==46">';
   			add_new +=  '</div></td>';
                        add_new +=  '<td><div id="pamt'+PNO+'">';
   			add_new +=  '<input type="text" placeholder="Amount" name="amt[]" id="amt'+PNO+'" value="" class="form-control m-b"  onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.keyCode==8 || event.keyCode==46" disabled="disabled">';
   			add_new +=  '</div></td>';
                        }
                        if(gsel=== '1')
                        {
                        add_new +=  '<td><div id="pqty'+PNO+'">';
   			add_new +=  '<input type="text" placeholder="Quantity" name="qty[]" id="qty'+PNO+'" value="" class="form-control m-b"  onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.keyCode==8 || event.keyCode==46" disabled="disabled">';
   			add_new +=  '</div></td>';
                        add_new +=  '<td><div id="pamt'+PNO+'">';
   			add_new +=  '<input type="text" placeholder="Amount" name="amt[]" id="amt'+PNO+'" value="" class="form-control m-b"  onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.keyCode==8 || event.keyCode==46">';
   			add_new +=  '</div></td>';
                        }
                        
			add_new +=  '<td>';
			add_new +=  '<button class="btn pull-right " type="button" id="bt_add_new'+PNO+'" onclick="add_new_row('+PNO+')"><img src="view/image/add.png"></button>';
			add_new +=  '</td>';
			add_new +=  '<td>';
			add_new +=  '<button class="btn pull-right" type="button" id="bt_remove'+PNO+'" onclick="remove_row('+PNO+')"><img src="view/image/delete.png"></button>';
			add_new +=  '</td>';
                        add_new +=  '</tr>';
                       
			 
			$('#table_add_product > tbody').append(add_new);
                        $("#teh_id").prop("disabled",true);
                }

//*******************REMOVE ROW*****************************//
function remove_row(rno){
    //alert('gdasjh');
	var arno=rno-1;
        //alert (arno);
        if(arno=='0')
        {
            $("#teh_id").prop("disabled",false);
        }
        
	swal({
            title: "Are you sure?",
    	    text: "Your will not be able to recover this record!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel it!",
            closeOnConfirm: false,
            closeOnCancel: false },
            function (isConfirm) {
            						if (isConfirm) {
                            		$('#PNO'+rno).remove();
                            		$("#bt_add_new"+arno).prop("disabled",false);
                            		$("#bt_remove"+arno).prop("disabled",false);
                            		PNO--;
                            		swal("Deleted!", "Your record has been deleted.", "success");
                        			}
									else{
                            		swal("Cancelled", "Your record is safe :)", "error");
                        			}
                    			});
}


</script>
<script>
    function starget(t)
    {
        //alert(t);
        if(t==0)
        {
           document.getElementById('amt0').disabled = true;
           document.getElementById('qty0').disabled = false;

        }
        else
        {
        document.getElementById('qty0').disabled = true;
        document.getElementById('amt0').disabled = false;
        }
   }
</script>

<?php echo $footer; ?>