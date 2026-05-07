<?php
namespace Shared;

use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    private PHPMailer $mailer;

    public function __construct()
    {
        $env = \Models\Database::class . '::parseEnvVar';
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host       = \Models\Database::parseEnvVar('SMTP_HOST')     ?: 'localhost';
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = \Models\Database::parseEnvVar('SMTP_USERNAME') ?: '';
        $this->mailer->Password   = \Models\Database::parseEnvVar('SMTP_PASSWORD') ?: '';
        $this->mailer->SMTPSecure = \Models\Database::parseEnvVar('SMTP_SECURE')   ?: 'tls';
        $this->mailer->Port       = (int)(\Models\Database::parseEnvVar('SMTP_PORT') ?: 587);
        $this->mailer->CharSet    = 'UTF-8';
        $this->mailer->setFrom(
            \Models\Database::parseEnvVar('FROM_EMAIL') ?: 'contact@royalautos.fr',
            \Models\Database::parseEnvVar('FROM_NAME')  ?: 'Royal Autos Montauban'
        );
    }

    public function send(string $toEmail, string $toName, string $subject, string $htmlBody): bool
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($toEmail, $toName);
            $this->mailer->Subject = $subject;
            $this->mailer->isHTML(true);
            $this->mailer->Body    = $htmlBody;
            $this->mailer->send();
            return true;
        } catch (\Exception $e) {
            error_log('[Mailer] ' . $e->getMessage());
            return false;
        }
    }
}
