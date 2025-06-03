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
				
				<?php  
				
				foreach ($_SESSION['folderlist'] as $item => $val) { ?>
                    <li role="presentation" class="menu-item-leads ">
                        <a href="inbox?fd=<?php echo $val['folder'];?>" class="mail-loader <?php if($_SESSION['webmail']['folder']==$val['folder']){ echo 'folder-active';} ?>"><?php echo ucwords(strtolower($val['folder']));?></a>
                    </li>
					<?php } ?>  
					<li role="presentation" class="menu-item-leads ">
                        <a href="inbox?fd=Deleted" class="mail-loader <?php if($_SESSION['webmail']['folder']=='Deleted'){ echo 'folder-active';} ?>">Deleted</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-10">
			
			 <div class="row">
			 <form>
			  <div class="col-md-7 mbot10"><div class="dt-buttons btn-group">
			 <?php if(!empty($_SESSION['webmail']['folder'])){ ?>
			  <button class="btn btn-default buttons-collection btn-sm btn-default-dt-options"  type="button" aria-haspopup="true"><span><?php echo $_SESSION['webmail']['folder'];?></span></button> <?php if(isset($_SESSION['inbox-total-email'])&&!empty($_SESSION['inbox-total-email'])){?><button class="btn btn-default btn-sm btn-default-dt-options bg-danger" type="button" ><span><?=$_SESSION['inbox-total-email'];?></span></button> <?php } ?>
			  <?php } ?><button class="btn btn-default btn-sm btn-default-dt-options bg-info refreshemail" 
			  type="button" title="Refresh <?php echo $_SESSION['webmail']['folder'];?> Box Online"><span><i class="fa-solid fa-retweet" id="refresh-loader"></i></span></button>
			  <span id="mail-loader"></span>
			  
			  </div></div>
			  <div class="col-md-5 mbot10">
			  <div class="dt-buttons btn-group55 tw-text-right">
			  <div class="w-full tw-inline-flex sm:max-w-xs">
			  <select name="stype" class="form-control input-sm input-group-addon" style="width:auto;border-top-left-radius: .375rem;border-bottom-left-radius: .375rem;" required>
			  <option value="">Select type</option>
			  <option value="from_email">FROM Email</option>
			  <option value="from_name">FROM Name</option>
			  <option value="to_emails">TO</option>
			  <option value="cc_emails">CC</option>
			  <option value="bcc_emails">BCC</option>
			  <option value="subject">SUBJECT</option>
			  <option value="body">Mail Body</option>
			  </select>
              <input type="text" class="form-control input-sm" name="skey" placeholder="Enter Search Keywords" required>
              <button type="submit" class="input-group-addon" style="padding-right: 25px;"><span class="fa fa-search"></span></button>
                </div>
				</div>
				</div>
				</form>
			  </div>

        
                <div class="panel_s">
                    <div class="panel-body panel-table-full mail-bg">

<?php  if (count($inboxemail) == 0) { ?>
<div class="alert alert-info text-center">

    <?php echo _l('Records Not Found'); ?>
</div>
<?php } ?>
<div class="table-responsive">
 <table class="table table-clients number-index-2 dataTable no-footer">

