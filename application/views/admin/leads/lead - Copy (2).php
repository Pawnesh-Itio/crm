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
			
			<!-- Tab panes -->
			<?php /*?>============Tab Content=========<?php */?>
			<div>
				<!-- from leads modal -->
				<div role="tabpanel" class="tab-pane active" id="tab_lead_profile">
					<?php $this->load->view('admin/leads/profile'); ?>
				</div>
				
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