<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php
function cleartext($txt){
$txt=str_replace('\n', '<br>', $txt);
$txt=str_replace('\"', ' "', $txt);
$txt=str_replace("\'", "'", $txt);
return $txt;
}

?>
<?php //print_r($webmaillist);?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
<?php if (is_admin()) {  ?>
<div class="tw-mb-2 sm:tw-mb-2" title="Update AI API KEY">
<a class="btn btn-primar" onclick="edit_key(); return false;" style="float:right">
<i class="fa fa-cog menu-icon tw-mr-1"></i></a>

</div>
<div class="clearfix tw-mb-2"></div>
<?php } ?>
                
                <div class="panel_s">
                    <div class="panel-body panel-table-full">



<form action="<?=  admin_url('ai_content_generator/generate') ?>" method="post">
<!-- CSRF Token -->
<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
<div class="mb-3">
        <label for="recipientEmail" class="form-label mtop10">Content Title / Subject</label>
        <input type="text" class="form-control" id="content_title" name="content_title" value="" required>
      </div>
<button type="submit" name="submit" class="btn btn-primary mtop20">Generate Content</button>
</form>


                    </div>
                </div>
				
	<?php if(isset($content_description)&&$content_description){?>			
				<div class="panel_s">
                    <div class="panel-body panel-table-full">
					<div id="vueApp" class="tw-inline pull-right tw-ml-0 sm:tw-ml-1.5" data-v-app=""><button type="button" class="btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $content_title;?> </button>&nbsp;<button class="btn btn-default"  title="Copy Content" onClick='CopyValTestbox("myInput")'>&nbsp;<i class="fa-solid fa-copy" aria-hidden="true"></i>&nbsp;</button></div>
					<div  style="clear:both;"></div>
					
					<div class="p-card" style="max-width:100% !important;">
					<h5 class="bold">Content : <?php echo $content_title;?></h5>
					<?php echo cleartext($content_description) ;?>
					</div>
<textarea style="height:1px;width:1px;float:inline-end;" id="myInput"><?php echo cleartext($content_description);?></textarea>
					</div>
				</div>
	<?php }?>	
	
	<?php if (count($_SESSION['datalists']) > 0) { ?>
	<div class="panel_s">
	<h4>&nbsp;&nbsp;History (Last 5)</h4>
	<?php foreach ($_SESSION['datalists'] as $rs) { ?>
                    <div class="panel-body panel-table-full">
					<div id="vueApp" class="tw-inline pull-right tw-ml-0 sm:tw-ml-1.5" data-v-app=""><button type="button" class="btn btn-default " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $rs['content_title'];?> </button>&nbsp;<button class="btn btn-default" title="Copy Content" onClick='CopyValTestbox("vkg<?php echo $rs['content_id'];?>")'>&nbsp;<i class="fa-solid fa-copy" aria-hidden="true"></i>&nbsp;</button></div>
					<div  style="clear:both;"></div>
					
					<div class="p-card" style="max-width:100% !important;" >
					<?php 
					echo cleartext($rs['content']);
					?>
<textarea style="height:1px;width:1px;float:inline-end;" id="vkg<?php echo $rs['content_id'];?>"><?php echo cleartext($rs['content']);?></textarea>
					
					
					</div>
					</div>
					<?php }?>	
				</div>
	<?php }?>	
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="entryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open(admin_url('ai_content_generator/ai_setup_create/'), ['data-create-url' => admin_url('ai_content_generator/ai_setup_create/'), 'data-update-url' => admin_url('ai_content_generator/ai_setup_update')]); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo 'AI APIKey Update'; ?></h4>
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
        <td><?php echo render_input('apikey', 'Name','', 'text', ['required' => 'true']); ?></td>
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

$(function() {
    initDataTable('.table-custom-fields', window.location.href);
});

	// js for copy data
	function CopyValTestbox(divid) {
	alert(divid)
   
	var range = document.createRange();
	range.selectNode(document.getElementById(divid));
	window.getSelection().removeAllRanges(); // clear current selection
	window.getSelection().addRange(range); // to select text
	
	
	        if (document.execCommand('copy')) {
                window.getSelection().removeAllRanges();// to deselect
				alert("Copied : " + theLabel);
            }
	}

function edit_key() {
    $.get(admin_url + 'ai_content_generator/ai_setup', function(response) {
        //alert(JSON.stringify(response, null, "\t"))
        var $form = $entryModal.find('form');
        $form.attr('action', $form.data('update-url'));
		$form.find('#apikey').val(response.apikey);
        $entryModal.modal('show');
    }, 'json');
}


</script>

</body>

</html>