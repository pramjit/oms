<div id="content">
 
  <div class="container-fluid">
    <div class="panel panel-default">
      
      <div class="panel-body">
          <style>
             td,th{border: 1px solid silver;text-align: center; }
              </style>
        <div class="table-responsive">
            <table border="0" cellspacing="0" cellpadding="0" class="table table-bordered" style="width: 100%;">
            <thead>
              <tr>
                <th class="text-left">Sl.No.</th>
                <th class="text-left">Unit</th>
                <th class="text-right">Store Name</th>
                <th class="text-right">Store ID</th>
                <th  class="text-right">Order ID</th>
                <th class="text-right">Grower ID</th>
                <th class="text-right">Name</th>
                <th class="text-right">Date</th>
                <!--<th class="text-right">Amount</th>-->
                <th class="text-right">Tagged-Amount</th>
                <th class="text-right" style="width: 50px;"></th>
              </tr>            </thead>
            <tbody>
              <?php if ($orders) { $aa=1; $total=0; ?>
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>
                <td class="text-left"><?php echo $order['unit']; ?></td>
                <td class="text-right"><?php echo $order['store_name']; ?></td>
                <td class="text-right"><?php echo $order['store_id']; ?></td>
                <td class="text-right"><?php echo $order['order_id']; ?></td>
                <td class="text-right"><?php echo $order['grower_id']; ?></td>
                <td class="text-right"><?php echo $order['farmer_name']; ?></td>
                <td class="text-right"><?php echo $order['date']; ?></td>
                <!--<td class="text-right"><?php echo $order['total']; ?></td>-->
                <td class="text-right"><?php echo number_format((float)$order['tagged'], 2, '.', ''); ?></td>
                <td class="text-right"></td>
              </tr>              <?php 
              $total=$total+$order['tagged'];
              $aa++; } ?>

              
              <tr>
                <td class="text-left"></td>
                <td class="text-left"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"><b>Total : </b></td>
                
                <td class="text-right"><?php echo number_format((float)$total, 2, '.', ''); ?></td>
                <td class="text-right"></td>
              </tr>   
              <?php  } else { ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>

<div style="padding-left: 50px;">
<br/><br/>
<h2>Our Bank Details</h2>
 
A/C Number -
<br/>
Bank Name -
<br/>
IFSC Code -
<br/><br/>
Checked and Submitted by : 


</div>
        
      </div>
    </div>
  </div>
  </div>
