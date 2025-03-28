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
                                        <div class="label">
                                           <span class="text-dark">Message</span>
                                        </div>
                                        <textarea name="message" id="message" class="form-control"  placeholder="Message..." required></textarea>
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
<script>
$('#directEmail').on('submit', function(event){
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
                // Success Message
                alert_float('success', "Mail sent succesfully!");
            }else{
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
</script>