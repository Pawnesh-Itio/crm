<?php
if(session_id() === "") session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the chat messages from the POST request
    $chatMessages = $_POST['chatMessages'];

    // Your email logic here
    $to = $_SESSION['client_email']; // The email to send to
    $subject = 'Chat Transcript';
    $message = "Here is your chat transcript:\n\n" . $chatMessages;
    $headers = 'From: no-reply@yourdomain.com' . "\r\n" .
               'Reply-To: no-reply@yourdomain.com' . "\r\n" .
               'X-Mailer: PHP/' . phpversion();

    if (mail($to, $subject, $message, $headers)) {
        echo "Email sent successfully!";
    } else {
        echo "Failed to send email.";
    }
}
?>