<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="card-wa-configuration">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <div class="config-dropdown">
                                    <select name="confDropdown" id="confDropdown" class="form-control">
                                        <!-- Option Populate -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
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
                                            <ul id="chatList">
                                                <!-- Chat List will populate Dynamically -->
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
                                             <div id="floatingDateLabel" class="floating-date-label"></div>

                                            <div class="chat-container" id="chatContainer">

                                            </div>
                                        </div>
                                        <div id="replyPreview" style="display:none;"></div>
                                        <!-- sendMessage button -->
                                        <div class="button wa-send-message">
                                            <form id="messageForm" >
                                                <input type="hidden" id="formUserId" value="<?= get_staff_user_id() ?>">
                                                <input type="hidden" id="formNumber" class="formNumber" name="chatId"/>
                                                <input type="hidden" id="formMediaUrl"/>
                                                <input type="hidden" id="formMediaId" name="formMediaId"/>
                                                <input type="hidden" id="formType" value="1">
                                                <input type="hidden" id="ContactType" value="Regular">
                                                <input type="hidden" id="confId">
                                                <input type="hidden" id="mediaCategory" name="mediaCategory">
                                                <input type="hidden" id="replyToMessageId" value="">

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
                                                                            <li><a href="#" id="imagePhotoOption">Photo/Image</a></li>
                                                                            <li><a href="#" id="documentMessageOption">Document Message</a></li>
                                                                            <li><a href="#" id="audioMessageOption">Audio Message</a></li>
                                                                            <li><a href="#" id="videoMessageOption">Video Message</a></li>
                                                                            <li><a href="#" id="templateMessageOption">Template Message</a></li>
                                                                            <li><a href="#" id="linkMessageOption">Link Message</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </span>
                                                                <!-- Container for the fields -->
                                                                    <!-- Default Text Message Field (shown by default) -->
                                                                    <textarea type="text" rows="1" class="form-control message-field message-input" id="textMessageField" placeholder="Type a message..." style="display: block;"></textarea>
                                                                    <!-- Media Message Field (hidden by default) -->
                                                                    <input type="file" id="mediaMessageFileField" style="display:none">
                                                                    <textarea type="text"rows="1"  id="mediaMessageCaptionField" class="form-control message-field message-input"placeholder="Media Caption" style="display:none"></textarea>
                                                                    <!-- Template Message Field (hidden by default) -->
                                                                    <select class="form-select wa-form-select message-field" id="templateMessageField" style="display: none;" aria-label="Default select example">
                                                                        <option selected> Open this select menu</option>
                                                                        <option value="hello_world">Hello World</option>
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
// let replyToMessage = null;

