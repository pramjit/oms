<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-user" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>Cash Deposit</h1>
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
      <?php if ($success) {  ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> Cash Deposit</h3>
      </div>
      <div class="panel-body">

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-user" class="form-horizontal">
            <input type="hidden" name="logged_user" value="<?php echo $logged_user; ?>" />

                    
          <div class="form-group required">
                <label class="col-sm-2 control-label" style="float: left;" for="input-username">Select Store</label>
            <div class="col-sm-10">
                <div class="input-group col-sm-12">
              
                <select required name="filter_store" id="input-store" class="form-control" onchange="return get_stores_data(this.value);">
                      <option selected="selected" value="">SELECT STORE</option>
                  <?php foreach ($stores as $store) {   ?>
                  <?php if ($store['store_id'] == $filter_store) {
                      if($filter_store!=""){
                      ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                      <?php }} else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>

                </div>
<div class="col-sm-8" style="font-weight: bold;padding-top: 9px;font-size: 18px;" id="for_current_credit" ></div>
             </div>
          </div>            
          

           <div class="form-group required">
                <label class="col-sm-2 control-label" style="float: left;" for="input-username">Select Transaction Type</label>
            <div class="col-sm-10">
                <div class="input-group col-sm-12">
              
                <select required name="filter_trans_type"  class="form-control" >
                      <option selected="selected" value="">SELECT TYPE</option>
                      <?php  foreach ($TransactionTypes as $TransactionType) {   ?>
                  
                  
                  <option value="<?php echo $TransactionType['bank_id']; ?>"><?php echo $TransactionType['bank']; ?></option>
                  
                  <?php } ?>
                  
                </select>

                </div>
             </div>
          </div>


            <div class="form-group required">
                <label class="col-sm-2 control-label" style="float: left;" for="input-username">Deposit Date</label>
            <div class="col-sm-10">
                <div class="input-group date">
              <input type="text" name="deposit_date" required data-date-format="YYYY-MM-DD"  placeholder="Deposit Date" id="input-deposit_date" class="form-control" />
              <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
                </div>
                  <?php if ($error_deposit_date) { ?>
              
              <div class="text-danger"><?php echo $error_deposit_date; ?></div>
              <?php } ?>
            </div>
          </div>
          

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-transaction_number">Transaction Number</label>
            <div class="col-sm-10">
              <input type="text" required name="transaction_number" value="<?php echo $transaction_number; ?>" placeholder="Transaction Number" id="input-transaction_number" class="form-control" />
              <?php if ($error_transaction_number) { ?>
              <div class="text-danger"><?php echo $error_transaction_number; ?></div>
              <?php } ?>
            </div>
          </div>
            <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-transaction_number">Deposit Amount</label>
            <div class="col-sm-10">
              <input type="text" required name="deposit_amount" value="" placeholder="Deposit Amount" id="input-transaction_number" class="form-control" />
              <?php if ($error_transaction_number) { ?>
              <div class="text-danger"></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-branch_code">Branch Code</label>
            <div class="col-sm-10">
              <input type="text" required name="branch_code" value="<?php echo $branch_code; ?>" placeholder="Branch Code" id="input-branch_code" class="form-control" />
              <?php if ($error_branch_code) { ?>
              <div class="text-danger"><?php echo $error_branch_code; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-branch_location">Branch Location</label>
            <div class="col-sm-10">
              <input type="text" required name="branch_location" value="<?php echo $branch_location; ?>" placeholder="Branch  Location" id="input-branch_location" class="form-control" />
              <?php if ($error_branch_location) { ?>
              <div class="text-danger"><?php echo $error_branch_location; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-remarks ">Remarks </label>
            <div class="col-sm-10">
              <textarea name="remarks" rows="5" placeholder="Remarks" id="input-remarks " class="form-control"><?php echo $remarks; ?></textarea>
            </div>
          </div>
            
            <input type="hidden" name="verified_by" id="verified_by" value="<?php ?>" />
           
        </form>
      </div>
    </div>
  </div>
</div>

//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
<script type="text/javascript">


function get_stores_data(store_id)
{
        $.ajax({
            url: 'index.php?route=cash/verify/get_store_data&token=<?php echo $token; ?>&store_id=' +  encodeURIComponent(store_id),
            //dataType: 'json',
            success: function(json) 
            {

             document.getElementById("for_current_credit").innerHTML='Current Credit - Rs. '+json; 
            }
        });
    
    return false;
}


</script>

<?php echo $footer; ?> 