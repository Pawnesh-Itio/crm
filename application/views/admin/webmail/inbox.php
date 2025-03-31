<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php //print_r($_SESSION['subfolderlist']);exit;?>
<style>
@media (min-width: 768px) {
    .modal-dialog {
        width: unset !important;
    }
	table.number-index-2 tbody>tr>td:nth-child(2), table.number-index-2 thead>tr>th:nth-child(2) {
        text-align: left !important;
    }
}

.folder-active {    
color: #d3e0ed !important;
background: #dc2626 !important;
}

.jqte_tool.jqte_tool_1 .jqte_tool_label {
    height: 20px !important;
}
.jqte {
    margin: 20px 0 !important;
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
                    <li role="presentation" class="menu-item-leads ">
                        <a href="inbox?fd=<?=$val;?>" class="mail-loader <?php if($_SESSION['webmail']['folder']==$val){ echo 'folder-active';} ?>"><?=$val;?></a>
                    </li>
					<?php if(count($_SESSION['subfolderlist'])>0 && isset($_SESSION['subfolderlist'][$val])){ ?>
                    </li>
                    <?php
                    foreach ($_SESSION['subfolderlist'][$val] as $sitem => $sval) {

					?>
                    <li role="presentation" class="menu-item-leads">
                        <a href="inbox?fd=<?=$sval;?>" class="mail-loader <?php if($_SESSION['webmail']['folder']==$sval){ echo 'folder-active';} ?>"><i class="fa-solid fa-arrow-right-long tw-mx-2 "></i> <?=$sval;?></a>
                    </li>
				  <?php } } } ?>  
                </ul>
            </div>
            <div class="col-md-10">
			
			 <div class="row">
			 <form>
			  <div class="col-md-7 mbot10"><div class="dt-buttons btn-group">
			 <?php if(!empty($_SESSION['webmail']['folder'])){ ?>
			  <button class="btn btn-default buttons-collection btn-sm btn-default-dt-options"  type="button" aria-haspopup="true"><span><?php echo $_SESSION['webmail']['folder'];?></span></button> <?php if(isset($_SESSION['inbox-total-email'])&&!empty($_SESSION['inbox-total-email'])){?><button class="btn btn-default btn-sm btn-default-dt-options bg-danger" type="button" ><span><?=$_SESSION['inbox-total-email'];?></span></button> <?php } ?>
			  <?php } ?><span id="mail-loader"></span>
			  
			  </div></div>
			  <div class="col-md-5 mbot10">
			  <div class="dt-buttons btn-group55 tw-text-right">
			  <div class="w-full tw-inline-flex sm:max-w-xs">
			  <select name="stype" class="form-control input-sm input-group-addon" style="width:auto;border-top-left-radius: .375rem;border-bottom-left-radius: .375rem;" required>
			  <option value="">Select type</option>
			  <option value="FROM">FROM</option>
			  <option value="TO">TO</option>
			  <option value="CC">CC</option>
			  <option value="BCC">BCC</option>
			  <option value="SUBJECT">SUBJECT</option>
			  <option value="TEXT">TEXT</option>
			  </select>
              <input type="text" class="form-control input-sm" name="skey" placeholder="Enter Search Keywords" required>
              <button type="submit" class="input-group-addon" style="padding-right: 25px;"><span class="fa fa-search"></span></button>
                </div>
				</div>
				</div>
				</form>
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

