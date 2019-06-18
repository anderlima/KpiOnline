<?php


date_default_timezone_set('America/Sao_Paulo');
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();  
$mail->SMTPDebug = 2;
$mail->Debugoutput = 'html';                                   // Set mailer to use SMTP
$mail->Host = 'localhost';  // Specify main and backup SMTP servers
$mail->SMTPAuth = false;                               // Enable SMTP authentication
$mail->Username = 'alimao';                 // SMTP username
$mail->Password = 'Braz123!';                           // SMTP password
#$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 25;                                    // TCP port to connect to

$mail->setFrom('alimao@br.ibm.com', 'Mailer');
$mail->addAddress('alimao@br.ibm.com', 'Joe User');     // Add a recipient
#$mail->addReplyTo('anderson.vine@.com', 'Information');
#$mail->addCC('cc@example.com');
#$mail->addBCC('bcc@example.com');

#$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
#$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Hello World from autoportal';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
#$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
#$mail->addAttachment('images/phpmailer_mini.png');

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
?>
