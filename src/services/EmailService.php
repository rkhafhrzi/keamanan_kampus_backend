<?php


require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);

        
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.gmail.com';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'akun@gmail.com';
        $this->mailer->Password = 'password-aplikasi';
        $this->mailer->SMTPSecure = 'tls';
        $this->mailer->Port = 587;
    }

    public function kirim($to, $subject, $body)
    {
        try {
            $this->mailer->setFrom('akun@gmail.com', 'Keamanan Kampus');
            $this->mailer->addAddress($to);
            $this->mailer->isHTML(true);

            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;

            return $this->mailer->send();
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
