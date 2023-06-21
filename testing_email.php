<?php
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\SMTP;
 use PHPMailer\PHPMailer\Exception;
 require 'vendor/autoload.php';


$mail = new PHPMailer(true);
$mail->isSMTP();                                           
$mail->Host       = 'smtp.philsaga.com';  
$mail->Port       = 25;                                    

//Recipients
$mail->setFrom('jatano@philsaga.com', 'jundrie');
$mail->addAddress('jatano@philsaga.com');
// $mail->addBCC('dutablante@philsaga.com', 'Dan Tablante');     // Add a recipient
// $mail->addBCC('mraquino@philsaga.com', 'Michael Aquino'); 
//$mail->addReplyTo('info@example.com', 'Information');             
// $emails = explode(",", $p->email_receivers);
// if( count($emails) > 0 ){
//     foreach($emails as $email){
//         if( strlen($email) > 2 ){
//             $mail->addAddress($email);
//         }
//     }     
// }

// $mail->msgHTML(file_get_contents(env('NATIVE_PHP_PATH').'email_sender.php?status='.urlencode($status).'&ponumber='.urlencode($p->poNumber).'&supplier='.urlencode($p->supplier_name->name).'&order_date='.urlencode($p->orderDate).'&rq_number='.urlencode($p->rq).'&rq_date='.urlencode($p->rq_date).'&expected_delivery='.urlencode($l->expectedDeliveryDate).'&po_id='.urlencode($l->poId)), __DIR__);
// // Content
 $mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = 'PO# Logistics Update';
 $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
 $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

$mail->send();

