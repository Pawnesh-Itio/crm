<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php //print_r($webmaillist);?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <button class="btn btn-primary mbot15" data-toggle="modal" data-target="#entryModal">
    <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Webmail Setup'); ?>
</button>
                </div>
                <div class="panel_s">
                    <div class="panel-body panel-table-full">

                        <?php if (count($webmaillist) == 0) { ?>
<div class="alert alert-info text-center">
    <?php echo _l('No Webmail Setup Entries'); ?>
</div>
<?php } ?>
<div class="table-responsive">
  <table class="table table-clients number-index-2 dataTable no-footer">
    <thead>
      <tr role="row">
        <th class="toggleable">Name</th>
        <th class="toggleable">Email</th>
        <th class="toggleable">Username</th>
        <th class="toggleable">SMTP HOST</th>
        <th class="toggleable">IMAP HOST</th>
		<th class="toggleable">Status</th>
		<th class="">Created</th>
      </tr>
    </thead>
    <tbody>
	<?php foreach ($webmaillist as $entry) { ?>
      <tr class="has-row-options odd">
        
        <td class="sorting_1"><?php echo e($entry['mailer_name']); ?>
          <div class="row-options"><?php if ($entry['creator'] == get_staff_user_id() || is_admin()) { ?>
            <a href="#" onclick="edit_mailer_entry(<?php echo e($entry['id']); ?>); return false;" class="text-muted">Edit</a> | <a href="<?php echo admin_url('webmail_setup/delete/' . $entry['id']); ?>"
                class="text-danger _delete">Delete </a><?php } ?></div></td>
        <td><?php echo e($entry['mailer_email']); ?><br />
<?php echo e($entry['departmentid']); ?></td>
        <td><?php echo e($entry['mailer_username']); ?><br />
<?php echo substr_replace(e($entry['mailer_password']),'*****',2,7); ?></td>
        
        <td><?php echo e($entry['mailer_smtp_host']); ?><br />
<?php echo e($entry['mailer_smtp_port']); ?></td>
        <td><?php echo e($entry['mailer_imap_host']); ?><br />
<?php echo e($entry['mailer_imap_port']); ?></td>
		<td>
				<?php if(e($entry['mailer_status'])==1){ ?>
<a href="<?php echo admin_url('webmail_setup/statusoff/' . $entry['id']); ?>" class="text-danger _delete" title="Deactive">
<i class="fa-solid fa-toggle-on fa-xl text-success" style="margin-top:10px;"></i> </a>
				<?php }else{ ?>
<a href="<?php echo admin_url('webmail_setup/statuson/' . $entry['id']); ?>" class="text-danger _delete" title="Activate">
<i class="fa-solid fa-toggle-off fa-xl " style="margin-top:10px;"></i></a>
				<?php } ?>
				 </td>
		  <td><?php echo e($entry['creator_name']); ?> - <?php echo e(time_ago($entry['date_created'])); ?><br />
<?php echo e(_dt($entry['date_created'])); ?></td>
      </tr>
	  <?php } ?>
    </tbody>
  </table>
</div>

