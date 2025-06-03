<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="widget relative" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('quick_stats'); ?>">
    <div class="widget-dragger"></div>
    <div class="row">
	
        <?php
         $initial_column = 'col-lg-4';
         
         ?>
        <?php if (is_staff_member()) { ?>
		<div class="quick-stats-invoices col-xs-12 col-md-6 col-sm-6 <?php echo e($initial_column); ?> tw-mb-2 sm:tw-mb-0">
            <div class="top_stats_wrapper">
			
			<?php
                  if (!is_admin()) {$this->db->where('assigned', get_staff_user_id());}
                    
                    $this->db->select("COUNT(CASE WHEN status = 2 AND is_deal = 0  THEN 1 END) AS unassign_lead, COUNT(CASE WHEN status = 3 AND is_deal = 0 THEN 1 END) AS assign_lead, COUNT(CASE WHEN status = 4 AND is_deal = 0 THEN 3 END) AS junk_lead, COUNT(CASE WHEN status = 1 AND is_deal = 0  THEN 1 END) AS hot_lead");
                    
					
                    $row = $this->db->get(db_prefix() . 'leads')->row();
			
					//echo $this->db->last_query();echo $row->new_lead;exit;
                  ?>
               
				<div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2">
                        <i class="fa fa-tty menu-icon fa-2x"></i>
                        <span class="tw-truncate tw-text-xl">&nbsp;&nbsp;Leads Status</span>
                    </div>
                    <span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0">
<?php echo $row->hot_lead;?> / <?php echo ($row->unassign_lead + $row->assign_lead + $row->junk_lead + $row->hot_lead);?>
                    </span>
                </div>
				
				 <script type="text/javascript">

      // Load Charts and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Draw the pie chart for Sarah's pizza when Charts is loaded.
      google.charts.setOnLoadCallback(drawLeadsChart);

     

      // Callback that draws the pie chart for Sarah's pizza.
      function drawLeadsChart() {

        // Create the data table for Sarah's pizza.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Hot', <?php echo $row->hot_lead;?>],
          ['Assign', <?php echo $row->assign_lead;?>],
		  ['UnAssign', <?php echo $row->unassign_lead;?>],
          ['Junk', <?php echo $row->junk_lead;?>]
          
        ]);

        // Set options for Sarah's pie chart.
        var options = {title:'Leads stats by Status',
		               width:300,
					   height:300,
					   legend:'bottom',
					   colors: ['#008000', '#FEBE10','#00BFFF','#FF0000']
					   };
                       
                       

        // Instantiate and draw the chart for Sarah's pizza.
        var chart = new google.visualization.PieChart(document.getElementById('Leads_chart_div'));
        chart.draw(data, options);
      }


    </script>
                <div id="Leads_chart_div" style="border: 1px solid #ccc"></div>
                
            </div>
        </div>
        <?php } ?>
		
		
		<?php if (is_staff_member()) { ?>
		<div class="quick-stats-invoices col-xs-12 col-md-6 col-sm-6 <?php echo e($initial_column); ?> tw-mb-2 sm:tw-mb-0">
            <div class="top_stats_wrapper">
			
			<?php
                  if (!is_admin()) {$this->db->where('assigned', get_staff_user_id());}
                    
                    $this->db->select("COUNT(CASE WHEN is_deal = 1 AND deal_status=1  THEN 1 END) AS new_lead, COUNT(CASE WHEN is_deal = 1 AND deal_status=2 THEN 1 END) AS doc_lead, COUNT(CASE WHEN is_deal = 1 AND deal_status=2 THEN 3 END) AS uw_lead, COUNT(CASE WHEN is_deal = 1 AND deal_status= 4  THEN 1 END) AS invoice_lead");
                    
					
                    $row = $this->db->get(db_prefix() . 'leads')->row();
			
					//echo $this->db->last_query();echo $row->new_lead;exit;
                  ?>
               
				<div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2">
                        <i class="fa-solid fa-handshake menu-icon fa-2x"></i>
                        <span class="tw-truncate tw-text-xl">&nbsp;&nbsp;Deal Status</span>
                    </div>
                    <span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0">
<?php echo $row->invoice_lead;?> / <?php echo ($row->new_lead + $row->doc_lead + $row->uw_lead + $row->invoice_lead);?>
                    </span>
                </div>
				
				 <script type="text/javascript">

      // Load Charts and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Draw the pie chart for Sarah's pizza when Charts is loaded.
      google.charts.setOnLoadCallback(drawDealChart);

     

      // Callback that draws the pie chart for Sarah's pizza.
      function drawDealChart() {

        // Create the data table for Sarah's pizza.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Hot', <?php echo $row->new_lead;?>],
          ['Doc', <?php echo $row->doc_lead;?>],
          ['UW', <?php echo $row->uw_lead;?>],
          ['Invoice', <?php echo $row->invoice_lead;?>]
          
        ]);

        // Set options for Sarah's pie chart.
        var options = {title:'Deal stats by Status',
		               width:300,
					   height:300,
					   legend:'bottom',
					   colors: ['#00BFFF','#FFD700', '#FEBE10','#008000']
					   };
                       
                       

        // Instantiate and draw the chart for Sarah's pizza.
        var chart = new google.visualization.PieChart(document.getElementById('Deal_chart_div'));
        chart.draw(data, options);
      }


    </script>
                <div id="Deal_chart_div" style="border: 1px solid #ccc"></div>
                
            </div>
        </div>
        <?php } ?>
		
		
        <?php if (is_staff_member()) { ?>
        <div class="quick-stats-leads col-xs-12 col-md-6 col-sm-6 <?php echo e($initial_column); ?> tw-mb-2 sm:tw-mb-0">
            <div class="top_stats_wrapper">
                <?php
                  
                    if (!is_admin()) {$this->db->where('addedfrom', get_staff_user_id());}
                    
                    $this->db->select("COUNT(CASE WHEN status = 1 and approver_status=1  THEN 1 END) AS new_count, COUNT(CASE WHEN status = 2 and approver_status=1 THEN 1 END) AS process_count, COUNT(CASE WHEN status = 2 and approver_status=2 THEN 1 END) AS success_count");
                    
					
                    $row = $this->db->get(db_prefix() . 'invoices')->row();
			
					//echo $this->db->last_query();echo $row->new_count;exit;
                   
                  ?>
                <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate  tw-my-2">
                        <i class="fa-solid fa-receipt menu-icon fa-2x"></i>
                        <span class="tw-truncate tw-text-xl">&nbsp;&nbsp;Invoice Status</span>
                    </div>
                    <span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0">
<span title="Success"><?php echo $row->success_count;?></span> / <span title="Total"><?php echo ($row->success_count + $row->new_count + $row->process_count);?></span>
                    </span>
                </div>
				<script type="text/javascript">


        // Draw the pie chart for the Anthony's pizza when Charts is loaded.
      google.charts.setOnLoadCallback(drawInvoiceChart);
      // Callback that draws the pie chart for Anthony's pizza.
      function drawInvoiceChart() {

        // Create the data table for Anthony's pizza.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['New', <?php echo $row->new_count;?>],
          ['Process', <?php echo $row->process_count;?>],
          ['Completed', <?php echo $row->success_count;?>]
        ]);

        // Set options for Anthony's pie chart.
        var options = {
		               title:'Invoice Stats By Status',
					   legend:'bottom',
					   width:300, 
					   height:300,
					   colors: ['#00BFFF','#FFD700', '#008000']
					   };
                       
                      

        // Instantiate and draw the chart for Invoice
        var chart = new google.visualization.PieChart(document.getElementById('Invoice_chart_div'));
        chart.draw(data, options);
      }
    </script>
				 <div id="Invoice_chart_div" style="border: 1px solid #ccc"></div>
            </div>
        </div>
        <?php } ?>
		
		<?php if (is_staff_member()) { ?>
        <div class="quick-stats-leads col-xs-12 col-md-6 col-sm-6 col-lg-12 tw-mb-2 sm:tw-mb-0 mtop10">
            <div class="top_stats_wrapper">
<?php
if (!is_admin()) {$this->db->where('addedfrom', get_staff_user_id());}
$year = $_GET['year'] ?? date('Y');
?>
                <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate  tw-my-2">
                        <i class="fa-solid fa-chart-simple menu-icon fa-2x"></i>
                        <span class="tw-truncate tw-text-xl">&nbsp;&nbsp;Overall Performance</span>
                    </div>
                    <span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0">
<label><select name="leads_length" id="yearSelect" aria-controls="leads" class="form-control input-sm">
<?php
for ($i = 2020; $i <= 2030; $i++) {
?>
<option value="<?php echo $i;?>" <?php if($i==$year){ ?> selected="selected" <?php } ?> ><?php echo $i;?></option>
<?php
}
?>
</select></label>
                    </span>
                </div>
<?php


// Numeric month values with leading zeros
$months = [
    '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
    '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug',
    '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'
];

?>

<script type="text/javascript">

 google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Month', 'Leads', 'Deals', 'Invoice'],
<?php foreach ($months as $num => $name) {

$monthyear=$name." - ". $year;
$monthyearnum=$year."-".$num; 
 
	
	//Count Total Added Leads Except Junk
	$_where=' `dateadded` LIKE "%' . $monthyearnum . '%" AND `status` <> 4 ';//exit;
	$leads = total_rows(db_prefix() . 'leads', $_where);
	
		
	//Count Total Added Deals Except Junk
	$_where=' `dateadded` LIKE "%' . $monthyearnum . '%" AND `is_deal` = 1 ';//exit;
	$deals = total_rows(db_prefix() . 'leads', $_where);
	
	
	//Count Total Added Invoice
	$_where=' `datecreated` LIKE "%' . $monthyearnum . '%" ';//exit;
	$invoice = total_rows(db_prefix() . 'invoices', $_where);
	//echo $this->db->last_query();exit;
	
	
?>		  
 ['<?php echo $monthyear;?>', <?php echo $leads;?>, <?php echo $deals;?>, <?php echo $invoice;?>],
 <?php } ?>         
		  
        ]);

        var options = {
          chart: {
            title: '',
            subtitle: 'Leads, Deals, and Invoice: <?php echo $year;?>',
          },
          bars: 'vertical',
          vAxis: {format: 'decimal'},
          height: 400,
          colors: ['#00BFFF','#FFD700', '#008000']
        };

        var chart = new google.charts.Bar(document.getElementById('chart_div'));

        chart.draw(data, google.charts.Bar.convertOptions(options));

        var btns = document.getElementById('btn-group');

        btns.onclick = function (e) {

          if (e.target.tagName === 'BUTTON') {
            options.vAxis.format = e.target.id === 'none' ? '' : e.target.id;
            chart.draw(data, google.charts.Bar.convertOptions(options));
          }
        }
      }

    </script>
				    <div id="chart_div"></div>
    <br/>
    <div id="btn-group">
      <button class="button button-blue" id="none">No Format</button>
      <button class="button button-blue" id="scientific">Scientific Notation</button>
      <button class="button button-blue" id="decimal">Decimal</button>
      <button class="button button-blue" id="short">Short</button>
    </div>
            </div>
        </div>
        <?php } ?>
		
		
        
        
    </div>
</div>