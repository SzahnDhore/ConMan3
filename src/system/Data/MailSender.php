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

    public static function notifyUserPaymentConfirmed($email) {
        // We assume mailsender has not be configured if the host is not set.
        if (empty(Settings::mailsender('host'))) { return; }

        $mailsender = new MailSender();
        $mailsender->mail->addAddress($email);
        $mailsender->mail->Subject = 'WSK 2015 - betalning';
        $mailsender->mail->Body = 'Hej, din betalning för WSK 2015 är registrerad.';

        if(!$mailsender->mail->send()) {
        } else {
        }
    }

    public static function notifyUserPaymentDismissed($email) {
        // We assume mailsender has not be configured if the host is not set.
        if (empty(Settings::mailsender('host'))) { return; }

        $mailsender = new MailSender();
        $mailsender->mail->addAddress($email);
        $mailsender->mail->Subject = 'WSK 2015 - betalning';
        $mailsender->mail->Body = 'Hej, din betalning för WSK 2015 är har inte blivit godkänd.';

        if(!$mailsender->mail->send()) {
        } else {
        }
    }

    public static function notifyUserPersonalDetailsDismissed($email) {
        // We assume mailsender has not be configured if the host is not set.
        if (empty(Settings::mailsender('host'))) { return; }

        $mailsender = new MailSender();
        $mailsender->mail->addAddress($email);
        $mailsender->mail->Subject = 'WSK 2015 - personuppgifter';
        $mailsender->mail->Body = 'Hej, dina nya personuppgifter för WSK 2015 är har inte blivit godkända. Vänligen försök igen.';

        if(!$mailsender->mail->send()) {
        } else {
        }
    }

    public static function notifyUserPersonalDetailsConfirmed($email) {
        // We assume mailsender has not be configured if the host is not set.
        if (empty(Settings::mailsender('host'))) { return; }

        $mailsender = new MailSender();
        $mailsender->mail->addAddress($email);
        $mailsender->mail->Subject = 'WSK 2015 - personuppgifter';
        $mailsender->mail->Body = 'Hej, dina nya personuppgifter för WSK 2015 är har blivit godkända.';

        if(!$mailsender->mail->send()) {
        } else {
        }
    }
}
