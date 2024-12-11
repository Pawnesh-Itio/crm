<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="_buttons tw-mb-2 sm:tw-mb-4">
                    <a href="#" onclick="init_lead(); return false;"
                        class="btn btn-primary mright5 pull-left display-block">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_lead'); ?>
                    </a>
                    <?php if (is_admin() || get_option('allow_non_admin_members_to_import_leads') == '1') { ?>
                    <a href="<?php echo admin_url('leads/import'); ?>"
                        class="btn btn-primary pull-left display-block hidden-xs">
                        <i class="fa-solid fa-upload tw-mr-1"></i>
                        <?php echo _l('import_leads'); ?>
                    </a>
                    <?php } ?>
                    <div class="row">
                        <div class="col-sm-5 ">
                            <a href="#" class="btn btn-default btn-with-tooltip" data-toggle="tooltip"
                                data-title="<?php echo _l('leads_summary'); ?>" data-placement="top"
                                onclick="slideToggle('.leads-overview'); return false;"><i
                                    class="fa fa-bar-chart"></i></a>
                            <a href="<?php echo admin_url('leads/switch_kanban/' . $switch_kanban); ?>"
                                class="btn btn-default mleft5 hidden-xs" data-toggle="tooltip" data-placement="top"
                                data-title="<?php echo $switch_kanban == 1 ? _l('leads_switch_to_kanban') : _l('switch_to_list_view'); ?>">
                                <?php if ($switch_kanban == 1) { ?>
                                <i class="fa-solid fa-grip-vertical"></i>
                                <?php } else { ?>
                                <i class="fa-solid fa-table-list"></i>
                                <?php }; ?>
                            </a>
                        </div>
                        <div class="col-sm-4 col-xs-12 pull-right leads-search">
                            <?php if ($this->session->userdata('leads_kanban_view') == 'true') { ?>
                            <div data-toggle="tooltip" data-placement="top"
                                data-title="<?php echo _l('search_by_tags'); ?>">
                                <?php echo render_input('search', '', '', 'search', ['data-name' => 'search', 'onkeyup' => 'leads_kanban();', 'placeholder' => _l('leads_search')], [], 'no-margin') ?>
                            </div>
                            <?php } else { ?>
                            <div id="vueApp" class="tw-inline pull-right">
                                <app-filters
                                    id="<?php echo $table->id(); ?>"
                                    view="<?php echo $table->viewName(); ?>"
                                    :rules="<?php echo app\services\utilities\Js::from($this->input->get('status') ? $table->findRule('status')->setValue([$this->input->get('status')]) : []); ?>"
                                    :saved-filters="<?php echo $table->filtersJs(); ?>"
                                    :available-rules="<?php echo $table->rulesJs(); ?>">
                                </app-filters>
                            </div>
                            <?php } ?>
                            <?php echo form_hidden('sort_type'); ?>
                            <?php echo form_hidden('sort', (get_option('default_leads_kanban_sort') != '' ? get_option('default_leads_kanban_sort_type') : '')); ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="hide leads-overview tw-mt-2 sm:tw-mt-4 tw-mb-4 sm:tw-mb-0">
                        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg">
                            <?php echo _l('leads_summary'); ?>
                        </h4>
                        <div class="tw-flex tw-flex-wrap tw-flex-col lg:tw-flex-row tw-w-full tw-gap-3 lg:tw-gap-6">
                            <?php
                           foreach ($summary as $status) { ?>
                            <div
                                class="lg:tw-border-r lg:tw-border-solid lg:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center last:tw-border-r-0">
                                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    <?php
                                          if (isset($status['percent'])) {
                                              echo '<span data-toggle="tooltip" data-title="' . $status['total'] . '">' . $status['percent'] . '%</span>';
                                          } else {
                                              // Is regular status
                                              echo $status['total'];
                                          }
                                       ?>
                                </span>
                                <span style="color:<?php echo e($status['color']); ?>"
                                    class="<?php echo isset($status['junk']) || isset($status['lost']) ? 'text-danger' : ''; ?>">
                                    <?php echo e($status['name']); ?>
                                </span>
                            </div>
                            <?php } ?>
                        </div>

                    </div>
                </div>
                <div class="<?php echo $isKanBan ? '' : 'panel_s' ; ?>">
                    <div class="<?php echo $isKanBan ? '' : 'panel-body' ; ?>">
                        <div class="tab-content">
                            <?php
                        if ($isKanBan) { ?>
                            <div class="active kan-ban-tab" id="kan-ban-tab" style="overflow:auto;">
                                <div class="kanban-leads-sort">
                                    <span class="bold"><?php echo _l('leads_sort_by'); ?>: </span>
                                    <a href="#" onclick="leads_kanban_sort('dateadded'); return false"
                                        class="dateadded">
                                        <?php if (get_option('default_leads_kanban_sort') == 'dateadded') {
                            echo '<i class="kanban-sort-icon fa fa-sort-amount-' . strtolower(get_option('default_leads_kanban_sort_type')) . '"></i> ';
                        } ?><?php echo _l('leads_sort_by_datecreated'); ?>
                                    </a>
                                    |
                                    <a href="#" onclick="leads_kanban_sort('leadorder');return false;"
                                        class="leadorder">
                                        <?php if (get_option('default_leads_kanban_sort') == 'leadorder') {
                            echo '<i class="kanban-sort-icon fa fa-sort-amount-' . strtolower(get_option('default_leads_kanban_sort_type')) . '"></i> ';
                        } ?><?php echo _l('leads_sort_by_kanban_order'); ?>
                                    </a>
                                    |
                                    <a href="#" onclick="leads_kanban_sort('lastcontact');return false;"
                                        class="lastcontact">
                                        <?php if (get_option('default_leads_kanban_sort') == 'lastcontact') {
                            echo '<i class="kanban-sort-icon fa fa-sort-amount-' . strtolower(get_option('default_leads_kanban_sort_type')) . '"></i> ';
                        } ?><?php echo _l('leads_sort_by_lastcontact'); ?>
                                    </a>
                                </div>
                                <div class="row">
                                    <div class="container-fluid leads-kan-ban">
                                        <div id="kan-ban"></div>
                                    </div>
                                </div>
                            </div>
                            <?php } else { ?>
                            <div class="row" id="leads-table">
                                <div class="col-md-12">
                                    <a href="#" data-toggle="modal" data-table=".table-leads"
                                        data-target="#leads_bulk_actions"
                                        class="hide bulk-actions-btn table-btn"><?php echo _l('bulk_actions'); ?></a>
                                    <div class="modal fade bulk_actions" id="leads_bulk_actions" tabindex="-1"
                                        role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close"><span
                                                            aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
                                                </div>
                                                <div class="modal-body">
                                                    <?php if (staff_can('delete',  'leads')) { ?>
                                                    <div class="checkbox checkbox-danger">
                                                        <input type="checkbox" name="mass_delete" id="mass_delete">
                                                        <label
                                                            for="mass_delete"><?php echo _l('mass_delete'); ?></label>
                                                    </div>
                                                    <hr class="mass_delete_separator" />
                                                    <?php } ?>
                                                    <div id="bulk_change">
                                                        <div class="form-group">
                                                            <div class="checkbox checkbox-primary checkbox-inline">
                                                                <input type="checkbox" name="leads_bulk_mark_lost"
                                                                    id="leads_bulk_mark_lost" value="1">
                                                                <label for="leads_bulk_mark_lost">
                                                                    <?php echo _l('lead_mark_as_lost'); ?>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <?php echo render_select('move_to_status_leads_bulk', $statuses, ['id', 'name'], 'ticket_single_change_status'); ?>
                                                        <?php
                                             echo render_select('move_to_source_leads_bulk', $sources, ['id', 'name'], 'lead_source');
                                             echo render_datetime_input('leads_bulk_last_contact', 'leads_dt_last_contact');
                                             echo render_select('assign_to_leads_bulk', $staff, ['staffid', ['firstname', 'lastname']], 'leads_dt_assigned');
                                             ?>
                                                        <div class="form-group">
                                                            <?php echo '<p><b><i class="fa fa-tag" aria-hidden="true"></i> ' . _l('tags') . ':</b></p>'; ?>
                                                            <input type="text" class="tagsinput" id="tags_bulk"
                                                                name="tags_bulk" value="" data-role="tagsinput">
                                                        </div>
                                                        <hr />
                                                        <div class="form-group no-mbot">
                                                            <div class="radio radio-primary radio-inline">
                                                                <input type="radio" name="leads_bulk_visibility"
                                                                    id="leads_bulk_public" value="public">
                                                                <label for="leads_bulk_public">
                                                                    <?php echo _l('lead_public'); ?>
                                                                </label>
                                                            </div>
                                                            <div class="radio radio-primary radio-inline">
                                                                <input type="radio" name="leads_bulk_visibility"
                                                                    id="leads_bulk_private" value="private">
                                                                <label for="leads_bulk_private">
                                                                    <?php echo _l('private'); ?>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal"><?php echo _l('close'); ?></button>
                                                    <a href="#" class="btn btn-primary"
                                                        onclick="leads_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->
                                    <?php

                              $table_data  = [];
                              $_table_data = [
                                '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="leads"><label></label></div>',
                                [
                                 'name'     => _l('the_number_sign'),
                                 'th_attrs' => ['class' => 'toggleable', 'id' => 'th-number'],
                               ],
                               [
                                 'name'     => _l('leads_dt_name'),
                                 'th_attrs' => ['class' => 'toggleable', 'id' => 'th-name'],
                               ],
                              ];
                              if (is_gdpr() && get_option('gdpr_enable_consent_for_leads') == '1') {
                                  $_table_data[] = [
                                    'name'     => _l('gdpr_consent') . ' (' . _l('gdpr_short') . ')',
                                    'th_attrs' => ['id' => 'th-consent', 'class' => 'not-export'],
                                 ];
                              }
                              $_table_data[] = [
                               'name'     => _l('lead_company'),
                               'th_attrs' => ['class' => 'toggleable', 'id' => 'th-company'],
                              ];
                              $_table_data[] = [
                               'name'     => _l('leads_dt_email'),
                               'th_attrs' => ['class' => 'toggleable', 'id' => 'th-email'],
                              ];
                              $_table_data[] = [
                               'name'     => _l('leads_dt_phonenumber'),
                               'th_attrs' => ['class' => 'toggleable', 'id' => 'th-phone'],
                              ];
                              $_table_data[] = [
                                 'name'     => _l('leads_dt_lead_value'),
                                 'th_attrs' => ['class' => 'toggleable', 'id' => 'th-lead-value'],
                                ];
                              $_table_data[] = [
                               'name'     => _l('tags'),
                               'th_attrs' => ['class' => 'toggleable', 'id' => 'th-tags'],
                              ];
                              $_table_data[] = [
                               'name'     => _l('leads_dt_assigned'),
                               'th_attrs' => ['class' => 'toggleable', 'id' => 'th-assigned'],
                              ];
                              $_table_data[] = [
                               'name'     => _l('leads_dt_status'),
                               'th_attrs' => ['class' => 'toggleable', 'id' => 'th-status'],
                              ];
                              $_table_data[] = [
                               'name'     => _l('leads_source'),
                               'th_attrs' => ['class' => 'toggleable', 'id' => 'th-source'],
                              ];
                              $_table_data[] = [
                               'name'     => _l('leads_dt_last_contact'),
                               'th_attrs' => ['class' => 'toggleable', 'id' => 'th-last-contact'],
                              ];
                              $_table_data[] = [
                                'name'     => _l('leads_dt_datecreated'),
                                'th_attrs' => ['class' => 'date-created toggleable', 'id' => 'th-date-created'],
                              ];
                              foreach ($_table_data as $_t) {
                                  array_push($table_data, $_t);
                              }
                              $custom_fields = get_custom_fields('leads', ['show_on_table' => 1]);
                              foreach ($custom_fields as $field) {
                                  array_push($table_data, [
                                   'name'     => $field['name'],
                                   'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1],
                                ]);
                              }
                              $table_data = hooks()->apply_filters('leads_table_columns', $table_data);
                               ?>
                                    <div class="panel-table-full">
                                        <?php
                                 render_datatable(
                                   $table_data,
                                   'leads',
                                   ['customizable-table number-index-2'],
                                   [
                                    'id'                         => 'leads',
                                    'data-last-order-identifier' => 'leads',
                                    'data-default-order'         => get_table_last_order('leads'),
                                 ]
                               );
                                ?>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
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
                <div id ="errdiv"></div>
                <div class="chat-container" id="chatContainer">
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
function getMessages(element){
    $('#lead-modal').modal('hide');
    var name = element.getAttribute('data-name');
    var chatId = parseInt(element.getAttribute('data-number'));
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
            const errSpan = "<span class='text-center'>No Message found...</span>";
            $('#errdiv').append(errSpan);
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
    console.log(chatId);
	socket.off('chat-' + chatId);
	socket.on('chat-' + chatId, (data)=>{
	console.log(data);//Checking.
	var type = data.type;
	var messageData = data.messageToInsert;//getting message body
	var data ; // Initializing empty variable'
	//Check if message is sent or status
	if(type=='received'){
	    data = '<div class="incoming-message">'+messageData.message_body+'</div>';
	    //Appending chat data to UI
	    $('.chat-container').append(data);
	    // Automatically scroll to the bottom of the chat box
	    $('#chatContainer').scrollTop($('#chatContainer')[0].scrollHeight);
	}else{
	    
	}
	});
}
</script>
<!-- End Socket lOgic -->
<script id="hidden-columns-table-leads" type="text/json">
<?php echo get_staff_meta(get_staff_user_id(), 'hidden-columns-table-leads'); ?>
</script>
<?php include_once(APPPATH . 'views/admin/leads/status.php'); ?>

<?php include_once(APPPATH . 'views/admin/leads/conversation.php'); ?>

<?php init_tail(); ?>
<script>
var openLeadID = '<?php echo e($leadid); ?>';
$(function() {
    leads_kanban();
    $('#leads_bulk_mark_lost').on('change', function() {
        $('#move_to_status_leads_bulk').prop('disabled', $(this).prop('checked') == true);
        $('#move_to_status_leads_bulk').selectpicker('refresh')
    });
    $('#move_to_status_leads_bulk').on('change', function() {
        if ($(this).selectpicker('val') != '') {
            $('#leads_bulk_mark_lost').prop('disabled', true);
            $('#leads_bulk_mark_lost').prop('checked', false);
        } else {
            $('#leads_bulk_mark_lost').prop('disabled', false);
        }
    });
});
// Function to automatically scroll the chat container to the bottom
function autoScrollToBottom() {
    var chatContainer = $('#chatContainer');
    chatContainer.scrollTop(chatContainer[0].scrollHeight);  // Scroll to the bottom
}
$(document).ready(function() {
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
                console.error('Error sending message:', error);
            }
        });
    });
});
</script>
</body>

</html>