<?php $cnt=101; foreach ($inboxemail as $message) { $cnt++; 
//print_r($message);//exit;
$string = "tw-bg-warning-600 tw-bg-primary-600 tw-bg-danger-600 tw-bg-danger-600 tw-bg-neutral-600 tw-bg-success-600 tw-bg-warning-800 tw-bg-primary-800 tw-bg-danger-800 tw-bg-danger-800 tw-bg-neutral-800 tw-bg-success-800";
// Step 1: Convert string to array of words
$words = preg_split('/\s+/', $string); // Split by spaces or multiple spaces
// Step 2: Select a random word
$randomWord = $words[array_rand($words)];
$mailcss="";
if(isset($message['status'])&&$message['status']==1){ $mailcss="isread"; }
?>
<tr class="table<?=$message['id'];?>">
<td style="width:35px;"><div class="tw-rounded-full <?php echo $randomWord;?> tw-text-white tw-inline-flex tw-items-center tw-justify-center tw-h-8 tw-w-8 -tw-mt-1 group-hover:!tw-bg-primary-700"><?=strtoupper(substr($message['from_email'],0,2));?></div></td>
<td style="width:50px;"><div>
<?php if(isset($message['isfalg'])&&$message['isfalg']==1){ ?>
<i class="fa-solid fa-fire-flame-simple tw-text-info-800 isflag" data-mid="<?=$message['id'];?>" data-fid="0" title="Click for normal"></i>
<?php }else{ ?>
<i class="fa-solid fa-fire-flame-simple tw-text-info-300 isflag isflag<?=$message['id'];?>" data-mid="<?=$message['id'];?>" data-fid="1" title="Click for important"></i>
<?php } ?>
<?php if(isset($message['is_deleted'])&&$message['is_deleted']==0){ ?>
<i class="fa-solid fa-trash text-danger isdelete" data-mid="<?=$message['id'];?>" data-fid="1" title="Delete"></i>
<?php }else{ ?>
<i class="fa-solid fa-envelope-circle-check text-warning isdelete" data-mid="<?=$message['id'];?>" data-fid="0" title="Move to inbox"></i>
<i class="fa-solid fa-square-xmark text-danger isdelete" data-mid="<?=$message['id'];?>" data-fid="3" title="Delete Permanent"></i>
<?php } ?>
<?php if(isset($message['isattachments'])&&$message['isattachments']==1&&$message['folder']!='Spam'){ ?>
&nbsp;<i class="fa-solid fa-paperclip" style="color: #000000;"></i>
<?php } ?>
<?php if(isset($message['folder'])&&$message['folder']=='Spam'&&$message['is_deleted']==0){ ?>
<i class="fa-solid fa-envelope-circle-check text-warning isdelete" data-mid="<?=$message['id'];?>" data-fid="2" title="Move to inbox"></i>
<?php } ?>


</div>
</td>

	<td class="hrefmodal tw-cursor-pointer <?php echo $mailcss;?> isread<?=$message['id'];?>" data-mid="<?=$message['id'];?>" data-fid="0" data-tid="<?=$message['subject'];?>" data-id="msg<?=$cnt;?>" title="<?=$message['subject'];?>" mailto="<?=htmlspecialchars($message['from_email']);?>" mailtox="<?=htmlspecialchars($message['to_emails']);?>" mailcc="<?=htmlspecialchars($message['cc_emails']);?>" mailbcc="<?=htmlspecialchars($message['bcc_emails']);?>" messageid="<?=$message['messageid'];?>" data-date="<?=$message['date'];?>"><div class="w-36 h-36 bg-red-600 rounded-full"></div> <span> <b><?=$message['subject'];?></b><br><?=htmlspecialchars($message['from_email']);?></span></td>
	<td class="w-25 text-end" style="min-width: 140px;"><span><?=$message['date'];?></span></td>
</tr>
<tr><td colspan="2" style="display:none;" id="msg<?=$cnt;?>">


<?php
echo '<iframe srcdoc="' . htmlspecialchars($message['body']) . '" style="width: 100%; min-height:50px; border: none;" onload="adjustIframeHeight(this)"></iframe>';
// Directory to save attachments

?>
<?php if(isset($message['attachments'])&&$message['attachments']){

$attachments = explode(',', $message['attachments']);

/////////////////////////
// Remove duplicates
$uniqueArray = array_unique($attachments);

// Convert back to a string
$attachments = implode(",", $uniqueArray);

////////////////////////
$attachments = explode(',', $attachments);
foreach($attachments as $attach){
$filePath = site_url() . '/' . $attach;
?>
<i class="fa-solid fa-paperclip"></i> <a href="<?=$filePath;?>" target="_blank" title="Click to view"><?=$filePath;?></a><br>
<?php }} ?>



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
$recordsPerPage = $_SESSION['mail_limit']; // Records per page
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
 
  <a class="btn btn-warning mtop10" id="reply-button"><i class="fa-solid fa-reply"></i> Reply</a>
</p>
<div class="collapse" id="reply-box">
  <div class="card card-body">
  
  
  
    <form action="<?=  admin_url('webmail/Reply') ?>" method="post" enctype="multipart/form-data">
	<!-- CSRF Token -->
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" 
               value="<?= $this->security->get_csrf_hash(); ?>">
	<input type="hidden" name="redirect" value="inbox.php">
	<input type="hidden" name="messageid" id="messageidIT" value="">
	<input type="hidden" name="messagetype" value="Reply">
      <div class="mb-3">
        <label for="recipientEmail" class="form-label">Recipient Email</label>
        <input type="text" class="form-control" id="recipientEmailIT" name="recipientEmail" value="" placeholder="Enter recipient email" required>
      </div>
	  <div class="mb-3">
        <label for="recipientCCIT" class="form-label mtop10">CC</label>
        <input type="text" class="form-control" id="recipientCCIT" name="recipientCC" value="" placeholder="Enter CC email" >
      </div>
	  <div class="mb-3">
        <label for="recipientBCCEmail" class="form-label mtop10">BCC</label>
        <input type="text" class="form-control" id="recipientBCCIT" name="recipientBCC" value="" placeholder="Enter BCC email" >
      </div>
      <div class="mb-3">
        <label for="emailSubject" class="form-label mtop10">Subject</label>
        <input type="text" class="form-control" id="emailSubjectIT" name="emailSubject" value="" placeholder="Enter email subject" required>
      </div>
      <div class="mb-3">
    
	   <textarea  name="emailBody" id="emailBody" class="form-control editor" required></textarea>
        <div class="checkbox checkbox-primary">
<input type="checkbox" id="toggleSignature" name="toggleSignature" value="1">
<label for="SignatureX">Add Signature</label>
</div>                       
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
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/editor/css/jquery-te.css'); ?>"/>

<script src="<?php echo base_url('assets/editor/js/jquery-te-1.4.0.min.js'); ?>"></script>

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
			$('.editor').val(formattedStr);
			$(".ailoader").html('<img src="<?php echo base_url('assets/images/artificial-intelligence.png')?>" title="Draft with AI"  style="width:30px;" />');
			// Usage example: Set cursor after 1 second
            setTimeout(setCursorToEnd, 2000);
			//alert("Please edit the content before send");
			
			}else{
			alert("Not Generated");
			$(".ailoader").html('<img src="<?php echo base_url('assets/images/artificial-intelligence.png')?>" title="Draft with AI"  style="width:30px;" />');
			
			}
			
            
        });
}else{
alert("Enter Correct Email Body with min length 5");
}

   
}
function setCursorToEnd() {
  var iframe = $('.jqte_editor')[0]; // Get the editable div (jqte_editor)
  var range = document.createRange();
  var selection = window.getSelection();

  range.selectNodeContents(iframe);
  range.collapse(false); // false = to end of the content
  selection.removeAllRanges();
  selection.addRange(range);
}
  </script>