// Socket connection
const SocketURL = "<?= Whatsapp_Socket_Url ?>";
const waURL = "<?= Whatsapp_Api_Url ?>";
const source = "crm-ITIO";
const socket = io(SocketURL);
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
    // Setup global chat list listener
    $(document).ready(function () {
        // other code...
        setupGlobalChatListListener(); //important!
    });
    // Function to get selected configuration ID from URL and set it in dropdown
    $(document).ready(function () {
        const urlParams = new URLSearchParams(window.location.search);
        const confId = urlParams.get('id');

        if (confId) {
            setTimeout(() => {
                let matchingVal = null;
                $('#confDropdown option').each(function () {
                    console.log("Checking option value:", $(this).val());
                    if ($(this).val().startsWith(confId + ',')) {
                        console.log("Matching value found:", $(this).val());
                        matchingVal = $(this).val();
                    }
                });

                if (matchingVal) {
                    $('#confDropdown').val(matchingVal); // Set selected value
                    contactDetails(confId);              // Load details
                }
            }, 500); // adjust delay if needed
        } else {
            // Default fallback
            $('#confDropdown').trigger('change');
        }
    });

    // Fetch All Configurations
    $(document).ready(function() {
    $.ajax({
        url: `${waURL}/api/configuration/fetch/`,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            source: source,
            userType: 1,
            companyId: 1
        }),
        success: function(response) {
            console.log(response);
            if (Array.isArray(response)) {  // Check if response is an array
                if(response.length >0){
                    let newRows = response.map( (item, index) => 
                        `<option value="${item.phoneNumberId},${item._id}" ${index === 0 ? 'selected' : ''}>${item.phoneNumber}</option>`
                    ).join(''); // Convert array to a string

                    $('#confDropdown').append(newRows); // Append all rows at once
                    const confData = $('#confDropdown').val().split(',');
                    contactDetails(confData[0]);
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
    // Function to fetch contacts based on selected phone number
    function contactDetails(phoneNumberId){
        if (!phoneNumberId) return; // Prevent unnecessary requests
        console.log("Fetching data for:", phoneNumberId);
        $.ajax({
            url: `${waURL}/api/chat/list/${phoneNumberId}/Regular`,
            method: 'GET',
            success: function (data) {  
                console.log("Fetched data length:", data.length);
                console.log("Fetched data:", data);
                if(data.length > 0){
                    let chatList = data.map((item, index) => {
                        const lastMsg = item.lastMessage?.message_body || '';
                            const msgTime = item.lastMessage?.time
                                            ? formatTime(item.lastMessage.time)
                                            : '';
                        const msgPreview = lastMsg.length > 25 ? lastMsg.substring(0, 25) + '...' : lastMsg;
                        const waName = item.wa_name.length > 10 ? item.wa_name.substring(0, 10) + '...' : item.wa_name;
                        return `<a class="chat-link">
                            <li class="chat-item" data-wa-id="${item.wa_id}" onclick="getMessage(this, '${item.wa_id}', '${item.wa_name}','${phoneNumberId}')">
                                <span style="font-size:14px; font-weight:bold">${waName} (${item.wa_phone_number})</span>
                                <br>
                                <small id="msgPre">${msgPreview}</small>
                                <small class="new-chat-badge" style="display:none;">New</small>
                                <small style="float:right">${msgTime}</small>
                            </li>
                        </a>`;
                    }).join('');
                    $('#chatList').html(chatList);
                }else{
                    let errMessage = `<p class="text-center text-danger">No contact found...</p>`;
                    $('#chatList').html(errMessage);
                }
                // Handle the response data
            },
            error: function (error) {
                console.error("Error fetching data:", error);
            }
        });
    }
     // Change event listener to trigger when a new value is selected
     $('#confDropdown').change(function () {
        const selectedValue = $(this).val();
        const confData = selectedValue.split(',');
        const confId = confData[0];

        // Update URL without reloading the page
        const newUrl = new URL(window.location);
        newUrl.searchParams.set('id', confId);
        window.history.pushState({}, '', newUrl);

        // Trigger your logic
        contactDetails(confId);
    });
    function getMessage(clickedLink,chatId,name,phoneNumberId){
        const confData = $('#confDropdown').val().split(',');
        // Assign confId in form
        $('#confId').val(confData[1]);
        if (window.innerWidth <= 768) {
          $('.wa-side-bar-col').hide(); // Toggles between block and none
          $('.wa-chat-col').show();
        }
        $('#formNumber').val(chatId);
        $('.chat-title').html(name+' ('+ chatId +')');
        $('.initial-screen').css('display', 'none');
        $('.wa-chat-screen').show();
        $('.wa-chat-col').append('<div class="overlay"></div>');
        $('.overlay').show();
        const links = document.querySelectorAll('.chat-item');
        // Remove 'active' class from all links
        links.forEach(link => link.classList.remove('active-chat'));
        // Add 'active' class to the clicked link
        clickedLink.classList.remove('new-chat');
        clickedLink.classList.add('active-chat');
        // Fetch chats
        $.ajax({
        url: waURL+'/api/chat/messages/'+chatId+'/'+phoneNumberId+'/Regular',
        method: 'GET',
        success: function (data) {
            $('.wa-send-message').show();
            $('.wa-lodder').hide();
            $('.overlay').fadeOut('slow', function () {
                $(this).remove(); // Remove overlay from the DOM
            });
            $('.chat-container').html('');
            // Add Messages to chat box.
               let lastDateGroup = null;

               data.messages.forEach(function (message) {
                    const messageClass = message.message_type === 'received' ? 'incoming-message' : 'sent-message';

                    // Format time
                    const msgDate = new Date((message.time || new Date(message.createdAt).getTime()) * 1000);

                    // Determine date label: Today, Yesterday, or formatted date
                    const today = new Date();
                    const yesterday = new Date();
                    yesterday.setDate(today.getDate() - 1);

                    let dateLabel;
                    if (msgDate.toDateString() === today.toDateString()) {
                        dateLabel = 'Today';
                    } else if (msgDate.toDateString() === yesterday.toDateString()) {
                        dateLabel = 'Yesterday';
                    } else {
                        dateLabel = msgDate.toLocaleDateString(undefined, {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        }); // Format: dd/mm/yyyy
                    }

                    // Insert a date divider if it's a new group
                    if (lastDateGroup !== dateLabel) {
                        const divider = $(`<div class="chat-date-divider text-center my-2"><span class="chat-date-label">${dateLabel}</span></div>`);
                        $('#chatContainer').append(divider);
                        lastDateGroup = dateLabel;
                    }

                    const formattedTime = msgDate.toLocaleTimeString(undefined, {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });

                    const tickIcon = getTickIcon(message.status);

                    // Reply Preview
                    let replyPreview = '';
                    if (message.replied_message) {
                        const originalMsg = message.replied_message;
                        const sender = originalMsg.message_type === 'sent' ? 'You' : 'Contact';
                        let previewText = '';

                        switch (originalMsg.message_content) {
                            case "1":
                                previewText = originalMsg.message_body?.slice(0, 50) || '[Text]';
                                break;
                            case "2":
                            case "4":
                                previewText = `[${originalMsg.media_type?.toUpperCase() || 'Media'} message]`;
                                break;
                            default:
                                previewText = '[Message]';
                        }

                        replyPreview = `
                            <div class="reply-preview" data-scroll-id="${originalMsg.message_id}">
                                <strong>${sender}:</strong> ${previewText}
                            </div>
                        `;
                    }

                    let messageDiv;

                    if (message.message_content != 4) {
                        const safeBody = escapeHtml(message.message_body).replace(/\n/g, '<br>');
                        // Text messages
                        messageDiv = $(`
                            <div class="${messageClass}" id="${message.message_id}">
                                ${replyPreview}
                                <div class="message_formating">${message.message_body}</div>
                                <a class="reply-btn" 
                                    data-reply-id="${message.message_id}" 
                                    data-message-body="${message.message_body}"
                                    style="margin-top:5px;font-size:12px">
                                   <i class="fa fa-reply" aria-hidden="true"></i>
                                </a>
                                <div class="chat-time">${formattedTime} ${message.message_type === 'sent' ? tickIcon : ''}</div>
                            </div>
                        `);
                    } else {
                        // Media messages
                        const mediaPath = waURL + '/' + message?.media_details?.path.replace('\\', '/');
                        const mediaCaption = message.message_body;

                        switch (message.media_type) {
                            case 'image':
                                messageDiv = $(`
                                    <div class="${messageClass}" id="${message.message_id}" style="width:50%">
                                        ${replyPreview}
                                        <a href="${mediaPath}" target="_blank"><img src="${mediaPath}" width="100%" /></a>
                                        <p class="message_formating" style="padding-top:10px">${mediaCaption || ''}</p>
                                        <div class="chat-time">${formattedTime} ${tickIcon}</div>
                                    </div>
                                `);
                                break;
                            case 'video':
                                messageDiv = $(`
                                    <div class="${messageClass}" id="${message.message_id}" style="width:50%">
                                        ${replyPreview}
                                        <video width="100%" controls>
                                            <source src="${mediaPath}" type="video/mp4">
                                        </video>
                                        <p class="message_formating" style="padding-top:10px">${mediaCaption || ''}</p>
                                        <div class="chat-time">${formattedTime} ${tickIcon}</div>
                                    </div>
                                `);
                                break;
                            case 'audio':
                                messageDiv = $(`
                                    <div class="${messageClass}" id="${message.message_id}" style="width:50%">
                                        ${replyPreview}
                                        <audio controls><source src="${mediaPath}" type="audio/mpeg"></audio>
                                        <p class="message_formating" style="padding-top:10px">${mediaCaption || ''}</p>
                                        <div class="chat-time">${formattedTime} ${tickIcon}</div>
                                    </div>
                                `);
                                break;
                            case 'document':
                                const fileName = mediaPath.split('/').pop();
                                messageDiv = $(`
                                    <div class="${messageClass}" id="${message.message_id}" style="width:50%">
                                        ${replyPreview}
                                        <a href="${mediaPath}" target="_blank">📄 ${fileName}</a>
                                        <p class="message_formating" style="padding-top:10px">${mediaCaption || ''}</p>
                                        <div class="chat-time">${formattedTime} ${tickIcon}</div>
                                    </div>
                                `);
                                break;
                            default:
                                messageDiv = $(`
                                    <div class="${messageClass}" id="${message.message_id}" style="width:50%">
                                        ${replyPreview}
                                        <a href="${mediaPath}" target="_blank">Download Media</a>
                                        <div class="chat-time">${formattedTime} ${tickIcon}</div>
                                    </div>
                                `);
                        }
                    }

                    $('#chatContainer').append(messageDiv);
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
    // Incomming messages socket
function setupChatSocketListener(chatId){
    console.log("Setting up socket listener for chatId:", chatId);
        activeChatId = chatId; // Track the active chat
	// Remove any exisiting listener before adding new one
	socket.off('chat-' + chatId); // Remove previous listeners
	socket.on('chat-' + chatId, (data)=>{
            if (chatId !== activeChatId) {
            // Ignore messages for other chats
            return;
        }
	var type = data.type;   
	//Check if message is sent or status
	if(type=='received' && data.messageContentToInsert){
        var msgContent ;
        var messageData = data.messageContentToInsert;//getting message body
        const formattedTime = messageData.time
        ? new Date(messageData.time * 1000).toLocaleTimeString(undefined, {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
            })
        : '';

 
        if(messageData.message_content != 4){
	    msgContent = '<div id='+messageData.message_id+' class="incoming-message message_formating">'+messageData.message_body+'<div class="chat-time">'+formattedTime+'</div></div>';
	    //Appending chat data to UI
	    $('.chat-container').append(msgContent);
        }else{

            switch (messageData.media_type) {
                case 'image':
                    msgContent =$(`<div class="incoming-message" id="${messageData.message_id}" style="width:50%">
                        <a href="${waURL}/${messageData.media_path.replace('\\', '/')}" target="_blank">
                            <img src="${waURL}/${messageData.media_path.replace('\\', '/')}" width="100%" />
                            <p class="message_formating" style="padding-top:10px">${messageData.message_body || ''}</p>
                            <div class="chat-time">${formattedTime}</div>
                        </a>
                            `);
                            $('.chat-container').append(msgContent);
                    break;
                case 'video':
                    msgContent = $(`<div id=${messageData.message_id} class="incoming-message" style="width:50%">
                        <video width="100%" controls>
                            <source src="${waURL}/${messageData.media_path.replace('\\', '/')}" type="video/mp4">
                        </video>
                        <p class="message_formating" style="padding-top:10px">${messageData.message_body || ''}</p>
                        <div class="chat-time">${formattedTime}</div>`);
                        $('.chat-container').append(msgContent);
                    break;
                case 'audio':
                    msgContent = $(`<div id=${messageData.message_id} class="incoming-message" style="width:50%">
                        <audio controls>
                            <source src="${waURL}/${messageData.media_path.replace('\\', '/')}" type="audio/mpeg">
                        </audio>
                        <p class="message_formating" style="padding-top:10px">${messageData.message_body || ''}</p>
                        <div class="chat-time">${formattedTime}</div>`);
                        $('.chat-container').append(msgContent);
                    break;
                case 'document':
                    const fileName = messageData.media_path.split('/').pop();
                    msgContent = $(`<div id=${messageData.message_id} class="incoming-message" style="width:50%">
                        <a href="${waURL}/${messageData.media_path.replace('\\', '/')}" target="_blank">📄 ${fileName}</a>
                        <p class="message_formating" style="padding-top:10px">${messageData.message_body || ''}</p>
                        <div class="chat-time">${formattedTime}</div>`);
                        $('.chat-container').append(msgContent);
                    break;
                default:
                    msgContent = $(
                                `<div class="incoming-message" id="${messageData.message_id}" style="width:50%">
                                    <a href="${messageData.media_path.replace('\\', '/')}" target="_blank">Download Media</a>
                                    <div class="chat-time">${formattedTime}</div>
                                </div>`
                            );
                            $('.chat-container').append(msgContent);
            }
        }
	    // Automatically scroll to the bottom of the chat box
        autoScrollToBottom();
	}else if(type=='status' ){
        const { messageId, status } = data;
        // Recalculate the tick icon
        const newTickIcon = getTickIcon(status);
        
        // Find the message element
        const messageElem = $(`[id='${messageId}']`);

        if (messageElem.length) {
            // Update the tick icon inside the `.chat-time` div
            const chatTimeDiv = messageElem.find('.chat-time');
            
            // Optional: Replace only the icon, keeping the timestamp
            const parts = chatTimeDiv.html().split(' ');
            const timeText = parts[0]; // Assuming time and icon are separated by a space
            chatTimeDiv.html(`${timeText} ${newTickIcon}`);
        }
    }
	});
}
// Function to automatically scroll the chat container to the bottom
function autoScrollToBottom() {
    console.log("Auto Scroll: Triggered");
    var chatContainer = $('#chatContainer');
    chatContainer.scrollTop(chatContainer[0].scrollHeight);  // Scroll to the bottom
}
// Sending messages function
$(document).ready(function() {
    $('#messageForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission
        // Gather form data
        const selectedPhoneNumberId = $('#confDropdown').val();
        const source = "crm-ITIO";
        const userId = $('#formUserId').val();
        const to = $('#formNumber').val();
        const type = $('#formType').val();
        const message = $('#textMessageField').val();
        const template = $('#templateMessageField').val();
        const linkMessage = $('#linkMessageField').val();
        const mediaId = $('#formMediaId').val()
        const mediaCategory = $('#mediaCategory').val();
        const mediaCaption = $('#mediaMessageCaptionField').val();
        const formMediaUrl = $('#formMediaUrl').val();
        const contactType = $('#ContactType').val();
        const confID = $('#confId').val();
        const replyToMessageId = $('#replyToMessageId').val();
        // SendMessage Payload
        const sendPayload = {
            userId: userId,
            source: source,
            configurationId:confID,
            ContactType:contactType,
            to: to,
            messageType:type
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
            sendPayload.mediaId = mediaId;
            sendPayload.mediaCategory = mediaCategory;
            if(mediaCaption){
            sendPayload.caption = mediaCaption;
            }
        }
        if(replyToMessageId){
            sendPayload.reply_to_message_id = replyToMessageId; 
        }
        // Send the data via AJAX
        $.ajax({
            type: 'POST',
            url: waURL+'/api/messages/send/', // Replace with your actual URL
            contentType: 'application/json',
            data: JSON.stringify(sendPayload),
            success: function(response) {
                // Clear the reply UI and hidden field
                $('#replyToMessageId').val('');
                $('#replyPreview').hide();
                $('#replyContent').text('');
                // Handle success (e.g., clear input, display message, etc.)
                    // Format time (e.g., 4:36 PM)
                    const formattedTime = new Date(response.time*1000).toLocaleTimeString(undefined, {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                    });

                    let messageDiv;
                const messageData = response.data.messages[0];
                const tickIcon = getTickIcon(response.status);
                
                console.log("Tick Icon:", tickIcon);
                if(response.type== 1){
                    $('#textMessageField').val(''); // Clear the input after sending
                    messageDiv = $(`<div class="sent-message" id="${messageData.id}">
                            <div class="message_formating">${message}</div>
                            <div class="chat-time">${formattedTime} ${tickIcon}</div>
                        </div>`);
                }
                if(response.type==2){
                    $('#linkMessageField').val(''); // Clear the input after sending
                        messageDiv = $(`<div class="sent-message" id="${messageData.id}">
                            <div class="message_formating">${linkMessage}</div>
                            <div class="chat-time">${formattedTime} ${tickIcon}</div>
                        </div>`);
                }
                if(response.type==3){
                    $('#templateMessageField').val(''); // Clear the input after sending
                        messageDiv = $(`<div class="sent-message" id="${messageData.id}">
                            <div>Template</div>
                            <div class="chat-time">${formattedTime} ${tickIcon}</div>
                        </div>`);
                }
                if(response.type==4){
                    $('.uploading-media').remove();
                    $('#formMediaUrl').val('');
                    $('#mediaMessageCaptionField').val('');
                    $('#mediaMessageFileField').val('');
                    $('#formMediaId').val('');
                    // Remove upload box
                    // Add Message box
                    const mediaPath = formMediaUrl; 
                    const currentCaption = mediaCaption;
                    console.log("response.category", response.category);
                    switch (response.category) {
                        case 'image':
                            messageDiv = $(
                                    `<div class="sent-message" id="${messageData.id}" style="width:50%">
                                        <a href="${mediaPath}" target="_blank"><img src="${mediaPath}" width="100%" /></a>
                                        <p class="message_formating" style="padding-top:10px">${currentCaption || ''}</p>
                                        <div class="chat-time">${formattedTime} ${tickIcon}</div>
                                    </div>`
                                );
                                break;
                        case 'video':
                            console.log("Video message");
                            messageDiv = $(
                                    `<div class="sent-message" id="${messageData.id}" style="width:50%">
                                        <video width="100%" controls>
                                            <source src="${mediaPath}" type="video/mp4">
                                        </video>
                                        <p class="message_formating" style="padding-top:10px">${currentCaption || ''}</p>
                                        <div class="chat-time">${formattedTime} ${tickIcon}</div>
                                    </div>`
                            );
                            break;
                        case 'audio':
                                messageDiv = $(
                                    `<div class="sent-message" id="${messageData.id}" style="width:50%">
                                        <audio controls><source src="${mediaPath}" type="audio/mpeg"></audio>
                                        <p class="message_formating" style="padding-top:10px">${currentCaption || ''}</p>
                                        <div class="chat-time">${formattedTime} ${tickIcon}</div>
                                    </div>`
                                );
                                break;
                        case 'document':
                                const fileName = mediaPath.split('/').pop();
                                messageDiv = $(
                                    `<div class="sent-message" id="${messageData.id}" style="width:50%">
                                        <a href="${mediaPath}" target="_blank">📄 ${fileName}</a>
                                        <p class="message_formating" style="padding-top:10px">${currentCaption || ''}</p>
                                        <div class="chat-time">${formattedTime} ${tickIcon}</div>
                                    </div>`
                                );
                                break;
                        default:
                                messageDiv = $(
                                    `<div class="sent-message" id="${messageData.id}" style="width:50%">
                                        <a href="${mediaPath}" target="_blank">Download Media</a>
                                        <div class="chat-time">${formattedTime} ${tickIcon}</div>
                                    </div>`
                                );
                    }
                }
                //Appending chat data to UI
                $('.chat-container').append(messageDiv);
                // Automatically scroll to the bottom of the chat box
                const $chatItem = $(`.chat-item[data-wa-id="${to}"]`);
                // Display Badge
                const $badge = $chatItem.find(".new-chat-badge");
                $badge.hide();
                // Update message preview
                const shortText = message.slice(0, 30);
                $chatItem.find("small#msgPre").text(shortText);
                // Update time
                $chatItem.find("small").last().text(formattedTime);
                // Move chat to top
                    $("#chatList").prepend($chatItem);
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
// Image Photo Option
document.getElementById('imagePhotoOption').addEventListener('click', function() {
    $('#formType').val(4);
    $('#mediaCategory').val('image'); // 
    changeMessageField('mediaMessageCaptionField');
    
    // Accept only image files
    document.getElementById('mediaMessageFileField').setAttribute('accept', 'image/png,image/jpeg');
    document.getElementById('mediaMessageFileField').click();
});
// Document Message Option
document.getElementById('documentMessageOption').addEventListener('click', function() {
    $('#formType').val(4); // Common messageType for all media
    $('#mediaCategory').val('document'); // Important: specify mediaCategory
    changeMessageField('mediaMessageCaptionField'); // Optionally show caption field

    // Accept only document files
    document.getElementById('mediaMessageFileField').setAttribute('accept', '.pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    document.getElementById('mediaMessageFileField').click();
});
//  Audio Message Option
document.getElementById('audioMessageOption').addEventListener('click', function() {
    $('#formType').val(4);
    $('#mediaCategory').val('audio');
    changeMessageField('mediaMessageCaptionField'); // You can hide this for audio if not needed

    // Accept only audio files
    document.getElementById('mediaMessageFileField').setAttribute('accept', 'audio/mpeg,audio/ogg');
    document.getElementById('mediaMessageFileField').click();
});
// Video Message Option
document.getElementById('videoMessageOption').addEventListener('click', function() {
    $('#formType').val(4);
    $('#mediaCategory').val('video');
    changeMessageField('mediaMessageCaptionField');

    // Accept only video files
    document.getElementById('mediaMessageFileField').setAttribute('accept', 'video/mp4,video/3gpp');
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
// Media Upload function
$(document).ready(function () {
    $('#mediaMessageFileField').on('change', function (event) {
        console.log("Media message file triggered");
        const file = event.target.files[0];
        const userId = $('#formUserId').val();
        const source = "crm";
        const confData = $('#confDropdown').val().split(',');
        const mediaCategory = $('#mediaCategory').val();

        if (!file || !mediaCategory) {
            alert("No file or media type selected.");
            return;
        }

        const fileSizeMB = file.size / (1024 * 1024);
        const fileType = file.type;

        // ✅ File size validation by media type
        const sizeLimits = {
            image: 5,
            video: 16,
            audio: 16,
            document: 100
        };

        const allowedTypes = {
            image: ['image/jpeg', 'image/png'],
            video: ['video/mp4', 'video/3gpp'],
            audio: ['audio/mpeg', 'audio/ogg'],
            document: ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
        };

        if (fileSizeMB > sizeLimits[mediaCategory]) {
            alert(`File exceeds maximum size for ${mediaCategory}: ${sizeLimits[mediaCategory]} MB`);
            return;
        }

        if (!allowedTypes[mediaCategory].includes(fileType)) {
            alert(`Invalid file type for ${mediaCategory}. Allowed: ${allowedTypes[mediaCategory].join(', ')}`);
            return;
        }

        // Show media preview
        const reader = new FileReader();
        reader.onload = function (e) {
            $('#formMediaUrl').val(e.target.result);

            let previewElement;
            const src = e.target.result;

            if (mediaCategory === 'image') {
                previewElement = $(`
                    <div style="width:50%">
                        <img src="${src}" width="100%" alt="image" />
                        <div id="overlay" class="wa-overlay">
                            <div class="wa-loader" id="loader"></div>
                        </div>
                    </div>
                `);
            } else if (mediaCategory === 'video') {
                previewElement = $(`
                    <div style="width:50%">
                        <video width="100%" controls muted>
                            <source src="${src}" type="${fileType}">
                        </video>
                        <div id="overlay" class="wa-overlay">
                            <div class="wa-loader" id="loader"></div>
                        </div>
                    </div>
                `);
            } else if (mediaCategory === 'audio') {
                previewElement = $(`
                    <div style="width:50%">
                        <audio controls>
                            <source src="${src}" type="${fileType}">
                        </audio>
                        <div id="overlay" class="wa-overlay">
                            <div class="wa-loader" id="loader"></div>
                        </div>
                    </div>
                `);
            } else {
                previewElement = $(`
                    <div class="doc-preview">
                        <i class="fa fa-file-pdf-o" style="font-size:40px;"></i>
                        <p>${file.name}</p>
                        <div id="overlay" class="wa-overlay">
                            <div class="wa-loader" id="loader"></div>
                        </div>
                    </div>
                `);
            }

            previewElement.addClass("uploading-media");
            $('#chatContainer').append(previewElement);
            setTimeout(autoScrollToBottom, 50);
        };
        reader.readAsDataURL(file);

        // Upload to backend
        const formData = new FormData();
        formData.append('file', file);
        formData.append('source', source);
        formData.append('userId', userId);
        formData.append('configurationId', confData[1]);

        $.ajax({
            url: waURL + '/api/messages/upload',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
                $('#formMediaId').val(data.media_id);
                $('#overlay').append($('<p class="succ-upload">').text('Upload successful! Click send to deliver.'));
                $('#loader').hide();
                $(".uploading-media").css({
                    border: "4px solid green",
                    position: "relative",
                    left: "-30px"
                });
            },
            error: function (error) {
                $('#overlay').append($('<p class="err-upload">').text('Upload failed, please try again.'));
                $('#loader').hide();
                $(".uploading-media").css({
                    border: "2px solid red",
                    position: "relative",
                    left: "-30px"
                });
                console.error('Error uploading file:', error.responseJSON);
            }
        });
    });
});


$(document).on('click', '.reply-btn', function () {
    const replyId = $(this).data('reply-id');
    const replyBody = $(this).data('message-body');
    $('#replyToMessageId').val(replyId); // set hidden input
    console.log("Replying to message ID:", replyId, "with body:", replyBody);
    
    replyToMessage = { id: replyId, body: replyBody };

    $('#replyPreview').html(`<div class="reply-preview">Replying to: ${replyBody} <span style="cursor:pointer;color:red" id="cancelReply">×</span></div>`);
    $('#replyPreview').show();
});
$(document).on('click', '#cancelReply', function () {
     $('#replyToMessageId').val('');
    replyToMessage = null;
    $('#replyPreview').hide();
});

// Global chat list listener
function setupGlobalChatListListener() {
    socket.offAny();
    socket.onAny((eventName, data) => {
        if (!eventName.startsWith("chat-")) return;

        const chatId = eventName.replace("chat-", "");
        // Only handle if it's a proper received message with content
        if (!data.messageContentToInsert || typeof data.messageContentToInsert.message_body !== 'string') {
            console.warn("Skipped non-text or status message:", data);
            return;
        }
        const msg = data.messageContentToInsert;

        const shortText = msg.message_body.slice(0, 30);
        const formattedTime = msg.time
            ? new Date(msg.time * 1000).toLocaleTimeString(undefined, {
                  hour: 'numeric',
                  minute: '2-digit',
                  hour12: true
              })
            : '';

        const $chatItem = $(`.chat-item[data-wa-id="${chatId}"]`);

        if ($chatItem.length) {
            // Update message preview
            $chatItem.find("small#msgPre").text(shortText);

            // Update time
            $chatItem.find("small").last().text(formattedTime);
            // Display Badge
            const $badge = $chatItem.find(".new-chat-badge");
            $badge.show();
            // Move chat to top
            $("#chatList").prepend($chatItem);
        } else {
            console.warn("Message for new/unloaded chat ID:", chatId);
        }
    });
}



function formatTime(timestamp) {
    const date = new Date(timestamp*1000);
    const now = new Date();

    const isToday = date.toDateString() === now.toDateString();

    const yesterday = new Date();
    yesterday.setDate(now.getDate() - 1);
    const isYesterday = date.toDateString() === yesterday.toDateString();

    if (isToday) {
        // Format as 10:30 AM
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
    } else if (isYesterday) {
        return 'Yesterday';
    } else {
        // Format as MM/DD/YYYY
        return (date.getMonth() + 1).toString().padStart(2, '0') + '/' +
               date.getDate().toString().padStart(2, '0') + '/' +
               date.getFullYear();
    }
}
function getTickIcon(status) {
    switch (status) {
        case "sent":
            return '<i class="fas fa-check" style="color:gray;"></i>'; // Single tick
        case "delivered":
            return '<i class="fas fa-check-double" style="color:gray;"></i>'; // Double tick
        case "read":
            return '<i class="fas fa-check-double" style="color:blue;"></i>'; // Double blue tick
        default:
            return ''; // No tick (unsent or unknown status)
    }
}

$(document).on('click', '.reply-preview', function () {
    const targetMessageId = $(this).data('scroll-id');
    const targetElement = document.getElementById(targetMessageId);
    
    if (targetElement) {
        targetElement.scrollIntoView({ behavior: 'smooth', block: 'center' });

        // Highlight
        $(targetElement).addClass('highlighted');
        setTimeout(() => {
            $(targetElement).removeClass('highlighted');
        }, 1500);
    }
});
$(document).off('scroll.chatDateWatcher'); // remove old listener if any
$('#chatContainer').on('scroll.chatDateWatcher', function () {
    let currentLabel = null;
    let closestOffset = -Infinity;

    $('.chat-date-divider').each(function () {
        const offset = $(this).offset().top - $('#chatContainer').offset().top;

        if (offset <= 10 && offset > closestOffset) {
            closestOffset = offset;
            currentLabel = $(this).text().trim();
        }
    });

    if (currentLabel) {
        $('#floatingDateLabel').text(currentLabel).show();
    } else {
        $('#floatingDateLabel').hide();
    }
});

</script>
</body>

</html>