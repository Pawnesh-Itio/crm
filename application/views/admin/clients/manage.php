<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
            <div id="ajaxAlert" class="alert d-none" role="alert"></div>
                <div class="_buttons">
                    <?php if (staff_can('create',  'customers')) { ?>
                    <a href="<?php echo admin_url('clients/client'); ?>"
                        class="btn btn-primary mright5 test pull-left display-block">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_client'); ?></a>
                    <a href="<?php echo admin_url('clients/import'); ?>"
                        class="btn btn-primary pull-left display-block mright5 hidden-xs">
                        <i class="fa-solid fa-upload tw-mr-1"></i>
                        <?php echo _l('import_customers'); ?></a>
                    <?php } ?>
                    <a href="<?php echo admin_url('clients/all_contacts'); ?>"
                        class="btn btn-default pull-left display-block mright5">
                        <i class="fa-regular fa-user tw-mr-1"></i>
                        <?php echo _l('customer_contacts'); ?>
                    </a>
                    <div class="visible-xs">
                        <div class="clearfix"></div>
                    </div>
                    <div id="vueApp" class="tw-inline pull-right tw-ml-0 sm:tw-ml-1.5">
                            <app-filters 
                                id="<?php echo $table->id(); ?>" 
                                view="<?php echo $table->viewName(); ?>"
                                :saved-filters="<?php echo $table->filtersJs(); ?>"
                                :available-rules="<?php echo $table->rulesJs(); ?>">
                        </app-filters>
                </div>
                </div>
                <div class="clearfix"></div>
                <div class="panel_s tw-mt-2 sm:tw-mt-4">
                    <div class="panel-body">

                        <?php if (staff_can('view',  'customers') || have_assigned_customers()) {
                      $where_summary = '';
                      if (staff_cant('view', 'customers')) {
                          $where_summary = ' AND userid IN (SELECT customer_id FROM ' . db_prefix() . 'customer_admins WHERE staff_id=' . get_staff_user_id() . ')';
                      } ?>
                        <div class="mbot15">
                            <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-flex tw-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor"
                                    class="tw-w-5 tw-h-5 tw-text-neutral-500 tw-mr-1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>

                                <span>
                                    <?php echo _l('client_summary'); ?>
                                </span>
                            </h4>
                            <div class="tw-grid tw-grid-cols-2 md:tw-grid-cols-3 lg:tw-grid-cols-6 tw-gap-2">
                                <div
                                    class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                    <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                        <?php echo total_rows(db_prefix() . 'clients', ($where_summary != '' ? substr($where_summary, 5) : '')); ?>
                                    </span>
                                    <span
                                        class="text-dark tw-truncate sm:tw-text-clip"><?php echo _l('customers_summary_total'); ?></span>
                                </div>
                                <?php /*?><div
                                    class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                    <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                        <?php echo total_rows(db_prefix() . 'clients', 'active=1' . $where_summary); ?></span>
                                    <span
                                        class="text-success tw-truncate sm:tw-text-clip"><?php echo _l('active_customers'); ?></span>
                                </div>
                                <div
                                    class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                    <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                        <?php echo total_rows(db_prefix() . 'clients', 'active=0' . $where_summary); ?></span>
                                    <span
                                        class="text-danger tw-truncate sm:tw-text-clip"><?php echo _l('inactive_active_customers'); ?></span>
                                </div><?php */?>
                                <div
                                    class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                    <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                        <?php echo total_rows(db_prefix() . 'contacts', 'active=1' . $where_summary); ?>
                                    </span>
                                    <span
                                        class="text-info tw-truncate sm:tw-text-clip"><?php echo _l('customers_summary_active'); ?></span>
                                </div>
                                <div
                                    class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                    <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                        <?php echo total_rows(db_prefix() . 'contacts', 'active=0' . $where_summary); ?>
                                    </span>
                                    <span
                                        class="text-danger tw-truncate sm:tw-text-clip"><?php echo _l('customers_summary_inactive'); ?></span>
                                </div>
                                <div
                                    class="tw-flex tw-items-center md:tw-border-r md:tw-border-solid tw-flex-1 md:tw-border-neutral-300 lg:tw-border-r-0">
                                    <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                        <?php echo total_rows(db_prefix() . 'contacts', 'last_login LIKE "' . date('Y-m-d') . '%"' . $where_summary); ?>
                                    </span>
                                    <span class="text-muted tw-truncate" data-toggle="tooltip"
                                        data-title="<?php echo _l('customers_summary_logged_in_today'); ?>">
                                        <?php
                                            $contactsTemplate = '';
                                                if (count($contacts_logged_in_today) > 0) {
                                                    foreach ($contacts_logged_in_today as $contact) {
                                                        $url          = admin_url('clients/client/' . $contact['userid'] . '?contactid=' . $contact['id']);
                                                        $fullName     = e($contact['firstname'] . ' ' . $contact['lastname']);
                                                        $dateLoggedIn = e(_dt($contact['last_login']));
                                                        $html         = "<a href='$url' target='_blank'>$fullName</a><br /><small>$dateLoggedIn</small><br />";
                                                        $contactsTemplate .= html_escape('<p class="mbot5">' . $html . '</p>');
                                                    } ?>
                                                <?php } ?>
                                        <span<?php if ($contactsTemplate != '') { ?> class="pointer text-has-action"
                                            data-toggle="popover"
                                            data-title="<?php echo _l('customers_summary_logged_in_today'); ?>"
                                            data-html="true" data-content="<?php echo $contactsTemplate; ?>"
                                            data-placement="bottom" <?php } ?>>
                                            <?php echo _l('customers_summary_logged_in_today'); ?>
 	                                   </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php
    	              } ?>
                        <hr class="hr-panel-separator" />
                        <a href="#" data-toggle="modal" data-target="#customers_bulk_action"
                            class="bulk-actions-btn table-btn hide"
                            data-table=".table-clients"><?php echo _l('bulk_actions'); ?></a>
                        <div class="modal fade bulk_actions" id="customers_bulk_action" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
                                    </div>
                                    <div class="modal-body">
                                        <?php if (staff_can('delete',  'customers')) { ?>
                                        <div class="checkbox checkbox-danger">
                                            <input type="checkbox" name="mass_delete" id="mass_delete">
                                            <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
                                        </div>
                                        <hr class="mass_delete_separator" />
                                        <?php } ?>
                                        <div id="bulk_change">
                                            <?php echo render_select('move_to_groups_customers_bulk[]', $groups, ['id', 'name'], 'customer_groups', '', ['multiple' => true], [], '', '', false); ?>
                                            <p class="text-danger">
                                                <?php echo _l('bulk_action_customers_groups_warning'); ?></p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default"
                                            data-dismiss="modal"><?php echo _l('close'); ?></button>
                                        <a href="#" class="btn btn-primary"
                                            onclick="customers_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                     $table_data  = [];
                     $_table_data = [
                      '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="clients"><label></label></div>',
                       [
                         'name'     => _l('the_number_sign'),
                         'th_attrs' => ['class' => 'toggleable', 'id' => 'th-number'],
                        ],
                         [
                         'name'     => _l('clients_list_company'),
                         'th_attrs' => ['class' => 'toggleable', 'id' => 'th-company'],
                        ],
                         [
                         'name'     => _l('contact_primary'),
                         'th_attrs' => ['class' => 'toggleable', 'id' => 'th-primary-contact'],
                        ],
                         [
                         'name'     => _l('company_primary_email'),
                         'th_attrs' => ['class' => 'toggleable', 'id' => 'th-primary-contact-email'],
                        ],
                        [
                         'name'     => _l('clients_list_phone'),
                         'th_attrs' => ['class' => 'toggleable', 'id' => 'th-phone'],
                        ],
                         [
                         'name'     => _l('customer_active'),
                         'th_attrs' => ['class' => 'toggleable', 'id' => 'th-active'],
                        ],
                        [
                         'name'     => _l('leads_dt_assigned'),
                         'th_attrs' => ['class' => 'toggleable', 'id' => 'th-assigned'],
                        ],
                        [
                         'name'     => _l('date_created'),
                         'th_attrs' => ['class' => 'toggleable', 'id' => 'th-date-created'],
                        ],
                        [
                         'name'     => 'Under Writing',
                         'th_attrs' => ['class' => 'toggleable', 'id' => 'th-underwriting'],
                        ],
                        [
                        'name'     => _l('chatBtn'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-date-created'],
                        ],
                      ];
                     foreach ($_table_data as $_t) {
                         array_push($table_data, $_t);
                     }

                     $custom_fields = get_custom_fields('customers', ['show_on_table' => 1]);

                     foreach ($custom_fields as $field) {
                         array_push($table_data, [
                           'name'     => $field['name'],
                           'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1],
                         ]);
                     }
                     $table_data = hooks()->apply_filters('customers_table_columns', $table_data);
                     ?>
                        <div class="panel-table-full">
                            <?php
                                render_datatable($table_data, 'clients', ['number-index-2'], [
                                    'data-last-order-identifier' => 'customers',
                                    'data-default-order'         => get_table_last_order('customers'),
                                    'id'=>'clients'
                                ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                    <div class="wa-lodder">
                        <img src="<?= base_url("assets/images/1488.gif") ?>" alt="">
                    </div>
                <div class="chat-container" id="chatContainer" style="background: url('<?= base_url('assets/images/chatbackground3.jpg')?>">
                    <!-- Message containet --> 
                </div>
                <div class="formBtnDiv">
                    <form id="messageForm" >
                        <input type="hidden" id="formUserId" value="<?= get_staff_user_id() ?>">
                        <input type="hidden" id="formNumber" class="formNumber" name="chatId"/>
                        <div class="message-input">
                            <input type="text" class="form-control" id="messageInput" placeholder="Type a message...">	
                            <button  type="submit" class="btn wa-btn" id="sendMessageBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" style="padding-top:3.5px" viewBox="0 0 50 25" width="50" height="24" fill="white"><path d="M2 21v-7l11-2-11-2V3l21 9-21 9z"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Assign Model -->
 <!-- Modal -->
 <div class="modal fade bd-example-modal-sm" id="contactAssignModel" tabindex="-1" role="dialog" aria-labelledby="leadAssignModel">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Contact's Assigned Staff</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span>&times;</span>
        </button>
      </div>
      <form id="assignClientsForm" action="<?= admin_url('clients/assign_staff') ?>" method="post">
        <div class="modal-body">
            <div class="form-group">
                <input type="hidden" name="contact_id" id="contact_id">
                <label>Assign Staff</label>
                    <select name="assigned_id" class="custom-select form-control" id="mySelect">
                    <!-- Dynamically Populate Here -->
                    </select>
            </div>
        </div>
        <div class="modal-footer">
            <button name="assignSubmit" type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
 <!-- End Contact Assign Model -->


</div>
<?php include_once(APPPATH . 'views/admin/leads/conversation.php'); ?>

<?php init_tail(); ?>

<!-- MySocket logic -->
<script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>
<script>
// Socket connection
const URL = "wss://wa-business-api.onrender.com";
const waURL = "https://wa-business-api.onrender.com";
const socket = io(URL);
socket.on('connect', () => {
    console.log('Connected to Socket.io server');
});
socket.on('disconnect', () => {
    console.log('Disconnected from Socket.io server');
});
socket.on('error', (error) => {
    console.log("Error:", error);
});

$(function() {
    var tAPI = initDataTable('.table-clients', admin_url + 'clients/table', [0], [0], {},
        <?php echo hooks()->apply_filters('customers_table_default_order', json_encode([2, 'asc'])); ?>);
});

function customers_bulk_action(event) {
    var r = confirm(app.lang.confirm_action_prompt);
    if (r == false) {
        return false;
    } else {
        var mass_delete = $('#mass_delete').prop('checked');
        var ids = [];
        var data = {};
        if (mass_delete == false || typeof(mass_delete) == 'undefined') {
            data.groups = $('select[name="move_to_groups_customers_bulk[]"]').selectpicker('val');
            if (data.groups.length == 0) {
                data.groups = 'remove_all';
            }
        } else {
            data.mass_delete = true;
        }
        var rows = $('.table-clients').find('tbody tr');
        $.each(rows, function() {
            var checkbox = $($(this).find('td').eq(0)).find('input');
            if (checkbox.prop('checked') == true) {
                ids.push(checkbox.val());
            }
        });
        data.ids = ids;
        $(event).addClass('disabled');
        setTimeout(function() {
            $.post(admin_url + 'clients/bulk_action', data).done(function() {
                window.location.reload();
            });
        }, 50);
    }
}
function getMessages(name,chatId){
    console.log(name);
    $('.modal-title').html(name+' ('+ chatId +')');
    $('#formNumber').val(chatId);
	$('.chat-container').html('');// Remove any exisiting listener before adding new one
    $.ajax({
        url: waURL+'/api/chat/messages/'+chatId,
        method: 'GET',
        success: function (data) {
            console.log(data);
            $('.formBtnDiv').show();
            $('.wa-lodder').hide();
            $('.chat-container').html('');
            // Add Messages to chat box.
            if(data.status != 'no_contact' && data.status !='no_messages'){
                data.messages.forEach(function (message) {
                    const messageClass = message.message_type === 'received' ? 'incoming-message' : 'sent-message';
                // Create a new div for each message
                const messageDiv = $('<div>'+message.message_body+'</div>') // Create the div
                    .attr('id', message.message_id) // Set message_id as the id attribute
                    .addClass(messageClass); // Optionally add a class for styling

                // Append the created message div to the body or a specific parent
                $('#chatContainer').append(messageDiv); // Or append to a specific container if needed
            });
        }else{
            const errSpan = "<span class='err_span text-center text-danger'>No Message found...</span>";
            $('.chat-container').append(errSpan);
        }
            autoScrollToBottom();
            // Add realtime incomming messages.
            setupChatSocketListener(chatId);

        },
        error: function () {
            $('.wa-lodder').hide();
            console.error('Failed to fetch data');
        }
    });
}
function setupChatSocketListener(chatId){
	socket.off('chat-' + chatId);
	socket.on('chat-' + chatId, (data)=>{
	console.log(data);//Checking.
	var type = data.type;
	var messageData = data.messageToInsert;//getting message body
	var data ; // Initializing empty variable'
	//Check if message is sent or status
	if(type=='received'){
        $('.err_span').html('');
	    data = '<div class="incoming-message">'+messageData.message_body+'</div>';
	    //Appending chat data to UI
	    $('.chat-container').append(data);
	    // Automatically scroll to the bottom of the chat box
	    $('#chatContainer').scrollTop($('#chatContainer')[0].scrollHeight);
	}else{
	    
	}
	});
}
// Function to automatically scroll the chat container to the bottom
function autoScrollToBottom() {
    var chatContainer = $('#chatContainer');
    chatContainer.scrollTop(chatContainer[0].scrollHeight);  // Scroll to the bottom
}
// Send Functionality...
$(document).ready(function() {
    $('#errSpan').html('');
    $('#messageForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission
        // Gather form data
        const source = "crm";
        const userId = $('#formUserId').val();
        const to = $('#formNumber').val();
        const message = $('#messageInput').val();
        const type = 1;

        // Send the data via AJAX
        $.ajax({
            type: 'POST',
            url: waURL+'/api/messages/send/', // Replace with your actual URL
            contentType: 'application/json',
            data: JSON.stringify({
                userId: userId,
                source: source,
                to: to,
                message: message,
                type: type
            }),
            success: function(response) {
                // Handle success (e.g., clear input, display message, etc.)
                const messageData = response.data.messages[0];
                $('#messageInput').val(''); // Clear the input after sending
                data = '<div id='+messageData.id+' class="sent-message">'+message+'</div>';
                //Appending chat data to UI
                $('.chat-container').append(data);
                // Automatically scroll to the bottom of the chat box
                autoScrollToBottom();
            },
            error: function(error) {
                // Handle error
                console.error('Error sending message:', error.message);
            }
        });
    });
});

function underWriting(userId){
        let button = $("#underwritingBtn"); // Select the button using its ID
        button.prop("disabled", true).addClass("disabled"); // Disable button
        // Send the data via AJAX
        $.ajax({
        type: 'POST',
        url:'clients/under_writing', // Replace with your actual URL
        data: {
            userId: userId
        },
        success: function(response) {
            let jsonResponse = JSON.parse(response);
            button.prop("disabled", false).removeClass("disabled"); // Re-enable on error
            if(jsonResponse.status){
                showAlert(jsonResponse.message, jsonResponse.status === "success" ? "alert-success" : "alert-danger");
            }else{
                showAlert("Somthing went wrong please try again later!", "alert-danger");
            }
        },
        error: function(error) {
            button.prop("disabled", false).removeClass("disabled"); // Re-enable on error
            // Handle error
            showAlert("Somthing went wrong please try again later!", "alert-danger");
            console.error('Error sending message:', error.message);
        }
    });
}
// Function to show and auto-hide the alert
function showAlert(message, alertType) {
    var alertBox = $("#ajaxAlert");
    alertBox.removeClass("d-none alert-success alert-danger") // Remove previous styles
           .addClass(alertType) // Add success or error class
           .html(message) // Set message
           .fadeIn(); // Show alert

    setTimeout(function() {
        alertBox.fadeOut(); // Hide after 5 sec
    }, 5000);
}
function contactAssign(id, Assigned_id){
    console.log("Contact Id:"+id);
    console.log("Assigned Id:"+Assigned_id);
     // Split Assigned_id into an array for comparison
    let assignedIds = Assigned_id.split(',').map(id => id.trim());
    let att ;
    $.ajax({
        url: '<?= admin_url('staff/getAllStaff') ?>',  // URL to send the request to
        type: 'GET',  // Request method, e.g., GET or POST
        dataType: 'json',  // Expected data format from the server
        success: function(response) {
            // Clear existing options
            $('#mySelect').empty();
            $('#contact_id').val(id);
            // Populate options from the server response
            response.forEach(function(item) {
                if(!assignedIds.includes(item.staffid.toString())){
                $('#mySelect').append(`<option value="${item.staffid}">${item.full_name}</option>`);
                }
            });
        },
        error: function(xhr, status, error) {
            // Handle any errors that occur
            console.error('An error occurred: ' + error);
        }
    });
}
</script>
</body>

</html>