<script>
$('.isread').click(function(){ 
         var mid=$(this).attr('data-mid');
		 var fid=$(this).attr('data-fid');
		 var resultid='.isread'+mid;
		//return;
		 $.post(admin_url + 'webmail/make_isread', {
            mid: mid,
			fid: fid
        })
        .done(function(response) { 
            response = JSON.parse(response);
			//alert(response.alert_type);
			//alert(response.message);
			
			if(response.alert_type=="success"){
			 //alert_float(response.alert_type, response.message);
			 $(resultid).removeClass('isread');
			}else{
			 //alert_float(response.alert_type, response.message);
			 
			}
            
        });
		 
});
  
  $('.hrefmodal').click(function(){ 

         //alert(11111);
         var tid=$(this).attr('data-tid');
		 var mailto=$(this).attr('mailto');
		 var mailtox=$(this).attr('mailtox');
		 var mailcc=$(this).attr('mailcc');
		 var mailbcc=$(this).attr('mailbcc');
		 var messageid=$(this).attr('messageid');
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
		 $('#recipientCCIT').val(mailcc);
		 $('#recipientBCCIT').val(mailbcc);
		 $('#messageidIT').val(messageid);
		 
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
        iframe.style.height = (iframe.contentWindow.document.body.scrollHeight + 20) + "px";
    }, 100);
}
$(document).ready(function() {
  setTimeout(function() {
    $('#lead-modal').removeAttr('id');
    console.log('ID removed from modal');
  }, 5000); // 5000 ms = 5 seconds
});
</script>

