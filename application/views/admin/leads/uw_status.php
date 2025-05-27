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
            <?php if (count($statuses) > 0) { ?>
            <table class="table dt-table" data-order-col="1" data-order-type="asc">
                            <thead>
                                <th><?php echo _l('Status'); ?></th>
								<th><?php echo _l('Name'); ?></th>
								<th><?php echo _l('Company'); ?></th>
								<th><?php echo _l('Email'); ?></th>
                                <th><?php echo _l('MDR'); ?></th>
                                <th><?php echo _l('SetupFee'); ?></th>
								<th><?php echo _l('HoldBack'); ?></th>
								<th><?php echo _l('CardType'); ?></th>
								<th><?php echo _l('Settlement'); ?></th>
								<th><?php echo _l('SettlementFee'); ?></th>
								<th><?php echo _l('MinSettlement'); ?></th>
								<th><?php echo _l('MonthlyFee'); ?></th>
								<th><?php echo _l('Descriptor'); ?></th>
								<th><?php echo _l('dateadded'); ?></th>
								
                            </thead>
                            <tbody>
<?php foreach ($statuses as $status) {?> 
<?php $rowclr="#f8d6dd"; $rowstatus="Rejected"; if($status['quotation_status']==1){ $rowstatus="Approved"; $rowclr="#d5f2d8";}?>
								
								
                                <tr style="background:<?php echo $rowclr;?>">
                                    
                                <td><?php echo $rowstatus;?></td>
								<td><?php echo $status['name']; ?></td>
								<td><?php echo $status['company']; ?></td>
								<td><?php echo $status['email']; ?></td>
                                <?php if(isset($status['quotation_status'])&&$status['quotation_status']==1){ ?>
                                <td><?php echo $status['MDR']; ?></td>
								<?php }else{ ?>
                                <td>Reason&nbsp;<i class="fa-solid fa-circle-exclamation text-warning" title="<?php echo $status['Reason']; ?>"></i></td> 
								<?php } ?>
                                <td><?php echo $status['SetupFee']; ?></td>
								<td><?php echo $status['HoldBack']; ?></td>
								<td><?php echo $status['CardType']; ?></td>
								<td><?php echo $status['Settlement']; ?></td>
								<td><?php echo $status['SettlementFee']; ?></td>
								<td><?php echo $status['MinSettlement']; ?></td>
								<td><?php echo $status['MonthlyFee']; ?></td>
								<td><?php echo $status['Descriptor']; ?></td>
								<td><?php echo $status['dateadded']; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
            <?php } else { ?>
            <p class="no-margin"><?php echo _l('UW Status Not Found'); ?></p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php /*?><div class="modal fade" id="status" tabindex="-1" role="dialog">
  <div class="modal-dialog"> <?php echo form_open(admin_url('leads/dealstatus'), ['id' => 'deal-status-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> <span class="edit-title"><?php echo _l('edit_status'); ?></span> <span class="add-title"><?php echo _l('Add New Deals Status'); ?></span> </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional"></div>
            <?php echo render_input('name', 'Status Title'); ?> 
			<?php echo render_color_picker('color', _l('Status Color')); ?> 
			<?php echo render_input('statusorder', 'leads_status_add_edit_order', total_rows(db_prefix() . 'deals_status') + 1, 'number'); ?></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
    </div>
    <!-- /.modal-content -->
    <?php echo form_close(); ?> </div>
  <!-- /.modal-dialog -->
</div><?php */?>
<!-- /.modal -->
<?php /*?><script>
  window.addEventListener('load', function () {
    appValidateForm($("body").find('#leads-status-form'), {
        name: 'required'
    }, manage_leads_statuses);
    $('#status').on("hidden.bs.modal", function (event) {
        $('#additional').html('');
        $('#status input[name="name"]').val('');
        $('#status input[name="color"]').val('');
        $('#status input[name="statusorder"]').val('');
        $('.add-title').removeClass('hide');
        $('.edit-title').removeClass('hide');
        $('#status input[name="statusorder"]').val($('table tbody tr').length + 1);
    });
});

// Create lead new status
function new_status() {
    $('#status').modal('show');
    $('.edit-title').addClass('hide');
}

// Edit status function which init the data to the modal
function edit_status(invoker, id) {
    $('#additional').append(hidden_input('id', id));
    $('#status input[name="name"]').val($(invoker).data('name'));
    $('#status .colorpicker-input').colorpicker('setValue', $(invoker).data('color'));
    $('#status input[name="statusorder"]').val($(invoker).data('order'));
    $('#status').modal('show');
    $('.add-title').addClass('hide');
}

// Form handler function for leads status
function manage_leads_statuses(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function (response) {
        window.location.reload();
    });
    return false;
}
</script><?php */?>
<?php init_tail(); ?>
</body></html>