<?php $cnt=101; foreach ($inboxemail as $message) { $cnt++; 
//echo $message->getMessageId();//exit;
//print_r($message);
$string = "tw-bg-warning-600 tw-bg-primary-600 tw-bg-danger-600 tw-bg-danger-600 tw-bg-neutral-600 tw-bg-success-600 tw-bg-warning-800 tw-bg-primary-800 tw-bg-danger-800 tw-bg-danger-800 tw-bg-neutral-800 tw-bg-success-800";
// Step 1: Convert string to array of words
$words = preg_split('/\s+/', $string); // Split by spaces or multiple spaces
// Step 2: Select a random word
$randomWord = $words[array_rand($words)];

?>
<tr>
<td class="w-10"><div class="tw-rounded-full <?php echo $randomWord;?> tw-text-white tw-inline-flex tw-items-center tw-justify-center tw-h-8 tw-w-8 -tw-mt-1 group-hover:!tw-bg-primary-700"><?=strtoupper(substr($message->from,0,2));?></div></td>
	<td class="hrefmodal tw-cursor-pointer" data-tid="<?=$message->subject;?>" data-id="msg<?=$cnt;?>" title="<?=$message->subject;?>" mailto="<?=htmlspecialchars($message->from);?>" mailtox="<?=htmlspecialchars($message->to);?>" mailcc="<?=htmlspecialchars($message->cc);?>" mailbcc="<?=htmlspecialchars($message->bcc);?>" data-date="<?=$message->date;?>"><div class="w-36 h-36 bg-red-600 rounded-full"></div> <span> <b><?=$message->subject;?></b><br><?=htmlspecialchars($message->from);?></span></td>
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
<i class="fa-solid fa-paperclip"></i> <a href="<?=$filePath;?>" target="_blank" title="Click to view"><?=$fileName;?></a><br>
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
	  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <span class="modal-title"></span>
          
        

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
       <?php /*?> <textarea id="emailBody" name="emailBody" class="form-control" rows="5"></textarea><?php */?>
	   <?php /*?><?php echo render_textarea('emailBody', '', '', [], [], '', 'tinymce'); ?><?php */?>
	   <textarea  name="emailBody" id="emailBody" class="form-control editor" required></textarea>
                               
      </div>
	  <div class="mb-3">
	  <div class="tw-text-right">
	  <a name="send" class="ailoader" onclick="get_content();return false;"><img src="<?php echo base_url('assets/images/artificial-intelligence.png')?>" title="Draft with AI"  style="width:30px;" /></a>
	 
	  </div>

	  </div>
	  
	  <div class="mb-3">
        <label for="recipientEmail" class="form-label">Attach Files:</label>
        <input type="file" name="attachments[]"  class="form-control" multiple>
      </div>
      <button type="submit" name="send" class="btn btn-primary mtop20 submitemailxxx">Send Email</button>
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
<link rel="stylesheet" type="text/css" href="https://jqueryte.com/css/jquery-te.css"/>

<script src="https://jqueryte.com/js/jquery-te-1.4.0.min.js"></script>

<script>

	$('.editor').jqte();

</script>
<script>


  
 // Toggle AI BOX	
 function toggleCollapse() {
 const div = document.getElementById('collapseDiv');
    div.classList.toggle('hidden');
  }
  
 $('.mail-loader').click(function(){  
 $("#mail-loader").html("<i class='fa-solid fa-spinner fa-spin-pulse mleft20 mtop5 text-info'></i>");
 });
  // Toggle AI BOX	
$('.submitemailxxx').click(function(){ 
	var recipientEmailIT=$.trim($('#recipientEmailIT').val());
	var emailSubjectIT=$.trim($('#emailSubjectIT').val());
	var emailBody=$.trim($('#emailBody').val());
        
		
		 if(recipientEmailIT==''){
			alert('Please enter to email');
			$('#recipientEmailIT').focus();
			return false;
		}else if(emailSubjectIT==''){
		    alert('Please enter email subject');
			$('#emailSubjectIT').focus();
			return false;
		}else if(emailBody=='' || emailBody.length < 6 ){
		    alert('Please check Email body before submit / Min content length 5 character');
			$('.jqte_editor').focus();
			return false;
		}else{
		$(".submitemailxxx").html("<i class='fa-solid fa-spinner fa-spin-pulse'></i>");
		}


});


function get_content() { 

//let str = $('input[name="aicontent"]').val();
let str = $('.editor').val();
let aicontent = $.trim(str);


if((aicontent !="") && (aicontent.length >= 5)){
//alert(emailSubject);
$(".ailoader").html("<i class='fa-solid fa-spinner fa-spin-pulse'></i>");
     $.post(admin_url + 'ai_content_generator/generate_email_ai', {
            content_title: aicontent,
        })
        .done(function(response) { 
            response = JSON.parse(response);
			//alert(response);
			
			if(response.alert_type=="success"){
			var str = response.message.toString();
            var formattedStr = str.replace(/\\n/g, "<br>");
            var formattedStr = formattedStr.replace(/\\/g, "");
            //alert(formattedStr);
			$('.editor').jqteVal(formattedStr);
			$(".ailoader").html('<img src="<?php echo base_url('assets/images/artificial-intelligence.png')?>" title="Draft with AI"  style="width:30px;" />');
			
			}else{
			alert("Not Generated");
			$(".ailoader").html('<img src="<?php echo base_url('assets/images/artificial-intelligence.png')?>" title="Draft with AI"  style="width:30px;" />');
			
			}
			
            
        });
}else{
alert("Enter Correct Email Body with min length 5");
}

   
}
  </script>
<script>

  
  $('.hrefmodal').click(function(){ 

         //alert(11111);
         var tid=$(this).attr('data-tid');
		 var mailto=$(this).attr('mailto');
		 var mailtox=$(this).attr('mailtox');
		 var mailcc=$(this).attr('mailcc');
		 var mailbcc=$(this).attr('mailbcc');
		 var did=$(this).attr('data-id');
		 var ddate=$(this).attr('data-date');
		 const formattedDate = moment(ddate).format('ddd, DD MMM YYYY h:mm:ss A Z');
		 //alert(tid);alert(mailto);alert(formattedDate);
		 
		 $('#myModal12').modal('show');
		  $('#myModal12 .modal-dialog').css({"max-width":"80%", "margin-top": "20px"});
		 //$('#myModal12').modal('show').find('.modal-body').load(urls);
	     $('#myModal12 .modal-title').html('<span class="h4"><b>' + tid + '</b></span><br>' + '<span class="h6 text-primary"> From : ' + escapeHtml(mailto) +'<br> To : ' + escapeHtml(mailtox) +'<br> CC :' + escapeHtml(mailcc) +' BCC :' + escapeHtml(mailbcc) +'<br>' + formattedDate +'</span>');
		// $('#emailSubject').val(tid);
		 $('#emailSubjectIT').val(tid);
		 $('#recipientEmailIT').val(mailto);
		 
		 var contents=$('#'+did).html();
		 $('#messageDisplay').html(contents);

$('#emailBody_ifr').contents().find('#tinymce').html(content);
		 

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
$(document).ready(function() {
  setTimeout(function() {
    $('#lead-modal').removeAttr('id');
    console.log('ID removed from modal');
  }, 5000); // 5000 ms = 5 seconds
});
</script>


</body>

</html>