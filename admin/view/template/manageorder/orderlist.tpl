<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Manage Indents</h1>

    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>Approved Indent List</h3>
	<?php if (isset($_SESSION['delete_success_message'])) {		?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['delete_success_message']; unset($_SESSION['delete_success_message']);?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
        <?php if (isset($_SESSION['input_error'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['input_error']; unset($_SESSION['input_error']);?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
        
        <?php if (isset($_SESSION['success'])) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i><?php echo $_SESSION['success']; unset($_SESSION['success']);?>
      <button type="button" class="close" data-dismiss="alert">&times</button>
    </div>
    <?php } ?>
      </div>
      <div class="panel-body">
          <!-- FILTER START -->
  <!--  <div class="well">
        <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label" for="input-name">SAP REFERENCE ID</label>
                 <input type="text" name="filter_name_sap" id="filter_name_sap" value="<?php echo $fi_sap_id_name; ?>"placeholder="SAP Refeference Id" id="input-name" class="form-control" />
                <input type="hidden" name="filter_name_sap_id" id="filter_name_sap_id"  value="<?php echo $fi_sap_id; ?>"/>
            </div>              
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label" for="input-name">SELECT WHOLESALER</label>
                <input type="text" name="filter_name_wh" name="filter_name_wh" value="<?php echo $fi_sto_id_name; ?>" placeholder="Select Wholesaler" id="input-name" class="form-control" />
                <input type="hidden" name="filter_name_wh_id" id="filter_name_wh_id" value="<?php echo $fi_sto_id; ?>"/>
            </div>              
        </div>
              
         <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label" for="input-name">SELECT PRODUCT</label>
                <input type="text" name="filter_name" id="filter_name" value="<?php echo $fi_pro_id_name; ?>" placeholder="Select Product"  class="form-control" />
                <input type="hidden" name="filter_name_id"  value="<?php echo $fi_pro_id; ?>" id="filter_name_id"/>
            </div>              
        </div>
        <div class="col-sm-3" style="margin-top: 22px!important">
            <button type="button" id="button-filter" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;<?php echo $button_filter; ?></button>
            <button type="button" id="disp_order" class="btn btn-warning pull-right"><i class="fa fa-file-text"></i> &nbsp;Dispatch Order</button>
        </div>-->
            <!-- Filter End -->
        </div>
        </div>
        <div class="table-responsive">
            <?php  ?> 
          <table class="table table-bordered table-hover">
              <thead>
                <tr style="background: #515151; color: #ffffff !important;">
                    <td class="text-left">SNO</td>
                    <td class="text-left">INDENT NO</td>
                    <td class="text-left">INDENT DATE</td>
                    <td class="text-left">MARKETING OFFICER</td>
                    <td class="text-left">WHOLESALER</td>
                    <td class="text-left">INDENT TOTAL QUANTITY</td>
                    <td class="text-left">ACTION</td>
		</tr>
              </thead>
              <tbody>
                <?php if($order_list){
                $a='1';
		foreach($order_list as $order){
                ?>
                    <tr>
                        <td class="text-left"><?php echo $a; ?></td>
                        <td class="text-left"><?php echo $order['IND_ID'];?></td>
                        <td class="text-left"><?php echo $order['IND_DATE'];?></td>
                        <td class="text-left"><?php echo $order['MO_NAME'];?></td>
                        <td class="text-left"><?php echo $order['WS_NAME'];?></td>
                        <td class="text-left"><?php echo round($order['TOT_IND_QTY']);?></td>
                        <td class="text-left btn-primary" onclick="showorderlist(<?php echo $order['IND_ID'];?>);">EDIT/UPDATE</td>
                    </tr>			
		<?php
                    $a++;
                    }
		}
                ?>                      
              </tbody>
            </table>
        </div>
        <!-- #####**********#####Supply Chain Modal Start#####*******#####-->
    <div class="container">
    <div class="modal fade" id="supModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div id="supModalLoad" style="margin: 0px auto; width: 200px; height: 100px;"><img src="view/image/loader.jpg"></div>
        <!-- Modal content-->
         <form action="index.php?route=manageorder/orderlist/submitData&token=<?php echo $token;?>" method="post" id="filter_form" class="form-horizontal">
        <div class="modal-content">
       
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-center font-bold">PRODUCT LIST OF INDENT NO :&nbsp;&nbsp;<span id="indno"></span></h4>
        </div>
        <div class="modal-body"><!-- Load Content --></div>
        <div class="modal-footer">
            <div class="form-group">
                <div class="col-md-6"></div>
            <div class="col-md-3">
                <button type="button" class="btn btn-primary form-control" id="sub_sup_data" onclick="updateData();">Submit</button>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-default form-control" data-dismiss="modal">Cancel</button>
            </div>
            </div>
        </div>
       
        </div>
              </form>
        <!--<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>-->
      </div>
      
    </div>
  </div>
  
</div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
$( document ).ready(function() {
    $("#supModalLoad").hide();
});
</script>
<script type="text/javascript">
    function showorderlist(indid){
        $.ajax({
        type: "POST",
        url: "index.php?route=manageorder/orderlist/prodlist&token=<?php echo $token;?>",
        data: "indid="+indid,
        dataType: "text",
        success: function( data ) 
            {
                $('#indno').html(indid);
                $('#supModal').modal();
                $(".modal-body").html(data);
                $(".modal-content").show();
            }
        }); 
    }
</script>
<script type="text/javascript">
 function add_new_row(rn) { 
      
            var chk = updateData_add();
            if(chk==true){
            var PNO=0;    
//            $("#bt_add_new"+PNO).prop("disabled",true);
//            $("#bt_remove"+PNO).prop("disabled",true);
//        
            PNO++;
            var add_new = '<tr id="PNO'+PNO+'" >';        	
	    add_new +='<td><div id="prod'+PNO+'">';
            add_new +='<select name="PRO_ID[]" id="productname" onchange="checkProduct(this.value);" id="prod_name'+PNO+'" class="form-control">';
            add_new +='<option  value="">Select Product</option>\n\
                <?php foreach ($prodname as $options) 
                {  
                    
                    ?>';
            add_new +='<option value="<?php echo $options['product_id']; ?>"><?php echo $options['model']."(".$options['sku'].")"; ?></option>';
            add_new +='<?php } ?></select> ';
            add_new +=  '</div></td>';
            
            add_new +=  '<td><div id="pqty'+PNO+'">';
            add_new +=  '<input type="text" placeholder=" Product Quantity" name="qty[]" id="qty'+PNO+'" value="" class="form-control m-b"  onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.KeyCode==110 || event.keyCode==8 || event.keyCode==46 ">';
            add_new +=  '</div></td>';
           // add_new +=  '<td><div id="pamt'+PNO+'">';
//            add_new +=  '<input type="text" placeholder="Wholeseller" name="wholeseller[]" id="wholeseller'+PNO+'" value="" class="form-control m-b"  onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.keyCode==8 || event.keyCode==46" disabled="disabled">';
//            add_new +=  '</div></td>';                     
                        
            add_new +=  '<td>';
            add_new +=  '<button class="btn btn-success" type="button" id="bt_add_new'+PNO+'" onclick="add_new_row('+PNO+')">+</button>';
            add_new +=  '</td>';
            add_new +=  '<td>';
            add_new +=  '<button class="btn btn-danger" type="button" id="bt_remove'+PNO+'" onclick="remove_row(this)">X</i></button>';
            add_new +=  '</td>';
            add_new +=  '</tr>';
                       
			 
        $('#table_add_product > tbody').append(add_new);
    }
        }
        function  remove_row(pno)
        {
            
            var table = pno.parentNode.parentNode.parentNode;
            var rowCount = table.rows.length;
            var row = pno.parentNode.parentNode; 
            row.parentNode.removeChild(row);
        }
        
        function delterec(pid,oid)
        {
         
        $.ajax({
        type: "GET",
        url: url = "index.php?route=manageorder/orderlist/insertdeletedata&token=<?php echo $token;?>",
        data:{"pid": pid, "oid": oid},
        dataType: "text",
       
        success: function(data)
             {
                  
                  showorderlist(data);
                 
             }
        }); 
  
        }
         function checkProduct(prodid)
         {  //alert(prodid);
             var cbc = document.getElementsByName('PRO_ID[]'); 
            var btnid= document.getElementById("bt_add_new0"); 
             
             var result =[]; 
             for(var i=0; i<cbc.length-1; i++)  
            {
                result.push( cbc[i].value); 
            }
            //alert(result);
            
            for(var j=0; j<result.length; j++) 
            {
                if(result[j]==prodid)
                {
                   
                     //
                   alert("Product Already Present In Product List : " +j);
                   $("#productname").prop('selectedIndex',0);
//                    
//                   
//                    
//                      result[j].freeze(j);
//                   document.getElementById("bt_add_new0");
//                  alert(btnid);
               //bt_add_new0

                    //prodid.setAttribute('style', 'display:none');
                    return false;
                }
            }
             //console.log(result);
           // alert(result);
             
         }
         
         
         
         function updateData()
         {
        
             var cbc = document.getElementsByName('PRO_ID[]'); 
             var cbc1 = document.getElementsByName('qty[]'); 
            // var pid=document.get
           
           var result =[]; 
             for(var i=0; i<cbc.length; i++)  
            {
                result.push( cbc[i].value); 
            }
             var result1 =[]; 
             for(var i=0; i<cbc1.length; i++)  
            {
                result1.push( cbc1[i].value); 
            }
            
            //alert(result.length);
           // alert(result1.length);
            for(var j=0; j<result1.length; j++) 
            {
                
                if(result[j]==0)
                {
                  var h=parseInt(j+1);
                  alert ("Please Select The Product Name : " +h);   
                    // document.getElementById("bt_add_new0");
                  return false;
                }
                if(result1[j]==0)
                {
                    var h=parseInt(j+1);
                    alert ("Please fill The Quantity : " +h);       
                     // document.getElementById("bt_add_new0").disabled;
                  return false;
                }
            }
            $('#filter_form').submit();
         }
         function updateData_add()
         {
        
             var cbc = document.getElementsByName('PRO_ID[]'); 
             var cbc1 = document.getElementsByName('qty[]'); 
            // var pid=document.get
           
           var result =[]; 
             for(var i=0; i<cbc.length; i++)  
            {
                result.push( cbc[i].value); 
            }
             var result1 =[]; 
             for(var i=0; i<cbc1.length; i++)  
            {
                result1.push( cbc1[i].value); 
            }
            
            //alert(result.length);
           // alert(result1.length);
            for(var j=0; j<result1.length; j++) 
            {
                
                if(result[j]==0)
                {
                  var h=parseInt(j+1);
                  alert ("Please Select The Product Name : " +h);   
                    // document.getElementById("bt_add_new0");
                  return false;
                }
                if(result1[j]==0)
                {
                    var h=parseInt(j+1);
                    alert ("Quantity Is Empty : "+h);       
                     // document.getElementById("bt_add_new0").disabled;
                  return false;
                }
            }
            return true;
         }
        
    </script>
  