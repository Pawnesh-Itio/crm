<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
.card{
    padding: 20px;
    background: #ffffff;
    border-radius: 5px;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
}
.card-title{
    text-align:center;
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
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-title">
                        <h2>Direct Email</h2>
                    </div>
                    <div class="card-body">
                        <form id="directEmail">
                            <div class="row" style="padding:20px">
                                <div class="col">
                                    <div class="form-group">
                                        <div class="label">
                                           <span class="text-dark">To</span>
                                        </div>
                                        <input type="text" id="email" name="email" class="form-control" placeholder="E-mails (example@email.com;example2@gmail.com...)" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <div class="label">
                                           <span class="text-dark">Subject</span>
                                        </div>
                                        <input type="text" id="subject" name="subject" class="form-control" placeholder="Subject..." required>
                                    </div>
                                </div>
								

                                <div class="col">
                                    <div class="form-group">
                                     
                                        <textarea name="message" id="message" class="form-control editor"  placeholder="Message..."></textarea>
										
                                    </div>
                                </div>
																<div class="col">
	  <div class="tw-text-right">
	  <a name="send" class="ailoader" onclick="get_content();return false;"><img src="<?php echo base_url('assets/images/artificial-intelligence.png')?>" title="Draft with AI"  style="width:30px;" /></a>
	 
	  </div>

	  </div>
								
                                <div class="col">
                                    <div class="form-group">
                                    <button type="submit" style="padding-right:30px" id="sendMail" class="btn btn-primary btn-lg">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/editor/css/jquery-te.css'); ?>"/>

<script src="<?php echo base_url('assets/editor/js/jquery-te-1.4.0.min.js'); ?>"></script>

<script>
	$('.editor').jqte();
	 // Toggle AI BOX	
 function toggleCollapse() {
 const div = document.getElementById('collapseDiv');
    div.classList.toggle('hidden');
  }
  
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
$('#directEmail').on('submit', function(event){


    var recipientEmailIT=$.trim($('#email').val());
	var emailSubjectIT=$.trim($('#subject').val());
	var emailBody=$.trim($('#message').val());
        
		
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
		$("#sendMail").html("<i class='fa-solid fa-spinner fa-spin-pulse'></i>");
		}
		
    $("#sendMail").prop("disabled", true); // Disable submit button
    event.preventDefault();
    var formData = {
        email:$('#email').val(),
        subject:$('#subject').val(),
        message:$('#message').val()
    };
    $.ajax({
        url:'Direct_email/sendMail',
        method:'POST',
        data:formData,
        success: function(response){
            const data = JSON.parse(response);
            const httpCode = data.response.match(/\d{3}/)[0];
            $("#sendMail").prop("disabled", false); // Enable submit button
            if(httpCode==200){
			    $("#sendMail").html("Submit");
                // Success Message
                alert_float('success', "Mail sent succesfully!");
            }else{
			   $("#sendMail").html("Submit");
                // Failure Message
                alert_float('danger', "Failed to send mail. Please try again.");
            }
        },
        error: function (xhr, status, error){
            $("#sendMail").prop("disabled", false); // Enable submit button
            console.error('Error:',error);
            // Failure Message
            alert_float('danger', "Failed to send mail. Please try again.");
        }
    })    
});
$(document).ready(function() {
  setTimeout(function() {
    $('#lead-modal').removeAttr('id');
	$('#_task_modal').removeAttr('id');
    console.log('ID removed from modal');
  }, 5000); // 5000 ms = 5 seconds
});
</script>