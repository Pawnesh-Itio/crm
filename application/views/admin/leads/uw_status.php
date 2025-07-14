<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  #sortable { list-style-type: none;}
  #sortable li { margin: 10px; padding: 10px; }
  </style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4"> <?php /*?><a href="#" onclick="new_status(); return false;" class="btn btn-primary"> <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Deal Status'); ?> </a><?php */?> </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (count($uwstatus) > 0) { ?>
			
            <table class="table dt-table" data-order-col="0" data-order-type="desc">
                            <thead>
							    <th><?php echo _l('ID'); ?></th>
                                <th><?php echo _l('Status'); ?></th>
								<th><?php echo _l('Name'); ?></th>
								<th><?php echo _l('Company'); ?></th>
								<th><?php echo _l('Email'); ?></th>
								<th><?php echo _l('Business URL'); ?></th>
								<th><?php echo _l('Assigned'); ?></th>
                                <th><?php echo _l('MDR'); ?></th>
                                <th><?php echo _l('SetupFee'); ?></th>
								<th><?php echo _l('HoldBack'); ?></th>
								<th><?php echo _l('CardType'); ?></th>
								<th><?php echo _l('Settlement'); ?></th>
								<th><?php echo _l('SettlementFee'); ?></th>
								<th><?php echo _l('MinSettlement'); ?></th>
								<th><?php echo _l('MonthlyFee'); ?></th>
								<th><?php echo _l('Descriptor'); ?></th>
								<th><?php echo _l('Last Updated'); ?></th>
								<th><?php echo _l('dateadded'); ?></th>
								
                            </thead>
                            <tbody>
<?php foreach ($uwstatus as $status) {?> 
<?php $rowclr="#f8d6dd"; $rowstatus="Rejected"; if($status['quotation_status']==1){ $rowstatus="Approved"; $rowclr="#d5f2d8";}?>
								
								
                                <tr style="background:<?php echo $rowclr;?>">
                                 <td title="Lead ID : <?php echo $status['leadid']; ?>"><?php echo $status['id']; ?></td>   
                                <td><?php echo $rowstatus;?> 
					<?php if(isset($status['Reason'])&&$status['Reason']){ ?>
					<i class="fa-solid fa-circle-exclamation text-warning" title="<?php echo $status['Reason']; ?>"></i>
					<?php } ?>
								</td>
								<td><?php echo $status['name']; ?></td>
								<td><?php echo $status['company']; ?></td>
								<td><?php echo $status['email']; ?></td>
								<td><?php echo $status['website']; ?></td>
								<td>
								<?php 
								if(isset($status['assigned'])&&$status['assigned']){
								echo get_staff_full_name($status['assigned']); 
								}else{
								echo get_staff_full_name(1); 
								}
								
								?>
								
								</td>
                                <?php if(isset($status['quotation_status'])&&$status['quotation_status']==1){ ?>
                                <td><?php echo $status['MDR']; ?></td>
								<?php }else{ ?>
                                <td>&nbsp;</td> 
								<?php } ?>
                                <td><?php echo $status['SetupFee']; ?></td>
								<td><?php echo $status['HoldBack']; ?></td>
								<td><?php echo $status['CardType']; ?></td>
								<td><?php echo $status['Settlement']; ?></td>
								<td><?php echo $status['SettlementFee']; ?></td>
								<td><?php echo $status['MinSettlement']; ?></td>
								<td><?php echo $status['MonthlyFee']; ?></td>
								<td><?php echo $status['Descriptor']; ?></td>
								<td><?php echo $status['last_status_change']; ?></td>
								<td><?php echo $status['dateadded']; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
						</div>
            <?php } else { ?>
            <p class="no-margin"><?php echo _l('UW Status Not Found'); ?></p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
</body></html>