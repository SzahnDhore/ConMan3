<?php

namespace Szandor\ConMan\Data;

class MailSender
{
    protected $mail;
    protected function __construct() {
        require_once (Settings::main('project_folder') . 'lib/PHPMailer/PHPMailerAutoload.php');
        $this->mail = new \PHPMailer();
        $this->mail->IsSMTP();
        $this->mail->CharSet   = 'UTF-8';
        $this->mail->Host      = Settings::mailsender('host');
        $this->mail->SMTPDebug = 0;
        $this->mail->SMTPAuth  = Settings::mailsender('enable_security');
        $this->mail->Port      = Settings::mailsender('port');
        $this->mail->Username  = Settings::mailsender('username');
        $this->mail->Password  = Settings::mailsender('password');
        if (Settings::mailsender('enable_security')) { $this->mail->SMTPSecure = 'ssl'; }
        $this->mail->From = Settings::mailsender('from_address');
        $this->mail->FromName = Settings::mailsender('from_name');
    }

    public static function notifyAdmin($subject, $message) {
        // We assume mailsender has not be configured if the host is not set.
        if (empty(Settings::mailsender('host'))) { return; }

        $mailsender = new MailSender();
        $mailsender->mail->addAddress(Settings::mailsender('admin_address'));
        $mailsender->mail->Subject = $subject;
        $mailsender->mail->Body = $message;

        if(!$mailsender->mail->send()) {
        } else {
        }
    }
}
