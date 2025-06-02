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


#wrapper { margin: 0 0 0 0px !important; }
#header { display:none !important; }


</style>

<div id="wrapper">
    <div class="content" style="background-image: linear-gradient(-225deg, #FFFEFF 0%, #D7FFFE 100%);">
        <div class="row">
		<?php if(!empty($_SESSION['mailersdropdowns'])){ ?>
            

            <div class="col-md-12">
			
			 <div class="row">
			 
			  <div class="col-md-7 mbot10"><div class="dt-buttons btn-group">
			 <?php if(!empty($_SESSION['inbox-total-email'])){ ?>
			  <button class="btn btn-default buttons-collection btn-sm btn-default-dt-options"  type="button" aria-haspopup="true"><span>Total</span></button> <?php if(isset($_SESSION['inbox-total-email'])&&!empty($_SESSION['inbox-total-email'])){?><button class="btn btn-default btn-sm btn-default-dt-options bg-danger" type="button" ><span><?=$_SESSION['inbox-total-email'];?></span></button></div>
<a href="<?php echo site_url('admin/webmail/compose'); ?>" target="_blank" class="btn btn-primary btn-sm mleft10">
<i class="fa-regular fa-paper-plane tw-mr-1"></i>
<?php echo _l('New Mail'); ?></a> 
<?php } ?>
			  <?php } ?> <span class="dropdown">
<button class="btn btn-default buttons-collection btn-default-dt-options dropdown-toggle" type="button" data-toggle="dropdown" ><span title="<?=$_SESSION['webmail']['mailer_email'];?>"><?=substr($_SESSION['webmail']['mailer_email'],0,18);?></span>
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
	<?php  foreach ($_SESSION['mailersdropdowns'] as $item) { ?>
	<li><a href="?mt=<?=$item['id'];?>&lead=1&ekey=<?=$_GET['skey'];?>"><?=$item['mailer_email'];?></a></li>
	<?php  } ?>
  </ul>
</span> <?php /*?>[<?=$_GET['skey'];?>]<?php */?>
			  
			  
			  </div>
			  <div class="col-md-5 mbot10">
			  
				</div>
				
			  </div>

        
                <div class="panel_s">
                    <div class="panel-body panel-table-full">

<?php if (count($inboxemail) == 0) { ?>
<div class="alert alert-info text-center">

    <?php echo _l('Records Not Found'); 
	$skey="";
	if(isset($_GET['skey'])&&$_GET['skey']){$skey=$_GET['skey'];} ?><br /><br />

	<a href='<?php echo site_url("admin/webmail/compose?id=$skey"); ?>' target="_blank" class="btn btn-primary btn-sm mleft10">
<i class="fa-regular fa-paper-plane tw-mr-1"></i>
<?php echo _l('Compose New Mail'); ?></a> 

</div>
<?php } ?>
<div class="tw-p-1 tw-rounded tw-border-danger-100">
 

<?php $cnt=101; foreach ($inboxemail as $message) { $cnt++; 

$string = "tw-bg-warning-600 tw-bg-primary-600 tw-bg-danger-600 tw-bg-danger-600 tw-bg-neutral-600 tw-bg-success-600 tw-bg-warning-800 tw-bg-primary-800 tw-bg-danger-800 tw-bg-danger-800 tw-bg-neutral-800 tw-bg-success-800";
// Step 1: Convert string to array of words
$words = preg_split('/\s+/', $string); // Split by spaces or multiple spaces
// Step 2: Select a random word
$randomWord = $words[array_rand($words)];


$mailbg="background-image: linear-gradient(-20deg, #e9defa 0%, #fbfcdb 100%);";
if($message['folder']=="INBOX"){
$mailbg="background-image: linear-gradient(to top, #e6e9f0 0%, #eef1f5 100%);";
}
?>



<div class="tw-my-2 tw-p-1 tw-bg-warning-100 tw-rounded" >
<div class="table-responsive" style="<?php echo $mailbg; ?>">
 <table class="table table-clients number-index-2 dataTable no-footer hrefmodal tw-cursor-pointer" data-tid="<?=$message['subject'];?>" data-id="msg<?=$cnt;?>" title="<?=$message['subject'];?>" mailto="<?=htmlspecialchars($message['from_email']);?>" mailtox="<?=htmlspecialchars($message['to_emails']);?>" mailcc="<?=htmlspecialchars($message['cc_emails']);?>" mailbcc="<?=htmlspecialchars($message['bcc_emails']);?>" messageid="<?=$message['messageid'];?>" data-date="<?=$message['date'];?>">
 <tr><td rowspan="5" style="width:100px;"><img src="<?php echo base_url('assets/images/'.$message['folder'].'.png')?>"  style="width: 100px;" /></td><td><b>Subject :</b></td><td><b><?=$message['subject'];?></b></td></tr>
 <tr><td style="width:100px;"><b>Receipient :</b></td><td><b><?=htmlspecialchars($message['to_emails']);?></b></td></tr>
 <tr><td><b>Sender :</b></td><td><b><?=htmlspecialchars($message['from_email']);?></b></td></tr>
 <tr><td colspan="2">
 <?php
 $html = $message['body']; // Paste your entire HTML content here

// Convert HTML to plain text
$plainText = strip_tags($html);

// Optional: Decode HTML entities
$plainText = html_entity_decode($plainText);

// Display or use the plain text
echo substr(nl2br($plainText),0,500); //
 ?></td></tr>
<tr><td colspan="2">
<button class="btn btn-warning btn-sm mleft10"><i class="fa-solid fa-reply-all tw-mr-1"></i> OPEN</button></td></tr>
</table>
 <div style="display:none;" id="msg<?=$cnt;?>">


<?php
echo '<iframe srcdoc="' . htmlspecialchars($message['body']) . '" style="width: 100%; min-height:50px; border: none;" onload="adjustIframeHeight(this)"></iframe>';
// Directory to save attachments

?>
<?php if(isset($message['attachments'])&&$message['attachments']){

$attachments = explode(',', $message['attachments']);

foreach($attachments as $attach){
$filePath = site_url() . '/' . $attach;
?>
<i class="fa-solid fa-paperclip"></i> <a href="<?=$filePath;?>" target="_blank" title="Click to view"><?=$filePath;?></a><br>
<?php }} ?>



</div>

</div>

</div>

<hr class="hr-text gradient" data-content="<?=date("d F Y H:i:s",strtotime($message['date']));?>">




<?php } ?>
</div>

 
  </div>

  



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
    <div class="modal-content" style="background-image: linear-gradient(-225deg, #E3FDF5 0%, #FFE6FA 100%);">
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
 
  <a class="btn btn-warning tw-my-2" id="reply-button"><i class="fa-solid fa-reply"></i> Reply</a>
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
        <label for="emailBody" class="form-label mtop10">Email Body</label>
    
	   <textarea  name="emailBody" id="emailBody" class="form-control editor" required></textarea>
        <div class="checkbox checkbox-primary">
<input type="checkbox" id="toggleSignature" name="toggleSignature" value="1">
<label for="SignatureX">Add Signature</label>
                               
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
		  //$('#myModal12 .modal-dialog').css({"margin-top": "0px"});
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
  //For Add /  Remove Signature
  //toggleSignature function define on asset/js/custom.js
  //need add css editor in jq editor textarea
  const signature = `<br><br><br><br><?php echo $email_signature;?>`;
</script>

</body>

</html>