<?php foreach ($webmaillist as $entry) { ?>
<?php /*?><div
    class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md tw-overflow-hidden tw-mb-3 last:tw-mb-0 panel-vault">
    <div class="tw-flex tw-justify-between tw-items-center tw-px-6 tw-py-3 tw-border-b tw-border-solid tw-border-neutral-200 tw-bg-neutral-50"
        id="<?php echo 'vaultEntryHeading-' . e($entry['id']); ?>">
        <h4 class="tw-font-semibold tw-my-0 tw-text-lg">
            <?php echo e($entry['mailer_name']); ?> - (<?php echo e($entry['mailer_email']); ?>)
        </h4>
        <div class="tw-flex-inline tw-items-center tw-space-x-2">
            <?php if ($entry['creator'] == get_staff_user_id() || is_admin()) { ?>
            <a href="#" onclick="edit_mailer_entry(<?php echo e($entry['id']); ?>); return false;" class="text-muted">
                <i class="fa-regular fa-pen-to-square"></i>
            </a>
            <a href="<?php echo admin_url('webmail_setup/delete/' . $entry['id']); ?>"
                class="text-danger _delete">
                <i class="fa fa-remove"></i>
            </a>
            <?php } ?>
        </div>
    </div>
    <div id="<?php echo 'mailerEntry-' . $entry['id']; ?>" class="tw-p-6">
        <div class="row">
            <div class="col-md-6 border-right">
                <p class="tw-mb-1">
                    <b><?php echo _l('Name'); ?>: </b><?php echo e($entry['mailer_name']); ?>
                </p> 
				<p class="tw-mb-1">
                    <b><?php echo _l('Email'); ?>: </b><?php echo e($entry['mailer_email']); ?>
                </p>
                <p class="tw-mb-1">
                    <b><?php echo _l('Username'); ?>: </b><?php echo e($entry['mailer_username']); ?>
                </p>
				<p class="tw-mb-1">
                    <b><?php echo _l('Password'); ?>: </b><?php echo substr_replace(e($entry['mailer_password']),'*****',2,7); ?>
                </p>
				<p class="tw-mb-1">
                    <b><?php echo _l('SMTP HOST'); ?>: </b><?php echo e($entry['mailer_smtp_host']); ?>
                </p>
				<p class="tw-mb-1">
                    <b><?php echo _l('SMTP PORT'); ?>: </b><?php echo e($entry['mailer_smtp_port']); ?>
                </p>
				<p class="tw-mb-1">
                    <b><?php echo _l('IMAP HOST'); ?>: </b><?php echo e($entry['mailer_imap_host']); ?>
                </p>
				<p class="tw-mb-1">
                    <b><?php echo _l('IMAP PORT'); ?>: </b><?php echo e($entry['mailer_imap_port']); ?>
                </p>
                
            </div>
            <div class="col-md-6 text-center">
              
                <p class="text-muted">This webmail setup entry is created by <?php echo e($entry['creator_name']); ?> -
                    <span class="text-has-action" data-toggle="tooltip"
                        data-title="<?php echo e(_dt($entry['date_created'])); ?>">
                        <?php echo e(time_ago($entry['date_created'])); ?>
                    </span>
                </p>
                <p>
                    <?php if (!empty($entry['last_updated_from'])) { ?>
                <p class="text-muted no-mbot">
                    <?php echo _l('vault_entry_last_update', $entry['last_updated_from']); ?> -
                    <span class="text-has-action" data-toggle="tooltip"
                        data-title="<?php echo e(_dt($entry['last_updated'])); ?>">
                        <?php echo e(time_ago($entry['last_updated'])); ?>
                
                </span>
                <p></p>
                    <?php } ?>
            </div>
        </div>
    </div>
</div><?php */?>
<?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="entryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open(admin_url('webmail_setup/webmail_setup_create/'), ['data-create-url' => admin_url('webmail_setup/webmail_setup_create/'), 'data-update-url' => admin_url('webmail_setup/webmail_setup_update')]); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo 'Webmail Setup'; ?></h4>
            </div>
            <div class="modal-body">
                <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
                <input type="text" class="fake-autofill-field" name="fakeusernameremembered" value='' tabindex="-1" />
                <input type="password" class="fake-autofill-field" name="fakepasswordremembered" value='' tabindex="-1" />
                 
<div class="table-responsive">
<span>All fields is required</span>
  <table class="table table-bordered roles no-margin">
    <tbody>
	
      <tr data-name="bulk_pdf_exporter">
        <td><?php echo render_input('mailer_name', 'Name','', 'text', ['required' => 'true']); ?></td>
        <td><?php echo render_input('mailer_email', 'Email','', 'text', ['required' => 'true']); ?></td>
      </tr>
	  
	  <tr data-name="bulk_pdf_exporter">
        <td><?php echo render_input('mailer_username', 'Username','', 'text', ['required' => 'true']); ?></td>
        <td><?php echo render_input('mailer_password', 'Password', '', 'password', ['required' => 'true']); ?></td>
      </tr>
	  
	  <tr data-name="bulk_pdf_exporter">
        <td><?php echo render_input('mailer_smtp_host', 'SMTP HOST','', 'text', ['required' => 'true']); ?></td>
        <td><?php echo render_input('mailer_smtp_port', 'SMTP PORT', '', 'number', ['required' => 'true']); ?></td>
      </tr>
	  
	  <tr data-name="bulk_pdf_exporter">
        <td><?php echo render_input('mailer_imap_host', 'IMAP HOST','', 'text', ['required' => 'true']); ?></td>
        <td><?php echo render_input('mailer_imap_port', 'IMAP PORT', '', 'number', ['required' => 'true']); ?></td>
      </tr>
	  <tr data-name="bulk_pdf_exporter">
        <td>Assign Department</td>
        <td>


	
  </td>
      </tr>
	  
	  
    </tbody>
  </table>
</div>   
                
				
				
                
				
                
				
                
                
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div>
    <!-- /.modal-dialog -->
</div>
<?php init_tail(); ?>
<script>
$(function() {
    initDataTable('.table-custom-fields', window.location.href);
});
</script>
<script>
var $entryModal = $('#entryModal');
$(function() {

    appValidateForm($entryModal.find('form'), {
        server_address: 'required',
        username: 'required',
        password: 'required',
    });
    setTimeout(function() {
        $($entryModal.find('form')).trigger('reinitialize.areYouSure');
    }, 1000)
    $entryModal.on('hidden.bs.modal', function() {
        var $form = $entryModal.find('form');
        $form.attr('action', $form.data('create-url'));
        $form.find('input[type="text"]').val('');
        $form.find('input[type="radio"]:first').prop('checked', true);
        $form.find('textarea').val('');
        $('#vault_password_change_notice').addClass('hide');
        $form.find('#password').rules('add', {
            required: true
        });
        $form.find('#password').parents().find('.req').removeClass('hide');
        $form.find('#share_in_projects').prop('checked', false);
    });
});

function edit_mailer_entry(id) {
//alert(8888); alert(id);alert(admin_url + 'webmail_setup/webmail_setup_entry/' + id)
    $.get(admin_url + 'webmail_setup/webmail_setup_entry/' + id, function(response) {

        var $form = $entryModal.find('form');
		//alert(999);
        $form.attr('action', $form.data('update-url') + '/' + id);
		$form.find('#mailer_name').val(response.mailer_name);
		$form.find('#mailer_email').val(response.mailer_email);
		$form.find('#mailer_username').val(response.mailer_username);
		$form.find('#mailer_password').val(response.mailer_password);
		$form.find('#mailer_smtp_host').val(response.mailer_smtp_host);
		$form.find('#mailer_smtp_port').val(response.mailer_smtp_port);
		$form.find('#mailer_imap_host').val(response.mailer_imap_host);
		$form.find('#mailer_imap_port').val(response.mailer_imap_port);
		alert(response.departmentid);
		
		$('#departmentid option[value="2"]').prop('selected','selected');
        /*$form.find('#server_address').val(response.server_address);
        $form.find('#port').val(response.port);
        $form.find('#username').val(response.username);
        $form.find('#description').val(response.description);
        $form.find('#password').rules('remove');
        $form.find('#password').parents().find('.req').addClass('hide');
        $form.find('input[value="' + response.visibility + '"]').prop('checked', true);
        $form.find('#share_in_projects').prop('checked', (response.share_in_projects == 1 ? true : false));
        $('#vault_password_change_notice').removeClass('hide');*/
        $entryModal.modal('show');
    }, 'json');
}
</script>
</body>

</html>