<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php //print_r($_SESSION['subfolderlist']);exit;?>
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
					<?php if(count($_SESSION['subfolderlist'])>0 && isset($_SESSION['subfolderlist'][$val])){ ?>
                    </li>
                    <?php
                    foreach ($_SESSION['subfolderlist'][$val] as $sitem => $sval) {

					?>
                    <li role="presentation" class="menu-item-leads">
                        <a href="inbox?fd=<?=$sval;?>"><i class="fa-solid fa-arrow-right-long tw-mx-2 "></i> <?=$sval;?></a>
                    </li>
				  <?php } } } ?>  
                </ul>
            </div>
            <div class="col-md-10">
			<div class="tw-flex tw-items-center tw-mb-2">
                    <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mr-4"><?php if(!empty($_SESSION['webmail']['folder'])){ echo $_SESSION['webmail']['folder'];}?> <?php if(isset($_SESSION['inbox-total-email'])&&!empty($_SESSION['inbox-total-email'])){?> (<?=$_SESSION['inbox-total-email'];?>) <?php } ?></h4>
             </div>

        
                <div class="panel_s">
                    <div class="panel-body panel-table-full">

<?php if (count($inboxemail) == 0) { ?>
<div class="alert alert-info text-center">
    <?php echo _l('Records Not Found'); ?>
</div>
<?php } ?>
<div class="table-responsive">
 <table class="table table-clients number-index-2 dataTable no-footer">

<?php $cnt=101; foreach ($inboxemail as $message) { $cnt++; ?>
<tr>
	<td class="hrefmodal" data-tid="<?=$message->subject;?>" data-id="msg<?=$cnt;?>" title="<?=$message->subject;?>" mailto="<?=$message->from;?>"><span > <?=$message->subject;?><br><?=htmlspecialchars($message->from);?></span></td>
	<td class="w-25 text-end" style="min-width: 140px;"><?=$message->date;?></td>
</tr>
<tr><td colspan="2" style="display:none;" id="msg<?=$cnt;?>">


<?php
echo '<iframe srcdoc="' . htmlspecialchars($message->getHtmlBody()) . '" style="width: 100%; min-height:50px; border: none;" onload="adjustIframeHeight(this)"></iframe>';
// Directory to save attachments
$attachmentDir = 'attachments';
// Create directory if it doesn't exist
if (!file_exists($attachmentDir)) {
mkdir($attachmentDir, 0777, true);
}
// Retrieve and save attachments
$attachments = $message->getAttachments();
foreach ($attachments as $attachment) {
$fileName = $attachment->name;
$filePath = site_url('attachments') . '/' . $fileName;
// Save the attachment
$attachment->save($attachmentDir);
?>
<i class="fa-solid fa-paperclip"></i> - <a href="<?=$filePath;?>" target="_blank" title="Click to view"><?=$fileName;?></a><br>
<?php
} 
//====================================== 


?>



</td></tr>
<?php } ?>

  </tbody>
  </table>
  </div>
<div class="dataTables_paginate paging_simple_numbers" id="clients_paginate"><ul class="pagination">
<?php
// Paging
// Configuration
$totalRecords = $_SESSION['inbox-total-email']; // Total number of records (replace with your DB query result)
$recordsPerPage = 30; // Records per page
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page from URL
$current_page = max(1, $current_page); // Ensure current page is at least 1

// Calculate total pages and boundaries
$totalPages = ceil($totalRecords / $recordsPerPage);
$startPage = max(1, $current_page - 5); // Start page for display
$endPage = min($totalPages, $startPage + 9); // End page for display

// Ensure proper range of start and end pages
if ($endPage - $startPage < 9) {
    $startPage = max(1, $endPage - 9);
}

// Generate Previous and Next page numbers
$prevPage = $current_page > 1 ? $current_page - 1 : null;
$nextPage = $current_page < $totalPages ? $current_page + 1 : null;

// Display the pagination
//echo '<nav aria-label="Page navigation example"><ul class="pagination justify-content-center ">';

// Previous Button
if ($prevPage) {
    echo '<li class="paginate_button previous" id="clients_previous"><a class="page-link " href="?page=' . $prevPage . '">Previous</a></li>';
}

// Page Links
for ($i = $startPage; $i <= $endPage; $i++) {
    if ($i == $current_page) {
        echo '<li class="paginate_button active 44"><a class="page-link">' . $i . '</a></li>';
    } else {
        echo '<li class="paginate_button 11"><a class="page-link"  href="?page=' . $i . '">' . $i . '</a></li>';
    }
}

// Next Button
if ($nextPage) {
    echo '<li class="paginate_button next" id="clients_next"><a class="page-link" href="?page=' . $nextPage . '">Next</a></li>';
}

//echo '</ul></nav>';

// Styling for pagination (optional, Bootstrap 5 example)
?>
</ul></div>
  



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

<div class="modal" id="myModal12">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">
          <!--Heading-->
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
      </div>
      <!-- Modal body -->
      <div class="modal-body">
        <div id="messageDisplay" class="p-4"></div>
		<div id="replyform p-2 border rounded">
  <p class="d-inline-flex gap-1 text-end">
 
  <a class="btn btn-warning" id="reply-button"><i class="fa-solid fa-reply"></i> Reply</a>
</p>
<div class="collapse" id="reply-box">
  <div class="card card-body">
  
  
  
    <form action="<?=  admin_url('webmail/Reply') ?>" method="post" enctype="multipart/form-data">
	<!-- CSRF Token -->
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" 
               value="<?= $this->security->get_csrf_hash(); ?>">
	<input type="hidden" name="redirect" value="inbox.php">
      <div class="mb-3">
        <label for="recipientEmail" class="form-label">Recipient Email</label>
        <input type="text" class="form-control" id="recipientEmailIT" name="recipientEmail" value="" placeholder="Enter recipient email" required>
      </div>
      <div class="mb-3">
        <label for="emailSubject" class="form-label">Subject</label>
        <input type="text" class="form-control" id="emailSubjectIT" name="emailSubject" value="" placeholder="Enter email subject" required>
      </div>
      <div class="mb-3">
        <label for="emailBody" class="form-label">Email Body</label>
       <?php /*?> <textarea id="emailBody" name="emailBody" class="form-control" rows="5"></textarea><?php */?>
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
    <div id="resultMessage" class="mt-4"></div>
  </div>
</div>
  </div>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                
            </div>
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
		 $('#emailSubjectIT').val(tid);
		 $('#recipientEmailIT').val(mailto);
		 
		 var contents=$('#'+did).html();
		 $('#messageDisplay').html(contents);

		 

	});
	
	$( "#reply-button" ).click(function() {
    $( "#reply-box" ).toggle();
});
  </script>
  
<script>
$(function() {
    //initDataTable('.table-custom-fields', window.location.href);
});
</script>
<script>
function adjustIframeHeight(iframe) {
    setTimeout(() => {
        iframe.style.height = iframe.contentWindow.document.body.scrollHeight + "px";
    }, 100);
}
</script>


</body>

</html>