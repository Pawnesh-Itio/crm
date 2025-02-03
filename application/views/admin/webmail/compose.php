<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php //print_r($webmaillist);?>
<style>
@media (min-width: 768px) {
    .modal-dialog {
        width: unset !important;
    }
}
</style>

<div id="wrapper">
    <div class="content">
        <div class="row">
		<?php if(!empty($_SESSION['mailersdropdowns'])){ ?>
            <div class="col-md-12">
                <div class="tw-mb-3">
   <span class="dropdown">
  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?=$_SESSION['webmail']['mailer_email'];?>
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
	<?php  foreach ($_SESSION['mailersdropdowns'] as $item) { ?>
	<li><a href="?mt=<?=$item['id'];?>"><?=$item['mailer_email'];?></a></li>
	<?php  } ?>
  </ul>
</span>
    <a href="<?php echo site_url('admin/webmail/inbox'); ?>" class="btn btn-primary new-ticket">
        <i class="fa-solid fa-envelope tw-mr-1"></i>
        <?php echo _l('Inbox')."(".@$_SESSION['inbox-total-email'].")"; ?><span class="badge text-bg-primary rounded-pill inbox-count"></span>
    </a>
	<a href="<?php echo site_url('admin/webmail/sent'); ?>" class="btn btn-primary new-ticket">
        <i class="fa-solid fa-envelope-circle-check tw-mr-1"></i>
        <?php echo _l('Sent Item')."(".@$_SESSION['outbox-total-email'].")"; ?><span class="badge text-bg-primary rounded-pill inbox-count"></span>
    </a>
	<a href="<?php echo site_url('admin/webmail/compose'); ?>" class="btn btn-primary new-ticket">
        <i class="fa-regular fa-paper-plane tw-mr-1"></i>
        <?php echo _l('New Mail'); ?>
    </a>
	
	
</div>
<div class="panel_s">
<div class="panel-body panel-table-full">

<form action="<?=  admin_url('webmail/reply') ?>" method="post" enctype="multipart/form-data">
	<!-- CSRF Token -->
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" 
               value="<?= $this->security->get_csrf_hash(); ?>">
	<input type="hidden" name="redirect" value="inbox.php">
      <div class="mb-3">
        <label for="recipientEmail" class="form-label mtop10">To</label>
        <input type="text" class="form-control" id="recipientEmailIT" name="recipientEmail" value="" placeholder="Enter recipient email" required>
      </div>
	  <div class="mb-3">
        <label for="recipientEmail" class="form-label mtop10">CC</label>
        <input type="text" class="form-control" id="recipientCCIT" name="recipientCC" value="" placeholder="Enter CC email" >
      </div>
      <div class="mb-3">
        <label for="emailSubject" class="form-label mtop10">Subject</label>
        <input type="text" class="form-control" id="emailSubjectIT" name="emailSubject" value="" placeholder="Enter email subject" required>
      </div>
      <div class="mb-3">
        <label for="emailBody" class="form-label mtop10">Email Body</label>
	   <?php echo render_textarea('emailBody', '', '', [], [], '', 'tinymce'); ?>
                                <div class="attachments_area">
                                    <div class="row attachments">
                                        <div class="attachment">
                                            <div class="col-md-4 mtop10">
                                                <div class="form-group">
                                                    <label for="attachment"
                                                        class="control-label"><?php echo _l('Add Attachments'); ?> </label>
                                                    <div class="input-group">
<input type="file" extension="jpg,png,pdf,doc,zip,rar" filesize="83886080" class="form-control" name="attachment1" accept=".jpg,.png,.pdf,.doc,.zip,.rar,image/jpeg,image/png,application/pdf,application/msword,application/x-zip,application/x-rar">
                                                    </div>
                                                </div>
                                            </div>
											<div class="col-md-4 mtop10">
                                                <div class="form-group">
                                                    <label for="attachment"
                                                        class="control-label"><?php echo _l('Add Attachments'); ?> </label>
                                                    <div class="input-group">
<input type="file" extension="jpg,png,pdf,doc,zip,rar" filesize="83886080" class="form-control" name="attachment2" accept=".jpg,.png,.pdf,.doc,.zip,.rar,image/jpeg,image/png,application/pdf,application/msword,application/x-zip,application/x-rar">
                                                    </div>
                                                </div>
                                            </div>
											<div class="col-md-4 mtop10">
                                                <div class="form-group">
                                                    <label for="attachment"
                                                        class="control-label"><?php echo _l('Add Attachments'); ?> </label>
                                                    <div class="input-group">
<input type="file" extension="jpg,png,pdf,doc,zip,rar" filesize="83886080" class="form-control" name="attachment3" accept=".jpg,.png,.pdf,.doc,.zip,.rar,image/jpeg,image/png,application/pdf,application/msword,application/x-zip,application/x-rar">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
      </div>
      <button type="submit" name="send" class="btn btn-primary mtop20">Send Email</button>
    </form>

</div>
</div>
            </div>
		<?php }else{?>
		<div class="alert alert-info text-center">
        <?php echo _l('No Webmail Setup Entries'); ?>
        </div>
		<?php } ?>
        </div>
    </div>
</div>



<?php init_tail(); ?>
<script>

  
  $('.hrefmodal').click(function(){ 

         //alert(11111);
         var tid=$(this).attr('data-tid');
		 var mailto=$(this).attr('mailto');
		 var did=$(this).attr('data-id');
		 //alert(tid);alert(mailto);alert(did);
		 
		 $('#myModal12').modal('show');
		  $('#myModal12 .modal-dialog').css({"max-width":"80%", "margin-top": "20px"});
		 //$('#myModal12').modal('show').find('.modal-body').load(urls);
	     $('#myModal12 .modal-title').html(tid + mailto);
		// $('#emailSubject').val(tid);
		 $('#emailSubjectIT').val("Re: "+tid);
		 $('#recipientEmailIT').val(mailto);
		 
		 var contents=$('#'+did).html();
		 $('#messageDisplay').html(contents);

		 

	});
	
	$( "#reply-button" ).click(function() {
    $( "#reply-box" ).toggle();
});
  </script>
  <script>
    tinymce.init({
    selector: 'textarea',
    plugins: [
      // Core editing features
      'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
      // Your account includes a free trial of TinyMCE premium features
      // Try the most popular premium features until Jan 30, 2025:
      'checklist', 'mediaembed', 'casechange', 'export', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown','importword', 'exportword', 'exportpdf'
    ],
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',
    mergetags_list: [
      { value: 'First.Name', title: 'First Name' },
      { value: 'Email', title: 'Email' },
    ],
    ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
  });

 
  </script>
<script>
$(function() {
    //initDataTable('.table-custom-fields', window.location.href);
});
</script>


</body>

</html>