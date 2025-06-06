<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
#lead-modal .modal-lg{
width: 90% !important;
}
</style>
<div class="modal-header">
 <?php if(isset($lead->id)&&$lead->id){ ?>

	<button type="button" class="close reminder-open" id="reminderx"  data-target=".reminder-modal-lead-<?php echo $lead->id;?>" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<?php }else{ ?>
	<button type="button" class="close" aria-label="Close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
	<?php } ?>
	
		
	<h4 class="modal-title">
	<?php 
	if (isset($lead)) {
		if (!empty($lead->name)) {
			$name = $lead->name;
		} elseif (!empty($lead->company)) {
			$name = $lead->company;
		} else {
			$name = _l('lead');
		}
		echo '#' . $lead->id . ' - ' . $name;
	} else {
		echo _l('add_new', _l('lead_lowercase'));
	}

	if (isset($lead)) {
		echo '<div class="tw-ml-4 -tw-mt-2 tw-inline-block">';
		if ($lead->lost == 1) {
			echo '<span class="label label-danger">' . _l('lead_lost') . '</span>';
		} elseif ($lead->junk == 1) {
			echo '<span class="label label-warning">' . _l('lead_junk') . '</span>';
		} else {
			if (total_rows(db_prefix() . 'clients', [
				'leadid' => $lead->id, ])) {
				echo '<span class="label label-success">' . _l('lead_is_client') . '</span>';
			}
		}
		echo '</div>';
	}
	?>
	</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<?php if (isset($lead)) {
				echo form_hidden('leadid', $lead->id);
			} ?>
			<div class="top-lead-menu">
				<?php if (isset($lead)) { ?>
				<div class="horizontal-scrollable-tabs preview-tabs-top panel-full-width-tabs mbot20">
					<div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
					<div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
					<?php /*?>============Tab Section=========<?php */?>
					<div class="horizontal-tabs">
						<ul class="nav-tabs-horizontal nav nav-tabs<?php if (!isset($lead)) {
							echo ' lead-new';
						} ?>" role="tablist">
							<li role="presentation" class="active">
								<a href="#tab_lead_profile" aria-controls="tab_lead_profile" role="tab"
									data-toggle="tab">
									<?php echo _l('lead_profile'); ?>
								</a>
							</li>
							<?php if (isset($lead)) { ?>
							<?php if (count($mail_activity) > 0 || isset($show_email_activity) && $show_email_activity) { ?>
							<li role="presentation">
								<a href="#tab_email_activity" aria-controls="tab_email_activity" role="tab"
									data-toggle="tab">
									<?php echo hooks()->apply_filters('lead_email_activity_subject', _l('lead_email_activity')); ?>
								</a>
							</li>
							<?php } ?>
							<?php /*?><li role="presentation">
								<a href="#tab_proposals_leads"
									onclick="initDataTable('.table-proposals-lead', admin_url + 'proposals/proposal_relations/' + <?php echo e($lead->id); ?> + '/lead','undefined', 'undefined','undefined',[6,'desc']);"
									aria-controls="tab_proposals_leads" role="tab" data-toggle="tab">
									<?php echo _l('proposals');

									if ($total_proposals > 0) {
										echo ' <span class="badge">' . $total_proposals . '</span>';
									}
									?>
								</a>
							</li>
							<li role="presentation">
								<a href="#tab_tasks_leads"
									onclick="init_rel_tasks_table(<?php echo e($lead->id); ?>,'lead','.table-rel-tasks-leads');"
									aria-controls="tab_tasks_leads" role="tab" data-toggle="tab">
									<?php echo _l('tasks');
									if ($total_tasks > 0) {
										echo ' <span class="badge">' . $total_tasks . '</span>';
									}
									?>
								</a>
							</li><?php */?>
							<li role="presentation">
								<a href="#attachments" aria-controls="attachments" role="tab" data-toggle="tab">
									<?php echo _l('lead_attachments')."XXX";
									if ($total_attachments > 0) {
										echo ' <span class="badge">' . $total_attachments . '</span>';
									}
									?>
								</a>
							</li>
							<li role="presentation">
								<a href="#lead_reminders"
									onclick="initDataTable('.table-reminders-leads', admin_url + 'misc/get_reminders/' + <?php echo e($lead->id); ?> + '/' + 'lead', undefined, undefined,undefined,[1, 'asc']);"
									aria-controls="lead_reminders" role="tab" data-toggle="tab">
									<?php echo _l('leads_reminders_tab');
									if ($total_reminders > 0) {
										echo ' <span class="badge">' . $total_reminders . '</span>';
									}
									?>
								</a>
							</li>
							<li role="presentation">
								<a href="#lead_notes" aria-controls="lead_notes" role="tab" data-toggle="tab">
									<?php echo _l('lead_add_edit_notes');
									if ($total_notes > 0) {
										echo ' <span class="badge">' . $total_notes . '</span>';
									}
									?>
								</a>
							</li>
							<li role="presentation">
								<a href="#lead_activity" aria-controls="lead_activity" role="tab" data-toggle="tab">
									<?php echo _l('lead_add_edit_activity'); ?>
								</a>
							</li>
							<?php if (is_gdpr() && (get_option('gdpr_enable_lead_public_form') == '1' || get_option('gdpr_enable_consent_for_leads') == '1')) { ?>
							<li role="presentation">
								<a href="#gdpr" aria-controls="gdpr" role="tab" data-toggle="tab">
									<?php echo _l('gdpr_short'); ?>
								</a>
							</li>
							<?php } ?>
							<?php } ?>
							<?php
							if(e($lead->source)==4)
							{
							?>
							<li role="presentation">
								<a href="leads/telegram/<?php echo ($lead->client_id);?>"><?php echo _l('lead_conversion');?></a>
								<?php /*?>
								<a href="#tab_leads_conversions" onclick="" aria-controls="tab_leads_conversions" role="tab" data-toggle="tab"><?php echo _l('lead_conversion');?></a>
							<?php */?>
							</li>
							<?php
							}
							elseif(e($lead->source)==5)
							{
							?>
							<li role="presentation">
								<a href="leads/webchat/<?php echo ($lead->client_id);?>"><?php echo _l('lead_conversion');?></a>
							</li>
							<?php
							}
							?>
							<?php hooks()->do_action('after_lead_lead_tabs', $lead ?? null); ?>
						</ul>
					</div>
				</div>
				<?php } ?>
			</div>
			<!-- Tab panes -->
			<?php /*?>============Tab Content=========<?php */?>
			<div class="tab-content">
				<!-- from leads modal -->
				<div role="tabpanel" class="tab-pane active" id="tab_lead_profile">
					<?php $this->load->view('admin/leads/profile'); ?>
				</div>
				<?php if (isset($lead)) { ?>
				<?php if (count($mail_activity) > 0 || isset($show_email_activity) && $show_email_activity) { ?>
				<div role="tabpanel" class="tab-pane" id="tab_email_activity">
					<?php hooks()->do_action('before_lead_email_activity', ['lead' => $lead, 'email_activity' => $mail_activity]); ?>
					<?php foreach ($mail_activity as $_mail_activity) { ?>
					<div class="lead-email-activity">
						<div class="media-left">
							<i class="fa-regular fa-envelope"></i>
						</div>
						<div class="media-body">
							<h4 class="bold no-margin lead-mail-activity-subject">
								<?php echo e($_mail_activity['subject']); ?>
								<br />
								<small
									class="text-muted display-block mtop5 font-medium-xs"><?php echo e(_dt($_mail_activity['dateadded'])); ?></small>
							</h4>
							<div class="lead-mail-activity-body">
								<hr />
								<?php echo process_text_content_for_display($_mail_activity['body']); ?>
							</div>
							<hr />
						</div>
					</div>
					<div class="clearfix"></div>
					<?php } ?>
					<?php hooks()->do_action('after_lead_email_activity', ['lead_id' => $lead->id, 'emails' => $mail_activity]); ?>
				</div>
				<?php } ?>
				<?php if (is_gdpr() && (get_option('gdpr_enable_lead_public_form') == '1' || get_option('gdpr_enable_consent_for_leads') == '1' || (get_option('gdpr_data_portability_leads') == '1') && is_admin())) { ?>
				<div role="tabpanel" class="tab-pane" id="gdpr">
					<?php if (get_option('gdpr_enable_lead_public_form') == '1') { ?>
					<a href="<?php echo e($lead->public_url); ?>" target="_blank" class="mtop5">
						<?php echo _l('view_public_form'); ?>
					</a>
					<?php } ?>
					<?php if (get_option('gdpr_data_portability_leads') == '1' && is_admin()) { ?>
					<?php
					if (get_option('gdpr_enable_lead_public_form') == '1') {
						echo ' | ';
					}
					?>
					<a href="<?php echo admin_url('leads/export/' . $lead->id); ?>">
						<?php echo _l('dt_button_export'); ?>
					</a>
					<?php } ?>
					<?php if (get_option('gdpr_enable_lead_public_form') == '1' || (get_option('gdpr_data_portability_leads') == '1' && is_admin())) { ?>
					<hr class="-tw-mx-3.5" />
					<?php } ?>
					<?php if (get_option('gdpr_enable_consent_for_leads') == '1') { ?>
					<h4 class="no-mbot">
						<?php echo _l('gdpr_consent'); ?>
					</h4>
					<?php $this->load->view('admin/gdpr/lead_consent'); ?>
					<hr />
					<?php } ?>
				</div>
				<?php } ?>
				<div role="tabpanel" class="tab-pane" id="lead_activity">
					<div>
						<div class="activity-feed">
							<?php foreach ($activity_log as $log) { ?>
							<div class="feed-item">
								<div class="date">
									<span class="text-has-action" data-toggle="tooltip"
										data-title="<?php echo e(_dt($log['date'])); ?>">
										<?php echo e(time_ago($log['date'])); ?>
									</span>
								</div>
								<div class="text">
									<?php if ($log['staffid'] != 0) { ?>
									<a href="<?php echo admin_url('profile/' . $log['staffid']); ?>">
										<?php 
											echo staff_profile_image($log['staffid'], ['staff-profile-xs-image pull-left mright5']);
										?>
									</a>
									<?php
								}
								$additional_data = '';
								if (!empty($log['additional_data'])) {
									$additional_data = unserialize($log['additional_data']);
									echo ($log['staffid'] == 0) ? _l($log['description'], $additional_data) : e($log['full_name']) . ' - ' . _l($log['description'], $additional_data);
								} else {
									echo e($log['full_name']) . ' - ';
	
									if ($log['custom_activity'] == 0) {
										echo e(_l($log['description']));
									} else {
										echo process_text_content_for_display(_l($log['description'], '', false));
									}
								}
								?>
								</div>
							</div>
							<?php } ?>
						</div>
						<div class="col-md-12">
							<?php echo render_textarea('lead_activity_textarea', '', '', ['placeholder' => _l('enter_activity')], [], 'mtop15'); ?>
							<div class="text-right">
								<button id="lead_enter_activity"
									class="btn btn-primary"><?php echo _l('submit'); ?></button>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane" id="tab_proposals_leads">
					<?php if (staff_can('create', 'proposals')) { ?>
					<a href="<?php echo admin_url('proposals/proposal?rel_type=lead&rel_id=' . $lead->id); ?>"
						class="btn btn-primary mbot25"><?php echo _l('new_proposal'); ?></a>
					<?php } ?>
					<?php if (total_rows(db_prefix() . 'proposals', ['rel_type' => 'lead', 'rel_id' => $lead->id]) > 0 && (staff_can('create', 'proposals') || staff_can('edit', 'proposals'))) { ?>
					<a href="#" class="btn btn-primary mbot25" data-toggle="modal"
						data-target="#sync_data_proposal_data"><?php echo _l('sync_data'); ?></a>
					<?php $this->load->view('admin/proposals/sync_data', ['related' => $lead, 'rel_id' => $lead->id, 'rel_type' => 'lead']); ?>
					<?php } ?>
					<?php
					$table_data = [
						_l('proposal') . ' #',
						_l('proposal_subject'),
						_l('proposal_total'),
						_l('proposal_date'),
						_l('proposal_open_till'),
						_l('tags'),
						_l('proposal_date_created'),
						_l('proposal_status'), ];

					$custom_fields = get_custom_fields('proposal', ['show_on_table' => 1]);
					foreach ($custom_fields as $field) {
						array_push($table_data, [
							'name' => $field['name'],
							'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1],
						]);
					}
					$table_data = hooks()->apply_filters('proposals_relation_table_columns', $table_data);
					render_datatable($table_data, 'proposals-lead', [], [
						'data-last-order-identifier'=> 'proposals-relation',
						'data-default-order'		=> get_table_last_order('proposals-relation'),
					]);
				?>
				</div>
				<div role="tabpanel" class="tab-pane" id="tab_tasks_leads">
					<?php init_relation_tasks_table(['data-new-rel-id' => $lead->id, 'data-new-rel-type' => 'lead'], 'tasksFilters'); ?>
				</div>
				<div role="tabpanel" class="tab-pane" id="lead_reminders">
					<a href="#" data-toggle="modal" class="btn btn-default"
						data-target=".reminder-modal-lead-<?php echo e($lead->id); ?>"><i class="fa-regular fa-bell"></i>
						<?php echo _l('lead_set_reminder_title'); ?></a>
					<hr />
					<?php render_datatable([ _l('reminder_description'), _l('reminder_date'), _l('reminder_staff'), _l('reminder_is_notified')], 'reminders-leads'); ?>
				</div>
				<div role="tabpanel" class="tab-pane" id="attachments">
					<?php echo form_open('admin/leads/add_lead_attachment', ['class' => 'dropzone mtop15 mbot15', 'id' => 'lead-attachment-upload']); ?>
					<?php echo form_close(); ?>
					<?php if (get_option('dropbox_app_key') != '') { ?>
					<hr />
					<div class=" pull-left">
						<?php if (count($lead->attachments) > 0) { ?>
						<a href="<?php echo admin_url('leads/download_files/' . $lead->id); ?>" class="bold">
							<?php echo _l('download_all'); ?> (.zip)
						</a>
						<?php } ?>
					</div>
					<div class="tw-flex tw-justify-end tw-items-center tw-space-x-2">
						<button class="gpicker">
							<i class="fa-brands fa-google" aria-hidden="true"></i>
							<?php echo _l('choose_from_google_drive'); ?>
						</button>
						<div id="dropbox-chooser-lead"></div>
					</div>
					<div class=" clearfix"></div>
					<?php } ?>
					<?php if (count($lead->attachments) > 0) { ?>
					<div class="mtop20" id="lead_attachments">
						<?php $this->load->view('admin/leads/leads_attachments_template', ['attachments' => $lead->attachments]); ?>
					</div>
					<?php } ?>
				</div>
				<div role="tabpanel" class="tab-pane" id="lead_notes">
					<?php echo form_open(admin_url('leads/add_note/' . $lead->id), ['id' => 'lead-notes']); ?>
					<div class="form-group">
						<textarea id="lead_note_description" name="lead_note_description" class="form-control"
							rows="4"></textarea>
					</div>
					<div class="lead-select-date-contacted hide">
						<?php echo render_datetime_input('custom_contact_date', 'lead_add_edit_datecontacted', '', ['data-date-end-date' => date('Y-m-d')]); ?>
					</div>
					<div class="radio radio-primary">
						<input type="radio" name="contacted_indicator" id="contacted_indicator_yes" value="yes">
						<label
							for="contacted_indicator_yes"><?php echo _l('lead_add_edit_contacted_this_lead'); ?></label>
					</div>
					<div class="radio radio-primary">
						<input type="radio" name="contacted_indicator" id="contacted_indicator_no" value="no" checked>
						<label for="contacted_indicator_no"><?php echo _l('lead_not_contacted'); ?></label>
					</div>
					<button type="submit"
						class="btn btn-primary pull-right"><?php echo _l('lead_add_edit_add_note'); ?></button>
					<?php echo form_close(); ?>
					<div class="clearfix"></div>
					<hr />
					<?php
					$len	= count($notes);
					$i		= 0;
					foreach ($notes as $note) { ?>
					<div class="media lead-note">
						<a href="<?php echo admin_url('profile/' . $note['addedfrom']); ?>" target="_blank">
							<?php echo staff_profile_image($note['addedfrom'], ['staff-profile-image-small', 'pull-left mright10']); ?>
						</a>
						<div class="media-body">
							<?php if ($note['addedfrom'] == get_staff_user_id() || is_admin()) { ?>
							<a href="#" class="pull-right text-danger"
								onclick="delete_lead_note(this,<?php echo e($note['id']); ?>, <?php echo e($lead->id); ?>);return false;">
	
								<i class="fa fa fa-times"></i></a>
							<a href="#" class="pull-right mright5"
								onclick="toggle_edit_note(<?php echo e($note['id']); ?>);return false;">
								<i class="fa-regular fa-pen-to-square"></i></a>
							<?php } ?>
	
							<a href="<?php echo admin_url('profile/' . $note['addedfrom']); ?>" target="_blank">
								<h5 class="media-heading tw-font-semibold tw-mb-0">
								<?php if (!empty($note['date_contacted'])) { ?>
									<span data-toggle="tooltip"
										data-title="<?php echo e(_dt($note['date_contacted'])); ?>">
										<i class="fa fa-phone-square text-success" aria-hidden="true"></i>
									</span>
									<?php } ?>
									<?php echo e(get_staff_full_name($note['addedfrom'])); ?>
									</h5>
									<span class="tw-text-sm tw-text-neutral-500">
										<?php echo e(_l('lead_note_date_added', _dt($note['dateadded']))); ?>
									</span>
							</a>

							<div data-note-description="<?php echo e($note['id']); ?>" class="text-muted mtop10"><?php echo process_text_content_for_display($note['description']); ?></div>
							<div data-note-edit-textarea="<?php echo e($note['id']); ?>" class="hide mtop15">
								<?php echo render_textarea('note', '', $note['description']); ?>
								<div class="text-right">
									<button type="button" class="btn btn-default"
										onclick="toggle_edit_note(<?php echo e($note['id']); ?>);return false;"><?php echo _l('cancel'); ?></button>
									<button type="button" class="btn btn-primary"
										onclick="edit_note(<?php echo e($note['id']); ?>);"><?php echo _l('update_note'); ?></button>
								</div>
							</div>
						</div>
						<?php if ($i >= 0 && $i != $len - 1) {
							echo '<hr />';
						}
						?>
					</div>
					<?php $i++; } ?>
				</div>
				<?php
				if(e($lead->source)==4)
				{
				?>
				<div role="tabpanel" class="tab-pane" id="tab_leads_conversions">
					<div class="lead_conversion_list" id="lead_conversion_list">
						<?php //require_once "./telegram.php";?>
					</div>
					<div class="clearfix"></div>

					<div class="col-md-12">
						<?php echo render_input('telegram_send_message', 'send_as_plain_text', '', 'text'); ?>
						<div class="text-right">
							<button id="send_telegram_conv" data-url="/crm/telegram.php" class="btn btn-primary"><?php echo _l('send'); ?></button>
						</div>
					</div>
				</div>
				<?php } ?>
				<?php } ?>
				<?php hooks()->do_action('after_lead_tabs_content',$lead??null);?>
			</div>
		</div>
	</div>
