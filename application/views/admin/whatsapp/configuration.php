<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
.card-wa-configuration{
    padding: 20px;
    background: #ffffff;
    border-radius: 5px;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;

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
                <div class="card-wa-configuration">
                <div class="table-responsive">
                    <a  data-toggle="modal" onclick="addNumber()" class="btn btn-sm btn-success" data-target="#addEditNewPhone">Add New +</a>
                    <div class="card-body ">
                        <!-- All Configuration Table -->
                        <table class="table table-clients number-index-2 dataTable no-footer">
                            <thead>
                            <tr role="row">
                                <th class="toggleable">Access Token</th>
                                <th class="toggleable">Phone Number Id</th>
                                <th class="toggleable">Phone Number</th>
                                <th class="toggleable">Webhook Verification Token</th>
                                <th class="toggleable">Type</th>
                                <th class="toggleable">Action</th>
                            </tr>
                            </thead>
                            <div class="wa-lodder">
                                    <img src="<?= base_url("assets/images/1488.gif") ?>" alt="">
                            </div>
                            <tbody id="tbody">
                                <!-- Body Populate here dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- addNewPhone Model -->
 <!-- Modal -->
 <div class="modal fade" id="addEditNewPhone"  role="dialog" aria-labelledby="addNewPhone" data-backdrop="static">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span>&times;</span>
        </button>
      </div>
      <form id="configurationForm">
            <div class="modal-body">
                <input type="text" name="source" id="source" value="crm">
                <input type="text" name="type" id="type">
                <input type="text" name="confId" id="confId">
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
                    <label>Phone Number</label>
                    <input name="phoneNumber" id="phoneNumber" type="text" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Webhook Verification Token</label>
                    <input name="webhookVerificationToken" id="webhookVerificationToken" type="text" class="form-control" required>
                </div>  
                <div class="form-group">
                    <label>Department</label>
                    <select name="department" id="department" class="form-control">
                        <option value="0">Select a department</option>
                        <?php foreach($departmentData as $DD){ ?>
                            <option value="<?= $DD['departmentid'] ?>"><?= $DD['name'] ?></option>
                        <?php } ?>
                    </select>
                </div> 
            </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
 <!-- End addNewPhone Model -->
<?php init_tail(); ?>
<script>
const url = "<?= Whatsapp_Api_Url ?>";
$(document).ready(function() {

    const source = "crm";
    const userId =$('#userId').val();
        $.ajax({
                url: `${url}/api/configuration/fetch/${source}`,
                method: 'GET',
                success: function(response) {
                    $('.wa-lodder').css('display', 'none');
                    console.log(response);
                    //Appending chat data to UI
                    if (Array.isArray(response)) {  // Check if response is an array
                        if(response.length >0){
                            let newRows = response.map(item => 
                                `<tr>
                                    <td style="max-width: 250px; word-wrap: break-word; white-space: normal;">${item.accessToken}</td>
                                    <td>${item.phoneNumberId}</td>
                                    <td>${item.phoneNumber}</td>
                                    <td>${item.webhookVerificationToken}</td>
                                    <td>${item.type}</td>
                                    <td><a href="#" data-toggle="modal" data-target="#addEditNewPhone" onclick="editNumber('${item._id}','${item.accessToken}',${item.phoneNumberId},${item.phoneNumber},'${item.webhookVerificationToken}','${item.type}')" class="btn btn-sm btn-success">Edit</a> <a href="#" onClick="deletePhone('${item._id}')" class="btn btn-sm btn-danger">Delete</a></td>
                                </tr>`
                            ).join(''); // Convert array to a string

                            $('#tbody').append(newRows); // Append all rows at once
                        }else{
                            const mess = `<span class="text-center">`;
                        }
                        } else {
                            console.error("Expected an array but got:", response);
                        }
                    },
                statusCode:{
                    400: function(response) {
                        $('.wa-lodder').css('display', 'none');
                        $('.card-wa-configuration').css('display', 'block');
                        console.error('Bad request:', response.responseJSON.message);
                    }
                }
            });
        });
</script>
<script>
    $('#configurationForm').on('submit', function(event) {
    event.preventDefault();
    const type =$('#type').val();
    console.log(type);
    var formData ={
        accessToken:$('#accessToken').val(),
        phoneNumberId:$('#phoneNumberId').val(),
        phoneNumber:$('#phoneNumber').val(),
        department:$('#department').val(),
        webhookVerificationToken:$('#webhookVerificationToken').val(),
        source:$('#source').val()
    };
    if(type=='Edit'){

    formData.config_id= $('#confId').val();
    }
    $.ajax({
            url: `${url}/api/configuration/save`,  // Replace with your Node.js API endpoint
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            success: function(response) {
                alert_float("success", "Configurtaion added successfully.");
                setTimeout(function() {
                            location.reload(); // Reload the page after 2 seconds
                }, 2000); // 2000 milliseconds = 2 seconds
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert_float("danger", "Somthing went wrong, Please try again later!");
                    setTimeout(function() {
                                location.reload(); // Reload the page after 2 seconds
                    }, 2000); // 2000 milliseconds = 2 seconds
            }
        });
});
function editNumber(confId, accessToken, phoneNumberId, phoneNumber, webhookVerificationToken, type){
    $('#confId, #accessToken, #phoneNumberId, #phoneNumber, #webhookVerificationToken, #type').val('');
    $('#exampleModalLabel').html('<strong>Edit Number</strong>');
    $('#confId').val(confId);
    $('#accessToken').val(accessToken);
    $('#phoneNumberId').val(phoneNumberId);
    $('#phoneNumber').val(phoneNumber);
    $('#webhookVerificationToken').val(webhookVerificationToken);
    $('#type').val("Edit");
}
function addNumber(){
$('#confId, #accessToken, #phoneNumberId, #phoneNumber, #webhookVerificationToken, #type').val('');
$('#exampleModalLabel').html('<strong>Add Number</strong>');
$('#type').val("Add");
}
function deletePhone(confId){
if (!confirm("Are you sure you want to delete this configuration?")) {
        return;
    }
$.ajax({
    url: `${url}/api/configuration/delete/${confId}`, // Adjust the endpoint as per your route
    method: 'DELETE',
    success: function(response) {
        alert_float("success", "Configuration added successfully");
        setTimeout(function() {
                    location.reload(); // Reload the page after 2 seconds
        }, 2000); // 2000 milliseconds = 2 seconds
    },
    error: function(xhr) {
        alert_float("danger", "Error deleting configuration");
        setTimeout(function() {
                    location.reload(); // Reload the page after 2 seconds
        }, 2000); // 2000 milliseconds = 2 seconds
    }
});
}

</script>
</body>

</html>