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
        <div class="tw-mb-2 sm:tw-mb-4"> <a href="#" onclick="new_status(); return false;" class="btn btn-primary"> <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Deal Status'); ?> </a> </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (count($statuses) > 0) { ?>
            <table class="table dt-table" data-order-col="1" data-order-type="asc">
                            <thead>
                                
                                <th><?php echo _l('leads_status_table_name'); ?></th>
								
                                <th><?php echo _l('options'); ?></th>
                            </thead>
                            <tbody>
                                <?php foreach ($statuses as $status) { ?>
                                <tr>
                                    
                                    <td>
                                        <i class="fa-solid fa-palette" style="color:<?php echo e($status['color']); ?>"></i>&nbsp;&nbsp;<a href="#"
                                            onclick="edit_status(this,<?php echo e($status['id']); ?>);return false;"
                                            data-color="<?php echo e($status['color']); ?>"
                                            data-name="<?php echo e($status['name']); ?>"
                                            data-order="<?php echo e($status['statusorder']); ?>"><?php echo e($status['name']); ?></a><br />
                                       
                                    </td>
                                    <td>
                                        <div class="tw-flex tw-items-center tw-space-x-3">
                                            <a href="#"
                                                onclick="edit_status(this,<?php echo e($status['id']); ?>);return false;"
                                                data-color="<?php echo e($status['color']); ?>"
                                                data-name="<?php echo e($status['name']); ?>"
                                                data-order="<?php echo e($status['statusorder']); ?>"
                                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                                                <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                            </a>
                                            
                                            <a href="<?php echo admin_url('leads/delete_deal_status/' . $status['id']); ?>"
                                                class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                                <i class="fa-regular fa-trash-can fa-lg"></i>
                                            </a>
                                           
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
            <?php } else { ?>
            <p class="no-margin"><?php echo _l('lead_statuses_not_found'); ?></p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="status" tabindex="-1" role="dialog">
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
</div>
<!-- /.modal -->
<script>
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
</script>
<?php init_tail(); ?>
</body></html>