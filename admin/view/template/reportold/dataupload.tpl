<?php echo $header; ?><?php echo $column_left;//print_r($results_n); ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Data Upload</h1>
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
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>Data Upload</h3>
        
      </div>
      <div class="panel-body">
        <div class="well">
        <div class="row">
            
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-group">File Upload</label>
                
                <input type="file" class="form-control" name="file" id="file"  required />
              </div>
            </div><br/>
             
            <div class="col-sm-2 pull-right">
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
                <td class="text-left">State</td>
                <td id="pqtyh" class="text-left">District</td>
                <td id="prodh" class="text-left">Tehsil</td>
                <td class="text-right">Block</td>
                <td class="text-right">Village</td>

              </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                   
                </tr>
            </tbody>
      
          </table>
        </div>
      
      </div>
    </div>
  </div>
  </form>
 </div>


<?php echo $footer; ?>