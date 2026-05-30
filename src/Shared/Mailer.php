<?php

namespace Shared;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Thin wrapper around PHPMailer for sending transactional HTML emails.
 * SMTP credentials are read from environment variables at instantiation.
 *
 * @package Shared
 */
class Mailer
{
    /** @var PHPMailer Configured PHPMailer instance */
    private PHPMailer $mailer;

    /**
     * Configures PHPMailer with SMTP settings from the environment.
     */
    public function __construct()
    {
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

    /**
     * Sends an HTML email to a single recipient.
     * Errors are silently logged to avoid exposing SMTP details to the user.
     *
     * @param string $toEmail
     * @param string $toName
     * @param string $subject
     * @param string $htmlBody
     * @return bool True on success, false on failure
     */
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
            /* Erreur SMTP loguée sans être propagée */
            error_log('[Mailer] ' . $e->getMessage());
            return false;
        }
    }
}