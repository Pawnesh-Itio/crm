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
	<h4>&nbsp;&nbsp;History</h4>
	<?php foreach ($_SESSION['datalists'] as $rs) { ?>
                    <div class="panel-body panel-table-full">
					<div id="vueApp" class="tw-inline pull-right tw-ml-0 sm:tw-ml-1.5" data-v-app=""><button type="button" class="btn btn-default " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $rs['content_title'];?> </button>&nbsp;<button class="btn btn-default" title="Copy Content" onClick='CopyValTestbox("vkg<?php echo $rs['content_id'];?>")'>&nbsp;<i class="fa-solid fa-copy" aria-hidden="true"></i>&nbsp;</button></div>
					<div  style="clear:both;"></div>
					
					<div class="p-card" style="max-width:100% !important;" >
					Content : <?php echo $rs['content_title'];?>
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

<?php init_tail(); ?>
<script>
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
</script>

</body>

</html>