</div>
<script>
    // open reminder modal from close button
    $('.reminder-open').click(function() {
      var targetModal = $(this).data('target');
      $(targetModal).modal('show');
	  $('#reminderx').removeClass('reminder-open');
    });
	
	// close leads modal on click reminder modal
	 $('.close-reminder-modal').click(function() { 
	 $('#lead-modal').modal('hide');

    });
</script>
<script>
chatBox	= document.querySelector(".lead_conversion_list");

/* code for retetrive telegram conversion*/
$(document).ready(function() {
	scrollToBottom();
	// Event listener for the send button
	$('#send_telegram_conv').click(function(e) {

		// alert(111);
		e.preventDefault(); // Prevent the default form submission

		// Get the URL from the button's data-url attribute
		var url = $(this).data('url');

		//alert(url)
		// Get the text from the textarea
		var message = $('input[name="telegram_send_message"]').val();

		if (message.trim() === '') {
			alert('Please enter a message!');
			return;
		}

		// Show a loading indicator if you want (optional)
		$('#lead_conversion_list').html('<p>Loading...</p>');

		// AJAX request
		$.ajax({
			url: url, // Use the dynamically fetched URL here
			type: 'POST', // Request method
			data: {
				telegram_message: message, // Sending the message to the specified URL
				lead_id: <?php echo e($lead->id);?>, // Sending the message to the specified URL
				staff_user_id: <?php echo e($_SESSION['staff_user_id']);?>,
			},
			success: function(response) {
				// Handle the response (e.g., display in the lead_conversion_list)
				$('#lead_conversion_list').html(response);
				// Clear the textarea after sending the message (optional)
				$('textarea[name="telegram_send_message"]').val('');
				scrollToBottom();
			},
			error: function(xhr, status, error) {
				// Handle errors if any
				$('#lead_conversion_list').html('<p>Error: ' + error + '</p>');
			}
		});
	});
	/* --- script for refresh div tag	--- */
	/* --- script for refresh div tag	--- */
});