<script> 
 
$('.isflag').click(function(){ 
         //alert(11111);
         var mid=$(this).attr('data-mid');
		 var fid=$(this).attr('data-fid');
		 var resultid='.isflag'+mid;
		 //alert(resultid);
		 
		 $.post(admin_url + 'webmail/make_isflag', {
            mid: mid,
			fid: fid
        })
        .done(function(response) { 
            response = JSON.parse(response);
			//alert(response.alert_type);
			//alert(response.message);
			
			if(response.alert_type=="success"){
			 alert_float(response.alert_type, response.message);
			 $(resultid).removeClass('tw-text-info-300').addClass('tw-text-info-800');
			}else{
			 alert_float(response.alert_type, response.message);
			 $(resultid).removeClass('tw-text-info-800').addClass('tw-text-info-300');
			}
            
        });
		 
});

$('.isdelete').click(function(){ 
         //alert(11111);
         var mid=$(this).attr('data-mid');
		 var fid=$(this).attr('data-fid');
		 var resultid='.isdelete'+mid;
		 var tableid='.table'+mid;
		 if(fid==0){ var msgx="Un Delete";}else if(fid==1){var msgx="Delete";}else if(fid==2){var msgx="Move to inbox";}else{var msgx="Permanent Delete";}
		 if (!confirm('Are you sure you want to ' + msgx + ' this email?')) {
		 return false;
		 }
		 //alert(resultid);
		// return;
		 $.post(admin_url + 'webmail/make_isdelete', {
            mid: mid,
			fid: fid
        })
        .done(function(response) { 
            response = JSON.parse(response);
			//alert(response.alert_type);
			//alert(response.message);
			
			if(response.alert_type=="success"){
			 alert_float(response.alert_type, response.message);
			 $(resultid).removeClass('tw-text-warning-100').addClass('tw-text-warning-500'); 
			 $(tableid).hide();
			}else{
			 alert_float(response.alert_type, response.message);
			 $(resultid).removeClass('tw-text-warning-500').addClass('tw-text-warning-100');
			}
            
        });
		 
});

$('.refreshemail').click(function(){ 
		 $("#refresh-loader").addClass('fa-spin-pulse');
		 $.post(admin_url + 'webmail/refresh_email', {
        })
        .done(function(response) { 
            response = JSON.parse(response);
			//alert(response.alert_type);
			//alert(response.message);
			
			if(response.alert_type=="success"){
			 alert_float(response.alert_type, response.message);
			 $("#refresh-loader").removeClass('fa-spin-pulse');
			 location.reload(); // Reloads the current page
			}else{
			 alert_float(response.alert_type, response.message);
			 $("#refresh-loader").removeClass('fa-spin-pulse');
			}
            
        });
});
</script>
<script>
  //For Add /  Remove Signature
  //toggleSignature function define on asset/js/custom.js
  //need add css editor in jq editor textarea
  const signature = `<br><br><br><br><?php echo $email_signature;?>`;
</script>
</body>

</html>