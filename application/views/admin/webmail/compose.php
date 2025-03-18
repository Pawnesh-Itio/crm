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
		
		    <div class="col-md-2 picker">

<div>			
<span class="dropdown">
  <button class="btn btn-default buttons-collection btn-default-dt-options dropdown-toggle" type="button" data-toggle="dropdown" style="width: 180px !important;"><span title="<?=$_SESSION['webmail']['mailer_email'];?>"><?=substr($_SESSION['webmail']['mailer_email'],0,18);?></span>
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
	<?php  foreach ($_SESSION['mailersdropdowns'] as $item) { ?>
	<li><a href="?mt=<?=$item['id'];?>"><?=$item['mailer_email'];?></a></li>
	<?php  } ?>
  </ul>
</span>
</div>
<div>
<a href="<?php echo site_url('admin/webmail/compose'); ?>" class="btn btn-primary mtop10" style="width: 180px !important;">
        <i class="fa-regular fa-paper-plane tw-mr-1"></i>
        <?php echo _l('New Mail'); ?>
    </a>
</div>
                <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked mtop10" id="theme_styling_areas">
				
				<?php  foreach ($_SESSION['folderlist'] as $item => $val) { ?>
                    <li role="presentation" class="menu-item-leads">
                        <a href="inbox?fd=<?=$val;?>"><?=$val;?></a>
                    </li>
					<?php  if(!empty($_SESSION['subfolderlist'][$val])){ 
					foreach ($_SESSION['subfolderlist'][$val] as $sitem => $sval) {
					?>
					<li role="presentation" class="menu-item-leads">
                        <a href="inbox?fd=<?=$val;?>/<?=$sval;?>"><i class="fa-solid fa-arrow-right-long tw-mx-2 "></i> <?=$sval;?></a>
                    </li>
					<?
					
					}} ?>
				  <?php  } ?>  
                </ul>
            </div>
            <div class="col-md-10">
                <div class="tw-flex tw-items-center tw-mb-2">
                    <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mr-4">Sent New Email</h4>
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
                                
      </div>
	  <div class="mb-3">
        <label for="recipientEmail" class="form-label">Attach Files:</label>
        <input type="file" name="attachments[]"  class="form-control" multiple>
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
  



</body>

</html>