function scrollToBottom(){
	//alert(chatBox.scrollHeight);
	chatBox.scrollTop=chatBox.scrollHeight;
}
</script>

<style>
/* Set a standard font size for all messages */
.message-text {
	font-size: 14px; /* Adjust to your preferred font size */
	line-height: 1.2;
}

/* Container for messages */
.message-container {
	display: flex;
	/*flex-direction: column-reverse; /* Ensure new messages are at the bottom */
	flex-direction: column; /* Ensure new messages are at the bottom */
	gap: 10px; /* Space between messages */
	height: 300px; /* Fixed height for the container */
	overflow-y: auto; /* Enable vertical scrolling */
	padding-right: 10px; /* Add padding to the right to prevent scrollbar overlap */
	width: 100%;
}

/* display date styles */
.disp_date {
	display: flex;
	flex-direction: column;
	align-items: center; /* Align to the center*/
	background-color:#00cc66; /* green background for incoming */
	color:#ffffff;
	padding: 1px;
	border-radius: 10px; /* Rounded corners*/
	margin: 0 auto;
	position: relative; /* To position*/
	width: 200px; /* Set width*/
}

/* Incoming message styles */
.incoming-msg {
	display: flex;
	flex-direction: column;
	align-items: flex-start; /* Align incoming messages to the left */
	background-color: #e1e1e1; /* Light gray background for incoming */
	padding: 10px;
	
	border-radius: 10px 20px 20px 0px; /* Rounded corners on top-left, top-right, bottom-right*/
	margin-right:auto;
	margin-left: 0; /* Keep incoming message on the left */
	max-width: 90%; /* Set max width for incoming messages */
	width: auto; /* Remove width restriction, let it expand based on content */
	position: relative; /* To position the cloud arrow */
	min-width: 200px; /* Set min width for incoming messages */
}

/* Outgoing message styles */
.outgoing-msg {
	display: flex;
	flex-direction: column;
	align-items: flex-end; /* Align outgoing messages to the right */
	background-color: #d5e8f8; /* Blue background for outgoing */
	/*color: white; /* Text color for outgoing message */
	padding: 10px;
	border-radius: 20px 10px 0px 20px; /* Rounded corners on top-left, top-right, bottom-left */
	max-width: 90%; /* Set max width for outgoing messages */
	width: auto; /* Remove width restriction, let it expand based on content */
	margin-right: 0; /* Keep outgoing message on the right */
	margin-left:auto;
	position: relative; /* To position the cloud arrow */
	min-width: 200px;	/* Set min width for outgoing messages */
}


/* Time stamp styles */
.send-time {
	font-size: 12px; /* Smaller font size for timestamp */
	color: #00CC66; /* Green color for send time */
	text-align: right;
	margin-top: 5px; /* Space between message and time */
	align-self: flex-end; /* Align timestamp to the right */
}

/* Make sure the message container takes full width if needed */
.message-container {
	width: 100%;
}
</style>
<?php 
hooks()->do_action('lead_modal_profile_bottom', (isset($lead) ? $lead->id : '')); 
?>