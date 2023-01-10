<?php

namespace App\Services\Email;

use PHPMailer\PHPMailer\PHPMailer;

class SendMail
{

    /**
     * @inheritDoc
     */
    public function sendMail($subject, $email, $message)
    {
        $text             = 'Hello Mail';
        $mail             = new PHPMailer(); // create a n
        $mail->SMTPDebug  = 0; // debugging: 1 = errors and messages, 2 = messages only
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->SMTPAuth   = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        $mail->Host       = 'smtp.gmail.com';
        $mail->Port       = 465; // or 587
        $mail->IsHTML(true);
        // $mail->Username = 'support@fotober.com';   // Cập nhật lại email gửi
        // $mail->Password = 'libwijhnczutynak';   // Cập nhật lại mk gửi email
        // $mail->SetFrom('support@fotober.com', 'Fotober Vietnam');  // Thiết lập gửi từ mail nào
        
        $mail->Username = 'notification@fotober.com';   // Cập nhật lại email gửi
        $mail->Password = 'ohdshnpipzucjifg';   // Cập nhật lại mk gửi email
        $mail->SetFrom('support@fotober.com', 'Fotober Vietnam');  // Thiết lập gửi từ mail nào
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->smtpConnect( array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
                "allow_self_signed" => true
            )
        ));
        $mail->AddAddress($email, $email);
        if ($mail->Send()) {
            return true; 
        } else {
            return false;
        }
    }
}

