<?php
namespace App\Model;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

final class EmailModel
{
    //inisialize by dependencies.php
    public function __construct($emailModel){
      $this->email = $emailModel;
    }
     public function sendMail($user_emailAddress,$authorized_code) {
       // $this->getApp()->contentType('text/html');
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $this->email['host'];                 // SMTP username
            $mail->Password = $this->email['password'];                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = $this->email['port'];                                    // TCP port to connect to
            //Recipients
            $mail->setFrom('area409@gmail.com', 'Heart_dog');
            $mail->addAddress($user_emailAddress, 'receiver');     // Add a recipient
  //            $mail->addAddress('ellen@example.com');               // Name is optional
            //Attachments
  //            $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
  //            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Heart_dog Email Authorized';
            $mail->Body    = "<p><b>$authorized_code</b> 를 입력하세요</p>";
           
            $mail->send();
        } catch (Exception $e) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
    }
}