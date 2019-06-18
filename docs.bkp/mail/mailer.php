<?php

require_once("../user_logic.php");
checkUser();

date_default_timezone_set('America/Sao_Paulo');
require 'PHPMailerAutoload.php';

$subject = $_POST['subject'];
$body = nl2br(htmlspecialchars($_POST['body']));
$user = Whois();

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

#$mail->IsHTML(false);
$mail->CharSet = "UTF-8";
$mail->isSMTP();
$mail->SMTPDebug = 2;
$mail->Debugoutput = 'html';                                   // Set mailer to use SMTP
$mail->Host = 'localhost';  // Specify main and backup SMTP servers
$mail->SMTPAuth = false;                               // Enable SMTP authentication
$mail->Username = 'wwwrun';                 // SMTP username
#$mail->Password = '';                           // SMTP password
#$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 25;                                    // TCP port to connect to

$mail->setFrom($user, 'Mailer');
$mail->addAddress('alimao@br.ibm.com');
$mail->addAddress('dcalves@br.ibm.com');
#$mail->addReplyTo('alimao@br.ibm.com', 'Information');
#$mail->addCC('dcalves@br.ibm.com');
#$mail->addBCC('bcc@example.com');

#$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
#$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = "[KPIONLINE] ". $subject;
$mail->Body    = $body;
#$mail->AltBody = $body;
#$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
#$mail->addAttachment('images/phpmailer_mini.png');

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
    $_SESSION["danger"] = 'Message could not be submitted!'. $mail->ErrorInfo;
    header("Location: ../help_page.php");
} else {
    echo 'Message has been sent';
    $_SESSION["success"] = 'Message was successfully submitted! We will be in touch with you soon!';
    header("Location: ../help_page.php");
}
?>
