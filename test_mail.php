<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

$transport = Transport::fromDsn('smtp://localhost:1025');
$mailer = new Mailer($transport);

$email = (new Email())
    ->from('kbartholomot@gmail.com')
    ->to('ton.email@exemple.com')
    ->subject('Test MailHog')
    ->text('Ceci est un test d’envoi via MailHog.');

try {
    $mailer->send($email);
    echo "Email envoyé avec succès.\n";
} catch (Exception $e) {
    echo "Erreur lors de l'envoi : " . $e->getMessage() . "\n";
}
