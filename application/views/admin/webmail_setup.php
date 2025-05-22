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

                        <?php  if (count($webmaillist) == 0) { ?>
<div class="alert alert-info text-center">
    <?php echo _l('No Webmail Setup Entries'); ?>
</div>
<?php } ?>
<div class="table-responsive"><i class="fa-regular fa-circle tw-bg-warning-100"></i> <strong>For Department</strong> <i class="fa-regular fa-circle tw-bg-success-100"></i> <strong>For Staff</strong>
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
		<th class="">Download Email</th>
      </tr>
    </thead>
    <tbody>
	<?php foreach ($webmaillist as $entry) { 
	$ctype="";
	if(e($entry['departmentid'])!=0){ $ctype="tw-bg-warning-100";
	}elseif(e($entry['staffid'])!=0){ $ctype="tw-bg-success-100";}
	
	//$departmentname=get_department_name(e($entry['departmentid']));
	
	?>
      <tr class="has-row-options odd <?=$ctype;?>">
        
        <td class="sorting_1"><?php echo e($entry['mailer_name']); ?>
          <div class="row-options"><?php if ($entry['creator'] == get_staff_user_id() || is_admin()) { ?>
            <a href="#" onclick="edit_mailer_entry(<?php echo e($entry['id']); ?>); return false;" class="text-muted">Edit</a> | <a href="<?php echo admin_url('webmail_setup/delete/' . $entry['id']); ?>"
                class="text-danger _delete">Delete </a><?php } ?></div></td>
        <td><?php echo e($entry['mailer_email']); ?><br />
Dep - <?php echo e($entry['departmentid']); ?> <!--- Staff -<?php echo e($entry['staffid']); ?>--></td>
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
<td><a href="<?php echo base_url('cronjob/download_email_from_cron/' . $entry['id']);?>" target="_blank" title="Download / Update Email"><i class="fa-solid fa-cloud-arrow-down"></i></a></td>
      </tr>
	  <?php } ?>
    </tbody>
  </table>
</div>

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
        <td><div class="form-group">
                            <label for="encryption"><?php echo _l('dept_encryption'); ?></label><br />
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" name="encryption" value="tls" id="tls">
                                <label for="tls">TLS</label>
                            </div>
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" name="encryption" value="ssl" id="ssl" checked>
                                <label for="ssl">SSL</label>
                            </div>
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" name="encryption" value="" id="no_enc" >
                                <label for="no_enc"><?php echo _l('dept_email_no_encryption'); ?></label>
                            </div>
                        </div></td>
        <td><div class="form-group">
                            <label for="folder" class="control-label">
                                <?php echo _l('imap_folder'); ?>
                                <a href="#" onclick="retrieve_imap_department_folders(); return false;">
                                    <i class="fa fa-refresh hidden" id="folders-loader"></i>
                                    <?php echo _l('retrieve_folders'); ?>
                                </a>
                            </label>
                            <select name="folder" class="form-control selectpicker" id="folder"></select>
                        </div></td>
      </tr>
	  
	  <tr data-name="bulk_pdf_exporter"><?php if (is_admin()) {?>
        <td><label for="departmentid" class="control-label">Assign Department</label>
<select name="departmentid" id="departmentid" class="form-control">
<option value="">Select Department</option>
<?php  foreach ($departmentlist as $item) { ?>
<option value="<?=$item['departmentid'];?>"><?=$item['name'];?></option>
<?php  } ?>
</select></td><?php } ?>
<td><br /></label><button onclick="test_dep_imap_connection(); return false;" class="btn btn-success">Test IMAP Connection</button></td>
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
        $form.find('#share_in_projects').prop('checked', false);
    });
});

function edit_mailer_entry(id) {
         //alert(admin_url + 'webmail_setup/webmail_setup_entry/' + id);
    $.get(admin_url + 'webmail_setup/webmail_setup_entry/' + id, function(response) {
        //alert(JSON.stringify(response, null, "\t"))
        var $form = $entryModal.find('form');
        $form.attr('action', $form.data('update-url') + '/' + id);
		$form.find('#mailer_name').val(response.mailer_name);
		$form.find('#mailer_email').val(response.mailer_email);
		$form.find('#mailer_username').val(response.mailer_username);
		$form.find('#mailer_password').val(response.mailer_password);
		$form.find('#mailer_smtp_host').val(response.mailer_smtp_host);
		$form.find('#mailer_smtp_port').val(response.mailer_smtp_port);
		$form.find('#mailer_imap_host').val(response.mailer_imap_host);
		$form.find('#mailer_imap_port').val(response.mailer_imap_port);
		var dptid=response.departmentid;
		//alert(dptid);
		$('#departmentid option[value="'+dptid+'"]').prop('selected','selected');
		var encryption = response.encryption;
        var input_enc_selector = encryption == '' ? '#no_enc' : '#' + encryption;
        $(input_enc_selector).prop('checked', true);
		var folder = response.folder;
		//alert(folder);
		$('#folder').html('<option value="' + folder + '" selected>' + folder + '</option>');
		$('#folder').selectpicker('refresh');
        
        $entryModal.modal('show');
    }, 'json');
}


function test_dep_imap_connection() {

   $.post(admin_url + 'webmail_setup/test_imap_connection', {
            email: $('input[name="mailer_email"]').val(),
            password: $('input[name="mailer_password"]').val(),
            host: $('input[name="mailer_imap_host"]').val(),
            username: $('input[name="mailer_username"]').val(),
            encryption: $('input[name="encryption"]:checked').val(),
            folder: $('#folder').selectpicker('val'),
        })
        .done(function(response) { 
            response = JSON.parse(response);
			//alert(response);
            alert_float(response.alert_type, response.message);
        });
}

function retrieve_imap_department_folders() {
    retrieve_imap_folders(admin_url + 'webmail_setup/folders', {
        email: $('input[name="mailer_email"]').val(),
        password: $('input[name="mailer_password"]').val(),
        host: $('input[name="mailer_imap_host"]').val(),
        username: $('input[name="mailer_username"]').val(),
        encryption: $('input[name="encryption"]:checked').val()
    })
}

</script>
</body>

</html>