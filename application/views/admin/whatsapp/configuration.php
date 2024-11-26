<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
.card-wa-configuration{
    padding: 20px;
    background: #ffffff;
    border-radius: 5px;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
    display: none;
}
.wa-lodder{
    position: absolute;
    left: 45%;
    width: 80%;

}
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
            <div class="wa-lodder">
                <img src="<?= base_url("assets/images/1488.gif") ?>" alt="">
            </div>
                <div class="card-wa-configuration">
                    <div class="card-body">
                        <form id="configurationForm">
                            <div class="form-group">
                                <label>Webhook url</label>
                                <input name="webhookUrl" id="webhookUrl" type="url" class="form-control" value="https://wa-business-api.onrender.com/api/messages/webhook/<?= get_staff_user_id() ?>" readonly>
                            </div>
                            <input type="hidden" name="userId" id="userId" value="<?= get_staff_user_id() ?>">
                            <div class="form-group">
                                <label>Access Token</label>
                                <input name="accessToken" id="accessToken" type="text" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Phone number Id</label>
                                <input name="phoneNumberId" id="phoneNumberId" type="text" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Webhook Verification Token</label>
                                <input name="webhookVerificationToken" id="webhookVerificationToken" type="text" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
$(document).ready(function() {
    const userId =$('#userId').val();
        $.ajax({
                url: `https://wa-business-api.onrender.com/api/configuration/fetch/${userId}`,
                method: 'GET',
                success: function(response) {
                    $('.wa-lodder').css('display', 'none');
                    $('.card-wa-configuration').css('display', 'block');
                    console.log(response);
                    $('#accessToken').val(response.accessToken);
                    $('#phoneNumberId').val(response.phoneNumberId);
                    $('#webhookVerificationToken').val(response.webhookVerificationToken);
                },
                statusCode:{
                    400: function(response) {
                        console.error('Bad request:', response.responseJSON.message);
                    }
                }
            });
        });
</script>
<script>
    $('#configurationForm').on('submit', function(event) {
    event.preventDefault();
    var formData ={
        userId:$('#userId').val(),
        accessToken:$('#accessToken').val(),
        phoneNumberId:$('#phoneNumberId').val(),
        webhookVerificationToken:$('#webhookVerificationToken').val()
    };
    $.ajax({
            url: 'https://wa-business-api.onrender.com/api/configuration/save',  // Replace with your Node.js API endpoint
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            success: function(response) {
                alert('Data successfully sent to Node.js API!');
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('There was an error sending data.');
            }
        });
});
</script>
</body>

</html>