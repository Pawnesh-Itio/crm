<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="card-wa-configuration">
                    <div class="card-body">
                        <div class="whatsapp-box">
                            <div class="row">
                                <div class="col-md-3 wa-side-bar-col">
                                    <div class="whatsapp-side-bar">
                                        <div class="sidebar-header">
                                            <ul class="sidebar-head-item">
                                                <li>
                                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="60"
                                                        height="60" viewBox="0,0,300,150">
                                                        <g fill="none" fill-rule="nonzero" stroke="none"
                                                            stroke-width="1" stroke-linecap="butt"
                                                            stroke-linejoin="miter" stroke-miterlimit="10"
                                                            stroke-dasharray="" stroke-dashoffset="0" font-family="none"
                                                            font-weight="none" font-size="none" text-anchor="none"
                                                            style="mix-blend-mode: normal">
                                                            <g transform="scale(6.4,6.4)">
                                                                <path
                                                                    d="M4.221,29.298l-0.104,-0.181c-1.608,-2.786 -2.459,-5.969 -2.458,-9.205c0.004,-10.152 8.267,-18.412 18.419,-18.412c4.926,0.002 9.553,1.919 13.03,5.399c3.477,3.48 5.392,8.107 5.392,13.028c-0.005,10.153 -8.268,18.414 -18.42,18.414c-3.082,-0.002 -6.126,-0.776 -8.811,-2.24l-0.174,-0.096l-9.385,2.46z"
                                                                    fill="#f2faff"></path>
                                                                <path
                                                                    d="M20.078,2v0c4.791,0.001 9.293,1.867 12.676,5.253c3.383,3.386 5.246,7.887 5.246,12.674c-0.005,9.878 -8.043,17.914 -17.927,17.914c-2.991,-0.001 -5.952,-0.755 -8.564,-2.18l-0.349,-0.19l-0.384,0.101l-8.354,2.19l2.226,-8.131l0.11,-0.403l-0.208,-0.361c-1.566,-2.711 -2.393,-5.808 -2.391,-8.955c0.004,-9.876 8.043,-17.912 17.919,-17.912M20.078,1c-10.427,0 -18.915,8.485 -18.92,18.912c-0.002,3.333 0.869,6.588 2.525,9.455l-2.683,9.802l10.03,-2.63c2.763,1.507 5.875,2.3 9.042,2.302h0.008c10.427,0 18.915,-8.485 18.92,-18.914c0,-5.054 -1.966,-9.807 -5.538,-13.382c-3.572,-3.574 -8.322,-5.543 -13.384,-5.545z"
                                                                    fill="#788b9c"></path>
                                                                <path
                                                                    d="M19.995,35c-2.504,-0.001 -4.982,-0.632 -7.166,-1.823l-1.433,-0.782l-1.579,0.414l-3.241,0.85l0.83,-3.03l0.453,-1.656l-0.859,-1.488c-1.309,-2.267 -2.001,-4.858 -2,-7.492c0.004,-8.267 6.732,-14.992 14.998,-14.993c4.011,0.001 7.779,1.563 10.61,4.397c2.833,2.834 4.392,6.602 4.392,10.608c-0.004,8.268 -6.732,14.995 -15.005,14.995z"
                                                                    fill="#1e293b"></path>
                                                                <path
                                                                    d="M28.28,23.688c-0.45,-0.224 -2.66,-1.313 -3.071,-1.462c-0.413,-0.151 -0.712,-0.224 -1.012,0.224c-0.3,0.45 -1.161,1.462 -1.423,1.761c-0.262,0.3 -0.524,0.337 -0.974,0.113c-0.45,-0.224 -1.899,-0.7 -3.615,-2.231c-1.337,-1.191 -2.239,-2.663 -2.501,-3.113c-0.262,-0.45 -0.029,-0.693 0.197,-0.917c0.202,-0.202 0.45,-0.525 0.674,-0.787c0.224,-0.262 0.3,-0.45 0.45,-0.75c0.151,-0.3 0.075,-0.563 -0.038,-0.787c-0.113,-0.224 -1.012,-2.437 -1.387,-3.336c-0.364,-0.876 -0.736,-0.757 -1.012,-0.771c-0.262,-0.014 -0.562,-0.015 -0.861,-0.015c-0.3,0 -0.787,0.113 -1.198,0.563c-0.411,0.45 -1.573,1.537 -1.573,3.749c0,2.212 1.611,4.35 1.835,4.649c0.224,0.3 3.169,4.839 7.68,6.786c1.072,0.462 1.911,0.739 2.562,0.947c1.076,0.342 2.057,0.294 2.832,0.178c0.864,-0.129 2.66,-1.087 3.034,-2.136c0.375,-1.049 0.375,-1.95 0.262,-2.136c-0.111,-0.192 -0.41,-0.305 -0.861,-0.529z"
                                                                    fill="#ffffff"></path>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </li>
                                                <li>
                                                    <span class="sidebar-head-text">Whatsapp Chats</span>
                                                </li>
                                            </ul>
                                            <hr style="border: 1px solid #1e293b">
                                        </div>
                                        <div class="sidebar-body">
                                            <ul>
                                                <?php foreach($chatData as $cd){ ?>
                                                <a class="chat-link" >
                                                    <li class="chat-item" onclick="getMessage(this,<?= $cd['phonenumber'] ?>,'<?= $cd['name'] ?>')" ><?= $cd['name']." (".$cd['phonenumber'].")" ?></li>
                                                </a>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-9 wa-chat-col">
                                    <div class="whatsapp-chat-interface">
                                        <div class="initial-screen"
                                            style="height:100% ;background: url('<?= base_url('assets/images/waChatBackground.png')?>')">
                                        </div>
                                        <div class="wa-chat-screen"
                                            style="background: url('<?= base_url('assets/images/chatbackground3.jpg')?>">
                                            <div class="chat-top-bar">
						                        <span class="chat-back-btn"><a href="" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a></span><span class="chat-title"></span>
					                        </div>
                                            <div class="wa-lodder">
                                                <img src="<?= base_url("assets/images/1488.gif") ?>" alt="">
                                             </div>
                                            <div class="chat-container" id="chatContainer">

                                            </div>
                                        </div>
                                        <!-- sendMessage button -->
                                        <div class="button wa-send-message">
                                            <form id="messageForm" >
                                                <input type="hidden" id="formUserId" value="<?= get_staff_user_id() ?>">
                                                <input type="hidden" id="formNumber" class="formNumber" name="chatId"/>
                                                <input type="hidden" id="formMediaUrl"/>
                                                <input type="hidden" id="formMediaId" name="formMediaId"/>
                                                <input type="text" id="formType" value="1">
                                                <div class="message-input">
                                                    <div class="row">
                                                        <div class="col-sm-10">
                                                            <div class="input-group">
                                                                <span class="input-group-btn">
                                                                    <div class="btn-group dropup">
                                                                        <button class="btn wa-drop-btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <i class="fa fa-paperclip" aria-hidden="true"></i>
                                                                        </button>
                                                                        <ul class="dropdown-menu">
                                                                            <li><a href="#" id="textMessageOption">Text Message</a></li>
                                                                            <li><a href="#" id="mediaMessageOption">Media Message</a></li>
                                                                            <li><a href="#" id="templateMessageOption">Template Message</a></li>
                                                                            <li><a href="#" id="linkMessageOption">Link Message</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </span>
                                                                <!-- Container for the fields -->
                                                                    <!-- Default Text Message Field (shown by default) -->
                                                                    <input type="text" class="form-control message-field message-input" id="textMessageField" placeholder="Type a message..." style="display: block;">
                                                                    <!-- Media Message Field (hidden by default) -->
                                                                            <input type="file" id="mediaMessageFileField" style="display:none">
                                                                            <input type="text" id="mediaMessageCaptionField" class="form-control message-field message-input"placeholder="Image Caption" style="display:none">
                                                                    <!-- Template Message Field (hidden by default) -->
                                                                    <select class="form-select wa-form-select message-field" id="templateMessageField" style="display: none;" aria-label="Default select example">
                                                                        <option selected> Open this select menu</option>
                                                                        <option value="hello_world">Hello Template</option>
                                                                    </select>
                                                                    <!-- Link Message Field (hidden by default) -->
                                                                    <input type="text" class="form-control message-field message-input" id="linkMessageField" style="display: none;" placeholder="Enter Link...">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-2">
                                                            <button  type="submit" class="btn  wa-btn" id="sendMessageBtn">
                                                              <svg xmlns="http://www.w3.org/2000/svg" style="padding-top:3.5px" viewBox="0 0 50 25" width="50" height="24" fill="white"><path d="M2 21v-7l11-2-11-2V3l21 9-21 9z"/></svg>
                                                            </button>
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
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
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
</script>
<script>
    function getMessage(clickedLink,chatId,name){
        if (window.innerWidth <= 768) {
          $('.wa-side-bar-col').hide(); // Toggles between block and none
          $('.wa-chat-col').show();
        }
        $('#formNumber').val(chatId);
        console.log(name);
        $('.chat-title').html(name+' ('+ chatId +')');
        $('.initial-screen').css('display', 'none');
        $('.wa-chat-screen').show();
        $('.wa-chat-col').append('<div class="overlay"></div>');
        $('.overlay').show();
        const links = document.querySelectorAll('.chat-item');
        // Remove 'active' class from all links
        links.forEach(link => link.classList.remove('active-chat'));
        // Add 'active' class to the clicked link
        clickedLink.classList.add('active-chat');
        // Fetch chats
        $.ajax({
        url: waURL+'/api/chat/messages/'+chatId,
        method: 'GET',
        success: function (data) {
            $('.wa-send-message').show();
            $('.wa-lodder').hide();
            $('.overlay').fadeOut('slow', function () {
                $(this).remove(); // Remove overlay from the DOM
            });
            $('.chat-container').html('');
            // Add Messages to chat box.
                data.messages.forEach(function (message) {
                    console.log(message);
                const messageClass = message.message_type === 'received' ? 'incoming-message' : 'sent-message';
                if(message.message_content !=4){
                // Create a new div for each message
                const messageDiv = $('<div>'+message.message_body+'</div>') // Create the div
                    .attr('id', message.message_id) // Set message_id as the id attribute
                    .addClass(messageClass); // Optionally add a class for styling
                // Append the created message div to the body or a specific parent
                $('#chatContainer').append(messageDiv); // Or append to a specific container if needed
                }else if(message.message_content ==4){
                    const mediaPath = waURL + '/' + message?.media_details?.path.replace('\\', '/');
                    const mediaCaption = message.message_body;
                    const messageDiv = $('<div style="width:50%"><a href="'+mediaPath+'" target="_blank"><img src="'+mediaPath+'" width="100%" alt="media" /></a></div>')
                    .attr('id', message.message_id) // Set message_id as the id attribute
                    .addClass(messageClass); // Optionally add a class for styling
                 // Append the created message div to the body or a specific parent
                $('#chatContainer').append(messageDiv); // Or append to a specific container if needed
                }
            });
            autoScrollToBottom();
            // Add realtime incomming messages.
            setupChatSocketListener(chatId);

        },
        error: function () {
            console.error('Failed to fetch data');
            $('.overlay').fadeOut('slow', function () {
                $(this).remove(); // Remove overlay even on error
            });
        }
    });

    }
</script>
<script>
    // Setup socket 
    function setupChatSocketListener(chatId){

	// Remove any exisiting listener before adding new one
	socket.off('chat-' + chatId);
	socket.on('chat-' + chatId, (data)=>{
	console.log(data);//Checking.
	var type = data.type;
	var messageData = data.messageToInsert;//getting message body
	var data ; // Initializing empty variable'
	//Check if message is sent or status
	if(type=='received'){
	    data = '<div id='+messageData.message_id+' class="incoming-message">'+messageData.message_body+'</div>';
	    //Appending chat data to UI
	    $('.chat-container').append(data);
	    // Automatically scroll to the bottom of the chat box
        autoScrollToBottom();
	}else{
    console.log(data);
	    
	}
	});
}
// Function to automatically scroll the chat container to the bottom
function autoScrollToBottom() {
    console.log("Auto Scroll: Triggered");
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
        const type = $('#formType').val();
        const message = $('#textMessageField').val();
        const template = $('#templateMessageField').val();
        const linkMessage = $('#linkMessageField').val();
        const mediaId = $('#formMediaId').val()
        const mediaCaption = $('#mediaMessageCaptionField').val();
        const formMediaUrl = $('#formMediaUrl').val();
        // SendMessage Payload
        const sendPayload = {
            userId: userId,
            source: source,
            to: to,
            type:type
        }
        if(type==1){
            sendPayload.message = message;
        }
        if(type==2){
            sendPayload.message = linkMessage;
        }
        if(type==3){
            sendPayload.tempName = template;
        }
        if(type==4){
            sendPayload.imageId = mediaId;
            if(mediaCaption){
            sendPayload.caption = mediaCaption;
            }
        }
        console.log(sendPayload);
        // Send the data via AJAX
        $.ajax({
            type: 'POST',
            url: waURL+'/api/messages/send/', // Replace with your actual URL
            contentType: 'application/json',
            data: JSON.stringify(sendPayload),
            success: function(response) {
                // Handle success (e.g., clear input, display message, etc.)
                const messageData = response.data.messages[0];
                if(response.type==1){
                    $('#textMessageField').val(''); // Clear the input after sending
                    data = '<div id='+messageData.id+' class="sent-message">'+message+'</div>';
                }
                if(response.type==2){
                    $('#linkMessageField').val(''); // Clear the input after sending
                    data = '<div id='+messageData.id+' class="sent-message">'+linkMessage+'</div>';
                }
                if(response.type==3){
                    $('#templateMessageField').val(''); // Clear the input after sending
                    data = '<div id='+messageData.id+' class="sent-message">Template</div>';
                }
                if(response.type==4){
                    $('.uploading-media').remove();
                    $('#formMediaUrl').val('');
                    $('#mediaMessageCaptionField').val('');
                    // Remove upload box
                    // Add Message box
                    const mediaPath = formMediaUrl;
                    if(mediaCaption){
                    const mediaCaption = mediaCaption;
                    }
                    data = $('<div style="width:50%"><a href="'+mediaPath+'" target="_blank"><img src="'+mediaPath+'" width="100%" alt="media" /></a></div>')
                    .attr('id', messageData.id) // Set message_id as the id attribute
                    .addClass("sent-message"); // Optionally add a class for styling
                 // Append the created message div to the body or a specific parent
                }
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
document.getElementById('textMessageOption').addEventListener('click', function() {
    changeMessageField('textMessageField');
    $('#formType').val(1);
});

document.getElementById('mediaMessageOption').addEventListener('click', function() {
    $('#formType').val(4);
    changeMessageField('mediaMessageCaptionField');
    document.getElementById('mediaMessageFileField').click();
});

document.getElementById('templateMessageOption').addEventListener('click', function() {
    $('#formType').val(3);
    changeMessageField('templateMessageField');
});

document.getElementById('linkMessageOption').addEventListener('click', function() {
    $('#formType').val(2);
    changeMessageField('linkMessageField');
});

// Function to switch the visible field
function changeMessageField(fieldId) {
    // Hide all fields
    const fields = document.querySelectorAll('.message-field');
    fields.forEach(function(field) {
        field.style.display = 'none';
    });

    // Show the selected field
    document.getElementById(fieldId).style.display = 'block';
}
$(document).ready(function() {
    // Handle file selection
    $('#mediaMessageFileField').on('change', function(event) {
        const file = event.target.files[0]; // Get the selected file
        const userId = $('#formUserId').val(); // Get user ID from input
        const source = "crm";
        if (file) {
            
            // Use FileReader to display the selected image
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#formMediaUrl').val(e.target.result);
                // Set the image source to the file reader result
                const uploadingImage = $('<div style="width:50%"><img src="'+e.target.result+'" width="100%" alt="media" /><div id="overlay" class="wa-overlay"><div class="wa-loader" id="loader"></div></div></div>')
                    .addClass("uploading-media"); // Optionally add a class for styling
                 // Append the created message div to the body or a specific parent
                $('#chatContainer').append(uploadingImage); // Or append to a specific container if needed
                    // Delay scrolling to ensure the DOM is updated
                setTimeout(() => {
                    autoScrollToBottom();
                }, 50); // Adjust delay if necessary
            };
            reader.readAsDataURL(file); // Read the image file as a data URL
            
            // Prepare the file data for upload (optional for immediate upload)
            const formData = new FormData();
            formData.append('file', file);
            formData.append('source', source);
            formData.append('userId', userId);
            
            // AJAX request for file upload
            $.ajax({
                url: 'http://localhost:4000/api/messages/upload', // Corrected URL
                type: 'POST',
                data: formData,
                contentType: false, // Prevent jQuery from setting content type
                processData: false, // Don't process the data (FormData handles it) 
                success: function(data) {
                    $('#formMediaId').val(data.media_id);
                    const paragraph = $('<p class="succ-upload">').text('Image uploaded successfully, press send button to send!');
                        $('#overlay').append(paragraph);
                        $('#loader').hide();
                        $(".uploading-media").css("border", "4px solid green");
                        // Set position and related styles
                        $(".uploading-media").css({
                            position: "relative", // or "relative" based on your layout
                            left: "-30px", // Distance from the left
                        });
                },
                error: function(error) {
                        const paragraph = $('<p class="err-upload">').text('Failed to upload, Please try again');
                        $('#overlay').append(paragraph);
                        $('#loader').hide();
                        $(".uploading-media").css("border", "2px solid red");
                        // Set position and related styles
                        $(".uploading-media").css({
                            position: "relative", // or "relative" based on your layout
                            left: "-30px", // Distance from the left
                        });
                    console.error('Error uploading file:', error.responseJSON);
                }
            });
        }
    });
});


</script>
</body>

</html>